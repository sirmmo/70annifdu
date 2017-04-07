<?php

// Minimize display of errors in Edit Gallery screen iframe
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);

$abspath = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
define('WP_USE_THEMES', false);
include_once($abspath.'/wp-config.php');
include_once($abspath.'/wp-load.php');
include_once($abspath.'/wp-includes/wp-db.php');

include_once(dirname(__FILE__) . str_replace('/', DIRECTORY_SEPARATOR, '/../../functions/admin-upload.php'));

global $wpdb;

	if (!current_user_can('edit_pages') && !current_user_can('edit_posts'))
	{
		wp_die('Permission Denied.');
	}

$post_id = intval(isset($post_id) ? $post_id : (isset($_GET['post_id']) ? $_GET['post_id'] : 0));
$SQL = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}photocrati_gallery_ids WHERE post_id = %d", $post_id);

$gals = $wpdb->get_results($SQL);
$i = 0;
$galarray = array();
foreach ($gals as $gals) {

	if($i == 0) {
		$galid = '';
	}

	if(!in_array($gals->gallery_id, $galarray)) {
		$galarray[] = $gals->gallery_id;
		$galid = $gals->gallery_id;
	}

	$i = $i + 1;
}

$gallast = end($galarray);
$gallastid = explode("_", $gallast);
$galnextid = (int)$gallastid[1]+1;

$upload_dir = wp_upload_dir();

?>
<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/admin/js/css_browser_selector.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory'); ?>/admin/admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory'); ?>/admin/css/uploadify.css" />
<script type="text/javascript" src="<?php echo includes_url('js/jquery/jquery.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/swfobject.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/json2.js')?>"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/admin/js/jquery.uploadify.v2.1.4.js"></script>
<script type="text/javascript">
jQuery.noConflict();
window.uploadify_error = function(file, errorCode, errorMsg, errorString){
    if (typeof(console) != 'undefined') {
        console.log(arguments);
    }
};
jQuery(document).ready(function()
{
	jQuery('input[name=gal_type]').change(function(){

		if(jQuery('input[name=gal_type]:checked').val() == '6' || jQuery('input[name=gal_type]:checked').val() == '7') {

			jQuery("#add_gallery").hide();
			jQuery("#add_album").show();
			jQuery("#album_desc").hide();

			jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/album-list-galleries.php'); ?>", data: 'post=<?php echo $post_id ?>', success: function(data)
			{
				jQuery('#add_album').html(data);
			}
			});

		} else {
			jQuery("#add_gallery").show();
			jQuery("#add_album").hide();
			jQuery("#album_desc").show();
		}

    });

    var galAspectRatioUpdate = function () {
    	var val = jQuery('#gal_aspect_ratio_list').val();

    	if (val == 'custom')
    	{
    		jQuery('#gal_aspect_ratio').show();
    		jQuery('#gal_aspect_ratio').change();
    	}
    	else
    	{
    		jQuery('#gal_aspect_ratio').hide();
    		jQuery('#gal_aspect_ratio_error').hide();
    		jQuery('#gal_aspect_ratio').val(val);
    	}
    };

    jQuery('#gal_aspect_ratio_list').change(function () {
    	galAspectRatioUpdate();
    });

    galAspectRatioUpdate();

    jQuery('#gal_aspect_ratio').change(function () {
    	var value = jQuery(this).val();
    	var errorElem = jQuery('#gal_aspect_ratio_error');

    	if (errorElem.size() < 1)
    	{
    		errorElem = jQuery('<span id="gal_aspect_ratio_error">&nbsp;</span>');
    		errorElem.css({
    			display: 'none',
    			width: 16,
    			height: 16,
    			padding: 8,
    			marginLeft: 5,
    			background: 'transparent url(\'<?php echo photocrati_gallery_file_uri('image/error.png'); ?>\') left center no-repeat'
    		});

    		errorElem.insertAfter(jQuery(this));
    	}

    	var valRatio = parseFloat(value);

    	if (isNaN(valRatio) || valRatio.toString() != value)
    	{
    		var error = 'Error! Only decimal values can be used to specify the aspect ratio.';

    		jQuery(this).attr('title', error);
    		jQuery(this).addClass('wrong-value');

		  	errorElem.attr('title', error);
		  	errorElem.show();
    	}
    	else
    	{
    		jQuery(this).attr('title', '');
    		jQuery(this).removeClass('wrong-value');

		  	errorElem.attr('title', '');
		  	errorElem.hide();
    	}
    }).keypress(function () {
    	//jQuery(this).change();
    });

    jQuery('#gal_aspect_ratio').change();

	var nums = 1000;

	jQuery('#upload_images').uploadify({
	'uploader'  : '<?php bloginfo('template_url'); ?>/admin/js/uploadify.swf',
	'script'    : '<?php bloginfo('template_url'); ?>/admin/scripts/uploadify_gallery.php',
	'scriptData': { 'cookie' : escape(document.cookie + ';<?php echo photocrati_upload_parameter_string(); ?>'), 'session_id' : '<?php echo session_id(); ?>' },
	'buttonImg'	: '<?php echo photocrati_gallery_file_uri('image/upload_gallery.jpg'); ?>',
	'cancelImg' : '<?php bloginfo('template_url'); ?>/admin/images/cancel_gallery.gif',
	'folder'    : '/galleries/post-<?php echo $post_id ?>/',
	'auto'      : true,
	'multi'     : true,
	'queueSizeLimit' : 99,
	'simUploadLimit' : 1,
	'fileExt' : '*.jpg;*.jpeg;*.gif;*.png;*.tiff',
	'fileDesc' : 'Images (.jpg, .jpeg, .gif, .png, .tiff)',
	'sizeLimit'	: <?php echo wp_max_upload_size(); ?>,
    onError: window.uploadify_error,
    onUploadError: window.uploadify_error,
	'onProgress': function(event, queueID, fileObj, response, data) {
		var browserName = navigator.appName;
		if (browserName == 'Microsoft Internet Explorer') {
			jQuery("#msggallery3").html("Uploading - Please Wait");
			jQuery("#msggallery3")
				.fadeIn('slow')
				.animate({opacity: 1.0}, 2000);
		}
	},
	'onComplete': function(event, queueID, fileObj, response, data) {
		var fname = response;
        var browserName = navigator.appName;
		var iptc = response;
		var eiptc = iptc.split("|");

		jQuery("#filesUploaded").append('<input type="hidden" id="gallery_image_'+nums+'" name="gallery_image_'+nums+'" value="'+eiptc[0]+'"><input type="hidden" id="image_alt_'+nums+'" name="image_alt_'+nums+'" value="'+eiptc[1]+'"><input type="hidden" id="image_desc_'+nums+'" name="image_desc_'+nums+'" value="'+eiptc[2]+'">');

        if (browserName == 'Microsoft Internet Explorer') {
			<?php if(!function_exists('gd_info')) { ?>
				jQuery("#fileName")
				.fadeIn('slow')
				.append('<div id="sortable_'+nums+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id ?>/'+eiptc[0].replace("%","%25")+'" align="absmiddle" style="height:90px;width:130px;"></div></div>');
			<?php } else { ?>
				jQuery("#fileName")
				.fadeIn('slow')
				.append('<div id="sortable_'+nums+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id ?>/thumbnails/'+eiptc[0].replace("%","%25")+'" align="absmiddle" style="height:90px;width:130px;"></div></div>');
			<?php } ?>
		} else {
            <?php if(!function_exists('gd_info')) { ?>
				jQuery("#fileName")
				.fadeIn('slow')
				.append('<div id="sortable_'+nums+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id ?>/'+eiptc[0].replace("%","%25")+'" align="absmiddle"></div></div>');
			<?php } else { ?>
				jQuery("#fileName")
				.fadeIn('slow')
				.append('<div id="sortable_'+nums+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id ?>/thumbnails/'+eiptc[0].replace("%","%25")+'" align="absmiddle"></div></div>');
			<?php } ?>
	    }

        // hide all thumbnails
		jQuery('#gallery_uploaded img').css('opacity', 0).each(function() {
			// when img is loaded, animate opacity to 1
			if (this.complete || this.readyState == 'complete') {
				jQuery(this).animate({'opacity': 1}, 600);
				jQuery(this).parent().css('background','#F1F1F1');
			} else {
				jQuery(this).load(function() {
					jQuery(this).animate({'opacity': 1}, 600);
					jQuery(this).parent().css('background','#F1F1F1');
				});
			}
		});
		nums++;
		//alert(nums);
	},
	'onAllComplete': function(event, queueID, fileObj, response, data) {
		jQuery("#save_top")
		 	.css('display','block');
        jQuery("#save_bottom")
		 	.css('display','block');
		jQuery("#msggallery3").css('background','none');
        jQuery("#msggallery3").html("");
		jQuery("#msggallery3").hide();
	}
	});

    jQuery('#import_nexgen').on('click', function(){
		jQuery('#import_nexgen_window').show();
		jQuery('#import_nexgen_window').html("<img src='<?php bloginfo('template_url'); ?>/admin/images/ajax-loader.gif'>");
		jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get-nextgen-image.php'); ?>", data: 'post=<?php echo $post_id ?>', success: function(data)
		{
			jQuery('#import_nexgen_window').html(data);
		}
		});
    });

    jQuery('#import_nexgen_images').on('click', function(){
        var answer = confirm("Are you sure you want to import these NextGen images?")
        if (answer){
            jQuery('#import_nexgen_window').hide();
            jQuery('[id^=nggallery_image_]').each(function(index) {

                if(jQuery(this).is(':checked')) {

                var currentId = jQuery(this).attr('id');
                var fileName = jQuery(this).val();
                var fileTitle = jQuery(this).attr('title');
                var fileDesc = jQuery(this).attr('alt');
                jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/import-nextgen-images.php'); ?>", data: 'image='+fileName.replace("&","%26")+'&path='+jQuery('#nggallery_path_'+currentId.substr(16)).val()+'&gallery_id=<?php echo $post_id ?>', success: function(data)
				{

                    jQuery("#"+currentId).attr('checked', false);
                    jQuery("#"+currentId).attr('disabled', true);
                    var currsize = jQuery('[id^=gallery_image_]').size();
                    var nextsize = currsize++;
                    jQuery("#filesUploaded").append('<input type="hidden" id="gallery_image_'+nextsize+'" name="gallery_image_'+nextsize+'" value="'+fileName.replace("&","&amp;")+'"><input type="hidden" id="image_order_'+nextsize+'" name="image_order_'+nextsize+'" value="'+nextsize+'"><input type="hidden" id="image_alt_'+nextsize+'" name="image_alt_'+nextsize+'" value="'+fileTitle+'"><input type="hidden" id="image_desc_'+nextsize+'" name="image_desc_'+nextsize+'" value="'+fileDesc+'">');
                    jQuery("#fileName")
                    .fadeIn('slow')
                    .append('<div id="sortable_'+nextsize+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id ?>/thumbnails/'+fileName.replace("%","%25").replace("&","&amp;")+'" align="absmiddle">')
                    .append('</div></div>');

                    jQuery("#save_top").css('display','block');
                    jQuery("#save_bottom").css('display','block');

                }
				});

                }

            });
        }
    });

    jQuery('#import_photocrati').on('click', function(){
		jQuery('#import_photocrati_window').show();
		jQuery('#import_photocrati_window').html("<img src='<?php bloginfo('template_url'); ?>/admin/images/ajax-loader.gif'>");
		jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get-photocrati-image.php'); ?>", data: 'post=<?php echo $post_id ?>', success: function(data)
		{
			jQuery('#import_photocrati_window').html(data);
		}
		});
    });

	jQuery('[id^=save_button_]').on('click', function(){

		<?php include('upload-common.php') ?>

		function insertInPost(gallery) {
			// IE support
			if (parent.document.post.content.selection) {
				parent.document.post.content.focus();
				sel = parent.document.post.content.selection.createRange();
				sel.text = gallery;
			}
			// MOZILLA/NETSCAPE support
			else if (parent.document.post.content.selectionStart || parent.document.post.content.selectionStart == 0) {
				var startPos = parent.document.post.content.selectionStart;
				var endPos = parent.document.post.content.selectionEnd;
				parent.document.post.content.value = parent.document.post.content.value.substring(0, startPos) + gallery + parent.document.post.content.value.substring(endPos,parent.document.post.content.value.length);
			} else {
				parent.document.post.content.value += gallery;
			}

		}

		return checkform();
		function checkform()
		{
			if (document.create_gallery.gal_title.value == '')
			{
				alert('The gallery title is required');
				return false;
			}
			jQuery("#msggallery1").html("Saving Gallery - Please Wait");
			jQuery("#msggallery1")
				.fadeIn('slow')
				.animate({opacity: 1.0}, 2000);
			jQuery("#msggallery2").html("Saving Gallery - Please Wait");
			jQuery("#msggallery2")
				.fadeIn('slow')
				.animate({opacity: 1.0}, 2000);
			saveGallery();
		}

	});

	jQuery('#delete_button').on('click', function(){

		var answer = confirm("Are you sure you want to delete this gallery? This cannot be undone!")
		if (answer){

			jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/delete-gallery.php'); ?>", data: 'post_id=<?php echo $post_id ?>', success: function(data)
				{
					window.location = window.location;
				}
			});

		}

	});

});
</script>

<div id="gallery-wrapper">

<form name="create_gallery" id="create_gallery" method="post" onSubmit="">

	<h1 class="titles">Add a Photocrati Gallery / Album</h1>

    <div id="gallery_title">

    	<h2>Title:</h2>

    	<input type="text" name="gal_title" style="width:600px;">

		<div id="album_desc">
		<h2>Gallery Description (for list style Album):</h2>

    	<textarea name="gal_desc" id="gal_desc" style="width:600px;height:50px"></textarea>
		</div>

    </div>

    <div id="gallery_types">

    	<h2>Gallery Type:</h2>

    	<div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type" value="1" checked="checked" /> Slideshow<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_animated.jpg'); ?>" id="gal_img_1" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type" value="2" /> Blog Style<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_sstack.jpg'); ?>" id="gal_img_2" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type" value="3" /> Filmstrip<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_hfilm.jpg'); ?>" id="gal_img_3" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type" value="4" /> Thumbnails<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_thumbs.jpg'); ?>" id="gal_img_4" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type" value="5" /> E-Commerce<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_ecomm.jpg'); ?>" id="gal_img_5" /></div>

    </div>

    <p><i>You can customize the global look of these galleries under <b>Theme Options / Gallery Settings</b>. Albums are a list of existing
	galleries. You must have already created some galleries to use this feature.</i></p>

    <div id="gallery_types">

    	<h2>Album Type:</h2>

		<div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type" value="6" /> Album - List<BR /><img src="<?php echo photocrati_gallery_file_uri('image/album_list.jpg'); ?>" id="gal_img_6" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type" value="7" /> Album - Grid<BR /><img src="<?php echo photocrati_gallery_file_uri('image/album_grid.jpg'); ?>" id="gal_img_7" /></div>
		<div class="types_inner"></div>
		<div class="types_inner"></div>
		<div class="types_inner"></div>

    </div>

    <div id="gallery_title" style="float:left;clear:both;margin-bottom:15px;width:99%;">

		<div style="float:left;width:48%;margin-right:2%;">
			<h2>Gallery Height:</h2>

			<input type="text" name="gal_height" id="gal_height" size="6" style="margin-bottom:5px;" /> px<BR>
			<p style="color:#ee3311"><b>IMPORTANT NOTES:</b> The Gallery Height setting is deprecated and is going to be removed in the next version. Please use the aspect ratio setting instead.</p>
		</div>

		<div style="float:left;width:48%;">
			<h2>Aspect Ratio:</h2>

			<select name="gal_aspect_ratio_list" id="gal_aspect_ratio_list" style="margin-bottom:5px;margin-right:4px;padding:2px;">
				<?php
					$galaspect = '1.5';
					$galaspect_list = photocrati_gallery_aspect_ratio_list();
					$galaspect_selected = false;

					foreach ($galaspect_list as $galaspect_key => $galaspect_name)
					{
						$option_selected = ($galaspect_key == $galaspect);

						if ($option_selected)
						{
							$galaspect_selected = $option_selected;
							$option_selected = ' selected="selected"';
						}

						echo '<option value="' . $galaspect_key . '"' . $option_selected . '>' . $galaspect_name . '</option>';
					}

					if (!$galaspect_selected)
					{
						$option_selected = ' selected="selected"';
					}

					echo '<option value="custom"' . $option_selected . '>Custom</option>';
				?>
			</select>
			<input type="text" name="gal_aspect_ratio" id="gal_aspect_ratio" size="6" value="<?php echo $galaspect; ?>" style="display:none;margin-bottom:5px;" /><br/>
			<p>Select the aspect ratio for the frame where images in this gallery will be displayed.</p>
		</div>
    </div>

    <div id="gallery_warnings" style="clear:both">
		<?php if(!function_exists('gd_info')) { ?>
		<p style="color:#FF0000;font-style:italic;">
		<b>Warning:</b> Your host does not have the PHP GD library installed!
		<BR>Gallery thumbnails will be generated on the fly which may affect loading speed. Contact your host for more info.
		</p>
		<?php } ?>

		<p>A maximum file size of <?php echo photocrati_upload_size_limit_text(); ?> per photo applies. It is however recommended that you resize your images to be under 2MB for performance reasons. <b>There is a maximum upload limit of 99 images at one time</b>.</p>
    </div>

	<div id="add_gallery">

	<div style="float:left;width:18%;">
	<?php
		$uploads = wp_upload_dir();
		$load = $uploads['basedir'] . str_replace('/', DIRECTORY_SEPARATOR, '/galleries/');

		if (!is_writable($load)) {
			echo '<p class="warning"><b>The galleries directory must be writable</b>!<BR><em>See <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">the Codex</a> for more information.</em></p>';
		} else {
			echo '<input type="button" id="upload_images" value="Add Images">';
		}
	?>
    </div>
    <div style="float:left;width:18%;">
    <input type="image" src="<?php echo photocrati_gallery_file_uri('image/gallery_nexgen.jpg'); ?>" id="import_nexgen" value="Import from NextGen" onclick="return false;" />
    </div>
    <div style="float:left;width:18%;">
    <input type="image" src="<?php echo photocrati_gallery_file_uri('image/gallery_photocrati.jpg'); ?>" id="import_photocrati" value="Import from Photocrati" onclick="return false;" />
    </div>
    <div id="save_top" style="float:left;width:17%;display:none;">
    <input type="image" src="<?php echo photocrati_gallery_file_uri('image/save_gallery.jpg'); ?>" id="save_button_1" value="Save Gallery" onclick="return false;" />
    </div>
    <div id="msggallery1"></div>
	<div id="msggallery3"></div>

    <div style="width:100%;clear:both;height:5px;"></div>

    <div id="import_nexgen_window" style="display:none;">

    </div>

    <div id="import_photocrati_window" style="display:none;">

    </div>

    <div id="filesUploaded"></div><div id="fileName"></div>

    <div style="width:100%;padding-top:20px;cursor:pointer;clear:both;"></div>
    <div id="save_bottom" style="float:left;cursor:pointer;display:none;">
    <input type="image" src="<?php echo photocrati_gallery_file_uri('image/save_gallery.jpg'); ?>" id="save_button_2" value="Save Gallery" onclick="return false;" />
    </div>
    <div id="msggallery2"></div>

	</div>

	<div id="add_album"></div>

</form>

</div>
