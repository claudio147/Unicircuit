<?php

/*
 *  Programmpunkt x.x Archiv
 */

//Session starten oder wiederaufnehmen
 session_start();
    
 if(!isset($_SESSION['IdUser']) || $_SESSION['UserType'] != 2) {
    header('Location: denied.php');
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

while($row= mysqli_fetch_array($result)){
   
    echo'<div class="post row">';
    echo '<h3><button type="button" class="btn_postEdit_pv" data-toggle="modal" data-target="#editPost" value="'.$row['IdProject'].'"><i class="fa fa-pencil-square-o"></i></button>'.$row['Title'].'
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