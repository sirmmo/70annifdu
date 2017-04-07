<?php

/***
	{
		Module: photocrati-theme_bulk
	}
***/
define('PHOTOCRATI_THEME_BULK_MOD_URL', PHOTOCRATI_GALLERY_MODULE_URL);

class M_Photocrati_ThemeBulk extends C_Base_Module
{
    function define()
    {
        parent::define(
            'photocrati-theme_bulk',
            'Photocrati Theme Bulk',
            'Photocrati Theme Bulk Code Files',
            '4.8.3',
            'http://www.photocrati.com',
            'Photocrati Media',
            'http://www.photocrati.com'
        );

        // XXX use constants?
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR;

        include_once($path . 'version.php');
        include_once($path . 'defines.php');
        require_once($path . 'photocrati_style_manager.php');
        require_once($path . 'photocrati_ui.php');
        include_once($path . 'theme-options.php');
        include_once($path . 'create-tables.php');
        include_once($path . 'get-updates.php');
        include_once($path . 'create-admin.php');
        include_once($path . 'create-sidebars.php');
        include_once($path . 'misc-functions.php');
        include_once($path . 'create-thematic.php');
        include_once($path . 'feeds.php');
        require_once('functions/photocrati-fonts.php');
    }

    function _register_hooks()
    {
        add_action('init', array(&$this, 'load_google_fonts'));
        add_action('init', array(&$this, 'update_cart_product_options'));
        add_action('init', array(&$this, 'empty_cart'));
        add_action('init', array(&$this, 'load_custom_js'));
    }

    function load_custom_js()
    {
        if (isset($_REQUEST['photocrati-js'])) {
            header('Content-Type: text/javascript');
            $preset = Photocrati_Style_Manager::get_active_preset();
            die($preset->custom_js);
        }
    }

    /**
     * Provides a means of proxying the request of loading fonts from Google. We use Sidjs to load
     * the stylesheet. Sidjs checks whether the cssRules have been loaded. This check fails in Firefox
     * as due to the cross-domain security policies that Firefox enforces. To get around this, we
     * proxy the request
     */
    function load_google_fonts()
    {
        if (isset($_GET['load_google_fonts'])) {
            header('Content-Type: text/css');
            $url = 'http://fonts.googleapis.com/css?family=';
            if (isset($_SERVER['HTTPS'])) $url = str_replace('http://', 'https://', $url);
            $url .= str_replace(' ' , '+', $_GET['family']);
            die(wp_remote_fopen($url));
        }
    }

    function update_cart_product_options()
    {
        $num_items = 0;
        require_once(get_template_directory().'/photocrati-gallery/shopping-cart.php');
        if (isset($_POST['action']) && $_POST['action'] == 'update_cart_product_options' && isset($_POST['data'])) {
            if (wp_verify_nonce($_POST['nonce'],'update_cart_product_options')) {
                parse_str($_POST['data'], $_POST);
                $cart = Photocrati_Shopping_Cart::get_instance();
                if (isset($_POST['product_id'])) {
                    $product_id = intval($_POST['product_id']);
                    if (isset($_POST['options'])) {
                        foreach ($_POST['options'] as $option_id => $arr) {
                            $option_id = intval($option_id);
                            $quantity = 0.0;
                            if (in_array('quantity', array_keys($arr))) {
                                $quantity = floatval($arr['quantity']);
                            }
                            $num_items = $cart->add_item($product_id, $option_id, $quantity);
                        }
                        $cart->write_session();
                    }
                }
            }
            die(json_encode(array('number_of_cart_items' => $num_items)));
        }
    }

    function empty_cart()
    {
        if (isset($_REQUEST['empty_cart'])) {
            $cart = Photocrati_Shopping_Cart::get_instance();
            $cart->destroy();
        }
    }
}

new M_Photocrati_ThemeBulk();
