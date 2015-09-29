<!--
*   Unicircuit Plattform
*   «SIA Baujournal (Modul)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
-->
<?php

require('../../../library/public/fpdf17/fpdf.php');
require_once('../../../library/public/database.inc.php');

//Variablendefinition
$zip;
$country;

$link=connectDB();
 
//PDF Generator
if(isset($_POST['submit']) && isset($_POST['date'])){
    
    //Variablen Definitionen
    $projectID= filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
    $dateOrg= $_POST['date'];
    $date = date('Y-m-d', strtotime($dateOrg));
    $dateOutput= date('d.m.Y', strtotime($dateOrg));
    $present= $_POST['present'];
    $notes= iconv('UTF-8', 'windows-1252', $_POST['notes']);
    $title;
    $prNr;
    
    if($date>  date('Y-m-d', time())){
        //Datum in Zukunft gewählt --> exit
        header('Location: index.php?id=9&status=0&project='.$projectID);
        exit();
    }
    
    if(empty($dateOrg)){
        //Überprüft ob ein Datum gesetzt wurde
        header('Location: index.php?status=0&id=9&project='.$projectID);
        exit();
    }
    
    //Hole Link Arch.-Logo
    $sql=selectArchLogo($projectID);
    $result=mysqli_query($link, $sql);
    $row=mysqli_fetch_array($result);
    $logo= $row['Picture'];
    
    //Alle Projektdaten holen
    $sql=getProjectDates($projectID);
    $result= mysqli_query($link, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $title= $row['Title'];
        $prNr= $row['ProjectNumber'];
        $zip= $row['ZIP'];
        $country= $row['Country'];
    }

    //fontfamily
    $font='Times';
    $font2='Arial';

    //Generator PDF
    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->SetMargins(20,15);
    $pdf->AddPage();

    //Logo
    $pdf->Image($logo,140,10,50,15);

    //1. Abschnitt
    $pdf->SetFont($font,'B',18);
    $pdf->Cell(100,10,'SIA Baujournal',0,0,'L');
    $pdf->SetFont($font,'',14);
    $pdf->Cell(70,15,'',0,1,'R');

    //2.Abschnitt
    $pdf->SetFont($font,'',14);
    $pdf->Cell(30,10,$prNr,0,0,'L');
    $pdf->SetFont($font,'',14);
    $pdf->Cell(100,10,$title,0,0,'L');
    $pdf->Cell(40,10,$dateOutput,0,1,'R');

    //Trennlinie
    $pdf->line(20,40,190,40);
    $pdf->ln(5);

    //3.Abschnitt - Anwesende
    $pdf->SetFont($font,'B',12);
    $pdf->Cell(30,8,'Anwesende:',0,0,'L');
    $pdf->SetFont($font,'',12);
    
    //Alle Kontaktdaten
    if(isset($present)){
        foreach ($present as $key => $value) {
        
            $sql=getProjectAddress($value);        
            $result= mysqli_query($link, $sql);

            while($row= mysqli_fetch_array($result)){
                //UTF8 Kodierung der Werte aus der DB (Firma und Name)
                $cp= iconv('UTF-8', 'windows-1252', $row['Company']);
                $nm= iconv('UTF-8', 'windows-1252', $row['ProjectCoordinator']);

                if(!empty($nm)){
                   $pdf->Cell(140,8,'- '.$nm.', '.$cp ,0,1,'L');
                   $pdf->Cell(30,8,'',0,0,'L'); 
                }else{
                    $pdf->Cell(140,8, '- '.$cp ,0,1,'L');
                    $pdf->Cell(30,8,'',0,0,'L'); 
                }
            }
        }
    }else{
        $pdf->SetFont($font,'I',12);
        $pdf->Cell(140,8,'keine Anwesenden'.$cp ,0,1,'L');
        $pdf->Cell(30,8,'',0,0,'L'); 
    }
    $pdf->ln(); 
    
    //4.Abschnitt - Ereignisse
    $pdf->SetFont($font,'B',12);
    $pdf->Cell(30,8,'Ereignisse:',0,0,'L');
    
    if(!empty($date)){
        $sql=selectPostbyDate($projectID, $date);
        $result=  mysqli_query($link, $sql);
        $exist=false;
        while($row= mysqli_fetch_array($result)){
            $exist=true;
            $title= iconv('UTF-8', 'windows-1252', $row['Title']);
            $cont= iconv('UTF-8', 'windows-1252', $row['Description']);
            
            $pdf->setX(50);
            $pdf->SetFont($font,'B',12);
            $pdf->Cell(140,8,$title,0,1,'L');
            $pdf->Cell(30,8,'',0,0,'L');
            $pdf->SetFont($font,'',12);
            $pdf->MultiCell(140,5,$cont,0,1,'');
            $pdf->ln();
        }
        if(!$exist){
            $pdf->setX(50);
            $pdf->SetFont($font,'I',12);
            $pdf->Cell(140,8,'kein Beitrag',0,1,'L');
            $pdf->Cell(30,8,'',0,0,'L');
            $pdf->SetFont($font,'I',12);
            $pdf->MultiCell(140,5,'',0,1,'');
            $pdf->ln();
        }
    }else{
            $error = iconv('UTF-8', 'windows-1252', 'kein Datum ausgewählt');
            $pdf->setX(50);
            $pdf->SetFont($font,'I',12);
            $pdf->Cell(140,8,$error,0,1,'L');
            $pdf->Cell(30,8,'',0,0,'L');
            $pdf->SetFont($font,'I',12);
            $pdf->MultiCell(140,5,'',0,1,'');
            $pdf->ln();
    }
            
    $y= $pdf->getY()+2;

    //5.Abschnitt - Fotos
    $pdf->SetFont($font,'B',12);
    $pdf->Cell(30,8,'Fotos:',0,0,'L');
    
    if(!empty($date)){
        $sql=selectPostbyDate($projectID, $date);
        $result=  mysqli_query($link, $sql);
        $count=1;
        $exist=false;
        $img;
        while($row= mysqli_fetch_array($result)){
            if($row['HashName']!='placeholder.png'){
                $exist=true;
                if($count==1){
                    if($y<215){
                        $img=$row['Path'].$row['HashName'];
                        $pdf->Image($img,50,$y,65);
                        $count++;
                    }else{
                        $pdf->AddPage();
                        $y= $pdf->GetY();
                        $img=$row['Path'].$row['HashName'];
                        $pdf->Image($img,50,$y,65);
                        $count++;
                    }
                    
                }else if($row['HashName']!= NULL){
                    $img= $row['Path'].$row['HashName'];
                    $pdf->Image($img,125,$y,65);
                    $pdf->ln();
                    $a = $pdf->GetNewImageSize($row['Path'].$row['HashName'], 65, 0); 
                    $image_height = $a["height"];
                    $y= $y+$image_height+10;
                    $count=1;
                }
            }
        }
        if(!$exist){
            $error = iconv('UTF-8', 'windows-1252', 'keine Fotos vorhanden');
            $pdf->SetFont($font,'I',12);
            $pdf->MultiCell(140,8,$error,0,1,'');
            $y= $y+15;
        }
        if($count==2){
            $pdf->ln();
            $a = $pdf->GetNewImageSize($img, 65, 0); 
            $image_height = $a["height"];
            $y= $y+$image_height+15;
        }
        
    }else{
        $error = iconv('UTF-8', 'windows-1252', 'kein Datum ausgewählt');
        $pdf->SetFont($font,'I',12);
        $pdf->MultiCell(140,8,$error,0,1,'');
        $y= $y+15;
        
    }

    //6.Abschnitt Wetter
    $pdf->SetY($y);
    $pdf->SetFont($font,'B',12);
    $pdf->Cell(30,8,'Wetter:',0,0,'L');
    
    if(!empty($date)){
        $max= $_POST['maxTemp'];
        $min= $_POST['minTemp'];
        $humidity= $_POST['humidity'];
        $icon= $_POST['weatherIcon'];
        $desc= $_POST['weatherDesc'];
        $maxTemp = iconv('UTF-8', 'windows-1252', $max.'°C');
        $minTemp = iconv('UTF-8', 'windows-1252', $min.'°C');
        $humidity = iconv('UTF-8', 'windows-1252', $humidity.'%');
        $desc = iconv('UTF-8', 'windows-1252', $desc);

        $pdf->SetFont($font2,'B',18);
        $pdf->Cell(30,4,$maxTemp,0,0,'C');
        $pdf->Cell(30,4,$minTemp,0,0,'C');
        $pdf->Cell(30,4,$humidity,0,0,'C');
        $x= $pdf->getX();
        $y= $pdf->getY();
        $pdf->Image($icon ,$x+10,$y-4,10,10);
        $pdf->ln();
        $pdf->Cell(30,8,'',0,0,'L');
        $pdf->SetFont($font2,'',8);
        $pdf->Cell(30,8,'max. Temp',0,0,'C');
        $pdf->Cell(30,8,'min. Temp',0,0,'C');
        $pdf->Cell(30,8,'Luftfeuchtigkeit',0,0,'C');
        $pdf->Cell(30,8, $desc ,0,0,'C');
        $pdf->ln(20);
    }else{
        $error = iconv('UTF-8', 'windows-1252', 'kein Datum ausgewählt');
        $pdf->SetFont($font,'I',12);
        $pdf->MultiCell(140,8,$error,0,1,'');
        $pdf->ln();
    }
 
    //7. Abschnitt - Notizen
    $pdf->SetFont($font,'B',12);
    $pdf->Cell(30,5,'Notizen:',0,0,'L');
    $pdf->SetFont($font,'',12);
    if(!empty($notes)){
        $pdf->MultiCell(140,5, $notes ,0,1,'');
    }else{
        $error = iconv('UTF-8', 'windows-1252', 'keine Notizen');
        $pdf->SetFont($font,'I',12);
        $pdf->MultiCell(140,5,$error,0,1,'');
    }
    
    //Download
    $pdf->Output('Baujournal_'.$date.'.pdf', 'D');
}

//Alle Projektdaten holen
$sql=getProjectDates($projectID);
$result= mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result)) {
    $zip= $row['ZIP'];
    $country= $row['Country'];
}
?>

        
    <div class="col-xs-12 col-md-6">
        <form method="POST" action="sia.php" id="pdf">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <input id="zip" type="hidden" name="zip" value="<?php echo $zip; ?>">
            <input id="country" type="hidden" name="country" value="<?php echo $country; ?>">
            
            <input id="maxTemp" type="hidden" name="maxTemp" value="">
            <input id="minTemp" type="hidden" name="minTemp" value="">
            <input id="humidity" type="hidden" name="humidity" value="">
            <input id="weatherIcon" type="hidden" name="weatherIcon" value="">
            <input id="weatherDesc" type="hidden" name="weatherDesc" value="">
            
            <label for="date">Datum des gewünschten Baujournal- Eintrags*</label>
            <input type="" name="date" id="date" class="form-control datepicker">
            <label for="handwerker">Anwesende Handwerker</label>
            <select name="present[]" size="10" multiple="multiple" class="form-control" id="handwerker">
                <?php
                    $sql= allProjectAddress($projectID);
                    $result= mysqli_query($link, $sql);
                    while($row= mysqli_fetch_array($result)){
                        echo'<option value="'.$row['IdProjectAddress'].'">';
                        echo $row['Company'].', '.$row['ProjectCoordinator'];
                        echo'</option>';
                    }
                ?>
            </select>
            <label for="notes">Notizen</label>
            <textarea id="notes" name="notes" placeholder="Bitte hier Kommentar eingeben..." class="form-control" rows="5"></textarea>
            <br />
            <input class="btn btn-default createPDF" type="submit" name="submit" value="SIA Baujournal herunterladen"/>
        </form>
        
    <?php  
        if(isset($_GET['status'])){
            $response = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_NUMBER_INT);
            //Rückgabemeldung für Event-Handling SIA
            $stat = checkEventSia($response);
            echo $stat;
        }
    ?>
    </div>