<?php
/*
*   Unicircuit Plattform
*   «Aktivierung des Users (Link wird per Mail verschickt)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/
        
//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

$rg = $_GET['regcode'];
$link=connectDB();
$sql = "SELECT * FROM User WHERE regcode='$rg'";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) == 1) {
    
    $sql = "UPDATE User SET regcode=1, Active=3 WHERE regcode='$rg'";
    $status = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
  
  
    //Erstellt Architekten Verzeichnis wenn der er User ein Architekt ist
    if($row['Fk_IdUserType'] == 2) {
        $dir = mkdir('../architects/architect_'.$row['IdUser']);
    
    }   
    //Weiterleitung auf Login mit Statusausgabe
    header('location: login.php?reg=1'); 
}else{
    //Weiterleitung auf Login mit Fehlerausgabe
    header('location: login.php?reg=2');
/*'<p>Es tut uns leid, aber die Registration auf unserer Plattform ist aus'
  . 'unerklärlichen Gründen fehlgeschlagen.</p><p>Bitte registrieren Sie sich '
  . 'erneut auf unserer Plattform oder setzen Sie sich mit unserem'
 
    . 'Help-Desk in Verbindung</p>';
   */
}