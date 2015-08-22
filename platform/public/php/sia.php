<?php

require('../../../library/public/fpdf17/fpdf.php');
require_once ('../../../library/public/database.inc.php');

$projectID=2;

$link=connectDB();

//PDF Generator
if(isset($_POST['submit'])){
    
    //Variablen Definitionen
    $date= $_POST['date'];
    $present= $_POST['present'];
    $title;
    $prNr;
    $zip;
    $country;
    
    
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
    $pdf->Image('../img/architect1/personal/logo.gif',140,10,50);


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
    $pdf->Cell(40,10,$date,0,1,'R');

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
            $exist=true;
            if($count==1){
                $img=$row['Path'].$row['HashName'];
                $pdf->Image($img,50,$y,65);
                $count++;
            }else{
                $img= $row['Path'].$row['HashName'];
                $pdf->Image($img,125,$y,65);
                $pdf->ln();
                $a = $pdf->GetNewImageSize($row['Path'].$row['HashName'], 65, 0); 
                $image_height = $a["height"];
                $y= $y+$image_height+10;
                $count=1;
            }
        }
        if(!$exist){
            $error = iconv('UTF-8', 'windows-1252', 'keine Fotos vorhanden');
            $pdf->SetFont($font,'I',12);
            $pdf->MultiCell(140,8,$error,0,1,'');
            $y= $y+15;
        }
        $pdf->ln();
        $a = $pdf->GetNewImageSize($img, 65, 0); 
        $image_height = $a["height"];
        $y= $y+$image_height+15;

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
        $max= $_GET['maxTemp'];
        $min= $_GET['minTemp'];
        $maxTemp = iconv('UTF-8', 'windows-1252', $max.'°C');
        $minTemp = iconv('UTF-8', 'windows-1252', $min.'°C');
        $humidity = iconv('UTF-8', 'windows-1252', '50%');
        $icon = iconv('UTF-8', 'windows-1252', 'sonnig');

        
        $pdf->SetFont($font2,'B',18);
        $pdf->Cell(30,4,$maxTemp,0,0,'C');
        $pdf->Cell(30,4,$minTemp,0,0,'C');
        $pdf->Cell(30,4,$humidity,0,0,'C');
        $x= $pdf->getX();
        $y= $pdf->getY();
        $pdf->Image('http://cdn.worldweatheronline.net/images/wsymbols01_png_64/wsymbol_0001_sunny.png',$x+10,$y-4,10,10);
        $pdf->ln();
        $pdf->Cell(30,8,'',0,0,'L');
        $pdf->SetFont($font2,'',8);
        $pdf->Cell(30,8,'max. Temp',0,0,'C');
        $pdf->Cell(30,8,'min. Temp',0,0,'C');
        $pdf->Cell(30,8,'Luftfeuchtigkeit',0,0,'C');
        $pdf->Cell(30,8,'sonnig',0,0,'C');
        $pdf->ln(20);
    }else{
        $error = iconv('UTF-8', 'windows-1252', 'kein Datum ausgewählt');
        $pdf->SetFont($font,'I',12);
        $pdf->MultiCell(140,8,$error,0,1,'');
        $pdf->ln();
    }
 


    //7. Abschnitt - Notizen
    $pdf->SetFont($font,'B',12);
    $pdf->Cell(30,8,'Notizen:',0,0,'L');
    $pdf->SetFont($font,'',12);
    $pdf->MultiCell(140,5,'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',0,1,'');


    $pdf->Output();
}


?>
<html>
    <head>  
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />       
        <!-- CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"/>

        <link rel="stylesheet" href="../css/style.css"/>
    </head>
    <body>
        
        <form method="POST" action="sia.php">
            <p>Datum des gewünschten Baujournal- Eintrags</p>
            <input type="date" name="date">
            <p>Anwesende Handwerker</p>
            <select name="present[]" size="10" multiple="multiple">
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
            <p>Notizen</p>
            <textarea name="notes" placeholder="Bitte hier Kommentar eingeben..."></textarea>
            <br />
            <input class="btn btn-default createPDF" type="submit" name="submit" value="SIA Baujournal erzeugen"/>
        </form>
        
        
        
        
        
        
        
        
        <!-- JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <script src="../js/script.js"></script>
        <script src="../js/weather.js"></script>
        
    </body>
</html>
