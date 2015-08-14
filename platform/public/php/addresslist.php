<?php
require_once ('../../../library/public/database.inc.php');

$projectID=2;

$link= connectDB();
$sql= allProjectAddress($projectID);
$result = mysqli_query($link, $sql);



if(isset($_POST['submit'])){
    $bkp= $_POST['bkp'];
    $company= $_POST['company'];
    $addressline1= $_POST['addressline1'];
    if(isset($_POST['addressline2'])){
        $addressline2= $_POST['addressline2'];
    }    
    $zip= $_POST['zip'];
    $city= $_POST['city'];
    $country= $_POST['country'];
    $email= $_POST['email'];
    $phoneNumber= $_POST['phoneNumber'];
    $homepage= $_POST['homepage'];
    if(isset($_POST['projectCoordinator'])){
        $projectCoordinator= $_POST['projectCoordinator'];
    }
    if(isset($_POST['phoneDirect'])){
        $phoneDirect= $_POST['phoneDirect'];
    }
    if(isset($_POST['mobileDirect'])){
        $mobileDirect= $_POST['mobileDirect'];
    }
    if(isset($_POST['emailDirect'])){
        $emailDirect= $_POST['emailDirect'];
    }
    if(isset($_POST['description'])){
        $description= $_POST['description'];
    }
    $link=connectDB();
    //Insert Into Globale Adressliste
    $sql= newGlobalAddress($bkp, $company, $addressline1, $addressline2, $zip,
            $city, $country, $email, $phoneNumber, $homepage);
    $statusGlobal = mysqli_query($link, $sql);
    $link=connectDB();
    //Hole ID von Eintrag in globalen Adressliste
    $sql= getIdGlobal($company, $addressline1);
    $resultID= mysqli_query($link, $sql);
    $row=  mysqli_fetch_array($resultID);
    $idGlobal= $row['IdGlobalAddress'];
    $link=connectDB();
    //Insert Into Projekt- Adressliste
    $sql= newProjectAddress($projectID, $IDGlobal, $projectCoordinator, $phoneDirect, $mobileDirect,
            $emailDirect, $description);
    $statusProject= mysqli_query($link, $sql);
    
    
    
}
?>

<html>
    <head>
        <title>Adressliste Architekt</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        
    </head>
    <body>
        


<?php

echo'<table border="1" width="800">';
echo'<tr>';
echo'<th>Firma</th>';
echo'<th>BKP</th>';
echo'<th>Kontaktperson</th>';
echo'<th>Tel. Direkt</th>';
echo'<th>Mobil</th>';
echo'<th>Email Direkt</th>';
echo'</tr>';

while($row= mysqli_fetch_array($result)){
    echo'<tr>';
    echo'<td>'.$row['Company'].'</td>';
    echo'<td>'.$row['BKP'].'</td>';
    echo'<td>'.$row['ProjectCoordinator'].'</td>';
    echo'<td>'.$row['PhoneDirect'].'</td>';
    echo'<td>'.$row['MobileNumber'].'</td>';
    echo'<td>'.$row['EmailDirect'].'</td>';
    echo'</tr>';
}
echo'</table>';
?>
        
<div class="container">

  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">+ Hinzufügen</button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
          <form action="addresslist.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handwerker hinzufügen</h4>
        </div>
        <div class="modal-body">
            
                <p>BKP*</p>
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
                <input type="textarea" name="description">
            
            
            
            
        </div>
        <div class="modal-footer">
            <input type="submit" name="submit" value="Speichern" class="btn btn-default">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>

      </div>
      
    </div>
  </div>
  
</div>


<script src="../../../productsite/public/js/jquery-1.11.1.min.js"></script>
<script src="../../../productsite/public/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>