<?php
require 'class.phpmailer.php';
function phpMailerFunction($to, $subject, $message) {
	$mail =	new PHPMailer(true); //New instance, with exceptions enabled
	$body	=	$message;
	$body	=	preg_replace('/\\\\/','', $body); //Strip backslashes
	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = OUT_PORT;                    // set the SMTP server port
	$mail->Host       = OUTGOING_SERVER; // SMTP server
	$mail->Username   = MAIL_USERNAME;     // SMTP server username
	$mail->Password   = MAIL_PASSWORD;            // SMTP server password
	//$mail->IsSendmail();  // tell the class to use Sendmail
	$mail->From       = FROM_MAIL;
	$mail->FromName   = FROM_NAME;
	$mail->AddAddress($to);
	$mail->Subject  = $subject;
	$mail->WordWrap   = 80; // set word wrap
	$mail->MsgHTML($body);
	$mail->IsHTML(true); // send as HTML
	$mail->Send();
}
function verifyEmail($firstname, $lastname, $token, $id,$email,$redirect_url) {
	
	$url="?id=".$id."&token=".$token;
	
	$redirect_url=$redirect_url.$url;
	$tokens = array(
		'{{redirect_url}}' => $redirect_url,
		'{{fullname}}' => 'Maneesh',
		//'email' => $email,
		//'mobile' => $mobile,
		//'city' => $city,
		//'state' => $state,
		//'username' => $username,
		//'password' => $password,
		//'ip' => $registerip,
		'{{_site_logo}}' => HTTP.'assets/images/logo-dark.png',
		'_site_name' => SITENAME,
		'_site_email' => 'dcsdfsdfsdf',
		'_site_phone' => 'asdasdada',
	);
	$pattern = '{{%s}}';
	$map = array();
	foreach($tokens as $var=>$value) {
		$map[sprintf($pattern, $var)] = $value;
	}
	$htmlbody = file_get_contents(DIR."/users/verify_mail.php");

	$emailbody = strtr($htmlbody, $map);
	$subject = "Email Verification By ".SITENAME;
	$emailbody=str_replace("{{redirect_url}}", $redirect_url, $emailbody);
	$emailbody=str_replace("{{fullname}}", $firstname." ".$lastname, $emailbody);
	$emailbody=str_replace("{{_site_logo}}",HTTP.'assets/images/logo-dark.png', $emailbody);
	$emailbody=str_replace("{{site_name}}",SITENAME, $emailbody);
	var_dump($emailbody);die;
	try {
		phpMailerFunction($email, $subject, $emailbody);
		
	}
	catch(Exception $e) {
  	echo 'Message: '.$e->getMessage();
	}
}
function sendNewUserEmail($kioskname, $fullname, $email, $mobile, $city, $state, $username, $password, $registerip) {
	$tokens = array(
		'kioskname' => $kioskname,
		'fullname' => $fullname,
		'email' => $email,
		'mobile' => $mobile,
		'city' => $city,
		'state' => $state,
		'username' => $username,
		'password' => $password,
		'ip' => $registerip,
		'_site_logo' => HTTP.'/assets/img/logo.png',
		'_site_name' => SITENAME,
		'_site_email' => EMAIL,
		'_site_phone' => PHONE,
	);
	$pattern = '{{%s}}';
	$map = array();
	foreach($tokens as $var=>$value) {
		$map[sprintf($pattern, $var)] = $value;
	}
	$htmlbody = file_get_contents(DIR."/assets/email/welcome.html");
	$emailbody = strtr($htmlbody, $map);
	$subject = "Welcome to ".SITENAME;
	try {
		phpMailerFunction($email, $subject, $emailbody);
	}
	catch(Exception $e) {
  	echo 'Message: '.$e->getMessage();
	}
}
function setNewPassword($firstname, $lastname, $email, $token_id) {
	$subject = "Password Reset Mail  form Grevance";
	$body = "<html>
				<body>
				<h2 style='color: green;'>Hello ".$firstname." ".$lastname.",</h2>
				<h4>Please click on the below link to Reset Your Password.</h4>
				<br>
				<a href='http://localhost/grievance/users/reset_password.php?token=".$token_id."&email=".$email."'>Click here to Reset Your Password</a>
				<br>
				<p>Grievance Department  Team</p>
				</body>
				</html>";
	try {
		phpMailerFunction($email, $subject, $body);
		return true;
	}
	catch(Exception $e) {
  	echo 'Message: '.$e->getMessage();
  	return false;
	}
}
function closeCase($firstname, $lastname,$email) {
	$subject = "Email Regarding Your Complain";
	$body = "<html>
				<body>
				<h2 style='color: green;'>Greetings</h2>
				<h4>Dear ".$firstname." ".$lastname."</h3>
				<br>
				<p>This mail is to inform you that we have successfully resolved Your Complain
				and we have now offically closed your complain. Kindly Log In for furthur details 
				<br>
				<p>Thanks,<br>Grievance Portal, Team</p>
				</body>
				</html>";
	try {
		phpMailerFunction($email, $subject, $body);
	    return true; 
	}
	catch(Exception $e) {
  	echo 'Message: '.$e->getMessage();
  	return false;
	}
}
?>