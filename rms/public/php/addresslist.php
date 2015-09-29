<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');

$link= connectDB();


if(isset($_POST['submit'])){
    $error=false;
    
    
    $bkp = filter_input(INPUT_POST, 'bkp', FILTER_SANITIZE_NUMBER_INT);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
    $addressline1 = filter_input(INPUT_POST, 'addressline1', FILTER_SANITIZE_STRING);
    $addressline2 = filter_input(INPUT_POST, 'addressline2', FILTER_SANITIZE_STRING);
    $zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_NUMBER_INT);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phoneNumber', FILTER_SANITIZE_STRING);
    $homepage = filter_input(INPUT_POST, 'homepage', FILTER_SANITIZE_STRING);


    // Fehler im Eingabefeld?
    if (empty($bkp) || strlen($bkp) > 3) {
        $errorBKP = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($company) || strlen($company) < 4) {
        $errorCompany = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($addressline1) || strlen($addressline1) < 5) {
        $errorAddressline1 = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($zip) || strlen($zip) < 4) {
        $errorZIP = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($city) || strlen($city) < 4) {
        $errorCity = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($country)) {
        $errorCountry = true;
        $error = true;
    }

    // Mailadresse korrekt?
    if (!checkMailFormat($email)) {
        $errorEmail = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($phone) || strlen($phone) < 10) {
        $errorPhoneNumber = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($homepage) || strlen($homepage) < 10) {
        $errorHomepage = true;
        $error = true;
    }

    //Datenbankbefehle wenn kein Error vorhanden ist.
    if(!$error){
        //Überprüfe ob eine id übergeben wurde
        if(isset($_POST['idGlobalAddress'])){
            $idGlobal = filter_input(INPUT_POST, 'idGlobalAddress', FILTER_SANITIZE_NUMBER_INT);
            
            $sql=updateGlobalAddress($idGlobal, $bkp, $company, $addressline1, $addressline2, $zip, $city, $country, $email, $phone, $homepage);
            $status=  mysqli_query($link, $sql);
            if($status){
                //Update erfolgreich
                header('Location: index.php?nav=4&statusSave=0');
                exit();
            }else{
                //Update Error
                header('Location: index.php?nav=4&statusSave=1');
                exit();
            }
            
        }else{
            //Update Error
            header('Location: index.php?nav=4&statusSave=1');
            exit();
        }
    }else{
            //Update Error
            header('Location: index.php?nav=4&statusSave=1');
            exit();
    }
}

if(isset($_POST['delete'])){
    //Id der globalen Adresse
    $idGlobal = filter_input(INPUT_POST, 'idGlobalAddress', FILTER_SANITIZE_NUMBER_INT);
    
    //Liefert alle Projektadressen die den globalen beinhalten
    $sql0=statsOfGlobalAddress($idGlobal);
    $result0= mysqli_query($link, $sql0);
    while($row0=mysqli_fetch_array($result0)){
        $idProjectAddr= $row0['IdProjectAddress'];
        $sql=updateDeadlineCraft($idProjectAddr);
        $status=mysqli_query($link, $sql);
        if(!$status){
            //$errorDeadline=true;
            header('Location: index.php?nav=4&statusSave=3');
            exit();
        }
    }



    $sql1=deleteGlobal1($idGlobal);
    $sql2=deleteGlobal2($idGlobal);
    $status1=  mysqli_query($link, $sql1);
    $status2=  mysqli_query($link, $sql2);
    
    if($status1 && $status2){
        header('Location: index.php?nav=4&statusSave=2');
        exit();
    }else{
        header('Location: index.php?nav=4&statusSave=3');
        exit();
    }
}

if(isset($_POST['save'])){
    $error=false;
    
    $idGlobal;

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


    // Fehler im Eingabefeld?
    if (empty($bkp) || strlen($bkp) > 3) {
        $errorBKP = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($company) || strlen($company) < 4) {
        $errorCompany = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($addressline1) || strlen($addressline1) < 5) {
        $errorAddressline1 = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($zip) || strlen($zip) < 4) {
        $errorZIP = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($city) || strlen($city) < 4) {
        $errorCity = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($country)) {
        $errorCountry = true;
        $error = true;
    }

    // Mailadresse korrekt?
    if (!checkMailFormat($email)) {
        $errorEmail = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($phoneNumber) || strlen($phoneNumber) < 10) {
        $errorPhoneNumber = true;
        $error = true;
    }

    // Fehler im Eingabefeld?
    if (empty($homepage) || strlen($homepage) < 10) {
        $errorHomepage = true;
        $error = true;
    }

    //Datenbankbefehle wenn kein Error vorhanden ist.
    if(!$error){
        if(checkGlobalAddress($company)){
            //Insert Into Globale Adressliste
            $sql= newGlobalAddress($bkp, $company, $addressline1, $addressline2, $zip,
            $city, $country, $email, $phoneNumber, $homepage);
            $statusSQL = mysqli_query($link, $sql);
            if($statusSQL){
                header('Location: index.php?nav=4&statusSave=4');
                exit();
            }else{
                header('Location: index.php?nav=4&statusSave=5');
                exit();
            }
        }else{
            //Ist bereits in gleicher oder ähnlicher Form vorhanden
            header('Location: index.php?nav=4&statusSave=6');
            exit();
        }
    }else{
        header('Location: index.php?nav=4&statusSave=5');
        exit();
    }        
}

?>

<div class="col-xs-12">
	<h2 class="modul-title">Adressdatenbank (Handwerker)</h2>
        
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#newGlobalAdd">+ Hinzufügen</button>


        <!-- Modal Hinzufügen-->
          <div class="modal" id="newGlobalAdd" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                  <form action="addresslist.php" method="POST">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#myModal">&times;</button>
                  <h4 class="modal-title">Globale Adresse hinzufügen</h4>
                </div>
                <div class="modal-body">
                    <div id="newAddress">
                        

                        <h4>Firmendaten</h4>
                        <label for="1">BKP*</label>
                        <input id="1" type="text" name="bkp" class="form-control" maxlength="3">
                        <label for="2">Firma*</label>
                        <input id="2" type="text" name="company" class="form-control">
                        <label for="3">Adresszeile 1*</label>
                        <input id="3" type="text" name="addressline1" class="form-control">
                        <label for="4">Adresszeile 2</label>
                        <input id="4" type="text" name="addressline2" class="form-control">
                        <div class="row">
                            <div class="col-xs-2">
                                <label for="5" >PLZ*</label>
                                <input id="5" type="text" name="zip" class="form-control" maxlength="4">
                            </div>
                            <div class="col-xs-10">
                                <label for="6">Ort*</label>
                                <input id="6" type="text" name="city" class="form-control">
                            </div>
                        </div>
                        <label for="7">Land*</label>
                        <select id="7" name="country" class="form-control">
                        <?php
                            //Auswahl Länderliste aus DB und erstellt die Dropdown Liste.
                            $sql = "SELECT Country FROM Countries";
                            $resultC = mysqli_query($link, $sql);
                            while($rowC= mysqli_fetch_array($resultC)){
                                echo'<option value="'.$rowC['Country'].'">'.$rowC['Country'].'</option>';
                            }
                        ?>
                        </select>
                        <label for="8">Email (Hauptadresse)*</label>
                        <input id="8" type="email" name="email" class="form-control">
                        <label for="9">Telefon (Hauptnummer)*</label>
                        <input id="9" type="text" name="phoneNumber" class="form-control">
                        <label for="10">Homepage*</label>
                        <input id="10" type="text" name="homepage" class="form-control">

                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="save" value="Speichern" class="btn btn-default">
                  <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal">Schliessen</button>
                </div>
                </form>

              </div>

            </div>
          </div>
          <!-- Modal End -->


        <!-- Modal Hinzufügen-->
        <div class="modal" id="address-details" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <form action="addresslist.php" method="POST">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#myModal">&times;</button>
                            <h4 class="modal-title">Adresse bearbeiten</h4>
                        </div>
                        <div class="modal-body">
                            <div id="address-ajax">

                            <!-- Platzhalter für Inhalt aus Ajax Methode (ajax.php) -->

                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="delete" value="Löschen" class="btn btn-default">
                            <input type="submit" name="submit" value="Speichern" class="btn btn-default">
                            <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal">Schliessen</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <!-- Modal End -->
  
  
  
    <?php
    
    
    if(isset($status)){
        if($status==1){
            echo'<div class="alert alert-danger" role="alert">Update fehlgeschlagen!</div>';
        }else if($status==0){
            echo'<div class="alert alert-success" role="alert">Update erfolgreich</div>';
        }else if($status==2){
            echo'<div class="alert alert-success" role="alert">Adresse erfolgreich gelöscht</div>';
        }else if($status==3){
            echo'<div class="alert alert-danger" role="alert">Löschen fehlgeschlagen!</div>';
        }else if($status==4){
            echo'<div class="alert alert-success" role="alert">Adresse erfolgreich hinzugefügt</div>';
        }else if($status==5){
            echo'<div class="alert alert-danger" role="alert">Adresse hinzufügen fehlgeschlagen!</div>';
        }else if($status==6){
            echo'<div class="alert alert-danger" role="alert">Adresse schon vorhanden!</div>';
        }
    }
    
    
    $sql=allGlobalAddress();
    $result= mysqli_query($link, $sql);

    echo'<table class="table order hover" id="addresslist-rms">';
    echo'<thead>';
    echo'<tr>';
    echo'<th>BKP</th>';
    echo'<th>Firma</th>';
    echo'<th>PLZ</th>';
    echo'<th>Ort</th>';
    echo'<th>Land</th>';
    echo'<th>Telefon</th>';
    echo'<th>Email</th>';
    echo'<th>Homepage</th>';
    echo'<th></th>';
    echo'</tr>';
    echo'</thead>';
    echo'<tbody>';

    while($row= mysqli_fetch_array($result)){
        echo'<tr>';
        echo'<td>'.$row['BKP'].'</td>';
        echo'<td><a href="http://'.$row['Homepage'].'/" target="_blank">'.$row['Company'].'</a></td>';
        echo'<td>'.$row['ZIP'].'</td>';
        echo'<td>'.$row['City'].'</td>';
        echo'<td>'.$row['Country'].'</td>';
        echo'<td>'.$row['PhoneNumber'].'</td>';
        echo'<td><a href="mailto:'.$row['Email'].'">'.$row['Email'].'</a></td>';
        echo'<td><a href="http://'.$row['Homepage'].'" target="_blank">'.$row['Homepage'].'</a></td>';
        echo'<td><button type="button" class="btn btn-default btn_add" data-toggle="modal" data-target="#address-details" data-dismiss="modal" value="'.$row['IdGlobalAddress'].'">Details</button></td>';
        echo'</tr>';
    }
    echo'</tbody>';
    echo'</table>';


    ?>


</div>