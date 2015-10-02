<?php
/*
*   Redaktionssystem
*   «userverwaltung.php / Modul ist zuständig für die Verwaltung von Usern (Architekt, Bauherr, Admin)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');
require_once ('../../../library/public/security.inc.php');

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
    }else if($stat==1){
        //Aktivieren fehlgeschlagen
        header('Location: userverwaltung.php?id='.$usID);
        exit();
    }else{
        //Fehlermeldung: User ist nicht gesperrt
        header('Location: index.php?nav=3&statusSave=6');
        exit();
    }
}

//Löscht einen User
if(isset($_POST['delete'])){
    $usID= $_POST['userID'];
    $typ= $_POST['userTyp'];
    
    if($typ==2){
        //Löschen von Architekt
        //Holt alle Projekte eines Architekts
        $sql=getAllProjectsByArch($usID);
        $result=mysqli_query($link, $sql);
        while($row=  mysqli_fetch_array($result)){
            $proID= $row['IdProject'];
            $idBauherr= $row['Fk_IdBauherr'];
            $path = '../../../platform/public/architects/architect_'.$usID.'/project_'.$proID.'/';
            
            //Funtkion zum Löschen des Ordners mit Inhalt des Projektes
            $handle = opendir($path);
            if($handle){
                while ( false !== ($file = readdir($handle))){
                    if ( $file != "." and $file != ".." ){
                        unlink($path.$file);
                    }
                }   
            }
            rmdir($path);
            
            $sql= deleteProject($proID);
            $status= mysqli_query($link, $sql);
            
            //Löscht den dazugehörigen Bauherren
            $sql = deleteBauherr($idBauherr);
            $statusDel = mysqli_query($link, $sql);
            if(!$status && !$statusDel){
                $error=true;
            }
        }
        //Überprüfung ob etwas fehlgeschlagen hat
        if(!isset($error)){
            $path = '../../../platform/public/architects/architect_'.$usID.'/';
            
            //Funtkion zum Löschen des Ordners mit Inhalt des Projektes
            $handle = opendir($path);
            if($handle){
                while ( false !== ($file = readdir($handle))){
                    if ( $file != "." and $file != ".." ){
                        unlink($path.$file);
                    }
                }   
            }
            rmdir($path);
            
            //Löschen des Architekten-Users
            $sql=deleteBauherr($usID);
            $status=mysqli_query($link, $sql);
            
            if($status){
                header('Location: index.php?nav=3&statusSave=7');
                exit();
            }else{
                header('Location: index.php?nav=3&statusSave=8');
                exit();
            }
        }else{
            header('Location: index.php?nav=3&statusSave=8');
            exit();
        }
    }else{
        header('Location: index.php?nav=3&statusSave=9');
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
                        <input type="submit" name="delete" value="User löschen" class="btn btn-default">
                        <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="">Schliessen</button>
                    </div>
                </form>
            </div>

        </div>
      </div>
    <!-- Modal End -->

<?php

//Ausgabe der Erfolgs- bzw. Fehlermeldungen
if(isset($status)){
    //Rückgabemeldung für Event-Handling Deadlines
    $stat = checkRMSUser($status);
    echo $stat;
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
echo'<th class="all"></th>';
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
    echo '<td><a href="mailto:'.$row['Email'].'">'.$row['Email'].'</a></td>';

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
        echo '<td class="danger">ERROR!!</td>';
    }

    echo'<td><button type="button" class="btn btn-default btn-sm btn-user-details" data-toggle="modal" data-target="#modalUser" value="'.$row['IdUser'].'">Details</button></td>';
    echo '</tr>';
 }
 echo'</tbody>';
 echo '</table>';

?>
</div>