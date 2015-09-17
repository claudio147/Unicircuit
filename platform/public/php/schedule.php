<?php
require_once ('../../../library/public/database.inc.php');

$link= connectDB();




//Upload und Überprüfung ob es ein PDF ist
if(isset($_POST['submit'])){
    $projectID= filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
    
    //Erzeugt Pfad für Bildupload
    $sql= getNameCust($projectID);
    $result= mysqli_query($link, $sql);
    $row= mysqli_fetch_array($result);
    $idArch= $row['Fk_IdArchitect'];

    $uploaddir = '../architects/architect_'.$idArch.'/project_'.$projectID.'/' ;
    
    if($_FILES['schedule']['error']==0){
        if(strtolower($_FILES['schedule']['type'])=='application/x-pdf' || strtolower($_FILES['schedule']['type'])== 'application/pdf'){
            //Überprüfung Filegrösse (max. 8MB)
            if($_FILES['schedule']['size']<8000000){
                $file= $_FILES['schedule'];
                
                $orgname= $file['name'];        //Originalname
                $tempname= $file['tmp_name'];   //Temp- Verzeichnis / Name
                $comment= filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

                //Datei in kryptischen einzigartigen Namen umbenennen (Überschreibungen verhindern)
                $filename= sha1(time().mt_rand().$orgname);
                $extension= strrchr($orgname, '.');
                
                //Zusammensetzen des kryptischen Namens mit der Original Dateiendung
                $pdf= $filename.$extension;
                
                //Uploadpfad inkli. Dateiname und Endung
                $uploadfile= $uploaddir.basename($pdf);
                
                if(move_uploaded_file($tempname, $uploadfile)){
                    //Speichert daten in DB
                    $sql= saveSchedule($projectID, $uploadfile, $orgname, $uploaddir, $comment);
                    $status= mysqli_query($link, $sql);
                    if($status){
                        //Erfolgreich hochgeladen
                        header('Location: index.php?id=3&status=0&project='.$projectID);
                    }else{
                        //Übermittlungsfehler
                        header('Location: index.php?id=3&status=1&project='.$projectID);
                    }
                }else{
                    //Übermittlungsfehler
                    header('Location: index.php?id=3&status=1&project='.$projectID);
                }
                
                
                
                
                
                
            }else{
                //zu grosses PDF
                header('Location: index.php?id=3&status=3&project='.$projectID);
            }  
        }else{
            //kein PDF
            header('Location: index.php?id=3&status=2&project='.$projectID);
        }
    }else{
        //Übermittlungsfehler
        header('Location: index.php?id=3&status=1&project='.$projectID);
    }
}
?>

<div class="col-xs-12">
    <h2 class="modul-title">Terminplan</h2>
    
    <!--Lightboxen (Modals)-->
    <div class="container modalgroup">
        
        <?php
            $sql=getStatusProject($projectID);
            $result=  mysqli_query($link, $sql);
            $row=  mysqli_fetch_array($result);
            $statusStorage=$row['Storage'];
            if($usertyp==2 && $statusStorage!=1){ 
                echo'<button type="button" class="btn btn-default" data-toggle="modal" data-target="#newSchedule"><i class="fa fa-plus-circle"></i> hochladen</button>';
            } 
        ?>  

        <!-- Modal Global-->
        <div class="modal" id="newSchedule" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <form enctype="multipart/form-data" action="schedule.php" method="POST">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Terminplan hochladen</h4>
                        </div>
                            <div class="modal-body">
                                <div id="input_container">
                                    <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                                    <label for="scheduleUpload">Terminplan</label>                                    
                                    <input id="scheduleUpload" type="file" name="schedule" >
                                    <p>(PDF Format)</p><br/>
                                    <label for="comment">Kommentar</label>
                                    <textarea id="comment" rows="3" name="comment" class="form-control"></textarea>                              

                                </div>
                            </div>
                        <div class="modal-footer">
                            <input type="submit" name="submit" value="Terminplan hochladen" class="btn btn-default"/>
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
        //Rückgabemeldung für Event-Handling Schedule
               $stat = checkEventSchedule($response);
               echo $stat;
    }
    
    $sql= showAllSchedule($projectID);
    $result= mysqli_query($link, $sql);
    
    $row= mysqli_fetch_array($result);
    $pdfSchedule= $row['HashName'];
    
    
    ?>
        
    <object id="schedule-pdf" data="<?php echo $pdfSchedule; ?>" type="application/pdf" style="width:100%; border: solid 1px"></object>




</div>