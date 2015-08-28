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
                <input type="text" name="bkp" value="'.$bkp.'" readonly="readonly" class="form-control">
                <p>Firma*</p>
                <input type="text" name="company" value="'.$company.'" readonly="readonly" class="form-control">
                <p>Adresszeile 1*</p>
                <input type="text" name="addressline1" value="'.$addressline1.'" readonly="readonly" class="form-control">
                <p>Adresszeile 2</p>
                <input type="text" name="addressline2" value="'.$addressline2.'" readonly="readonly" class="form-control">
                <div class="row">
                    <div class="col-xs-2">
                        <p>PLZ*</p>
                        <input type="text" name="zip" value="'.$zip.'" readonly="readonly" class="form-control">
                    </div>
                    <div class="col-xs-10">
                        <p>Ort*</p>
                        <input type="text" name="city" value="'.$city.'" readonly="readonly" class="form-control">
                    </div>
                </div>
                <p>Land*</p>
                <select name="country" class="form-control" readonly="readonly">
                <option value="Schweiz" selected="selected">Schweiz</option>
                <option value="Deutschland">Deutschland</option>
                <option value="Österreich">Österreich</option>
                <option value="Lichtenstein">Lichtenstein</option>
                </select>
                <p>Email (Hauptadresse)*</p>
                <input type="email" name="email" value="'.$email.'" readonly="readonly" class="form-control">
                <p>Telefon (Hauptnummer)*</p>
                <input type="text" name="phoneNumber" value="'.$phoneNumber.'" readonly="readonly" class="form-control">
                <p>Homepage*</p>
                <input type="text" name="homepage" value="'.$homepage.'" readonly="readonly" class="form-control">
                <br /><br />
                <h4>Direkte Kontaktdaten</h4>
                <p>Ansprechpartner</p>
                <input type="text" name="projectCoordinator" class="form-control">
                <p>Email (Direkt)</p>
                <input type="text" name="emailDirect" class="form-control">
                <p>Telefon (Direkt)</p>
                <input type="text" name="phoneDirect" class="form-control">
                <p>Mobile (Direkt)</p>
                <input type="text" name="mobileDirect" class="form-control">
                <p>Notizen</p>
                <textarea name="description" class="form-control" rows="3"></textarea>';
                echo $data;
    }
}

//Leeres Formular um neue Adresse hinzuzufügen
if(isset($_POST['new'])){
    $data= '';

        $data.= '<h4>Firmendaten</h4>
                <p>BKP*</p>
                <input type="text" name="bkp" class="form-control" maxlength="3">
                <p>Firma*</p>
                <input type="text" name="company" class="form-control">
                <p>Adresszeile 1*</p>
                <input type="text" name="addressline1" class="form-control">
                <p>Adresszeile 2</p>
                <input type="text" name="addressline2" class="form-control">
                <div class="row">
                    <div class="col-xs-2">
                        <p>PLZ*</p>
                        <input type="text" name="zip" class="form-control" maxlength="4">
                    </div>
                    <div class="col-xs-10">
                        <p>Ort*</p>
                        <input type="text" name="city" class="form-control">
                    </div>
                </div>
                <p>Land*</p>
                <select name="country" class="form-control">
                <option value="Schweiz" selected="selected">Schweiz</option>
                <option value="Deutschland">Deutschland</option>
                <option value="Österreich">Österreich</option>
                <option value="Lichtenstein">Lichtenstein</option>
                </select>
                <p>Email (Hauptadresse)*</p>
                <input type="email" name="email" class="form-control">
                <p>Telefon (Hauptnummer)*</p>
                <input type="text" name="phoneNumber" class="form-control">
                <p>Homepage*</p>
                <input type="text" name="homepage" class="form-control">

                <br /><br />
                <h4>Direkte Kontaktdaten</h4>
                <p>Ansprechpartner</p>
                <input type="text" name="projectCoordinator" class="form-control">
                <p>Email (Direkt)</p>
                <input type="text" name="emailDirect" class="form-control">
                <p>Telefon (Direkt)</p>
                <input type="text" name="phoneDirect" class="form-control">
                <p>Mobile (Direkt)</p>
                <input type="text" name="mobileDirect" class="form-control">
                <p>Notizen</p>
                <textarea name="description" class="form-control" rows="3"></textarea>';
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
                <input type="text" name="bkp" value="'.$bkp.'" readonly="readonly" class="form-control">
                <p>Firma*</p>
                <input type="text" name="company" value="'.$company.'" readonly="readonly" class="form-control">
                <p>Adresszeile 1*</p>
                <input type="text" name="addressline1" value="'.$addressline1.'" readonly="readonly" class="form-control">
                <p>Adresszeile 2</p>
                <input type="text" name="addressline2" value="'.$addressline2.'" readonly="readonly" class="form-control">
                <div class="row">
                    <div class="col-xs-2">
                        <p>PLZ*</p>
                        <input type="text" name="zip" value="'.$zip.'" readonly="readonly" class="form-control">
                    </div>
                    <div class="col-xs-10">
                        <p>Ort*</p>
                        <input type="text" name="city" value="'.$city.'" readonly="readonly" class="form-control">
                    </div>
                </div>
                <p>Land*</p>
                <select name="country" readonly="readonly" class="form-control">
                <option value="Schweiz" selected="selected">Schweiz</option>
                <option value="Deutschland">Deutschland</option>
                <option value="Österreich">Österreich</option>
                <option value="Lichtenstein">Lichtenstein</option>
                </select>
                <p>Email (Hauptadresse)*</p>
                <input type="email" name="email" value="'.$email.'" readonly="readonly" class="form-control">
                <p>Telefon (Hauptnummer)*</p>
                <input type="text" name="phoneNumber" value="'.$phoneNumber.'" readonly="readonly" class="form-control">
                <p>Homepage*</p>
                <input type="text" name="homepage" value="'.$homepage.'" readonly="readonly" class="form-control">

                <br /><br />
                <h4>Direkte Kontaktdaten</h4>
                <p>Ansprechpartner</p>
                <input type="text" name="projectCoordinator" value="'.$projectCoordinator.'" class="form-control">
                <p>Email (Direkt)</p>
                <input type="text" name="emailDirect" value="'.$emailDirect.'" class="form-control">
                <p>Telefon (Direkt)</p>
                <input type="text" name="phoneDirect" value="'.$phoneDirect.'" class="form-control">
                <p>Mobile (Direkt)</p>
                <input type="text" name="mobileDirect" value="'.$mobileNumber.'" class="form-control">
                <p>Notizen</p>
                <textarea name="description" class="form-control" rows="3">'.$description.'</textarea>';
                echo $data;
    }
}

//Formular mit Platzhaltern für das Editieren eines Chronik-Beitrags
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
                <label for="title">Titel*</label>
                <input id="title" type="text" name="title" value="'.$title.'" class="form-control">
                <label for ="content">Inhalt*</label>
                <textarea id="content" name="content" class="form-control" rows="8">'.$content.'</textarea>
                <label for="visibility">Sichtbarkeit*</label>
                <div id="visibility" class="radio near">
                <label class="near">
                <input type="radio" name="visible" value="1" '.$check1.'/>
                Nur Architekt
                </label>
                </div>
                <div class="radio">
                <label class="near">
                <input type="radio" name="visible" value="2" '.$check2.'/>
                Architekt und Bauherr
                </label>
                </div>
                <label for="upload">Bildupload</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/> <!-- Grössenbegrenzung (nicht Sicher) -->
                <input type="file" name="userfile"/>';

                echo $data;
    }

}

//Löschen eines Bildes aus der Galerie
if(isset($_POST['delIMG'])){
    $id= $_POST['delIMG'];
    
    $sql= getIMGPath($id);
    $result= mysqli_query($link, $sql);
    while($row= mysqli_fetch_array($result)){
        $imgS= $row['HashNameS'];
        $imgL= $row['HashNameL'];
    }

    if(unlink($imgS) && unlink($imgL)){
        $sql= deleteImgGallery($id);
        $status= mysqli_query($link, $sql);
        echo $status;
    }else{
        echo $status;
    }
    
}

//Ermittlung des Usertyps
if(isset($_POST['getUserTyp'])){
    //1= Architekt
    //2= Bauherr
    $usertyp=1;
    echo $usertyp;
}

//Formular mit Platzhaltern für das Editieren eines Chronik-Beitrags
if(isset($_POST['eventEdit'])){
    $data= '';

    //Post ID
    $id= filter_input(INPUT_POST, 'eventEdit', FILTER_SANITIZE_NUMBER_INT);
    $sql= selectEvent($id);
    $result= mysqli_query($link, $sql);

    while($row= mysqli_fetch_array($result)){
        $date= $row['Date'];
        $time= $row['Time'];
        $title= $row['Title'];
        $description= $row['Description'];
        $location= $row['Location'];

        $data.= '<input type="hidden" name="eventID" value="'.$id.'">
                <label for="date">Datum*</label><br/>
                <input id="date" type="date" name="date" value="'.$date.'"><br/>
                <label for="time">Zeit*</label><br/>
                <input id="time" type="time" name="time" value="'.$time.'"><br/>
                <label for="title">Titel*</label>
                <input id="title" type="text" name="title" class="form-control" value="'.$title.'">
                <label for="description">Bemerkung</label>
                <textarea id="description" name="description" class="form-control" rows="3" value="'.$description.'"></textarea>
                <label for="location">Ort*</label>
                <input id="location" type="text" name="location" class="form-control" value="'.$location.'">';

                echo $data;
    }

}