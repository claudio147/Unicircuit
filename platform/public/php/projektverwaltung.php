<?php
/*
*   Unicircuit Plattform
*   «Projektverwaltung (Controller für Aktive Projekte und Archiv)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/
        
//Session starten oder wiederaufnehmen
 session_start();

if(!isset($_SESSION['IdUser']) || $_SESSION['UserType'] != 2) {
    header('Location: login.php?denied=1');
    exit();
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
    
$link = connectDB();

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

        <!--CSS 3rd Party-->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.min.css" rel="stylesheet" type="text/css">
        <link href="../css/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css" rel="stylesheet" type="text/css">
        
        <!--CSS Custom-->
        <link href="../css/style.css" rel="stylesheet" type="text/css">
        
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
        
        <div id="wrapper wrapper-pv" class="wrapper-pv">
        
            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

                <!-- Logo und "Toggle" -->
                <div class="navbar-header navbar-header-pv">
                    <a class="navbar-brand" href="#"><img src="<?php echo $logo; ?>" alt="Logo" class="logo-pv"></a>
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

            <div class="container container-pv">
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
            </div>
        </div>
        
         
    <!--JS 3rd Party-->
    <script src="../js/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../css/jquery-ui-1.11.4.custom/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../js/imgLiquid-min.js" type="text/javascript"></script>
    <script src="../js/jquery.popconfirm.js" type="text/javascript"></script>
    <script>
        window.history.pushState('', '', '/php/projektverwaltung.php');
        //window.history.pushState('', '', '/diplomarbeit/platform/public/php/projektverwaltung.php');
    </script>
    <script src="../js/script-pv.js" type="text/javascript"></script>
    
    </body>
</html>