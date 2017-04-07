<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
$post_id = intval(isset($_GET['post']) ? $_GET['post'] : (isset($_GET['post_id'])? $_GET['post_id'] : 0));
$upload_dir = wp_upload_dir();

$wp_curr_ver = get_bloginfo('version');

?>
<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/admin/js/css_browser_selector.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory'); ?>/admin/admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory'); ?>/admin/css/uploadify.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory'); ?>/admin/js/ui-lightness/jquery-ui-1.8.2.custom.css" />
<script type="text/javascript" src="<?php echo includes_url('js/jquery/jquery.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/json2.js')?>"></script>
<?php if (version_compare($wp_curr_ver, '4.1', '>=')): ?>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/core.min.js ')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/widget.min.js ')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/mouse.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/draggable.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/sortable.min.js')?>"></script>
<?php else: ?>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.core.min.js ')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.widget.min.js ')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.mouse.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.draggable.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.sortable.min.js')?>"></script>
<?php endif ?>
<script type="text/javascript" src="<?php echo includes_url('js/swfobject.js')?>"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/admin/js/jquery.uploadify.v2.1.4.js"></script>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{

	jQuery('input[name=gal_type]').change(function(){

		if(jQuery('input[name=gal_type]:checked').val() == '6' || jQuery('input[name=gal_type]:checked').val() == '7') {

			jQuery("#album_desc").hide();

		} else {
			jQuery("#album_desc").show();
		}

    });

	if(jQuery('input[name=gal_type]:checked').val() == '6' || jQuery('input[name=gal_type]:checked').val() == '7') {

		jQuery("#add_gallery").hide();
		jQuery("#add_album").show();
		jQuery("#album_desc").hide();

		jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/album-list-galleries.php'); ?>", data: 'edit=true&post=<?php echo $post_id; ?>&gallery_id=<?php echo $_GET['gallery_id']; ?>', success: function(data)
		{
			jQuery('#add_album').html(data);
		}
		});

	} else {
		jQuery("#add_gallery").show();
		jQuery("#add_album").hide();
		jQuery("#album_desc").show();
	}


	var nums = 1000;

    var sort2 = 1;
    jQuery('[id^=image_order_]').each(function(intIndex,objValue){
		var currentId = jQuery(this).attr('id');
        jQuery(this).val(sort2);
        sort2++;
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
});
</script>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{

	jQuery("#sortable").sortable({
		revert: true,
        opacity: 0.75,
		appendTo: '#widget_drag',
        update: function(event, ui) {
            var info = jQuery(this).sortable("toArray");
            var sort = 1;
            jQuery.each(
                info,
                    function( intIndex, objValue ){
                        jQuery('#image_order_'+objValue.substr(9)).val(sort);
                        sort++;
                    }
            );
        }
	});

	jQuery("#sortable .gallery_uploaded").disableSelection();
});
</script>

<script type="text/javascript">
jQuery.noConflict();
window.uploadify_error = function(file, errorCode, errorMsg, errorString){
  if (typeof(console) != 'undefined') {
      console.log(arguments);
  }
};
jQuery(document).ready(function()
{
    jQuery('#select_all').on('click', function(e){
        e.preventDefault();
        jQuery('.ecomm_options_master').val('ON').change();
    });

    jQuery('#select_none').on('click', function(e){
        e.preventDefault();
        jQuery('.ecomm_options_master').val('OFF').change();
    });

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
				.append('<div id="sortable_'+nums+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id; ?>/'+eiptc[0].replace("%","%25")+'" align="absmiddle" style="height:90px;width:130px;"></div></div>');
			<?php } else { ?>
				jQuery("#fileName")
				.fadeIn('slow')
				.append('<div id="sortable_'+nums+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id; ?>/thumbnails/'+eiptc[0].replace("%","%25")+'" align="absmiddle" style="height:90px;width:130px;"></div></div>');
			<?php } ?>
		} else {
            <?php if(!function_exists('gd_info')) { ?>
				jQuery("#fileName")
				.fadeIn('slow')
				.append('<div id="sortable_'+nums+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id; ?>/'+eiptc[0].replace("%","%25")+'" align="absmiddle"></div></div>');
			<?php } else { ?>
				jQuery("#fileName")
				.fadeIn('slow')
				.append('<div id="sortable_'+nums+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id; ?>/thumbnails/'+eiptc[0].replace("%","%25")+'" align="absmiddle"></div></div>');
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

    jQuery('#delete_selected').on('click', function(){
		var checked = jQuery("[id^=delete_img_]:checked").length;
		var cntchk = 0;
        var answer = confirm("Are you sure you want to delete the selected images? This can't be undone!")
        if (answer){

            jQuery('[id^=delete_img_]:checked').each(function(index) {

				var currentId = jQuery(this).attr('id');

				setTimeout(function(){

					jQuery("#msggallery1").html("Deleting Image - Please Wait");
					jQuery("#msggallery1")
						.fadeIn('slow')
						.animate({opacity: 1.0}, 2000);
						jQuery("#msggallery2").html("Deleting Image - Please Wait");
					jQuery("#msggallery2")
						.fadeIn('slow')
						.animate({opacity: 1.0}, 2000);

					jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/delete-gallery-images.php'); ?>", data: "gallery_id=<?php echo $post_id; ?>&img_id="+currentId.substr(11), success: function(data)
					{
						jQuery('#gallery_uploaded_'+currentId.substr(11)).hide(250);

						cntchk++;

						if(cntchk == checked) {
						location.reload();
						}

					}
					});

				},index*1000);

            });
        }
    });

    var fnGalleryOrder = function(orderField) {
			var sortList = jQuery('[id^=sortable_]').get();
			var sortParent = jQuery(sortList[0]).parent();

			jQuery("#msggallery1").html("Sorting... - Please Wait");
			jQuery("#msggallery1")
				.fadeIn(50);
			jQuery("#msggallery2").html("Sorting... - Please Wait");
			jQuery("#msggallery2")
				.fadeIn(50);

			sortList.sort(function(item1, item2) {
				  var imageId1 = jQuery(item1).attr('id').substr(9);
				  var imageId2 = jQuery(item2).attr('id').substr(9);
				  var imageName1 = jQuery('#gallery_image_' + imageId1).val().toUpperCase();
				  var imageName2 = jQuery('#gallery_image_' + imageId2).val().toUpperCase();

				  if (orderField == 'title')
				  {
						imageName1 = jQuery('#image_alt_' + imageId1).val().toUpperCase();
						imageName2 = jQuery('#image_alt_' + imageId2).val().toUpperCase();
				  }

				  return imageName1.localeCompare(imageName2);
			});

			jQuery.each(sortList, function(idx, item) {
				sortParent.append(item);
			});

			var sort2 = 1;
			jQuery('[id^=image_order_]').each(function(intIndex, objValue){
				var currentId = jQuery(this).attr('id');
				  jQuery(this).val(sort2);
				  sort2++;
			});

			jQuery("#msggallery1")
				.fadeOut('fast');
			jQuery("#msggallery2")
				.fadeOut('fast');
    };

    jQuery('#order_fname').on('click', function() {
    	fnGalleryOrder('filename');
    });

    jQuery('#order_title').on('click', function() {
    	fnGalleryOrder('title');
    });

    jQuery('#order_invert').on('click', function() {
			var sortList = jQuery('[id^=sortable_]').get();
			var sortParent = jQuery(sortList[0]).parent();

			jQuery("#msggallery1").html("Sorting... - Please Wait");
			jQuery("#msggallery1")
				.fadeIn(50);
			jQuery("#msggallery2").html("Sorting... - Please Wait");
			jQuery("#msggallery2")
				.fadeIn(50);

			sortList.sort(function(item1, item2) {
				  var imageId1 = jQuery(item1).attr('id').substr(9);
				  var imageId2 = jQuery(item2).attr('id').substr(9);
				  var imageOrd1 = parseInt(jQuery('#image_order_' + imageId1).val());
				  var imageOrd2 = parseInt(jQuery('#image_order_' + imageId2).val());

				  if (imageOrd1 > imageOrd2)
				  	return -1;

				  if (imageOrd1 < imageOrd2)
				  	return 1;

				  return 0;
			});

			jQuery.each(sortList, function(idx, item) {
				sortParent.append(item);
			});

			var sort2 = 1;
			jQuery('[id^=image_order_]').each(function(intIndex, objValue){
				var currentId = jQuery(this).attr('id');
				  jQuery(this).val(sort2);
				  sort2++;
			});

			jQuery("#msggallery1")
				.fadeOut('fast');
			jQuery("#msggallery2")
				.fadeOut('fast');
    });

		jQuery('[id^=image_order_]').keydown(function (event) {
			jQuery(event.currentTarget).addClass('photocrati-edited');

			return true;
		});

    jQuery('#meta_button').on('click', function(){
        jQuery('.ui-state-default').css('clear','both');
        jQuery('.ui-state-default').css('width','100%');
        jQuery('[id^=gallery_meta_]').show();
        jQuery('[id^=gallery_meta_]').css('margin-top','8px');
        jQuery('[id^=gallery_uploaded_]').css('margin-top','8px');
        jQuery('#meta_button').hide();
        jQuery('#meta_button_close').show();
        jQuery('[id^=gallery_options_]').hide();
        jQuery('#ecommerce_button').show();
        jQuery('#ecommerce_button_close').hide();
        jQuery('#ecommerce_note').hide();
    });

    jQuery('#meta_button_close').on('click', function(){
        jQuery('.ui-state-default').css('clear','none');
        jQuery('.ui-state-default').css('width','auto');
        jQuery('[id^=gallery_meta_]').hide();
        jQuery('[id^=gallery_uploaded_]').css('margin-top','2px');
        jQuery('#meta_button').show();
        jQuery('#meta_button_close').hide();
    });

    jQuery('#ecommerce_button').on('click', function(){
        jQuery('.ui-state-default').css('clear','both');
        jQuery('.ui-state-default').css('width','100%');
        jQuery('[id^=gallery_options_]').show();
        jQuery('[id^=gallery_options_]').css('margin-top','8px');
        jQuery('[id^=gallery_uploaded_]').css('margin-top','8px');
        jQuery('#ecommerce_button').hide();
        jQuery('#ecommerce_button_close').show();
        jQuery('[id^=gallery_meta_]').hide();
        jQuery('#meta_button').show();
        jQuery('#meta_button_close').hide();
        jQuery('#ecommerce_note').show();
    });

    jQuery('#ecommerce_button_close').on('click', function(){
        jQuery('.ui-state-default').css('clear','none');
        jQuery('.ui-state-default').css('width','auto');
        jQuery('[id^=gallery_options_]').hide();
        jQuery('[id^=gallery_uploaded_]').css('margin-top','2px');
        jQuery('#ecommerce_button').show();
        jQuery('#ecommerce_button_close').hide();
        jQuery('#ecommerce_note').hide();
    });

	jQuery('[id^=ecomm_options_master_]').change(function(){

		var currentId = jQuery(this).attr('id');
        var enabled = jQuery(this).val() == 'ON';
        jQuery('input[id^="ecomm_options_'+currentId.substr(21)+'"]').each(function(){
            if (enabled)
                jQuery(this).attr('checked', 'checked').prop('checked', true);
            else
                jQuery(this).removeAttr('checked').prop('checked', false);
        });
	});

    jQuery('#import_nexgen').on('click', function(e){
        e.preventDefault();
		jQuery('#import_nexgen_window').show();
		jQuery('#import_nexgen_window').html("<img src='<?php bloginfo('template_url'); ?>/admin/images/ajax-loader.gif'>");
		jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get-nextgen-image.php'); ?>", data: 'post=<?php echo $post_id; ?>', success: function(data)
		{
			jQuery('#import_nexgen_window').html(data);
		}
		});
    });

    jQuery('#import_photocrati').on('click', function(){
		jQuery('#import_photocrati_window').show();
		jQuery('#import_photocrati_window').html("<img src='<?php bloginfo('template_url'); ?>/admin/images/ajax-loader.gif'>");
		jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get-photocrati-image.php'); ?>", data: 'post=<?php echo $post_id; ?>', success: function(data)
		{
			jQuery('#import_photocrati_window').html(data);
		}
		});
    });

    jQuery('#import_photocrati_images').on('click', function(){
        var answer = confirm("Are you sure you want to import these Photocrati images?")
        if (answer){
            jQuery('#import_photocrati_window').hide();
            jQuery('[id^=photocrati_image_]').each(function(index) {

                if(jQuery(this).is(':checked')) {

                var currentId = jQuery(this).attr('id');
                var fileName = jQuery(this).val();
                var fileTitle = jQuery(this).attr('title');
                var fileDesc = jQuery(this).attr('alt');
                jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/import-photocrati-images.php'); ?>", data: 'image='+fileName.replace("&","%26")+'&path='+jQuery('#photocrati_path_'+currentId.substr(17)).val()+'&gallery_id=<?php echo $post_id; ?>', success: function(data)
				{

                    jQuery("#"+currentId).attr('checked', false);
                    jQuery("#"+currentId).attr('disabled', true);
                    var currsize = jQuery('[id^=gallery_image_]').size();
                    var nextsize = currsize++;
                    jQuery("#filesUploaded").append('<input type="hidden" id="gallery_image_'+nextsize+'" name="gallery_image_'+nextsize+'" value="'+fileName.replace("&","&amp;")+'"><input type="hidden" id="image_order_'+nextsize+'" name="image_order_'+nextsize+'" value="'+nextsize+'"><input type="hidden" id="image_alt_'+nextsize+'" name="image_alt_'+nextsize+'" value="'+fileTitle+'"><input type="hidden" id="image_desc_'+nextsize+'" name="image_desc_'+nextsize+'" value="'+fileDesc+'">');
                    jQuery("#fileName")
                    .fadeIn('slow')
                    .append('<div id="sortable_'+nextsize+'"><div id="gallery_uploaded"><img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id; ?>/thumbnails/'+fileName.replace("%","%25").replace("&","&amp;")+'" align="absmiddle">')
                    .append('</div></div>');

                    jQuery("#save_top").css('display','block');
                    jQuery("#save_bottom").css('display','block');

                }
				});

                }

            });
        }
    });

	jQuery('[id^=save_button_]').on('click', function(){

        function replacePost(gallery) {

            parent.document.post.content.value = gallery;

		}

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

		<?php include('upload-common.php'); ?>

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
			//parent.jQuery('#edButtonHTML').click();
			var str = parent.jQuery('#the_content_<?php echo $_GET['gallery_id']; ?>').val();
			var pattern = /(<img([^<]*)id="phgallery-<?php echo $_GET['gallery_id']; ?> ([^<]*)"[^<]*>)/;
			var repstr = str.replace(pattern, '');
			parent.jQuery('#the_content_<?php echo $_GET['gallery_id']; ?>').val(repstr);
			if (parent.tinyMCE.activeEditor != null && parent.tinyMCE.activeEditor.isHidden() == false) {
				var html = parent.tinyMCE.activeEditor.getContent();
				var string_replace = html.replace(pattern,'');
				//parent.tinyMCE.execCommand('mceSetContent',false,string_replace);
				parent.tinyMCE.activeEditor.setContent(string_replace);
				//parent.tinyMCE.execCommand('mceInsertContent',false,string_replace);
			} else {
				replacePost(parent.jQuery('#the_content_<?php echo $_GET['gallery_id']; ?>').val());
			}
			//replacePost(parent.jQuery('#the_content_<?php echo $_GET['gallery_id']; ?>').val());
			saveGallery();
			//parent.jQuery('#edButtonPreview').click();
		}

	});

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

});
</script>

<div id="gallery-wrapper">

<form name="create_gallery" id="create_gallery" method="post">

	<?php
	$galinfo = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE gallery_id = '" . $wpdb->escape($_GET['gallery_id']) . "'");
	foreach ($galinfo as $galinfo) {
		$galtype = $galinfo->gal_type;
        $galtitle = $galinfo->gal_title;
        $galheight = $galinfo->gal_height;
        $galaspect = $galinfo->gal_aspect_ratio;
        $galdesc = $galinfo->gal_desc;
        $galnumber = $galinfo->gallery_id;
	}
    $galnumber = explode("_", $galnumber);

	if ($galaspect == null)
	{
		$galaspect = '1.5';
	}
	?>

	<?php if($galtype == 6 || $galtype == 7) { ?>

	<h1 class="titles">Edit a Photocrati Album</h1>

	<?php } else { ?>

	<h1 class="titles">Edit a Photocrati Gallery</h1>

	<?php } ?>

    <div id="gallery_title">

		<?php if($galtype == 6 || $galtype == 7) { ?>

    	<h2>Album Title:</h2>

		<?php } else { ?>

    	<h2>Gallery Title:</h2>

		<?php } ?>

    	<input type="text" name="gal_title" style="width:600px;" value="<?php echo stripslashes($galtitle); ?>">

		<div id="album_desc"<?php if($galtype == 6 || $galtype == 7) { echo 'style="display:none;"'; } ?>>
		<h2>Gallery Description (for list style Album):</h2>

    	<textarea name="gal_desc" id="gal_desc" style="width:600px;height:50px"><?php echo stripslashes($galdesc); ?></textarea>
		</div>

    </div>

	<?php if($galtype == 6 || $galtype == 7) { ?>

    <div id="gallery_types">

    	<h2>Album Type:</h2>

        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type_6" value="6" <?php if($galtype == 6) { echo 'checked="checked"'; } ?> /> Album - List<BR /><img src="<?php echo photocrati_gallery_file_uri('image/album_list.jpg'); ?>" id="gal_img_6" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type_7" value="7" <?php if($galtype == 7) { echo 'checked="checked"'; } ?> /> Album - Grid<BR /><img src="<?php echo photocrati_gallery_file_uri('image/album_grid.jpg'); ?>" id="gal_img_7" /></div>
		<div class="types_inner"></div>
		<div class="types_inner"></div>
		<div class="types_inner"></div>

    </div>

	<?php } else { ?>

    <div id="gallery_types">

    <h2>Gallery Type:</h2>

    	<div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type_1" value="1" <?php if($galtype == 1) { echo 'checked="checked"'; } ?> /> Slideshow<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_animated.jpg'); ?>" id="gal_img_1" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type_2" value="2" <?php if($galtype == 2) { echo 'checked="checked"'; } ?> /> Blog Style<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_sstack.jpg'); ?>" id="gal_img_2" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type_3" value="3" <?php if($galtype == 3) { echo 'checked="checked"'; } ?> /> Filmstrip<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_hfilm.jpg'); ?>" id="gal_img_3" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type_4" value="4" <?php if($galtype == 4) { echo 'checked="checked"'; } ?> /> Thumbnails<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_thumbs.jpg'); ?>" id="gal_img_4" /></div>
        <div class="types_inner"><input type="radio" name="gal_type" class="gal_type" id="gal_type_5" value="5" <?php if($galtype == 5) { echo 'checked="checked"'; } ?> /> E-Commerce<BR /><img src="<?php echo photocrati_gallery_file_uri('image/gallery_ecomm.jpg'); ?>" id="gal_img_5" /></div>

    </div>

    <p><i>You can customize the global look of these galleries under <b>Theme Options / Gallery Settings</b>. Albums are a list of existing
	galleries. You must have already created some galleries to use this feature.</i></p>

    <div id="gallery_title" style="float:left;clear:both;margin-bottom:10px;width:99%;">

		<div style="float:left;width:48%;margin-right:2%;">
			<h2>Gallery Height:</h2>

			<input type="text" name="gal_height" id="gal_height" size="6" value="<?php echo $galheight; ?>" style="margin-bottom:5px;" /> px<BR>
			<p style="color:#ee3311"><b>IMPORTANT NOTES:</b> The Gallery Height setting is deprecated and is going to be removed in the next version. Please use the aspect ratio setting instead.</p>
		</div>

		<div style="float:left;width:48%;">
			<h2>Aspect Ratio:</h2>

			<select name="gal_aspect_ratio_list" id="gal_aspect_ratio_list" value="<?php echo $galaspect; ?>" style="margin-bottom:5px;margin-right:4px;padding:2px;">
				<?php
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

	<?php } ?>

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

	<div class="pc_actions">
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
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/gallery_nexgen.jpg'); ?>" id="import_nexgen" value="Import from NexGen" onclick="return false;" />
		</div>
		<div style="float:left;width:18%;">
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/gallery_photocrati.jpg'); ?>" id="import_photocrati" value="Import from Photocrati" onclick="return false;" />
		</div>
		<div style="float:left;width:18%;">
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/delete_selected.jpg'); ?>" id="delete_selected" value="Delete Selected" onclick="return false;" />
		</div>
		<div style="float:left;width:17%;">
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/save_gallery.jpg'); ?>" id="save_button_1" value="Save Gallery" onclick="return false;" />
		</div>
		<div style="float:left;width:18%;clear:both;">
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/meta_gallery.jpg'); ?>" id="meta_button" value="Edit Meta Data" onclick="return false;" />
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/meta_gallery_close.jpg'); ?>" id="meta_button_close" value="Close Meta Data" onclick="return false;" style="display:none;" />
		</div>
		<div style="float:left;width:18%;">
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/ecommerce_gallery.jpg'); ?>" id="ecommerce_button" value="Edit Ecommerce Data" onclick="return false;" />
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/ecommerce_gallery_close.jpg'); ?>" id="ecommerce_button_close" value="Close Ecommerce Data" onclick="return false;" style="display:none;" />
		</div>
		<div style="float:left;width:18%;">
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/order_name.jpg'); ?>" id="order_fname" value="Order By Name" onclick="return false;" />
		</div>
		<div style="float:left;width:18%;">
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/order_title.jpg'); ?>" id="order_title" value="Order By Title" onclick="return false;" />
		</div>
		<div style="float:left;width:18%;">
		<input type="image" src="<?php echo photocrati_gallery_file_uri('image/order_invert.jpg'); ?>" id="order_invert" value="Invert Order" onclick="return false;" />
		</div>
		<div id="msggallery1"></div>
		<div id="msggallery3"></div>
  </div>

    <div style="width:100%;clear:both;height:5px;"></div>

    <div id="import_nexgen_window" style="display:none;">

    </div>

    <div id="import_photocrati_window" style="display:none;">

    </div>

	<div id="ecommerce_note" style="display:none;margin-bottom:10px;">

		<p><b>NOTE:</b> The e-commerce options are set at Theme Options / Ecommerce Settings. The checkboxes below just allow you to
		exclude print size options on a per image basis.</p>

		<p>
            <label><strong>Include / Exclude Options Gallery Wide:</strong></label>
            (<a href='#' id='select_all'>Select All</a> |
            <a href='#' id='select_none'>Select None</a>)
        </p>

        <?php $options = Photocrati_Ecommerce_Options::get_instance(); ?>
        <?php foreach ($options->options as $option_number => $option_params): ?>
            <?php if (strlen($option_params['option_name']) > 0 && strlen($option_params['option_value']) > 0): ?>
            <div class='ecomm_op' style='float:left; width:33%'>
                <select class='ecomm_options_master' id="ecomm_options_master_<?php echo esc_attr($option_number)?>">
                    <option value=""> </option>
                    <option value="ON">ON</option>
                    <option value="OFF">OFF</option>
                </select>
                <?php echo esc_html(stripslashes($option_params['option_name']))?>
            </div>
            <?php endif ?>
        <?php endforeach ?>

		<div style="width:100%;clear:both;height:5px;"></div>

	</div>

    <div style="width:100%;clear:both;height:5px;"></div>

    <div id="filesUploaded" style="clear:both;"></div>
    <div id="fileName">

	<div id="widget_drag">

    <ul id="sortable">
    <?php
    $nums = 1;
	$gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '" . $wpdb->escape($_GET['gallery_id']) . "' ORDER BY image_order,image_name ASC");
	foreach ($gallery as $gallery) {
	?>

        <li class="ui-state-default" id="sortable_<?php echo $nums; ?>">
        <div id="galleria_<?php echo $gallery->id; ?>">
            <div class="gallery_uploaded" id="gallery_uploaded_<?php echo $gallery->id; ?>">
                <div class="image">
					<?php if(!function_exists('gd_info')) { ?>
						<?php if (file_exists($upload_dir['basedir'].'/galleries/post-'.$post_id.'/'.str_replace("&amp;","&",$gallery->image_name))) { ?>
							<img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id; ?>/<?php echo str_replace("%","%25",$gallery->image_name); ?>" align="absmiddle" style="max-height:100px;max-width:130px;">
						<?php } else { ?>
							<img src="<?php echo bloginfo('template_url'); ?>/galleries/post-<?php echo $post_id; ?>/<?php echo str_replace("%","%25",$gallery->image_name); ?>" align="absmiddle" style="max-height:100px;max-width:130px;">
						<?php } ?>
					<?php } else { ?>
						<?php if (file_exists($upload_dir['basedir'].'/galleries/post-'.$post_id.'/'.str_replace("&amp;","&",$gallery->image_name))) { ?>
							<img src="<?php echo $upload_dir['baseurl']; ?>/galleries/post-<?php echo $post_id; ?>/thumbnails/<?php echo str_replace("%","%25",$gallery->image_name); ?>" align="absmiddle" style="max-height:100px;max-width:130px;">
						<?php } else { ?>
							<img src="<?php echo bloginfo('template_url'); ?>/galleries/post-<?php echo $post_id; ?>/thumbnails/<?php echo str_replace("%","%25",$gallery->image_name); ?>" align="absmiddle" style="max-height:100px;max-width:130px;">
						<?php } ?>
					<?php } ?>
					<input type="hidden" id="gallery_image_<?php echo $nums; ?>" name="gallery_image_<?php echo $nums; ?>" value="<?php echo $gallery->image_name; ?>">
                </div>
                <div class="controls">
                    <span class="order_label">Order</span> <input type="text" id="image_order_<?php echo $nums; ?>" class="order_box" name="image_order_<?php echo $gallery->id; ?>" value="<?php echo $gallery->image_order; ?>">
					<input type="checkbox" id="delete_img_<?php echo $gallery->id; ?>" value="<?php echo $gallery->id; ?>">
				</div>
            </div>
            <div class="gallery_meta" id="gallery_meta_<?php echo $gallery->id; ?>" style="display:none;">
            <p style="width:120px;float:right;overflow:hidden;"><label>File Name:</label><br/><span class="image-file-name"><?php echo htmlspecialchars_decode($gallery->image_name); ?></span></p>
            <p style="margin-right:120px;"><label>Title:</label><BR><input class="image-input-field" type="text" id="image_alt_<?php echo $nums; ?>" value="<?php if($gallery->image_alt) { echo htmlspecialchars_decode(stripslashes($gallery->image_alt)); } else { echo htmlspecialchars_decode($gallery->image_name); } ?>"></p>
            <p><label>Caption:</label><BR><textarea class="image-input-field" cols="50" rows="2" id="image_desc_<?php echo $nums; ?>"><?php echo htmlspecialchars_decode(stripslashes($gallery->image_desc)); ?></textarea></p>
            </div>
            <div class="gallery_meta" id="gallery_options_<?php echo $gallery->id; ?>" style="display:none;overflow:visible;height:auto;">
				<p><label>Include / Exclude Options:</label></p>

					<?php $ecomm_ops = explode(',',$gallery->ecomm_options); ?>
                    <?php foreach ($options->options as $option_number => $option_params): ?>
                        <?php if (strlen($option_params['option_name']) > 0 && strlen($option_params['option_value']) > 0): ?>
                            <div class='ecomm_op'>
                                <input
                                    name='ecomm_options_<?php echo esc_attr($nums)?>'
                                    id='ecomm_options_<?php echo esc_attr($option_number)?>_<?php echo esc_attr($gallery->id)?>'
                                    type='checkbox'
                                    value='<?php echo esc_attr($option_number)?>'
                                    <?php if (in_array($option_number, $ecomm_ops)): ?>
                                    checked='checked'
                                    <?php endif ?>
                                /> <?php echo esc_html(stripslashes($option_params['option_name']))?>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>

					<div style="width:100%;clear:both;height:10px;"></div>

            </div>
        </div>
        </li>

    <?php $nums = $nums+1; } ?>
    </ul>

	</div>

    </div>
    <input type="hidden" id="gal_number" value="<?php echo $galnumber[1]; ?>">

    <div style="width:100%;padding-top:20px;cursor:pointer;clear:both;"></div>
    <div style="float:left;cursor:pointer;">
    <input type="image" src="<?php echo photocrati_gallery_file_uri('image/save_gallery.jpg'); ?>" id="save_button_2" value="Save Gallery" onclick="return false;" />
    </div>
    <div id="msggallery2"></div>

	</div>

	<div id="add_album"></div>

</form>

</div>
