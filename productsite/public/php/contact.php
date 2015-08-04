<?php

if(isset($_POST['message'])){

	$name = $_POST['name'];
	$email = $_POST['email'];
	$message = $_POST['message'];

	$messageForm= 
		'<html>
		<head>
		    <title>Kontaktanfrage Unicircuit</title>
		</head>
		 
		<body>
		 
		<h3>Kontaktanfrage Unicircuit</h3>
		<br />
		<p><b>Name:</b> '.$name.' </p>
		<br /><br />		 
		<p><b>Nachricht:</b><br />'.$message.'</p>
		 
		</body>
		</html>';
		

	$messageBack= 
		'<html>
		<head>
		    <title>Kontaktanfrage Unicircuit</title>
		</head>
		 
		<body>
		 
		<h3>Kontaktanfrage Unicircuit</h3>
		<br />
		<p>Sehr geehrter '.$name.'</p>
		<p>Besten Dank für Ihre Kontaktanfrage.</p>
		<p>Wir werden uns schnellstmöglich mit Ihnen in Verbindung setzen.<p>
		<br />
		<p>Freundliche Grüsse</p>
		<p>Ihr Unicircuit-Team</p>
		 
		</body>
		</html>';
   
	
	$to      = 'claudio.schaepper@gmail.com';
	$subject = 'Anfrage Unicircuit';

	$header  = "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
	 
	$header .= 'From: '.$email."\r\n";
	$header .= 'Reply-To: '.$email."\r\n";
	// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
	$header .= "X-Mailer: PHP/". phpversion();


	$to2      = $email;
	$subject2 = 'Anfrage Unicircuit';

	$header2  = "MIME-Version: 1.0\r\n";
	$header2 .= "Content-type: text/html; charset=iso-8859-1\r\n";
	 
	$header2 .= 'From: "noreply@unicircuit.ch"\r\n';
	$header2 .= 'Reply-To: "info@unicircuit.ch"\r\n';
	// $header .= "Cc: $cc\r\n";  // falls an CC gesendet werden soll
	$header2 .= "X-Mailer: PHP/". phpversion();


	mail($to2, $subject2, $messageBack, $header);

	$status = mail($to, $subject, $messageForm, $header);

	if($status == TRUE){	
		$res['sendstatus'] = 'done';
	
		//Edit your message here
		$res['message'] = 'Ihre Kontaktanfrage wird so schnell wie möglich beantwortet.';

	//Auto Antwort an Kunden	
	/*$to2      = $email;
	$subject2 = 'Vielen Dank für Ihr Interesse an Unicircuit';

	$headers2 = 'From: noreply@unicircuit.ch' "\r\n" .
    'Reply-To: noreply@unicircuit.ch' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	$status = mail($to2, $subject2, $messageBack, $headers2);*/
    }
	else{
		$res['message'] = 'Senden fehlgeschlagen. Bitte senden Sie Ihre Nachricht an info@unicircuit.ch';
	}
	
	
	echo json_encode($res);
}

?>