<?php
define('ABSPATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/');
define('WP_USE_THEMES', false);
//define('WP_INSTALLING', true);
include_once(ABSPATH.'wp-config.php');
include_once(ABSPATH.'wp-load.php');
include_once(ABSPATH.'wp-includes/wp-db.php');
?>

<?php
	/* IMPORTANT! This code adds items to the cart session! */
	$cart_qty = $_SESSION['cart_qty'];
	if ($cart_qty) {
		$cart_qty .= ','.$_POST['id'].'|'.$_POST['size_id'].'|'.$_POST['qty'].'|'.$_POST['total'];
	} else {
		$cart_qty = $_POST['id'].'|'.$_POST['size_id'].'|'.$_POST['qty'].'|'.$_POST['total'];
	}
	$_SESSION['cart_qty'] = $cart_qty;
?>
