<?php
require_once ('../../../library/public/database.inc.php');


$link= connectDB();


if(isset($_POST['id'])){
    $data= '';
    
    $id= filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    $sql3=getGlobalAddress($id);
    $result3= mysqli_query($link, $sql3);
    while($row= mysqli_fetch_array($result3)){
        $bkp= $row['BKP'];
        $company= $row['Company'];
        $addressline1= $row['Addressline1'];
        $addressline2= $row['Addressline2'];
        $zip= $row['ZIP'];
        $city= $row['City'];
        $country= $row['Country'];
        $email= $row['Email'];
        $phoneNumber= $row['PhoneNumber'];
        $homepage= $row['Homepage'];
        
        $data.= '<input type="hidden" name="id" value="'.$id.'">
                <p>BKP*</p>
                <input type="text" name="bkp" value="'.$bkp.'" readonly="readonly">
                <p>Firma*</p>
                <input type="text" name="company" value="'.$company.'" readonly="readonly">
                <p>Ansprechpartner</p>
                <input type="text" name="projectCoordinator">
                <p>Adresszeile 1*</p>
                <input type="text" name="addressline1" value="'.$addressline1.'" readonly="readonly">
                <p>Adresszeile 2</p>
                <input type="text" name="addressline2" value="'.$addressline2.'" readonly="readonly">
                <p>PLZ* / Ort*</p>
                <input type="text" name="zip" value="'.$zip.'" readonly="readonly"><input type="text" name="city" value="'.$city.'" readonly="readonly">
                <p>Land*</p>
                <select name="country">
                <option value="Schweiz" selected="selected">Schweiz</option>
                <option value="Deutschland">Deutschland</option>
                <option value="Österreich">Österreich</option>
                <option value="Lichtenstein">Lichtenstein</option>
                </select>
                <p>Email (Hauptadresse)*</p>
                <input type="email" name="email" value="'.$email.'" readonly="readonly">
                <p>Email (Direkt)</p>
                <input type="text" name="emailDirect">
                <p>Telefon (Hauptnummer)*</p>
                <input type="text" name="phoneNumber" value="'.$phoneNumber.'" readonly="readonly">
                <p>Telefon (Direkt)</p>
                <input type="text" name="phoneDirect">
                <p>Mobile (Direkt)</p>
                <input type="text" name="mobileDirect">
                <p>Homepage*</p>
                <input type="text" name="homepage" value="'.$homepage.'" readonly="readonly">
                <p>Notizen</p>
                <input type="textarea" name="description">';
                echo $data;
    }
}

if(isset($_POST['new'])){
    $data= '';
            
        $data.= '<p>BKP*</p>
                <input type="text" name="bkp">
                <p>Firma*</p>
                <input type="text" name="company">
                <p>Ansprechpartner</p>
                <input type="text" name="projectCoordinator">
                <p>Adresszeile 1*</p>
                <input type="text" name="addressline1">
                <p>Adresszeile 2</p>
                <input type="text" name="addressline2">
                <p>PLZ* / Ort*</p>
                <input type="text" name="zip"><input type="text" name="city">
                <p>Land*</p>
                <select name="country">
                <option value="Schweiz" selected="selected">Schweiz</option>
                <option value="Deutschland">Deutschland</option>
                <option value="Österreich">Österreich</option>
                <option value="Lichtenstein">Lichtenstein</option>
                </select>
                <p>Email (Hauptadresse)*</p>
                <input type="email" name="email">
                <p>Email (Direkt)</p>
                <input type="text" name="emailDirect">
                <p>Telefon (Hauptnummer)*</p>
                <input type="text" name="phoneNumber">
                <p>Telefon (Direkt)</p>
                <input type="text" name="phoneDirect">
                <p>Mobile (Direkt)</p>
                <input type="text" name="mobileDirect">
                <p>Homepage*</p>
                <input type="text" name="homepage">
                <p>Notizen</p>
                <input type="textarea" name="description">';
                echo $data;
}