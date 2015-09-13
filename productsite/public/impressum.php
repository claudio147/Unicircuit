<?php
require_once '../../library/public/contentLoaderProductsite.inc.php';

?>

<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable= no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	
	<title>Unicircuit-Impressum</title>

	<!-- CSS 3rd party -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/font-awesome.css" rel="stylesheet" media="screen">
	<link href="css/simple-line-icons.css" rel="stylesheet" media="screen">
	<link href="css/animate.css" rel="stylesheet">
    
	<!-- Custom styles CSS -->
	<link href="css/style.css" rel="stylesheet" media="screen">
        
        <!-- FAVICON -->
        <link rel="shortcut icon" href="icon.png" type="image/png" />
        <link rel="icon" href="icon.png" type="image/png" />
  	
  	<!-- JS 3rd Party-->  
        <script src="js/modernizr.custom.js"></script>

</head>
<body>
	<header class="header-fix">
		<nav class="navbar navbar-custom" role="navigation">
			<div class="container">
				<div class="navbar-header">				
					<a class="navbar-brand" href="index.php"><?php echo $NA_productName ?></a>
					<a href="index.php" class="disturber-btn"><i class="fa fa-home fa-2x"></i></a>			
				</div>
				<div class="collapse navbar-collapse" id="custom-collapse">
					<ul class="nav navbar-nav navbar-right nav-titel">
						<li><a href="index.php">Zurück zur Hauptseite</a></li>						
					</ul>
				</div>
			</div><!-- .container -->		
		</nav>
	</header>

	<div class="container" id="top">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 agb biglist">
				<h1><?php echo $IP_title ?></h1>
                <?php echo $IP_content ?>				
			</div>
		</div>	
	</div>

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
					<p><a href="">Login</a></p>
					<p><a href="">Registration</a></p>		
				</div>
				<div class="col-sm-3">
					<p class="footer-title">Rechtliches</p>
					<p><a href="agb.php">AGB</a></p>
					<p><a href="impressum.php">Impressum</a></p>
				</div>
			</div><!-- End row -->
			<div class="row copyright">
				<div class="col-sm-12">
					<p>Copyright <i class="fa fa-copyright"></i><?php echo $FO_copyright ?></p>
				</div>
			</div>
		</div><!-- End container -->
	</footer>

	<!-- Scroll to top Start-->
	<div class="scroll-up">
		<a href="#top"><i class="fa fa-angle-up"></i></a>
	</div>   
    <!-- Scroll to top end-->

	<!-- Javascript files -->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="js/jquery.parallax-1.1.3.js"></script>
	<script src="js/imagesloaded.pkgd.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/smoothscroll.js"></script>
	<script src="js/wow.min.js"></script>
    <script src="js/jquery.easypiechart.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.cbpQTRotator.js"></script>
    <script src="js/slick.js"></script>
	<script src="js/script.js"></script>

</body>
</html>





