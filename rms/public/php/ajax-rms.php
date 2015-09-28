<?php
session_start();
require_once ('../../../library/public/database.inc.php');

$link= connectDB();

//Formular mit Platzhalterwerten einer Globalen Adresse
if(isset($_POST['showUserDetails'])){
    $data= '';
    //ID aus globaler Adressliste
    $id= filter_input(INPUT_POST, 'showUserDetails', FILTER_SANITIZE_NUMBER_INT);

    $sql= userData($id);
    $result= mysqli_query($link, $sql);

    while($row= mysqli_fetch_array($result)){
    	switch($row['Fk_IdUserType']){
    		case 1:
    			$typ= 'Administrator';
    			break;
    		case 2:
    			$typ= 'Architekt';
    			break;
    		case 3:
    			$typ= 'Bauherr';
    			break;
    		default:
    			$typ= 'Undefiniert';
    	}

    	switch($row['Active']){
    		case 1:
    			$status= '<a href="userverwaltung.php?id='.$id.'">aktivieren</a>';
    			break;
    		case 2:
    			$status= 'Aktivierungs Mail verschickt';
    			$blocked= 'warning';
    			break;
    		case 3:
    			$status= 'Aktiv';
    			$blocked= 'success';
    			break;
    		case 4:
    			$status= 'User gesperrt';
    			$blocked= 'danger';
    			break;
    		default:
    			$status= 'Undefiniert';
    	}

    	$fn= $row['Firstname'];
    	$ln= $row['Lastname'];
    	$comp= $row['Company'];
    	$adr1= $row['Addressline1'];
    	$adr2= $row['Addressline2'];
    	$zip= $row['ZIP'];
    	$city= $row['City'];
    	$country= $row['Country'];
    	$email= $row['Email'];
    	$phon= $row['PhoneNumber'];
    	$mob= $row['MobileNumber'];
    	$lastT= $row['LastLoginTime'];
    	$lastD= $row['LastLoginDate'];
        $usertyp= $row['Fk_IdUserType'];

    	if($usertyp==2){
    		$count=0;
    		$sql2= getProjectsByArch($id);
    		$result2= mysqli_query($link, $sql2);
    		while($row2= mysqli_fetch_array($result2)){
    			//Projekte in array speichern
    			$count++;
    		}
    	}else if($usertyp==3){
    		$count=1;
    	}else if($usertyp==1){
            $count=0;
        }



    	$data.= '<input type="hidden" name="status" value="'.$row['Active'].'">
                <input type="hidden" name="userID" value="'.$id.'">
                <input type="hidden" name="userTyp" value="'.$usertyp.'">
                <h4>Status</h4>
    			<table class="table table-hover">
    			<tr>
    				<td class="col-xs-3">Usertyp</td>
    				<td class="col-xs-9">'.$typ.'</td>
    			</tr>
    			<tr>
    				<td>User ID</td>
    				<td>'.$id.'</td>
    			</tr>
    			<tr>
    				<td>Letzter Login</td>
    				<td>'.$lastD.', '.$lastT.'</td>
    			</tr>
    			<tr class="'.$blocked.'">
    				<td>Status</td>
    				<td>'.$status.'</td>
    			</tr>
    			</table>

    			<h4>Kontaktdaten</h4>
    			<table class="table table-hover">
    			<tr>
    				<td class="col-xs-3">Vorname</td>
    				<td class="col-xs-9">'.$fn.'</td>
    			</tr>
    			<tr>
    				<td>Nachname</td>
    				<td>'.$ln.'</td>
    			</tr>
    			<tr>
    				<td>Firma</td>
    				<td>'.$comp.'</td>
    			</tr>
    			<tr>
    				<td>Adresse 1</td>
    				<td>'.$adr1.'</td>
    			</tr>
    			<tr>
    				<td>Adresse 2</td>
    				<td>'.$adr2.'</td>
    			</tr>
    			<tr>
    				<td>PLZ / Ort</td>
    				<td>'.$zip.' / '.$city.'</td>
    			</tr>
    			<tr>
    				<td>Land</td>
    				<td>'.$country.'</td>
    			</tr>
    			<tr>
    				<td>Email</td>
    				<td><a href="mailto:'.$email.'">'.$email.'</a></td>
    			</tr>
    			<tr>
    				<td>Telefon</td>
    				<td>'.$phon.'</td>
    			</tr>
    			<tr>
    				<td>Mobile</td>
    				<td>'.$mob.'</td>
    			</tr>
				</table>
				<h4>Projektinformationen</h4>
				<table class="table table-hover">
				<tr>
    				<td class="col-xs-3">Anzahl Projekte</td>
    				<td class="col-xs-9">'.$count.'</td>
    			</tr>
				</table>';
                echo $data;
    }
}

//Formular mit Platzhaltern um einen bestehenden Projekt-Adress-Eintrag zu bearbeiten
if(isset($_POST['showAddressDetails'])){
    $data= '';

    //Projektadresse ID
    $id= filter_input(INPUT_POST, 'showAddressDetails', FILTER_SANITIZE_NUMBER_INT);

    $sql= getGlobalAddress($id);
    $result= mysqli_query($link, $sql);
    while($row= mysqli_fetch_array($result)){
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
        $count=0;
        
        $sql2=countProjectAddress($id);
        $result2=  mysqli_query($link, $sql2);
        $row2=mysqli_fetch_array($result2);
        $count=$row2['COUNT(*)'];


        $data.= '<input type="hidden" name="idGlobalAddress" value="'.$id.'">
                <h4>Firmendaten</h4>
                <label for="1">BKP*</label>
                <input id="1" type="text" name="bkp" value="'.$bkp.'" class="form-control">
                <label for="2">Firma*</label>
                <input id="2" type="text" name="company" value="'.$company.'" class="form-control">
                <label for="3">Adresszeile 1*</label>
                <input id="3" type="text" name="addressline1" value="'.$addressline1.'" class="form-control">
                <label for="4">Adresszeile 2</label>
                <input id="4" type="text" name="addressline2" value="'.$addressline2.'" class="form-control">
                <div class="row">
                    <div class="col-xs-2">
                        <label for="5">PLZ*</label>
                        <input id="5" type="text" name="zip" value="'.$zip.'" class="form-control">
                    </div>
                    <div class="col-xs-10">
                        <label for="6">Ort*</label>
                        <input id="6" type="text" name="city" value="'.$city.'" class="form-control">
                    </div>
                </div>
                <label for="7">Land*</label>
                <select id="7" name="country" class="form-control">
                    <option value="Schweiz" selected="selected">Schweiz</option>
                    <option value="Deutschland">Deutschland</option>
                    <option value="Österreich">Österreich</option>
                    <option value="Lichtenstein">Lichtenstein</option>
                </select>
                <label for="8">Email (Hauptadresse)*</label>
                <input id="8" type="email" name="email" value="'.$email.'" class="form-control">
                <label for="9">Telefon (Hauptnummer)*</label>
                <input id="9" type="text" name="phoneNumber" value="'.$phoneNumber.'" class="form-control">
                <label for="10">Homepage*</label>
                <input id="10" type="text" name="homepage" value="'.$homepage.'" class="form-control"><br/><br/>
                <h4>Statistik</h4>
                <label>Anzahl Verwendungen in Projekten (Aktiv)</label>
                <p>'.$count.' mal</p>';
                echo $data;
    }
}