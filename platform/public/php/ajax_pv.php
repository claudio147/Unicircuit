<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

$link = connectDB();

//Formular mit Platzhaltern für das Editieren eines CHronik-Beitrags
if (isset($_POST['postEdit'])) {
    $data = '';

    //Post ID
    $id = filter_input(INPUT_POST, 'postEdit', FILTER_SANITIZE_NUMBER_INT);
    //$sql= selectProjectById($id);
    $sql = 'SELECT p.ProjectNumber, p.Title, p.Addressline1, p.Addressline2, p.ZIP, p.City,
        p.Country, p.Description, p.Picture, u.IdUser, u.Firstname AS FirstnameBh, u.Lastname AS LastnameBh,
        u.Addressline1 AS Addressline1Bh, u.Addressline2 AS Addressline2Bh, u.ZIP AS ZIPBh,
        u.City AS CityBh, u.Country AS CountryBh, u.Email, u.PhoneNumber, u.MobileNumber FROM project as p JOIN user
        as u on p.Fk_IdBauherr = u.IdUser WHERE IdProject = ' . $id;
    $result = mysqli_query($link, $sql);
    
    while ($row = mysqli_fetch_array($result)) {
        $postProject = $id;
        $projectNumb = $row['ProjectNumber'];
        $title = $row['Title'];
        $addressline1 = $row['Addressline1'];
        $addressline2 = $row['Addressline2'];
        $zip = $row['ZIP'];
        $city = $row['City'];
        $country = $row['Country'];
        $description = $row['Description'];
        $picture = $row['Picture'];
        $idUser = $row['IdUser'];
        $bhFirstname = $row['FirstnameBh'];
        $bhLastname = $row['LastnameBh'];
        $bhAddressline1 = $row['Addressline1Bh'];
        $bhAddressline2 = $row['Addressline2Bh'];
        $bhZIP = $row['ZIPBh'];
        $bhCity = $row['CityBh'];
        $bhCountry = $row['CountryBh'];
        $bhEmail = $row['Email'];
        $bhPhNu = $row['PhoneNumber'];
        $bhMoNu = $row['MobileNumber'];






        $data.= '<input type="hidden" name="postID" value="'.$postProject.'"/>
                <p>Projektnummer*</p>
                 <input type="text" name="ProjectNumber" value="'.$projectNumb.'">
                <p>Projektbezeichnung</p>
                 <input type="text" name="Title" value="'.$title.'">
                 <p>Strasse</p>
                 <input type="text" name="Addressline1" value="'.$addressline1.'">
                 <p>Addresszeile 2</p>
                 <input type="text" name="Addressline2" value="'.$addressline2.'">
                 <p>PLZ*/Ort*</p>
                 <input type="text" name="ZIP" value="'.$zip.'"><input type="text" name="City" value="'.$city.'">
                 <p>Land</p>
                 <input type="text" name="Country" value="'.$country.'">
                 <p>Projektbeschrieb</p>
                 <textarea name="Description">'.$description.'</textarea>
                 <p>Projektbild</p>
                 <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/> <!-- Grössenbegrenzung (nicht Sicher) -->
                 <input type="file" name="userfile"/>
                 <!-- Bauherren Daten, zur erstellung Bauherr -->
                 <h4>Daten Bauherr</h4>
                 <p>Vorname</p>
                 <input type="text" name="BhFirstname" value="'.$bhFirstname.'">
                 <p>Nachname</p>
                 <input type="text" name="BhLastname" value="'.$bhLastname.'">
                 <p>Strasse</p>
                 <input type="text" name="BhAddressline1" value="'.$bhAddressline1.'">
                 <p>Adresszeile 2</p>
                 <input type="text" name="BhAddressline2" value="'.$bhAddressline2.'">
                 <p>PLZ/Ort</p>
                 <input type="text" name="BhZIP" value="'.$bhZIP.'"><input type="text" name="BhCity" value="'.$bhCity.'">
                 <p>Land</p>
                 <input type="text" name="BhCountry" value="'.$bhCountry.'">
                 <p>Telefonnummer</p>
                 <input type="submit" name="pwReset" value="Passwort Reset" class="btn btn-default" />
                 <input type="text" name="BhPhoneNumber" value="'.$bhPhNu.'">
                 <p>Mobile Nummer</p>
                 <input type="text" name="BhMobileNumber" value="'.$bhMoNu.'">
                 <p>Email</p>
                 <input type="text" name="BhEmail" value="'.$bhEmail.'">';

        echo $data;
    }
}