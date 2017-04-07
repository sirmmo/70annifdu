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

if (!current_user_can('upload_files'))
{
	wp_die('Permission Denied.');
}

$ext = explode ('.', $_FILES['Filedata']['name']);
$ext = $ext[count($ext)-1];
	
if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'gif' || $ext == 'GIF' || $ext == 'png' || $ext == 'PNG' || $ext == 'tiff' || $ext == 'TIFF') {
	
	if (!empty($_FILES)) {  
		$folder_dir = $_REQUEST['folder'];
		$full_dir =  dirname(dirname(dirname(__FILE__))) . $folder_dir;
		if (file_exists(dirname(dirname(dirname(__FILE__))) . $folder_dir)) {
		
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = dirname(dirname(dirname(__FILE__))) . $_REQUEST['folder'] . '/';
			$filename = $_FILES['Filedata']['name'];
			$targetFile =  str_replace('//','/',$targetPath) . $filename;
		   
			//Avoid files Overwrite
			while(file_exists($targetFile)){
				$user = "c".rand(0,100);
				$filename = $user."-". $_FILES['Filedata']['name'];
				$targetFile =  str_replace('//','/',$targetPath) . $filename;
			} 
					
			move_uploaded_file($tempFile,$targetFile);
			//echo $_FILES['Filedata']['name'];
		   echo $filename;
		
		} else {
           photocrati_mkdir(dirname(dirname(dirname(__FILE__))) . $folder_dir);
		   //$tempFile = $_FILES['Filedata']['tmp_name'];
		   //$targetPath = dirname(dirname(dirname(__FILE__))) . $folder_dir . '/';
		   
		   
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = dirname(dirname(dirname(__FILE__))) . $_REQUEST['folder'] . '/';
			$filename = $_FILES['Filedata']['name'];
			$targetFile =  str_replace('//','/',$targetPath) . $filename;
		   
			//Avoid files Overwrite
			while(file_exists($targetFile)){
				$user = "c".rand(0,100);
				$filename = $user."-". $_FILES['Filedata']['name'];
				$targetFile =  str_replace('//','/',$targetPath) . $filename;
			}  
				   
			move_uploaded_file($tempFile,$targetFile);
			//echo $_FILES['Filedata']['name'];
		   echo $filename;
		}
	}
	
}

?>
