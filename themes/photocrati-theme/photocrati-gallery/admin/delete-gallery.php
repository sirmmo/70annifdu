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
	
	$upload_dir = wp_upload_dir();
	
	define('GALPATH', $upload_dir['basedir'].'/galleries/post-'.$_POST['post_id'].'/');
	$gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '" . $wpdb->escape($_POST['gallery_id']) . "' ORDER BY image_name ASC");
	foreach ($gallery as $gallery) {
	
		unlink(GALPATH.$gallery->image_name);
		unlink(GALPATH.'full/'.$gallery->image_name);
		unlink(GALPATH.'thumbnails/'.$gallery->image_name);
		unlink(GALPATH.'thumbnails/med-'.$gallery->image_name);
	
	}
	
	$SQL = "DELETE FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '" . $wpdb->escape($_POST['gallery_id']) . "'";	
	$wpdb->query($SQL);
	
	$SQL2 = "DELETE FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE gallery_id = '" . $wpdb->escape($_POST['gallery_id']) . "'";	
	$wpdb->query($SQL2);
		
	
?>
