<?php

// These functions control the ecommerce portion of the Photocrati SuperTheme.
// Please do not edit these functions!!

function cp_admin_init()
{
	if (!session_id())
	{
		@session_start();
	}
	
	if (isset($_GET['merchant_return_link']) || isset($_GET['photocrati_return_link']))
	{
		unset($_SESSION['cart']);
		unset($_SESSION['cart_qty']);
	}
}

add_action('init', 'cp_admin_init');

function wp_exist_post_by_title($title_str) {
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."posts WHERE post_title = '" . $title_str . "'", 'ARRAY_A');
}

function photocrati_ecommerce_get_cart_page($read_only = false)
{
	global $wpdb;
	$setting = $wpdb->get_results("SELECT ecomm_title FROM ".$wpdb->prefix."photocrati_ecommerce_settings WHERE id = 1");
	foreach ($setting as $setting) {
		$ecomm_title = $setting->ecomm_title;
	}
	
	if (!isset($ecomm_title) or is_null($ecomm_title))
	{
		$ecomm_title = 'Shopping Cart';
	}
	
	$params = array(
		'post_type' => 'page',
		'meta_key' => '_wp_page_template',
		'meta_value' => 'template-cart.php',
		'post_status' => array('publish', 'pending', 'draft', 'future', 'private', 'inherit', 'trash')
	);
	
	$posts = get_posts($params);
	
	if (($posts == null || count($posts) == 0) && !$read_only) {
		$new_post = array(
		'post_title' => ''.$ecomm_title.'',
		'post_content' => '',
		'post_status' => 'publish',
		'post_date' => date('Y-m-d H:i:s'),
		'post_author' => 1,
		'menu_order' => 999,
		'post_type' => 'page'
		);
		$post_id = wp_insert_post($new_post);
		
		if($post_id) {
			update_post_meta($post_id, '_wp_page_template',  'template-cart.php');
		}
		
		$posts = get_posts($params);
	}
	
	if (count($posts) > 0) {
		return $posts[0];
	}
	
	return null;
}

function add_cart_page() {

	$cart_page = photocrati_ecommerce_get_cart_page();
}

add_action('init', 'add_cart_page');

// Add an admin notice for the Shopping Cart page saying it's required by ecommerce
function photocrati_ecommerce_admin_notice()
{
	$screen = get_current_screen();
	
	if ($screen->parent_base == 'edit' && $screen->id == 'page')
	{
		if (isset($_GET['post']))
		{
			$post_id = $_GET['post'];
			$post = get_post($post_id);
			
			if ($post != null)
			{
				if (get_post_meta($post_id, '_wp_page_template', true) == 'template-cart.php')
				{
?>
  <div class="error">
      <p style="font-size: 140%;"><?php echo '<strong>' . __('Note', 'photocrati-framework') . ': ' . __('This page is required by the ecommerce functionality and will be created automatically if deleted. If you want to hide it from your site and you don\'t use ecommerce, then simply set the page Status to "Draft".', 'photocrati-framework') . '</strong>'; ?></p>
  </div>
<?php
				}
			}
		}
	}
}
add_action('admin_notices', 'photocrati_ecommerce_admin_notice' );

function writeShoppingCart() {

	global $wpdb;
	$settings = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_ecommerce_settings WHERE id = 1", ARRAY_A);
	foreach ($settings as $key => $value) {
		$$key = $value;
	}
	
	$cart_page = photocrati_ecommerce_get_cart_page();
	$cart_page_link = get_permalink($cart_page->ID);
	
	$cart = $_SESSION['cart'];
	if (!$cart) {
		return '<p><em>'.$ecomm_empty.'</em> </p>';
	} else {
		// Parse the cart session variable
		$items = explode(',',$cart);
		$s = (count($items) > 1) ? 's':'';
		
		return '<button id="addto2" class="positive" style="margin:0 5px;" onClick="window.location.href = \'' . esc_js($cart_page_link) . '\'"><img src="' . photocrati_gallery_file_uri('image/cart.png') . '"> '.$ecomm_title.': '.count($items).' item'.$s.'</button>';
	}
}

?>
