<?php

/***
	{
		Product: photocrati-theme
	}
***/

class P_Photocrati_Theme extends C_Base_Product
{
	function define()
	{
		parent::define(
			'photocrati-theme',
			'Photocrati Theme',
			'Photocrati Theme',
			'4.8.3',
			'http://www.photocrati.com/photography-wordpress-themes/',
			'Photocrati Media',
			'http://www.photocrati.com'
		);

		$module_path = path_join(dirname(__FILE__), 'modules');
		$this->object->get_registry()->set_product_module_path($this->module_id, $module_path);
		$this->object->get_registry()->add_module_path($module_path, true, true);
	}

	function get_dashboard_message($type = null)
	{
		switch ($type)
		{
			case null:
			case 'primary':
			{
				return __('You can customize your theme using the Theme Options menu to the left.');
			}
			case 'secondary':
			{
				return __('If you have any questions
	please visit our member area at <a href="http://members.photocrati.com" target="_blank">
	http://members.photocrati.com</a>');
			}
		}

		return parent::get_dashboard_message($type);
	}
}

new P_Photocrati_Theme();
