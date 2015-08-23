<?php



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UNICIRCUIT</title>

    <!-- CSS 3rd Party -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/sb-admin.css" rel="stylesheet">
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.8/css/jquery.dataTables.min.css">

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
                <a class="navbar-brand" href="index.html"><img src="../img/architect1/personal/logo.gif" alt="Logo"></a>
                <h2 class="navbar-text">EFH Mustermann</h2>
            </div>

            <!-- Top Menu -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>Max Muster<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Sidebar Menü (Element klappen ein bei kleinem Viewport) -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="index.php?id=1"><i class="fa fa-home"></i>Home</a>
                    </li>
                    <li>
                        <a href="index.php?id=2"><i class="fa fa-tachometer"></i>Chronik</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-calendar"></i>Termine <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="index.php?id=3">Terminplan</a>
                            </li>
                            <li>
                                <a href="index.php?id=4">Events</a>
                            </li>
                            <li>
                                <a href="index.php?id=5">Deadlines</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="index.php?id=6"><i class="fa fa-list-ul"></i>Adressliste</a>
                    </li>
                    <li>
                        <a href="index.php?id=7"><i class="fa fa-camera"></i>Fotogalerie</a>
                    </li>
                    <li>
                        <a href="index.php?id=8"><i class="fa fa-comments"></i>Kontakt</a>
                    </li>
                    <li>
                        <a href="index.php?id=9"><i class="fa fa-cloud-download"></i>SIA Baujournal</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <?php
                if(isset($_GET['id'])){
                    switch($_GET['id']){
                        case 1:
                            echo'<p>HOME SEITE</p>';
                            break;
                        case 2:
                            include ('timeline.php');
                            break;
                        case 3:
                            echo'<p>TERMINPLAN</p>';
                            break;
                        case 4:
                            echo'<p>EVENTS</p>';
                            break;
                        case 5:
                            echo'<p>DEADLINES</p>';
                            break;
                        case 6:
                            include ('addresslist.php');
                            break;
                        case 7:
                            echo'<p>FOTOGALERIE</p>';
                            break;
                        case 8:
                            echo'<p>KONTAKT</p>';
                            break;
                        case 9:
                            include ('sia.php');
                            break;
                        default:
                            echo'<p>Error Loading Content</p>';
                    }
                    
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
	<script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
	<script src="//cdn.rawgit.com/noelboss/featherlight/1.3.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="../js/imgLiquid-min.js"></script>
    <!-- Adressliste -->
    <script src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.0/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.0.0/js/buttons.html5.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>

	<script src="../js/script.js"></script>
    <script src="../js/weather.js"></script>
	

</body>

</html>
