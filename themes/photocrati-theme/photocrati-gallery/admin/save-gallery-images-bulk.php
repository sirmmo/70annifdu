<?php

// Load WordPress Framework
define('ABSPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/');
include_once(ABSPATH.'wp-config.php');
include_once(ABSPATH.'wp-load.php');
include_once(ABSPATH.'wp-includes/wp-db.php');
global $wpdb;

// Ensure that the current user has permission to perform this action
if (!current_user_can('edit_pages') && !current_user_can('edit_posts'))
{
	wp_die('Permission Denied.');
}

$values = $_POST;

if (isset($values['data'])) {
	$data = $values['data'];
	$values = $data;
	
	if (is_string($values))
	{
		$values = json_decode($values, true);
		
		if (!$values)
		{
			$values = json_decode(stripslashes($data), true);
		}
	}
}

// Escape parameters
$post_id			= $values['post_id'];
$gallery_id 		= $values['gallery_id'];
$gallery_title		= $values['gallery_title'];
$gallery_description= $values['gallery_description'];
$gallery_type		= $values['gallery_type'];
$gallery_height		= $values['gallery_height'];
$aspect_ratio		= $values['aspect_ratio'];
$number_of_images	= $values['number_of_images'];
$next_gallery_id	= $values['next_gallery_id'];
$images				= isset($values['images']) ? $values['images'] : array();
$is_new_gallery = false;
$ecommerce_options = Photocrati_Ecommerce_Options::get_instance()->options;
$ecommerce_options_all = implode(',', array_keys($ecommerce_options));

// We'll return this array as JSON to the browser (client)
$retval = array('inserts' => 0, 'updates' => 0, 'gallery_id' => $gallery_id, 'log' => array());

// Table aliases
$wpdb->photocrati_galleries = $wpdb->prefix.'photocrati_galleries';
$wpdb->photocrati_gallery_ids = $wpdb->prefix.'photocrati_gallery_ids';
$wpdb->suppress_errors(FALSE);


// Assemble gallery data
$data = array(
	'post_id'			=>	$post_id,
	'gal_height'		=>	$gallery_height,
	'gal_aspect_ratio'	=>	$aspect_ratio,
	'gal_title'			=>	$gallery_title,
	'gal_desc'			=>	$gallery_description,
	'gal_type'			=>	$gallery_type
);

// Update the gallery
if ($gallery_id) {
	$retval['log'][] = "Updating gallery";
	$wpdb->update($wpdb->photocrati_gallery_ids, $data, array('gallery_id' => $gallery_id));
}

// Insert
else {
	$is_new_gallery = true;
	$retval['log'][] = "Inserting gallery";
	$retval['gallery_id'] = $data['gallery_id'] = $gallery_id = "{$post_id}_{$next_gallery_id}";
	$wpdb->insert($wpdb->photocrati_gallery_ids, $data);
}

// Update/add each image
foreach ($images as $image) {
	
	if ($image['ecommerce_options'] == 0 && $is_new_gallery) {
		$image['ecommerce_options'] = $ecommerce_options_all;
	} 

	$data = array(
		'gallery_id'	=>	$gallery_id,
		'post_id'		=>	$post_id,
		'gal_type'		=>	$gallery_type,
		'image_name'	=>	$image['caption'],
		'image_desc'	=>	$image['description'],
		'image_alt'		=>	$image['alt_text'],
		'image_order'	=>	$image['order'],
		'ecomm_options'	=>	$image['ecommerce_options']
	);

	// Update ?
	if (isset($image['id'])) {
		$retval['log'][] = "Updating image #{$image['id']}";
		$retval['log'][] = print_r($data, TRUE);
		$retval['updates'] += $wpdb->update(
			$wpdb->photocrati_galleries,
			$data,
			array('id' => $image['id'])
		);

	}

	// Insert
	else {
		$retval['log'][] = "Inserting image {$data['image_name']}";
		$retval['log'][] = print_r($data, TRUE);
		$retval['inserts'] += $wpdb->insert($wpdb->photocrati_galleries, $data);
	}
}

// Return JSON
echo json_encode($retval);
