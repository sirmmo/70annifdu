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
	define('GALPATHTH', $upload_dir['basedir'].'/galleries/post-'.$_POST['gallery_id'].'/thumbnails/');
	define('GALPATHFULL', $upload_dir['basedir'].'/galleries/post-'.$_POST['gallery_id'].'/full/');
	
	$file = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).$_POST['path'].$_POST['image'];
	$newfile = GALPATHFULL.$_POST['image'];
	$newfileth = GALPATHTH.$_POST['image'];
	$newfilemed = GALPATHTH.'med-'.$_POST['image'];
	$newfilemedmain = GALPATH.$_POST['image'];
	
	if (!file_exists(GALROOT)) {
		photocrati_mkdir(GALROOT); //make folder and directory
	}
	
	if (!file_exists(GALPATH)) {
        photocrati_mkdir(GALPATH); //make folder and directory
	}
	
	if (!file_exists(GALPATHTH)) {
        photocrati_mkdir(GALPATHTH); //make folder and directory
	}
	
	if (!file_exists(GALPATHFULL)) {
        photocrati_mkdir(GALPATHFULL); //make folder and directory
	}
	
	if (!copy($file, $newfile)) {
		echo "failed to copy $file";
	}
	
	if(function_exists('gd_info')) {
			
		$imgsize = getimagesize($file);
		switch(strtolower(substr($file, -3)))
		{
		case "jpg":
		$image = imagecreatefromjpeg($file);
		break;
		case "png":
		$image = imagecreatefrompng($file);
		break;
		case "gif":
		$image = imagecreatefromgif($file);
		break;
		default:
		exit;
		break;
		}
		
		$width = 300; //New width of image
		$height = $imgsize[1]/$imgsize[0]*$width; //This maintains proportions
		
		$src_w = $imgsize[0];
		$src_h = $imgsize[1];
		
		$picture = imagecreatetruecolor($width, $height);
		imagealphablending($picture, false);
		imagesavealpha($picture, true);
		$bool = imagecopyresampled($picture, $image, 0, 0, 0, 0, $width, $height, $src_w, $src_h);
		
		if($bool)
		{
		switch(strtolower(substr($file, -3)))
		{
		case "jpg":
		//header("Content-Type: image/jpeg");
		$bool2 = imagejpeg($picture,$newfileth,80);
		break;
		case "png":
		//header("Content-Type: image/png");
		imagepng($picture,$newfileth);
		break;
		case "gif":
		//header("Content-Type: image/gif");
		imagegif($picture,$newfileth);
		break;
		}
		}
		
		imagedestroy($picture);
		imagedestroy($image);
	
	
		// Create Medium Sized Thumb
		$imgsize2 = getimagesize($file);
		switch(strtolower(substr($file, -3)))
		{
		case "jpg":
		$image2 = imagecreatefromjpeg($file);
		break;
		case "png":
		$image2 = imagecreatefrompng($file);
		break;
		case "gif":
		$image2 = imagecreatefromgif($file);
		break;
		default:
		exit;
		break;
		}
		
		$src_w2 = $imgsize[0];
		$src_h2 = $imgsize[1];
		
		if($src_w2 > 1200) {
		$width2 = 1200; //New width of image
		$height2 = $imgsize[1]/$imgsize[0]*$width2; //This maintains proportions
		} else {
		$width2 = $src_w; //Keep same width of image
		$height2 = $src_h2; //Keep the same height
		}
		
		$picture2 = imagecreatetruecolor($width2, $height2);
		imagealphablending($picture2, false);
		imagesavealpha($picture2, true);
		$bool3 = imagecopyresampled($picture2, $image2, 0, 0, 0, 0, $width2, $height2, $src_w2, $src_h2);
		
		if($bool3)
		{
		switch(strtolower(substr($file, -3)))
		{
		case "jpg":
		//header("Content-Type: image/jpeg");
		$bool4 = imagejpeg($picture2,$newfilemed,80);
		break;
		case "png":
		//header("Content-Type: image/png");
		imagepng($picture2,$newfilemed);
		break;
		case "gif":
		//header("Content-Type: image/gif");
		imagegif($picture2,$newfilemed);
		break;
		}
		}
		
		imagedestroy($picture2);
		imagedestroy($image2);
	
	
		// Create Medium Sized Image
		$imgsize = getimagesize($file);
		switch(strtolower(substr($file, -3)))
		{
		case "jpg":
		$image = imagecreatefromjpeg($file);
		break;
		case "png":
		$image = imagecreatefrompng($file);
		break;
		case "gif":
		$image = imagecreatefromgif($file);
		break;
		default:
		exit;
		break;
		}
		
		$src_w = $imgsize[0];
		$src_h = $imgsize[1];
			
		if($src_w > 1200) {
		$width = 1200; //New width of image
		$height = $imgsize[1]/$imgsize[0]*$width; //This maintains proportions
		} else {
		$width = $src_w; //Keep same width of image
		$height = $src_h; //Keep the same height
		}
		
		$picture = imagecreatetruecolor($width, $height);
		imagealphablending($picture, false);
		imagesavealpha($picture, true);
		$bool = imagecopyresampled($picture, $image, 0, 0, 0, 0, $width, $height, $src_w, $src_h);
		
		if($bool)
		{
		switch(strtolower(substr($file, -3)))
		{
		case "jpg":
		//header("Content-Type: image/jpeg");
		$bool2 = imagejpeg($picture,$newfilemedmain,80);
		break;
		case "png":
		//header("Content-Type: image/png");
		imagepng($picture,$newfilemedmain);
		break;
		case "gif":
		//header("Content-Type: image/gif");
		imagegif($picture,$newfilemedmain);
		break;
		}
		}
		
		imagedestroy($picture);
		imagedestroy($image);
	
	
	} else {
		
		if (!copy($file, $newfileth)) {
			echo "failed to copy $file";
		}
		
	}
	
?>
