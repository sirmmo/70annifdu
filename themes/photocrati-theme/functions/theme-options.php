<?php

interface PhotocratiTheme_IOptionScheme
{
	public function sanitize($id, $value, $strict = true);
}

interface PhotocratiTheme_IOptionProvider
{
	// XXX only for backward compatibility
	public function get_all_options();
	
	public function get_option($name);
	
	public function set_option($name, $value);
	
	public function get_option_list();
	
	public function update();
	
	// XXX not done at this level yet
	//public function setScheme(PhotocratiTheme_IOptionScheme $scheme);
}

class PhotocratiTheme_OptionProviderList implements PhotocratiTheme_IOptionProvider
{
	public static function create($list, $source = null)
	{
		$data_provider = new self($list, $source);
		
		return null;
	}
	
	public static function get($list, $source = null)
	{
		// No caching for now, not useful
		return self::create($list, $source);
	}
	
	
	private $_list;
	private $_changed;
	private $_source;
	
	protected function __construct($list, $source = null)
	{
		if ($list == null)
		{
			$list = array();
		}
		
		$this->_list = $list;
		$this->_changed = array();
		$this->_source = $source;
	}
	
	// XXX only for backward compatibility
	public function get_all_options()
	{
		return $this->get_all_options_internal(false);
	}
	
	protected function get_all_options_internal($only_changed = false)
	{
		$list = $this->get_option_list($only_changed);
		$final_list = array();
		
		foreach ($list as $name)
		{
			$final_list[$name] = $this->get_option($name);
		}
		
		return $final_list;
	}
	
	public function get_option($name)
	{
		if (in_array($name, array_keys($this->_list)))
		{
			return $this->_list[$name];
		}
		
		return null;
	}
	
	public function set_option($name, $value)
	{
		$old = $this->get_option($name);
		
		if ($old != $value)
		{
			if (!isset($this->_changed[$name]))
			{
				$this->_changed[$name] = 0;
			}
			
			$this->_changed[$name] += 1;
		}
		
		$this->_list[$name] = $value;
	}
	
	public function get_option_list($only_changed = false)
	{
		$list = array();
		
		if ($only_changed)
		{
			foreach ($this->_changed as $name => $count)
			{
				if ($count > 0)
				{
					$list[] = $name;
				}
			}
		}
		else
		{
			$list = array_keys($this->_list);
		}
		
		return $list;
	}
	
	public function update()
	{
		$this->_changed = array();
		
		return true;
	}
	
	public function get_source()
	{
		return $this->_source;
	}
}

// only temporarily based on ProviderList?
class PhotocratiTheme_OptionProviderDatabase extends PhotocratiTheme_OptionProviderList
{
	private static $_list = array();
	
	public static function create($table, $filter = null, $wp_db = null)
	{
		$data_provider = new self($table, $filter, $wp_db);
		
		// XXX perform validation of provider, e.g. failed SQL query
		return $data_provider;
	}
	
	public static function get($table, $filter = null, $wp_db = null)
	{
		if ($table != null)
		{
			$data_provider = null;
			
			foreach (self::$_list as $provider_item)
			{
				// $wp_db is assumed to always be the same
				if ($provider_item['table'] == $table && $provider_item['filter'] == $filter)
				{
					$data_provider = $provider_item['data-provider'];
				}
			}
			
			if ($data_provider == null)
			{
				$data_provider = self::create($table, $filter, $wp_db);
				
				self::$_list[] = array('table' => $table, 'filter' => $filter, 'data-provider' => $data_provider);
			}
		
			return $data_provider;
		}
		
		return null;
	}
	
	
	private $_wp_db;
	private $_table;
	private $_filter;
	
	protected function __construct($table, $filter = null, $wp_db = null)
	{
		$list = null;
		
		if ($wp_db == null)
		{
			global $wpdb;
			
			if ($wpdb != null)
			{
				$wp_db = $wpdb;
			}
		}
		
		$this->_wp_db = $wp_db;
		$this->_table = $table;
		$this->_filter = $filter;
		
		if ($wp_db != null && $table != null)
		{
			$expanded = $this->expand_filter($filter);
			$where = $expanded['query'];
			$args = $expanded['arguments'];
			
			$list = $wp_db->get_row($wp_db->prepare('SELECT * FROM ' . $wp_db->prefix . $table . $where, $args), ARRAY_A);
		}
		
		parent::__construct($list, 'DATABASE.' . $table);
	}
	
	public function update()
	{
		$list = parent::get_all_options_internal(false);
		
		if ($list != null)
		{
			$preset = Photocrati_Style_Manager::get_active_preset();
			foreach ($list as $key => $val) $preset->$key = $val;
			$preset->save(PHOTOCRATI_ACTIVE_PRESET);

			return TRUE;
		}
		
		return FALSE;
	}
	
	protected function expand_filter($filter)
	{
		$expanded = $this->expand_query_list($filter, '%s', '=', 'AND');
		$query = $expanded['query'];
		
		if ($query != null)
		{
			$expanded['query'] = ' WHERE ' . $query;
		}
		
		return $expanded;
	}
	
	protected function expand_query_list($list, $def_var, $def_cmp, $def_op)
	{
		$query = null;
		$args = array();
		
		if ($list != null)
		{
			$count = 0;
			
			foreach ($list as $key => $item)
			{
				$var = $def_var;
				$cmp = $def_cmp;
				$value = $item;
				$op = $def_op;
				
				if (is_int($value))
				{
					$var = '%d';
				}
				else if (is_float($value))
				{
					$var = '%f';
				}
				else if (is_string($value))
				{
					$var = '%s';
				}
				
				if (is_array($item))
				{
					$var = isset($item['variable']) ? $item['variable'] : $var;
					$cmp = isset($item['compare']) ? $item['compare'] : $cmp;
					$value = isset($item['value']) ? $item['value'] : $value;
					$op = isset($item['operator']) ? $item['operator'] : $op;
				}
				
				if ($count > 0)
				{
					$query .= ' ' . $op . ' ';
				}
				
				$query .= $key . ' ' . $cmp . ' ' . $var;
				$args[] = $value;
				
				$count++;
			}
		}
		
		return array('query' => $query, 'arguments' => $args);
	}
}

class PhotocratiTheme_OptionScheme implements PhotocratiTheme_IOptionScheme
{
	private $_id;
	private $_list;
	
	public function __construct($scheme_id = null)
	{
		$this->_id = $scheme_id;
		$this->_list = array();
	}
	
	// Look at sanitize method for possible $type values and their meaning
	public function add_option($id, $type = null, $default = null, $name = null, $meta = null)
	{
		if (!isset($this->_list[$id]))
		{
			if ($name == null)
			{
				$index = strpos($id, '.');
				
				if ($index !== false)
				{
					$name = substr($id, $index + 1);
				}
				else
				{
					$name = $id;
				}
			}
			
			$descriptor = array('id' => $id, 'type' => $type, 'default' => $default, 'name' => $name);
			
			if ($meta != null && is_array($meta))
			{
				$descriptor = array_merge($meta, $descriptor);
			}
			
			$this->_list[$id] = $descriptor;
		}
	}
	
	public function get_option_meta($id, $name)
	{
		if (isset($this->_list[$id]))
		{
			if (isset($this->_list[$id][$name]))
			{
				return $this->_list[$id][$name];
			}
			
			return null;
		}
		
		return false;
	}
	
	public function set_option_meta($id, $name, $value)
	{
		if (isset($this->_list[$id]))
		{
			if ($name != 'id' && $name != 'type' && $name != 'name')
			{
				$this->_list[$id][$name] = $value;
			}
		}
	}
	
	public function get_option_type($id)
	{
		return $this->get_option_meta($id, 'type');
	}
	
	public function get_option_name($id)
	{
		return $this->get_option_meta($id, 'name');
	}
	
	public function get_option_label($id)
	{
		return $this->get_option_meta($id, 'label');
	}
	
	public function set_option_label($id, $label)
	{
		$this->set_option_meta($id, 'label', $label);
	}
	
	public function get_option_description($id)
	{
		return $this->get_option_meta($id, 'description');
	}
	
	public function set_option_description($id, $description)
	{
		$this->set_option_meta($id, 'description', $description);
	}
	
	public function get_option_default($id)
	{
		return $this->get_option_meta($id, 'default');
	}
	
	public function set_option_default($id, $default)
	{
		$this->set_option_meta($id, 'default', $default);
	}
	
	public function get_option_rule($id)
	{
		return $this->get_option_meta($id, 'rule');
	}
	
	public function set_option_rule($id, $rule)
	{
		$this->set_option_meta($id, 'rule', $rule);
	}
	
	public function is_option($id)
	{
		return isset($this->_list[$id]);
	}
	
	public function validate($id, $value)
	{
		return $value === $this->sanitize($id, $value, true);
	}
	
	public function validate_test($id, $value)
	{
		return $value === $this->sanitize($id, $value, false);
	}
	
	// XXX $strict == true is supposed to throw off exceptions when validation fails, not implemented yet but keep this in mind to create future proof code
	public function sanitize($id, $value, $strict = false)
	{
		if (isset($this->_list[$id]))
		{
			$return = null;
			$exception = null;
			$option = $this->_list[$id];
			$type = $option['type'];
			$rule = isset($option['rule']) ? $option['rule'] : null;
			
			switch ($type)
			{
				// Note, there are various ways to define booleans, mostly because of backward compatibility with the theme that used many different ways to specify truth values
				case 'boolean':
				{
					$return = $value ? true : false;
					
					break;
				}
				case 'affirmative':
				{
					$return = strtolower($value) == 'yes' ? true : false;
					
					break;
				}
				case 'toggle':
				{
					if (is_bool($value)) $return = $value;
					else $return = strtolower($value) == 'on' ? true : false;
					
					break;
				}
				case 'integer':
				{
					$value = trim($value);
					$return = (int) $value;
					
					if (((string) $return) != ((string) $value))
					{
						$return = 0;
						$exception = new Exception('Wrong format for value, only numbers accepted');
					}
					
					break;
				}
				case 'decimal':
				{
					$return = (double) $value;
					
					break;
				}
				case 'color':
				{
					$return = (string) $value;
					$return = trim($value);
					$rule_def = '/^#?(?:[a-fA-F0-9]{3}){1,2}$/';
					
					if (preg_match($rule_def, $return))
					{
						if ($return[0] != '#')
						{
							$return = '#' . $return;
						}
					}
					else if ($return != 'transparent')
					{
						$return = null;
						$exception = new Exception('Wrong format for color value');
					}
					
					break;
				}
				case 'text':
				default:
				{
					$return = (string) $value;
					
					break;
				}
			}
			
			if ($rule != null && !preg_match($rule, $return))
			{
				$return = null;
				$exception = new Exception('Wrong format for value');
			}
			
			if ($strict && $exception != null)
			{
				throw $exception;
			}
			
			return $return;
		}
		
		return null;
	}
	
	public function get_option_list()
	{
		return array_keys($this->_list);
	}
}

function photocrati_theme_admin_init()
{
	wp_register_script('photocrati-theme-options-admin', get_template_directory_uri() . '/admin/js/theme-options-admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), '1.0');

	if ((isset($_POST['action']) && $_POST['action'] == 'photocrati_theme_options_admin_handle'))
	{
		ob_start();
	}
}

function photocrati_theme_admin_menu()
{
	$pages = array();
	
	$pages[] = add_menu_page('Theme Options', 'Theme Options', 'administrator', 'set-up', 'QuickSetUp', get_template_directory_uri() . '/admin/images/admin-icon.png', 1);
	$pages[] = add_submenu_page('set-up', 'Quick Set Up', 'Quick Set Up', 'administrator', 'set-up', 'QuickSetUp'); 
	$pages[] = add_submenu_page('set-up', 'Choose Theme', 'Choose Theme', 'administrator', 'choose-theme', 'ChooseTheme'); 
	$pages[] = add_submenu_page('set-up', 'Customize Theme', 'Customize Theme', 'administrator', 'photocrati-customize-theme', 'CustomTheme');
	$pages[] = add_submenu_page('set-up', 'Gallery Settings', 'Gallery Settings', 'administrator', 'gallery-options', 'GalleryOptions');
	$pages[] = add_submenu_page('set-up', 'Ecommerce Settings', 'Ecommerce Settings', 'administrator', 'ecomm-options', 'EcommOptions');
	$pages[] = add_submenu_page('set-up', 'Other Options', 'Other Options', 'administrator', 'other-options', 'OtherOptions'); 
	$pages[] = add_submenu_page('set-up', 'Help & Support', 'Help & Support', 'administrator', 'help-support', 'HelpSupport');
	
	$done_pages = array();
	
	foreach ($pages as $page)
	{
		if (!in_array($page, $done_pages))
		{
		add_action('admin_print_scripts-' . $page, 'photocrati_theme_admin_print_scripts');
		add_action('admin_print_styles-' . $page, 'photocrati_theme_admin_print_styles');
			
			$done_pages[] = $page;
		}
	}
}

function photocrati_theme_admin_print_scripts()
{
	wp_enqueue_script('photocrati-theme-options-admin');
	wp_localize_script('photocrati-theme-options-admin', 'Photocrati_ThemeOptions_Settings', 
		array(
			'ajaxurl' => admin_url('admin-ajax.php'), 
			'actionSec' => wp_create_nonce('photocrati-theme-options-admin-submit-nonce'), 
			'ajaxloader' => get_template_directory_uri() . '/admin/images/ajax-loader.gif'
		)
	);
}

function photocrati_theme_admin_print_styles()
{
}

function photocrati_theme_admin_ajax_handle()
{
	check_ajax_referer('photocrati-theme-options-admin-submit-nonce', 'actionSec');

	if (!isset($_POST['innerAction']) || $_POST['innerAction'] == null) {
		return;
	}

	$action = $_POST['innerAction'];
	$params = isset($_POST['actionParams']) ? $_POST['actionParams'] : null;
	$response = null;
	$pass = false;
	
	// Ensure permissions are OK ($pass == true)
	switch ($action) {
		case 'update-styles':
		case 'image-list-add':
		case 'image-list-remove':
		case 'image-list-get-markup':
		{
			if (current_user_can('manage_options'))
			{
				$pass = true;
			}

			break;
		}
		case 'create-default-pages':
		{
			if (current_user_can('manage_options') && current_user_can('edit_pages') && current_user_can('edit_posts'))
			{
				$pass = true;
			}

			break;
		}
	}

	if ($pass) {
		try {
			switch ($action) {
				case 'update-styles':
		  	{
					$failed_list = array();
					$style_list = $params['item-list'];
					
					foreach ($style_list as $style_name => $style_value) {
						if (is_array($style_value)) {
							if (isset($style_list[$style_name . '_autoSort'])) {
								$style_sort = $style_list[$style_name . '_autoSort'];
								
								ksort($style_sort, SORT_NUMERIC);
								
								$style_value_new = $style_sort;
								
								foreach ($style_value as $style_value_it) {
									$value_idx = array_search($style_value_it, $style_value_new);
									
									if ($value_idx === false) {
										$style_value_new[] = $style_value_it;
									}
								}
								
								$style_list[$style_name] = $style_value_new;
							}
						}
					}
					
					$preset = Photocrati_Style_Manager::get_active_preset();
					foreach ($style_list as $key => $val) $preset->$key = $val;

					if (!Photocrati_Style_Manager::generate_static_stylesheet()) {
						$error = 'Could not write to styles/style.css file';
						//$failed_list['dynamic_style'] = array('error' => $error, 'value' => $style_list['dynamic_style']);
						Photocrati_Style_Manager::enable_dynamic_stylesheet();
					}
					
					if (isset($style_list['logo_menu_position'])) {
						$logo_position  = $preset->logo_menu_position;
						$logo_onecolumn = $preset->one_column_logo;
				
						if ($logo_onecolumn) {
							$preset->header_logo_margin_above = 0;
							$preset->header_logo_margin_below = 0;
						}
						else {
							switch ($logo_position) {
								case 'bottom-top':
								{
									$preset->header_logo_margin_above = 60;
									$preset->header_logo_margin_below = 20;
									break;
								}
								case 'top-bottom':
								{
									$preset->header_logo_margin_above = 10;
									$preset->header_logo_margin_below = 20;
									break;
								}
								case 'left-right':
								case 'right-left':
								{
									$preset->header_logo_margin_above = 25;
									$preset->header_logo_margin_below = 10;
									break;
								}
							}
						}
						$style_list['header_logo_margin_above'] = $preset->header_logo_margin_above;
						$style_list['header_logo_margin_below'] = $preset->header_logo_margin_below;
					}

					$preset->save(PHOTOCRATI_ACTIVE_PRESET);
					$response['result'] = 'OK';
					$response['item-list'] = $style_list; 
					$response['failed-list'] = $failed_list;
					
					break;
				}
				case 'create-default-pages':
				{
					$post_default = array(
						'post_type' => 'page',
						'post_status' => 'publish',
						'comment_status' => 'closed'
					);
					
					$page_list = array(
						'home' => array(
							'post_title' => 'Home'
						),
						'galleries' => array(
							'post_title' => 'Galleries'
						),
						'blog' => array(
							'post_title' => 'Blog'
						),
						'about' => array(
							'post_title' => 'About'
						),
						'contact' => array(
							'post_title' => 'Contact'
						),
					);
					
					$res_page_list = array();
					$page_number = 1;
					
					foreach ($page_list as $page_key => $page) {
						$post = $post_default;
						
						foreach ($page as $post_key => $post_val)
						{
							$post[$post_key] = $post_val;
						}
						
						$post['post_name'] = $page_key;
						$post['menu_order'] = $page_number;
						
						$id = wp_insert_post($post);
						$res_page_list[$page_key] = array('id' => $id);
						
						$page_number++;
					}
					
					if (isset($res_page_list['home'])) {
						update_option('show_on_front', 'page');
						update_option('page_on_front', $res_page_list['home']['id']);
					}
					
					if (isset($res_page_list['blog'])) {
						update_option('show_on_front', 'page');
						update_option('page_for_posts', $res_page_list['blog']['id']);
					}
					
					// Create ecommerce page if not present
					photocrati_ecommerce_get_cart_page();
					
					$response['result'] = 'OK';
					$response['page-list'] = $res_page_list;
					
					break;
				}
				case 'image-list-add':
				case 'image-list-remove':
		  	{
					$item_list = isset($params['item-list']) ? $params['item-list'] : null;
					$item = isset($params['item']) ? $params['item'] : null;
					
					if ($item_list == null && $item != null) {
						$item_list = array($item);
					}
					
					foreach ($item_list as $item) {
						$name = $item['name'];
						
						if ($action == 'image-list-add') {
							Photocrati_Style_Manager::image_list_add($name, $item);
						}
						else if ($action == 'image-list-remove') {
							Photocrati_Style_Manager::image_list_remove($name);
						}
					}
					
					$response['result'] = 'OK';
					
					break;
		  	}
				case 'image-list-get-markup':
		  	{
					$setting_name = $params['setting_name'];
					$setting_value = $params['setting_value'];
					$type = $params['type'];
					
					$dropdown_html = self::build_image_dropdown_html($setting_name, $setting_value, $type);
					
					$response['result'] = 'OK';
					$response['markup'] = $dropdown_html;
					
					break;
		  	}
			}
		}
		catch (Exception $ex) {
			$error = $ex->getMessage();

			if ($ex->getCode() == 1001 || $ex->getCode() == 1002) {
				//$error .= ' ' . __('Extra message.');
			}

			$response['error'] = htmlentities($error);
		}
	}
	
	$output = null;

	while (ob_get_level() > 0) {
		$output .= ob_get_clean();
	}

	if ($response != null) {
		$response['output'] = $output;
		$response = json_encode($response);

		header('Content-Type: application/json');

		echo $response;
	}
	else {
		header('HTTP/1.1 403 Forbidden');
	}

	exit();
}

function photocrati_theme_load()
{
	add_action('admin_menu', 'photocrati_theme_admin_menu');
	add_action('admin_init', 'photocrati_theme_admin_init');
	add_action('wp_ajax_photocrati_theme_options_admin_handle', 'photocrati_theme_admin_ajax_handle');
}

photocrati_theme_load();
