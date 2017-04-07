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
	$cart = $_SESSION['cart'];
	if ($cart) {
		$cart .= ','.$_POST['id'];
	} else {
		$cart = $_POST['id'];
	}
	$_SESSION['cart'] = $cart;
?>
