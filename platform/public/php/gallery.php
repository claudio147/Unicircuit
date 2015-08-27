<?php
require_once ('../../../library/public/database.inc.php');

$projectID=2;
$uploaddir= '../img/architect1/project1/img/';

$link= connectDB();

function resizeImage ($filepath_old, $filepath_new, $image_dimension, $scale_mode = 0){ 
  if (!(file_exists($filepath_old)) || file_exists($filepath_new)) return false; 

  $image_attributes = getimagesize($filepath_old); 
  $image_width_old = $image_attributes[0]; 
  $image_height_old = $image_attributes[1]; 
  $image_filetype = $image_attributes[2]; 

  if ($image_width_old <= 0 || $image_height_old <= 0) return false; 
  $image_aspectratio = $image_width_old / $image_height_old; 

  if ($scale_mode == 0) { 
   $scale_mode = ($image_aspectratio > 1 ? -1 : -2); 
  } elseif ($scale_mode == 1) { 
   $scale_mode = ($image_aspectratio > 1 ? -2 : -1); 
  } 

  if ($scale_mode == -1) { 
   $image_width_new = $image_dimension; 
   $image_height_new = round($image_dimension / $image_aspectratio); 
  } elseif ($scale_mode == -2) { 
   $image_height_new = $image_dimension; 
   $image_width_new = round($image_dimension * $image_aspectratio); 
  } else { 
   return false; 
  } 

  switch ($image_filetype) { 
   case 1: 
    $image_old = imagecreatefromgif($filepath_old); 
    $image_new = imagecreate($image_width_new, $image_height_new); 
    imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old); 
    imagegif($image_new, $filepath_new); 
    break; 
  
   case 2: 
    $image_old = imagecreatefromjpeg($filepath_old); 
    $image_new = imagecreatetruecolor($image_width_new, $image_height_new); 
    imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old); 
    imagejpeg($image_new, $filepath_new); 
    break; 

   case 3: 
    $image_old = imagecreatefrompng($filepath_old); 
    $image_colordepth = imagecolorstotal($image_old); 

    if ($image_colordepth == 0 || $image_colordepth > 255) { 
     $image_new = imagecreatetruecolor($image_width_new, $image_height_new); 
    } else { 
     $image_new = imagecreate($image_width_new, $image_height_new); 
    } 

    imagealphablending($image_new, false); 
    imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old); 
    imagesavealpha($image_new, true); 
    imagepng($image_new, $filepath_new); 
    break; 

   default: 
    return false; 
  } 

  imagedestroy($image_old);
  imagedestroy($image_new);
  return true; 
 } 

if(isset($_POST['submit'])){
    //Überprüfung ob mind. 1 File ausgewählt wurde
    if(isset($_FILES['my_file'])){
        $files= $_FILES['my_file'];
        $fileCount= count($files['name']);
        $comment= filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        
        //Schlaufe durch alle ausgewählten Files
        for($i=0; $i<$fileCount; $i++){
            $na= $files['name'][$i];            //Originalname
            $tempna= $files['tmp_name'][$i];    //Temp- Verzeichnis / Name
            $size= $files['size'][$i];          //Dateigrösse
            
            //Ermittle Bildgrösse
            $image_attributes = getimagesize($tempna); 
            $image_width_old = $image_attributes[0]; 
            $image_height_old = $image_attributes[1];
            
            //Array mit Statusmeldungen
            $errorstatus= array('Alles OK', 'Zeitüberschreitung', 'Grössenüberschreitung',
                'Nicht vollständig', 'Keine Datei hochgeladen');
            
            //Datei in kryptischen einzigartigen Namen umbenennen (Überschreibungen verhindern)
            $filename= sha1(time().mt_rand().$na);
            $extension= strrchr($na, '.');

            $fileS= $filename.'_S'.$extension;
            $fileL= $filename.'_L'.$extension;
            
            $uploadfileS= $uploaddir.basename($fileS);
            $uploadfileL= $uploaddir.basename($fileL);
            
            $saveS=false;
            $saveL=false;
            
            $imageFileTypeS = pathinfo($uploadfileS, PATHINFO_EXTENSION);
            $imageFileTypeL = pathinfo($uploadfileL, PATHINFO_EXTENSION);
            

            // Allow certain file formats
            if($imageFileTypeS != "jpg" && $imageFileTypeS != "png" && $imageFileTypeS != "jpeg"
            && $imageFileTypeS != "gif" && $imageFileTypeS != "JPG" && $imageFileTypeS != "JPEG"
               && $imageFileTypeS != "PNG" && $imageFileTypeS != "GIF") {
                header("Location: index.php?id=7&status=2");
                exit();
            }
            
            // Allow certain file formats
            if($imageFileTypeL != "jpg" && $imageFileTypeL != "png" && $imageFileTypeL != "jpeg"
            && $imageFileTypeL != "gif" && $imageFileTypeS != "JPG" && $imageFileTypeS != "JPEG"
                    && $imageFileTypeS != "PNG" && $imageFileTypeS != "GIF") {
                header("Location: index.php?id=7&status=2");
                exit();
            }
            
            if($image_width_old>1000 || $image_height_old>1000){
                //Verkleinert das Originalbild auf eine Länge von 1000px
                if(resizeImage($tempna, $uploadfileS, 1000)){
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
                //Verkleinert das Originalbild auf eine Länge von 2880px
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
                $sql= saveIMG($projectID, $uploadfileL, $uploadfileS, $na, $uploaddir, $comment);
                $status= mysqli_query($link, $sql);
                if($status){
                    header("Location: index.php?id=7&status=1");
                }else{
                    header("Location: index.php?id=7&status=0");
                }
            }
            
            
            
        }
    }
}



?>
<div class="col-xs-12 col-md-12">
    <h2 class="modul-title">Galerie</h2>
    
        <!--Lightboxen (Modals)-->
    <div class="container modalgroup">

        <!-- Trigger the modal with a button -->
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#newPost">Fotos hochladen</button>

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

                                    <label for="imgupload">Bildupload</label>                                    
                                    <input type="hidden" name="MAX_FILE_SIZE" value="4100000"/>
                                    <input id="imgupload" type="file" name="my_file[]" multiple >
                                    <p>(Multi-upload möglich, max. 4mb/ Foto)</p><br/>
                                    <label for="comment">Kommentar</label>
                                    <textarea id="comment" rows="3" name="comment" class="form-control"></textarea>                              

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
        $x=$_GET['status'];
        if($x==1){
            echo'<br/><div class="alert alert-success" role="alert">Fotos erfolgreich hochgeladen</div>';
        }else if($x==0){
            echo'<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! - Bitte erneut versuchen</div>';
        }else if($x==2){
            echo'<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! - Nur JPG, JPEG, PNG, GIF</div>';
        }else if($x==3){
            echo'<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! max. 4 MB pro Foto</div>';
        }
    }
    ?>


    <div id="nanoGallery3">
    <?php
        $sql=showIMG($projectID);
        $result= mysqli_query($link, $sql);
        while($row= mysqli_fetch_array($result)){
            $imgL= $row['HashNameL'];
            $imgS= $row['HashNameS'];
            $com= $row['Comment'];
            echo'<a href="'.$imgL.'" data-ngthumb="'.$imgS.'" data-ngdesc="'.$com.'"></a>';
        }
    ?>
    <!--
        <a href="../img/architect1/project1/img/test_1_L.jpg" data-ngthumb="../img/architect1/project1/img/test_1_S.jpg" data-ngdesc="Testbild 1">Title Image1</a>
        <a href="../img/architect1/project1/img/test_2_L.jpg" data-ngthumb="../img/architect1/project1/img/test_2_S.jpg" data-ngdesc="Testbild 2">Title Image2</a>
        <a href="../img/architect1/project1/img/test_3_L.jpg" data-ngthumb="../img/architect1/project1/img/test_3_S.jpg" data-ngdesc="Testbild 3">Title Image3</a>
    -->
    </div>

</div><!-- END Include Gallery -->