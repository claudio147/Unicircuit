<?php

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');

$link = connectDB();

//Formular mit Platzhaltern für das Editieren eines CHronik-Beitrags
if(isset($_POST['postEdit'])){
    $data= '';

    //Post ID
    $id= filter_input(INPUT_POST, 'postEdit', FILTER_SANITIZE_NUMBER_INT);
    //$sql= selectProjectById($id);


    while($row= mysqli_fetch_array($result)){
        $postProject= $row['IdProject'];
        
        $
        
        
        $data.= '<input type="hidden" name="postID" value="'.$postProject.'"/>
                <input type="hidden" name="hash" value="'.$hashName.'"/>
                <input type="hidden" name="path" value="'.$path.'"/>
                <input type="hidden" name="orgName" value="'.$orgName.'"/>
                <label for="title">Titel*</label>
                <input id="title" type="text" name="title" value="'.$title.'" class="form-control">
                <label for ="content">Inhalt*</label>
                <textarea id="content" name="content" class="form-control" rows="8">'.$content.'</textarea>
                <label for="visibility">Sichtbarkeit*</label>
                <div id="visibility" class="radio near">
                <label class="near">
                <input type="radio" name="visible" value="1" '.$check1.'/>
                Nur Architekt
                </label>
                </div>
                <div class="radio">
                <label class="near">
                <input type="radio" name="visible" value="2" '.$check2.'/>
                Architekt und Bauherr
                </label>
                </div>
                <label for="upload">Bildupload</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="2100000"/> <!-- Grössenbegrenzung (nicht Sicher) -->
                <input type="file" name="userfile"/>';

                echo $data;
    }

}