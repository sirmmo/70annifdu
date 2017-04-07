<?php

	define('ABSPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/');
	include_once(ABSPATH.'wp-config.php');
	include_once(ABSPATH.'wp-load.php');
	include_once(ABSPATH.'wp-includes/wp-db.php');
	global $wpdb;

	if (!current_user_can('manage_options'))
	{
		wp_die('Permission Denied.');
	}
	
	$SQL = "UPDATE ".$wpdb->prefix."photocrati_gallery_settings SET ";
		$i = 0;
		foreach ($_POST as $key => $value) {
			if ($i != 0) { $comma = ", ";} else { $comma = ""; }
				$SQL = $SQL.$comma.$key."='" . $wpdb->escape(htmlspecialchars($value)) . "'";
			$i = $i + 1;
		}
	$SQL = $SQL." WHERE id = 1";
	
	$wpdb->query($SQL);
					
?>

