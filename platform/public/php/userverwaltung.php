<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');



/*
 * Herstellen der Datenbankverbindung und
 * Abfrage Datenbank nach allen Userdaten
 */
//Datenbankverbindung
$link = connectDB();

$sql = allUserData();

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
      $reg = $row['RegCode'];
      if ($reg === "1") {
        echo '<td>Bereits aktiviert.</td>';
        echo '<td>'.$reg.'</td>';
        
      } else {
          
        echo '<td><a href="userverwaltung.php?id=' . $row['IdUser'] . '">aktivieren</a></td>';
        echo '<td>'.$reg.'</td>';
        
      }
 }
 echo '</table>';
 
 
 /*
 * Überprüft ob ein Aktivierungsbutton geklickt wurde
 * sendet ein E-Mail mit dem Aktivierungslink an den user.
 */
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = userData($id);

$result = mysqli_query($link, $sql);
$data = mysqli_fetch_array($result);

    echo $id ;
    $em = $data['Email'];
    $fn = $data['Firstname'];
    $ln = $data['Lastname'];
    $to = $data['RegCode'];      
    echo $em ;
    createRegMail($em, $fn, $ln, $to);
    
}
