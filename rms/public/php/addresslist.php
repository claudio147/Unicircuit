<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');


?>

<div class="col-xs-12">
	<h2 class="modul-title">Adressdatenbank (Handwerker)</h2>


	<?php
	$sql=allGlobalAddress();
    $result= mysqli_query($link, $sql);

    echo'<table class="table order hover" id="addresslist-rms">';
    echo'<thead>';
    echo'<tr>';
    echo'<th>BKP</th>';
    echo'<th>Firma</th>';
    echo'<th>PLZ</th>';
    echo'<th>Ort</th>';
    echo'<th>Land</th>';
    echo'<th>Telefon</th>';
    echo'<th>Email</th>';
    echo'<th>Homepage</th>';
    echo'<th></th>';
    echo'</tr>';
    echo'</thead>';
    echo'<tbody>';

    while($row= mysqli_fetch_array($result)){
        echo'<tr>';
        echo'<td>'.$row['BKP'].'</td>';
        echo'<td><a href="http://'.$row['Homepage'].'/" target="_blank">'.$row['Company'].'</a></td>';
        echo'<td>'.$row['ZIP'].'</td>';
        echo'<td>'.$row['City'].'</td>';
        echo'<td>'.$row['Country'].'</td>';
        echo'<td>'.$row['PhoneNumber'].'</td>';
        echo'<td>'.$row['Email'].'</td>';
        echo'<td>'.$row['Homepage'].'</td>';
        echo'<td><button type="button" class="btn btn-default btn_add" data-toggle="modal" data-target="#modalSearch" data-dismiss="modal" value="'.$row['IdGlobalAddress'].'">Details</button></td>';
        echo'</tr>';
    }
    echo'</tbody>';
    echo'</table>';


    ?>


</div>