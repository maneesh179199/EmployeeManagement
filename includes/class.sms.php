<?php
function smsSend($mobile, $message, $module='') {
	global $db;
	$url = "http://alerts.prioritysms.com/api/web2sms.php?workingkey=afdsfadfasdfd9e18f549b1973a32&sender=TEST&to=".$mobile."&message=".urlencode($message);	 	
	/*
	* Submit to SMS API
	*/
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
	/*
	* Insert into database
	*/
	$db->execute("INSERT INTO tblsentsms(mobile, textsms, sentdate, responsedetail) values('".$mobile."', '".$message."', NOW(), '".mysql_real_escape_string($output)."') ");			
	return true;
}

function sendNewUserSMS($type, $name, $email, $password, $tomobile) {
	$message = "Hi ".$name.", Welcome to ".SITENAME.". your ".$type." account has been created. Your login details are Email: ".$email.", Password: ".$password;
	smsSend($tomobile, $message, 'account');
	return true;
}
function sendPasswordSMS($name, $password, $tomobile) {
	$message = "Hi ".$name.", Your ".SITENAME." account password is recently updated, your new password is: ".$password;
	smsSend($tomobile, $message, $module='');
	return true;
}
function sendAccountActivitySMS($type, $name, $tomobile, $action) {
	if($action=='delete') {
		$message = "Hi ".$name.", Your ".SITENAME." ".$type." account has been deleted, Now you can no longer use our services.";
	} elseif($action=='suspend') {
		$message = "Hi ".$name.", Your ".SITENAME." ".$type." account has been suspended, Now you can no longer use our services.";
	} elseif($action=='activate') {
		$message = "Hi ".$name.", Your ".SITENAME." ".$type." account has been activated.";
	}
	smsSend($tomobile, $message, $module='');
	return true;
}