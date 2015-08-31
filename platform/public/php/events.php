<?php
require_once ('../../../library/public/database.inc.php');

$projectID=2;
$usertyp=1; //1= Architekt, 2=Bauherr

$link= connectDB();

//Speichern eines neuen Events
if(isset($_POST['submit'])){
    $dateOrg= $_POST['date'];
    $date = date('Y-m-d', strtotime($dateOrg));
    $time= $_POST['time'];
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    
    if(empty($date) || $date== 0000-00-00){
        $error=true;
    }
    if(empty($time) || $time== '00:00:00'){
        $error=true;
    }
    if(empty($title)){
        $error=true;
    }
    if(empty($location)){
        $error=true;
    }
    
    
    if(!isset($error)){
        $sql= newEvent($projectID, $date, $time, $title, $description, $location);
        $status= mysqli_query($link, $sql);
        if($status){
            header("Location: index.php?id=4&status=0");
        }else{
            header("Location: index.php?id=4&status=1");
        }
    }else{
        header("Location: index.php?id=4&status=1");
    }
}

//Update eines bestehenden Events
if(isset($_POST['update'])){
    $dateOrg= $_POST['date'];
    $date = date('Y-m-d', strtotime($dateOrg));
    $time= $_POST['time'];
    $eventID= $_POST['eventID'];
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    
    if(empty($date) || $date== 0000-00-00){
        $error=true;
    }
    if(empty($time) || $time== '00:00:00'){
        $error=true;
    }
    if(empty($title)){
        $error=true;
    }
    if(empty($location)){
        $error=true;
    }
    
    
    if(!isset($error)){
        $sql= updateEvent($eventID, $date, $time, $title, $description, $location);
        $status= mysqli_query($link, $sql);
        if($status){
            header("Location: index.php?id=4&status=2");
        }else{
            header("Location: index.php?id=4&status=3");
        }
    }else{
        header("Location: index.php?id=4&status=3");
    }
}

//Löschfunktion
if(isset($_POST['delete'])){

    if(!empty($_POST['eventID'])){
        $id=$_POST['eventID'];
        
        $sql= deleteEvent($id);
        $status = mysqli_query($link, $sql);
        if($status){
            header("Location: index.php?id=4&status=4");
        }else{
            header("Location: index.php?id=4&status=5");
        }
    }else{
        header("Location: index.php?id=4&status=5");
    }
    
}


?>
<div class="col-xs-12">
<h2 class="modul-title">Events</h2>

<!--Lightboxen (Modals)-->
<div class="container modalgroup">

    <!-- Trigger the modal with a button -->
<?php
    if($usertyp==1){
        echo'<button type="button" class="btn btn-default" data-toggle="modal" data-target="#newEvent">+ hinzufügen</button>';
    }
?>
    <!-- Modal Global-->
    <div class="modal" id="newEvent" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="events.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Event hinzufügen</h4>
                    </div>
                        <div class="modal-body">
                            <div id="input_container">

                                <label for="date">Datum*</label>
                                <input id="date" name="date" class="form-control datepicker">
                                <label for="time">Zeit*</label>
                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                    <input type="text" name="time" class="form-control" value="08:00">
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                                <label for="title">Titel*</label>
                                <input id="title" type="text" name="title" class="form-control">
                                <label for="description">Bemerkung</label>
                                <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                                <label for="location">Ort*</label>
                                <input id="location" type="text" name="location" class="form-control">


                            </div>
                        </div>
                    <div class="modal-footer">
                        <input type="submit" name="submit" value="Speichern" class="btn btn-default"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
    
    
    <!-- Modal Global-->
    <div class="modal" id="editEvent" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="events.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Event bearbeiten</h4>
                    </div>
                        <div class="modal-body">
                            <div id="eventEditContainer">

                                <!-- Ajax Content -->

                            </div>
                        </div>
                    <div class="modal-footer">
                        <input type="submit" name="delete" value="Löschen" class="btn btn-default"/>
                        <input type="submit" name="update" value="Speichern" class="btn btn-default"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
    

    
</div>


<!-- Fehlermeldungen / Erfolgsmeldungen -->
<?php  
    if(isset($_GET['status'])){
        $x=$_GET['status'];
        if($x==0){
            echo'<br/><div class="alert alert-success" role="alert">Event erfolgreich hinzugefügt</div>';
        }else if($x==1){
            echo'<br/><div class="alert alert-danger" role="alert">Hinzufügen fehlgeschlagen! Bitte erneut versuchen</div>';
        }else if($x==2){
            echo'<br/><div class="alert alert-success" role="alert">Event erfolgreich bearbeitet</div>';
        }else if($x==3){
            echo'<br/><div class="alert alert-danger" role="alert">Bearbeiten fehlgeschlagen! Bitte erneut versuchen</div>';
        }else if($x==4){
            echo'<br/><div class="alert alert-success" role="alert">Event erfolgreich gelöscht</div>';
        }else if($x==5){
            echo'<br/><div class="alert alert-danger" role="alert">Löschen fehlgeschlagen! Bitte erneut versuchen</div>';
        }
    }
    
    //Lösche alte Termine
    $sql= deleteOld($projectID);
    $status= mysqli_query($link, $sql);
    
    //Zeige Termine
    $sql= getAllEvents($projectID);
    $result = mysqli_query($link, $sql);
    $count=0;

    echo'<div class="row event-wrapper">';
    
    while($row= mysqli_fetch_array($result)){
        $count++;
        
        $date= $row['Date'];
        $time= substr($row['Time'],0,5);
        $title= $row['Title'];
        $description= $row['Description'];
        $location= $row['Location']; 
        $id= $row['IdEvent'];
        
        
        //Aufsplittung von Datum
        $array= explode("-",$date);
        $year= $array[0];
        $month= $array[1];
        $day= $array[2];
        
        switch($month){
            case 1:
                $m='Januar';
                break;
            case 2:
                $m='Februrar';
                break;
            case 3:
                $m='März';
                break;
            case 4:
                $m='April';
                break;
            case 5:
                $m='Mai';
                break;
            case 6:
                $m='Juni';
                break;
            case 7:
                $m='Juli';
                break;
            case 8:
                $m='August';
                break;
            case 9:
                $m='September';
                break;
            case 10:
                $m='Oktober';
                break;
            case 11:
                $m='November';
                break;
            case 12:
                $m='Dezember';
                break;
            default:
                $m='???';
        }
        
        echo'<div class="col-xs-4 col-md-3 event-container">';
            echo'<div class="event-front">';
                echo'<p class="event-day">'.$day.'</p>';
                echo'<p class="event-month">'.$m.'</p>';
                echo'<p class="event-title">'.$title.'</p>';
            echo'</div>';
            echo'<div class="event-back">';
                echo'<p class="event-desc-title">'.$title.'</p>';
                echo'<p class="event-desc-date">'.$day.'.'.$month.'.'.$year.', '.$time.' Uhr</p>';
                echo'<p class="event-desc-address">'.$location.'</p>';
                echo'<p class="event-desc-desc">'.$description.'</p>';
                if($usertyp==1){
                    echo'<button type="button" class="btn btn-default btn_event_edit" data-toggle="modal" data-target="#editEvent" value="'.$id.'"><i class="fa fa-pencil-square-o"></i></button>';
                }
            echo'</div>';
        echo'</div>';

    }
    
    //Anzeige von leeren, damit mind. 12 Kästchen angezeigt werden
    while($count<12){
        $count++;
        
        echo'<div class="col-xs-4 col-md-3 event-container">';
            echo'<div class="event-front">';
                echo'<p class="event-day"></p>';
                echo'<p class="event-month"></p>';
                echo'<p class="event-title"></p>';
            echo'</div>';
        echo'</div>';
    }
    
    echo'</div>';


?>




</div><!-- End include page-->