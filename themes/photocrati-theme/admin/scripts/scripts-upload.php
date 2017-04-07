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

include_once(dirname(__FILE__) . str_replace('/', DIRECTORY_SEPARATOR, '/../../functions/admin-upload.php'));

?>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
	
	jQuery('#fileUpload').uploadify({
	'uploader'  : '<?php bloginfo('template_url'); ?>/admin/js/uploadify.swf',
	'script'    : '<?php bloginfo('template_url'); ?>/admin/scripts/uploadify.php',
	'scriptData': { 'cookie' : escape(document.cookie + ';<?php echo photocrati_upload_parameter_string(); ?>'), 'session_id' : '<?php echo session_id(); ?>' },
	'auto'      : true,
	'buttonImg'	: '<?php echo photocrati_gallery_file_uri('image/upload.jpg'); ?>',
	'folder'    : '/images/uploads',
	'onComplete': function(event, queueID, fileObj, response, data) {
		 jQuery("#filesUploaded").html('<input type="hidden" id="bg_image" name="bg_image" value="'+fileObj.name+'">');
		 jQuery("#fileName")
		 		.fadeIn('slow')
				.html(fileObj.name+' uploaded successfully!<BR><em>Remember to save.</em>');
	}
	});
	
});
</script>
