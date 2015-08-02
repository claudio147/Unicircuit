<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

// Mail funktion
function sendMail($empfaenger, $absender, $betreff, $message) {
  $header = 'MIME-Version: 1.0' . "\r\n";
  $header.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $header.= 'To: ' . $empfaenger . "\r\n";
  $header.= 'From: ' . $absender . "\r \n";
  $header.= 'X-Mailer: PHP/' . phpversion() . "\r\n";

  mail($empfaenger, $betreff, $message, $header);
}

function createMail ($em, $fn ,$ln) {
          $betreff = 'Registrationsfreischaltung auf Unicircuit';
      // Nachricht zusammenbauen
      $nachricht = "
	<html><head>
	<title>Anmledung bei Archconsulting Unicircuit</title>
	</head><body><p>Hallo $fn $ln</p>
	<p>Sie haben sich auf der Plattform <i>Unicircuit</i> als neuer Benutzer 
    registiert. Um die Registration abzuschliessen, klicken Sie bitte auf 
    folgenden Link: <br />
    <a href=\"http://palmers.dynathome.net:8045/personenverwaltung/".
    "registration/verification.php?regcode=$to\">Registration abschliessen</a>".
    "</p><p>Es gr&uuml;sst das Team von Archconsulting</p></body></html>";

      // Mail an Benutzer/in senden. 
      sendMail($em, 'noreply@palmers.dynathome.net', 'Registration', $nachricht);
}


/*
 * Herstellen der Datenbankverbindung und
 * Abfrage Datenbank nach allen Userdaten
 */
//Datenbankverbindung
$link = connectDB();

$sql = 'SELECT Firstname, Lastname, Company, ZIP, Country, PhoneNumber, 
            MobileNumber, Email, RegCode, LastLoginDate, LastLoginTime, IdUser FROM User';

$result = mysqli_query($link, $sql);





//Erstellen der Anzeige mit den Usern
echo '<table border="1" width="600">';

 while ($row = mysqli_fetch_array($result)) {
     echo '<tr>';
      echo '<td>' . $row['Firstname'] . '</td>';
      echo '<td>' . $row['Lastname'] . '</td>';
      echo '<td>' . $row['Company'] . '</td>';
      echo '<td>' . $row['ZIP'] . '</td>';
      echo '<td>' . $row['Country'] . '</td>';
      echo '<td>' . $row['PhoneNumber'] . '</td>';
      echo '<td>' . $row['MobileNumber'] . '</td>';
      echo '<td>' . $row['Email'] . '</td>';
      
      //Ueberpruefung ob User bereits aktiviert ist und sich schon eingeloggt hat
      if ($row['RegCode'] == 1) {
        echo '<td>Bereits aktiviert.</td>';
      } else {
          
        echo '<td><a href="userverwaltung.php?id=' . $row['IdUser'] . '">aktivieren</a></td>';
        
      }
 }
 echo '</table>';
 
 
 /*
 * Überprüft ob ein Aktivierungsbutton geklickt wurde
 * sendet ein E-Mail mit dem Aktivierungslink an den user.
 */
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = 'SELECT Firstname, Lastname, Email FROM User WHERE IdUser = '. $id;

$result = mysqli_query($link, $sql);
$result2 = mysqli_fetch_array($result);

    echo $id ;
    $em = $result2['Email'];
    $fn = $result2['Firstname'];
    $ln = $result2['lastname'];
            
    echo $em ;
    createMail($em, $fn, $ln);
    
}
