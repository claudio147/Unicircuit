<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

$rg = $_GET['regcode'];
$link=connectDB();
$sql = "SELECT * FROM User WHERE regcode='$rg'";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) == 1) {
    
  $sql = "UPDATE User SET regcode=1 WHERE regcode='$rg'";
  $status = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($result);
  



  echo '<p>Sie haben sich erfolgreich auf der Plattform <i>personenverwaltung</i>'
  . ' registriert. <a href="http://palmers.dynathome.net:8045/login/login.php">'
  . 'Bitte melden Sie sich an</a>. Wir wünschen viel Vergnügen</p>';
    $id = $row['IdUser'];
    $set = setActive3($id);
  $result = mysqli_query($link, $set);
  
  //Erstellt Architekten Verzeichnis wenn der er User ein Architekt ist
  if($row['Fk_IdUserType'] == 2) {
      
  $dir = mkdir('architekt'.$row['IdUser']);
  }
  
} else {
  
  echo '<p>Es tut uns leid, aber die Registration auf unserer Plattform ist aus'
  . 'unerklärlichen Gründen fehlgeschlagen.</p><p>Bitte registrieren Sie sich '
  . 'erneut auf unserer Plattform oder setzen Sie sich mit unserem'
  . 'Help-Desk in Verbindung</p>';
}

