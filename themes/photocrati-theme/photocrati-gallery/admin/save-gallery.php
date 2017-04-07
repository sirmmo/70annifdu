<?php

	define('ABSPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/');
	include_once(ABSPATH.'wp-config.php');
	include_once(ABSPATH.'wp-load.php');
	include_once(ABSPATH.'wp-includes/wp-db.php');
	global $wpdb;
	
	if (!current_user_can('edit_pages') && !current_user_can('edit_posts'))
	{
		wp_die('Permission Denied.');
	}
	
	$SQL3 = "INSERT INTO ".$wpdb->prefix."photocrati_gallery_ids (gallery_id, post_id, gal_title, gal_desc, gal_type, gal_height, gal_aspect_ratio) VALUES ('" .$wpdb->escape($_POST['gallery_id']) . "', " . ((int)$_POST['post_id']) . ", '" . $wpdb->escape(htmlspecialchars($_POST['gal_title'])) . "', '" . $wpdb->escape(htmlspecialchars($_POST['gal_desc'])) . "', " . ((int)$_POST['gal_type']) . ", '" . $wpdb->escape($_POST['gal_height']) . "', '" . $wpdb->escape($_POST['gal_aspect_ratio']) . "')";
	$wpdb->query($SQL3);
					
?>
