<?php
//Session starten oder wiederaufnehmen
 session_start();
 
require_once ('../../../library/public/database.inc.php');
//require_once ('../../../library/public/mail.inc.php');

$link= connectDB();



//Usertyp wird ermittelt (falls vorhanden)
if(isset($_SESSION['UserType'])){
    $usertyp= $_SESSION['UserType']; //1= Archconsulting //2= Architekt //3= Bauherr

    if($usertyp!=1){
        //Usertyp ohne Berechtigung
        header('Location: ../../../platform/public/php/login.php?denied=1');
    }
}else{
    //kein Usertyp = kein Zugriff
    header('Location: ../../../platform/public/php/login.php?denied=1');
}

//Hole Navigation
if(isset($_GET['nav'])){
    $_POST['nav']=$_GET['nav'];
}

//Hole Textpart der geladen werden soll
if(isset($_GET['select'])){
    $select=$_GET['select'];
}

//Hole Status aus Aktion
if(isset($_GET['statusSave'])){
    $status=$_GET['statusSave'];
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


    <title>Redaktionssystem</title>

    <!-- CSS 3rd Party -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
    <!--<link href="../css/dataTable.css" rel="stylesheet">-->

    <!-- CSS spezifisch -->
    <link href="../css/style.css" rel="stylesheet">
    
    <!-- FAVICON -->
    <link rel="shortcut icon" href="../img/icon.png" type="image/png" />
    <link rel="icon" href="../img/icon.png" type="image/png" />
    
    <script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
    <script>tinymce.init({
            selector:'textarea#2',
            plugins: 'code link image lists preview table',
            skin: 'custom',
            width: 600,
            min_height: 300,
        });
    </script>
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
                <a class="navbar-brand" href="#">ARCHCONSULTING</a>
                <h1 class="navbar-text navbar-title-rms">Redaktionssystem</h1>
            </div>

            <!-- Top Menu -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $fnCust.' '.$lnCust ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Einstellungen</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="../../../platform/public/php/login.php"><i class="fa fa-fw fa-power-off"></i> Abmelden</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Sidebar Menü (Element klappen ein bei kleinem Viewport) -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <form action="index.php" method="POST">
                    <ul class="nav navbar-nav side-nav">
                        
                        <li id="homepage_li">
                            <a id="homepagegroup" href="javascript:;" data-toggle="collapse" data-target="#homepage"><i class="fa fa-calendar"></i>Homepage<i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="homepage" class="collapse">
                                <li>
                                    <button type="submit" id="hptext" name="nav" value="1"  class="<?php echo $active3; ?>">Textanpassung</button>
                                    <!--<a href="index.php?id=3" id="terminplan" >Terminplan</a>-->
                                </li>
                                <li>
                                    <button type="submit" id="hpimages" name="nav" value="2"  class="<?php echo $active4; ?>">Bildanpassung</button>
                                    <!--<a href="index.php?id=4" id="events">Events</a>-->
                                </li>
                            </ul>
                        </li>
                        
                        <li id="platform_li">
                            <a id="platformgroup" href="javascript:;" data-toggle="collapse" data-target="#platform"><i class="fa fa-calendar"></i>Plattform<i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="platform" class="collapse">
                                <li>
                                    <button type="submit" id="userverwaltung" name="nav" value="3"  class="<?php echo $active3; ?>">Userverwaltung</button>
                                    <!--<a href="index.php?id=3" id="terminplan" >Terminplan</a>-->
                                </li>
                                <li>
                                    <button type="submit" id="globaladdress" name="nav" value="4"  class="<?php echo $active4; ?>">Globale Adressliste</button>
                                    <!--<a href="index.php?id=4" id="events">Events</a>-->
                                </li>
                            </ul>
                        </li>

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
                            include ('textChange.php');
                            break;
                        case 2:
                            include ('imageupload.php');
                            break;
                        case 3:
                            include ('userverwaltung.php');
                            break;
                        case 4:
                            include ('addresslist.php');
                            break;
                        default:
                            echo'<p>Error Loading Content</p>';
                    }
                }else{
                    include ('textChange.php');
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

    <!-- Timeline -->
    <!--<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>-->
    <script type="text/javascript" src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.9/js/dataTables.bootstrap.min.js"></script>
    

    <!-- Adressliste -->
    <!--<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>-->
    <script src="https://cdn.datatables.net/buttons/1.0.0/js/dataTables.buttons.min.js"></script>

    <script src="//cdn.datatables.net/buttons/1.0.0/js/buttons.html5.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <!-- Gallery -->

    <!-- Überschreibt URL -->
   
    <script type="text/javascript" src="../js/script.js"></script>



</body>

</html>
