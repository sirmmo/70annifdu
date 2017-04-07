<?php

add_action('init',
        'of_options');
if (!function_exists('of_options')) {

    function of_options() {
        // VARIABLES
        $themename = 'PhotoMaker Theme';
        $shortname = "of";
        // Populate OptionsFramework option in array for use in theme
        global $of_options;
        $of_options = inkthemes_get_option('of_options');
        //Front page on/off
        $file_rename = array(
            "on" => "On",
            "off" => "Off");
        $home_page_slider = array(
            "on" => "On",
            "off" => "Off");
        $home_page_top_heading = array(
            "on" => "On",
            "off" => "Off");
        $home_page_feature = array(
            "on" => "On",
            "off" => "Off");
        $home_page_blog = array(
            "on" => "On",
            "off" => "Off");
        $testimonial = array(
            "on" => "On",
            "off" => "Off");
        // Background Defaults
        $background_defaults = array(
            'color' => '',
            'image' => '',
            'repeat' => 'repeat',
            'position' => 'top center',
            'attachment' => 'scroll');
        // Pull all the categories into an array
        $options_categories = array();
        $options_categories_obj = get_categories();
        foreach ($options_categories_obj as $category) {
            $options_categories[$category->cat_ID] = $category->cat_name;
        }
        // Populate OptionsFramework option in array for use in theme
        $contact_option = array(
            "on" => "On",
            "off" => "Off");
        $captcha_option = array(
            "on" => "On",
            "off" => "Off");
        // Pull all the pages into an array
        $options_pages = array();
        $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
        $options_pages[''] = 'Select a page:';
        foreach ($options_pages_obj as $page) {
            $options_pages[$page->ID] = $page->post_title;
        }
        // If using image radio buttons, define a directory path
        $imagepath = get_template_directory_uri() . '/images/';

        $options = array(
            array(
                "name" => "General Settings",
                "type" => "heading"),
            array(
                "name" => "Custom CSS",
                "desc" => "Quickly add your custom CSS code to your theme by writing the code in this block.",
                "id" => "inkthemes_customcss",
                "std" => "",
                "type" => "textarea"));

        inkthemes_update_option('of_template',
                $options);
        inkthemes_update_option('of_themename',
                $themename);
        inkthemes_update_option('of_shortname',
                $shortname);
    }

}
?>
