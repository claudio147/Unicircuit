<?php
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');

$projectID=2;

$link= connectDB();


if(isset($_POST['submit'])){
    $subject= filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message= filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $emArch;
    $emCust;
    $fnCust;
    $lnCust;
    $projectNr;
    $projectName;
    
    //hole Email Architekt
    $sql= getMailArch($projectID);
    $result= mysqli_query($link, $sql);
    while($row= mysqli_fetch_array($result)){
        $emArch= $row['Email'];
        $projectNr= $row['ProjectNumber'];
        $projectName= $row['Title'];
    }
    
    //hole Email Bauherr
    $sql= getNameCust($projectID);
    $result= mysqli_query($link, $sql); 
    while($row= mysqli_fetch_array($result)){
        $fnCust= $row['Firstname'];
        $lnCust= $row['Lastname'];
        $emCust= $row['Email'];        
    }
    
    
    //check mail
    if(!checkMailFormat($emArch)){
        //echo'error email architekt';
        $error=true;
    }
    
    if(!checkMailFormat($emCust)){
        //echo'error email bauherr';
        $error=true;
    }
    
    // Fehler im Eingabefeld?
    if (empty($subject) || strlen($subject) < 4) {
        //echo 'error subject';
        $errorSJ = true;
        $error = true;
    }
    
    if (empty($message) || strlen($message) < 10) {
        //echo 'error message';
        $errorME = true;
        $error = true;
    }
    
    if(!isset($error)){
        if(sendMailtoArch($emArch, $emCust, $fnCust, $lnCust, $subject, $message, $projectNr, $projectName)){
            header('Location: index.php?id=8&sent=1');
            exit();
        }else{
            header('Location: index.php?id=8&sent=2');
            exit();
        }
    }else{
        header('Location: index.php?id=8&sent=2');
        exit();
    }
    
}

?>


<div class="col-xs-12 col-md-6">
    <h3>Kontaktformular</h3>
    <form method="POST" action="contact.php">
        <label for="subject">Betreff:</label>
        <input type="text" name="subject" id="subject" class="form-control" />
        <label for="message">Nachricht:</label>
        <textarea name="message" id="message" class="form-control" rows="10"></textarea>
        <br/>
        <input type="submit" name="submit" value="Senden" class="btn btn-default"/>
    </form>
    <?php  
    if(isset($_GET['sent'])){
        $x=$_GET['sent'];
        if($x==1){
            echo'<br/><div class="alert alert-success" role="alert">Nachricht erfolgreich gesendet!</div>';
        }else if($x==2){
            echo'<br/><div class="alert alert-warning" role="alert">Senden fehlgeschlagen! Bitte eingaben überprüfen</div>';
        }
    }
    ?>
</div>
