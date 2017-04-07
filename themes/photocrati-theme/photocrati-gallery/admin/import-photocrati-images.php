<?php
	ini_set('memory_limit','64M');
	define('ABSPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/');
	include_once(ABSPATH.'wp-config.php');
	include_once(ABSPATH.'wp-load.php');
	include_once(ABSPATH.'wp-includes/wp-db.php');
	global $wpdb;

	if (!current_user_can('upload_files'))
	{
		wp_die('Permission Denied.');
	}
	
	$upload_dir = wp_upload_dir();
	
	define('GALROOT', $upload_dir['basedir'].'/galleries/');
	define('GALPATH', $upload_dir['basedir'].'/galleries/post-'.$_POST['gallery_id'].'/');
	
	if (!file_exists(GALROOT)) {
        photocrati_mkdir(GALROOT); //make folder and directory
	}
	
	if (!file_exists(GALPATH)) {
        photocrati_mkdir(GALPATH); //make folder and directory
	}
	
	if (!file_exists(GALPATH.'thumbnails/')) {
        photocrati_mkdir(GALPATH.'thumbnails/'); //make folder and directory
	}
	
	if (!file_exists(GALPATH.'full/')) {
        photocrati_mkdir(GALPATH.'full/'); //make folder and directory
	}
	
	if (file_exists($upload_dir['basedir'].$_POST['path'].$_POST['image'])) {
		
		$file = $upload_dir['basedir'].$_POST['path'].$_POST['image'];
		$fileth = $upload_dir['basedir'].$_POST['path'].'thumbnails/'.$_POST['image'];
		$filethmed = $upload_dir['basedir'].$_POST['path'].'thumbnails/med-'.$_POST['image'];
		$filefull = $upload_dir['basedir'].$_POST['path'].'full/'.$_POST['image'];
		
	} else {
		
		$file = dirname(dirname(dirname(__FILE__))).$_POST['path'].$_POST['image'];
		$fileth = dirname(dirname(dirname(__FILE__))).$_POST['path'].'thumbnails/'.$_POST['image'];
		$filethmed = dirname(dirname(dirname(__FILE__))).$_POST['path'].'thumbnails/med-'.$_POST['image'];
		$filefull = dirname(dirname(dirname(__FILE__))).$_POST['path'].'full/'.$_POST['image'];
		
	}
	
	$newfile = GALPATH.$_POST['image'];
	$newfileth = GALPATH.'thumbnails/'.$_POST['image'];
	$newfilethmed = GALPATH.'thumbnails/med-'.$_POST['image'];
	$newfilefull = GALPATH.'full/'.$_POST['image'];
	
	if (!copy($file, $newfile)) {
		echo "failed to copy $file";
	}
	
	if (!copy($fileth, $newfileth)) {
		echo "failed to copy $fileth";
	}
	
	if (!copy($filethmed, $newfilethmed)) {
		echo "failed to copy $filethmed";
	}
	
	if (!copy($filefull, $newfilefull)) {
		echo "failed to copy $filefull";
	}
		
	
?>
