<?php
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');

$projectID=2;

$idGLobal;

$link= connectDB();
$sql= allProjectAddress($projectID);
$result = mysqli_query($link, $sql);

$sql2=allGlobalAddress();
$result2= mysqli_query($link, $sql2);
        
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
    $phoneNumber = filter_input(INPUT_POST, 'phoneNumber', FILTER_SANITIZE_STRING);
    $homepage = filter_input(INPUT_POST, 'homepage', FILTER_SANITIZE_STRING);
    $projectCoordinator = filter_input(INPUT_POST, 'projectCoordinator', FILTER_SANITIZE_STRING);
    $phoneDirect = filter_input(INPUT_POST, 'phoneDirect', FILTER_SANITIZE_STRING);
    $mobileDirect = filter_input(INPUT_POST, 'mobileDirect', FILTER_SANITIZE_STRING);
    $emailDirect = filter_input(INPUT_POST, 'emailDirect', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    
    // Fehler im Eingabefeld?
    if (empty($bkp) || strlen($bkp) > 3) {
        $errorBKP = true;
        $error = true;
        echo $bkp;
    }
    
    // Fehler im Eingabefeld?
    if (empty($company) || strlen($company) < 4) {
        $errorCompany = true;
        $error = true;   
        echo'2';
    }
    
    // Fehler im Eingabefeld?
    if (empty($addressline1) || strlen($addressline1) < 5) {
        $errorAddressline1 = true;
        $error = true;
        echo '3';
    }
    
    // Fehler im Eingabefeld?
    if (empty($zip) || strlen($zip) < 4) {
        $errorZIP = true;
        $error = true;
        echo'4';
    }
    
    // Fehler im Eingabefeld?
    if (empty($city) || strlen($city) < 4) {
        $errorCity = true;
        $error = true;
        echo'5';
    }
    
    // Fehler im Eingabefeld?
    if (empty($country)) {
        $errorCountry = true;
        $error = true;
        echo'6';
    }
    
    // Mailadresse korrekt?
    if (!checkMailFormat($email)) {
        $errorEmail = true;
        $error = true;
        echo'7';
    }
    
    // Fehler im Eingabefeld?
    if (empty($phoneNumber) || strlen($phoneNumber) < 10) {
        $errorPhoneNumber = true;
        $error = true;
        echo'8';
    }
    
    // Fehler im Eingabefeld?
    if (empty($homepage) || strlen($homepage) < 10) {
        $errorHomepage = true;
        $error = true;
        echo'9';
    }
    
    //Datenbankbefehle wenn kein Error vorhanden ist.
    if(!$error){
        //Überprüfe ob eine id übergeben wurde
        if(!isset($_POST['idGlobalAddress']) && !isset($_POST['idProjectAddress'])){
            if(checkGlobalAddress($company)){
                //Insert Into Globale Adressliste
                $sql= newGlobalAddress($bkp, $company, $addressline1, $addressline2, $zip,
                $city, $country, $email, $phoneNumber, $homepage);
                $statusGlobal = mysqli_query($link, $sql);

                //Hole ID von Eintrag in globalen Adressliste
                $sql= getIdGlobal($company, $addressline1);
                $resultID= mysqli_query($link, $sql);
                while($row=  mysqli_fetch_array($resultID)){
                    $idGlobal= $row['IdGlobalAddress'];
                } 
            }else{
                $statusGlobal=false;
                echo'<p>Firma exisitert bereits in globaler DB</p>';
            }
        }else if(isset($_POST['idGlobalAddress'])){      
            $idGlobal = filter_input(INPUT_POST, 'idGlobalAddress', FILTER_SANITIZE_NUMBER_INT);
            $statusGlobal=true;
        }else{
            $statusGlobal=true;
        }
        


        if(isset($_POST['idProjectAddress'])){
            $idProjectAddress= filter_input(INPUT_POST, 'idProjectAddress', FILTER_SANITIZE_NUMBER_INT);
            //Update Into Projekt- Adressliste (Eintrag wird bearbeitet)
            $sql= updateProjectAddress($idProjectAddress, $projectCoordinator, $phoneDirect, $mobileDirect,
            $emailDirect, $description);
            $statusProject= mysqli_query($link, $sql);
        }else{
            //Insert Into Projekt- Adressliste (Neuer Eintrag wird erstellt)
            $sql= newProjectAddress($projectID, $idGlobal, $projectCoordinator, $phoneDirect, $mobileDirect,
            $emailDirect, $description);
            $statusProject= mysqli_query($link, $sql);
        }
            
        
        
        
    
        if($statusGlobal==true && $statusProject==true){
            header('Location: addresslist.php');
        }else{
            echo'<p>Error1</p>';
        }
    }else{
        echo'<p>Error2</p>';
    }   
}

if(isset($_POST['delete'])){
    $idProjectAddress= filter_input(INPUT_POST, 'idProjectAddress', FILTER_SANITIZE_NUMBER_INT);

    $sql= deleteProjectAddress($idProjectAddress);
    $resultDel = mysqli_query($link, $sql);
    if($resultDel){
        header('Location: addresslist.php');
    }else{
        echo'<p>Löschen fehlgeschlagen</p>';
    }
}

?>

<html>
    <head>
        <title>Adressliste Architekt</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.8/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="../css/style.css">
        
        
        
        
        
    </head>
    <body>
 
<!--Lightboxen (Modals)-->
<div class="container">

  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">+ Hinzufügen</button>

  <!-- Modal Global-->
  <div class="modal" id="myModal" role="dialog">
      <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
          <form action="addresslist.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Handwerker hinzufügen</h4>
        </div>
              <div class="modal-body">
            
            <div id="input_container">
                
                <?php

                    echo'<table class="table table-striped table-bordered">';
                    echo'<thead>';
                    echo'<tr>';
                    echo'<th>Firma</th>';
                    echo'<th>BKP</th>';
                    echo'<th>PLZ</th>';
                    echo'<th>Ort</th>';
                    echo'<th></th>';
                    echo'</tr>';
                    echo'</thead>';
                    echo'<tbody>';

                    while($row= mysqli_fetch_array($result2)){
                        echo'<tr>';
                        echo'<td><a href="http://'.$row['Homepage'].'/" target="_blank">'.$row['Company'].'</a></td>';
                        echo'<td>'.$row['BKP'].'</td>';
                        echo'<td>'.$row['ZIP'].'</td>';
                        echo'<td>'.$row['City'].'</td>';
                        echo'<td><button type="button" class="btn btn-default btn_add" data-toggle="modal" data-target="#modalSearch" data-dismiss="modal" value="'.$row['IdGlobalAddress'].'">hinzufügen</button></td>';
                        echo'</tr>';
                    }
                    echo'</tbody>';
                    echo'</table>';
                    ?>
                
                
                
                <button type="button" class="btn btn-default btn_new" data-toggle="modal" data-target="#modalSearch" data-dismiss="modal">Neue Adresse</button>
            </div>       
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
        </div>
        </form>

      </div>
      
    </div>
  </div>
  
  
  
  <!-- Modal Hinzufügen-->
  <div class="modal" id="modalSearch" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
          <form action="addresslist.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#myModal">&times;</button>
          <h4 class="modal-title">Handwerker hinzufügen</h4>
        </div>
              <div class="modal-body">
            
            <div id="newAddress">
                
                <!-- Platzhalter für Inhalt aus Ajax Methode (ajax.php) -->
                
            </div>     
        </div>
        <div class="modal-footer">
            <input type="submit" name="submit" value="Speichern" class="btn btn-default">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#myModal">Schliessen</button>
        </div>
        </form>

      </div>
      
    </div>
  </div>
  <!-- Modal End -->
  
  
   <!-- Modal Edit -->
  <div class="modal" id="modalEdit" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
          <form action="addresslist.php" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" data-toggle="modal">&times;</button>
          <h4 class="modal-title">Handwerker bearbeiten</h4>
        </div>
              <div class="modal-body">
            
            <div id="editAddress">
                
                <!-- Platzhalter für Inhalt aus Ajax Methode (ajax.php) -->
                
            </div>     
        </div>
        <div class="modal-footer">
            <input type="submit" name="submit" value="Speichern" class="btn btn-default">
            <input type="submit" name="delete" value="Löschen" class="btn btn-default">
          <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal">Schliessen</button>
        </div>
        </form>

      </div>
      
    </div>
  </div>
  <!-- Modal End -->
  
</div>


<?php

echo'<table class="table table-striped table-bordered">';
echo'<thead>';
echo'<tr>';
echo'<th>Firma</th>';
echo'<th>BKP</th>';
echo'<th>Kontaktperson</th>';
echo'<th>Tel. Direkt</th>';
echo'<th>Mobil</th>';
echo'<th>Email Direkt</th>';
echo'<th></th>';
echo'</tr>';
echo'</thead>';
echo'<tbody>';

while($row= mysqli_fetch_array($result)){
    echo'<tr>';
    echo'<td><a href="http://'.$row['Homepage'].'/" target="_blank">'.$row['Company'].'</a></td>';
    echo'<td>'.$row['BKP'].'</td>';
    echo'<td>'.$row['ProjectCoordinator'].'</td>';
    echo'<td><a href="tel:'.$row['PhoneDirect'].'">'.$row['PhoneDirect'].'</td>';
    echo'<td><a href="tel:'.$row['MobileNumber'].'">'.$row['MobileNumber'].'</td>';
    echo'<td><a href="mailto:'.$row['EmailDirect'].'">'.$row['EmailDirect'].'</td>';
    echo'<td><button type="button" class="btn btn-default btn_edit" data-toggle="modal" data-target="#modalEdit" value="'.$row['IdProjectAddress'].'">bearbeiten</button></td>';
    echo'</tr>';
}
echo'</tbody>';
echo'</table>';

?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="../js/script.js"></script>
<!--<script src="../../../productsite/public/bootstrap/js/bootstrap.min.js"></script>-->

    </body>
</html>
