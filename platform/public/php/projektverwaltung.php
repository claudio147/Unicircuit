<?php

/*
 *  Programmpunkt 1.1 Projektverwaltung
 */

//Session starten oder wiederaufnehmen
 session_start();
    
 if(!isset($_SESSION['IdUser']) || $_SESSION['UserType'] != 2) {
    header('Location: denied.php');
}

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/security.inc.php');
require_once ('../../../library/public/mail.inc.php');





//Holt Architekten User Daten
$id = $_SESSION['IdUser'];

$link = connectDB();




//Erstellung eines neuen Projektes
if(isset($_POST['submit'])) {
    //Projektdaten in Variablen Speichern
     $projectNumb = filter_input(INPUT_POST, 'ProjectNumber', FILTER_SANITIZE_STRING);
     $title = filter_input(INPUT_POST, 'Title', FILTER_SANITIZE_STRING);
     $addressline1 = filter_input(INPUT_POST, 'Addressline1', FILTER_SANITIZE_STRING);
     $addressline2 = filter_input(INPUT_POST, 'Addressline2', FILTER_SANITIZE_STRING);
     $zip = filter_input(INPUT_POST, 'ZIP', FILTER_SANITIZE_STRING);
     $city = filter_input(INPUT_POST, 'City', FILTER_SANITIZE_STRING);
     $country = filter_input(INPUT_POST, 'Country', FILTER_SANITIZE_STRING);
     $description = filter_input(INPUT_POST, 'Description', FILTER_SANITIZE_STRING);
     
     //Bauherrendaten in Variablen Speichern
     $bhFn = filter_input(INPUT_POST, 'BhFirstname', FILTER_SANITIZE_STRING);
     $bhLn = filter_input(INPUT_POST, 'BhLastname', FILTER_SANITIZE_STRING);
     $bhAddressline1 = filter_input(INPUT_POST, 'BhAddressline1', FILTER_SANITIZE_STRING);
     $bhAddressline2 = filter_input(INPUT_POST, 'BhAddressline2', FILTER_SANITIZE_STRING);
     $bhZIP = filter_input(INPUT_POST, 'BhZIP', FILTER_SANITIZE_STRING);
     $bhCity = filter_input(INPUT_POST, 'BhCity', FILTER_SANITIZE_STRING);
     $bhCountry = filter_input(INPUT_POST, 'BhCountry', FILTER_SANITIZE_STRING);
     $bhPhNu = filter_input(INPUT_POST, 'BhPhoneNumber', FILTER_SANITIZE_STRING);
     $bhMoNu = filter_input(INPUT_POST, 'BhMobileNumber', FILTER_SANITIZE_STRING);
     $bhEmail = filter_input(INPUT_POST, 'BhEmail', FILTER_SANITIZE_STRING);
     
     //PW erstellung für Bauherr
     $BhPw = generatePassword();
     // Verschickt Mail an Bauherren
     $mail = createBauhMail($bhEmail, $bhFn, $bhLn, $BhPw, $title);
     
     //macht weiter wenn Mail geschickt wurde
     if($mail == TRUE) {
     
     //Verschlüsselt das Passwort
     $pwHash = hash('sha256',$BhPw);
     
     //Fügt Bauherr der Datenbank hinzu
     $sql = createBauherr($bhFn, $bhLn, $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhEmail, $bhPhNu, $bhMoNu, $pwHash);
     $status = mysqli_query($link, $sql);
     
     //Holt ID des zuvor hinzugefügten Bauherren um danach die Projektinformationen abzuspeichern
     $sql = getIdBauherr($pwHash);   
     $result = mysqli_query($link, $sql);
     $row3 = mysqli_fetch_array($result);
     $bhId = $row3['IdUser'];
     
     
     //Erstellt das Projekt mit allen benötigten Daten
     $sql = createProject($id, $bhId, $projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description);
     $result = mysqli_query($link, $sql);
     
     
   
     //Verzeichnis erstellung für das Projekt
     $sql = getIdProject($projectNumb, $bhId);
     $result = mysqli_query($link, $sql);
     $row4 = mysqli_fetch_array($result);
     $proId = $row4['IdProject'];
     $dir = mkdir('../architects/architekt'.$id.'/project'.$proId);
     
     $uploaddir = '../architects/architekt'.$id.'/project'.$proId.'/' ;
     
     //Bildupload für neues Projekt
    if(!empty($_FILES['userfile']['name'])){

    //Array mit Statusmeldungen
    $errorstatus= array('Alles OK', 'Zeitüberschreitung', 'Grössenüberschreitung',
        'Nicht vollständig', 'Keine Datei hochgeladen');
    

    $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
    $extension= strrchr($_FILES['userfile']['name'],'.');
    $file= $filename.$extension;
    
    //Dateipfad mit Dateinamen zusammensetzen
    $uploadfile= $uploaddir.basename($file);
        if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
            //echo'<p>Datei wurde erfolgreich hochgeladen.</p>';
            $orgname= $_FILES['userfile']['name'];


            $sql= addPicToProject($uploadfile, $proId);
            $status= mysqli_query($link, $sql);

            //Errorcode der Übertragung abfragen
            $code= $_FILES['userfile']['error'];

            //Übersetzer Error-Code in Worten (sh. Array) ausgeben
            
        }else{
            echo'<p>Datei konnte nicht hochgeladen werden!</p>';
            
        }
    }else{
       // $uploaddirAlt= '../img/';
       // $file= 'placeholder.png';
       // $orgname= 'placeholder.png';
        $uploadfile = '../img/placeholder.png' ;
        $sql= addPicToProject($uploadfile, $proId);
        $status= mysqli_query($link, $sql);
        if(!$status){
            echo'<p>Fehlgeschlagen</p>';
        }
    }
}
}

//Anpassung eines Projektes
if(isset($_POST['edit'])) {
    //Projektdaten in Variablen Speichern
     $proId2 = filter_input(INPUT_POST, 'postID', FILTER_SANITIZE_STRING);
     $projectNumb = filter_input(INPUT_POST, 'ProjectNumber', FILTER_SANITIZE_STRING);
     $title = filter_input(INPUT_POST, 'Title', FILTER_SANITIZE_STRING);
     $addressline1 = filter_input(INPUT_POST, 'Addressline1', FILTER_SANITIZE_STRING);
     $addressline2 = filter_input(INPUT_POST, 'Addressline2', FILTER_SANITIZE_STRING);
     $zip = filter_input(INPUT_POST, 'ZIP', FILTER_SANITIZE_STRING);
     $city = filter_input(INPUT_POST, 'City', FILTER_SANITIZE_STRING);
     $country = filter_input(INPUT_POST, 'Country', FILTER_SANITIZE_STRING);
     $description = filter_input(INPUT_POST, 'Description', FILTER_SANITIZE_STRING);
     
     //Bauherrendaten in Variablen Speichern
     $bhFn = filter_input(INPUT_POST, 'BhFirstname', FILTER_SANITIZE_STRING);
     $bhLn = filter_input(INPUT_POST, 'BhLastname', FILTER_SANITIZE_STRING);
     $bhAddressline1 = filter_input(INPUT_POST, 'BhAddressline1', FILTER_SANITIZE_STRING);
     $bhAddressline2 = filter_input(INPUT_POST, 'BhAddressline2', FILTER_SANITIZE_STRING);
     $bhZIP = filter_input(INPUT_POST, 'BhZIP', FILTER_SANITIZE_STRING);
     $bhCity = filter_input(INPUT_POST, 'BhCity', FILTER_SANITIZE_STRING);
     $bhCountry = filter_input(INPUT_POST, 'BhCountry', FILTER_SANITIZE_STRING);
     $bhPhNu = filter_input(INPUT_POST, 'BhPhoneNumber', FILTER_SANITIZE_STRING);
     $bhMoNu = filter_input(INPUT_POST, 'BhMobileNumber', FILTER_SANITIZE_STRING);
     $bhEmail = filter_input(INPUT_POST, 'BhEmail', FILTER_SANITIZE_STRING);

    //Update wenn auch ein neues Bild hochgeladen wurde
     if(!empty($_FILES['userfile']['name'])){

    //Array mit Statusmeldungen
    $errorstatus= array('Alles OK', 'Zeitüberschreitung', 'Grössenüberschreitung',
        'Nicht vollständig', 'Keine Datei hochgeladen');
    
    $uploaddir = '../architects/architekt'.$id.'/project'.$proId2.'/' ;
    $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
    $extension= strrchr($_FILES['userfile']['name'],'.');
    $file= $filename.$extension;
        
    //Dateipfad mit Dateinamen zusammensetzen
    $uploadfile= $uploaddir.basename($file);
        if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
            //echo'<p>Datei wurde erfolgreich hochgeladen.</p>';
            $orgname= $_FILES['userfile']['name'];

                $sql= updateProjectWithPic($projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description, $uploadfile, $bhFn, $bhLn,
        $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhPhNu, $bhMoNu, $bhEmail, $proId2);
            
            $status= mysqli_query($link, $sql);

            //Errorcode der Übertragung abfragen
            $code= $_FILES['userfile']['error'];

            //Übersetzer Error-Code in Worten (sh. Array) ausgeben
            
        }else{
            echo'<p>Datei konnte nicht hochgeladen werden!</p>';
            
        }
    } else {
        //Projekt Upload wenn kein Bild hochgeladen wurde.
        $sql = updateProjectWithout($projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description, $bhFn, $bhLn,
        $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhPhNu, $bhMoNu, $bhEmail, $proId2);
        
        $status = mysqli_query($link, $sql);
    }
}

//Archivierung eines Projektes
if(isset($_POST['store'])) {
     $store = filter_input(INPUT_POST, 'store', FILTER_SANITIZE_STRING); 
     
     if(!empty($_POST['postID'])) {
         $proId2 = filter_input(INPUT_POST, 'postID', FILTER_SANITIZE_STRING);
         
         $sql = storeProject($proId2);
         $status2 = mysqli_query($link, $sql);
         if(isset($status2)) {
             echo 'Das Projekt wurde in Ihr Archiv verschoben, und der dazugehörige Bauherr wurde Deaktiviert.';
         }
         

         
         
     }
}

//geht zum gewählten Projekt auf die Index Seite
if(isset($_POST['goto'])) {
    $idProject = filter_input(INPUT_POST, 'goto', FILTER_SANITIZE_STRING);
    
     $_SESSION['IdProject'] = $idProject;
     
     if(!empty($idProject)) {
          header('Location: index.php');
     } else {
         header('Location: 404.php');
     }
}

//Reset des Passwortes des Bauherren
if(isset($_POST['pwReset'])) {
    $IdProject = filter_input(INPUT_POST, 'postID', FILTER_SANITIZE_STRING);
    
    $bhFn = filter_input(INPUT_POST, 'BhFirstname', FILTER_SANITIZE_STRING);
    $bhLn = filter_input(INPUT_POST, 'BhLastname', FILTER_SANITIZE_STRING);
    $bhEmail = filter_input(INPUT_POST, 'BhEmail', FILTER_SANITIZE_STRING);
    $title = filter_input(INPUT_POST, 'Title', FILTER_SANITIZE_STRING);
    
    //PW erstellung für Bauherr
     $BhPw = generatePassword();
     // Verschickt Mail an Bauherren
     $mail = createBauhResetPw($bhEmail, $bhFn, $bhLn, $BhPw, $title);
     
      //macht weiter wenn Mail geschickt wurde
     if($mail == TRUE) {
     
     //Verschlüsselt das Passwort
     $pwHash = hash('sha256',$BhPw);
     
     //Fügt Bauherr der Datenbank hinzu
     $sql = resetBauhPw($IdProject, $pwHash);
     $status = mysqli_query($link, $sql);
     echo '<div class="alert alert-success">Das Passwort des Bauherren '.$bhFn.' '.$bhLn.' wurde zurückgesetzt. Dem Bauherren wurde ein Mail mit dem neuen Passwort gesendet. </div>' ;
     }
    
}

?>

<html>
    <head>
        <title>Projekt verwaltung</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">
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
                               <!-- Projektspezifische Angaben -->     
                                <p>Projektnummer*</p>
                                <input type="text" name="ProjectNumber">
                                <p>Projektbezeichnung</p>
                                <input type="text" name="Title">
                                <p>Strasse</p>
                                <input type="text" name="Addressline1">
                                <p>Addresszeile 2</p>
                                <input type="text" name="Addressline2">
                                <p>PLZ*/Ort*</p>
                                <input type="text" name="ZIP"><input type="text" name="City">
                                <p>Land</p>
                                <select name="Country">
                                    <?php 
                                    //Liste mit Ländern aus der Datenbank
                                    $sql = "SELECT Country FROM countries";
                                    $resultC = mysqli_query($link, $sql);
                                        while($rowC= mysqli_fetch_array($resultC)){
                                        echo '<option value="'.$rowC['Country'].'">'.$rowC['Country'].'</option>';
                                    }?>
                                </select>
                                <p>Projektbeschrieb</p>
                                <textarea name="Description"></textarea>
                                <p>Projektbild</p>
                                <label for="upload">Bildupload</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/> <!-- Grössenbegrenzung (nicht Sicher) -->
                                <input id="upload" type="file" name="userfile"/>
                                <!-- Bauherren Daten, zur erstellung Bauherr -->
                                <h4>Daten Bauherr</h4>
                                <p>Vorname</p>
                                <input type="text" name="BhFirstname">
                                <p>Nachname</p>
                                <input type="text" name="BhLastname">
                                <p>Strasse</p>
                                <input type="text" name="BhAddressline1">
                                <p>Adresszeile 2</p>
                                <input type="text" name="BhAddressline2">
                                <p>PLZ/Ort</p>
                                <input type="text" name="BhZIP"><input type="text" name="BhCity">
                                <p>Land</p>
                                <input type="text" name="BhCountry">
                                <p>Telefonnummer</p>
                                <input type="text" name="BhPhoneNumber">
                                <p>Mobile Nummer</p>
                                <input type="text" name="BhMobileNumber">
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
    
    <!-- Projekt bearbeiten -->
    <!-- Modal Global-->
    <div class="modal" id="editPost" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="projektverwaltung.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Projekt bearbeiten</h4>
                    </div>
                        <div class="modal-body">
                            <div id="editContainer_pv">

                                <!-- Platzhalter für ajax Inhalt -->

                            </div>       
                        </div>
                    <div class="modal-footer">
                        <input type="submit" name="store" value="Archivieren" class="btn btn-default"/>
                        <input type="submit" name="edit" value="Speichern" class="btn btn-default"/>
                        <input type="submit" name="delete" value="Löschen" class="btn btn-default" />
                        
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    </div>
              </form>

            </div>

        </div>
    </div>
        </div>
         
<?php

// Ausgabe Projekte


$sql = getProjectsByArch($id);

$result = mysqli_query($link, $sql);

while($row= mysqli_fetch_array($result)){
   
    echo'<div class="post row">';
    echo '<h3><button type="button" class="btn_postEdit_pv" data-toggle="modal" data-target="#editPost" value="'.$row['IdProject'].'"><i class="fa fa-pencil-square-o"></i></button>'.$row['Title'].'
           <form action="index.php" method="POST">
            <button type="submit" name ="goto" class="btn_postEdit_pv" data-toggle="modal" value="'.$row['IdProject'].'"><i class="fa fa-share"></i></button> </h3>
            </form>';
    echo '<h2>Projektnummer:'.$row['ProjectNumber'].'</h2>';
    echo'<div class="col-sm-2 imgLiquidFill imgLiquid ">';
   echo'<a href="#" data-featherlight="'.$row['Picture'].'"><img alt="" src="'.$row['Picture'].'"/></a>';
    echo'</div>';
    echo'<div class="col-sm-6">';
    echo'<p>'.$row['Description'].'</p>';
    echo'</div>';
    echo'</div>';
}
echo'</div>';
echo'</div>';

?>



        
         
  <!-- JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
<script src="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../js/imgLiquid-min.js"></script>
<script src="../js/script.js"></script>

    </body>
</html>