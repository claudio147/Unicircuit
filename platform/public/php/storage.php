<?php

/*
 *  Programmpunkt x.x Archiv
 */

//Session starten oder wiederaufnehmen
 session_start();
    
 if(!isset($_SESSION['IdUser']) || $_SESSION['UserType'] != 2) {
    header('Location: login.php?denied=true');
}

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

//Holt Architekten User Daten
$id = $_SESSION['IdUser'];
//DB Verbindung herstellen
$link = connectDB();
//Beschaffung aller Projekte des Architekten die als Archiv gekennzeichnet sind.
$sql = getProjectsByArchStore($id);

$result = mysqli_query($link, $sql);

//Ausgabe Warnung
echo'<div class="alert alert-danger" role="alert">Archivierte Projekte können nicht zurückgeholt werden. <br> 
    Bauherren haben keinen Zugriff auf Archiv Projekte</div>';
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
      <div class="container">  
        <!-- Projekt ProjektInfos -->
    <!-- Modal Global-->
    <div class="modal" id="editPost" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="storage.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Projekt bearbeiten</h4>
                    </div>
                        <div class="modal-body">
                            <div id="editContainer_storage">

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
    while($row= mysqli_fetch_array($result)){
    
    echo'<div class="post row">';
    echo '<h3><button type="button" class="btn_postEdit_storage" data-toggle="modal" data-target="#editPost" value="'.$row['IdProject'].'"><i class="fa fa-pencil-square-o"></i></button>'.$row['Title'].'
           <form action="projektverwaltung.php" method="POST">
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