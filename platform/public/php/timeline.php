<?php
require_once ('../../../library/public/database.inc.php');

//Platzhalter für Projekt ID aus Session
$projectID=2;
$uploaddir= '../img/architect1/project1/img/';


$link= connectDB();

if(isset($_POST['submit'])){
    
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $visible = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_NUMBER_INT);
    $date = date("Y-m-d");
    $time = date("H:i:s");
   
    //Bildupload
    if(!empty($_FILES)){

    //Array mit Statusmeldungen
    $errorstatus= array('Alles OK', 'Zeitüberschreitung', 'Grössenüberschreitung',
        'Nicht vollständig', 'Keine Datei hochgeladen');
    

    $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
    $extension= strrchr($_FILES['userfile']['name'],'.');
    $file= $filename.$extension;
    
    //Dateipfad mit Dateinamen zusammensetzen
    $uploadfile= $uploaddir.basename($file);
        if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
            echo'<p>Datei wurde erfolgreich hochgeladen.</p>';
            $orgname= $_FILES['userfile']['name'];


            $sql= addPostwithIMG($projectID, $visible, $file, $orgname, $uploaddir, $title, $content, $date, $time);
            $status= mysqli_query($link, $sql);

            //Errorcode der Übertragung abfragen
            $code= $_FILES['userfile']['error'];

            //Übersetzer Error-Code in Worten (sh. Array) ausgeben
            echo'<p>Fehlerstatus: '.$errorstatus[$code].'</p>';
        }else{
            echo'<p>Datei konnte nicht hochgeladen werden!</p>';
    }
}



}


?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />       
<!-- CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/style.css">

</head>
<body>

<!--Lightboxen (Modals)-->
<div class="container">

    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#newPost">+ hinzufügen</button>

    <!-- Modal Global-->
    <div class="modal" id="newPost" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="timeline.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Chronikbeitrag hinzufügen</h4>
                    </div>
                        <div class="modal-body">
                            <div id="input_container">

                                <p>Titel*</p>
                                <input type="text" name="title">
                                <p>Inhalt*</p>
                                <textarea name="content"></textarea>
                                <p>Sichtbarkeit*</p>
                                <p>
                                    <input type="radio" name="visible" value="1" checked="checked"/>  Nur Architekt
                                    <input type="radio" name="visible" value="2"/>  Architekt und Bauherr
                                </p>
                                <p>Bildupload</p>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/> <!-- Grössenbegrenzung (nicht Sicher) -->
                                <input type="file" name="userfile"/>


                            </div>       
                        </div>
                    <div class="modal-footer">
                        <input type="submit" name="submit" value="Speichern" class="btn btn-default"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    </div>
              </form>

            </div>

        </div>
    </div>

</div>

<?php
$sql= selectPost($projectID);
$result = mysqli_query($link, $sql);

echo'<div class="container">';
while($row= mysqli_fetch_array($result)){
    echo'<div class="post row">';
    echo'<h3>'.$row['Title'].'</h3>';
    echo'<p class="date">'.$row['Date'].', '.$row['Time'].'</p>';
    echo'<div class="col-sm-2">';
    echo'<img src="'.$row['Path'].$row['HashName'].'">';
    echo'</div>';
    echo'<div class="col-sm-6">';
    echo'<p>'.$row['Description'].'</p>';
    echo'</div>';
    echo'</div>';
}
echo'</div>';
?>



<!-- JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="../js/script.js"></script>


</body>
</html>