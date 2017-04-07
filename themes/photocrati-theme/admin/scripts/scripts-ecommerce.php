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

$nonce = wp_create_nonce('update_ecommerce_options');
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
	
	jQuery("#update-global").on('click', function()
	{
		var str2 = jQuery("#ecommerce-options").serialize();
        var data = {
            nonce:  '<?php echo $nonce ?>',
            action: 'update_photocrati_ecommerce',
            data:   str2
        };
        jQuery.post(ajaxurl, data, function(response){
            jQuery("#msg").html("Settings Saved");
            jQuery("#msg")
                .fadeIn('slow')
                .animate({opacity: 1.0}, 2000)
                .fadeOut('slow');
        });
	});
	
});
</script>
