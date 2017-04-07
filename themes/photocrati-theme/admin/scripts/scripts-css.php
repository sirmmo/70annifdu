<?php

	if (!function_exists('current_user_can') || !current_user_can('manage_options'))
	{
		if (function_exists('wp_die'))
		{
			wp_die('Permission Denied.');
		}
		else
		{
			die('Permission Denied.');
		}
	}

?>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{

	jQuery(".options").uncorner();
	jQuery(".options").corner("top");
	
	jQuery("#dynamic_style").change(function()
	{
		var answer = false;
		
		if (jQuery("#dynamic_style").val() == 'NO') {
			answer = confirm("Are you sure you want to disable the dynamic styles and use the static style sheet? You can also enter custom styles the Custom CSS menu option.");
		} else {
			answer = confirm("Are you sure you want to enable the dynamic styles? This will over ride any changes you made to styles/style.css.")
		}
	
		if (answer) {
			Photocrati_ThemeOptions_Admin.submitStyleForm('#dynamic-styling');
		}
	});

	jQuery("#update-css").on('click', function()
	{
		Photocrati_ThemeOptions_Admin.submitStyleForm('#css-options', '#msg2');
	});

	jQuery("#update-css-modern").on('click', function()
	{
		Photocrati_ThemeOptions_Admin.submitStyleForm('#css-options-modern', '#msg-css-modern');
	});

	jQuery("#update-css-modern-desktop").on('click', function()
	{
		Photocrati_ThemeOptions_Admin.submitStyleForm('#css-options-modern-desktop', '#msg-css-modern-desktop');
	});

	jQuery("#update-css-modern-mobile").on('click', function()
	{
		Photocrati_ThemeOptions_Admin.submitStyleForm('#css-options-modern-mobile', '#msg-css-modern-mobile');
	});
	
});
</script>
