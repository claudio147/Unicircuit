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

?>

<div class="col-xs-12">
	<h2 class="modul-title">Adressdatenbank (Handwerker)</h2>

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
                            <!--<input type="submit" name="delete" value="Löschen" class="btn btn-default">-->
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
            echo'<div class="alert alert-warning" role="alert">Update fehlgeschlagen!</div>';
        }else if($status==0){
            echo'<div class="alert alert-success" role="alert">Update erfolgreich</div>';
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