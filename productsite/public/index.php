<!--
*   Unicircuit Onepager
*   «index.php»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
-->
<?php
require_once ('../../library/public/contentLoaderProductsite.inc.php');
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable= no">
    <meta name="apple-mobile-web-app-capable" content="yes" />

    <title>Unicircuit</title>

    <!--CSS 3rd Party-->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/font-awesome.css" rel="stylesheet" media="screen">
    <link href="css/simple-line-icons.css" rel="stylesheet" media="screen">
    <link href="css/animate.css" rel="stylesheet" media="screen">
    <link href="css/slick.css" rel="stylesheet" media="screen">
    <link href="css/slick-theme.css" rel="stylesheet" media="screen">
    
    <!--CSS Custom-->
    <style>
        #home {
            background: url(img/<?php
            $link= connectDB();
            $sql= selectImages(1);
            $result = mysqli_query($link, $sql);
            while($row= mysqli_fetch_array($result)){
                echo $row['Filename'];
            }
            ?>);
        }
    </style>   
    <link href="css/style.css" rel="stylesheet" media="screen">

    <!--Favicon-->
    <link rel="shortcut icon" href="icon.png" type="image/png"/>
    <link rel="icon" href="icon.png" type="image/png"/>

    <!--JS 3rd Party-->
    <script src="js/modernizr.custom.js"></script>
      
</head>
<body>
    <!--Preloader-->
    <div id="preloader">
        <div id="status"></div>
    </div>

    <!--Landing Page-->
    <section id="home" class="pfblock-image screen-height">
        <div class="home-overlay"></div>
        <div class="intro">
            <div class="start"><?php echo $LP_company ?></div>
            <h1><?php echo $LP_productName ?></h1>
            <div class="start"><?php echo $LP_slogan ?></div>
        </div>

        <a href="#product">
            <div class="scroll-down">
                <span>
                    <i class="fa fa-angle-down fa-4x"></i>
                </span>
            </div>
        </a>
    </section>
    <!--END Landing page-->

    <!--START Navigation-->
    <header class="header">
        <nav class="navbar navbar-custom">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#custom-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a href="http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/registration.php" class="disturber-btn" target="_blank"><i class="fa fa-shopping-cart fa-2x"></i></a>
                    <a class="navbar-brand" href="index.php"><?php echo $NA_productName ?></a>
                </div>
                <div class="collapse navbar-collapse" id="custom-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#product"><?php echo $NA_menu1 ?></a></li>
                        <li><a href="#edge"><?php echo $NA_menu2 ?></a></li>
                        <li><a href="#gallery"><?php echo $NA_menu3 ?></a></li>                        
                        <li><a href="#pricing"><?php echo $NA_menu4 ?></a></li>
                        <li><a href="#aboutus"><?php echo $NA_menu5 ?></a></li>
                        <li><a href="#contact"><?php echo $NA_menu6 ?></a></li>
                    </ul>
                </div>
            </div>
            
            <div class="disturber"><a href="http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/registration.php" target="_blank"><?php echo $NA_disturber ?></a></div>
		
        </nav>	
    </header>
    <!--END Navigation-->

    <!--START Animation-->
    <section id="product" class="pfblock pfblock-gray">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="pfblock-header wow fadeInUp">
                        <h2 class="pfblock-title"><?php echo $AN_title ?></h2>
                        <div class="pfblock-line"></div>
                        <div class="pfblock-subtitle">                                    
                            <?php echo $AN_subtitle ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12">
                    <div class="animation800">
                        <object type="text/html" data="Animation_800px.html">
                            <p><code>object</code>-Element wird nicht unterstützt bzw. die <a href="Animation_800px.html">Quelle</a> ist nicht verfügbar.</p>
                        </object>
                    </div>
                    <div class="animation600">
                        <object type="text/html" data="Animation_600px.html">
                            <p><code>object</code>-Element wird nicht unterstützt bzw. die <a href="Animation_600px.html">Quelle</a> ist nicht verfügbar.</p>
                        </object>
                    </div>
                    <div class="animation300">
                        <object type="text/html" data="Animation_300px.html">
                            <p><code>object</code>-Element wird nicht unterstützt bzw. die <a href="Animation_300px.html">Quelle</a> ist nicht verfügbar.</p>
                        </object>
                    </div>		
                </div>
            </div>		
        </div>
    </section>
    <!--END Animation-->

    <!--START Module-->  
    <section class="pfblock pfblock-white" id="edge">		
        <div class="container">			
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="pfblock-header wow fadeInUp">
                        <h2 class="pfblock-title"><?php echo $MD_title ?></h2>
                        <div class="pfblock-line"></div>
                        <div class="pfblock-subtitle"><?php echo $MD_subtitle ?></div>						
                    </div>
                </div>
            </div>

            <div class="row modulrow">
                <!--Modul 1-->
                <div class="col-md-2 col-md-offset-2 col-sm-3 col-xs-6 modulcontainer">
                    <div class="modulinhalt">
                        <div><i class="fa fa-newspaper-o fa-2x"></i></div>
                        <h3><?php echo $MD_1_title ?></h3>
                    </div>
                    <!-- mouseover -->	
                    <div class="modul-description">
                        <p><?php echo $MD_1_description ?></p>
                    </div>	
                </div>

                <!-- modul2 -->
                <div class="col-md-2 col-sm-3 col-xs-6 modulcontainer">
                    <div class="modulinhalt">
                        <div><i class="fa fa-calendar fa-2x"></i></div>
                        <h3><?php echo $MD_2_title ?></h3>
                    </div>
                    <!-- mouseover -->	
                    <div class="modul-description">
                        <p><?php echo $MD_2_description ?></p>
                    </div>	
                </div>

                <!-- modul3 -->
                <div class="col-md-2 col-sm-3 col-xs-6 modulcontainer">
                    <div class="modulinhalt">
                        <div><i class="fa fa-bell-o fa-2x"></i></div>
                        <h3><?php echo $MD_3_title ?></h3>
                    </div>
                    <!-- mouseover -->	
                    <div class="modul-description">
                        <p><?php echo $MD_3_description ?></p>
                    </div>	
                </div>

                <!-- modul4 -->
                <div class="col-md-2 col-sm-3 col-xs-6 modulcontainer">
                    <div class="modulinhalt">
                        <div><i class="fa fa-rocket fa-2x"></i></div>
                        <h3><?php echo $MD_4_title ?></h3>
                    </div>
                    <!-- mouseover -->	
                    <div class="modul-description">
                        <p><?php echo $MD_4_description ?></p>
                    </div>	
                </div>
            </div>

            <div class="row">
                <!-- modul5 -->
                <div class="col-md-2 col-md-offset-2 col-sm-3 col-xs-6 modulcontainer">
                    <div class="modulinhalt">
                        <div><i class="fa fa-at fa-2x"></i></div>
                        <h3><?php echo $MD_5_title ?></h3>
                    </div>
                    <!-- mouseover -->	
                    <div class="modul-description">
                        <p><?php echo $MD_5_description ?></p>
                    </div>	
                </div>

                <!-- modul6 -->
                <div class="col-md-2 col-sm-3 col-xs-6 modulcontainer">
                    <div class="modulinhalt">
                        <div><i class="fa fa-camera fa-2x"></i></div>
                        <h3><?php echo $MD_6_title ?></h3>
                    </div>
                    <!-- mouseover -->	
                    <div class="modul-description">
                        <p><?php echo $MD_6_description ?></p>
                    </div>	
                </div>

                <!-- modul7 -->
                <div class="col-md-2 col-sm-3 col-xs-6 modulcontainer">
                    <div class="modulinhalt">
                        <div><i class="fa fa-comments-o fa-2x"></i></div>
                        <h3><?php echo $MD_7_title ?></h3>
                    </div>
                    <!-- mouseover -->	
                    <div class="modul-description">
                        <p><?php echo $MD_7_description ?></p>
                    </div>	
                </div>

                <!-- modul8 -->
                <div class="col-md-2 col-sm-3 col-xs-6 modulcontainer">
                    <div class="modulinhalt">
                        <div><i class="fa fa-building-o fa-2x"></i></div>
                        <h3><?php echo $MD_8_title ?></h3>
                    </div>
                    <!-- mouseover -->	
                    <div class="modul-description">
                        <p><?php echo $MD_8_description ?></p>
                    </div>	
                </div>
                
            </div>
        </div>		
    </section>
    <!--END Module-->	

    <!--START Galerie-->
    <section id="gallery" class="pfblock pfblock-gray">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="pfblock-header wow fadeInUp">
                        <h2 class="pfblock-title"><?php echo $GA_title ?></h2>
                        <div class="pfblock-line"></div>
                        <div class="pfblock-subtitle"><?php echo $GA_subtitle ?></div>						
                    </div>
                </div>
            </div>

            <div class="row gallery-btn">
                <div class="col-sm-2 col-sm-offset-4 col-xs-4 col-xs-offset-2">
                    <button type="button" class="btn btn-default gallery btn-block btn-sm btn-xs" id="btn-desktop">Desktop</button>
                </div>
                <div class="col-sm-2 col-sm-offset-0 col-xs-4 col-xs-offset-0">
                    <button type="button" class="btn btn-default gallery btn-block btn-sm btn-xs" id="btn-mobile">Mobile</button>
                </div>
            </div>

            <div class="row slider-desktop" id="sliderDesktop">			
                <div class="slider">
                    <?php
                    $link= connectDB();
                    $sql= selectImages(3);
                    $result = mysqli_query($link, $sql);
                    while($row= mysqli_fetch_array($result)){
                        echo'<div><img src="img/'.$row['Filename'].'" alt="'.$row['Comment'].'"></div>';
                    }
                    ?>
                </div>	
                <img src="img/imac.png" alt="Bildrahmen Imac" class="imac">
            </div>

            <div class="row slider-mobile" id="sliderMobile">
                <div class="slider">
                    <?php
                    $link= connectDB();
                    $sql= selectImages(2);
                    $result = mysqli_query($link, $sql);
                    while($row= mysqli_fetch_array($result)){
                        echo'<div><img src="img/'.$row['Filename'].'" alt="'.$row['Comment'].'"></div>';
                    }
                    ?>
                </div>
                <img src="img/ipad.png" alt="Bildrahmen Ipad" class="ipad">
            </div>
        </div>
    </section>
    <!--END Galerie-->

    <!--START CallToAction-->
    <section class="calltoaction">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <h2 class="wow slideInRight" data-wow-delay=".1s"><?php echo $CA_title ?></h2>
                    <div class="calltoaction-decription wow slideInRight" data-wow-delay=".2s"><?php echo $CA_subtitle ?></div>
                </div>
                <div class="col-md-12 col-lg-12 calltoaction-btn wow slideInRight" data-wow-delay=".3s">
                    <a href="#contact" class="btn btn-lg"><?php echo $CA_btn ?></a>
                </div>
            </div>
        </div>
    </section>
    <!--END CallToAction-->	

    <!--START Preise -->
    <section id="pricing" class="pfblock pfblock-gray">
        <div class="container">           
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="pfblock-header wow fadeInUp">
                        <h2 class="pfblock-title"><?php echo $PR_title ?></h2>
                        <div class="pfblock-line"></div>
                        <div class="pfblock-subtitle"><?php echo $PR_subtitle ?></div>                       
                    </div>
                </div>
            </div>

            <div class="row pricing">
                <div class="col-sm-4">
                    <div id="preisA">
                        <div class="preiskopf">
                            <h3><?php echo $PR_L_title?></h3>
                            <p><?php echo $PR_L_subtitle?></p>
                        </div>
                        <p><?php echo $PR_L_content1 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_L_content2 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_L_content3 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_L_content4 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_L_content5 ?></p>
                        <div class="col-sm-6 col-sm-offset-3">
                            <button type="button" class="btn btn-default btn-block btn-sm btn-xs btn-preis" onclick="location.href='http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/registration.php'"><?php echo $PR_L_btn ?></button>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div id="preisB">
                        <div class="preiskopf">
                            <h3><?php echo $PR_M_title ?></h3>
                            <p><?php echo $PR_M_subtitle ?></p>
                        </div>
                        <p><?php echo $PR_M_content1 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_M_content2 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_M_content3 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_M_content4 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_M_content5 ?></p>
                        <div class="col-sm-6 col-sm-offset-3">
                            <button type="button" class="btn btn-default btn-block btn-sm btn-xs btn-preis" onclick="location.href='http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/registration.php'"><?php echo $PR_M_btn ?></button>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div id="preisC">
                        <div class="preiskopf">
                            <h3><?php echo $PR_R_title ?></h3>
                            <p><?php echo $PR_R_subtitle ?></p>
                        </div>
                        <p><?php echo $PR_R_content1 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_R_content2 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_R_content3 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_R_content4 ?></p>
                        <div class="preis-line"></div>
                        <p><?php echo $PR_R_content5 ?></p>
                        <div class="col-sm-6 col-sm-offset-3">
                            <button type="button" class="btn btn-default btn-block btn-sm btn-xs btn-preis" onclick="location.href='http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/registration.php'"><?php echo $PR_R_btn ?></button>
                        </div>
                    </div>
                </div>
            </div>					
        </div>
    </section>
    <!--END Preise-->

    <!--START Über Uns-->
    <section id="aboutus" class="pfblock pfblock-white">
        <div class="container">            
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="pfblock-header wow fadeInUp">
                        <h2 class="pfblock-title"><?php echo $AU_title ?></h2>
                        <div class="pfblock-line"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 aboutUs">
                    <?php echo $AU_text ?>
                </div>
            </div>

            <div class="row portrait-container">          	
                <div class="col-sm-4 col-sm-offset-2 portrait">
                    <?php
                    $link= connectDB();
                    $sql= selectImages(4);
                    $result = mysqli_query($link, $sql);
                    while($row= mysqli_fetch_array($result)){
                        echo'<img src="img/'.$row['Filename'].'" alt="'.$row['Comment'].'">';
                    }
                    ?>
                    <p class="name"><?php echo $AU_personLeftName ?></p>
                    <p class="function"><?php echo $AU_personLeftFunction ?></p>
                </div>
                <div class="col-sm-4 col-sm-offset-0 portrait">
                    <?php
                    $link= connectDB();
                    $sql= selectImages(5);
                    $result = mysqli_query($link, $sql);
                    while($row= mysqli_fetch_array($result)){
                        echo'<img src="img/'.$row['Filename'].'" alt="'.$row['Comment'].'">';
                    }
                    ?>
                    <p class="name"><?php echo $AU_personRightName ?></p>
                    <p class="function"><?php echo $AU_personRightFunction ?></p>
                </div>
            </div>					
        </div>
    </section>
    <!--END Über Uns-->

    <!--START Kontakt-->
    <section id="contact" class="pfblock">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="pfblock-header">
                        <h2 class="pfblock-title"><?php echo $CO_title ?></h2>
                        <div class="pfblock-line"></div>
                        <div class="pfblock-subtitle"><?php echo $CO_subtitle ?></div>						
                    </div>
                </div>
            </div>

            <div class="row contact-form">
                <div class="col-sm-6 col-sm-offset-3">
                    <form id="contact-form">
                        <div class="ajax-hidden">
                            <!-- Input Email Feld als Honeypot für Bots -->
                            <input type="email" class="c_important" id="h_email" class="form-control" name="h_email" placeholder="Bitte nicht ausfüllen">
                            <input type="text" class="c_important" id="h_time" class="form-control" name="h_time" value="<?php echo time();  ?>">
                            
                            <div class="form-group wow fadeInUp">
                                <label class="sr-only" for="c_name">Name</label>
                                <input type="text" id="c_name" class="form-control" name="c_name" placeholder="Name">
                            </div>
                            <div class="form-group wow fadeInUp" data-wow-delay=".1s">
                                <label class="sr-only" for="c_email">Email</label>
                                <input type="email" id="c_email" class="form-control" name="c_email" placeholder="E-mail">
                            </div>
                            <div class="form-group wow fadeInUp" data-wow-delay=".2s">
                                <textarea class="form-control" id="c_message" name="c_message" rows="7" placeholder="Nachricht"></textarea>
                            </div>
                            <button type="submit" class="btn btn-default btn-lg btn-block wow fadeInUp btn-send" data-wow-delay=".3s">Senden</button>
                        </div>
                        <div class="ajax-response"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--END Kontakt-->

    <!--START Footer-->
    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <p class="footer-title"><?php echo $FO_1_title ?></p>
                    <p><?php echo $FO_1_contentLine1 ?></p>
                    <p><?php echo $FO_1_contentLine2 ?></p>
                    <p><?php echo $FO_1_contentLine3 ?></p>
                </div>
                <div class="col-sm-3">
                    <p class="footer-title"><?php echo $FO_2_title ?></p>
                    <p><?php echo $FO_2_contentLine1 ?></p>
                    <p><?php echo $FO_2_contentLine2 ?></p>
                    <p><?php echo $FO_2_contentLine3 ?></p>
                </div>
                <div class="col-sm-3">
                    <p class="footer-title">Unicircuit</p>
                    <p><a href="http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/login.php" target="_blank">Login</a></p>
                    <p><a href="http://palmers.dynathome.net:8024/diplomarbeit/platform/public/php/registration.php" target="_blank">Registration</a></p>		
                </div>
                <div class="col-sm-3">
                    <p class="footer-title">Rechtliches</p>
                    <p><a href="agb.php">AGB</a></p>
                    <p><a href="impressum.php">Impressum</a></p>
                </div>
            </div>

            <div class="row copyright">
                <div class="col-sm-12">
                    <p>Copyright <i class="fa fa-copyright"></i><?php echo $FO_copyright ?></p>
                </div>
            </div>
        </div>
    </footer>
    <!--END Footer-->	

    <!--START ScrollToTop-->
    <div class="scroll-up">
        <a href="#product"><i class="fa fa-angle-up"></i></a>
    </div>   
    <!--END ScrollToTop-->

    <!--JS 3rd Party-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.parallax-1.1.3.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/slick.js"></script>
    
    <!--JS Custom-->
    <script src="js/script.js"></script>	
    
</body>
</html>		