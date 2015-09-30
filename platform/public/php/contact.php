<?php
/*
*   Unicircuit Plattform
*   «Kontaktformular für Bauherr (Modul)»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/
        
require_once ('../../../library/public/database.inc.php');
require_once ('../../../library/public/mail.inc.php');

$link= connectDB();


if(isset($_POST['submit'])){
    
    $projectID= filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);
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
    $result= mysqli_query($link,$sql);
    while($row= mysqli_fetch_array($result)){
        $emArch= $row['Email'];
        $projectNr= $row['ProjectNumber'];
        $projectName= $row['Title'];
    }
    
    //hole Email Bauherr
    $sql= getNameCust($projectID);
    $result= mysqli_query($link,$sql); 
    while($row= mysqli_fetch_array($result)){
        $fnCust= $row['Firstname'];
        $lnCust= $row['Lastname'];
        $emCust= $row['Email'];        
    }
    
    
    //Überprüfung auf Fehleingaben
    if(!checkMailFormat($emArch)){
        $error=true;
    }    
    if(!checkMailFormat($emCust)){
        $error=true;
    }
    if (empty($subject) || strlen($subject) < 4) {
        $errorSJ = true;
        $error = true;
    }   
    if (empty($message) || strlen($message) < 10) {
        $errorME = true;
        $error = true;
    }
    
    if(!isset($error)){
        if(sendMailtoArch($emArch, $emCust, $fnCust, $lnCust, $subject, $message, $projectNr, $projectName)){
            header('Location: index.php?id=8&sent=1&project='.$projectID);
            exit();
        }else{
            header('Location: index.php?id=8&sent=2&project='.$projectID);
            exit();
        }
    }else{
        header('Location: index.php?id=8&sent=2&project='.$projectID);
        exit();
    }
    
}
?>

<div class="col-xs-12 col-md-6">
    <form method="POST" action="contact.php">
        <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
        <label for="subject">Betreff:</label>
        <input type="text" name="subject" id="subject" class="form-control" />
        <label for="message">Nachricht:</label>
        <textarea name="message" id="message" class="form-control" rows="10"></textarea>
        <br/>
        <input type="submit" name="submit" value="Senden" class="btn btn-default"/>
    </form>
    <?php  
    if(isset($_GET['sent'])){
        $response = filter_input(INPUT_GET, 'sent', FILTER_SANITIZE_NUMBER_INT);
        //Rückgabemeldung für Event-Handling Contact
        $stat = checkEventContact($response);
        echo $stat;
    }
    ?>
</div>