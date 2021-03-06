<?php
/*
*   Unicircuit Plattform
*   «Chronik (Modul)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/
        
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/security.inc.php');

//Session starten oder wiederaufnehmen
session_start();

 //User wird anhand Session ID Überprüft
 
 $idUser = $_SESSION['IdUser'];
 $sessionId = session_id();
 $valide = checkSessionId($idUser, $sessionId);
 //Stimmt SessionID und SessionId aus DB nicht überein wird der User zum Login
 //weitergeleitet.
 if($valide == false) {
    header('Location: login.php?denied=1');
    exit(); 
 }

$link= connectDB();

//Speichert einen neuen Eintrag in DB
if(isset($_POST['submit'])){
    $projectID= filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $visible = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_NUMBER_INT);
    $date = date("Y-m-d");
    $time = date("H:i:s");
    
    // Fehler im Eingabefeld?
    if (empty($title) || strlen($title) < 5) {
        $error = true;
    }
    if(empty($content) || strlen($content) < 5){
        $error=true;
    }
    if(empty($visible)){
        $error=true;
    }
    
    if(!isset($error)){
        //Erzeugt Pfad für Bildupload
        $sql= getNameCust($projectID);
        $result= mysqli_query($link, $sql);
        $row= mysqli_fetch_array($result);
        $idArch= $row['Fk_IdArchitect'];

        $uploaddir = '../architects/architect_'.$idArch.'/project_'.$projectID.'/';

        //Bildupload
        if(!empty($_FILES['userfile']['name'])){       

            $tempna= $_FILES['userfile']['tmp_name'];
            $orgname= $_FILES['userfile']['name'];
            $size= $_FILES['userfile']['size'];
            $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
            $extension= strrchr($_FILES['userfile']['name'],'.');
            $file= $filename.$extension;

            //Dateipfad mit Dateinamen zusammensetzen
            $uploadfile= $uploaddir.basename($file);

            //Ermittle Bildgrösse
            $image_attributes = getimagesize($tempna); 
            $image_width_old = $image_attributes[0];
            $image_height_old = $image_attributes[1];

            //Überprüft Dateityp
            if(!checkImageType($file)){
                header('Location: index.php?id=2&status=6&project='.$projectID);
                exit();
            }

            //Überprüft Dateigrösse
            if($size > 4100000){
                header('Location: index.php?id=2&status=7&project='.$projectID);
                exit();
            }

            //Verkleinert Bilder über 800px Seitenlänge und speichert diese im verzeichnis,
            //Bilder unter 800px Seitenlänge werden direkt ins Verzeichnis gespeichert
            if($image_width_old>800 || $image_height_old>800){
                if(resizeImage($tempna, $uploadfile, 800)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;
                }
            }else{
                if(move_uploaded_file($tempna, $uploadfile)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;
                }
            }

            if($statusUpload){
                //Erfolgreich gespeichert --> Speichert DB Eintrag
                $sql= addPostwithIMG($projectID, $visible, $file, $orgname, $uploaddir, $title, $content, $date, $time);
                $status= mysqli_query($link, $sql);

                header('Location: index.php?id=2&status=0&project='.$projectID);
                exit();
            }else{
                header('Location: index.php?id=2&status=1&project='.$projectID);
                exit();
            }

        }else{
            $uploaddir= '../img/';
            $file= 'placeholder.png';
            $orgname= 'placeholder.png';

            $sql= addPostwithIMG($projectID, $visible, $file, $orgname, $uploaddir, $title, $content, $date, $time);
            $status= mysqli_query($link, $sql);
            if(!$status){
                header('Location: index.php?id=2&status=3&project='.$projectID);
                exit();
            }else{
                header('Location: index.php?id=2&status=0&project='.$projectID);
                exit();
            }
        }
    }else{
        header('Location: index.php?id=2&status=3&project='.$projectID);
        exit();
    } 
}

//Updated einen bestehenden Eintrag in DB
if(isset($_POST['edit'])){
    
    $projectID= filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
    $postID= filter_input(INPUT_POST, 'postID', FILTER_SANITIZE_NUMBER_INT);
    $title= filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content= filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $visible= filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_NUMBER_INT);
    $hashName= filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_STRING);
    $orgName= filter_input(INPUT_POST, 'orgName', FILTER_SANITIZE_STRING);
    $path= filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);
    $date = date("Y-m-d");
    $time = date("H:i:s");
    
    // Fehler im Eingabefeld?
    if (empty($title) || strlen($title) < 5) {
      $error = true;
    }
    if(empty($content) || strlen($content) < 5){
        $error=true;
    }
    if(empty($visible)){
        $error=true;
    }
    
    if(!isset($error)){
        //Erzeugt Pfad für Bildupload
        $sql= getNameCust($projectID);
        $result= mysqli_query($link, $sql);
        $row= mysqli_fetch_array($result);
        $idArch= $row['Fk_IdArchitect'];

        $uploaddir = '../architects/architect_'.$idArch.'/project_'.$projectID.'/' ;

        //Wenn neues File hochgeladen wird
        if(!empty($_FILES['userfile']['name'])){

            $tempna= $_FILES['userfile']['tmp_name'];
            $orgname= $_FILES['userfile']['name'];
            $size= $_FILES['userfile']['size'];
            $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
            $extension= strrchr($_FILES['userfile']['name'],'.');
            $file= $filename.$extension;

            //Dateipfad mit Dateinamen zusammensetzen
            $uploadfile= $uploaddir.basename($file);

            //Ermittle Bildgrösse
            $image_attributes = getimagesize($tempna); 
            $image_width_old = $image_attributes[0];
            $image_height_old = $image_attributes[1];

            //Überprüft Dateityp
            if(!checkImageType($file)){
                header('Location: index.php?id=2&status=6&project='.$projectID);
                exit();
            }

            //Überprüft Dateigrösse
            if($size > 4100000){
                header('Location: index.php?id=2&status=7&project='.$projectID);
                exit();
            }

            //Verkleinert Bilder über 800px Seitenlänge und speichert diese im verzeichnis,
            //Bilder unter 800px Seitenlänge werden direkt ins Verzeichnis gespeichert
            if($image_width_old>800 || $image_height_old>800){
                if(resizeImage($tempna, $uploadfile, 800)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;
                }
            }else{
                if(move_uploaded_file($tempna, $uploadfile)){
                    $statusUpload=true;
                }else{
                    $statusUpload=false;
                }
            }

            if($statusUpload){
                //Erfolgreich gespeichert --> Speichert DB Eintrag
                $sql= updatePost($postID, $visible, $file, $orgname, $uploaddir, $title, $date, $time, $content);
                $status= mysqli_query($link, $sql);

                header('Location: index.php?id=2&status=0&project='.$projectID);
                exit();
            }else{
                header('Location: index.php?id=2&status=1&project='.$projectID);
                exit();
            }
        }else{
            $sql= updatePost($postID, $visible, $hashName, $orgName, $path, $title, $date, $time, $content);
            $status= mysqli_query($link, $sql);
            if(!$status){
                header('Location: index.php?id=2&status=1&project='.$projectID);
                exit();
            }else{
                header('Location: index.php?id=2&status=5&project='.$projectID);
                exit();
            }
        }
    }else{
        header('Location: index.php?id=2&status=1&project='.$projectID);
        exit();
    }   
}

//Löschfunktion
if(isset($_POST['delete'])){

    if(!empty($_POST['postID'])){
        $projectID= filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
        $id=$_POST['postID'];
        $link= connectDB();
        $sql= selectPostIMG($id);
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        $fina=$row['HashName'];
        $path= $row['Path'];

        //Erzeugt Pfad für Bildupload
        $sql= getNameCust($projectID);
        $result= mysqli_query($link, $sql);
        $row= mysqli_fetch_array($result);
        $idArch= $row['Fk_IdArchitect'];

        $uploaddir = '../architects/architect_'.$idArch.'/project_'.$projectID.'/' ;

        //Überprüfung ob das Platzhalter Bild eingestzt ist
        if($fina == 'placeholder.png'){
            $sql2= deletePost($id);
            $status= mysqli_query($link, $sql2);
            if($status == true){
                header('Location: index.php?id=2&status=4&project='.$projectID);
                exit();
            }else{
                header('Location: index.php?id=2&status=2&project='.$projectID);
                exit();
            }
        }else{
            if(unlink($path.$fina)){
                $sql2= deletePost($id);
                $status= mysqli_query($link, $sql2);
                if($status == true){
                    header('Location: index.php?id=2&status=4&project='.$projectID);
                    exit();
                }else{
                    header('Location: index.php?id=2&status=2&project='.$projectID);
                    exit();
                }
            }else{
                header('Location: index.php?id=2&status=2&project='.$projectID);
                exit();
            }
        }    
    }
}

?>

<div class="col-xs-12">
    <!--Lightboxen (Modals)-->
    <div class="container modalgroup">
    <?php
        $sql=getStatusProject($projectID);
        $result=  mysqli_query($link, $sql);
        $row=  mysqli_fetch_array($result);
        $statusStorage=$row['Storage'];
        if($usertyp==2 && $statusStorage!=1){ 
            echo'<button type="button" class="btn btn-default" data-toggle="modal" data-target="#newPost"><i class="fa fa-plus-circle"></i> hinzufügen</button>';
        } 
    ?> 
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
                                <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                                <label for="title">Titel*</label>
                                <input id="title" type="text" name="title" class="form-control">
                                <label for="content">Inhalt*</label>
                                <textarea id="content" name="content" class="form-control" rows="8"></textarea>
                                <label for="visibility">Sichtbarkeit*</label>
                                <div id="visibility" class="radio near">
                                    <label class="near">
                                        <input type="radio" name="visible" value="1" checked="checked"/>
                                        Nur Architekt
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="near">
                                        <input type="radio" name="visible" value="2"/>
                                        Architekt und Bauherr
                                    </label>
                                </div>
                                <label for="upload">Bildupload</label>
                                <input id="upload" type="file" name="userfile"/>
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
                            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
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
    if(isset($_GET['status'])){
        $response = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_NUMBER_INT);
        //Rückgabemeldung für Event-Handling Timeline
        $stat = checkEventTimeline($response);
        echo $stat;
    }
?>

<?php

$sql= selectPosts($projectID);
$result = mysqli_query($link, $sql);

echo'<div class="container timeline-container">';
// Ausgabe Timeline
while($row= mysqli_fetch_array($result)){
    //Architekt sieht alle
    if($usertyp==2){
        $date = date('d.m.Y', strtotime($row['Date']));
        $time= substr($row['Time'],0,5);
        if($row['Id_visible']==1){
            $lock='<i class="fa fa-lock"></i>';
        }else{
            $lock='';
        }
        echo'<div class="post row">';            
        echo'<div class="col-xs-4 col-sm-3 col-md-2 imgLiquidFill imgLiquid">';
        echo'<a href="#" data-featherlight="'.$row['Path'].$row['HashName'].'"><img class="post-img" alt="" src="'.$row['Path'].$row['HashName'].'"/></a>';
        echo'</div>';
        echo'<div class="col-xs-8 col-sm-9 col-md-10">';
        echo'<h3 class="post-title">';
        if($statusStorage!=1){
            echo'<button type="button" class="btn_postEdit" data-toggle="modal" data-target="#editPost" value="'.$row['IdTimeline'].'"><i class="fa fa-pencil-square-o"></i></button>';
        }
        echo $row['Title'].'  '.$lock.'</h3>';  
        echo'<p class="post-date">'.$date.', '.$time.'</p>';
        echo'<p>'.$row['Description'].'</p>';
        echo'</div>';
        echo'</div>';
    
    //Bauherr sieht nur Einträge für ihn    
    }else if($usertyp==3 && $row['Id_visible']==2){
        $date = date('d.m.Y', strtotime($row['Date']));
        $time= substr($row['Time'],0,5);
        echo'<div class="post row">';            
        echo'<div class="col-xs-4 col-sm-3 col-md-2 imgLiquidFill imgLiquid">';
        echo'<a href="#" data-featherlight="'.$row['Path'].$row['HashName'].'"><img class="post-img" alt="" src="'.$row['Path'].$row['HashName'].'"/></a>';
        echo'</div>';
        echo'<div class="col-xs-8 col-sm-9 col-md-10">';
        echo'<h3 class="post-title">'.$row['Title'].'</h3>';  
        echo'<p class="post-date">'.$date.', '.$time.'</p>';
        echo'<p>'.$row['Description'].'</p>';
        echo'</div>';
        echo'</div>';
    }   
}
echo'</div>';
echo'</div>';
?>