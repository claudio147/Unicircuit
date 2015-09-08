<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

$sections= array(1=>'Landing page (2365 x 1744px)', 2=>'Slider Mobile (483 x 353px)', 3=>'Slider Desktop (502 x 301px)', 
        4=>'Über Uns Links (210 x 140px)', 5=>'Über Uns Rechts (210 x 140px)');

//Datenbankverbindung
$link = connectDB();

if (isset($_POST['Selection'])){
    $select=$_POST['Selection'];
    
    header('Location: index.php?nav=2&select='.$select);
    exit(); 
}else if(!isset($select)){
    $select=1;
}


//Dateiupload
if(isset($_POST['upload'])){
    $select= $_POST['select'];
    //Array mit Statusmeldungen
    $errorstatus= array('Alles OK', 'Zeitüberschreitung', 'Grössenüberschreitung',
        'Nicht vollständig', 'Keine Datei hochgeladen');

    //Zielverzeichnis, in welches die Datei kopiert wird
    $uploaddir= '../../../productsite/public/images/';

    $filename= sha1(time().mt_rand().$_FILES['userfile']['name']);
    $extension= strrchr($_FILES['userfile']['name'],'.');
    $file= $filename.$extension;
    
    //Dateipfad mit Dateinamen zusammensetzen
    $uploadfile= $uploaddir.basename($file);
    
    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
        
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

        if($status){
            header('Location: index.php?nav=2&statusSave=0');
            exit();
        }else{
            header('Location: index.php?nav=2&statusSave=1');
            exit();
        }
    }else{
        header('Location: index.php?nav=2&statusSave=1');
        exit();
    }
}


//Löschfunktion
if(isset($_POST['delete'])){
    if(!empty($_POST['id'])){
        $id=$_POST['id'];
            $link= connectDB();
            $sql= selectFilename($id);
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            $fina=$row['Filename'];
            $path= $row['Path'];
            
            if(unlink($path.$fina)){
                $sql2= deleteImage($id);
                $status= mysqli_query($link, $sql2);
                if($status){
                    header('Location: index.php?nav=2&statusSave=2');
                    exit();
                }else{
                    header('Location: index.php?nav=2&statusSave=3');
                    exit();
                }
            }else{
                header('Location: index.php?nav=2&statusSave=3');
                exit();
            }
    }
}

//Speicherfunktion
if(isset($_POST['save'])){
    $idHTML= $_POST['idHTML'];
    if($idHTML==2 || $idHTML==3){
        $id= $_POST['active'];
        $link= connectDB();
        $sql1= setAllActiveNull($idHTML);
        $result1 = mysqli_query($link, $sql1);
        for($c=0; $c<count($id); $c++){
            $sql2= updateImageStatus($id[$c]);
            $status2 = mysqli_query($link, $sql2);
        }
        
        if($status2){
            header('Location: index.php?nav=2&statusSave=0');
            exit();
        }
        
        
    }else{
        $id= $_POST['active'];
        $link= connectDB();
        $sql1= setAllActiveNull($idHTML);
        $sql2= updateImageStatus($id);

        $status1 = mysqli_query($link, $sql1);
        $status2 = mysqli_query($link, $sql2);
        
        if($status1 && $status2){
            header('Location: index.php?nav=2&statusSave=0');
            exit();
        }
    }
    
    
    
}
?>


<div class="col-xs-12">
    <h2 class="modul-title">Bildverwaltung</h2>
    <div class="row">
        <div class="col-xs-12 col-md-6 img-upload">
            <form action="imageupload.php" method="POST" >
                <label for="select1">Auswahl Selektion:</label>
                <select id="select1" class="form-control" name="Selection" onchange="this.form.submit()">
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
            <hr/>


            <!--Bildupload-->
            <form enctype="multipart/form-data" action="imageupload.php" method="POST">
                <!-- Grössenbegrenzung seitens HTML. NICHT SICHER!!!-->
                <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/>
                <input type="hidden" name="select" value="<?php echo $select ?>"/>
                <!-- Anzeige des Datei Choosers-->
                <label for="upload1">Dateiupload auf Server</label>
                <input id="upload1" name="userfile" type="file"/>
                <label for="alt1">Bildbeschreibung (Alt-Text)</label>
                <input id="alt1" type="text" name="comment" class="form-control" /><br />
                <input type="submit" name="upload" value="hochladen" class="btn btn-default"/><br/>
            </form>
        </div>
    </div>
    <?php  
    
    if(isset($status)){
        if($status==1){
            echo'<div class="alert alert-warning" role="alert">Update fehlgeschlagen!</div>';
        }else if($status==0){
            echo'<div class="alert alert-success" role="alert">Update erfolgreich</div>';
        }else if($status==2){
            echo'<div class="alert alert-success" role="alert">Löschen erfolgreich.</div>';
        }else if($status==3){
            echo'<div class="alert alert-warning" role="alert">Löschen fehlgeschlagen.</div>';
        }
    }

    if (isset($select)){
        $sql= allImagesOfIdHTML($select);
        $result = mysqli_query($link, $sql);
    
       echo'<form action="imageupload.php" method="POST" style="margin-top:15px;">';
       echo'<table class="table table-hover">';
       echo'<tr>';
       echo'<th>Aktiv</th>';
       echo'<th>Datum</th>';
       echo'<th>Zeit</th>';
       echo'<th>Originalname</th>';
       echo'<th>Bildvorschau</th>';
       echo'<th></th>';
       echo'</tr>';
        while($row= mysqli_fetch_array($result)){
            if($row['Active']==1){
                $check='checked="checked"';
                echo'<tr class="success">';
            }else{
                $check='';
                echo'<tr>';
            }
            
            if($select==2 || $select==3){
                echo'<td><input type="checkbox" name="active[]" value="'.$row['ID'].'" '.$check.'</td>';
            }else{
                echo'<td><input type="radio" name="active" value="'.$row['ID'].'" '.$check.'</td>';
            }
            echo'<form action="imageupload.php" method="POST">';
            echo'<input type="hidden" name="id" value="'.$row['ID'].'"/>';
            echo'<td>'.$row['Date'].'</td>';
            echo'<td>'.$row['Time'].'</td>';
            echo'<td>'.$row['Orgname'].'</td>';
            echo'<td><img class="td-img" src="'.$row['Path'].$row['Filename'].'" alt="'.$row['Comment'].'"></td>';
            echo'<td><input type="submit" name="delete" value="löschen"/></td>';
            echo'</form>';
            echo'</tr>'; 
        }
        echo'</table>';
        echo'<input type="hidden" name="idHTML" value="'.$select.'"/>';
        echo'<input type="submit" name="save" value="Speichern" class="btn btn-default"/>';
        echo'</form>';
    }
    ?>
    
</div>