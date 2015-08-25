<?php
/*
 * Library mit allen Funktionen für E-Mail Versand und Überprüfung
 */


//Einbindung Librarys
require_once('PHPMailerAutoload.php');

require_once('class.phpmailer.php');


require_once('PHPMailerAutoload.php');


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


// Erstellt und versendet Kontaktmail von Plattform
function sendMailtoArch($emArch, $emCust, $fnCust, $lnCust, $subject, $message, $projectNr, $projectName) {
    global $mail;
    $mail ->Subject = $projectNr.' '.$projectName.': '.$subject;
    $address = $emArch;
    $mail->AddAddress($address);
      // Nachricht zusammenbauen
      $mail->MsgHTML('
	<html>
        <head>
        <meta charset="UTF-8">
	<title>Kontaktanfrage über Unicircuit-Plattform</title>
	</head>
        <body>
        <p><strong>Projektnummer:</strong><br/>'.$projectNr.'</p>
        <p><strong>Projekt:</strong><br/>'.$projectName.'</p>
        <p><strong>Absender:</strong><br/>'.$fnCust.' '.$lnCust.'</p>
        <p><strong>Antworten an:</strong><br/>'.$emCust.'</p>
	<p><strong>Nachricht:</strong><br/>'.$message.'</p>
        <br/><br/>
        <p><i>Nachricht gesendet über Archconsulting - Unicircuit</i></p>
    </body>
    </html>');

      // Mail an Benutzer/in senden. 
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
        } else {
        return true;
        }
}



