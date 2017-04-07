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
});
</script>

<div id="admin-wrapper">

	<div id="header-left">
    <img src="<?php bloginfo('template_directory'); ?>/admin/images/ph-logo.gif" align="absmiddle" />
	</div>
    
    <div id="header-right">
    <?php theme_version(); ?>
    </div>
    
    <div id="container">
    
    	<div class="options">Photocrati Help & Support</div>
        
            <div class="sub-options"><span id="comments">Find out the answers to your questions and request assistance</span></div>
            <div class="option-content">
            
                <div id="option-container">
                
                <div class="left" style="width:100%">
                
                <p>
                If you have questions about your theme or require assistance please contact us:
                </p>
                
                <p>
                <strong>E-Mail Support:</strong> <a href="http://members.photocrati.com/contact-us" target="_blank">http://members.photocrati.com/contact-us</a>
                </p>
                <p>
                <strong>Support Forum & Member Area:</strong> <a href="http://members.photocrati.com" target="_blank">http://members.photocrati.com</a>
                </p>
                
                </div>
                
                </div>
                
        </div>
    
    </div>
    
</div>
