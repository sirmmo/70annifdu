<?php

define('PHOTOCRATI_GALLERY_VERSION', 10000000);
define('PHOTOCRATI_GALLERY_VERSION_STRING', '1.0');


function photocrati_gallery_version()
{
	return PHOTOCRATI_GALLERY_VERSION;
}

function photocrati_gallery_version_string()
{
	return PHOTOCRATI_GALLERY_VERSION_STRING;
}

function photocrati_gallery_file_path($file_name = null)
{
	$path = implode(DIRECTORY_SEPARATOR, array(
		untrailingslashit(get_template_directory()),
		'photocrati-gallery',
		$file_name
	));

	return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
}

function photocrati_gallery_path_uri($path = null, $url_encode = false)
{
	$theme_dir = strtolower(get_template_directory());
	$theme_dir = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $theme_dir);
	$gallery_dir = strtolower(photocrati_gallery_file_path());
	$common_dir = substr($gallery_dir, 0, strlen($theme_dir));
	$uri = null;
	
	$path = str_replace(array('/', '\\'), '/', $path);
	
	if ($url_encode)
	{
		$path_list = explode('/', $path);
		
		foreach ($path_list as $index => $path_item)
		{
			$path_list[$index] = urlencode($path_item);
		}
		
		$path = implode('/', $path_list);
	}
	
	if ($common_dir == $theme_dir)
	{
		$theme_uri = get_template_directory_uri();
		
		$uri = $theme_uri . '/photocrati-gallery/' . $path;
	}
	else
	{
		// XXX complete for plugin
		// Note, paths could not match but STILL being contained in the theme (i.e. WordPress returns the wrong path for the theme directory, either with wrong formatting or wrong encoding)
	}
	
	return $uri;
}

// XXX only returns 'basedir' and 'baseurl'
function photocrati_gallery_wp_upload_dir()
{
	$upload_location = wp_upload_dir();
	
	if (!isset($upload_location['basedir']) || !isset($upload_location['baseurl']))
	{
		global $switched;
		$siteurl = get_option( 'siteurl' );
		$upload_path = get_option( 'upload_path' );
		$upload_path = trim($upload_path);
		$main_override = is_multisite() && defined( 'MULTISITE' ) && is_main_site();
		
		if ( empty($upload_path) ) {
			$dir = WP_CONTENT_DIR . '/uploads';
		} else {
			$dir = $upload_path;
			if ( 'wp-content/uploads' == $upload_path ) {
				$dir = WP_CONTENT_DIR . '/uploads';
			} elseif ( 0 !== strpos($dir, ABSPATH) ) {
				// $dir is absolute, $upload_path is (maybe) relative to ABSPATH
				$dir = path_join( ABSPATH, $dir );
			}
		}

		if ( !$url = get_option( 'upload_url_path' ) ) {
			if ( empty($upload_path) || ( 'wp-content/uploads' == $upload_path ) || ( $upload_path == $dir ) )
				$url = WP_CONTENT_URL . '/uploads';
			else
				$url = trailingslashit( $siteurl ) . $upload_path;
		}

		if ( defined('UPLOADS') && !$main_override && ( !isset( $switched ) || $switched === false ) ) {
			$dir = ABSPATH . UPLOADS;
			$url = trailingslashit( $siteurl ) . UPLOADS;
		}

		if ( is_multisite() && !$main_override && ( !isset( $switched ) || $switched === false ) ) {
			if ( defined( 'BLOGUPLOADDIR' ) )
				$dir = untrailingslashit(BLOGUPLOADDIR);
			$url = str_replace( UPLOADS, 'files', $url );
		}

		$bdir = $dir;
		$burl = $url;
		
		if (!isset($upload_location['basedir'])) {
			$upload_location['basedir'] = $bdir;
		}
		
		if (!isset($upload_location['baseurl'])) {
			$upload_location['baseurl'] = $burl;
		}
	}
	
	return $upload_location;
}

function photocrati_gallery_file_uri($file_name = null)
{
	return photocrati_gallery_path_uri($file_name);
}

function photocrati_gallery_content_path($path, $old_location = false)
{
	$upload_location = photocrati_gallery_wp_upload_dir();
	$upload_dir = $upload_location['basedir'];
	
	if ($old_location)
	{
		$upload_dir = get_template_directory();
	}
	
	$path = $upload_dir . '/galleries/' . $path;
	
	return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
}

function photocrati_gallery_content_uri($path, $old_location = false)
{
	$upload_location = photocrati_gallery_wp_upload_dir();
	$upload_uri = $upload_location['baseurl'];
	
	if ($old_location)
	{
		$upload_uri = get_template_directory_uri();
	}
	
	$path = $upload_uri . '/galleries/' . $path;
	
	return str_replace(array('/', '\\'), '/', $path);
}

function photocrati_gallery_image_location($image_name, $post_id, $image_type = null)
{
	$location = 'post-' . $post_id . '/';
	
	switch ($image_type)
	{
		case 'full':
		{
			$location .= 'full/';
			
			break;
		}
		case 'thumbnail':
		{
			$location .= 'thumbnails/';
			
			break;
		}
		case 'thumbnail-med':
		{
			$location .= 'thumbnails/med-';
			
			break;
		}
	}
	
	return $location . $image_name;
}

function photocrati_gallery_image_path($image_name, $post_id, $image_type = null, $old_location = false)
{
	return photocrati_gallery_content_path(photocrati_gallery_image_location($image_name, $post_id, $image_type), $old_location);
}

function photocrati_gallery_image_uri($image_name, $post_id, $image_type = null, $old_location = false)
{
	return photocrati_gallery_content_uri(photocrati_gallery_image_location($image_name, $post_id, $image_type), $old_location);
}

function photocrati_gallery_option_list_get()
{
	global $wpdb;

	return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1", ARRAY_A);
}

function photocrati_gallery_type_name($type_id)
{
	$gallery_type_list = array('none', 'slideshow', 'blog', 'filmstrip', 'thumbnail');
	
	$type_id = (int) $type_id;
	
	if (isset($gallery_type_list[$type_id]))
	{
		return $gallery_type_list[$type_id];
	}
	
	return 'none';
}

function photocrati_gallery_aspect_ratio_list()
{
	$aspect_ratio_list = array('1.5' => '3:2', '1.333' => '4:3', '1.777' => '16:9', '1.6' => '16:10', '1.85' => '1.85:1', '2.39' => '2.39:1', '1.81' => '1.81:1', '1' => '1:1 (Square)');
	
	foreach ($aspect_ratio_list as $aspect_value => $aspect_label)
	{
		$aspect_label .= ' [' . $aspect_value . ']';
		
		$aspect_ratio_list[$aspect_value] = $aspect_label;
	}
	
	return $aspect_ratio_list;
}

function photocrati_gallery_info_get($gallery_id)
{
	global $wpdb;

	$gallery_info = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE gallery_id = '" . $wpdb->escape($gallery_id) . "'");
	$count = count($gallery_info);
	
	if ($count > 0)
	{
		if ($count > 1)
		{
			// XXX should never happen
		}
		
		return $gallery_info[$count - 1];
	}
	
	return null;
}

function photocrati_gallery_instance_get($gallery_id)
{
	global $wpdb;

	$gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '" . $wpdb->escape($gallery_id) . "' ORDER BY image_order,image_name ASC");
	
	return $gallery;
}

class PhotocratiGalleryImage
{
	public $Name;
	public $NameHTML;
	public $GalleryID;
	public $Path;
	public $URI;
	public $ThumbPath;
	public $ThumbURI;
	public $Title;
	public $TitleHTML;
	public $Description;
	public $DescriptionHTML;
}

function photocrati_gallery_instance_list($gallery, $options)
{
	if ($gallery == null)
	{
		return null;
	}
	
	// Get gallery options
	$gallery_type = isset($options['gallery_type']) ? $options['gallery_type'] : null;
	$image_resolution = isset($options['image_resolution']) ? $options['image_resolution'] : null;
	
	$count = count($gallery);
	$instance_list = array();
	
	for ($i = 0; $i < $count; $i++)
	{
		$instance_image = new PhotocratiGalleryImage();
		$gallery_image = $gallery[$i];
		$image_name = $gallery_image->image_name;
		$image_post = $gallery_image->post_id;
		// Note, I'm not sure why the original code was doing these conversions, possibly because it didn't correctly URL-decode the strings passed in as filenames for the images - keeping it for backward compatibility
		$image_name_legacy = str_replace('&amp;', '&', $image_name);
		$image_name_legacy_uri = str_replace('%', '%25', $image_name_legacy);
		
		$image_name = htmlspecialchars_decode($image_name);
		$instance_image->Name = $image_name;
		$instance_image->NameHTML = htmlspecialchars($image_name);
		
		$instance_image->GalleryID = $gallery_image->gallery_id;
		
		if($image_resolution == '1')
		{
			$image_path = photocrati_gallery_image_path($image_name_legacy, $image_post);
			$image_uri = photocrati_gallery_image_uri($image_name_legacy_uri, $image_post);
		
			$image_path_old = photocrati_gallery_image_path($image_name_legacy, $image_post, null, true);
			$image_uri_old = photocrati_gallery_image_uri($image_name_legacy_uri, $image_post, null, true);
		}
		else
		{
			$image_path = photocrati_gallery_image_path($image_name_legacy, $image_post, 'full');
			$image_uri = photocrati_gallery_image_uri($image_name_legacy_uri, $image_post, 'full');
		
			$image_path_old = photocrati_gallery_image_path($image_name_legacy, $image_post, 'full', true);
			$image_uri_old = photocrati_gallery_image_uri($image_name_legacy_uri, $image_post, 'full', true);
		}
	
		if (file_exists($image_path))
		{
			$instance_image->Path = $image_path;
			$instance_image->URI = $image_uri;
		} 
		else if (file_exists($image_path_old))
		{
			$instance_image->Path = $image_path_old;
			$instance_image->URI = $image_uri_old;
		}
		
		// XXX next check is probably not needed because of file_exists check below, but leaving it for legacy reasons
		if (function_exists('gd_info'))
		{
			if ($gallery_type == 'filmstrip' || $gallery_type == 'thumbnail')
			{
				$image_path = photocrati_gallery_image_path($image_name_legacy, $image_post, 'thumbnail');
				$image_uri = photocrati_gallery_image_uri($image_name_legacy_uri, $image_post, 'thumbnail');
		
				$image_path_old = photocrati_gallery_image_path($image_name_legacy, $image_post, 'thumbnail', true);
				$image_uri_old = photocrati_gallery_image_uri($image_name_legacy_uri, $image_post, 'thumbnail', true);
			}
			else if ($gallery_type == 'blog' && $image_resolution == 'slideshow')
			{
				$image_path = photocrati_gallery_image_path($image_name_legacy, $image_post, 'thumbnail-med');
				$image_uri = photocrati_gallery_image_uri($image_name_legacy_uri, $image_post, 'thumbnail-med');
		
				$image_path_old = photocrati_gallery_image_path($image_name_legacy, $image_post, 'thumbnail-med', true);
				$image_uri_old = photocrati_gallery_image_uri($image_name_legacy_uri, $image_post, 'thumbnail-med', true);
			}
	
			if (file_exists($image_path))
			{
				$instance_image->ThumbPath = $image_path;
				$instance_image->ThumbURI = $image_uri;
			} 
			else if (file_exists($image_path_old))
			{
				$instance_image->ThumbPath = $image_path_old;
				$instance_image->ThumbURI = $image_uri_old;
			}
		}
		
		$instance_image->Title = stripslashes(htmlspecialchars_decode($gallery_image->image_alt));
		$instance_image->TitleHTML = htmlspecialchars($instance_image->Title);
		
		$instance_image->Description = $instance_image->TitleHTML;
		$instance_image->DescriptionHTML = '<b>' . $instance_image->Description . '</b>';
		
		if($gallery_image->image_desc)
		{
			$image_description = htmlspecialchars(stripslashes(htmlspecialchars_decode(str_replace(array("\r", "\r\n", "\n"), '', $gallery_image->image_desc))));
			
			if ($gallery_image->image_alt != null)
			{
				$instance_image->Description .= ' - ';
				$instance_image->DescriptionHTML .= ' - ';
			}
			
			$instance_image->Description .= $image_description;
			$instance_image->DescriptionHTML .= $image_description;
		}
		
		$instance_list[] = $instance_image;
	}
	
	return $instance_list;
}

function photocrati_gallery_instance_json($gallery, $options)
{
	$instance_list = photocrati_gallery_instance_list($gallery, $options);
	$count = count($instance_list);
	$json_list = array();
	
	for ($i = 0; $i < $count; $i++)
	{
		$instance_image = $instance_list[$i];
		
		$image_uri = $instance_image->URI;
		$thumb_uri = $instance_image->ThumbURI;
		$description = $instance_image->DescriptionHTML;
		
		if ($image_uri != null)
		{
			$image_uri = str_replace('#', '%23', $image_uri);
			$thumb_uri = str_replace('#', '%23', $thumb_uri);
		
			$json_list[] = array('image' => $image_uri, 'thumb' => $thumb_uri, 'description' => $description);
		}
	}
	
	return json_encode($json_list);
}

function photocrati_gallery_init()
{
}

function photocrati_gallery_admin_init()
{
	$upload_dir = photocrati_gallery_wp_upload_dir();

	if (!file_exists($upload_dir['basedir'] . '/galleries/')) {
		photocrati_mkdir($upload_dir['basedir'] . '/galleries/');
	}
}

add_action('init', 'photocrati_gallery_init');
add_action('admin_init', 'photocrati_gallery_admin_init');

?>
