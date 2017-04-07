<?php

// Create custom admin menus
$dynamic_style = Photocrati_Style_Manager::get_active_preset()->dynamic_style;

/**
 * Leaving this here for legacy purposes, but it appears to be unused
 */
function adminMenu(){
    $dynamic_style = Photocrati_Style_Manager::get_active_preset()->dynamic_style;
}
add_action('admin_menu', 'adminMenu');

function QuickSetUp(){
	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/admin/admin.css" />';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/jquery.fancybox-1.3.4.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.fancybox-1.3.4.pack.js"></script>';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.corner.js"></script>';
	include TEMPLATEPATH."/admin/quick-setup.php";
}

function ChooseTheme(){
	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/admin/admin.css" />';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/jquery.fancybox-1.3.4.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.fancybox-1.3.4.pack.js"></script>';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.corner.js"></script>';
	include TEMPLATEPATH."/admin/setup-admin.php";
}

function CustomTheme(){
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/css_browser_selector.js"></script>';
	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/admin/admin.css" />';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/colorpicker.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/colorpicker.js"></script>';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/jquery.fancybox-1.3.4.css" />';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/js/ui-lightness/jquery-ui-1.8.2.custom.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.fancybox-1.3.4.pack.js"></script>';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.corner.js"></script>';
	if (version_compare(get_bloginfo('version'), '4.1', '>=')) {
		echo '<script type="text/javascript" src="'.includes_url('js/jquery/ui/core.min.js').'"></script>';
	}
	else {
		echo '<script type="text/javascript" src="'.includes_url('js/jquery/ui/jquery.ui.core.min.js').'"></script>';
	}
    include TEMPLATEPATH.'/admin/thickbox-includes.php';
	include TEMPLATEPATH."/admin/theme-admin.php";
}

function GalleryOptions(){
	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/admin/admin.css" />';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/colorpicker.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/colorpicker.js"></script>';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/jquery.fancybox-1.3.4.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.fancybox-1.3.4.pack.js"></script>';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.corner.js"></script>';
    include TEMPLATEPATH.'/admin/thickbox-includes.php';
	include TEMPLATEPATH."/admin/gallery-options.php";
}

function EcommOptions(){

	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/admin/admin.css" />';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/colorpicker.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/colorpicker.js"></script>';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/jquery.fancybox-1.3.4.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.fancybox-1.3.4.pack.js"></script>';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.corner.js"></script>';
    include TEMPLATEPATH.'/admin/thickbox-includes.php';
	include TEMPLATEPATH."/admin/ecommerce-options.php";
}

function OtherOptions(){
	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/admin/admin.css" />';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/jquery.fancybox-1.3.4.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.fancybox-1.3.4.pack.js"></script>';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.corner.js"></script>';
	include TEMPLATEPATH."/admin/analytics-admin.php";
	include TEMPLATEPATH."/admin/css-admin.php";
}

function HelpSupport(){
	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/admin/admin.css" />';
	echo '<link rel="stylesheet" media="screen" type="text/css" href="'.get_template_directory_uri().'/admin/css/jquery.fancybox-1.3.4.css" />';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.fancybox-1.3.4.pack.js"></script>';
	echo '<script type="text/javascript" src="'.get_template_directory_uri().'/admin/js/jquery.corner.js"></script>';
	include TEMPLATEPATH."/admin/help.php";
	include TEMPLATEPATH."/admin/theme-updates.php";
}


//A simple function to get data stored in a custom field
if(!function_exists('get_custom_field')) {
function get_custom_field($field) {
	global $post;
	$custom_field = get_post_meta($post->ID, $field, true);
	echo $custom_field;
}
}

// Adds a custom section to the "advanced" Post and Page edit screens
function add_custom_box() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'custom_box_1', __( 'Add Music To The Page', 'photocrati' ), 'inner_custom_box_1', 'page', 'side', 'high' );
	}
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'custom_box_1', __( 'Add Music To The Post', 'photocrati' ), 'inner_custom_box_1', 'post', 'side', 'high' );
	}
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'custom_box_2', __( 'Comments', 'photocrati' ), 'inner_custom_box_2', 'page', 'side', 'high' );
	}
}

function inner_custom_box_1() {
	global $post;

	// Use nonce for verification ... ONLY USE ONCE!
	echo '<input type="hidden" name="csa_noncename" id="csa_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	// The actual fields for data entry
    echo '<div style="height:auto;position:relative;overflow:hidden;">';


    echo '<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function() {

        jQuery("[id^=add_image_]").on("click", function() {
            currentId = jQuery(this).attr("id");
            formfield = jQuery("#"+currentId.substr(10)).attr("name");
            tb_show("", "media-upload.php?type=image&amp;post_id=1-'.$_GET["post"].'&amp;TB_iframe=true");
            return false;
        });

    });
    </script>';

    echo '<div style="width:100%;float:left;margin:8px 0 5px 0;">';
    echo '<b>Use Music? </b>&nbsp;&nbsp;<select name="music">';
    echo '<option value="NO"';

    if(get_post_meta($post->ID, 'music', true) == 'NO') {

    echo ' SELECTED';

    }

    echo '>NO</option>';
    echo '<option value="YES"';

    if(get_post_meta($post->ID, 'music', true) == 'YES') {

    echo ' SELECTED';

    }

    echo '>YES</option>';
    echo '</select></div>';

    echo '<div style="width:100%;float:left;margin:8px 0 5px 0;">';
    echo '<b>Autoplay on page load? </b>&nbsp;&nbsp;<select name="music_auto">';
    echo '<option value="NO"';

    if(get_post_meta($post->ID, 'music_auto', true) == 'NO') {

    echo ' SELECTED';

    }

    echo '>NO</option>';
    echo '<option value="YES"';

    if(get_post_meta($post->ID, 'music_auto', true) == 'YES') {

    echo ' SELECTED';

    }

    echo '>YES</option>';
    echo '</select></div>';

    echo '<div style="width:100%;float:left;margin:8px 0 5px 0;">';
    echo '<b>Display controls? </b>&nbsp;&nbsp;<select name="music_controls">';
    echo '<option value="NO"';

    if(get_post_meta($post->ID, 'music_controls', true) == 'NO') {

    echo ' SELECTED';

    }

    echo '>NO</option>';
    echo '<option value="YES"';

    if(get_post_meta($post->ID, 'music_controls', true) == 'YES') {

    echo ' SELECTED';

    }

    echo '>YES</option>';
    echo '</select></div>';

    echo '<div style="width:100%;clear:both;float:left;position:relative;padding-bottom:5px;">';
    echo '<p><label for="music_file">' . __("<strong>File (mp3 format)</strong>", 'photocrati' ) . '</label><BR>Upload a file, copy the "Link URL", paste it into the field below and save this page/post.</p>';
	echo '<input type="text" size="25" name="music_file" id="music_file" value="'.get_post_meta($post->ID, 'music_file', true).'"> ';
    echo '<input type="button" class="button" id="add_image_music_file" value="Upload mp3" style="clear:none;" />';
    echo '</div>';

    echo '</div>';
}

function inner_custom_box_2() {
	global $post;

	// Use nonce for verification ... ONLY USE ONCE!
	echo '<input type="hidden" name="csa_noncename" id="csa_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	// The actual fields for data entry
    echo '<div style="height:auto;position:relative;overflow:hidden;">';

    echo '<div style="width:100%;float:left;margin:8px 0 5px 0;">';
    echo '<b>Page Comments Are </b>&nbsp;&nbsp;<select name="comments">';
    echo '<option value="OFF"';

    if(get_post_meta($post->ID, 'commments', true) == 'OFF') {

    echo ' SELECTED';

    }

    echo '>OFF</option>';
    echo '<option value="comments"';

    if(get_post_meta($post->ID, 'comments', true) == 'comments') {

    echo ' SELECTED';

    }

    echo '>ON</option>';
    echo '</select></div>';

    echo '</div>';
}

/* When the post is saved, saves our custom data */
function save_postdata($post_id, $post) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if (!isset($_POST['csa_noncename']) OR !wp_verify_nonce( $_POST['csa_noncename'], plugin_basename(__FILE__) )) {
	    return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post->ID ))
		return $post->ID;
	} else {
		if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	}

	// OK, we're authenticated

	// We need to find and save the data
	// We'll put it into an array to make it easier to loop though.

    $mydata['music_file'] = $_POST['music_file'];
	$mydata['music_auto'] = $_POST['music_auto'];
	$mydata['music_controls'] = $_POST['music_controls'];
    $mydata['music'] = $_POST['music'];
    $mydata['comments'] = $_POST['comments'];

	// Add values of $mydata as custom fields

	foreach ($mydata as $key => $value) { //Let's cycle through the $mydata array!
		if( $post->post_type == 'revision' ) return; //don't store custom data twice
		$value = implode(',', (array)$value); //if $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { //if the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { //if the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); //delete if blank
	}

}

/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'add_custom_box');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'save_postdata', 1, 2); // save the custom fields

?>
