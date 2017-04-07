<?php

/***
	{
		Module: photocrati-theme_admin
	}
***/

define('PHOTOCRATI_THEME_ADMIN_MOD_URL', path_join(PHOTOCRATI_GALLERY_MODULE_URL, basename(dirname(__FILE__))));
require_once(get_template_directory().'/functions/photocrati-fonts.php');
require_once(get_template_directory().'/functions/ecommerce_options.php');

class M_Photocrati_ThemeAdmin extends C_Base_Module
{
    function define()
    {
        parent::define(
            'photocrati-theme_admin',
            'Photocrati Theme Admin',
            'Photocrati Theme Admin Code Files',
            '4.8.1',/* identical for 4.8.3 */
            'http://www.photocrati.com',
            'Photocrati Media',
            'http://www.photocrati.com'
        );
    }


    function _register_hooks()
    {
        add_action('admin_init', array(&$this, 'register_resources'), 1);
        add_action('admin_init', array(&$this, 'start_buffer'), PHP_INT_MAX);
        add_action('admin_init', array(&$this, 'import_photocrati_theme'), 1);
        add_action('init',       array(&$this, 'export_theme'));
        add_action('admin_head', array(&$this, 'output_buffer'));
        add_action('wp_ajax_update_photocrati_ecommerce', array(&$this, 'update_ecommerce_settings'));
        add_action('wp_ajax_save_third_party_preset', array(&$this, 'save_third_party_preset'));
        add_action('wp_ajax_delete_photocrati_preset', array(&$this, 'delete_photocrati_preset'));
        add_action('wp_ajax_set_photocrati_preset', array(&$this, 'set_photocrati_preset'));
    }

    /**
     * Enqueues the google font groups for the Customize Theme page
     */
    function register_resources()
    {
        if (isset($_REQUEST['test'])) {
            define('TESTICLES', true);
            $preset = new Photocrati_Style_Manager(array(
                'preset_name'   => 'preset-test',
                'preset_title'  => 'Test'
            ));
        }
    
        wp_register_style(
            'photocrati-image-picker-base',
            get_template_directory_uri().'/admin/js/image-picker/image-picker.css'
        );
        wp_register_script(
            'photocrati-image-picker-base',
            get_template_directory_uri().'/admin/js/image-picker/image-picker.min.js',
            array('jquery')
        );
        
        wp_register_style(
            'photocrati-image-picker',
            get_template_directory_uri().'/admin/css/photocrati-image-picker.css',
            array('photocrati-image-picker-base')
        );
        wp_register_script(
            'photocrati-image-picker',
            get_template_directory_uri().'/admin/js/photocrati-image-picker.js',
            array('jquery', 'photocrati-image-picker-base')
        );

        if (isset($_GET['page']) && $_GET['page'] == 'photocrati-customize-theme') {
            // Ensure that first group of fonts
            $font_group = new Photocrati_Google_Font_Group();
            wp_enqueue_style($font_group->id(), $font_group->url());

            // Enqueue the next font groups
            $font_group = $font_group->next();
            while (!$font_group->is_empty()) {
                wp_enqueue_style($font_group->id(), $font_group->url());
                $font_group = $font_group->next();
            }

            // Enqueue the photocrati-fonts script, used to ensure selections are maintained
            wp_enqueue_script(
                'photocrati-fonts',
                get_stylesheet_directory_uri().'/admin/js/photocrati-fonts.js',
                array('jquery')
            );
            
            wp_enqueue_style('photocrati-image-picker');
            wp_enqueue_script('photocrati-image-picker');
            
            wp_enqueue_media();
        }

        elseif (strpos("/wp-admin/themes.php", $_SERVER['PHP_SELF']) !== FALSE) {
            wp_enqueue_script('photocrati-customize-link', get_stylesheet_directory_uri().'/admin/js/customize-link.js');
        }
    }

    /**
     * We buffer the contents of the head tag for the admin area to re-arrange
     * the elements later
     */
    function start_buffer()
    {
        ob_start();
    }

    /**
     * Re-arranges the google font requests to be loaded first
     */
    function output_buffer()
    {
        $html = ob_get_contents();
        ob_end_clean();
        if (preg_match_all("/<link.*id=['\"]font-group.*\/>/", $html, $matches)) {
            foreach ($matches[0] as $link) {
                $html = str_replace($link, '', $html);
            }
            $links = implode("\n", $matches[0]);
            $html = str_replace('<head>', '<head>'."\n".$links, $html);
        }
        echo $html;
    }

    /**
     * Saves a third party preset
     */
    function save_third_party_preset()
    {
        if ($_POST['action'] == 'save_third_party_preset') {
            if (wp_verify_nonce($_POST['nonce'], 'save_third_party_preset') && current_user_can('manage_options')) {
                // If a preset title is specified, then we're creating a new preset
                if (isset($_POST['preset_title'])) {

                    // Parse the input data
                    $preset_title   = $_POST['preset_title'];
                    $preset_name    = sanitize_title_with_dashes($preset_title);

                    // Create the preset if it doesn't exist already
                    if (Photocrati_Style_Manager::get_preset($preset_name)->is_new()) {
                        Photocrati_Style_Manager::clone_active_as($_POST['preset_title'], TRUE);
                    }
                    else {
                        die(json_encode(array(
                            'error' =>  "A preset with that name already exists."
                        )));
                    }
                }
                // If a preset name is specified, then we're updating an existing preset
                elseif (isset($_POST['preset_name'])) {
                    $preset = Photocrati_Style_Manager::get_preset($_POST['preset_name']);
                    Photocrati_Style_Manager::clone_active_as($preset->get_title(), TRUE);
                }
            }
        }
    }

    function set_photocrati_preset()
    {
        if ($_POST['action'] == 'set_photocrati_preset') {
            if (wp_verify_nonce($_POST['nonce'], 'set_photocrati_preset') && current_user_can('manage_options')) {
                if (isset($_POST['preset_name'])) {
                    Photocrati_Style_Manager::set_active_preset($_POST['preset_name']);
                    die(json_encode(array(
                        'active' => Photocrati_Style_Manager::get_active_preset()->get_name()
                    )));
                }
            }
            else die("Damn!");
        }
    }

    function delete_photocrati_preset()
    {
        if ($_POST['action'] == 'delete_photocrati_preset') {
            if (wp_verify_nonce($_POST['nonce'], 'delete_photocrati_preset') && current_user_can('manage_options')) {
                if (isset($_POST['preset_name'])) {
                    Photocrati_Style_Manager::delete_preset($_POST['preset_name']);
                }
            }
        }
    }

    function export_theme()
    {
        if (isset($_GET['export_theme'])) {
            if (wp_verify_nonce($_GET['nonce'], 'export_theme') && current_user_can('manage_options')) {
                if (isset($_GET['preset_name'])) {
                    $preset = Photocrati_Style_Manager::get_preset($_GET['preset_name']);
                    if (!$preset->is_new()) {
                        // Remove some values that shouldn't be exported
                        $preset->delete('blog_meta');
                        $preset->delete('music_blog');
                        $preset->delete('music_blog_file');
                        $preset->delete('music_cat');
                        $preset->delete('custom_logo');
                        $preset->delete('custom_logo_image');
                        $preset->delete('social_media');
                        $preset->delete('social_media_title');
                        $preset->delete('social_media_set');
                        $preset->delete('social_rss');
                        $preset->delete('social_email');
                        $preset->delete('social_twitter');
                        $preset->delete('social_facebook');
                        $preset->delete('social_flickr');
                        $preset->delete('google_analyics');
                        $preset->delete('custom_sidebar');
                        $preset->delete('custom_sidebar_html');
                        $preset->delete('show_photocrati');
                        $preset->delete('footer_copy');



                        // Send JSON back
                        header("Pragma: public", true);
                        header("Expires: 0"); // set expiration time
                        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                        header("Content-Type: application/force-download");
                        header("Content-Type: application/octet-stream");
                        header("Content-Type: application/download");
                        header("Content-Disposition: attachment; filename=".$preset->get_name().'.crati');
                        die(json_encode($preset->to_array()));
                    }
                }
            }
        }
    }

    function import_photocrati_theme()
    {
        if (isset($_POST['import_photocrati_theme'])) {
            if (wp_verify_nonce($_POST['nonce'], 'import_photocrati_theme') && current_user_can('manage_options')) {
                if (isset($_FILES['custom_theme_file'])) {
                    Photocrati_Style_Manager::import_preset($_FILES['custom_theme_file']['tmp_name']);
                }
                wp_redirect(wp_get_referer());
            }
        }
    }

    /**
     * Updates the Photocrati e-commerce options
     */
    function update_ecommerce_settings()
    {
        $retval = FALSE;

        if ($_POST['action'] == 'update_photocrati_ecommerce') {
            if (wp_verify_nonce($_POST['nonce'], 'update_ecommerce_options') && isset($_POST['data']) && current_user_can('manage_options')) {

                global $wpdb;

                // Parse the input data
                parse_str($_POST['data'], $_POST);

                // Save e-commerce options
                $options = $_POST['options'];
                unset($_POST['options']);
                Photocrati_Ecommerce_Options::update($options);

                // Update global e-commerce settings
                $wpdb->update($wpdb->prefix.'photocrati_ecommerce_settings', $_POST, array('id' => 1));

                $retval = TRUE;
            }
            echo intval($retval);
        }
    }
}

new M_Photocrati_ThemeAdmin();
