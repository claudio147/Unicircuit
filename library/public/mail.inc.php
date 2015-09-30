<?php
/*
*   Funktionsbibliothek
*   «mail.inc.php / Regelt den gesamten Mailversand der Plattform und des Onepagers»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/


//Einbindung Librarys
require_once('PHPMailerAutoload.php');
require_once('class.phpmailer.php');


$mail = new PHPMailer(); // Erstellen eines Objektes PHPMailer
$mail->IsSMTP();
//$mail->Host = 'smtp.gmail.com'; // SMTP Server
// $mail->SMTPDebug  = 2; // Aktivierung Debug Informationen
$mail->SMTPAuth   = true;  // SMTP Authentifierzung wird benötigt bei gmail Servern
$mail->SMTPSecure = "tls"; // Server präfix
$mail->Host       = "smtp.gmail.com"; // SMTP Server Adresse
$mail->Port       = 587; // Gmail SMTP Server Port

$mail->CharSet = 'utf-8';

$mail->Username   = "archconsulting2@gmail.com"; // Gmail username
$mail->Password   = "arch!consulting";  // Gmail password


$mail->SetFrom('archconsulting2@gmail.com', 'Archconsulting'); // Absenderadresse
$mail->addReplyTo('archconsulting2@gmail.com','Archconsulting');

//Mail 2 ist für Kontaktformular des Onepagers (ohne Add Reply Archconsulting UND damit beide mails korrekt versendet werden!)
$mail2 = new PHPMailer(); // Erstellen eines Objektes PHPMailer
$mail2->IsSMTP();
//$mail->Host = 'smtp.gmail.com'; // SMTP Server
// $mail->SMTPDebug  = 2; // Aktivierung Debug Informationen
$mail2->SMTPAuth   = true;  // SMTP Authentifierzung wird benötigt bei gmail Servern
$mail2->SMTPSecure = "tls"; // Server präfix
$mail2->Host       = "smtp.gmail.com"; // SMTP Server Adresse
$mail2->Port       = 587; // Gmail SMTP Server Port

$mail2->CharSet = 'utf-8';

$mail2->Username   = "archconsulting2@gmail.com"; // Gmail username
$mail2->Password   = "arch!consulting";  // Gmail password


$mail2->SetFrom('archconsulting2@gmail.com', 'Archconsulting'); // Absenderadresse



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
	</head><body><p>Guten Tag $fn $ln</p>
	<p>Sie haben sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer
    registiert. Um die Registration abzuschliessen, klicken Sie bitte auf
    folgenden Link: <br />
    <a href=\"http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/verification.php?regcode=$to\">Registration abschliessen</a>".
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
	<title>Anmeldung bei Archconsulting Unicircuit</title>
	</head><body><p>Guten Tag $fn $ln</p>
	<p>Vielen Dank das Sie sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer
        registiert haben.<br/>Nach erfolgreicher Prüfung erhalten Sie ihr Aktivierungs-Mail.<br/>
        Falls Sie noch Fragen haben wenden Sie sich an unser Team unter info@archconsulting.ch<br/><br/>
        Freundliche Grüsse<br/>Ihr Unicircuit Team</body></html>");

      // Mail an Benutzer/in senden.
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        } else {

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

// Erstellt und versendet Aktivierungsmail für Bauherr
function createBauhMail($bhEmail, $BhFn, $BhLn, $BhPw, $title) {
    global $mail;
    $mail ->Subject = 'Ihr Projekt wurde wurde erstellt';
    $address = $bhEmail;
    $mail ->addAddress($address, $BhFn.' '.$BhLn);
        //Nachricht erstellen
    $mail->msgHTML("
            <html><head>
            <title> Projekt wurde auf Unicircuit eröffnet</title>
            </head>
            <body>
            <p> Guten Tag $BhFn $BhLn</p>
            <p> Ihr Architekt hat Ihr Projekt $title auf der Plattform Unicircuit eröffnet. </p>
            <p> Sie können sich unter <a href=\"http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/login.php\">Login</a> direkt einloggen</p>
            <p> Ihr Passwort für den erstmaligen Login lautet: <b>$BhPw</b> Bitte ändern Sie dieses in ihren Einstellungen nach dem Login </p>
               <p> Viel Spass wünscht <b> Archconsulting</b> </p>");
    
    // Mail an Benutzer/in senden.
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
        } else {
        return true;
        }
    
}

//Mail für Paswort Reset des Bauherren
function createBauhResetPw($bhEmail, $bhFn, $bhLn, $BhPw, $title) {
    global $mail;
    $mail ->Subject = 'Passwort Reset ihres Unicircuit Accounts';
    $address = $bhEmail;
    $mail ->addAddress($address, $bhFn.' '.$bhLn);
        //Nachricht erstellen
    $mail->msgHTML("
            <html><head>
            <title> Passwort Rücksetzunh</title>
            </head>
            <body>
            <p> Guten Tag $bhFn $bhLn</p>
            <p> Ihr Architekt hat Ihr Passwort für das Projekt $title zurückgesetzt. </p>
            <p> Sie können sich unter <a href=\"http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/login.php\">Login</a> direkt einloggen mit dem neuen Passwort</p>
            <p> Ihr Passwort lautet: <b>$BhPw</b> Bitte ändern Sie dieses in ihren Einstellungen nach dem Login </p>
               <p> Viel Spass wünscht <b> Archconsulting</b> </p>");
    
    // Mail an Benutzer/in senden.
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
        } else {
        return true;
        }
}

//Mail für Paswort Reset eines Users
function createResetPw($email, $fn, $ln, $newPw) {
    global $mail;
    $mail ->Subject = 'Passwort Reset ihres Unicircuit Accounts';
    $address = $email;
    $mail ->addAddress($address, $fn.' '.$ln);
        //Nachricht erstellen
    $mail->msgHTML("
            <html><head>
            <title> Passwort Rücksetzunh</title>
            </head>
            <body>
            <p> Guten Tag $Fn $Ln</p>
            <p> Ihr Passwort für <b>UNICIRCUIT </b> wurde zurückgesetzt,. </p>
            <p> Sie können sich unter <a href=\"http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/login.php\">Login</a> direkt einloggen mit dem neuen Passwort</p>
            <p> Ihr Passwort lautet: <b>$newPw</b> Bitte ändern Sie dieses in ihren Einstellungen nach dem Login </p>
               <p> Viel Spass wünscht <b> Archconsulting</b> </p>");
    
    // Mail an Benutzer/in senden.
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
        } else {
        return true;
        }
}



/***********************************************
 * MAILS ONEPAGER
 ***********************************************/
// Erstellt und versendet Mail an Kunden als Auto-response
function sendMailCustomer($na, $em) {
    global $mail;
    $mail ->Subject = 'Anfrage Unicircuit';
    $mail->AddAddress($em, $na);
      // Nachricht zusammenbauen
      $mail->MsgHTML("
	<html>
        <head>
            <title>Kontaktanfrage Unicircuit</title>
            <style>
                font-family: 'Arial', sans-serif;
            </style>
        </head>

        <body>

        <h3>Kontaktanfrage Unicircuit</h3>
        <br />
        <p>Guten Tag ".$na."</p>
        <p>Besten Dank für Ihre Kontaktanfrage.</p>
        <p>Wir werden uns schnellstmöglich mit Ihnen in Verbindung setzen.<p>
        <br />
        <p>Freundliche Grüsse</p>
        <p>Ihr Unicircuit-Team</p>

        </body>
        </html>");

      // Mail an Benutzer/in senden.
      if(!$mail->Send()) {
          return false;
        } else {
            return true;
        }
}

//Erstellt Mail an Archconsulting mit Nachricht aus Kontaktformular
function sendMailArchcon($na2, $em2, $me, $emCust){
    global $mail2;
    $mail2 ->Subject = 'Anfrage Unicircuit';
    $mail2->AddAddress($em2, 'Archconsulting');
    $mail2->addReplyTo($emCust, $na2);
      // Nachricht zusammenbauen
      $mail2->MsgHTML("
	<html>
        <head>
            <title>Kontaktanfrage Unicircuit</title>
            <style>
                font-family: 'Arial', sans-serif;
            </style>
        </head>

        <body>

        <h3>Kontaktanfrage Unicircuit</h3>
        <br />
        <p><b>Name:</b> ".$na2." </p>
        <p><b>Email:</b> ".$emCust." </p>
        <br />		 
        <p><b>Nachricht:</b><br />".$me."</p>

        </body>
        </html>");

      // Mail an Benutzer/in senden.
      if(!$mail2->Send()) {
          return false;
        } else {
            return true;
        }
}