<?php
//Session starten oder wiederaufnehmen
 session_start();
 
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');

$link= connectDB();

//Umwandlung der Get Requests
if(isset($_GET['id'])){
    $_POST['nav']=$_GET['id'];
    $_POST['projectID']=$_GET['project'];
    
}

//Usertyp wird ermittelt (falls vorhanden)
if(isset($_SESSION['UserType'])){
    $usertyp= $_SESSION['UserType']; //1= Archconsulting //2= Architekt //3= Bauherr
    
    if($usertyp==2){
        if($_SESSION['IdProject']){
            $validprojects= $_SESSION['IdProject'];//hole Array mit Projekten des Architekten (die er öffnen darf, sprich seine eigenen Projekte)
            
            //Speichert letzt geöffnetes Projekt in der Session (nur Architekt)
            if(isset($_POST['projectID'])){
                $_SESSION['LastProjectID']=$_POST['projectID'];
            }
            if(!isset($_POST['nav'])){//Bei einem Refresh über den Browser wird die Navigation 1 (Home) aufgerufen
                $_POST['nav']=1;
            }
            
        }else{
            //keine Projekt ID's = kein Zugriff
            header('Location: login.php?denied=1');
        }   
    }else if($usertyp==3){
        if(isset($_SESSION['IdProject'])){
            $projectID= $_SESSION['IdProject'];
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

//Projekt ID wird ermittelt
if(isset($_POST['goto'])){//Architekt (1. Aufruf)
    $projectID= $_POST['goto'];
    //Pürft ob das ausgewählte Projekt vom Architekten X ist
    if(in_array($projectID, $validprojects)){
        //Setzt Menüpunkt Home auf aktiv
        $active1='active';
    }else{
        //kein Zugriff auf dieses Projekt
        header('Location: login.php?denied=1');
    }
  
}else if(isset($_POST['nav'])){
    //Wenn eine ProjektID übergeben wird, nimm diese
    if(isset($_POST['projectID'])){
        $projectID= $_POST['projectID'];
     
        //Wenn keine übergeben wird,
    }else if (isset($_SESSION['LastProjectID'])) {
        $projectID=$_SESSION['LastProjectID'];
    }
    
    
    //Überprüf welcher Menüpunkt ausgewählt ist
    if(isset($_POST['nav'])){
        //Setzt angeklickten Menüpunkt als Aktiv (Grüner hintergrund)
        $active= $_POST['nav'];
        switch ($active) {
            case 1:
                $active1='active';
                break;
            case 2:
                $active2='active';
                break;
            case 3:
                $active3='active';
                break;
            case 4:
                $active4='active';
                break;
            case 5:
                $active5='active';
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
    
    
    
}else if(isset ($projectIDx)){//Bauherr (1. Aufruf)
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

    <!-- CSS 3rd Party -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.8/css/jquery.dataTables.min.css">
    <link href="../css/dataTable.css" rel="stylesheet">
    <link href="../css/nanogallery/css/nanogallery.min.css" rel="stylesheet">
    <link href="../css/nanogallery/css/themes/light/nanogallery_light.min.css" rel="stylesheet">
    <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.min.css" rel="stylesheet">
    <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" rel="stylesheet">
    <link href="../css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="../css/slick.css" rel="stylesheet">
    <link href="../css/slick-theme.css" rel="stylesheet">

    <!-- CSS spezifisch -->
    <link href="../css/style.css" rel="stylesheet">


</head>

<body>

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
                <a class="navbar-brand" href="index.html" target="_blank"><img src="../img/architect1/personal/logo.gif" alt="Logo"></a>
                <h1 class="navbar-text"><?php echo $prNr.'&nbsp;&nbsp;&nbsp;&nbsp;'.$prNa;?></h1>
            </div>

            <!-- Top Menu -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $fnCust.' '.$lnCust ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
                        if($usertyp==2){
                            echo'<li>';
                            echo'<a class="link-pv" href="projektverwaltung.php"><i class="fa fa-tasks"></i>&nbsp;&nbsp;Projektverwaltung</a>';
                            echo'</li>';   
                        }
                        ?>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Einstellungen</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="login.php"><i class="fa fa-fw fa-power-off"></i> Abmelden</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Sidebar Menü (Element klappen ein bei kleinem Viewport) -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <form action="index.php" method="POST">
                    <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                    <ul class="nav navbar-nav side-nav">
                        <li id="home_li">
                            <button type="submit" id="home" name="nav" value="1" class="<?php echo $active1; ?>"><i class="fa fa-home"></i>Home</button>
                            <!--<a href="index.php?id=1" id="home"><i class="fa fa-home"></i>Home</a>-->
                        </li>
                        <li id="timeline_li">
                            <button type="submit" id="timeline" name="nav" value="2" class="<?php echo $active2; ?>"><i class="fa fa-tachometer"></i>Chronik</button>
                            <!--<a href="index.php?id=2" id="timeline"><i class="fa fa-tachometer"></i>Chronik</a>-->
                        </li>
                        <li id="termine_li">
                            <a id="termingroup" href="javascript:;" data-toggle="collapse" data-target="#termine"><i class="fa fa-calendar"></i>Termine<i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="termine" class="collapse">
                                <li>
                                    <button type="submit" id="terminplan" name="nav" value="3"  class="<?php echo $active3; ?>">Terminplan</button>
                                    <!--<a href="index.php?id=3" id="terminplan" >Terminplan</a>-->
                                </li>
                                <li>
                                    <button type="submit" id="events" name="nav" value="4"  class="<?php echo $active4; ?>">Events</button>
                                    <!--<a href="index.php?id=4" id="events">Events</a>-->
                                </li>
                                <li>
                                    <button type="submit" id="deadlines" name="nav" value="5"  class="<?php echo $active5; ?>">Deadlines</button>
                                    <!--<a href="index.php?id=5" id="deadlines">Deadlines</a>-->
                                </li>
                            </ul>
                        </li>
                        <li id="addresslist_li">
                            <button type="submit" id="addresslist" name="nav" value="6" class="<?php echo $active6; ?>"><i class="fa fa-list-ul"></i>Adressliste</button>
                            <!--<a href="index.php?id=6" id="addresslist"><i class="fa fa-list-ul"></i>Adressliste</a>-->
                        </li>
                        <li id="gallery_li">
                            <button type="submit" id="gallery" name="nav" value="7" class="<?php echo $active7; ?>"><i class="fa fa-camera"></i>Fotogalerie</button>
                            <!--<a href="index.php?id=7" id="gallery"><i class="fa fa-camera"></i>Fotogalerie</a>-->
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
            
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <?php
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
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- JS 3rd Party -->
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../css/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
    <script src="../js/bootstrap-clockpicker.min.js"></script>
    <script src="../js/slick.js"></script>
    <!-- Timeline -->
    <script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="../js/imgLiquid-min.js"></script>
    <!-- Adressliste -->
    <!--<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>-->
    <script src="https://cdn.datatables.net/buttons/1.0.0/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.0.0/js/buttons.html5.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <!-- Gallery -->
    <script src="../css/nanogallery/jquery.nanogallery.min.js"></script>
    <!-- Überschreibt URL -->
    <script>
        window.history.pushState('', '', '/php/index.php');
        //window.history.pushState('', '', '/diplomarbeit/platform/public/php/index.php');
    </script>
    <script src="../js/script.js"></script>
    <script src="../js/weather.js"></script>


</body>

</html>
