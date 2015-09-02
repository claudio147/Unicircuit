<?php

?>

<div class="col-xs-12 ">
    <div class="row dash-wrapper">
        <div class="col-xs-6 ">
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
                        $date = date('d.m.Y', strtotime($row['Date']));
                        $time= substr($row['Time'],0,5);
                        if($row['Id_visible']==1){
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
                            echo'<h2 class="dashboard-title">Galerie<a class="dash-link" href="index.php?id=2"><i class="fa fa-external-link"></i></a></h2>';
                        echo'</div>';
                    echo'</div>';
                echo'<div class="row row-dash-gallery">';
                    echo'<div class="col-xs-12 dash-gallery">';
                    
                        echo'<div class="dash-slick-gallery">';
                        if($usertyp==1){
                            $sql=showAllIMG($projectID);
                        }else{
                            $sql=showIMG($projectID, $usertyp);
                        }
                        $result= mysqli_query($link, $sql);
                        while($row= mysqli_fetch_array($result)){
                            $imgL= $row['HashNameL'];
                            $imgS= $row['HashNameS'];
                            $com= $row['Comment'];
                            $date= $row['Date'];
                            $id= $row['IdPicture'];
                            echo'<div class="dash-slick-img"><img src="'.$imgS.'" alt="'.$com.'"></div>';
                        }
                        echo'</div>';
                    
                    
                    echo'</div>';  
                echo'</div>'; 
                    
                    ?>
                </div>
                <div class="col-xs-12 dash-deadlines">
                    <?php
                    //include ('deadlines.php');
                    ?>
                </div>
                <div class="col-xs-12 dash-events">
                    <?php
                    //include ('events.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    
</div><!-- End Include Teil -->

