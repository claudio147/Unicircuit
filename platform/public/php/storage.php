<!--
*   Unicircuit Plattform
*   «Archiv (Projektverwaltung)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
-->
<?php

//Session starten oder wiederaufnehmen
 session_start();
    
 if(!isset($_SESSION['IdUser']) || $_SESSION['UserType'] != 2) {
    header('Location: login.php?denied=1');
    exit();
}

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/security.inc.php');

//Holt Architekten User Daten
$id = $_SESSION['IdUser'];
//DB Verbindung herstellen
$link = connectDB();
//Beschaffung aller Projekte des Architekten die als Archiv gekennzeichnet sind.
$sql = getProjectsByArchStore($id);

$result = mysqli_query($link, $sql);

//Löschen eines Archivierten Projektes, Löscht auch dazugehörigen Bauherr und Verzeichnis
if(isset($_POST['idUser'])){
    $idBauherr = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT);
    $idProject = filter_input(INPUT_POST, 'postID', FILTER_SANITIZE_NUMBER_INT);
    $path = '../architects/architect_'.$id.'/project_'.$idProject.'/' ;
    
    //Funtkion zum löschen des Ordners mit Inhalt des Projektes
    $handle = opendir($path);
        if($handle)
           {
                while ( false !== ($file = readdir($handle)) )
                {
                if ( $file != "." and $file != ".." )
                    {
                     unlink($path.$file);
                    }
                }   
            }
            rmdir($path);
    
     //Löscht alle DB Einträge des Projektes
    $sql = deleteProject($idProject);
    $status = mysqli_query($link, $sql);
    
    //Löscht den dazugehörigen Bauherren
    $sql = deleteBauherr($idBauherr);
    $statusDel = mysqli_query($link, $sql);
    
    if(isset($statusDel)) {
        //Speichert erfolgreiche Löschung in Variable zur Ausgabe
        header('Location: projektverwaltung.php?nav=2&status=10');
        exit();
    } else {
        header('Location: projektverwaltung.php?nav=2&status=9');
        exit();
    }           
}
?>
 
          
<!-- Projekt ProjektInfos -->
<!-- Modal Global-->
<div class="modal" id="editPost" role="dialog" style="z-index: 2;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form enctype="multipart/form-data" action="storage.php" method="POST" id="deleteProject" name="deleteProject">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Projekt Details</h4>
                </div>
                <div class="modal-body">
                    <div id="editContainer_storage">

                        <!-- Platzhalter für Ajax Inhalt -->

                    </div>       
                </div>
                <div class="modal-footer">
                    <input type="submit" name="delete" value="Löschen" class="btn btn-default"/>

                    <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                </div>
          </form>
        </div>

    </div>
</div>
 
<!--Warnung beim löschen-->
<div id="dialog" title="Projekt Löschen">
    <i class="fa fa-exclamation-triangle fa-4x"></i>
    <p>Möchten Sie das Projekt wirklich löschen?</p>
    <p>Das Löschen eines Projekts kann nicht wiederrufen werden.</p>
</div>

         
<?php
    //Rückgabemeldung für Eingaben in Lightboxen
    $stat = checkResponse($response);
    echo $stat;
               
    $projectsId = array();
    //Ausgabe der Archivierten Projekte
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
        echo'<button type="button" class="btn-pv btn btn-default btn_postEdit_storage" data-toggle="modal" data-target="#editPost" value="'.$row['IdProject'].'"><i class="fa fa-pencil-square-o"></i> Details</button>';
        echo'</div>';
        echo'<div class="col-xs-6">';
        echo'<form action="index.php" method="POST">
            <button type="submit" name ="goto" class="btn-pv btn btn-default" value="'.$row['IdProject'].'"><i class="fa fa-share"></i> öffnen</button>
            </form>';
        echo'</div>';
        echo'</div>';
        echo'</div>';
        echo'</div>';
        
        array_push($_SESSION['IdProject'], $row['IdProject']);
    }
?>