<?php
require_once ('../../../library/public/database.inc.php');
$projectID=2;
$uploaddir= '../img/architect1/project1/img/';


$link= connectDB();

//Speichert einen neuen Eintrag in DB
if(isset($_POST['submit'])){

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $visible = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_NUMBER_INT);
    $date = date("Y-m-d");
    $time = date("H:i:s");
   
    //Bildupload
    if(!empty($_FILES['userfile']['name'])){

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
    }else{
        $uploaddir= '../img/';
        $file= 'placeholder.png';
        $orgname= 'placeholder.png';

        $sql= addPostwithIMG($projectID, $visible, $file, $orgname, $uploaddir, $title, $content, $date, $time);
        $status= mysqli_query($link, $sql);
        if(!$status){
            echo'<p>Fehlgeschlagen</p>';
        }
    }
    header("Location: index.php?id=2");
}

//Updated einen bestehenden Eintrag in DB
if(isset($_POST['edit'])){

    $postID= filter_input(INPUT_POST, 'postID', FILTER_SANITIZE_NUMBER_INT);
    $title= filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content= filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $visible= filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_NUMBER_INT);
    $hashName= filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_STRING);
    $orgName= filter_input(INPUT_POST, 'orgName', FILTER_SANITIZE_STRING);
    $path= filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);
    $date = date("Y-m-d");
    $time = date("H:i:s");

    //Wenn neues File hochgeladen wird
    if(!empty($_FILES['userfile']['name'])){
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


            $sql= updatePost($postID, $visible, $file, $orgname, $uploaddir, $title, $date, $time, $content);
            $status= mysqli_query($link, $sql);

            //Errorcode der Übertragung abfragen
            $code= $_FILES['userfile']['error'];

            //Übersetzer Error-Code in Worten (sh. Array) ausgeben
            echo'<p>Fehlerstatus: '.$errorstatus[$code].'</p>';
        }else{
            echo'<p>Datei konnte nicht hochgeladen werden!</p>';
    }}else{
        $sql= updatePost($postID, $visible, $hashName, $orgName, $path, $title, $date, $time, $content);
        $status= mysqli_query($link, $sql);
        if(!$status){
            echo'<p>Fehlgeschlagen</p>';
        }
    }
    header("Location: index.php?id=2");
}


//Löschfunktion
if(isset($_POST['delete'])){

    if(!empty($_POST['postID'])){
        $id=$_POST['postID'];
            $link= connectDB();
            $sql= selectPostIMG($id);
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            $fina=$row['HashName'];
            $path= $row['Path'];
            
            //Überprüfung ob das Platzhalter Bild eingestzt ist
            if($fina == 'placeholder.png'){
                $sql2= deletePost($id);
                $status= mysqli_query($link, $sql2);
                if($status == true){
                    echo '<p>Datensatz erfolgreich gelöscht</p>';
                }else{
                    echo '<p>Datensatz konnte nicht gelöscht werden</p>';
                }
            }else{
                if(unlink($path.$fina)){
                    $sql2= deletePost($id);
                    $status= mysqli_query($link, $sql2);
                    if($status == true){
                        echo '<p>Datensatz erfolgreich gelöscht</p>';
                    }else{
                        echo '<p>Datensatz konnte nicht gelöscht werden</p>';
                    }
                }else{
                    echo'<p>Löschen fehlgeschlagen</p>';
                }
            }
                
    }
    header("Location: index.php?id=2");
}





?>




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



    <!-- Modal Global-->
    <div class="modal" id="editPost" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="timeline.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Chronikbeitrag bearbeiten</h4>
                    </div>
                        <div class="modal-body">
                            <div id="editContainer">

                                <!-- Platzhalter für ajax Inhalt -->

                            </div>       
                        </div>
                    <div class="modal-footer">
                        <input type="submit" name="delete" value="Löschen" class="btn btn-default"/>
                        <input type="submit" name="edit" value="Speichern" class="btn btn-default"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    </div>
              </form>

            </div>

        </div>
    </div>

</div>

<?php
$sql= selectPosts($projectID);
$result = mysqli_query($link, $sql);


echo'<div class="container">';
// Ausgabe Timeline
while($row= mysqli_fetch_array($result)){
    if($row['Id_visible']==1){
        $lock='<i class="fa fa-lock"></i>';
    }else{
        $lock='';
    }
    echo'<div class="post row">';
    echo'<h3><button type="button" class="btn_postEdit" data-toggle="modal" data-target="#editPost" value="'.$row['IdTimeline'].'"><i class="fa fa-pencil-square-o"></i></button>'.$row['Title'].'  '.$lock.'</h3>';
    echo'<p class="date">'.$row['Date'].', '.$row['Time'].'</p>';
    echo'<div class="col-sm-2 imgLiquidFill imgLiquid ">';
    echo'<a href="#" data-featherlight="'.$row['Path'].$row['HashName'].'"><img alt="" src="'.$row['Path'].$row['HashName'].'"/></a>';
    echo'</div>';
    echo'<div class="col-sm-6">';
    echo'<p>'.$row['Description'].'</p>';
    echo'</div>';
    echo'</div>';
}
echo'</div>';
?>


