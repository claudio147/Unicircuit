<?php

//Einbindung Librarys
require_once ('../../library/public/database.inc.php');

$sections= array(1=>'Landing page', 2=>'Header Navigation', 3=>'Animation', 
        4=>'Module', 5=>'Galerie', 6=>'Einschub Kontakt', 7=>'Preise',
        8=>'Über Uns', 9=>'Kontakt', 10=>'Footer Navigation', 11=>'Impressum',
        12=>'AGB');
$select=1;

if (isset($_POST['Selection'])) {
    $select=$_POST['Selection'];
    
    //Datenbankverbindung
    $link = connectDB();
    $sql= allContentOfIdHTML($select);
    
    $result = mysqli_query($link, $sql);
}


if(isset($_POST['save'])){
    $select= $_POST['select'];
    $ergebnis=false;
    
    $link= connectDB();
    $sql= allContentOfIdHTML($select);
    $result= mysqli_query($link, $sql);
        
    while($row= mysqli_fetch_array($result)){
        $title= $row['Title'];
        $content= $_POST[$title];
        $sql2= saveToDB($title, $content);
        
        $status = mysqli_query($link, $sql2);
        $ergebnis=true;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<title>RMS-Productsite</title>
        
        <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
        <script>tinymce.init({
            selector:'textarea#2',
            plugins: 'code link image lists preview table',
            skin: 'custom',
            width: 600,
            min_height: 300,
        });
        </script>
        
</head>
<body>
    
<form action="productsite.php" method="POST" >
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

<form action="productsite.php" method="POST">
<?php
if (isset($_POST['Selection'])){
    while($row = mysqli_fetch_array($result)){
        $date= $row['Date'];
        $time= $row['Time'];
        $description= $row['Description'];
        $title= $row['Title'];
        $content= $row['Content'];
        $inputType= $row['InputType'];
        echo'<h3>'.$description.'</h3>';
        if($inputType == 1){
            echo'<textarea id="1" name="'.$title.'" cols="50" rows="2">'.$content.'</textarea>';
        }else{
            echo'<textarea id="2" name="'.$title.'" cols="50" rows="2">'.$content.'</textarea>';  
        } 
    }
    echo'<p>Letzte Änderung: '.$date.', '.$time.'</p>';
}
    
?>
    <input type="hidden" name="select" value="<?php echo $select ?>" />
    <br /><input type="submit" name="save" value="Speichern" />
</form>
<?php
if($ergebnis== true){
    echo'<p>Speichern erfolgreich.</p>';
}
?>

    
    
    
</body>
</html>