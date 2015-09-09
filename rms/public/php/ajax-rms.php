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

    	if($row['Fk_IdUserType']==2){
    		$count=0;
    		$sql2= getProjectsByArch($id);
    		$result2= mysqli_query($link, $sql2);
    		while($row2= mysqli_fetch_array($result2)){
    			//Projekte in array speichern
    			$count++;
    		}
    	}else{
    		$count=1;
    	}



    	$data.= '<input type="hidden" name="status" value="'.$row['Active'].'">
                <input type="hidden" name="userID" value="'.$id.'">
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
    				<td>'.$email.'</td>
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