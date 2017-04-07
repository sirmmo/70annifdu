<?php

include_once get_template_directory() . '/functions/inkthemes-functions.php';
$functions_path = get_template_directory() . '/functions/';
require_once ($functions_path . 'define_template.php');
/* These files build out the options interface.  Likely won't need to edit these. */
require_once ($functions_path . 'admin-functions.php');  // Custom functions and plugins
require_once ($functions_path . 'admin-interface.php');  // Admin Interfaces 
require_once ($functions_path . 'theme-options.php');   // Options panel settings and custom settings
require_once ($functions_path . 'dynamic-image.php');
require_once ($functions_path . 'photomaker_metabox.php');
require_once($functions_path . 'custom-header.php' );
/* ----------------------------------------------------------------------------------- */
/* Styles Enqueue */
/* ----------------------------------------------------------------------------------- */

function inkthemes_add_stylesheet() {
    wp_enqueue_style('Pretty_photo', get_template_directory_uri() . "/css/prettyPhoto.css", '', '', 'all');
    wp_enqueue_style('animate_css3', get_template_directory_uri() . "/css/animate.css", '', '', 'all');
    wp_enqueue_style('smart_scroll', get_template_directory_uri() . "/css/jquery.mCustomScrollbar.css", '', '', 'all');
    wp_enqueue_style('font_awosome', get_template_directory_uri() . "/fonts/font-awesome/css/font-awesome.css", '', '', 'all');
}

add_action('init', 'inkthemes_add_stylesheet');
/* ----------------------------------------------------------------------------------- */
/* jQuery Enqueue */
/* ----------------------------------------------------------------------------------- */

function inkthemes_wp_enqueue_scripts() {
    wp_enqueue_script('inkthemes-flex', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array(
        'jquery'));
    wp_enqueue_script('inkthemes-prettyphoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array(
        'jquery'));
    wp_enqueue_script('inkthemes-imageloaded', get_template_directory_uri() . '/js/jquery.imagesloaded.js', array(
        'jquery'));
    wp_enqueue_script('inkthemes-wookmark', get_template_directory_uri() . '/js/jquery.wookmark.js', array(
        'jquery'));
    wp_enqueue_script('inkthemes-jquery.mCustomScrollbar.concat.min', get_template_directory_uri() . '/js/jquery.mCustomScrollbar.concat.min.js', array(
        'jquery'));
    wp_enqueue_script('inkthemes-custom', get_template_directory_uri() . '/js/custom.js', array(
        'jquery'));
    if (is_singular() and get_site_option('thread_comments')) {
        wp_print_scripts('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'inkthemes_wp_enqueue_scripts');
/* ----------------------------------------------------------------------------------- */
/* Custom Jqueries Enqueue */
/* ----------------------------------------------------------------------------------- */

//
function inkthemes_get_option($name) {
    $options = get_option('inkthemes_options');
    if (isset($options[$name]))
        return $options[$name];
}

//
function inkthemes_update_option($name, $value) {
    $options = get_option('inkthemes_options');
    $options[$name] = $value;
    return update_option('inkthemes_options', $options);
}

//
function inkthemes_delete_option($name) {
    $options = get_option('inkthemes_options');
    unset($options[$name]);
    return update_option('inkthemes_options', $options);
}

//Add plugin notification 
require_once(get_template_directory() . '/functions/plugin-activation.php');
require_once(get_template_directory() . '/functions/inkthemes-plugin-notify.php');
add_action('tgmpa_register', 'inkthemes_plugins_notify');
?>

