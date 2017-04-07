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

	jQuery(".options").corner("top");

	jQuery("#view-theme").fancybox({
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'width'			:	1020, 
		'height'		:	450,
		'overlayShow'	:	true
	});
	
	jQuery("a#single_image").fancybox();
	
	jQuery('[id^=preset-]').on('click', function()
	{
		var answer = confirm('WARNING: This will overwrite your customizations. Please save customizations first!' + "\r\n\r\n" + 'FULLSCREEN? Please choose the "Page Without Content" page template for your home page.');
		
		if (answer){
            var post_data = {
              preset_name:   jQuery(this).attr('id'),
              nonce:         "<?php echo wp_create_nonce('set_photocrati_preset')?>",
              action:        "set_photocrati_preset"
            };

            jQuery.post(ajaxurl, post_data, function(response){
               window.location = window.location;
            });
		}
	});	

	jQuery("#update-pages").on('click', function()
	{
		var answer = confirm("Are you sure you want create the default page set now? This should only be done on a fresh install of Wordpress!")
		if (answer) {
			Photocrati_ThemeOptions_Admin.createDefaultPages('#page-options', '#msgpages');
		}
	});

	jQuery("#update-plugins").on('click', function()
	{
		var str2 = jQuery("#plugin-options").serialize();
		jQuery.ajax({type: "POST", url: "<?php bloginfo('template_url'); ?>/admin/scripts/setup-nggallery.php", data: str2, success: function(data)
		{
			jQuery("#msg2").html("NextGen Moved!");
			jQuery("#msg2")
				.fadeIn('slow')
				.animate({opacity: 1.0}, 2000)
				.fadeOut('slow');
			alert("Please activate the plugin now by going to Plugins and clicking the Activate link under NextGEN Gallery");
			window.location = window.location;
		}
		});
	});

	jQuery("#add_custom_name").focus(function()
	{
		jQuery(this).val('');
		jQuery(this).css('color','#333');
	});
	

	jQuery("#add_custom").click(function(){
        var preset_name = jQuery('#add_custom_name').val();

        // Validate request
		if(preset_name == 'Custom Setting Name...' || preset_name == '') {
			alert("Please type in a custom setting name");
		}
        else {
            var post_data = {
              preset_title:  preset_name,
              action:       'save_third_party_preset',
              update:       false,
              nonce:        "<?php echo wp_create_nonce('save_third_party_preset')?>"
            };
            jQuery.post(ajaxurl, post_data, function(response){
                try {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    if (response.error) {
                        alert(response.error);
                    }
                    else {
                        window.location = window.location;
                    }
                }
                catch (ex)
                {
                   alert(ex);
                }
            });
		}
	}).keypress(function(e){
        if (e.keyCode == 13) {
            $(this).trigger('click');
        }
    });

    jQuery('.export_theme_btn').click(function(e){
        e.preventDefault();
        var url = "<?php echo site_url() ?>";
        url += "?export_theme=1&nonce=<?php echo wp_create_nonce('export_theme')?>&preset_name="+jQuery(this).attr('id');
        window.location = url;
    });
	
	jQuery('[id^=custom-]').on('click', function()
	{
		var answer = confirm("Are you sure you want to use this custom style? This cannot be undone and will overwrite any customizations you have done thus far. You can save your current customizations before you proceed under Save Custom Themes.")
		if (answer){
            var post_data = {
                preset_name:   jQuery(this).attr('id').replace(/^custom-/, ''),
                nonce:         "<?php echo wp_create_nonce('set_photocrati_preset')?>",
                action:        "set_photocrati_preset"
            };

            jQuery.post(ajaxurl, post_data, function(response){
                window.location = window.location;
            });
		}
	});	
	
	jQuery('[id^=preset_delete_]').on('click', function()
	{
		var answer = confirm("Are you sure you want to delete your custom settings? This cannot be undone.")
		if (answer){
            var post_data = {
              preset_name:   jQuery(this).attr('id').replace('preset_delete_', ''),
              nonce:         "<?php echo wp_create_nonce('delete_photocrati_preset')?>",
              action:        "delete_photocrati_preset"
            };
            jQuery.post(ajaxurl, post_data, function(){
                window.location = window.location;
            });
		}
	});	
	
	jQuery('[id^=preset_update_]').on('click', function()
	{
		var answer = confirm("Are you sure you want to update your custom settings? This will overwrite the saved settings with the current customizations.")
		if (answer){
            var post_data = {
              preset_name: jQuery(this).attr('id').replace('preset_update_', ''),
              nonce:       "<?php echo wp_create_nonce('save_third_party_preset') ?>",
              action:        "save_third_party_preset",
              update: true
            };
            jQuery.post(ajaxurl, post_data, function(){
                window.location = window.location;
            });
		}
	});	
	
});
</script>
