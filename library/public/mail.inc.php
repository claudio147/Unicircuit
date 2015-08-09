<?php

require_once('../class.phpmailer.php');

$mail             = new PHPMailer(); // Erstellen eines Objektes PHPMailer
$mail->IsSMTP();
$mail->Host = 'lsgrafik@gmail.com'; // SMTP Server
$mail->SMTPDebug  = 2; // Aktivierung Debug Informationen
$mail->SMTPAuth   = true;  // SMTP Authentifierzung wird benötigt bei gmail Servern
$mail->SMTPSecure = "tls"; // Server präfix
$mail->Host       = "smtp.gmail.com"; // SMTP Server Adresse
$mail->Port       = 587; // Gmail SMTP Server Port

$mail->Username   = "lsgrafik@gmail.com"; // Gmail username
$mail->Password   = "yourpassword";  // Gmail password

$mail->SetFrom('lsgrafik@gmail.com', 'Archconsulting'); // Absenderadresse



// prueft Mail Format
function checkMailFormat($email) {
  if (preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+\.([a-zA-Z]{2,4})$/", $email)) {
    return true;
  } else {
  return false;
  }
    
}






function createRegMail ($mail, $em, $fn ,$ln, $to) {
    //Subject des E-Mails
    $mail->Subject ='Registrationsfreischaltung auf Unicircuit';
    $mail->AddAdress($em, $fn.' '.$ln);      
      // Nachricht zusammenbauen als HTML Dokument
      $mail->MsgHTML = ("
	<html><head>
	<title>Anmledung bei Archconsulting Unicircuit</title>
	</head><body><p>Hallo $fn $ln</p>
	<p>Sie haben sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer 
    registiert. Um die Registration abzuschliessen, klicken Sie bitte auf 
    folgenden Link: <br />
    <a href=\"http://palmers.dynathome.net:8024/diplomarbeit/".
    "platform/public/php/verification.php?regcode=$to\">Registration abschliessen</a>".
    "</p><p>Es gr&uuml;sst das Team von Archconsulting</p></body></html>");

      // Mail an Benutzer/in senden. 
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
        echo "Message sent!";

}
}

function createArchRegMail($mail, $fn, $ln, $em) {
    $mail ->Subject = 'Registrierung auf Unicircuit';
    $mail->AddAdress($em, $fn.' '.$ln);
      // Nachricht zusammenbauen
      $mail->MsgHTML = "
	<html><head>
	<title>Anmledung bei Archconsulting Unicircuit</title>
	</head><body><p>Hallo $fn $ln</p>
	<p>Sie haben sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer 
    registiert. Nach erfolgreicher Prüfung erhalten Sie ihr aktivierungs Mail.</body></html>";

      // Mail an Benutzer/in senden. 
      if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
        echo "Message sent!";

}
}