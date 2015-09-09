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

    if($status){
      header('Location: index.php?nav=3&statusSave=0');
      exit();
    }else{
      header('Location: index.php?nav=3&statusSave=1');
      exit();
    }
}

//Sperrt einen User (sofern er aktiv ist)
if(isset($_POST['block'])){
  $stat= $_POST['status'];
  $usID= $_POST['userID'];
  if($stat==3){
    //User sperren
    $sql=setActive4($usID);
    $status= mysqli_query($link, $sql);
    if($status){
      //Sperren erfolgreich
      header('Location: index.php?nav=3&statusSave=2');
      exit();
    }else{
      //Sperren fehlgeschlagen
      header('Location: index.php?nav=3&statusSave=3');
      exit();
    }
  }else{
    //Fehlermeldung: User noch nicht aktiviert oder schon gesperrt
    header('Location: index.php?nav=3&statusSave=4');
    exit();
  }
}


//Reaktiviert einen User der gesperrt ist
if(isset($_POST['activate'])){
  $stat= $_POST['status'];
  $usID= $_POST['userID'];
  if($stat==4){
    //User aktivieren
    $sql= reactivateUser($usID);
    $status= mysqli_query($link, $sql);
    if($status){
      //Aktivieren erfolgreich
      header('Location: index.php?nav=3&statusSave=0');
      exit();
    }else{
      //Aktivieren fehlgeschlagen
      header('Location: index.php?nav=3&statusSave=5');
      exit();
    }
  }else{
    //Fehlermeldung: User ist nicht gesperrt
    header('Location: index.php?nav=3&statusSave=6');
    exit();
  }
}
?>



<div class="col-xs-12">
    <h2 class="modul-title">Userverwaltung</h2>

  <!-- Modal Hinzufügen-->
    <div class="modal" id="modalUser" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <form action="userverwaltung.php" method="POST">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="">&times;</button>
              <h4 class="modal-title">User Details</h4>
            </div>
            <div class="modal-body">

                <div id="userDetails">


                    <!-- Platzhalter für Inhalt aus Ajax Methode (ajax.php) -->

                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" name="activate" value="User aktivieren" class="btn btn-default">
                <input type="submit" name="block" value="User sperren" class="btn btn-default">
              <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="">Schliessen</button>
            </div>
          </form>

        </div>

      </div>
    </div>
    <!-- Modal End -->



<?php
//Statusmeldungen
if(isset($status)){
        if($status==1){
            echo'<div class="alert alert-warning" role="alert">User aktivierung fehlgeschlagen!</div>';
        }else if($status==0){
            echo'<div class="alert alert-success" role="alert">User erfolgreich aktiviert</div>';
        }else if($status==2){
            echo'<div class="alert alert-success" role="alert">User erfolgreich gesperrt</div>';
        }else if($status==3){
            echo'<div class="alert alert-warning" role="alert">User sperren fehlgeschlagen</div>';
        }else if($status==4){
            echo'<div class="alert alert-warning" role="alert">User ist bereits gesperrt oder inaktiv.</div>';
        }else if($status==5){
            echo'<div class="alert alert-warning" role="alert">User reaktivierung fehlgeschlagen!</div>';
        }else if($status==6){
            echo'<div class="alert alert-warning" role="alert">User die nicht gesperrt sind können nicht reaktiviert werden.</div>';
        }
    }


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
        echo '<td class="info"><a href="userverwaltung.php?id=' . $row['IdUser'] . '">aktivieren</a></td>';

      } else if($reg == 2) {

        echo '<td class="warning">Aktivierungs Mail verschickt</td>';

      } else if($reg == 3) {

          echo '<td>Aktiv</td>';
      } else if($reg == 4) {
          echo '<td class="danger">User gesperrt</td>';
      } else {
          echo 'Fehler';
      }

      echo'<td><button type="button" class="btn btn-default btn-sm btn-user-details" data-toggle="modal" data-target="#modalUser" value="'.$row['IdUser'].'">Details</button></td>';
      echo '</tr>';
 }
 echo'</tbody>';
 echo '</table>';


?>
</div>

