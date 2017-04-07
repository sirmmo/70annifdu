<?php

/***
	{
		Module: photocrati-auto_update-admin,
		Depends: { photocrati-auto_update }
	}
***/

class M_AutoUpdate_Admin extends C_Base_Module
{
		var $_updater = null;
		var $_update_list = null;
		var $_controller = null;
		var $_ajax_handler = null;

    function define()
    {
        parent::define(
            'photocrati-auto_update-admin',
            'Photocrati Auto Update Admin',
            "Provides an AJAX admin interface to sequentially and progressively download and install updates",
            '0.8',
            'http://www.photocrati.com',
            'Photocrati Media',
            'http://www.photocrati.com'
        );
    }


    function _register_adapters()
    {
        $this->object->get_registry()->add_adapter('I_Component_Factory', 'A_AutoUpdate_Admin_Factory');
        $this->object->get_registry()->add_adapter('I_Ajax_Handler', 'A_AutoUpdate_Admin_Ajax');
    }


    function _register_hooks()
    {
        add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('wp_dashboard_setup', array($this, 'dashboard_setup'));

        if (is_admin())
        {
		      if (!interface_exists('I_Ajax_Handler', false)) {
		      	if (!class_exists('C_AutoUpdate_Admin_Ajax')) {
		      		include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class.autoupdate_admin_ajax.php');
		      	}

		      	$this->_ajax_handler = new C_AutoUpdate_Admin_Ajax();

						add_action('wp_ajax_photocrati_autoupdate_admin_handle', array($this->_ajax_handler, 'handle_ajax'));
		      }
        }
    }


    function _get_update_list()
    {
		// If we haven't checked for an update in a while...
		$check_date = get_option('photocrati_auto_update_admin_check_date', 0);
		if ((time() - $check_date) >= 60*60*8) {
			$this->_updater = $this->object->get_registry()->get_module('photocrati-auto_update');
			$return = $this->_updater->check_product_list();
			$update_list = array();

			if ($return != null && is_array($return))
			{
				foreach ($return as $item)
				{
					if (in_array($item['action'], array('module-add', 'module-remove', 'module-update')))
					{
						$update_list[] = $item;
					}
				}
			}
			update_option('photocrati_auto_update_admin_update_list', json_encode($update_list));
			update_option('photocrati_auto_update_admin_check_date', time());
			$this->_update_list = $update_list;
		}

		// If we have no update list, and it's not time yet to check for updates, return a cached list
		elseif (!$this->_update_list) {
			$update_list = get_option('photocrati_auto_update_admin_update_list', '[]');
			if (is_string($update_list)) {
				$this->_update_list = json_decode($update_list, TRUE);
			}
		}

    	return $this->_update_list;
    }


    function _get_text_list()
    {
  		// XXX note that because of how json_encode works across PHP versions we need NOT TO USE double quotes in the text it seems
    	return array(
    		'no_updates' => __('No updates available. You are using the latest version of Photocrati.'),
    		'updates_available' => __('An update is available.'),
    		'updates_sizes' => __('Update size is {0} and a total of <b>{1}</b> will be downloaded.'),
    		'updates_license_invalid' => __('In order to update, we need to confirm that you are still an active member. You\'ll be redirected to our site, prompted for the email address you used during purchase, and returned here for the update. {2}This is part of a new update mechanism, and you\'ll only need to do it once.'),
    		'updates_license_get' => __('Start confirmation'),
    		'updates_expired' => __('Your updates cannot be installed because your membership has expired. You can update in minutes and get immediate access to updates and support for an additional year.'),
    		'updates_renew' => __('Renew my membership'),
    		'updater_button_start' => __('Start Update'),
    		'updater_button_done' => __('Return to dashboard'),
    		'updater_status_done' => __('Success! Everything is now up-to-date.'),
    		'updater_status_start' => __('Click <b>Start Update</b> to begin the upgrade process.'),
    		'updater_status_preparing' => __('Preparing upgrade process...'),
    		'updater_status_stage_download' => __('Downloading package {1} of {0}...'),
    		'updater_status_stage_install' => __('Installing package {1} of {0}...'),
    		'updater_status_stage_activate' => __('Activating packages...'),
    		'updater_status_stage_cleanup' => __('Cleaning up...'),
    		'updater_status_cancel' => __('Update was canceled.'),
    		'updater_status_error' => __('An error occurred during your update ({0}).'),
    		'updater_logger_title' => __('Show Update Log'),
    		'updater_logger_download' => __('Download Update Log')
    	);
    }

    function get_update_page_url()
    {
    	// XXX make index.php automatic? maybe store it when creating subpage
    	return admin_url('index.php?page=photocrati-auto_update-admin');
    }


    function admin_init()
    {
			// XXX always use WP built-in ajax handler?
			$ajaxurl = admin_url('ajax_handler');

			if (!interface_exists('I_Ajax_Handler', false)) {
				$ajaxurl = admin_url('admin-ajax.php');
			}
			
			$static_progressbar_js = null;
			$static_admin_js = null;
			$static_jqueryui_css = null;
			$static_admin_css = null;
			$router = null;
			
			try {
				$router = C_Component_Registry::get_instance()->get_utility('I_Router');
			}
			catch (Exception $e) {
				$router = null;
			}
			
			if ($router != null) {
				$static_progressbar_js = $router->get_static_url('photocrati-auto_update-admin#/jqueryUI.progressbar.js');
				$static_admin_js = $router->get_static_url('photocrati-auto_update-admin#/admin.js');
				$static_jqueryui_css = $router->get_static_url('photocrati-auto_update-admin#/jquery-ui/jquery-ui-1.9.1.custom.css');
				$static_admin_css = $router->get_static_url('photocrati-auto_update-admin#/admin.css');
			}
			else {
				$theme_uri = get_template_directory_uri();
				$static_uri = $theme_uri . '/products/photocrati_theme/modules/autoupdate_admin/static/';
				$static_progressbar_js = $static_uri . 'jqueryUI.progressbar.js';
				$static_admin_js = $static_uri . 'admin.js';
				$static_jqueryui_css = $static_uri . 'jquery-ui/jquery-ui-1.9.1.custom.css';
				$static_admin_css = $static_uri . 'admin.css';
			}

			wp_register_script(
				'jquery-ui-progressbar', $static_progressbar_js,
				array('jquery-ui-core')
			);

			wp_register_script(
				'pc-autoupdate-admin', $static_admin_js,
				array('jquery-ui-core', 'jquery-ui-progressbar', 'jquery-ui-dialog')
			);

			wp_register_style(
				'jquery-ui', $static_jqueryui_css, false, '1.8.16'
			);

			wp_register_style(
				'pc-autoupdate-admin', $static_admin_css
			);

			wp_enqueue_script('pc-autoupdate-admin');
			wp_enqueue_style('jquery-ui');
			//wp_enqueue_style('wp-jquery-ui-dialog');
			wp_enqueue_style('pc-autoupdate-admin');

			wp_localize_script('pc-autoupdate-admin', 'Photocrati_AutoUpdate_Admin_Settings', array('ajaxurl' => $ajaxurl, 'adminurl' => admin_url(), 'actionSec' => wp_create_nonce('pc-autoupdate-admin-nonce'), 'request_site' => base64_encode(admin_url()), 'update_list' => json_encode($this->_get_update_list()), 'text_list' => json_encode($this->_get_text_list())));

			if ((isset($_POST['action']) && $_POST['action'] == 'photocrati_autoupdate_admin_handle'))
			{
				ob_start();
			}
    }


    function admin_menu()
    {
    	$list = $this->_get_update_list();

      if ($list != null)
      {
        $factory = $this->object->get_registry()->get_utility('I_Component_Factory');
        $this->_controller = $factory->create('autoupdate_admin_controller');

				add_submenu_page('index.php', __('Photocrati Updates'), __('Photocrati') . ' <span class="update-plugins"><span class="update-count">' . count($list) . '</span></span>', 'update_plugins', $this->module_id, array($this->_controller, 'admin_page'));
      }
      else if (isset($_GET['page']) && $_GET['page'] == $this->module_id)
			{
				wp_redirect(admin_url());

				exit();
			}
    }

    function dashboard_setup()
    {
   		wp_add_dashboard_widget('photocrati_admin_dashboard_widget', __('Welcome to Photocrati'), array($this, 'dashboard_widget'));

			global $wp_meta_boxes;

			if (isset($wp_meta_boxes['dashboard']['normal']['core']))
			{
				$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
				$widget_backup = array('photocrati_admin_dashboard_widget' => $normal_dashboard['photocrati_admin_dashboard_widget']);
				unset($normal_dashboard['photocrati_admin_dashboard_widget']);

				$sorted_dashboard = array_merge($widget_backup, $normal_dashboard);
				$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
			}
    }

    function dashboard_widget()
    {
    	$product_list = $this->object->get_registry()->get_product_list();
    	$product_count = count($product_list);
    	$update_list = $this->_get_update_list();
    	$out = null;

    	if ($product_count > 0)
    	{
    		$front_count = 0;
    		$list_out = null;
    		$msg_out = null;

    		for ($i = 0, $l = 0; $i < $product_count; $i++)
    		{
    			$product = $this->object->get_registry()->get_product($product_list[$i]);

    			if (!$product->is_background_product())
    			{
		  			if ($l > 0)
		  			{
		  				$list_out .= ',';
		  			}

    				$l++;
    				$front_count++;

		  			$list_out .= ' ' . $product->module_name . ' ' . __('version') . ' ' . $product->module_version;

		  			$msg_primary = $product->get_dashboard_message('primary');
		  			$msg_secondary = $product->get_dashboard_message('secondary');

		  			if ($msg_primary != null)
		  			{
		  				if ($msg_out != null)
		  				{
		  					$msg_out .= '<br/>';
		  				}

		  				$msg_out .= $msg_primary;
		  			}

		  			if ($msg_secondary != null)
		  			{
		  				if ($msg_out != null)
		  				{
		  					$msg_out .= '<br/>';
		  				}

		  				$msg_out .= $msg_secondary;
		  			}
    			}
    		}

    		$out .= '<p><b>';

    		if ($front_count > 1)
    		{
    			$out .= __('You are using the following products:');
    		}
    		else {
    			$out .= __('You are using');
    		}

    		$out .= $list_out;

    		$out .= '</b></p>';

    		$out .= '<p>';
    		$out .= $msg_out;
    		$out .= '</p>';

    		echo $out;
    	}

    	if ($update_list != null)
    	{
    		echo '<p>There are updates available <a class="button-secondary" href="' . esc_url($this->get_update_page_url()) . '">Update Now</a></p>';
    	}
    }

    function get_type_list()
    {
        return array(
            'A_Autoupdate_Admin_Ajax' => 'adapter.autoupdate_admin_ajax.php',
            'A_Autoupdate_Admin_Factory' => 'adapter.autoupdate_admin_factory.php',
            'C_Autoupdate_Admin_Ajax' => 'class.autoupdate_admin_ajax.php',
            'C_Autoupdate_Admin_Controller' => 'class.autoupdate_admin_controller.php'
        );
    }
}

new M_AutoUpdate_Admin();
