<?php

/* 
 * Programmpunkt 2.0 , Registrierung eines neuen Kunden(Architekten)
 * 
 * 
 */

//Einbindung Librarys

require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');

//check mail
checkMailFormat($email);

/*
 * Überprüfung ob Submit schon geklickt wurde.
 * Fehler Abfrage
 * Security: Bereinigung von möglichen falsch Eingaben.
 */
if (isset($_POST['submit'])) {
    
    
  $fn = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
  $ln = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
  $co = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
  $a1 = filter_input(INPUT_POST, 'adressline1', FILTER_SANITIZE_STRING);
  $a2 = filter_input(INPUT_POST, 'adressline2', FILTER_SANITIZE_STRING);
  $zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_NUMBER_INT);
  $ci = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
  $cn = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
  $pn = filter_input(INPUT_POST, 'PhoneNumber', FILTER_SANITIZE_STRING);
  $mn = filter_input(INPUT_POST, 'MobileNumber', FILTER_SANITIZE_STRING);
  $em = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $p1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
  $p2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);

  // Fehler im Eingabefeld?
  if (empty($fn) || strlen($fn) < 2) {
    $errorFN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($ln) || strlen($ln) < 2) {
    $errorLN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($co) || strlen($co) < 2) {
    $errorCO = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($a1) || strlen($a1) < 5) {
    $errorA1 = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($zip) || strlen($zip) < 4) {
    $errorZIP = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($ci) || strlen($ci) < 4) {
    $errorCi = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($cn) || strlen($cn) < 2) {
    $errorCn = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($pn) || strlen($pn) < 2) {
    $errorPN = true;
    $error = true;
  }

  // Mailadresse korrekt?
  if (!checkMailFormat($em)) {
    $errorEM = true;
    $error = true;
  }

  // Passworte identisch
  if (($p1 != $p2) || (strlen($p1) < 8)) {
    $errorPW = true;
    $error = true;
  }

  // Prüfen, ob ein Fehler im Eingabeformular seitens Benutzer/in erkannt wurde
  if (!isset($error)) {
    // Wenn kein Fehler, dann zuerst prüfen, ob eventuell Benutzer/in schon in
    // der Datenbank registiert ist. Dabei können wir uns auf die E-Mail
    // Adresse beschränken, da diese ja weltweit eindeutig ist
    // Verbinde mit der Datenbank
     $link = connectDB();
    // Hole aus der Datebank ID von Benutzer/in, welche die eingegebene 
    // E-Mail Adresse besitzt
    $sql2 = "SELECT * FROM User WHERE Email='$em'";
    
    $result = mysqli_query($link, $sql2);
    // Zähle alle Datensätze, welcher das SQL-Statement zurück gegeben hat
    
    $anzahl = mysqli_num_rows($result);
    

    // Wenn die Anzahl 1 ist, dann ist die Person nicht in der Datenbank 
    // registiert
    if ($anzahl < 1) {
      // Beschaffe das aktuelle Datum (siehe php.net)
      $da = date('Y-m-d');
      // Beschaffe die aktuelle Uhrzeit 
      $ti = date('H:i:s');
      // Beschaffe Anzahl Millisekunden seit 1.1.1970
      $tm = time();
      // Beschaffe IP-Adresse des Clients 
      $ra = $_SERVER['REMOTE_ADDR'];
      // Bestime Salt
      $sa = 'FB8w?xn!Ju.j+Dk“YcG1EHpPFWc_!"*';
      // Füge alle Daten zusammen und genereriere einen HASH-Wert
      $to = sha1($fn . $ln . $em . $da . $ti . $ra . $sa);
      //  Passwort Verschlüsseln
      $p1 = hash('sha256', $p1);
      // SQL-Statement zusammensetzen, um Datensatz in DB zu speichern
      $sql = createArchitect($link, $fn, $ln, $co, $zip, $ci, $cn, $pn, $mn, $em, $to, $p1, $da, $ti, $a1, $a2);
      // Anfrage an Datenbank senden
     
     
      $status = mysqli_query($link, $sql);
    echo $sql;
    if($status == true){
        createArchRegMail($fn, $ln, $em);
        $response=0;
    }else{
        $response=1;
    }
    mysqli_close($link);
  }else{
      $response=2;
  }
}else{
  $response=3;
}
}
//Registrationsformular
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title>Registration Unicircuit</title>
        
        <!-- CSS 3rd Party -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <style>
            body{
                background-color: #373d42;
            }

            .registration-container{
                background-color: #f0f0f0;
                margin-bottom: 50px;
                padding: 30px;
                position: relative;
                -webkit-box-shadow: 10px 10px 20px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 10px 10px 20px 0px rgba(0,0,0,0.75);
                box-shadow: 10px 10px 20px 0px rgba(0,0,0,0.75);
            }

            #btn-reg:hover{
                background-color: #9fcd35;
            }
            .email{
                margin-bottom: 10px;
            }
            .registration-title{
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
                <form action="registration.php" method="post">
                    <h1 class="brand">Unicircuit</h1>
                    <?php
                    if(isset($response)){
                            $response;
                            if($response==0){
                                echo'<br/><div class="alert alert-success col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Besten Dank für Ihre Registrierung! Wir benachrichtigen Sie bei Freischaltung.</div>';
                            }else if($response==1){
                                echo'<br/><div class="alert alert-danger col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Registration fehlgeschlagen!</div>';
                            }else if($response==2){
                                echo'<br/><div class="alert alert-danger col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Sie sind bereits registriert. Bitte warten Sie auf Ihre Freigabe.</div>';
                            }else if($response==3){
                                echo'<br/><div class="alert alert-danger col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Registration fehlgeschlagen. Bitte eingaben überprüfen.</div>';
                            }
                        }

                    ?>
                    <div class="col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3 registration-container">
                        <h2 class="registration-title">Registration</h2>

                        <label for="1">Vorname</label>
                        <input id="1" class="form-control" type="text" name="firstname" value="<?php echo $fn; ?>">
                        <label for="2">Nachname</label>
                        <input id="2" class="form-control" type="text" name="lastname" value="<?php echo $ln; ?>">
                        <label for="3">Firma</label>
                        <input id="3" class="form-control" type="text" name="company" value="<?php echo $co; ?>">
                        <label for="4">Strasse und Nr.</label>
                        <input id="4" class="form-control" type="text" name="adressline1" value="<?php echo $a1; ?>">
                        <label for="5">Adresszusatz</label>
                        <input id="5" class="form-control" type="text" name="adressline2" value="<?php echo $a2; ?>">
                        <div class="row">
                          <div class="col-xs-2">
                            <label for="6">PLZ</label>
                            <input id="6" type="text" name="zip" value="<?php echo $zip; ?>" class="form-control">
                          </div>
                          <div class="col-xs-10">
                            <label for="7">Ort</label>
                            <input id="7" type="text" name="city" value="<?php echo $ci; ?>" class="form-control">
                          </div>
                        </div>
                        <label for="8">Land</label>
                        <select id="8" name="country" class="form-control">
                            <?php 
                            $link=connectDB();
                            //Liste mit Ländern aus der Datenbank
                            $sql = "SELECT Country FROM Countries";
                            $resultC = mysqli_query($link, $sql);
                                while($rowC= mysqli_fetch_array($resultC)){
                                echo '<option value="'.$rowC['Country'].'">'.$rowC['Country'].'</option>';
                            }?>
                        </select>
                        <label for="9">Email</label>
                        <input id="9" class="form-control" type="text" name="email" value="<?php echo $em; ?>">
                        <label for="10">Telefon</label>
                        <input id="10" class="form-control" type="text" name="PhoneNumber" value="<?php echo $pn; ?>">
                        <label for="11">Mobil</label>
                        <input id="11" class="form-control" type="text" name="MobileNumber" value="<?php echo $mn; ?>">
                        <label for="12">Passwort</label>
                        <input id="12" class="form-control" type="password" name="password1" id="p1" value="<?php echo $p1; ?>">
                        <label for="13">Passwort wiederholen</label>
                        <input id="13" class="form-control" type="password" name="password2" id="p2" value="<?php echo $p2; ?>">
                        <br/><input id="btn-reg" class="btn btn-default" type="submit" name="submit">




                    </div>
                </form>    
            </div>
            
        </div>

        <!-- JS 3rd Party -->
        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
