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
	
	$SQL = "UPDATE ".$wpdb->prefix."photocrati_gallery_ids SET gal_type = " . ((int)$_POST['gal_type']) . ", gal_title = '" . $wpdb->escape(htmlspecialchars($_POST['gal_title'])) . "', gal_desc = '" . $wpdb->escape(htmlspecialchars($_POST['gal_desc'])) . "', gal_height = '" . $wpdb->escape($_POST['gal_height']) . "', gal_aspect_ratio = '" . $wpdb->escape($_POST['gal_aspect_ratio']) . "' WHERE gallery_id = '" . $wpdb->escape($_POST['gallery_id']) . "'";
	$wpdb->query($SQL);
	
?>
