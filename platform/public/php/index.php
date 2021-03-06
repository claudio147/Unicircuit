<?php
/*
*   Unicircuit Plattform
*   «Index (Controller)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/

//Session starten oder wiederaufnehmen
 session_start();
 
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');
require_once ('../../../library/public/security.inc.php');

 //User wird anhand Session ID Überprüft
 
 $idUser = $_SESSION['IdUser'];
 $sessionId = session_id();
 $valide = checkSessionId($idUser, $sessionId);
 //Stimmt SessionID und SessionId aus DB nicht überein wird der User zum Login
 //weitergeleitet.
 if($valide == false) {
    header('Location: login.php?denied=1');
    exit(); 
 }

$link= connectDB();

//Umwandlung der Get Requests
if(isset($_GET['id'])){
    $_POST['nav']=$_GET['id'];
    $_POST['projectID']=$_GET['project'];
}

//Usertyp wird ermittelt (falls vorhanden)
if(isset($_SESSION['UserType'])){
    $usertyp= $_SESSION['UserType'];
    
    if($usertyp==2){
        if($_SESSION['IdProject']){
            $validprojects= $_SESSION['IdProject'];//hole Array mit Projekten des Architekten (die er öffnen darf, sprich seine eigenen Projekte)

            //Speichert letzt geöffnetes Projekt in der Session (nur Architekt)
            if(isset($_POST['projectID'])){
                $_SESSION['LastProjectID']=$_POST['projectID'];
            }
            if(!isset($_POST['nav'])){//Bei einem Refresh über den Browser wird die Navigation 1 (Home) aufgerufen
                $_POST['nav']=$_SESSION['LastNav'];
            }
            if(isset($_SESSION['LastProjectID'])){
                $projectID=$_SESSION['LastProjectID'];
            }
        }else{
            //keine Projekt ID's = kein Zugriff
            header('Location: login.php?denied=1');
        }   
    }else if($usertyp==3){
        if(isset($_SESSION['IdProject'])){
            $projectID= $_SESSION['IdProject'];
            
            if(!isset($_POST['nav'])){//Bei einem Refresh über den Browser wird die Navigation 1 (Home) aufgerufen
                if(isset($_SESSION['LastNav'])){
                    $_POST['nav']=$_SESSION['LastNav'];
                }else{
                    $_POST['nav']=1;
                    //Setzt Menüpunkt Home auf aktiv
                    $active1='active';
                    //Collapse Menü (Termine: geschlossen)
                    $collapse='collapse';
                }
            }
        }else{
            //keine Projekt ID = kein Zugriff
            header('Location: login.php?denied=1');
        }
    }else{
        //Usertyp unbekannt
        header('Location: login.php?denied=1');
    }
}else{
    //kein Usertyp = kein Zugriff
    header('Location: login.php?denied=1');
}

//Anpassungen User Einstellungen 
if(isset($_POST['edit_User'])) {
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
    }else{
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

//Projekt ID wird ermittelt
if(isset($_POST['goto'])){//Architekt (1. Aufruf)
    $projectID= $_POST['goto'];
    $_SESSION['LastProjectID']=$projectID;
    $_SESSION['LastNav']=1;
    $_POST['nav']=1;
    //Pürft ob das ausgewählte Projekt vom Architekten X ist
    if(in_array($projectID, $validprojects)){
        //Setzt Menüpunkt Home auf aktiv
        $active1='active';
        //Collapse Menü (Termine: geschlossen)
        $collapse='collapse';
    }else{
        //kein Zugriff auf dieses Projekt
        header('Location: login.php?denied=1');
    }
  
}else if(isset($_POST['nav'])){
    //Wenn eine ProjektID übergeben wird, nimm diese
    if(isset($_POST['projectID'])){
        $projectID= $_POST['projectID'];
     
        //Wenn keine übergeben wird,
    }else if (isset($_SESSION['LastProjectID'])){
        $projectID=$_SESSION['LastProjectID'];
    }
    
    //Überprüf welcher Menüpunkt ausgewählt ist
    if(isset($_POST['nav'])){
        //Setzt angeklickten Menüpunkt als Aktiv (Grüner hintergrund)
        $active= $_POST['nav'];
        $_SESSION['LastNav']=$active;
        
        //Collapse Menü (Termine: geschlossen)
        $collapse='collapse';
        switch ($active) {
            case 1:
                $active1='active';
                break;
            case 2:
                $active2='active';
                break;
            case 3:
                $active3='active';
                //Collapse Menü (Termine: geöffnet)
                $collapse='collapse.in';
                break;
            case 4:
                $active4='active';
                //Collapse Menü (Termine: geöffnet)
                $collapse='collapse.in';
                break;
            case 5:
                $active5='active';
                //Collapse Menü (Termine: geöffnet)
                $collapse='collapse.in';
                break;
            case 6:
                $active6='active';
                break;
            case 7:
                $active7='active';
                break;
            case 8:
                $active8='active';
                break;
            case 9:
                $active9='active';
                break;
            default:
                break;
        }
    }else{
        //kein Zugriff auf dieses Projekt
        header('Location: login.php?denied=1');
    }
}else if(isset($projectIDx)){//Bauherr (1. Aufruf)
    $projectID= $projectIDx;
    //Setzt Menüpunkt Home auf aktiv
    $active1='active';   
}

//Variablen Deklaration
$prNr;
$prNa;
$fnCust;
$lnCust;

//Projekt Details
$sql= getNameCust($projectID);
$result= mysqli_query($link, $sql);
while($row= mysqli_fetch_array($result)){
    $prNr= $row['ProjectNumber'];
    $prNa= $row['Title'];
}

//User Details
$userID= $_SESSION['IdUser'];
$sql=userData($userID);
$result= mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$fnCust=$row['Firstname'];
$lnCust=$row['Lastname'];

//Hole Link Arch.-Logo
$sql=selectArchLogo($projectID);
$result=mysqli_query($link, $sql);
$row=mysqli_fetch_array($result);
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

    <title><?php echo $prNr.' '.$prNa;?></title>

    <!--CSS 3rd Party-->
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.css" rel="stylesheet" type="text/css" />
    <link href="//cdn.datatables.net/1.10.8/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="//cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="//cdn.datatables.net/1.10.9/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../css/dataTable.css" rel="stylesheet" type="text/css">
    <link href="../css/nanogallery/css/nanogallery.min.css" rel="stylesheet" type="text/css">
    <link href="../css/nanogallery/css/themes/light/nanogallery_light.min.css" rel="stylesheet" type="text/css">
    <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" rel="stylesheet" type="text/css">
    <link href="../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
    <link href="../css/slick.css" rel="stylesheet" type="text/css">
    <link href="../css/slick-theme.css" rel="stylesheet" type="text/css">

    <!--CSS Custom-->
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    
    <!-- FAVICON -->
    <link rel="shortcut icon" href="../img/icon.png" type="image/png" />
    <link rel="icon" href="../img/icon.png" type="image/png" />

</head>

<body style="overflow-x: hidden;">

    <!-- User Settings -->
    <!-- Modal Global-->
    <div class="modal" id="editUser" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="index.php" method="POST">
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
                        <input type="submit" name="edit_User" value="Speichern" class="btn btn-default"/>

                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            <!-- Logo und "Toggle" -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img src="<?php echo $logo; ?>" alt="Logo"></a>
                <h1 class="navbar-text"><?php echo $prNr.'&nbsp;&nbsp;&nbsp;&nbsp;'.$prNa;?></h1>
                
                <!-- Top Menu -->
                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $fnCust.' '.$lnCust ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?php
                            if($usertyp==2){
                                echo'<li>';
                                echo'<a class="link-pv" href="projektverwaltung.php?nav=1"><i class="fa fa-tasks"></i>&nbsp;&nbsp;Projektverwaltung</a>';
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

            <!-- Sidebar Menü (Element klappen ein bei kleinem Viewport) -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <form action="index.php" method="POST">
                    <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                    <ul class="nav navbar-nav side-nav">
                        <li id="home_li">
                            <button type="submit" id="home" name="nav" value="1" class="<?php echo $active1; ?>"><i class="fa fa-home"></i>Home</button>
                        </li>
                        <li id="timeline_li">
                            <button type="submit" id="timeline" name="nav" value="2" class="<?php echo $active2; ?>"><i class="fa fa-tachometer"></i>Chronik</button>
                        </li>
                        <li id="termine_li">
                            <a id="termingroup" href="javascript:;" data-toggle="collapse" data-target="#termine"><i class="fa fa-calendar"></i>Termine<i id="icon-collaps-menu" class="fa fa-fw fa-caret-down collapse-icon"></i></a>
                            <ul id="termine" class="<?php echo $collapse; ?>">
                                <li>
                                    <button type="submit" id="terminplan" name="nav" value="3"  class="<?php echo $active3; ?>">Terminplan</button>
                                </li>
                                <li>
                                    <button type="submit" id="events" name="nav" value="4"  class="<?php echo $active4; ?>">Events</button>
                                </li>
                                <li>
                                    <button type="submit" id="deadlines" name="nav" value="5"  class="<?php echo $active5; ?>">Deadlines</button>
                                </li>
                            </ul>
                        </li>
                        <li id="addresslist_li">
                            <button type="submit" id="addresslist" name="nav" value="6" class="<?php echo $active6; ?>"><i class="fa fa-list-ul"></i>Adressliste</button>
                        </li>
                        <li id="gallery_li">
                            <button type="submit" id="gallery" name="nav" value="7" class="<?php echo $active7; ?>"><i class="fa fa-camera"></i>Fotogalerie</button>
                        </li>
                        <?php
                            //User == Architekt
                            if($usertyp==2){
                                echo'<li id="sia_li">';
                                echo'<button type="submit" id="sia" name="nav" value="9" class="'.$active9.'"><i class="fa fa-cloud-download"></i>SIA Baujournal</button>';
                                echo'</li>';
                            //User == Bauherr
                            }else if($usertyp==3){
                                echo'<li id="contact_li">';
                                echo'<button type="submit" id="contact" name="nav" value="8" class="'.$active8.'"><i class="fa fa-comments"></i>Kontakt</button>';
                                echo'</li>';
                            } 
                        ?>
                        <p class="navbar-text unicircuit"><a class="noStyleLink" href="http://palmers.dynathome.net:8024/diplomarbeit/productsite/public/" target="_blank">UNICIRCUIT</a></p>
                    </ul>
                </form>
            </div>
            
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <?php
                    //Rückgabemeldung für Eingaben in Lightboxen
                    $stat = checkResponse($response);
                    echo $stat;
               
                    if(isset($_POST['nav'])){
                        switch($_POST['nav']){
                            case 1:
                                include ('dashboard.php');
                                break;
                            case 2:
                                include ('timeline.php');
                                break;
                            case 3:
                                include ('schedule.php');
                                break;
                            case 4:
                                include ('events.php');
                                break;
                            case 5:
                                include ('deadlines.php');
                                break;
                            case 6:
                                include ('addresslist.php');
                                break;
                            case 7:
                                include ('gallery.php');
                                break;
                            case 8:
                                include ('contact.php');
                                break;
                            case 9:
                                include ('sia.php');
                                break;
                            default:
                                echo'<p>Error Loading Content</p>';
                        }
                    }else{
                        include ('dashboard.php');
                    }
                ?>
            </div>
        </div>
    </div>
    

    <!--JS 3rd Party-->
    <script src="../js/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../css/jquery-ui-1.11.4.custom/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../js/bootstrap-clockpicker.min.js" type="text/javascript"></script>
    <script src="../js/slick.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.10.9/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="../js/imgLiquid-min.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/buttons/1.0.0/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js" type="text/javascript"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js" type="text/javascript"></script>
    <script src="//cdn.datatables.net/buttons/1.0.0/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>
    <script src="../css/nanogallery/jquery.nanogallery.min.js" type="text/javascript"></script>
    <script src="../js/jquery.nicescroll.min.js" type="text/javascript"></script>
    
    <!-- Überschreibt URL -->
    <script>
        window.history.pushState('', '', '/php/index.php');
        //window.history.pushState('', '', '/diplomarbeit/platform/public/php/index.php');
    </script>
    
    <!--JS Custom-->
    <script src="../js/script.js" type="text/javascript"></script>
    <script src="../js/weather.js" type="text/javascript"></script>
    
</body>
</html>
