
<?php


/**
 * Send debug code to the Javascript console
 */ 
/*
function debug_to_console($data) {
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}
	debug_to_console('test');



*/
require_once '../../../library/public/mail.inc.php';


if(isset($_POST['message'])){

	$name = $_POST['name'];
	$email = $_POST['email'];
	$message = $_POST['message'];

	

	$email2 = 'claudio.schaepper@gmail.com';
	
	$status2 = sendMailArchcon($name, $email2, $message, $email);
        $status1 = sendMailCustomer($name, $email);
        //$status2=true;
        //$status1=true;



/*
	//Email Absenderadresse (Archconsulting)
	$email2 = 'info@unicircuit.ch';
	
	//Nachricht für Archconsulting
	$messageForm= 
		'<html>
		<head>
		    <title>Kontaktanfrage Unicircuit</title>
		    <style>
		    	font-family: "Arial", sans-serif;
		    </style>
		</head>
		 
		<body>
		 
		<h3>Kontaktanfrage Unicircuit</h3>
		<br />
		<p><b>Name:</b> '.$name.' </p>
		<br />		 
		<p><b>Nachricht:</b><br />'.$message.'</p>
		 
		</body>
		</html>';
		
	//Autoantwort für Kunde
	$messageBack= 
		'<html>
		<head>
		    <title>Kontaktanfrage Unicircuit</title>
		    <style>
		    	font-family: "Arial", sans-serif;
		    </style>
		</head>
		 
		<body>
		 
		<h3>Kontaktanfrage Unicircuit</h3>
		<br />
		<p>Guten Tag '.$name.'</p>
		<p>Besten Dank für Ihre Kontaktanfrage.</p>
		<p>Wir werden uns schnellstmöglich mit Ihnen in Verbindung setzen.<p>
		<br />
		<p>Freundliche Grüsse</p>
		<p>Ihr Unicircuit-Team</p>
		 
		</body>
		</html>';
   
	
	//Email für Archconsulting
	$to      = 'claudio.schaepper@gmail.com';
	$subject = 'Anfrage Unicircuit';

	$header  = "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html; charset=utf-8\r\n";
	 
	$header .= 'From: '.$email."\r\n";
	$header .= 'Reply-To: '.$email."\r\n";
	// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
	$header .= "X-Mailer: PHP/". phpversion();


	//Email für Kunde (Autoantwort)
	$to2      = $email;
	$subject2 = 'Anfrage Unicircuit';

	$header2  = "MIME-Version: 1.0\r\n";
	$header2 .= "Content-type: text/html; charset=utf-8\r\n";
	 
	$header2 .= 'From: '.$email2."\r\n";
	$header2 .= 'Reply-To: '.$email2."\r\n";
	// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
	$header2 .= "X-Mailer: PHP/". phpversion();

	//Send Email für Kunde (Autoantwort)
	$status2 = mail($to2, $subject2, $messageBack, $header2);

	//Send Email für Archconsutling mit Kontaktanfrage
	$status1 = mail($to, $subject, $messageForm, $header);
*/
	if($status1 && $status2/* == TRUE && $status2==TRUE*/){	

		$res['message'] = 'Ihre Kontaktanfrage wird so schnell wie möglich beantwortet.';
    }
	else{
		$res['message'] = 'Senden fehlgeschlagen. Bitte senden Sie Ihre Nachricht an info@unicircuit.ch';
	}
	
	
	echo json_encode($res);
}

