<?php
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');


$link= connectDB();

if(isset($projectID)){
    $sql= allProjectAddress($projectID);
    $result = mysqli_query($link, $sql);

    $sql2=allGlobalAddress();
    $result2= mysqli_query($link, $sql2);
}


if(isset($_POST['submit'])){
    $error=false;
    
    $idGlobal;
    $projectID = filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
    $bkp = filter_input(INPUT_POST, 'bkp', FILTER_SANITIZE_NUMBER_INT);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
    $addressline1 = filter_input(INPUT_POST, 'addressline1', FILTER_SANITIZE_STRING);
    $addressline2 = filter_input(INPUT_POST, 'addressline2', FILTER_SANITIZE_STRING);
    $zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_NUMBER_INT);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $phoneNumber = filter_input(INPUT_POST, 'phoneNumber', FILTER_SANITIZE_STRING);
    $homepage = filter_input(INPUT_POST, 'homepage', FILTER_SANITIZE_STRING);
    $projectCoordinator = filter_input(INPUT_POST, 'projectCoordinator', FILTER_SANITIZE_STRING);
    $phoneDirect = filter_input(INPUT_POST, 'phoneDirect', FILTER_SANITIZE_STRING);
    $mobileDirect = filter_input(INPUT_POST, 'mobileDirect', FILTER_SANITIZE_STRING);
    $emailDirect = filter_input(INPUT_POST, 'emailDirect', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    //echo $projectID;
    // Fehler im Eingabefeld?
    if (empty($bkp) || strlen($bkp) > 3) {
        $errorBKP = true;
        $error = true;
        echo $bkp;
    }

    // Fehler im Eingabefeld?
    if (empty($company) || strlen($company) < 4) {
        $errorCompany = true;
        $error = true;
        echo'2';
    }

    // Fehler im Eingabefeld?
    if (empty($addressline1) || strlen($addressline1) < 5) {
        $errorAddressline1 = true;
        $error = true;
        echo '3';
    }

    // Fehler im Eingabefeld?
    if (empty($zip) || strlen($zip) < 4) {
        $errorZIP = true;
        $error = true;
        echo'4';
    }

    // Fehler im Eingabefeld?
    if (empty($city) || strlen($city) < 4) {
        $errorCity = true;
        $error = true;
        echo'5';
    }

    // Fehler im Eingabefeld?
    if (empty($country)) {
        $errorCountry = true;
        $error = true;
        echo'6';
    }

    // Mailadresse korrekt?
    if (!checkMailFormat($email)) {
        $errorEmail = true;
        $error = true;
        echo'7';
    }

    // Fehler im Eingabefeld?
    if (empty($phoneNumber) || strlen($phoneNumber) < 10) {
        $errorPhoneNumber = true;
        $error = true;
        echo'8';
    }

    // Fehler im Eingabefeld?
    if (empty($homepage) || strlen($homepage) < 10) {
        $errorHomepage = true;
        $error = true;
        echo'9';
    }

    //Datenbankbefehle wenn kein Error vorhanden ist.
    if(!$error){
        //Überprüfe ob eine id übergeben wurde
        if(!isset($_POST['idGlobalAddress']) && !isset($_POST['idProjectAddress'])){
            if(checkGlobalAddress($company)){
                //Insert Into Globale Adressliste
                $sql= newGlobalAddress($bkp, $company, $addressline1, $addressline2, $zip,
                $city, $country, $email, $phoneNumber, $homepage);
                $statusGlobal = mysqli_query($link, $sql);

                //Hole ID von Eintrag in globalen Adressliste
                $sql= getIdGlobal($company, $addressline1);
                $resultID= mysqli_query($link, $sql);
                while($row=  mysqli_fetch_array($resultID)){
                    $idGlobal= $row['IdGlobalAddress'];
                }
            }else{
                $statusGlobal=false;
                echo'<p>Firma exisitert bereits in globaler DB</p>';
            }
        }else if(isset($_POST['idGlobalAddress'])){
            $idGlobal = filter_input(INPUT_POST, 'idGlobalAddress', FILTER_SANITIZE_NUMBER_INT);
            //echo $idGlobal;
            $statusGlobal=true;
        }else{
            $statusGlobal=true;
        }



        if(isset($_POST['idProjectAddress'])){
            $idProjectAddress= filter_input(INPUT_POST, 'idProjectAddress', FILTER_SANITIZE_NUMBER_INT);
            //Update Into Projekt- Adressliste (Eintrag wird bearbeitet)
            $sql= updateProjectAddress($idProjectAddress, $projectCoordinator, $phoneDirect, $mobileDirect,
            $emailDirect, $description);
            $statusProject= mysqli_query($link, $sql);
        }else{
            //Insert Into Projekt- Adressliste (Neuer Eintrag wird erstellt)
            $sql= newProjectAddress($projectID, $idGlobal, $projectCoordinator, $phoneDirect, $mobileDirect,
            $emailDirect, $description);
            //echo$sql;
            //echo$statusGlobal;
            $statusProject= mysqli_query($link, $sql);
        }


        if($statusGlobal==true && $statusProject==true){
            header('Location: index.php?id=6&project='.$projectID);
        }else{
            echo'<p>Error1</p>';
        }
    }else{
        echo'<p>Error2</p>';
    }
}

if(isset($_POST['delete'])){
    $projectID = filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
    $idProjectAddress= filter_input(INPUT_POST, 'idProjectAddress', FILTER_SANITIZE_NUMBER_INT);

    $sql= deleteProjectAddress($idProjectAddress);
    $resultDel = mysqli_query($link, $sql);
    if($resultDel){
        header('Location: index.php?id=6&project='.$projectID);
        exit();
    }else{
        echo'<p>Löschen fehlgeschlagen</p>';
    }
}

?>


<?php
echo'<div class="col-xs-12">';
echo'<h2 class="modul-title">Adressliste</h2>';
?>

<!--Lightboxen (Modals)-->
<div class="container modalgroup">
    
<?php
    if($usertyp==2){
        echo'<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" id="btn_new_address">+ Hinzufügen</button>';
    } 
?> 
  <!-- Modal Global-->
  <div class="modal" id="myModal" role="dialog">
      <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
          <form action="addresslist.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handwerker hinzufügen</h4>
        </div>
              <div class="modal-body">

            <div id="input_container testXY">
            <h3>Adressdatenbank</h3>
                <?php

                    echo'<table class="table order hover" id="globalAddress">';
                    echo'<thead>';
                    echo'<tr>';
                    echo'<th>Firma</th>';
                    echo'<th>BKP</th>';
                    echo'<th>PLZ</th>';
                    echo'<th>Ort</th>';
                    echo'<th></th>';
                    echo'</tr>';
                    echo'</thead>';
                    echo'<tbody>';

                    while($row= mysqli_fetch_array($result2)){
                        echo'<tr>';
                        echo'<td><a href="http://'.$row['Homepage'].'/" target="_blank">'.$row['Company'].'</a></td>';
                        echo'<td>'.$row['BKP'].'</td>';
                        echo'<td>'.$row['ZIP'].'</td>';
                        echo'<td>'.$row['City'].'</td>';
                        echo'<td><button type="button" class="btn btn-default btn_add" data-toggle="modal" data-target="#modalSearch" data-dismiss="modal" value="'.$row['IdGlobalAddress'].'">hinzufügen</button></td>';
                        echo'</tr>';
                    }
                    echo'</tbody>';
                    echo'</table>';
                    ?>
                <button type="button" class="btn btn-default btn_new" data-toggle="modal" data-target="#modalSearch" data-dismiss="modal">Neue Adresse</button>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
        </div>
        </form>

      </div>

    </div>
  </div>


  <!-- Modal Hinzufügen-->
  <div class="modal" id="modalSearch" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
          <form action="addresslist.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#myModal">&times;</button>
          <h4 class="modal-title">Handwerker hinzufügen</h4>
        </div>
        <div class="modal-body">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <div id="newAddress">
                

                <!-- Platzhalter für Inhalt aus Ajax Methode (ajax.php) -->

            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" name="submit" value="Speichern" class="btn btn-default">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#myModal">Zurück</button>
        </div>
        </form>

      </div>

    </div>
  </div>
  <!-- Modal End -->


   <!-- Modal Edit -->
  <div class="modal" id="modalEdit" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
          <form action="addresslist.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" data-toggle="modal">&times;</button>
          <h4 class="modal-title">Handwerker bearbeiten</h4>
        </div>
              <div class="modal-body">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <div id="editAddress">

                <!-- Platzhalter für Inhalt aus Ajax Methode (ajax.php) -->

            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" name="submit" value="Speichern" class="btn btn-default">
            <input type="submit" name="delete" value="Löschen" class="btn btn-default">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal">Schliessen</button>
        </div>
        </form>

      </div>

    </div>
  </div>
  <!-- Modal End -->

  
   <!-- Modal Details -->
  <div class="modal" id="modalDetails" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
          
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" data-toggle="modal">&times;</button>
          <h4 class="modal-title">Handwerker Details</h4>
        </div>
              <div class="modal-body">
            
            <div id="detailAddress">

                <!-- Platzhalter für Inhalt aus Ajax Methode (ajax.php) -->

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal">Schliessen</button>
        </div>
       

      </div>

    </div>
  </div>
  <!-- Modal End -->
  
  
</div>

<?php
echo'<table class="table order hover" id="localAddress">';
echo'<thead>';
echo'<tr>';
echo'<th>Firma</th>';
echo'<th>BKP</th>';
echo'<th>Kontaktperson</th>';
echo'<th>Tel. Direkt</th>';
echo'<th>Mobil</th>';
echo'<th>Email Direkt</th>';
echo'<th></th>';
echo'</tr>';
echo'</thead>';
echo'<tbody>';

while($row= mysqli_fetch_array($result)){
    echo'<tr>';
    echo'<td><a href="http://'.$row['Homepage'].'/" target="_blank">'.$row['Company'].'</a></td>';
    echo'<td>'.$row['BKP'].'</td>';
    echo'<td>'.$row['ProjectCoordinator'].'</td>';
    echo'<td><a href="tel:'.$row['PhoneDirect'].'">'.$row['PhoneDirect'].'</td>';
    echo'<td><a href="tel:'.$row['MobileNumber'].'">'.$row['MobileNumber'].'</td>';
    echo'<td><a href="mailto:'.$row['EmailDirect'].'">'.$row['EmailDirect'].'</td>';
    if($usertyp==2){
        echo'<td><button type="button" class="btn btn-default btn_edit" data-toggle="modal" data-target="#modalEdit" value="'.$row['IdProjectAddress'].'">bearbeiten</button></td>';
    }else{
        echo'<td><button type="button" class="btn btn-default btn_details" data-toggle="modal" data-target="#modalDetails" value="'.$row['IdProjectAddress'].'">Details</button></td>';
    }
    
    echo'</tr>';
}
echo'</tbody>';
echo'</table>';
echo'</div>';

?>


