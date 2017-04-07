<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

class C_Photocrati_Theme_Bootstrap
{
	private $_minimum_wordpress_version  = '3.2';
	private $_minimum_memory_limit = '16';

	function __construct()
	{
		// Boostrap
		//if ($this->are_requirements_met()) {
			$this->_define_constants();
			$this->_register_hooks();
			$this->init();
		//}
		//else add_action('admin_notices', 'render_requirements_not_met');
	}


	/**
	 * Registers hooks for the WordPress framework necessary for instantiating
	 * the theme
	 */
	function _register_hooks()
	{
	}


	/**
	 * Defines necessary plugins for the plugin to load correctly
	 */
	function _define_constants()
	{
		define('PHOTOCRATI_GALLERY_THEME', basename($this->directory_path()));
		define('PHOTOCRATI_GALLERY_THEME_DIR', $this->directory_path());
		define('PHOTOCRATI_GALLERY_THEME_URL', $this->path_uri());
		define('PHOTOCRATI_GALLERY_THEME_PRODUCT_DIR', path_join(PHOTOCRATI_GALLERY_THEME_DIR, 'products'));
		define('PHOTOCRATI_GALLERY_THEME_PRODUCT_URL', path_join(PHOTOCRATI_GALLERY_THEME_URL, 'products'));
		define('PHOTOCRATI_GALLERY_THEME_MODULE_DIR', path_join(PHOTOCRATI_GALLERY_THEME_PRODUCT_DIR, 'photocrati_theme/modules'));
		define('PHOTOCRATI_GALLERY_THEME_MODULE_URL', path_join(PHOTOCRATI_GALLERY_THEME_PRODUCT_URL, 'photocrati_theme/modules'));
		define('PHOTOCRATI_GALLERY_THEME_STARTED_AT', microtime());
		define('PHOTOCRATI_GALLERY_THEME_OPTION_PREFIX', 'photocrati');
		define('PHOTOCRATI_GALLERY_THEME_VERSION', '4.8.3');

		define('PHOTOCRATI_GALLERY_PRODUCT_DIR', PHOTOCRATI_GALLERY_THEME_PRODUCT_DIR);
		define('PHOTOCRATI_GALLERY_PRODUCT_URL', PHOTOCRATI_GALLERY_THEME_PRODUCT_URL);
		define('PHOTOCRATI_GALLERY_MODULE_DIR', PHOTOCRATI_GALLERY_THEME_MODULE_DIR);
		define('PHOTOCRATI_GALLERY_MODULE_URL', PHOTOCRATI_GALLERY_THEME_MODULE_URL);
	}


	/**
	 * Initializes the theme
	 */
	function init()
	{
		// Include pope framework
		require_once(path_join(PHOTOCRATI_GALLERY_THEME_DIR, implode(
			DIRECTORY_SEPARATOR, array('pope','lib','autoload.php')
		)));

		$gallery_legacy_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, PHOTOCRATI_GALLERY_THEME_DIR . '/photocrati-gallery');
		$theme_bulk_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, PHOTOCRATI_GALLERY_THEME_DIR . '/module.photocrati_theme_bulk.php');
		$theme_admin_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, PHOTOCRATI_GALLERY_THEME_DIR . '/admin/module.photocrati_theme_admin.php');

		$registry = C_Component_Registry::get_instance();

    $registry->add_utility('I_Component_Factory', 'C_Component_Factory');

    // Adds paths to all modules locations
		$registry->add_module_path($gallery_legacy_path, true, false);
		$registry->add_module_path($theme_bulk_path, true, false);
		$registry->add_module_path($theme_admin_path, true, false);
		$registry->add_module_path(PHOTOCRATI_GALLERY_THEME_PRODUCT_DIR, true, false);

		// Here modules are temporarily loaded manually for more control
		// This modules represent the broken-down structure of the theme
		// Note: theme_bulk has to be loaded *after* gallery_legacy because of some functions that are used in some scripts directly at inclusion time. This should be changed at some point.
		$registry->load_module('photocrati-gallery_legacy');
		$registry->load_module('photocrati-theme_bulk');
		$registry->load_module('photocrati-theme_admin');

		// Load all bundled products from the default products folder, this is the correct supported way of shipping modules.
		$registry->load_all_products();

		// Initialize all modules loaded until now
		$registry->initialize_all_modules();

		add_action('init', array($this, '_wp_init'));
	}

	function _wp_init()
	{
		$this->_clean_old_files();
	}

	function _get_file_list($path)
	{
		$path = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $path);
		$file_list = array();

		if (is_dir($path)) {

			if ($dh = opendir($path)) {

				if (substr($path, -1) != DIRECTORY_SEPARATOR) {
					$path .= DIRECTORY_SEPARATOR;
				}

				rewinddir($dh);

				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..') {
						$file_list[] = $path . $file;
					}
				}

				closedir($dh);
			}
		}

		return $file_list;
	}

	function remove_path($path)
	{
		if (is_dir($path)) {
			$file_list = $this->_get_file_list($path);

			foreach ($file_list as $file) {
				$this->remove_path($file);
			}

			rmdir($path);
		}
		else {
			unlink($path);
		}
	}

	function _clean_old_files()
	{
		$file_list = array(
			'/admin/scripts/uploadify-gallery.php',
			'/admin/gallery/',
			'/admin/updates/',
			'ecomm-sizes.php',
		);

		foreach ($file_list as $file) {
			$file = $this->file_path($file);

			if (file_exists($file)) {
				$this->remove_path($file);
			}
		}
	}


	/**
	 * Checks whether requirements have been met
	 */
	function are_requirements_met()
	{
		return (($this->has_required_memory_limit() && $this->has_required_software_versions()));
	}


	/**
	 * Renders a notice that the system requirements are not met
	 */
	function render_requirements_not_met()
	{
		include(path_join(
			$this->directory_path('templates'),
			'requirements_not_met.php'
		));
	}


	/**
	 * Ensures that the PHP memory limit is 16MB or above
	 * @return boolean
	 */
	function has_required_memory_limit()
	{
		$retval = TRUE;

        // Get the real memory limit before some increase it
		$this->memory_limit = ini_get('memory_limit');

		// If memory limit is specified in MB
		if (strtolower( substr($this->memory_limit, -1) ) == 'm') {
            $this->memory_limit = (int) substr( $this->memory_limit, 0, -1);

    		// Ensure that the memory limit is greater or equal to our minimum
    		if ( ($this->memory_limit != 0) && ($this->memory_limit < $this->_minimum_memory_limit ) ) {
				$retval = FALSE;
    		}
        }

		return $retval;
	}

	/**
	 * Checks whether the required WordPress version has been met
	 * @global string $wp_version
	 * @return boolean
	 */
	function has_required_software_versions()
	{
		global $wp_version;

		// Check for WP version installation
		return version_compare($wp_version, $this->_minimum_wordpress_version, '>=');
	}


	/**
	 * Returns the path to a file within the plugin root folder
	 * @param type $file_name
	 * @return type
	 */
	function file_path($file_name=NULL)
	{
		$location = $this->get_plugin_location();
		$path = dirname(__FILE__);

		if ($file_name != null)
		{
			$path .= '/' . $file_name;
		}

		return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	}


	/**
	 * Gets the directory path used by the plugin
	 * @return string
	 */
	function directory_path($dir=NULL)
	{
		return $this->file_path($dir);
	}


	/**
	 * Determines the location of the plugin - within a theme or plugin
	 * @return string
	 */
	function get_plugin_location()
	{
		$path = dirname(__FILE__);
		$gallery_dir = strtolower($path);
		$gallery_dir = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $gallery_dir);

		$theme_dir = strtolower(get_template_directory());
		$theme_dir = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $theme_dir);

		$plugin_dir = strtolower(WP_PLUGIN_DIR);
		$plugin_dir = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $plugin_dir);

		$common_dir_theme = substr($gallery_dir, 0, strlen($theme_dir));
		$common_dir_plugin = substr($gallery_dir, 0, strlen($plugin_dir));

		if ($common_dir_theme == $theme_dir)
		{
			return 'theme';
		}

		if ($common_dir_plugin == $plugin_dir)
		{
			return 'plugin';
		}

		$parent_dir = dirname($path);

		if (file_exists($path . DIRECTORY_SEPARATOR . 'style.css') || file_exists($parent_dir . DIRECTORY_SEPARATOR . 'style.css'))
		{
			return 'theme';
		}

		return 'plugin';
	}


	/**
	 * Gets the URI for a particular path
	 * @param string $path
	 * @param boolean $url_encode
	 * @return string
	 */
	function path_uri($path = null, $url_encode = false)
	{
		$location = $this->get_plugin_location();
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

		if ($location == 'theme')
		{
			$theme_uri = get_template_directory_uri();

			$uri = $theme_uri;

			// XXX this might need fixing for when NextGEN is embedded into the theme
			//$uri .= 'nextgen-gallery';

			if ($path != null)
			{
				$uri .= '/' . $path;
			}
		}
		else
		{
			// XXX Note, paths could not match but STILL being contained in the theme (i.e. WordPress returns the wrong path for the theme directory, either with wrong formatting or wrong encoding)
			$base = basename(dirname(__FILE__));

			if ($base != 'nextgen-gallery')
			{
				// XXX this is needed when using symlinks, if the user renames the plugin folder everything will break though
				// XXX this doesn't apply to theme yet but leaving in for future reference
				// $base = 'nextgen-gallery';
			}

			if ($path != null)
			{
				$base .= '/' . $path;
			}

			$uri = plugins_url($base);
		}

		return $uri;
	}

	/**
	 * Returns the URI for a particular file
	 * @param string $file_name
	 * @return string
	 */
	function file_uri($file_name = NULL)
	{
		return $this->path_uri($file_name);
	}
}

new C_Photocrati_Theme_Bootstrap();
