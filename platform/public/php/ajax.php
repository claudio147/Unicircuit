<?php
require_once ('../../../library/public/database.inc.php');


$link= connectDB();

//Formular mit Platzhalterwerten einer Globalen Adresse
if(isset($_POST['id'])){
    $data= '';
    //ID aus globaler Adressliste
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
        
        $data.= '<input type="hidden" name="idGlobalAddress" value="'.$id.'">
                <h4>Firmendaten</h4>
                <p>BKP*</p>
                <input type="text" name="bkp" value="'.$bkp.'" readonly="readonly">
                <p>Firma*</p>
                <input type="text" name="company" value="'.$company.'" readonly="readonly">
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
                <p>Telefon (Hauptnummer)*</p>
                <input type="text" name="phoneNumber" value="'.$phoneNumber.'" readonly="readonly">
                <p>Homepage*</p>
                <input type="text" name="homepage" value="'.$homepage.'" readonly="readonly">
                <br /><br />
                <h4>Direkte Kontaktdaten</h4>
                <p>Ansprechpartner</p>
                <input type="text" name="projectCoordinator">                
                <p>Email (Direkt)</p>
                <input type="text" name="emailDirect">
                <p>Telefon (Direkt)</p>
                <input type="text" name="phoneDirect">
                <p>Mobile (Direkt)</p>
                <input type="text" name="mobileDirect">
                <p>Notizen</p>
                <input type="textarea" name="description">';
                echo $data;
    }
}

//Leeres Formular um neue Adresse hinzuzufügen
if(isset($_POST['new'])){
    $data= '';
            
        $data.= '<h4>Firmendaten</h4>
                <p>BKP*</p>
                <input type="text" name="bkp">
                <p>Firma*</p>
                <input type="text" name="company">
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
                <p>Telefon (Hauptnummer)*</p>
                <input type="text" name="phoneNumber">
                <p>Homepage*</p>
                <input type="text" name="homepage">

                <br /><br />
                <h4>Direkte Kontaktdaten</h4>
                <p>Ansprechpartner</p>
                <input type="text" name="projectCoordinator">
                <p>Email (Direkt)</p>
                <input type="text" name="emailDirect">
                <p>Telefon (Direkt)</p>
                <input type="text" name="phoneDirect">
                <p>Mobile (Direkt)</p>
                <input type="text" name="mobileDirect">
                <p>Notizen</p>
                <input type="textarea" name="description">';
                echo $data;
}

//Formular mit Platzhaltern um einen bestehenden Projekt-Adress-Eintrag zu bearbeiten
if(isset($_POST['edit'])){
    $data= '';
    
    //Projektadresse ID
    $id= filter_input(INPUT_POST, 'edit', FILTER_SANITIZE_NUMBER_INT);
    
    $sql4= getProjectAddress($id);
    $result4= mysqli_query($link, $sql4);
    while($row= mysqli_fetch_array($result4)){
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
        $projectCoordinator= $row['ProjectCoordinator'];
        $phoneDirect= $row['PhoneDirect'];
        $mobileNumber= $row['MobileNumber'];
        $emailDirect= $row['EmailDirect'];
        $description= $row['Description'];
        
        $data.= '<input type="hidden" name="idProjectAddress" value="'.$id.'">
                <h4>Firmendaten</h4>
                <p>BKP*</p>
                <input type="text" name="bkp" value="'.$bkp.'" readonly="readonly">
                <p>Firma*</p>
                <input type="text" name="company" value="'.$company.'" readonly="readonly">
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
                <p>Telefon (Hauptnummer)*</p>
                <input type="text" name="phoneNumber" value="'.$phoneNumber.'" readonly="readonly">
                <p>Homepage*</p>
                <input type="text" name="homepage" value="'.$homepage.'" readonly="readonly">

                <br /><br />
                <h4>Direkte Kontaktdaten</h4>
                <p>Ansprechpartner</p>
                <input type="text" name="projectCoordinator" value="'.$projectCoordinator.'">
                <p>Email (Direkt)</p>
                <input type="text" name="emailDirect" value="'.$emailDirect.'">
                <p>Telefon (Direkt)</p>
                <input type="text" name="phoneDirect" value="'.$phoneDirect.'">
                <p>Mobile (Direkt)</p>
                <input type="text" name="mobileDirect" value="'.$mobileNumber.'">
                <p>Notizen</p>
                <input type="textarea" name="description" value="'.$description.'">';
                echo $data;
    }
}

if(isset($_POST['postEdit'])){
    $data= '';

    //Post ID
    $id= filter_input(INPUT_POST, 'postEdit', FILTER_SANITIZE_NUMBER_INT);
    $sql= selectPostbyID($id);
    $result= mysqli_query($link, $sql);

    while($row= mysqli_fetch_array($result)){
        $postID= $row['IdTimeline'];
        $visible= $row['Id_visible'];
        $hashName= $row['HashName'];
        $orgName= $row['OrgName'];
        $path= $row['Path'];
        $title= $row['Title'];
        $date= $row['Date'];
        $time= $row['Time'];
        $content= $row['Description'];

        if($visible==1){
            $check1='checked="checked"';
            $check2='';
        }else if($visible==2){
            $check2='checked="checked"';
            $check1='';
        }

        $data.= '<input type="hidden" name="postID" value="'.$postID.'"/>
                <input type="hidden" name="hash" value="'.$hashName.'"/>
                <input type="hidden" name="path" value="'.$path.'"/>
                <input type="hidden" name="orgName" value="'.$orgName.'"/>
                <p>Titel*</p>
                <input type="text" name="title" value="'.$title.'">
                <p>Inhalt*</p>
                <textarea name="content">'.$content.'</textarea>
                <p>Sichtbarkeit*</p>
                <p>
                    <input type="radio" name="visible" value="1" '.$check1.'/>  Nur Architekt
                    <input type="radio" name="visible" value="2" '.$check2.'/>  Architekt und Bauherr
                </p>
                <p>Bildupload</p>
                <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/> <!-- Grössenbegrenzung (nicht Sicher) -->
                <input type="file" name="userfile"/>';

                echo $data;
    }

}