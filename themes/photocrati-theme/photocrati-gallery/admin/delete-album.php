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
	
	define('GALPATH', dirname(dirname(dirname(__FILE__))).'/galleries/post-'.$_POST['post_id'].'/');
	$gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_albums WHERE album_id = '" . $wpdb->escape($_POST['gallery_id']) . "' ORDER BY image_name ASC");
	
	$SQL = "DELETE FROM ".$wpdb->prefix."photocrati_albums WHERE album_id = '" . $wpdb->escape($_POST['gallery_id']) . "'";	
	$wpdb->query($SQL);
	
	$SQL2 = "DELETE FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE gallery_id = '" . $wpdb->escape($_POST['gallery_id']) . "'";	
	$wpdb->query($SQL2);
		
	
?>
