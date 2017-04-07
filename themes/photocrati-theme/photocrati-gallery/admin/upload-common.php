function saveGallery()
{
	var $ = jQuery.noConflict();

	// POST data
	var data = {
		post_id:			<?php echo isset($_GET['post_id']) ? intval($_GET['post_id']) : intval($_GET['post']) ?>,
		gallery_id: 		'<?php echo $_GET['gallery_id'] ?>',
		gallery_title:      jQuery("input[name='gal_title']").val().replace("&","%26"),
		gallery_description:jQuery("#gal_desc").val().replace('&', '%26'),
		gallery_type:		jQuery("input[name='gal_type']:checked").val(),
		gallery_height:		jQuery("#gal_height").val(),
		aspect_ratio: 		jQuery("#gal_aspect_ratio").val(),
		next_gallery_id:	<?php echo isset($galnextid) ? $galnextid : 0 ?>,
		number_of_images: 	jQuery('[id^=gallery_image_]').size(),
		images: []
	};

	// Loop through each image of the gallery, and add to the POST request
	jQuery('[id^=sortable_]').each(function(index) {
		var image_obj = {};
		var currentId = jQuery(this).attr('id');

		// Get e-commerce options
		var options = '0';
		jQuery('[name=ecomm_options_' + currentId.substr(9) + ']:checked').each(function(index) {
			options = options + ',' + jQuery(this).val();
		});
		image_obj.ecommerce_options = options;


		// Get image order
		var imgOrderElem = jQuery('#image_order_'+currentId.substr(9));
		var imgOrder = 0;
		if (imgOrderElem.length > 0) {
			imgOrder = parseInt(jQuery('#image_order_'+currentId.substr(9)).val());
			if (imgOrderElem.hasClass('photocrati-edited')) {
				imgOrder -= 1;
			}
		}
		image_obj.order = imgOrder;

		// Get the image caption
		image_obj.caption = jQuery('#gallery_image_'+currentId.substr(9)).val().replace("&","%26");

		// Get the image alt text
		image_obj.alt_text = jQuery('#image_alt_'+currentId.substr(9)).val().replace("&","%26");

		// Get the image description
		image_obj.description = jQuery('#image_desc_'+currentId.substr(9)).val().replace("&","%26");

		// Get the image ID
		var image_id = jQuery(this).find('div[id^=gallery_uploaded_]').attr('id');
		if (typeof(image_id) != 'undefined') {
			image_obj.id = image_id.substr(17);
		}

		// Add the image to the POST data
		data.images.push(image_obj);
	});

	// Submit the AJAX request used to persist the changes
	var ajax_url = "<?php echo photocrati_gallery_file_uri('admin/save-gallery-images-bulk.php'); ?>";
	$.post(ajax_url, { 'data' : JSON.stringify(data) }, function(response){

		if (typeof(response) != 'object') response = JSON.parse(response);

		// Insert the img placeholder in the post content
		var gallery_number = <?php echo isset($galnextid) ? $galnextid : 0 ?>;
		if (gallery_number == 0) gallery_number = jQuery("#gal_number").val();
		var img_html = '<img id="phgallery-'+response.gallery_id+' '+jQuery("input[name='gal_type']:checked").val()+'" src="<?php echo photocrati_gallery_path_uri('image/gallery-placeholder-'); ?>'+gallery_number+'.gif" alt="photocrati gallery" />';

		var editor_win = window.dialogArguments || opener || parent || top;
		if (editor_win != null && typeof(editor_win.send_to_editor) == 'function') {
			editor_win.send_to_editor(img_html);
			editor_win.jQuery('[id^=the_content_]').val(editor_win.document.post.content.value);
		}
		else if (parent.tinyMCE.activeEditor != null && parent.tinyMCE.activeEditor.isHidden() == false) {
			parent.tinyMCE.execCommand('mceInsertContent',false, img_html);
			parent.jQuery('[id^=the_content_]').val(parent.document.post.content.value);

		} else {
			insertInPost(img_html);
			parent.jQuery('[id^=the_content_]').val(parent.document.post.content.value);
		}

		jQuery.ajax({type: "POST", async: false, url: "<?php echo photocrati_gallery_file_uri('admin/get_galleries.php'); ?>", data: 'post_id=<?php echo $post_id ?>', success: function(data)
			{
				parent.jQuery('#display_galleries').html(data);
				parent.tb_remove();
				alert("Remember, you must update or publish your page before gallery changes will take effect!");
				parent.jQuery('#reinsert_button_<?php echo $post_id.'_'.$galnextid; ?>').hide();
			}
		});
	});
}
