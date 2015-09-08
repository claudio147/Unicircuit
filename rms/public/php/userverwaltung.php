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




 /*
 * Überprüft ob ein Aktivierungsbutton geklickt wurde
 * sendet ein E-Mail mit dem Aktivierungslink an den user.
 */
if(isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    // $id = $_GET['id'];
    
    $sql = userData($id);

    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $fn = $row['Firstname'];
    $ln = $row['Lastname'];
    $em = $row['Email'];
    $to = $row['RegCode'];

    createRegMail($em, $fn, $ln, $to);
    
    // setze Active auf Stufe 2
    $set = setActive($id);
    $status = mysqli_query($link, $set);
}
?>



<div class="col-xs-12">
    <h2 class="modul-title">Userverwaltung</h2>





<?php

//Erstellen der Anzeige mit den Usern
echo'<table class="table order hover" id="table-user-list">';
echo'<thead>';
echo'<tr>';
echo'<th>Usertyp</th>';
echo'<th>Vorname</th>';
echo'<th>Nachname</th>';
echo'<th>Firma</th>';
echo'<th>PLZ</th>';
echo'<th>Land</th>';
echo'<th>Email</th>';
echo'<th>Status</th>';
echo'<th></th>';
echo'</tr>';
echo'</thead>';

echo'<tbody>';
 while ($row = mysqli_fetch_array($result)) {
      echo '<tr>';
      switch($row['Fk_IdUserType']){
          case 1:
              echo '<td>Administrator</td>';
              break;
          case 2:
              echo '<td>Architekt</td>';
              break;
          case 3:
              echo '<td>Bauherr</td>';
              break;
          default:
              echo '<td></td>';
      } 
      echo '<td>' . $row['Firstname'] . '</td>';
      echo '<td>' . $row['Lastname'] . '</td>';
      echo '<td>' . $row['Company'] . '</td>';
      echo '<td>' . $row['ZIP'] . '</td>';
      echo '<td>' . $row['Country'] . '</td>';
      echo '<td>' . $row['Email'] . '</td>';
      
      
      //Ueberpruefung ob User bereits aktiviert ist und sich schon eingeloggt hat
      $reg = $row['Active'];
      if ($reg == 1) {
        echo '<td><a href="userverwaltung.php?id=' . $row['IdUser'] . '">aktivieren</a></td>';
        
      } else if($reg == 2) {
          
        echo '<td>Aktivierungs Mail verschickt</td>';
        
      } else if($reg == 3) {
        
          echo '<td>User bereits Aktiviert</td>';
      } else if($reg == 4) {
          echo '<td>User gesperrt</td>';
      } else {
          echo 'Fehler';
      }
      
      echo'<td><button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalUser" value="'.$row['IdUser'].'">Details</button></td>';
      echo '</tr>';
 }
 echo'</tbody>';
 echo '</table>';
 
 
 

 
?>
</div>

