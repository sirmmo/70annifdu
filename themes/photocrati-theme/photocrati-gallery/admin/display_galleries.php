<?php

if (!function_exists('current_user_can') || (!current_user_can('edit_pages') && !current_user_can('edit_posts')))
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
	
global $post;
global $wpdb;
?>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
    jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get_galleries.php'); ?>", data: 'post_id=<?php echo $_GET['post']; ?>', success: function(data)
        {
            jQuery('#display_galleries').html(data)
        }
    });
        
});
</script>

<div id="display_galleries"></div>
