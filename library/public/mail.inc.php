<?php
// prueft Mail Format
function checkMailFormat($email) {
  if (preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+\.([a-zA-Z]{2,4})$/", $email)) {
    return true;
  } else {
  return false;
  }
    
}

// sendet Email ab.
function sendMail($empfaenger, $absender, $head, $message) {
  $header = 'MIME-Version: 1.0' . "\r\n";
  $header.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $header.= 'To: ' . $empfaenger . "\r\n";
  $header.= 'From: ' . $absender . "\r \n";
  $header.= 'X-Mailer: PHP/' . phpversion() . "\r\n";

  mail($empfaenger, $head, $message, $header);
}


function createRegMail ($em, $fn ,$ln, $to) {
          $head = 'Registrationsfreischaltung auf Unicircuit';
      // Nachricht zusammenbauen
      $message = "
	<html><head>
	<title>Anmledung bei Archconsulting Unicircuit</title>
	</head><body><p>Hallo $fn $ln</p>
	<p>Sie haben sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer 
    registiert. Um die Registration abzuschliessen, klicken Sie bitte auf 
    folgenden Link: <br />
    <a href=\"http://palmers.dynathome.net:8024/diplomarbeit/".
    "platform/public/php/verification.php?regcode=$to\">Registration abschliessen</a>".
    "</p><p>Es gr&uuml;sst das Team von Archconsulting</p></body></html>";

      // Mail an Benutzer/in senden. 
      sendMail($em, 'noreply@archconsulting.net', $head, $message);
}

function createArchRegMail($fn, $ln, $em) {
    $head = 'Registrierung auf Unicircuit';
      // Nachricht zusammenbauen
      $message = "
	<html><head>
	<title>Anmledung bei Archconsulting Unicircuit</title>
	</head><body><p>Hallo $fn $ln</p>
	<p>Sie haben sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer 
    registiert. Nach erfolgreicher Prüfung erhalten Sie ihr aktivierungs Mail.</body></html>";

      // Mail an Benutzer/in senden. 
      sendMail($em, 'noreply@archconsulting.net', $head, $message);
}