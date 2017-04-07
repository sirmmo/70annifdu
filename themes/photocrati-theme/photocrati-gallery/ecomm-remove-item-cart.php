<?php
define('ABSPATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/');
define('WP_USE_THEMES', false);
//define('WP_INSTALLING', true);
include_once(ABSPATH.'wp-config.php');
include_once(ABSPATH.'wp-load.php');
include_once(ABSPATH.'wp-includes/wp-db.php');
?>

<?php
	/* IMPORTANT! This code removes an item from the cart! */
	$cart = $_SESSION['cart'];

	if ($cart) {
		$items = explode(',',$cart);
		$newcart = '';
		foreach ($items as $item) {
			if ($_POST['remove_id'] != $item) {
				if ($newcart != '') {
					$newcart .= ','.$item;
				} else {
					$newcart = $item;
				}
			}
		}
		$cart = $newcart;
	}
	
	$_SESSION['cart'] = $cart;
	
	
	$cart_qty = $_SESSION['cart_qty'];
	
	if ($cart_qty) {
		$items_qty = explode(',',$cart_qty);
		$newcart_qty = '';
		foreach ($items_qty as $item) {
			$item_id = explode('|',$item);
			if ($_POST['remove_id'] != $item_id[0]) {
				if ($newcart_qty != '') {
					$newcart_qty .= ','.$item;
				} else {
					$newcart_qty = $item;
				}
			}
		}
		$cart_qty = $newcart_qty;
	}
	
	$_SESSION['cart_qty'] = $cart_qty;
	
?>
