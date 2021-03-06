<?php
/*
*   Redaktionssystem
*   «textChange.php / Modul ist für die Verwaltung von Texten auf der Homepage zuständig»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/

//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/security.inc.php');

$link = connectDB();

$sections= array(1=>'Landing page', 2=>'Header Navigation', 3=>'Animation', 
        4=>'Module', 5=>'Galerie', 6=>'Einschub Kontakt', 7=>'Preise',
        8=>'Über Uns', 9=>'Kontakt', 10=>'Footer Navigation', 11=>'Impressum',
        12=>'AGB');

if (isset($_POST['Selection'])){
    $select=$_POST['Selection'];
    header('Location: index.php?nav=1&select='.$select);
    exit(); 
}else if(!isset($select)){
    $select=1;
}

if(isset($_POST['save'])){
    $select= $_POST['select'];
    $error=true;

    $sql= allContentOfIdHTML($select);
    $result= mysqli_query($link, $sql);
        
    while($row= mysqli_fetch_array($result)){
        $title= $row['Title'];
        $content= $_POST[$title];
        $sql2= saveToDB($title, $content);

        $status = mysqli_query($link, $sql2);
        if($status){
            $error=false;  
        }else{
            $error=true; 
        }
    }
    
    if($error){
        header('Location: index.php?nav=1&statusSave=0&select='.$select);
        exit();
    }else{
        header('Location: index.php?nav=1&statusSave=1&select='.$select);
        exit();
    }
}
?>


<div class="col-xs-12 col-md-6">
    <h2 class="modul-title">Textverwaltung</h2>
    
    <form action="textChange.php" method="POST" >
        <label for="select1">Auswahl Selektion:</label>
        <select id="select1" name="Selection" onchange="this.form.submit()" class="form-control">
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
    
    
    <?php  
    //Ausgabe der Erfolgs- bzw. Fehlermeldungen
    if(isset($status)){
        //Rückgabemeldung für Event-Handling Deadlines
        $stat = checkRMStext($status);
        echo $stat;
    }
    ?>
    
    <form action="textChange.php" method="POST">
    <?php
        if (isset($select)){
            
            //Datenbankverbindung
            $sql= allContentOfIdHTML($select);
            $result = mysqli_query($link, $sql);
            
            while($row = mysqli_fetch_array($result)){
                $date= $row['Date'];
                $time= $row['Time'];
                $description= $row['Description'];
                $title= $row['Title'];
                $content= $row['Content'];
                $inputType= $row['InputType'];
                echo'<h4>'.$description.'</h4>';
                if($inputType == 1){
                    echo'<textarea id="1" name="'.$title.'" class="form-control">'.$content.'</textarea>';
                }else{
                    echo'<textarea id="2" name="'.$title.'" class="form-control">'.$content.'</textarea>';  
                } 
            }
            echo'<p>Letzte Änderung: '.$date.', '.$time.'</p>';
        }
    ?>
        <input type="hidden" name="select" value="<?php echo $select ?>" /><br />
        <input type="submit" name="save" value="Speichern" class="btn btn-default"/>
    </form>
</div>