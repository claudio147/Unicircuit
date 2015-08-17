<?php
/*
 * Library mit allen Funktionen für E-Mail Versand und Überprüfung
 */

<<<<<<< HEAD
//Einbindung Librarys
require_once('PHPMailerAutoload.php');

require_once('class.phpmailer.php');
=======

require_once('PHPMailerAutoload.php');
>>>>>>> faaa301984cd87394cec8a63afcf3c7821bee676


$mail = new PHPMailer(); // Erstellen eines Objektes PHPMailer
$mail->IsSMTP();
//$mail->Host = 'smtp.gmail.com'; // SMTP Server
// $mail->SMTPDebug  = 2; // Aktivierung Debug Informationen
$mail->SMTPAuth   = true;  // SMTP Authentifierzung wird benötigt bei gmail Servern
$mail->SMTPSecure = "tls"; // Server präfix
$mail->Host       = "smtp.gmail.com"; // SMTP Server Adresse
$mail->Port       = 587; // Gmail SMTP Server Port

$mail->Username   = "archconsulting2@gmail.com"; // Gmail username
$mail->Password   = "arch!consulting";  // Gmail password

$mail->SetFrom('archconsulting2@gmail.com', 'Archconsulting'); // Absenderadresse
$mail->addReplyTo('archconsulting2@gmail.com','Archconsulting');



// prueft Mail Format
function checkMailFormat($email) {
  if (preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+\.([a-zA-Z]{2,4})$/", $email)) {
    return true;
  } else {
  return false;
  }
    
}




//Erstellt und versendet Aktivierungsmail
//Link steht auf Localhost! Muss noch geändert werden
function createRegMail ($em, $fn ,$ln, $to) {
    global $mail;
    //Subject des E-Mails
    $mail->Subject ='Registrationsfreischaltung auf Unicircuit';
    $address = $em;
    
    $mail->AddAddress($address, $fn.' '.$ln);   
      // Nachricht zusammenbauen als HTML Dokument
      $mail->MsgHTML("
	<html><head>
	<title>Anmledung bei Archconsulting Unicircuit</title>
	</head><body><p>Hallo $fn $ln</p>
	<p>Sie haben sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer 
    registiert. Um die Registration abzuschliessen, klicken Sie bitte auf 
    folgenden Link: <br />
    <a href=\"http://local-platform.int.ch/php/verification.php?regcode=$to\">Registration abschliessen</a>".
    "</p><p>Es gr&uuml;sst das Team von Archconsulting</p></body></html>");

      // Mail an Benutzer/in senden. 
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        echo 'Email:'.$em;
        
        
        } else {
        header('Location: userverwaltung.php');
        

}
}

// Erstellt und versendet Registrationsmail
function createArchRegMail($fn, $ln, $em) {
    global $mail;
    $mail ->Subject = 'Registrierung auf Unicircuit';
    $address = $em;
   $mail->AddAddress($address, $fn.' '.$ln);
      // Nachricht zusammenbauen
      $mail->MsgHTML("
	<html><head>
	<title>Anmledung bei Archconsulting Unicircuit</title>
	</head><body><p>Hallo $fn $ln</p>
	<p>Sie haben sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer 
    registiert. Nach erfolgreicher Prüfung erhalten Sie ihr aktivierungs Mail.</body></html>");

      // Mail an Benutzer/in senden. 
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
        echo "Message sent!";

}
}



/*
*Productsite Kontaktformular
*/

<<<<<<< HEAD
function createContactMailCustomer( $name, $em) {
=======
function MailCustomer($name, $email) {
>>>>>>> faaa301984cd87394cec8a63afcf3c7821bee676
    global $mail;
    $mail ->Subject = 'Kontaktanfrage Unicircuit';
    $mail->AddAdress($email, $name);
      // Nachricht zusammenbauen
      $mail->MsgHTML = '<html>
    <head>
        <title>Kontaktanfrage Unicircuit</title>
    </head>
     
    <body>
     
    <h3>Kontaktanfrage Unicircuit</h3>
    <br />
    <p>Sehr geehrter '.$name.'</p>
    <p>Besten Dank für Ihre Kontaktanfrage.</p>
    <p>Wir werden uns schnellstmöglich mit Ihnen in Verbindung setzen.<p>
    <br />
    <p>Freundliche Grüsse</p>
    <p>Ihr Unicircuit-Team</p>
     
    </body>
    </html>';

      // Mail an Benutzer/in senden. 
      if(!$mail->Send()) {
        return false;
        } else {
        return true;
}
}


function MailArchconsulting($name , $email2, $message) {
    global $mail;
    $mail ->Subject = 'Kontaktanfrage Unicircuit';
    $mail->AddAdress($email2, $name);
      // Nachricht zusammenbauen
      $mail->MsgHTML = '<html>
    <head>
        <title>Kontaktanfrage Unicircuit</title>
    </head>
     
    <body>
     
    <h3>Kontaktanfrage Unicircuit</h3>
    <br />
    <p><b>Name:</b> '.$name.' </p>
    <br />     
    <p><b>Nachricht:</b><br />'.$message.'</p>
     
    </body>
    </html>';

      // Mail an Benutzer/in senden. 
      if(!$mail->Send()) {
        return false;
        } else {
        return true;

}
}

