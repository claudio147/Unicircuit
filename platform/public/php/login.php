<?php
/*
 *  Programmpunkt 1.0 Login / Login für Admin, Architekten und Bauherren
 */

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

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

    
    $link = connectDB();
    $sql = selectUser($email, $pw);
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    if($row['Active'] != 3 && !empty($row['IdUser'])) {
        $status=0;
        //echo '<p style="font-color:red; font-weight:bold">Sie sind noch nicht aktiviert oder sind gesperrt.</p>';
        
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
      
      // Beschaffen wir uns ein paar interessante Informationen
      $date = date("Y:m:d");
      $time = date("G:i:s");
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
              header('Location: ../../../rms/public/index.php');
              break;
          case 2:
              header('Location: projektverwaltung.php');
              break;
          case 3:
              //Holt die entsprechende ProjektId des Bauherren
              $sql = "SELECT IdProject FROM Project WHERE Fk_IdBauherr = $datensatz ";
              $result = mysqli_query($link, $sql);
              $row = mysqli_fetch_array($result);
              //echo $sql;
              //Fügt die Projekt ID der Session hinzu
              $_SESSION['IdProject'] = $row['IdProject'];
              
              if(!empty($row['IdProject'])) {
              header('Location: index.php');
              }
              break;
          
      }
      
    } else {
        $status=1;
      //echo '<p style="font-color:red; font-weight:bold">Falscher Benutzername oder Password!</p>';
    }  
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
        
        <!-- CSS 3rd Party -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
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
                <form action="login.php" method="post">
                    <h1 class="brand">Unicircuit</h1>
                    <div class="col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4 login-container">
                        <h2 class="login-title">Login</h2>
                        <label for="1">Email</label>
                        <input id="1" type="email" name="email" class="form-control email"/>
                        <label for="2">Passwort</label>
                        <input id="2" type="password" name="password" class="form-control"/>
                        <div id="signin-btn"><input type="submit" value="Anmelden" name="submit" class="btn btn-default"/><a href="">Passwort vergessen?</a></div>
                    </div>
                </form>    
            </div>
            <?php
            if(!empty($status)){
                    $status;
                    if($status==0){
                        echo'<br/><div class="alert alert-danger col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" role="alert"><i class="fa fa-exclamation-triangle"></i>Sie sind noch nicht aktiviert oder gesperrt.</div>';
                    }else if($status==1){
                        echo'<br/><div class="alert alert-danger col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" role="alert"><i class="fa fa-exclamation-triangle"></i>Falscher Benutzername oder Passwort!</div>';
                    }
                }

            ?>
        </div>
        
        <!-- JS 3rd Party -->
        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
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

