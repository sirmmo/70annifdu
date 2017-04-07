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
		
	$SQL2 = "INSERT INTO ".$wpdb->prefix."photocrati_albums (album_id,gallery_id,album_order) VALUES (";
		$i = 0;
		foreach ($_POST as $key => $value) {
			if ($i != 0) { $comma = ", ";} else { $comma = ""; }
				if($key == 'album_order') {
				$SQL2 = $SQL2.$comma."" . ((int)$value) . "";	
				} else {
				$SQL2 = $SQL2.$comma."'" . $wpdb->escape($value) . "'";
				}
			$i = $i + 1;
		}
	$SQL2 = $SQL2.")";
			
	$wpdb->query($SQL2);				
						
?>
