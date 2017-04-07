<?php
/*
Uploadify v2.0.3
Release Date: August 3, 2009

Copyright (c) 2009 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
/*if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = dirname(dirname(dirname(__FILE__))) . $_REQUEST['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
	
	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);
	
	// if (in_array($fileParts['extension'],$typesArray)) {
		// Uncomment the following line if you want to make the directory if it doesn't exist
		// mkdir(str_replace('//','/',$targetPath), 0755, true);
		
		move_uploaded_file($tempFile,$targetFile);
		echo "1";
	// } else {
	// 	echo 'Invalid file type.';
	// }
}*/

// Report no errors
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
ini_set('display_errors', 'Off');
error_reporting(0);

// Uploadify uses Flash to send files and Flash does not send cookies correctly
if (isset($_POST['cookie']))
{
	$cookie = $_POST['cookie'];
	$cookie_list = explode(';', $cookie);
	
	foreach ($cookie_list as $cookie)
	{
		$cookie_parts = explode('=', $cookie);
		
		if (count($cookie_parts) > 1)
		{
			$_COOKIE[urldecode($cookie_parts[0])] = urldecode($cookie_parts[1]);
		}
	}
}

if (isset($_POST['session_id']))
{
	session_id($_POST['session_id']);
}

define('ABSPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/');
include_once(ABSPATH.'wp-config.php');
include_once(ABSPATH.'wp-load.php');
include_once(ABSPATH.'wp-includes/wp-db.php');
include_once(ABSPATH.'wp-includes/pluggable.php');
global $wpdb;

// Report no errors
ini_set('display_errors', 'Off');
error_reporting(0);

if (!current_user_can('upload_files'))
{
	wp_die('Permission Denied.');
}
	
$ext = explode ('.', $_FILES['Filedata']['name']);
$ext = $ext[count($ext)-1];
	
if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'gif' || $ext == 'GIF' || $ext == 'png' || $ext == 'PNG' || $ext == 'tiff' || $ext == 'TIFF') {
	
	$upload_dir = wp_upload_dir();
	
	if (!empty($_FILES)) {
		ini_set('memory_limit','256M');
		$folder_dir = $_REQUEST['folder'];
		$full_dir =  $upload_dir['basedir'] . $folder_dir;
		if (file_exists($upload_dir['basedir'] . '/galleries/') && file_exists($upload_dir['basedir'] . $folder_dir) && file_exists($upload_dir['basedir'] . $folder_dir . 'thumbnails/') && file_exists($upload_dir['basedir'] . $folder_dir . 'full/')) {
		
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $upload_dir['basedir'] . $_REQUEST['folder'] . '/';
			$filename = $_FILES['Filedata']['name'];
			$targetFile =  str_replace('//','/',$targetPath.'full/') . $filename;
		   
			//Avoid files Overwrite
			while(file_exists($targetFile)){
				$user = "c".rand(0,100);
				$file_parts = explode('.', $filename);
				$file_parts[0] .= '-' . $user;
				$filename = implode('.', $file_parts);
				$targetFile =  str_replace('//','/',$targetPath.'full/') . $filename;
			}
			
			$title = $filename;
			$caption = '';
			
			$size = GetImageSize ($tempFile,$info); 
			//$iptc = iptcparse ($info["APP13"]);
			if (isset($info["APP13"])) 
			{
				$iptc = iptcparse($info["APP13"]);
				
				if (is_array($iptc)) 
				{
					if(isset($iptc["2#105"][0]) && $iptc["2#105"][0] <> '')
					{
						$title = $iptc["2#105"][0];
					}
					else if(isset($iptc["2#005"][0]) && $iptc["2#005"][0] <> '')
					{
						$title = $iptc["2#105"][0];
					}
					else
					{
						$title = $filename;
					}
					
					if(isset($iptc["2#120"][0]) && $iptc["2#120"][0] <> '')
					{
						$caption = $iptc["2#120"][0];
					}
				}
			}
			
			move_uploaded_file($tempFile,$targetFile);
			if(function_exists('gd_info')) {		
			
				// Create Thumbnail Image	
				$imgsize = getimagesize($targetFile);
				switch(strtolower(substr($targetFile, -4)))
				{
				case ".jpg":
				$image = imagecreatefromjpeg($targetFile);
				break;
				case "jpeg":
				$image = imagecreatefromjpeg($targetFile);
				break;
				case ".png":
				$image = imagecreatefrompng($targetFile);
				break;
				case ".gif":
				$image = imagecreatefromgif($targetFile);
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
				switch(strtolower(substr($targetFile, -4)))
				{
				case ".jpg":
				//header("Content-Type: image/jpeg");
				$bool2 = imagejpeg($picture,$targetPath.$filename,80);
				break;
				case "jpeg":
				//header("Content-Type: image/jpeg");
				$bool2 = imagejpeg($picture,$targetPath.$filename,80);
				break;
				case ".png":
				//header("Content-Type: image/png");
				imagepng($picture,$targetPath.$filename);
				break;
				case ".gif":
				//header("Content-Type: image/gif");
				imagegif($picture,$targetPath.$filename);
				break;
				}
				}
				
				imagedestroy($picture);
				imagedestroy($image);
				
			
			}
			echo $filename.'|'.$title.'|'.$caption;
		
		} else {
			if (!file_exists($upload_dir['basedir'] . '/galleries/')) {
				photocrati_mkdir($upload_dir['basedir'] . '/galleries/');
			}
			if (!file_exists($upload_dir['basedir'] . $folder_dir)) {
				photocrati_mkdir($upload_dir['basedir'] . $folder_dir, $perm);
			}
			if (!file_exists($upload_dir['basedir'] . $folder_dir . 'thumbnails/')) {
				photocrati_mkdir($upload_dir['basedir'] . $folder_dir . 'thumbnails/', $perm);
			}
			if (!file_exists($upload_dir['basedir'] . $folder_dir . 'full/')) {
				photocrati_mkdir($upload_dir['basedir'] . $folder_dir . 'full/', $perm);
			}
		   
		   
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $upload_dir['basedir'] . $_REQUEST['folder'] . '/';
			$filename = $_FILES['Filedata']['name'];
			$targetFile =  str_replace('//','/',$targetPath.'full/') . $filename;
		   
			//Avoid files Overwrite
			while(file_exists($targetFile)){
				$user = "c".rand(0,100);
				$file_parts = explode('.', $filename);
				$file_parts[0] .= '-' . $user;
				$filename = implode('.', $file_parts);
				$targetFile =  str_replace('//','/',$targetPath.'full/') . $filename;
			} 
			
			$title = $filename;
			$caption = '';
			
			$size = GetImageSize ($tempFile,$info); 
			//$iptc = iptcparse ($info["APP13"]); 
			if (isset($info["APP13"])) 
			{
				$iptc = iptcparse($info["APP13"]);
				
				if (is_array($iptc)) 
				{
					if(isset($iptc["2#105"][0]) && $iptc["2#105"][0] <> '')
					{
						$title = $iptc["2#105"][0];
					}
					else if(isset($iptc["2#005"][0]) && $iptc["2#005"][0] <> '')
					{
						$title = $iptc["2#105"][0];
					}
					else
					{
						$title = $filename;
					}
					
					if(isset($iptc["2#120"][0]) && $iptc["2#120"][0] <> '')
					{
						$caption = $iptc["2#120"][0];
					}
				}
			}
			
			move_uploaded_file($tempFile,$targetFile);
			if(function_exists('gd_info')) {		
			
				// Create Thumbnail Image	
				$imgsize = getimagesize($targetFile);
				switch(strtolower(substr($targetFile, -4)))
				{
				case ".jpg":
				$image = imagecreatefromjpeg($targetFile);
				break;
				case "jpeg":
				$image = imagecreatefromjpeg($targetFile);
				break;
				case ".png":
				$image = imagecreatefrompng($targetFile);
				break;
				case ".gif":
				$image = imagecreatefromgif($targetFile);
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
				switch(strtolower(substr($targetFile, -4)))
				{
				case ".jpg":
				//header("Content-Type: image/jpeg");
				$bool2 = imagejpeg($picture,$targetPath.$filename,80);
				break;
				case "jpeg":
				//header("Content-Type: image/jpeg");
				$bool2 = imagejpeg($picture,$targetPath.$filename,80);
				break;
				case ".png":
				//header("Content-Type: image/png");
				imagepng($picture,$targetPath.$filename);
				break;
				case ".gif":
				//header("Content-Type: image/gif");
				imagegif($picture,$targetPath.$filename);
				break;
				}
				}
				
				imagedestroy($picture);
				imagedestroy($image);
				
			
			}
			echo $filename.'|'.$title.'|'.$caption;
		}
	}
	
	if(function_exists('gd_info')) {
			
		// Create Thumbnail Image	
		$imgsize = getimagesize($targetFile);
		switch(strtolower(substr($targetFile, -4)))
		{
		case ".jpg":
		$image = imagecreatefromjpeg($targetFile);
		break;
		case "jpeg":
		$image = imagecreatefromjpeg($targetFile);
		break;
		case ".png":
		$image = imagecreatefrompng($targetFile);
		break;
		case ".gif":
		$image = imagecreatefromgif($targetFile);
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
		switch(strtolower(substr($targetFile, -4)))
		{
		case ".jpg":
		//header("Content-Type: image/jpeg");
		$bool2 = imagejpeg($picture,$targetPath."thumbnails/".$filename,80);
		break;
		case "jpeg":
		//header("Content-Type: image/jpeg");
		$bool2 = imagejpeg($picture,$targetPath."thumbnails/".$filename,80);
		break;
		case ".png":
		//header("Content-Type: image/png");
		imagepng($picture,$targetPath."thumbnails/".$filename);
		break;
		case ".gif":
		//header("Content-Type: image/gif");
		imagegif($picture,$targetPath."thumbnails/".$filename);
		break;
		}
		}
		
		imagedestroy($picture);
		imagedestroy($image);
	
	
		// Create Medium Sized Image
		$imgsize2 = getimagesize($targetFile);
		switch(strtolower(substr($targetFile, -4)))
		{
		case ".jpg":
		$image2 = imagecreatefromjpeg($targetFile);
		break;
		case "jpeg":
		$image2 = imagecreatefromjpeg($targetFile);
		break;
		case ".png":
		$image2 = imagecreatefrompng($targetFile);
		break;
		case ".gif":
		$image2 = imagecreatefromgif($targetFile);
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
		switch(strtolower(substr($targetFile, -4)))
		{
		case ".jpg":
		//header("Content-Type: image/jpeg");
		$bool4 = imagejpeg($picture2,$targetPath."thumbnails/med-".$filename,80);
		break;
		case "jpeg":
		//header("Content-Type: image/jpeg");
		$bool4 = imagejpeg($picture2,$targetPath."thumbnails/med-".$filename,80);
		break;
		case ".png":
		//header("Content-Type: image/png");
		imagepng($picture2,$targetPath."thumbnails/med-".$filename);
		break;
		case ".gif":
		//header("Content-Type: image/gif");
		imagegif($picture2,$targetPath."thumbnails/med-".$filename);
		break;
		}
		}
		
		imagedestroy($picture2);
		imagedestroy($image2);
		
	
	}
	//echo '1';
	
}

?>
