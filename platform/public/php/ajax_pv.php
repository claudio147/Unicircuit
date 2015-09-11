<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

$link = connectDB();

//Formular mit Platzhaltern für das Editieren eines Projekt-Beitrags
if (isset($_POST['postEdit'])) {
    $data = '';

    //Post ID
    $id = filter_input(INPUT_POST, 'postEdit', FILTER_SANITIZE_NUMBER_INT);
    //$sql= selectProjectById($id);
    $sql = 'SELECT p.ProjectNumber, p.Title, p.Addressline1, p.Addressline2, p.ZIP, p.City,
        p.Country, p.Description, p.Picture, u.IdUser, u.Firstname AS FirstnameBh, u.Lastname AS LastnameBh,
        u.Addressline1 AS Addressline1Bh, u.Addressline2 AS Addressline2Bh, u.ZIP AS ZIPBh,
        u.City AS CityBh, u.Country AS CountryBh, u.Email, u.PhoneNumber, u.MobileNumber FROM Project as p JOIN User
        as u on p.Fk_IdBauherr = u.IdUser WHERE IdProject = '.$id;
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

        //Auswahl Länderliste aus DB
     $sql = "SELECT Country FROM Countries";
     $resultC = mysqli_query($link, $sql);
 $countries = '';
 $countriesBh='';
                while($rowC= mysqli_fetch_array($resultC)){
                    if($rowC['Country'] == $country) {
                    $countries .= '<option value="'.$rowC['Country'].'" selected = "selected">'.$rowC['Country'].'</option>';
                    }else {
                    $countries .= '<option value="'.$rowC['Country'].'">'.$rowC['Country'].'</option>'; 
                    }
                    
                    if($rowC['Country'] == $bhCountry) {
                    $countriesBh .= '<option value="'.$rowC['Country'].'" selected = "selected">'.$rowC['Country'].'</option>';
                    }else {
                    $countriesBh .= '<option value="'.$rowC['Country'].'">'.$rowC['Country'].'</option>'; 
                    }
                }
               

                
        $data.= '<h4>Daten Projekt</h4>
                <input type="hidden" name="postID" value="'.$postProject.'"/>
                <label for="1">Projektnummer*</label>
                <input id="1" type="text" name="ProjectNumber" class="form-control" value="'.$projectNumb.'">
                <label for="2">Projektbezeichnung</label>
                <input id="2" type="text" name="Title" class="form-control" value="'.$title.'">
                <label for="3">Strasse</label>
                <input id="3" type="text" name="Addressline1" class="form-control" value="'.$addressline1.'">
                <label for="4">Addresszeile 2</label>
                <input id="4" type="text" name="Addressline2" class="form-control" value="'.$bhAddressline2.'">
                <div class="row">
                    <div class="col-xs-2">
                        <label for="5">PLZ</label>
                        <input id="5" type="text" name="ZIP" class="form-control" value="'.$zip.'">
                    </div>
                    <div class="col-xs-10">
                        <label for="6">Ort</label>
                        <input id="6" type="text" name="City" class="form-control" value="'.$city.'">
                    </div>
                </div>
                <label for="7">Land</label>
                <select id="7" name="Country" class="form-control">';
        $data .= $countries;    
        $data .='</select>
                <label for="8">Projektbeschrieb</label>
                <textarea id="8" name="Description" class="form-control">'.$description.'</textarea>
                <label for="upload">Projektbild</label>
                <input id="upload" type="file" name="userfile"/>
                <!-- Bauherren Daten, zur erstellung Bauherr -->
                <hr/>
                <h4>Daten Bauherr</h4>
                <label for="9">Vorname</label>
                <input id="9" type="text" name="BhFirstname" class="form-control" value="'.$bhFirstname.'">
                <label for="10">Nachname</label>
                <input id="10" type="text" name="BhLastname" class="form-control" value="'.$bhLastname.'">
                <label for="11">Strasse</label>
                <input id="11" type="text" name="BhAddressline1" class="form-control" value="'.$bhAddressline1.'">
                <label for="12">Adresszeile 2</label>
                <input id="12" type="text" name="BhAddressline2" class="form-control" value="'.$bhAddressline2.'">
                <div class="row">
                    <div class="col-xs-2">
                        <label for="13">PLZ*</label>
                        <input id="13" type="text" name="BhZIP" class="form-control" value="'.$bhZIP.'">
                    </div>
                    <div class="col-xs-10">
                        <label for="14">Ort*</label>
                        <input id="14" type="text" name="BhCity" class="form-control" value="'.$bhCity.'">
                    </div>
                </div>
                <label for="15">Land</label>
                <select id="15" name="BhCountry" class="form-control">';
        $data .= $countriesBh;    
        $data .='</select>
                <label for="16">Telefonnummer</label>
                <input id="16" type="text" name="BhPhoneNumber" class="form-control" value="'.$bhPhNu.'">
                <label for="17">Mobile Nummer</label>
                <input id="17" type="text" name="BhMobileNumber" class="form-control" value="'.$bhMoNu.'">
                <label for="18">Email</label>
                <input id="18" type="email" name="BhEmail" class="form-control" value="'.$bhEmail.'"><br/>
                <input type="submit" name="pwReset" value="Passwort Reset Bauherr" class="btn btn-default"/>';

        echo $data;
    }
}

//Formular Ansicht für Projekte in Storage, nicht editierbar.
if (isset($_POST['postStorage'])) {
    $data = '';

    //Post ID
    $id = filter_input(INPUT_POST, 'postStorage', FILTER_SANITIZE_NUMBER_INT);
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






        $data.= '<input type="hidden" name="postID" value="'.$postProject.'" readonly="readonly"/>
                <p>Projektnummer*</p>
                 <input type="text" name="ProjectNumber" value="'.$projectNumb.'"readonly="readonly>
                <p>Projektbezeichnung</p>
                 <input type="text" name="Title" value="'.$title.' readonly="readonly" ">
                 <p>Strasse</p>
                 <input type="text" name="Addressline1" value="'.$addressline1.'" readonly="readonly">
                 <p>Addresszeile 2</p>
                 <input type="text" name="Addressline2" value="'.$addressline2.'" readonly="readonly">
                 <p>PLZ*/Ort*</p>
                 <input type="text" name="ZIP" value="'.$zip.'"><input type="text" name="City" value="'.$city.'" readonly="readonly">
                 <p>Land</p>
                 <input type="text" name="Country" value="'.$country.'" readonly="readonly">
                 <p>Projektbeschrieb</p>
                 <textarea name="Description" readonly="readonly" >'.$description.'</textarea>
                 <p>Projektbild</p>
                 
                 <h4>Daten Bauherr</h4>
                 <p>Vorname</p>
                 <input type="text" name="BhFirstname" value="'.$bhFirstname.'" readonly="readonly">
                 <p>Nachname</p>
                 <input type="text" name="BhLastname" value="'.$bhLastname.'" readonly="readonly">
                 <p>Strasse</p>
                 <input type="text" name="BhAddressline1" value="'.$bhAddressline1.'" readonly="readonly">
                 <p>Adresszeile 2</p>
                 <input type="text" name="BhAddressline2" value="'.$bhAddressline2.'" readonly="readonly">
                 <p>PLZ/Ort</p>
                 <input type="text" name="BhZIP" value="'.$bhZIP.'"><input type="text" name="BhCity" value="'.$bhCity.'" readonly="readonly">
                 <p>Land</p>
                 <input type="text" name="BhCountry" value="'.$bhCountry.'" readonly="readonly">
                 <p>Telefonnummer</p>
                 <input type="text" name="BhPhoneNumber" value="'.$bhPhNu.'" readonly="readonly">
                 <p>Mobile Nummer</p>
                 <input type="text" name="BhMobileNumber" value="'.$bhMoNu.'" readonly="readonly">
                 <p>Email</p>
                 <input type="text" name="BhEmail" value="'.$bhEmail.'" readonly="readonly">';

        echo $data;
    }
}