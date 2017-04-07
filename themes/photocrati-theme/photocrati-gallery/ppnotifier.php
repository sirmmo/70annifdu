<?php
/* minimize reported failures from PayPal */
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
// Report no errors
ini_set('display_errors', 'Off');
error_reporting(0);

	define('ABSPATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/');
	include_once(ABSPATH.'wp-config.php');
	include_once(ABSPATH.'wp-load.php');
	include_once(ABSPATH.'wp-includes/wp-db.php');
	global $wpdb;
	
// Report no errors
ini_set('display_errors', 'Off');
error_reporting(0);
	
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	
	foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
	}
	
	// post back to PayPal system to validate
	$header = null;
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
	
	// assign posted variables to local variables
	$firstName = $_POST['first_name'];
	$lastName = $_POST['last_name'];
	$email = $_POST['payer_email'];
	
	if (!$fp) {
	// HTTP ERROR
	} else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
	$res = fgets ($fp, 1024);
	if (strcmp ($res, "VERIFIED") == 0) {
	// check the payment_status is Completed
	// check that txn_id has not been previously processed
	// check that receiver_email is your Primary PayPal email
	// check that payment_amount/payment_currency are correct
	// process payment
	}
	else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	}
	}
	fclose ($fp);
	}
	
	$admin_info = get_userdata(1);
	$admineaddr = get_option('admin_email');
	
	if (isset($admin_info->user_email) && $admin_info->user_email != null)
	{
		$admineaddr = $admin_info->user_email;
	}
	
	// Send thank you email/receipt
	$p_date = date("M d, Y");
	$subject = "Thank you for your purchase!";
	$message = "Thank you for your purchase! A notification will be sent to you by PayPal with the transaction details.";
	$headers = "From: ".$admineaddr."\r\n";
	$headers .= "Return-Path: ".$admineaddr."\r\n";
							
	mail($email, $subject, $message, $headers);
							
							
	$subject2 = "New Sale";
	$message2 = "A new sale has been made:
Name: ".$firstName." ".$lastName."
Email: ".$email."";							
	$headers2 = "From: ".$admineaddr."\r\n";
	$headers2 .= "Return-Path: ".$admineaddr."\r\n";
							
	mail($admineaddr, $subject2, $message2, $headers2);
	
	/* IMPORTANT! This code empties the entire cart! */
	unset($_SESSION['cart']);
	unset($_SESSION['cart_qty']);
?>
