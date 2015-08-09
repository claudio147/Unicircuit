<?php

//Einbindung Librarys
require_once ('../../library/public/database.inc.php');

$sections= array(1=>'Landing page', 2=>'Slider Mobile', 3=>'Slider Desktop', 
        4=>'Über Uns Links', 5=>'Über Uns Rechts');


if (isset($_POST['Selection'])) {
    $select=$_POST['Selection'];
    
    //Datenbankverbindung
    $link = connectDB();
    $sql= allImagesOfIdHTML($select);
    
    $result = mysqli_query($link, $sql);

}


//Dateiupload
if(isset($_POST['upload'])){
    $select= $_POST['select'];
    //Array mit Statusmeldungen
    $errorstatus= array('Alles OK', 'Zeitüberschreitung', 'Grössenüberschreitung',
        'Nicht vollständig', 'Keine Datei hochgeladen');

    //Zielverzeichnis, in welches die Datei kopiert wird
    $uploaddir= '../../productsite/public/images/';

    $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
    $extension= strrchr($_FILES['userfile']['name'],'.');
    $file= $filename.$extension;
    
    //Dateipfad mit Dateinamen zusammensetzen
    $uploadfile= $uploaddir.basename($file);
    
    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
        echo'<p>Datei wurde erfolgreich hochgeladen.</p>';
        $orgname= $_FILES['userfile']['name'];
       
        if(isset($_POST['comment'])){
            $comment= $_POST['comment'];
        }else{
            $comment= '';
        }
        



        $link = connectDB();
        $sql= saveImageToDB($orgname, $comment, $file, $uploaddir, $select);
        $status= mysqli_query($link, $sql);


        //Errorcode der Übertragung abfragen
        $code= $_FILES['userfile']['error'];

        //Übersetzer Error-Code in Worten (sh. Array) ausgeben
        echo'<p>Fehlerstatus: '.$errorstatus[$code].'</p>';
        }else{
            echo'<p>Datei konnte nicht hochgeladen werden!</p>';
    }
}
?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
    <title>Dateiupload auf Server</title>
    <style>
    table {
       border: 1px solid gray;   
    }
    th, td {
       border: 1px solid gray;
       padding: 3px;
    }
    th {
       font-weight: bold;
    }
    td>img{
        max-width: 200px;
        max-height: 200px;
    }
    </style>
</head>
<body>

<form action="imageupload.php" method="POST" >
  <p>Auswahl Selektion:</p>
  <select name="Selection" onchange="this.form.submit()">
    <?php
    foreach ($sections as $key => $value) {
        if($key==$select){
            echo'<option value='.$key.' selected="selected">'.$value.'</option>';
        }else{
            echo'<option value='.$key.'>'.$value.'</option>';
        }
    }
    ?>
 </select>
</form>


    <p>Dateiupload auf Server</p>
    <fieldset>
        <legend>Bitte Datei wählen</legend>
        <!-- Spezieller encryption-type für Formulardaten-->
        <form enctype="multipart/form-data" action="imageupload.php" method="POST">
            <!-- Grössenbegrenzung seitens HTML. NICHT SICHER!!!-->
            <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/>
            <input type="hidden" name="select" value="<?php echo $select ?>"/>
            <!-- Anzeige des Datei Choosers-->
            <input name="userfile" type="file"/>
            <p>Bildbeschreibung (Alt-Text)</p>
            <input type="text" name="comment" /><br /><br />
            <input type="submit" name="upload" value="hochladen"/>
        </form>
    </fieldset>


<?php
if (isset($_POST['Selection'])){
   echo'<form action="imageupload.php" method="POST">';
   echo'<table>';
   echo'<tr>';
   echo'<th>Datum</th>';
   echo'<th>Zeit</th>';
   echo'<th>Originalname</th>';
   echo'<th>Bildvorschau</th>';
   echo'<th>Löschen</th>';
   echo'</tr>';
    while($row= mysqli_fetch_array($result)){
        echo'<tr>';
        echo'<td>'.$row['Date'].'</td>';
        echo'<td>'.$row['Time'].'</td>';
        echo'<td>'.$row['Orgname'].'</td>';
        echo'<td><img src="'.$row['Path'].$row['Filename'].'" alt="'.$row['Comment'].'"></td>';
        echo'<td><input type="submit" name="delete" value="'.$row['ID'].'"/></td>';
        echo'</tr>'; 
    }
    echo'</table>';
    echo'<input type="submit" name="save" value="Speichern"/>';
    echo'</form>';
}
    ?>
    <img src="../log/client-1.jpg" alt="">
    
</body>
</html>