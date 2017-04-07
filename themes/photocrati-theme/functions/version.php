<?php

function photocrati_theme_name()
{
	return 'Photocrati Theme';
}

function photocrati_theme_slug()
{
	return 'phototheme';
}

function photocrati_theme_version_string()
{
	return '4.8.3';
}

function photocrati_theme_update_list()
{
	$registry = C_Component_Registry::get_instance();
	$module = $registry->get_module('photocrati-auto_update-admin');

	if ($module != null) {
		return $module->_get_update_list();
	}

	return null;
}

function photocrati_theme_update_page_url()
{
	$registry = C_Component_Registry::get_instance();
	$module = $registry->get_module('photocrati-auto_update-admin');

	if ($module != null) {
		return $module->get_update_page_url();
	}

	return null;
}


?>
