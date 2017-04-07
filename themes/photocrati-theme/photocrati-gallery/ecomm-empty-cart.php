<?php
define('ABSPATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/');
define('WP_USE_THEMES', false);
//define('WP_INSTALLING', true);
include_once(ABSPATH.'wp-config.php');
include_once(ABSPATH.'wp-load.php');
include_once(ABSPATH.'wp-includes/wp-db.php');
?>

<?php
	/* IMPORTANT! This code empties the entire cart! */
	unset($_SESSION['cart']);
	unset($_SESSION['cart_qty']);
?>
