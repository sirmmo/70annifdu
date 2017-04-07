<?php
/* minimize reported failures from PayPal */
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
// Report no errors
ini_set('display_errors', 'Off');
error_reporting(0);

define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))).'/');
include_once(ABSPATH.'wp-config.php');
include_once(ABSPATH.'wp-load.php');
include_once(ABSPATH.'wp-includes/wp-db.php');
global $wpdb;

// Report no errors
ini_set('display_errors', 'Off');
error_reporting(0);

$post_array = $_POST;
$cart_settings = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_ecommerce_settings WHERE id = 1", ARRAY_A);

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate&' . http_build_query($post_array);

// post back to PayPal system to validate
$header = null;
$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .= "Host: www.paypal.com\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$firstName = $post_array['first_name'];
$lastName = $post_array['last_name'];
$email = $post_array['payer_email'];
$item_number = isset($post_array['item_number']) ? $post_array['item_number'] : null;
$payment_status = $post_array['payment_status'];
$receiver_email = $post_array['receiver_email'];
$payment_currency = $post_array['mc_currency'];
$paid_amount = $post_array['mc_gross'];
$currency_code = $cart_settings['ecomm_currency'];

$is_valid = false;

if (!$fp) {
    // HTTP ERROR
} else {
		$res = null;
		
    fputs ($fp, $header . $req);
    
    while (!feof($fp)) {
        $res .= fgets ($fp, 1024);
    }
    fclose ($fp);
    
    $res_lines = explode("\r\n\r\n", $res);
    $res_state = isset($res_lines[1]) ? $res_lines[1] : $res_lines[0];

		if (strcmp ($res_state, "VERIFIED") == 0) {
			if ($payment_status == 'Completed' &&
			    // Unfortunately we can't check this data as we don't have access to it
					/*$item_number == $item_id && 
					($receiver_email == $seller_email || $receiver_email == $primary_email) && */
					$payment_currency == $currency_code)
			{
				$is_valid = true;
			}
		}
		else if (strcmp ($res_state, "INVALID") == 0) {
		    // log for manual investigation
		}
}

if ($is_valid) 
{
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
	Email: ".$email."
	Amount: ". $paid_amount . " " . $payment_currency;
	$headers2 = "From: ".$admineaddr."\r\n";
	$headers2 .= "Return-Path: ".$admineaddr."\r\n";

	mail($admineaddr, $subject2, $message2, $headers2);
}

/* IMPORTANT! This code empties the entire cart! */
unset($_SESSION['cart']);
unset($_SESSION['cart_qty']);
?>
