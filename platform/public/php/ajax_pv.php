<!--
*   Unicircuit Plattform
*   «Ajax Loader (für Projektverwaltung)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
-->
<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

$link = connectDB();

//Formular mit Platzhaltern für das Editieren eines Projekt-Beitrags
if (isset($_POST['postEdit'])){
    $data = '';

    //Post ID
    $id = filter_input(INPUT_POST, 'postEdit', FILTER_SANITIZE_NUMBER_INT);
    $sql= selectProjectById($id);
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
                <input id="1" type="text" name="ProjectNumber" class="form-control" value="'.$projectNumb.'" maxlength="14">
                <label for="2">Projektbezeichnung*</label>
                <input id="2" type="text" name="Title" class="form-control" value="'.$title.'" maxlength="14">
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
    $sql= selectProjectById($id);
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






        $data.= '<h4>Daten Projekt</h4>
                <input type="hidden" name="postID" value="'.$postProject.'"/>
                <input type="hidden" name="idUser" value="'.$idUser.'" />
                <label for="1">Projektnummer*</label>
                <input id="1" type="text" name="ProjectNumber" class="form-control" value="'.$projectNumb.'" maxlength="14" readonly="readonly">
                <label for="2">Projektbezeichnung</label>
                <input id="2" type="text" name="Title" class="form-control" value="'.$title.'" readonly="readonly">
                <label for="3">Strasse</label>
                <input id="3" type="text" name="Addressline1" class="form-control" value="'.$addressline1.'" readonly="readonly">
                <label for="4">Addresszeile 2</label>
                <input id="4" type="text" name="Addressline2" class="form-control" value="'.$bhAddressline2.'" readonly="readonly">
                <div class="row">
                    <div class="col-xs-2">
                        <label for="5">PLZ</label>
                        <input id="5" type="text" name="ZIP" class="form-control" value="'.$zip.'" readonly="readonly">
                    </div>
                    <div class="col-xs-10">
                        <label for="6">Ort</label>
                        <input id="6" type="text" name="City" class="form-control" value="'.$city.'" readonly="readonly">
                    </div>
                </div>
                <label for="7">Land</label>
                <select id="7" name="Country" class="form-control" disabled="disabled">
                <option value="'.$country.'">'.$country.'</option>
                </select>
                <label for="8">Projektbeschrieb</label>
                <textarea id="8" name="Description" class="form-control" readonly="readonly">'.$description.'</textarea>
                <label for="upload">Projektbild</label>
                <!-- Bauherren Daten, zur erstellung Bauherr -->
                <hr/>
                <h4>Daten Bauherr</h4>
                <label for="9">Vorname</label>
                <input id="9" type="text" name="BhFirstname" class="form-control" value="'.$bhFirstname.'" readonly="readonly">
                <label for="10">Nachname</label>
                <input id="10" type="text" name="BhLastname" class="form-control" value="'.$bhLastname.'" readonly="readonly">
                <label for="11">Strasse</label>
                <input id="11" type="text" name="BhAddressline1" class="form-control" value="'.$bhAddressline1.'" readonly="readonly">
                <label for="12">Adresszeile 2</label>
                <input id="12" type="text" name="BhAddressline2" class="form-control" value="'.$bhAddressline2.'" readonly="readonly">
                <div class="row">
                    <div class="col-xs-2">
                        <label for="13">PLZ*</label>
                        <input id="13" type="text" name="BhZIP" class="form-control" value="'.$bhZIP.'" readonly="readonly">
                    </div>
                    <div class="col-xs-10">
                        <label for="14">Ort*</label>
                        <input id="14" type="text" name="BhCity" class="form-control" value="'.$bhCity.'" readonly="readonly">
                    </div>
                </div>
                <label for="15">Land</label>
                <select id="15" name="BhCountry" class="form-control" disabled="disabled">
                <option value="'.$bhCountry.'">'.$bhCountry.'</option>
                </select>
                <label for="16">Telefonnummer</label>
                <input id="16" type="text" name="BhPhoneNumber" class="form-control" value="'.$bhPhNu.'" readonly="readonly">
                <label for="17">Mobile Nummer</label>
                <input id="17" type="text" name="BhMobileNumber" class="form-control" value="'.$bhMoNu.'" readonly="readonly">
                <label for="18">Email</label>
                <input id="18" type="email" name="BhEmail" class="form-control" value="'.$bhEmail.'" readonly="readonly"><br/>';

        echo $data;
    }
}

if(isset($_POST['userSettings'])) {
    $id = filter_input(INPUT_POST, 'userSettings', FILTER_SANITIZE_NUMBER_INT);
    
    $sql = getUserbyId($id);
    
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    
    $firstname = $row['Firstname'];
    $lastname = $row['Lastname'];
    $company = $row['Company'];
    $addressline1 = $row['Addressline1'];
    $addressline2 = $row['Addressline2'];
    $zip = $row['ZIP'];
    $city = $row['City'];
    $country = $row['Country'];
    $email = $row['Email'];
    $phoneNumber = $row['PhoneNumber'];
    $mobileNumber = $row['MobileNumber'];
    $usertype = $row['Fk_IdUserType'];  // 1=Administrator 2= Architekt 3= Bauherr

     //Auswahl Länderliste aus DB und erstellt die Dropdown Liste.
     $sql = "SELECT Country FROM Countries";
     $resultC = mysqli_query($link, $sql);
    $countries = '';
        while($rowC= mysqli_fetch_array($resultC)){
            if($rowC['Country'] == $country) {
                $countries .= '<option value="'.$rowC['Country'].'" selected = "selected">'.$rowC['Country'].'</option>';
            }else {
                $countries .= '<option value="'.$rowC['Country'].'">'.$rowC['Country'].'</option>'; 
            }
        }
        //Fügt eine Zeile Firma hinzu falls es sich um einen Architekt handelt.
        //Fügt den Logo Upload hinzu falls es sich um einen Architekten handelt.
        $companyShow = '';
        $logo = '';
        if($usertype == 2) {
            $companyShow = '<label for="3">Firma</label>
                        <input id="3" type="text" name="Company" class="form-control" value="'.$company.'">';
            $logo = '<label for="upload">Logo</label>
                    <input id="upload" type="file" name="userfile"/>';
        }
        
         $data.= '
                <input type="hidden" name="UserId" value="'.$id.'"/>
                <label for="1">Vorname*</label>
                <input id="1" type="text" name="Firstname" class="form-control" value="'.$firstname.'">
                <label for="2">Nachname</label>
                <input id="2" type="text" name="Lastname" class="form-control" value="'.$lastname.'">';
         $data.= $companyShow;
         $data.= '<label for="4">Strasse</label>
                <input id="4" type="text" name="Addressline1" class="form-control" value="'.$addressline1.'">
                <label for="5">Addresszeile 2</label>
                <input id="5" type="text" name="Addressline2" class="form-control" value="'.$addressline2.'">
                <div class="row">
                    <div class="col-xs-2">
                        <label for="6">PLZ</label>
                        <input id="6" type="text" name="ZIP" class="form-control" value="'.$zip.'">
                    </div>
                    <div class="col-xs-10">
                        <label for="7">Ort</label>
                        <input id="7" type="text" name="City" class="form-control" value="'.$city.'">
                    </div>
                </div>
                <label for="8">Land</label>
                <select id="8" name="Country" class="form-control">';
        $data .= $countries;
        $data .= '</select>';
        $data .= '<label for="8"> E-Mail</label>
                <input id="8" type="text" name="Email" class="form-control" value="'.$email.'">';
        $data .= $logo;
        $data .= '<h3>Neues Passwort:</h3>
                <label for="9">Passwort</label>
                <input id="9" class="form-control" type="password" name="password1">
                <label for="10">Passwort wiederholen</label>
                <input id="10" class="form-control" type="password" name="password2">';
                
       
        echo $data;   
}