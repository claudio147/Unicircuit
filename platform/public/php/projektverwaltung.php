<?php
/*
 *  Programmpunkt 1.1 Projektverwaltung
 */

//Session starten oder wiederaufnehmen
 session_start();
    
 if(!isset($_SESSION['IdUser']) || $_SESSION['UserType'] != 2) {
    header('Location: login.php?denied=1');
}

if(isset($_GET['nav'])){
    $nav=$_GET['nav']; //1= Aktive Projekte //2= Archiv   
}else{
    $nav=1;
}

if($nav==1){
    $title='Projektverwaltung';
}else if($nav==2){
    $title='Archiv';
}

if($_GET['status']){
    $response=$_GET['status'];
}

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/security.inc.php');
require_once ('../../../library/public/mail.inc.php');


//Holt Architekten User Daten
$id = $_SESSION['IdUser'];
    
$link = connectDB();







/*
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
    $id = filter_input(INPUT_POST, 'UserId', FILTER_SANITIZE_NUMBER_INT);
    
     //Daten in Variablen Speichern
     $firstname = filter_input(INPUT_POST, 'Firstname', FILTER_SANITIZE_STRING);
     $lastname = filter_input(INPUT_POST, 'Lastname', FILTER_SANITIZE_STRING);
     $company = filter_input(INPUT_POST, 'Company', FILTER_SANITIZE_STRING);
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
    
        $uploaddir = '../architects/architect_'.$id.'/' ;
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
            $sql= updateArchWithPic($firstname, $lastname, $company, $addressline1, $addressline2, $zip, $city, $country, $phoneNumber,
                        $mobileNumber, $email, $uploadfile, $id);

            $status= mysqli_query($link, $sql);
            
            if($status){
                $response='5';
            }else{
                $response='1';
            }
            
        }else if($filetypeError){
            $response='4';
        }else{
            $response='1';
        }

    } 
    else {
        $sql = updateArchWithoutPic($firstname, $lastname, $company, $addressline1, $addressline2, $zip, $city, $country, $phoneNumber,
                        $mobileNumber, $email, $id);
        $status= mysqli_query($link, $sql);
            
            if($status){
                $response='5';
            }else{
                $response='1';
            }
    }
    
    if(!empty($_POST['password1'])) {
        $pw1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
        $pw2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
        // Passworte identisch
        if (($pw1 != $pw2) || (strlen($pw1) < 8)) {
        $response = '6';
        $error = true;
        }
        if(!isset($error)) {
            $pw1 = hash('sha256', $pw1);
            
            $sql = updateUserPw($pw1, $id);
            $status = mysqli_query($link, $sql);
            if($status) {
                $response = '5';
            } else{
                $response = '1';
            }
        }
    }
     

}
*/


//User Details
$userID= $_SESSION['IdUser'];
$sql=userData($userID);
$result= mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$fnCust=$row['Firstname'];
$lnCust=$row['Lastname'];
$logo= $row['Picture'];
?>

<!DOCTYPE html>
<html lang="de">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


        <title><?php echo $title; ?></title>

        <!-- CSS 3rd Party -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.min.css" rel="stylesheet">
        <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" rel="stylesheet">
        <!--<link href="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.css" type="text/css" rel="stylesheet" />-->
        <!--<link rel="stylesheet" href="//cdn.datatables.net/1.10.8/css/jquery.dataTables.min.css">
        <link href="../css/dataTable.css" rel="stylesheet">
        <link href="../css/nanogallery/css/nanogallery.min.css" rel="stylesheet">
        <link href="../css/nanogallery/css/themes/light/nanogallery_light.min.css" rel="stylesheet">
        <link href="../css/datepicker.css" rel="stylesheet">
        <link href="../css/bootstrap-clockpicker.min.css" rel="stylesheet">
        <link href="../css/slick.css" rel="stylesheet">
        <link href="../css/slick-theme.css" rel="stylesheet">
        
        <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" rel="stylesheet">-->
        
        <!-- CSS spezifisch -->
        <link href="../css/style.css" rel="stylesheet">
        
        <!-- FAVICON -->
        <link rel="shortcut icon" href="../img/icon.png" type="image/png" />
        <link rel="icon" href="../img/icon.png" type="image/png" />
        
        

    </head>
    <body>
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
        
        <div id="wrapper wrapper-pv">
        
            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

                <!-- Logo und "Toggle" -->
                <div class="navbar-header navbar-header-pv">
                    <a class="navbar-brand" href="#"><img src="<?php echo $logo; ?>" alt="Logo"></a>
                    <h1 class="navbar-text"><?php echo $title; ?></h1>
                

                    <!-- Top Menu -->
                    <ul class="nav navbar-right top-nav">
                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $fnCust.' '.$lnCust ?><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <?php
                                //Projektverwaltung
                                if($nav==1){
                                    echo'<li>';
                                    echo'<a class="btn_userSettings" href="projektverwaltung.php?nav=2"><i class="fa fa-tasks"></i>&nbsp;&nbsp;Archiv</a>';
                                    echo'</li>';
                                //Archiv
                                }else if($nav==2){
                                    echo'<li>';
                                    echo'<a class="btn_userSettings" href="projektverwaltung.php?nav=1"><i class="fa fa-tasks"></i>&nbsp;&nbsp;Projektverwaltung</a>';
                                    echo'</li>';
                                } 
                                ?>
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
                </div>
            </nav>

            <div class="container">

                <?php
                if(isset($nav)){
                    switch ($nav){
                        case 1:
                            include ('activeProjects.php');
                            break;
                        case 2:
                            include ('storage.php');
                            break;
                        default:
                            echo'<p>Error Loading Content</p>';
                    }
                }
                ?>

            </div><!-- End Container--> 
        
        </div><!-- End #Wrapper--> 
        
         
    <!-- JS 3rd Party -->
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../css/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
    <!--<script src="../js/bootstrap-clockpicker.min.js"></script>
    <script src="../js/slick.js"></script>-->
    <!-- Timeline -->
    <!--<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>-->
    <!--<script src="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>-->
    <script src="../js/imgLiquid-min.js"></script>
    <script type="text/javascript" src="../js/jquery.popconfirm.js"></script>
    <!-- Adressliste -->
    <!--<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.0/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.0.0/js/buttons.html5.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>-->
    <!-- Gallery -->
    <!--<script src="../css/nanogallery/jquery.nanogallery.min.js"></script>-->
    <script>
        window.history.pushState('', '', '/php/projektverwaltung.php');
        //window.history.pushState('', '', '/diplomarbeit/platform/public/php/projektverwaltung.php');
    </script>
    <script type="text/javascript" src="../js/script-pv.js"></script>
   <!-- <script src="../js/weather.js"></script>-->

    </body>
</html>