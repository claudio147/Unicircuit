<?php
/*
*   Unicircuit Plattform
*   «Login (Weiterleitung von Usern)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/security.inc.php');
require_once ('../../../library/public/mail.inc.php');

$link = connectDB();
//Zerstörung Cookie
if (isset($_COOKIE[session_name()])) {
  session_start(); // Reinitialisiere Session
  setcookie(session_name(), '', time() - 42000, '/');  // Setze Cookie ungültig
  session_destroy(); // Zerstöre Session
  header('Location: login.php'); // Spring zurück auf login.php
}

if (isset($_POST['submit'])) {
    // Es wird untersucht, ob in der Datenbank ein entsprechender User eingetragen ist, ansonsten wird eine Fehlermeldung 
    // ausgegeben
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Hole die Benutzer-ID aus der Tabelle user
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $pw = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        $sql = selectUser($email, $pw);
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);

        //Überprüfung auf Sperrung
        if($row['Active'] !=3 && !empty($row['IdUser'])) {
            $response= 5;
        }
        // Konnte eine ID aufgrund der Login-Daten ermittelt werden?
        else if(!empty($row['IdUser'])) {
            // Hier wird die Session das erste Mal initialisiert
            session_start();
            // Speichere den PK des Users in der Session
            $_SESSION['IdUser'] = $row['IdUser'];
            // Speicher den Username (email) in der Session
            $_SESSION['UserName'] = $email;
            //Speichere den entsprechenden Usertype in der Session
            $_SESSION['UserType'] = $row['Fk_IdUserType'];
            //Vorbereitung für Speicherung der ProjektId
            $_SESSION['IdProject'] = '';

            // Beschaffung User Daten
            $date = date("Y:m:d");
            $time = date("H:i:s");
            $sessionId = session_id();
            $browserTyp = substr($_SERVER['HTTP_USER_AGENT'], 0, 250);
            $datensatz = $row['IdUser'];

            // Es wird ein Update durchgeführt, da die Benutzdaten stimmen
            $sql = updateUser($date, $time, $sessionId, $browserTyp, $datensatz);
            $result = mysqli_query($link, $sql);


            // Anhand des UserTyps auf die entsprechende Seite weiterleinten.
            //1= Archconsulting //2= Architekt //3= Bauherr
            switch($row['Fk_IdUserType']) {
                case 1:
                    header('Location: ../../../rms/public/php/index.php');
                    break;
                case 2:
                    header('Location: projektverwaltung.php');
                    break;
                case 3:
                    //Holt die entsprechende ProjektId des Bauherren
                    $sql = getProjectId($datensatz);
                    $result = mysqli_query($link, $sql);
                    $row = mysqli_fetch_array($result);
                    //Fügt die Projekt ID der Session hinzu
                    $_SESSION['IdProject'] = $row['IdProject'];

                    if(!empty($row['IdProject'])) {
                    header('Location: index.php');
                    }
                    break;
            }
        } else {
            $response=1;
        }  
    }
}


//Funktion wenn User auf Passwort vergessen klickt
if ($_GET['forgotten'] == 1) {
    $forgotten = true;       
}

//Passwort Renew eines Users
if(isset($_POST['pwRenew'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $sql = getDetailsByMail($email);
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $fn = $row['Firstname'];
    $ln = $row['Lastname'];
        if($row != 0 ) {
            $newPw = generatePassword();
            $newPwHash = hash('sha256', $newPw);
            $sql = updatePassword($newPwHash, $email);
            $result = mysqli_query($link, $sql);
            $sendMail =  createResetPw($email, $fn, $ln, $newPw);
            if($sendMail == true) {
                $response = 2;
            } else {
                $response = 5;
            }
        }   
}
//Ausgabe für Aktivierungsmeldung
if(isset($_GET['reg'])) {
    //Setzt Status für Eventhandling
    $reg = filter_input(INPUT_GET, 'reg', FILTER_SANITIZE_NUMBER_INT);
    if($reg == 1) {
        $response = 3;
    } else {
        $response = 4;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title>Login Unicircuit</title>
        
        <!--CSS 3rd Party-->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <style>
            body{
                background-color: #373d42;
            }
            
            .login-container{
                background-color: #f0f0f0;
                
                padding: 30px;
                position: relative;
                -webkit-box-shadow: 10px 10px 20px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 10px 10px 20px 0px rgba(0,0,0,0.75);
                box-shadow: 10px 10px 20px 0px rgba(0,0,0,0.75);
            }
            #signin-btn{
                position: absolute;
                bottom: 0;
                left: 0;
                padding: 30px;
            }
            #signin-btn>input{
                margin-right: 30px;
            }
            #signin-btn>input:hover{
                background-color: #9fcd35;
            }
            .email{
                margin-bottom: 10px;
            }
            .login-title{
                margin-top: 0px;
                margin-bottom: 20px;
                font-size: 30px;
            }
            .brand{
                letter-spacing: 1px;
                font-size: 48px;
                margin-top: 60px;
                margin-bottom: 60px;
                text-align: center;
                text-transform: uppercase;
                font-weight: bold;
                color: #9fcd35;
            }
            .alert>i{
                margin-right: 10px;
            }
        </style>
    </head>
    <body>
        
        <div class="container">
            <div class="row">
                <?php
                    //Ausgabe Login normal
                    if(!isset($forgotten)) {
                        echo'<form action="login.php" method="post">';
                        echo'<h1 class="brand">Unicircuit</h1>';
                        echo'<div class="col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4 login-container">';
                        echo'<h2 class="login-title">Login</h2>';
                        echo'<label for="1">Email</label>';
                        echo'<input id="1" type="email" name="email" class="form-control email"/>';
                        echo' <label for="2">Passwort</label>';
                        echo'<input id="2" type="password" name="password" class="form-control"/>';
                        echo'<div id="signin-btn"><input type="submit" value="Anmelden" name="submit" class="btn btn-default"/><a href="login.php?forgotten=1">Passwort vergessen?</a></div>';
                        echo'</div>';
                        echo'</form>';  
                    //Ausgabe Passwort vergessen Funktion falls flag(forgotten) auf true ist.
                    } else if($forgotten == true) {
                        echo'<form action="login.php" method="post">';
                        echo'<h1 class="brand">Unicircuit</h1>';
                        echo'<div class="col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4 login-container">';
                        echo'<h2 class="login-title">Passwort Vergessen</h2>';
                        echo'<label for="1">Email</label>';
                        echo'<input id="1" type="email" name="email" class="form-control email"/>';
                        echo'<div id="signin-btn"><input type="submit" value="Passwort zurücksetzen" name="pwRenew" class="btn btn-default"/></div>';
                        echo'</div>';
                        echo'</form>';  
                    }
                 ?>
            </div>
            
            <?php
                //Login Event-Handling
                $stat = checkLogin($response);
                echo $stat;
            ?>
        </div>
        
        <!--JS 3rd Party-->
        <script src="../js/jquery-1.11.1.min.js" type="text/javascript"></script>
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <script>
            function resizer(){
            var cw = $('.login-container').width();
            cw=cw+30;
            $('.login-container').css({'height':cw+'px'});
            }
            
            window.onresize= resizer;
            window.onload= resizer;
        </script>
    </body>
</html>