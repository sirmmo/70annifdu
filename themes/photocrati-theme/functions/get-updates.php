<?php

// Create header update notification

function theme_update_header() {
	$themename = photocrati_theme_name();
	$shortname = photocrati_theme_slug();
	$version = photocrati_theme_version_string();
	
	$update_link = photocrati_theme_update_page_url();
	$update_list = photocrati_theme_update_list();
	
	$return = '';
	
	if ($update_list != null) {
		$return = '<p>A new version of your theme is <a href="' . esc_url($update_link) . '">now available here</a>!</p>';
	} else {
		$return = '<p><em>There are no updates to this theme available at this time.</em></p>';
	}
	
	echo $return;
}


// Display theme version on admin header

function theme_version() {
	$themename = photocrati_theme_name();
	$shortname = photocrati_theme_slug();
	$version = photocrati_theme_version_string();
	
	echo '<h1>'.$themename.' '.$version.'</h1>';
	
	theme_update_header();
}


// Display theme update feed on update page

function theme_updates() {
	$themename = photocrati_theme_name();
	$shortname = photocrati_theme_slug();
	$version = photocrati_theme_version_string();
	
	$update_link = photocrati_theme_update_page_url();
	$update_list = photocrati_theme_update_list();
	
	if ($update_list != null) {
		echo '<p><a class="button-primary" href="' . esc_url($update_link) . '">&raquo; Get Update Now</a></p>';
	} else {
		echo '<p>There are no updates to this theme available at this time.</p>';
	}
}

?>
