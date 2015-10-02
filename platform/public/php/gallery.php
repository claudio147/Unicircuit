<?php
/*
*   Unicircuit Plattform
*   «Galerie (Modul)»
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

if(isset($_POST['submit'])){
    $projectID= filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
    //Überprüfung ob mind. 1 File ausgewählt wurde
    if($_FILES['my_file']['error'][0]!=4){ 
        
        $files= $_FILES['my_file'];
        $fileCount= count($files['name']);
        $comment= filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        $visible= $_POST['visible'];
        
        //Erzeugt Pfad für Bildupload
        $sql= getNameCust($projectID);
        $result= mysqli_query($link, $sql);
        $row= mysqli_fetch_array($result);
        $idArch= $row['Fk_IdArchitect'];

        $uploaddir = '../architects/architect_'.$idArch.'/project_'.$projectID.'/';
        
        //Schlaufe durch alle ausgewählten Files
        for($i=0; $i<$fileCount; $i++){
            $na= $files['name'][$i];            //Originalname
            $tempna= $files['tmp_name'][$i];    //Temp- Verzeichnis / Name
            $size= $files['size'][$i];          //Dateigrösse
            
            //Ermittle Bildgrösse
            $image_attributes = getimagesize($tempna); 
            $image_width_old = $image_attributes[0]; 
            $image_height_old = $image_attributes[1];
            
            //Datei in kryptischen einzigartigen Namen umbenennen (Überschreibungen verhindern)
            $filename= sha1(time().mt_rand().$na);
            $extension= strrchr($na, '.');

            $fileS= $filename.'_S'.$extension;
            $fileL= $filename.'_L'.$extension;
            
            $uploadfileS= $uploaddir.basename($fileS);
            $uploadfileL= $uploaddir.basename($fileL);
            
            $saveS=false;
            $saveL=false;
            
            if($size > 4100000){
                header('Location: index.php?id=7&status=3&project='.$projectID);
                exit();
            }
            
            //Überprüfung Dateiformat
            if(!checkImageType($uploadfileS)){
                header('Location: index.php?id=7&status=2&project='.$projectID);
                exit();
            }
            
            //Überprüfung Dateiformat
            if(!checkImageType($uploadfileL)){
                header('Location: index.php?id=7&status=2&project='.$projectID);
                exit();
            }
            
            if($image_width_old>600 || $image_height_old>600){
                //Verkleinert das Originalbild auf eine Länge von 600px
                if(resizeImage($tempna, $uploadfileS, 600)){
                    $saveS=true;
                }else{
                    $saveS=false;
                }
            }else{
                if(copy($tempna, $uploadfileS)){
                    $saveS=true;
                }else{
                    $saveS=false;
                }
            }
            
            if($image_width_old>1920 || $image_height_old>1920){
                //Verkleinert das Originalbild auf eine Länge von 1920px
                if(resizeImage($tempna, $uploadfileL, 1920)){
                    $saveL=true;
                }else{
                    $saveL=false;
                }
            }else{
                if(move_uploaded_file($tempna, $uploadfileL)){
                    $saveL=true;
                }else{
                    $saveL=false;
                }    
            }
            
            if($saveS && $saveL){
                $sql= saveIMG($projectID, $uploadfileL, $uploadfileS, $na, $uploaddir, $comment, $visible);
                $status= mysqli_query($link, $sql); 
                //Prüfung ob Erfolgreich in DB geschrieben
                if(!$status){
                    header('Location: index.php?id=7&status=0&project='.$projectID);
                }
            }   
        }
        if($status){
            header('Location: index.php?id=7&status=1&project='.$projectID);
        }else{
            header('Location: index.php?id=7&status=0&project='.$projectID);
        }
    }else{
        header('Location: index.php?id=7&status=0&project='.$projectID);
    }
}

?>

<div class="col-xs-12 col-md-12">
    
    <!--Lightboxen (Modals)-->
    <div class="container modalgroup">
        
        <?php
            $sql= getStatusProject($projectID);
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
                    <form enctype="multipart/form-data" action="gallery.php" method="POST">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Fotos hochladen</h4>
                        </div>
                        <div class="modal-body">
                            <div id="input_container">
                                <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                                <label for="imgupload">Bildupload</label>                                    
                                <input id="imgupload" type="file" name="my_file[]" multiple >
                                <p>(Multi-upload möglich, max. 4mb/ Foto)</p><br/>
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
                                <label for="comment">Kommentar</label>
                                <textarea id="comment" rows="3" name="comment" class="form-control" maxlength="20"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" name="submit" value="Bilder hochladen" class="btn btn-default"/>
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
        //Rückgabemeldung für Event-Handling Gallery
        $stat = checkEventGallery($response);
        echo $stat;
    }
?>


    <div id="nanoGallery3">
    <?php
        if($usertyp==2){
            $sql=showAllIMG($projectID);
        }else{
            $sql=showIMG($projectID, 2);
        }
        $result= mysqli_query($link, $sql);
        while($row= mysqli_fetch_array($result)){
            $imgL= $row['HashNameL'];
            $imgS= $row['HashNameS'];
            $com= $row['Comment'];
            $date= $row['Date'];
            $id= $row['IdPicture'];
            echo'<a href="'.$imgL.'" data-ngid="'.$id.'" data-ngthumb="'.$imgS.'" data-ngdesc="'.$date.'">'.$com.'</a>';
        }
    ?>  
    </div>

</div><!-- END Include Gallery -->