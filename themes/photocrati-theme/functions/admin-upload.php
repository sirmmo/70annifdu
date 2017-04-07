<?php

function photocrati_upload_parameter_list()
{
	// XXX move to own function, flash uploader params
	$parameter_list = array(
			AUTH_COOKIE => (is_ssl() ? $_COOKIE[SECURE_AUTH_COOKIE] : $_COOKIE[AUTH_COOKIE]),
			LOGGED_IN_COOKIE => $_COOKIE[LOGGED_IN_COOKIE],
			"_wpnonce" => wp_create_nonce('photocrati-upload')
	);
	
	return $parameter_list;
}

function photocrati_upload_parameter_string($parameter_list = null)
{
	if ($parameter_list === null)
	{
		$parameter_list = photocrati_upload_parameter_list();
	}

	$upload_params_str = '';

	foreach ($parameter_list as $upload_param_key => $upload_param_value)
	{
		$upload_params_str .= urlencode($upload_param_key) . '=' . urlencode($upload_param_value) . ';';
	}
	
	return $upload_params_str;
}

function photocrati_upload_size_limit_text()
{
	$max_size = wp_max_upload_size();
	$max_text = wp_convert_bytes_to_hr($max_size);
	
	return $max_text;
}
	
?>
