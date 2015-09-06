<?php

?>

<div class="col-xs-12 ">
    <div class="row dash-wrapper">
        <div class="col-xs-6 dash-left">
            <div class="row">
            <?php
                echo'<div class="col-xs-12">';
                    echo'<div class="row">';
                        echo'<div class="col-xs-12 dash-header">';
                            echo'<h2 class="dashboard-title">Chronik<a class="dash-link" href="index.php?id=2"><i class="fa fa-external-link"></i></a></h2>';
                            echo'';
                        echo'</div>';
                    echo'</div>';
                echo'</div>';
                echo'<div class="col-xs-12 dash-timeline">';
                    $sql= selectPosts($projectID);
                    $result = mysqli_query($link, $sql);

                    //echo'<div class="container">';
                    // Ausgabe Timeline
                    while($row= mysqli_fetch_array($result)){
                        //Architekt sieht alle
                        if($usertyp==2){
                            $date = date('d.m.Y', strtotime($row['Date']));
                            $time= substr($row['Time'],0,5);
                            if($row['Id_visible']==1 && $usertyp==2){
                                $lock='<i class="fa fa-lock"></i>';
                            }else{
                                $lock='';
                            }
                            echo'<div class="post row post-dash">';
                            echo'<h3>'.$row['Title'].'  '.$lock.'</h3>';
                            echo'<p class="date">'.$date.', '.$time.'</p>';
                            echo'<div class="col-xs-3 imgLiquidFill imgLiquid dash-timeline-img">';
                            echo'<a href="#" data-featherlight="'.$row['Path'].$row['HashName'].'"><img alt="" src="'.$row['Path'].$row['HashName'].'"/></a>';
                            echo'</div>';
                            echo'<div class="col-xs-9">';
                            echo'<p>'.$row['Description'].'</p>';
                            echo'</div>';
                            echo'</div>';//End row
                        }else if($usertyp==3 && $row['Id_visible']==2){
                            $date = date('d.m.Y', strtotime($row['Date']));
                            $time= substr($row['Time'],0,5);
                            echo'<div class="post row post-dash">';
                            echo'<h3>'.$row['Title'].'</h3>';
                            echo'<p class="date">'.$date.', '.$time.'</p>';
                            echo'<div class="col-xs-3 imgLiquidFill imgLiquid dash-timeline-img">';
                            echo'<a href="#" data-featherlight="'.$row['Path'].$row['HashName'].'"><img alt="" src="'.$row['Path'].$row['HashName'].'"/></a>';
                            echo'</div>';
                            echo'<div class="col-xs-9">';
                            echo'<p>'.$row['Description'].'</p>';
                            echo'</div>';
                            echo'</div>';//End row
                        }
                        
                    }
                    //echo'</div>';//End container
                echo'</div>';
            ?>
            </div><!-- End row-->
        </div>
        <div class="col-xs-6">
            <div class="row dash-right">
                <div class="col-xs-12 dash-gallery-container">
                    <?php

                    echo'<div class="row">';
                        echo'<div class="col-xs-12 dash-header">';
                            echo'<h2 class="dashboard-title">Galerie<a class="dash-link" href="index.php?id=7"><i class="fa fa-external-link"></i></a></h2>';
                        echo'</div>';
                    echo'</div>';
                echo'<div class="row row-dash-gallery">';
                    echo'<div class="col-xs-12 dash-gallery">';
                        echo'<div class="dash-slick-gallery">';
                        if($usertyp==2){
                            $sql=showAllIMG($projectID);
                        }else{
                            $sql=showIMG($projectID, 2);
                        }
                        $result= mysqli_query($link, $sql);
                        while($row= mysqli_fetch_array($result)){
                            $imgL= $row['HashNameL'];
                            $imgS= $row['HashNameS'];
                            $com= $row['Comment'];
                            $date= $row['Date'];
                            $id= $row['IdPicture'];
                            echo'<div class="dash-slick-img imgLiquidFill imgLiquid" data-imgLiquid-verticalAlign="50%"><img src="'.$imgS.'" alt="'.$com.'"></div>';
                        }
                        echo'</div>';
                    echo'</div>';
                echo'</div>';
                    ?>
                </div>
                <div class="col-xs-12 dash-deadlines-container">
                    <?php

                    echo'<div class="row">';
                        echo'<div class="col-xs-12 dash-header">';
                            echo'<h2 class="dashboard-title">Deadlines<a class="dash-link" href="index.php?id=5"><i class="fa fa-external-link"></i></a></h2>';
                        echo'</div>';
                    echo'</div>';
                    echo'<div class="row row-dash-deadlines">';
                        echo'<div class="col-xs-12 dash-deadlines">';
                            echo'<div class="row deadline-wrapper">';

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

                                echo'<div class="col-xs-12 deadline-row deadline-row-dash '.$class.'">';
                                    echo'<div class="deadline-date deadline-date-dash">'.$date.'</div>';
                                    echo'<div class="deadline-title deadline-title-dash">'.$title.'</div>';
                                echo'</div> ';
                            }

                        echo'</div>';



                        echo'</div>';
                    echo'</div>';
                    ?>
                </div>
                <div class="col-xs-12 dash-deadlines-container">
                    <?php

                    echo'<div class="row">';
                        echo'<div class="col-xs-12 dash-header">';
                            echo'<h2 class="dashboard-title">Events<a class="dash-link" href="index.php?id=4"><i class="fa fa-external-link"></i></a></h2>';
                        echo'</div>';
                    echo'</div>';
                    echo'<div class="row row-dash-events">';
                        echo'<div class="col-xs-12 dash-events">';
                            //Lösche alte Termine
                            $sql= deleteOld($projectID);
                            $status= mysqli_query($link, $sql);
                            
                            //Zeige Termine
                            $sql= getAllEvents($projectID);
                            $result = mysqli_query($link, $sql);
                            $count=0;

                            echo'<div class="row event-wrapper">';
                            
                            
                            while($row= mysqli_fetch_array($result)){
                                if($count<4){
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
                                    
                                    echo'<div class="col-xs-4 col-md-3 event-container event-container-dash">';
                                        echo'<div class="event-front event-front-dash">';
                                            echo'<p class="event-day">'.$day.'</p>';
                                            echo'<p class="event-month">'.$m.'</p>';
                                        echo'</div>';
                                        echo'<div class="event-back event-back-dash">';
                                            echo'<p class="event-desc-title">'.$title.'</p>';
                                        echo'</div>';
                                    echo'</div>';
                                }
                                

                            }
                            
                            echo'</div>';








                        echo'</div>';
                    echo'</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>





</div><!-- End Include Teil -->

