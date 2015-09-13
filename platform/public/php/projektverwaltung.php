<?php
/*
 *  Programmpunkt 1.1 Projektverwaltung
 */

//Session starten oder wiederaufnehmen
 session_start();
    
 if(!isset($_SESSION['IdUser']) || $_SESSION['UserType'] != 2) {
    header('Location: login.php?denied=1');
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
     if($mail){
     
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
     $dir = mkdir('../architects/architect_'.$id.'/project_'.$proId);
     
     $uploaddir = '../architects/architect_'.$id.'/project_'.$proId.'/' ;
     
     //Bildupload für neues Projekt
    if(!empty($_FILES['userfile']['name'])){

        $tempna= $_FILES['userfile']['tmp_name'];
        $orgname= $_FILES['userfile']['name'];
        $size= $_FILES['userfile']['size'];
        $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
        $extension= strrchr($_FILES['userfile']['name'],'.');
        $file= $filename.$extension;

        //Dateipfad mit Dateinamen zusammensetzen
        $uploadfile= $uploaddir.basename($file);

        //Ermittle Bildgrösse
        $image_attributes = getimagesize($tempna); 
        $image_width_old = $image_attributes[0];
        $image_height_old = $image_attributes[1];
        
        $error1=false;
        $error2=false;
        //Überprüft Dateityp
        if(!checkImageType($file)){
            $error1=true;
        }

        //Überprüft Dateigrösse
        if($size > 2100000){
            $error2=true;
        }

        //Verkleinert Bilder über 600px Seitenlänge und speichert diese im verzeichnis,
        //Bilder unter 600px Seitenlänge werden direkt ins Verzeichnis gespeichert
        if(!$error1 && !$error2){
            if($image_width_old>600 || $image_height_old>600){
                if(resizeImage($tempna, $uploadfile, 600)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;
                }
            }else{
                if(move_uploaded_file($tempna, $uploadfile)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;
                }
            }
        }else{
            $statusUpload=false;
            $filetypeError=true;
        } 

        if($statusUpload){
            //Erfolgreich gespeichert --> Speichert DB Eintrag
            $sql= addPicToProject($uploadfile, $proId);
            $status= mysqli_query($link, $sql);

            if($status){
                $response='2';
                $usePlaceholder=false;
            }else{
                $response='3';
                $usePlaceholder=true;
            }

        }else if($filetypeError){
            $response='4';
            $usePlaceholder=true;
        }else{
            $response='3';
            $usePlaceholder=true;
        }
    
    }else{
        $usePlaceholder=true;
    }
    //Setzt Platzhalterbild
    if($usePlaceholder){
        $uploadfile = '../img/placeholder.png' ;
        $sql= addPicToProject($uploadfile, $proId);
        $status= mysqli_query($link, $sql);
        if($status){
            $response='2';
        }else{
            $response='3';
        }
        
        if($filetypeError){
            $response='4';
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
    
        $uploaddir = '../architects/architect_'.$id.'/project_'.$proId2.'/' ;
        $tempna= $_FILES['userfile']['tmp_name'];
        $orgname= $_FILES['userfile']['name'];
        $size= $_FILES['userfile']['size'];
        $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
        $extension= strrchr($_FILES['userfile']['name'],'.');
        $file= $filename.$extension;

        //Dateipfad mit Dateinamen zusammensetzen
        $uploadfile= $uploaddir.basename($file);

        //Ermittle Bildgrösse
        $image_attributes = getimagesize($tempna); 
        $image_width_old = $image_attributes[0];
        $image_height_old = $image_attributes[1];
        
        $error1=false;
        $error2=false;
        //Überprüft Dateityp
        if(!checkImageType($file)){
            $error1=true;
        }

        //Überprüft Dateigrösse
        if($size > 2100000){
            $error2=true;
        }

        //Verkleinert Bilder über 600px Seitenlänge und speichert diese im verzeichnis,
        //Bilder unter 600px Seitenlänge werden direkt ins Verzeichnis gespeichert
        if(!$error1 && !$error2){
            if($image_width_old>600 || $image_height_old>600){
                if(resizeImage($tempna, $uploadfile, 600)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;     
                }
            }else{
                if(move_uploaded_file($tempna, $uploadfile)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;
                }
            }
        }else{
            $statusUpload=false;
            $filetypeError=true;
        }
        

        if($statusUpload){
            //Erfolgreich gespeichert --> Speichert DB Eintrag
            $sql= updateProjectWithPic($projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description, $uploadfile, $bhFn, $bhLn,
                $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhPhNu, $bhMoNu, $bhEmail, $proId2);

            $status= mysqli_query($link, $sql);
            
            if($status){
                $response='0';
            }else{
                $response='1';
            }
            
        }else if($filetypeError){
            $response='4';
        }else{
            $response='1';
        }

    }else{
        //Projekt Upload wenn kein Bild hochgeladen wurde.
        $sql = updateProjectWithout($projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description, $bhFn, $bhLn,
        $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhPhNu, $bhMoNu, $bhEmail, $proId2);
        
        $status = mysqli_query($link, $sql);
        if($status){
            $response='0';
        }else{
            $response='1';
        }
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

//Anpassungen User Einstellungen 
if(isset($_POST['editUser'])) {
    $id = filter_input(INPUT_POST, 'editUser', FILTER_SANITIZE_NUMBER_INT);
    
     //Daten in Variablen Speichern
     $firstname = filter_input(INPUT_POST, 'Firstname', FILTER_SANITIZE_STRING);
     $lastname = filter_input(INPUT_POST, 'Lastname', FILTER_SANITIZE_STRING);
     $addressline1 = filter_input(INPUT_POST, 'Addressline1', FILTER_SANITIZE_STRING);
     $addressline2 = filter_input(INPUT_POST, 'Addressline2', FILTER_SANITIZE_STRING);
     $zip = filter_input(INPUT_POST, 'ZIP', FILTER_SANITIZE_STRING);
     $city = filter_input(INPUT_POST, 'City', FILTER_SANITIZE_STRING);
     $country = filter_input(INPUT_POST, 'Country', FILTER_SANITIZE_STRING);
     $phoneNumber = filter_input(INPUT_POST, 'PhoneNumber', FILTER_SANITIZE_STRING);
     $mobileNumber = filter_input(INPUT_POST, 'MobileNumber', FILTER_SANITIZE_STRING);
     $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_STRING);
     
     //Update wenn auch ein neues Bild hochgeladen wurde
     if(!empty($_FILES['userfile']['name'])){
    
        $uploaddir = '../architects/architect_'.$id.'/project_'.$proId2.'/' ;
        $tempna= $_FILES['userfile']['tmp_name'];
        $orgname= $_FILES['userfile']['name'];
        $size= $_FILES['userfile']['size'];
        $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
        $extension= strrchr($_FILES['userfile']['name'],'.');
        $file= $filename.$extension;

        //Dateipfad mit Dateinamen zusammensetzen
        $uploadfile= $uploaddir.basename($file);

        //Ermittle Bildgrösse
        $image_attributes = getimagesize($tempna); 
        $image_width_old = $image_attributes[0];
        $image_height_old = $image_attributes[1];
        
        $error1=false;
        $error2=false;
        //Überprüft Dateityp
        if(!checkImageType($file)){
            $error1=true;
        }

        //Überprüft Dateigrösse
        if($size > 2100000){
            $error2=true;
        }

        //Verkleinert Bilder über 200px Seitenlänge und speichert diese im verzeichnis,
        //Bilder unter 200px Seitenlänge werden direkt ins Verzeichnis gespeichert
        if(!$error1 && !$error2){
            if($image_width_old>200 || $image_height_old>200){
                if(resizeImage($tempna, $uploadfile, 200)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;     
                }
            }else{
                if(move_uploaded_file($tempna, $uploadfile)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;
                }
            }
        }else{
            $statusUpload=false;
            $filetypeError=true;
        }
        

        if($statusUpload){
            //Erfolgreich gespeichert --> Speichert DB Eintrag
            $sql= updateProjectWithPic($projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description, $uploadfile, $bhFn, $bhLn,
                $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhPhNu, $bhMoNu, $bhEmail, $proId2);

            $status= mysqli_query($link, $sql);
            
            if($status){
                $response='0';
            }else{
                $response='1';
            }
            
        }else if($filetypeError){
            $response='4';
        }else{
            $response='1';
        }

    }
     
     
    
}



//User Details
$userID= $_SESSION['IdUser'];
$sql=userData($userID);
$result= mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$fnCust=$row['Firstname'];
$lnCust=$row['Lastname'];
?>

<!DOCTYPE html>
<html lang="de">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


        <title>Projektverwaltung</title>

        <!-- CSS 3rd Party -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!--<link href="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.css" type="text/css" rel="stylesheet" />-->
        <!--<link rel="stylesheet" href="//cdn.datatables.net/1.10.8/css/jquery.dataTables.min.css">
        <link href="../css/dataTable.css" rel="stylesheet">
        <link href="../css/nanogallery/css/nanogallery.min.css" rel="stylesheet">
        <link href="../css/nanogallery/css/themes/light/nanogallery_light.min.css" rel="stylesheet">
        <link href="../css/datepicker.css" rel="stylesheet">
        <link href="../css/bootstrap-clockpicker.min.css" rel="stylesheet">
        <link href="../css/slick.css" rel="stylesheet">
        <link href="../css/slick-theme.css" rel="stylesheet">
        <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.min.css" rel="stylesheet">
        <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" rel="stylesheet">-->
        
        <!-- CSS spezifisch -->
        <link href="../css/style.css" rel="stylesheet">
        
        

    </head>
    <body>
        
        <div id="wrapper wrapper-pv">
        
            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

                <!-- Logo und "Toggle" -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html" target="_blank"><img src="../img/architect1/personal/logo.gif" alt="Logo"></a>
                    <h1 class="navbar-text">Projektverwaltung</h1>
                </div>

                <!-- Top Menu -->
                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $fnCust.' '.$lnCust ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="btn_userSettings" data-target="#editUser" href="#" data-toggle="modal" data-value="<?php echo $userID; ?> "><i class="fa fa-fw fa-gear"></i> Einstellungen</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="login.php"><i class="fa fa-fw fa-power-off"></i> Abmelden</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                
            </nav>

            <div class="container">

                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#newPost">+ hinzufügen</button>

                <!-- Modal Global-->
                <div class="modal" id="newPost" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <form enctype="multipart/form-data" action="projektverwaltung.php" method="POST" name="createProject" onsubmit="return formCheck()">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Projekt erfassen</h4>
                                </div>
                                    <div class="modal-body">
                                        <div id="input_container">
                                           <!-- Projektspezifische Angaben --> 
                                            <h4>Daten Projekt</h4>
                                            <div id="ProNumb">
                                            <label for="1" class="control-label">Projektnummer*</label>
                                            <input id="1" type="text" name="ProjectNumber" class="form-control">
                                            </div>
                                            <div id="Title">
                                            <label for="2" class="control-label">Projektbezeichnung</label>
                                            <input id="2" type="text" name="Title" class="form-control">
                                            </div>
                                            <div id="Address1">
                                            <label for="3" class="control-label">Strasse</label>
                                            <input id="3" type="text" name="Addressline1" class="form-control">
                                            </div>
                                            <div id="Address2">
                                            <label for="4" class="control-label">Addresszeile 2</label>
                                            <input id="4" type="text" name="Addressline2" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-2">
                                                    <div id="ZIP">
                                                    <label for="5" class="control-label">PLZ*</label>
                                                    <input id="5" type="text" name="ZIP" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-xs-10">
                                                    <div id="City">
                                                    <label for="6" class="control-label">Ort*</label>
                                                    <input id="6" type="text" name="City" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="7">Land*</label>
                                            <select id="7" name="Country" class="form-control">
                                                <?php 
                                                //Liste mit Ländern aus der Datenbank
                                                $sql = "SELECT Country FROM countries";
                                                $resultC = mysqli_query($link, $sql);
                                                    while($rowC= mysqli_fetch_array($resultC)){
                                                    echo '<option value="'.$rowC['Country'].'">'.$rowC['Country'].'</option>';
                                                }?>
                                            </select>
                                            <div id="Description">
                                            <label for="8" class="control-label">Projektbeschreib</label>
                                            <textarea id="8" name="Description" class="form-control"></textarea>
                                            </div>
                                            <label for="upload3">Projektbild</label>
                                            <input id="upload3" type="file" name="userfile"/>
                                            <!-- Bauherren Daten, zur erstellung Bauherr -->
                                            <hr/>
                                            <h4>Daten Bauherr</h4>
                                            <div id="BhFn">
                                            <label for="9" class="control-label">Vorname*</label>
                                            <input id="9" type="text" name="BhFirstname" class="form-control">
                                            </div>
                                            <div id="BhLn">
                                            <label for="10" class="control-label">Nachname*</label>
                                            <input id="10" type="text" name="BhLastname" class="form-control">
                                            </div>
                                            <div id="BhAddress1">
                                            <label for="11" class="control-label">Strasse*</label>
                                            <input id="11" type="text" name="BhAddressline1" class="form-control">
                                            </div>
                                            <div id="BhAddress2">
                                            <label for="12" class="control-label">Adresszeile 2</label>
                                            <input id="12" type="text" name="BhAddressline2" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-2">
                                                    <div id="BhZIP">
                                                    <label for="13" class="control-label">PLZ*</label>
                                                    <input id="13" type="text" name="BhZIP" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-xs-10">
                                                    <div id="BhCity">
                                                    <label for="14" class="control-label">Ort*</label>
                                                    <input id="14" type="text" name="BhCity" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="15">Land</label>
                                            <select id="15" name="BhCountry" class="form-control">
                                                <?php 
                                                //Liste mit Ländern aus der Datenbank
                                                $sql = "SELECT Country FROM countries";
                                                $resultC = mysqli_query($link, $sql);
                                                    while($rowC= mysqli_fetch_array($resultC)){
                                                    echo '<option value="'.$rowC['Country'].'">'.$rowC['Country'].'</option>';
                                                }?>
                                            </select>
                                            <div id="BhPhNu">
>                                            <label for="16" class="control-label">Telefonnummer</label>
                                            <input id="16" type="text" name="BhPhoneNumber" class="form-control">
                                            </div>
                                            <div id="BhMoNu">
                                            <label for="17" class="control-label">Mobile Nummer</label>
                                            <input id="17" type="text" name="BhMobileNumber" class="form-control">
                                            </div>
                                            <div id="BhEmail">
                                            <label for="18" class="control-label">Email*</label>
                                            <input id="18" type="email" name="BhEmail" class="form-control">
                                            </div>
                                            
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
                
                 <!-- User Settings -->
                <!-- Modal Global-->
                <div class="modal" id="editUser" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <form enctype="multipart/form-data" action="projektverwaltung.php" method="POST">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Benutzer Einstellungen</h4>
                                </div>
                                    <div class="modal-body">
                                        <div id="editContainer_User">

                                            <!-- Platzhalter für ajax Inhalt -->

                                        </div>       
                                    </div>
                                <div class="modal-footer">
                                    <input type="submit" name="editUser" value="Speichern" class="btn btn-default"/>

                                    <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                                </div>
                          </form>

                        </div>

                    </div>
                </div>
            
<?php


if(isset($response)){
        $x=$response;
        if($x==0){
            echo'<br/><div class="alert alert-success" role="alert">Projekt erfolgreich bearbeitet.</div>';
        }else if($x==1){
            echo'<br/><div class="alert alert-danger" role="alert">Bearbeiten fehlgeschlagen</div>';
        }else if($x==2){
            echo'<br/><div class="alert alert-success" role="alert">Projekt erfolgreich hinzugefügt</div>';
        }else if($x==3){
            echo'<br/><div class="alert alert-danger" role="alert">Projekt hinzufügen fehlgeschlagen</div>';
        }else if($x==4){
            echo'<br/><div class="alert alert-danger" role="alert">Max. 2MB und Filetypen: .jpg/.png/.gif</div>';
        }
    }
    
    
// Ausgabe Projekte

$sql = getProjectsByArch($id);

$result = mysqli_query($link, $sql);


$projectsId = array();

echo'<div class="pv-row row">';
while($row= mysqli_fetch_array($result)){
    $projectsId[] = $row['IdProject'];
    echo'<div class="col-xs-4 col-md-3 pv-container">';
        echo'<div class="pv-cont-content">';
        echo'<div class="imgLiquidFill imgLiquid project-img-cont">';
        echo'<img class="projectimage" alt="project-image" src="'.$row['Picture'].'"/>';
        echo'</div>';
        echo'<p class="pv-label">Projektnummer</p>';
        echo'<p class="pv-bold">'.$row['ProjectNumber'].'</p>';
        echo'<p class="pv-label">Bezeichnung</p>';
        echo'<p class="pv-bold">'.$row['Title'].'</p>';
        echo'<p class="pv-label">Bauherr</p>';
        echo'<p class="pv-reg">'.$row['Firstname'].' '.$row['Lastname'].'</p>';
        echo'<div class="row">';
            echo'<div class="col-xs-6">';
                echo'<button type="button" class="btn-pv btn_postEdit_pv btn btn-default" data-toggle="modal" data-target="#editPost" value="'.$row['IdProject'].'"><i class="fa fa-pencil-square-o"></i> Einstell.</button>';
            echo'</div>';
            echo'<div class="col-xs-6">';
                echo'<form action="index.php" method="POST">
                <button type="submit" name ="goto" class="btn-pv btn btn-default" value="'.$row['IdProject'].'"><i class="fa fa-share"></i> öffnen</button>
                </form>';
            echo'</div>';
        echo'</div>';
        echo'</div>';
    echo'</div>';
}
echo'</div>';

//Speichert alle ProjectIds in einer Session Array
$_SESSION['IdProject'] = $projectsId;

?>


            </div><!-- End Container--> 
        
        </div><!-- End #Wrapper--> 
        
         
    <!-- JS 3rd Party -->
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <!--<script src="../css/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>-->
    <!--<script src="../js/bootstrap-clockpicker.min.js"></script>
    <script src="../js/slick.js"></script>-->
    <!-- Timeline -->
    <!--<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>-->
    <!--<script src="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>-->
    <script src="../js/imgLiquid-min.js"></script>
    <!-- Adressliste -->
    <!--<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.0/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.0.0/js/buttons.html5.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>-->
    <!-- Gallery -->
    <!--<script src="../css/nanogallery/jquery.nanogallery.min.js"></script>-->

    <script type="text/javascript" src="../js/script-pv.js"></script>
   <!-- <script src="../js/weather.js"></script>-->

    </body>
</html>