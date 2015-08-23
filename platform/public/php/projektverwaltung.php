<?php

/*
 *  Programmpunkt 1.1 Projektverwaltung
 */

//Session starten oder wiederaufnehmen
 session_start();
    
//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

//generiert ein zufälliges Passwort
function generatePassword(){

   
        $alpha = "abcdefghikmnopqrstuvqxyz";
       
        $alpha .= "23456789";
       
        $alpha .= "ABCDEFGHKLMNOPQRSTUVWXYZ";
       
        $alpha .= "!$%&/()=";

        srand ((double)microtime()*1000000);
       
        for($index = 0; $index < 7; $index++)
        {
                   $password .= substr($alpha,(rand()%(strlen ($alpha))), 1);
        }
        return $password;
   
}






if(!isset($_SESSION['IdUser']) || $_SESSION['UserType'] != 2) {
    header('Location: denied.php');
}
//Holt Architekten User Daten
$id = $_SESSION['IdUser'];

$link = connectDB();
$sql = 'SELECT Firstname, Lastname, Company, SessionId FROM User WHERE IdUser ='.$id;
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$fn = $row['Firstname'];
$SID1 = $row['SessionId'];
echo $fn;


$sql2 = 'SELECT p.IdProject, p.ProjectNumber, p.Title, p.Addressline1, p.Addressline2, p.ZIP, p.City,
        p.Country, p.Description, p.Picture, u.IdUser, u.Firstname, u.Lastname FROM project as p JOIN user
        as u on p.Fk_IdBauherr = u.IdUser WHERE Fk_IdArchitect = '.$id;

$result2 = mysqli_query($link, $sql2);
$row2 = mysqli_fetch_array($result2);



if(isset($_POST['submit'])) {
    //Projektdaten in Variablen Speichern
     $projectNumb = filter_input(INPUT_POST, 'ProjectNumber', FILTER_SANITIZE_STRING);
     $title = filter_input(INPUT_POST, 'Title', FILTER_SANITIZE_STRING);
     $addressline1 = filter_input(INPUT_POST, 'Addressline1', FILTER_SANITIZE_STRING);
     $zip = filter_input(INPUT_POST, 'ZIP', FILTER_SANITIZE_STRING);
     $city = filter_input(INPUT_POST, 'City', FILTER_SANITIZE_STRING);
     $description = filter_input(INPUT_POST, 'Description', FILTER_SANITIZE_STRING);
     //Bauherrendaten in Variablen Speichern
     $fnBh = filter_input(INPUT_POST, 'BhFirstname', FILTER_SANITIZE_STRING);
     $lnBh = filter_input(INPUT_POST, 'BhLastname', FILTER_SANITIZE_STRING);
     $BhAddressline1 = filter_input(INPUT_POST, 'BhAddressline1', FILTER_SANITIZE_STRING);
     $BhZIP = filter_input(INPUT_POST, 'BhZIP', FILTER_SANITIZE_STRING);
     $BhCity = filter_input(INPUT_POST, 'BhCity', FILTER_SANITIZE_STRING);
     $BhPhNu = filter_input(INPUT_POST, 'BhPhoneNumber', FILTER_SANITIZE_STRING);
     $BhEmail = filter_input(INPUT_POST, 'BhEmail', FILTER_SANITIZE_STRING);
     
     //PW erstellung für Bauherr
     $BhPw = generatePassword();
     $pwHash = hash('sha256', $BhPW);
     
     //Fügt Bauherr der Datenbank hinzu
     $sql= "INSERT INTO user (Firstname, Lastname, Addressline1, ZIP, City, Email, PhoneNumber,
             Password, Fk_IdUserType, Active) VALUES
             ('$fnBh', '$lnBh', '$BhAddressline1', '$BhZIP', '$BhCity', '$BhEmail', '$BhPhNu', '$pwHash', 3, 3)";
     $status = mysqli_query($link, $sql);
     //Holt ID des zuvor hinzugefügten Bauherren um danach die Projektinformationen abzuspeichern
     $sql = 'SELECT IdUser FROM user WHERE Password="'.$pwHash.'"';   
     $result = mysqli_query($link, $sql);
     $row3 = mysqli_fetch_array($result);
     $BhId = $row3['IdUser'];
     
     
     //Erstellt das Projekt mit allen benötigten Daten
     $sql = "INSERT INTO project (Fk_IdArchitect, Fk_IdBauherr, ProjectNumber, Title, Addressline1, ZIP, City, Description)
             VALUES ('$id', '$BhId', '$projectNumb', '$title', '$addressline1' ,'$zip' ,'$city' ,'$description')";
     $result = mysqli_query($link, $sql);
     
     
   
     //Verzeichnis erstellung für das Projekt
     $sql = 'SELECT IdProject FROM project WHERE ProjectNumber ='.$projectNumb;
     $result = mysqli_query($link, $sql);
     $row4 = mysqli_fetch_array($result);
     $proId = $row4['IdProject'];

 
     $dir = mkdir('../../../architects/architekt'.$id.'/project'.$proId);
}

?>

<html>
    <head>
        <title>Projekt verwaltung</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        
        
        
        
        
    </head>
    <body>
        <h4>Projekte:</h4>
        
        <div class="container">

    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#newPost">+ hinzufügen</button>

    <!-- Modal Global-->
    <div class="modal" id="newPost" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="projektverwaltung.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Projekt</h4>
                    </div>
                        <div class="modal-body">
                            <div id="input_container">

                                <p>Projektnummer*</p>
                                <input type="text" name="ProjectNumber">
                                <p>Projektbezeichnung</p>
                                <input type="text" name="Title">
                                <p>Strasse</p>
                                <input type="text" name="Addressline1">
                                <p>PLZ*/Ort*</p>
                                <input type="text" name="ZIP"><input type="text" name="City">                           
                                <p>Projektbeschrieb</p>
                                <textarea name="Description"></textarea>
                                <p>Projektbild</p>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/> <!-- Grössenbegrenzung (nicht Sicher) -->
                                <input type="file" name="Picture"/>
                                <h4>Daten Bauherr</h4>
                                <p>Vorname</p>
                                <input type="text" name="BhFirstname">
                                <p>Nachname</p>
                                <input type="text" name="BhLastname">
                                <p>Strasse</p>
                                <input type="text" name="BhAddressline1">
                                <p>PLZ/Ort</p>
                                <input type="text" name="BhZIP"><input type="text" name="BhCity">
                                <p>Telefonnummer</p>
                                <input type="text" name="BhPhoneNumber">
                                <p>Email</p>
                                <input type="text" name="BhEmail">
                                
                                   

                            </div>       
                        </div>
                    <div class="modal-footer">
                        <input type="submit" name="submit" value="Projekt Erstellen" class="btn btn-default"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    </div>
              </form>

            </div>

        </div>
    </div>
        </div>
         
         
  <!-- JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
<script src="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/imgLiquid-min.js"></script>
<script src="../js/script.js"></script>

    </body>
</html>