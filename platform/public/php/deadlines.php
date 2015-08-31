<?php
require_once ('../../../library/public/database.inc.php');

$projectID=2;
$usertyp=1; //1= Architekt, 2=Bauherr

$link= connectDB();

//Speichern einer neuen Deadline
if(isset($_POST['submit'])){
    $dateOrg= $_POST['date'];
    $date = date('Y-m-d', strtotime($dateOrg));
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $idCraftsman = filter_input(INPUT_POST, 'craftsman', FILTER_SANITIZE_NUMBER_INT);
    
    if(empty($date) || $date== 0000-00-00){
        $error=true;
    }
    if(empty($title)){
        $error=true;
    }
    
    
    if(!isset($error)){
        $sql= newDeadline($projectID, $date, $title, $description, $idCraftsman);
        $status= mysqli_query($link, $sql);
        if($status){
            header("Location: index.php?id=5&status=0");
        }else{
            header("Location: index.php?id=5&status=1");
        }
    }else{
        header("Location: index.php?id=5&status=1");
    }
}

//Update einer bestehenden Deadline
if(isset($_POST['update'])){
    $dateOrg= $_POST['date'];
    $date = date('Y-m-d', strtotime($dateOrg));
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $idCraftsman = filter_input(INPUT_POST, 'craftsman', FILTER_SANITIZE_NUMBER_INT);
    $deadlineID= filter_input(INPUT_POST, 'deadlineID', FILTER_SANITIZE_NUMBER_INT);
    
    if(empty($date) || $date== 0000-00-00){
        $error=true;
    }
    if(empty($title)){
        $error=true;
    }
    
    
    if(!isset($error)){
        $sql= updateDeadline($deadlineID, $date, $title, $description, $idCraftsman);
        $status= mysqli_query($link, $sql);
        if($status){
            header("Location: index.php?id=5&status=2");
        }else{
            header("Location: index.php?id=5&status=3");
        }
    }else{
        header("Location: index.php?id=5&status=3");
    }
}

//Löschfunktion
if(isset($_POST['delete'])){

    if(!empty($_POST['deadlineID'])){
        $id= filter_input(INPUT_POST, 'deadlineID', FILTER_SANITIZE_NUMBER_INT);
        
        $sql= deleteDeadline($id);
        $status = mysqli_query($link, $sql);
        if($status){
            header("Location: index.php?id=5&status=4");
        }else{
            header("Location: index.php?id=5&status=5");
        }
    }else{
        header("Location: index.php?id=5&status=5");
    }
    
}
?>

<div class="col-xs-12">
    <h2 class="modul-title">Entscheidungs- Termine</h2>
    
    <!--Lightboxen (Modals)-->
    <div class="container modalgroup">

    <!-- Trigger the modal with a button -->
<?php
    if($usertyp==1){
        echo'<button type="button" class="btn btn-default" data-toggle="modal" data-target="#newDeadline">+ hinzufügen</button>';
    } 
?>    
    <!-- Modal Global-->
    <div class="modal" id="newDeadline" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="deadlines.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Deadline hinzufügen</h4>
                    </div>
                        <div class="modal-body">
                            <div id="input_container">

                                <label for="title">Titel*</label>
                                <input id="title" type="text" name="title" class="form-control" maxlength="25">
                                <label for="date">Datum*</label>
                                <input id="date" type="" name="date" class="form-control datepicker">
                                <label for="craftsman">Partner</label>
                                <select name="craftsman" class="form-control" id="craftsman">
                                    <option value="">kein Handwerker</option>
                                    <option value="" disabled="disabled">—————————————————————</option>
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
                                <label for="description">Beschreibung</label>
                                <textarea id="description" name="description" class="form-control" rows="5"></textarea>

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
    <div class="modal" id="editDeadline" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form enctype="multipart/form-data" action="deadlines.php" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Deadline bearbeiten</h4>
                    </div>
                        <div class="modal-body">
                            <div id="deadlineEditContainer">

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
    
    
    <!-- Modal Global-->
    <div class="modal" id="showDeadline" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Deadline Details</h4>
                    </div>
                        <div class="modal-body">
                            <div id="deadlineShowContainer">

                                <!-- Ajax Content -->

                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    </div>

            </div>

        </div>
    </div>



</div>
 
<?php
if(isset($_GET['status'])){
        $x=$_GET['status'];
        if($x==0){
            echo'<br/><div class="alert alert-success" role="alert">Deadline erfolgreich hinzugefügt</div>';
        }else if($x==1){
            echo'<br/><div class="alert alert-danger" role="alert">Hinzufügen fehlgeschlagen! Bitte erneut versuchen</div>';
        }else if($x==2){
            echo'<br/><div class="alert alert-success" role="alert">Deadline erfolgreich bearbeitet</div>';
        }else if($x==3){
            echo'<br/><div class="alert alert-danger" role="alert">Bearbeiten fehlgeschlagen! Bitte erneut versuchen</div>';
        }else if($x==4){
            echo'<br/><div class="alert alert-success" role="alert">Deadline erfolgreich gelöscht</div>';
        }else if($x==5){
            echo'<br/><div class="alert alert-danger" role="alert">Löschen fehlgeschlagen! Bitte erneut versuchen</div>';
        }
    }
    
?>
    
    
    
    <div class="row deadline-wrapper">
        
        <?php
        
        $sql= getAllDeadlines($projectID);
        $result= mysqli_query($link, $sql);

        while($row=  mysqli_fetch_array($result)){
            $title=$row['DeadlineTitle'];
            $dateOrg= $row['DeadlineDate'];
            $date = date('d.m.Y', strtotime($dateOrg));
            $dateINT= strtotime($date);
            $idCraftsman= $row['IdCraftsman'];
            $id= $row['IdDeadlines'];
            
            //Hintergrundklasse bestimmen aufgrund von Datum (2 Tage -> Rot, 7 Tage-> Orange)
            $dateCurrent = date('d.m.Y');
            $dateredString= date('d.m.Y', strtotime($dateCurrent. ' + 2 days'));
            $datered= strtotime($dateredString);
            $dateorangeString= date('d.m.Y', strtotime($dateCurrent. ' + 7 days'));
            $dateorange= strtotime($dateorangeString);
            
            if($dateINT <= $datered){
                 $class='deadline-red'; 
            }else if($dateINT <= $dateorange){
                $class='deadline-orange';
            }else{
                $class='';
            }
            
            
            //SQL Abfrage nach Handwerker Name, Firma
            if(!empty($idCraftsman)){
                $sql2= getProjectAddress($idCraftsman);
                $result2= mysqli_query($link, $sql2);
                while($row2=  mysqli_fetch_array($result2)){
                    $company= $row2['Company'];
                }
            }else{
                $company='';
            }
            
            
            echo'<div class="col-xs-12 deadline-row '.$class.'">';
                echo'<div class="deadline-date">'.$date.'</div>';
                echo'<div class="deadline-title">'.$title.'</div>';
                echo'<div class="deadline-description hidden-sm hidden-xs">'.$company.'</div>';
                if($usertyp==1){
                    echo'<button type="button" class="btn btn-default deadline-btn-edit" data-toggle="modal" data-target="#editDeadline" value="'.$id.'"><i class="fa fa-pencil-square-o"></i></button>';
                }else if($usertyp==2){
                    echo'<button type="button" class="btn btn-default deadline-btn-show" data-toggle="modal" data-target="#showDeadline" value="'.$id.'"><i class="fa fa-info-circle"></i></button>';
                }
            echo'</div> ';            
        }
        
        ?>          
        
        
    </div>
       
</div><!-- End Include Teil -->