<?php

// These functions control the image management portion of the Photocrati SuperTheme.
// Please do not edit these functions!!

/**
 * Adds the additional Media Library buttons above the TinyMCE toolbar
 * @deprecated since WP 2.9
 */
function add_photocrati_media_buttons()
{
	echo _media_button('Add an Image', 'images/media-button-image.gif', 'image', 'add_image');
	echo _media_button('Add Video',    'images/media-button-video.gif', 'video', 'add_video');
	echo _media_button('Add Audio',    'images/media-button-music.gif', 'audio', 'add_audio');
}
//add_action('media_buttons', 'add_photocrati_media_buttons', PHP_INT_MAX);


/**
 * Adds the Photocrati Upload Media button above the TinyMCE toolbar
 */
function add_photocrati_upload_button()
{
	$retval = '';
	$title = 'Add Photocrati Gallery';
	$iframe_src = str_replace(
		admin_url('media-upload.php'),
		photocrati_gallery_file_uri('admin/upload.php'),
		get_upload_iframe_src()
	);
	$icon_src = photocrati_gallery_file_uri('image/new_gallery.gif');

	// If we're not editing an existing post, we need to tell the user to save
	// the post first
    $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_GET['post_id']) ? $_GET['post_id'] : FALSE);
	if(!$post_id) {
        $retval = '
			<a
				href="#"
				onclick="alert(\'You need to save your post or page before you can insert a Photocrati gallery. You can do this by inserting a title and clicking the Save Draft button.\');"
				id="add_gallery"
				title="' . $title . '"
				onclick="return false;"
			><img src="' . $icon_src . '" alt="' . $title . '" /></a>
		';
    }

	// Return the button for adding Photocrati Galleries
	else {
		$retval = "
			<a
				id='ph_gallery_button'
				style='clear:none;'
				href='%s'
				id='add_gallery'
				class='thickbox'
				title='%s'
				onclick='return false;'
			><img src='%s' alt='%s'/></a>
		";
		$retval =  sprintf($retval, $iframe_src, $title, $icon_src, $title);
	}

	echo $retval;
}
add_action('media_buttons', 'add_photocrati_upload_button',PHP_INT_MAX);


    function oppColour($c, $inverse=true){
        if(strlen($c)== 3)    { // short-hand
            $c = $c{0}.$c{0}.$c{1}.$c{1}.$c{2}.$c{2};
        }
            $r = (strlen($r=dechex(255-hexdec($c{0}.$c{1})))<2)?'0'.$r:$r;
            $g = (strlen($g=dechex(255-hexdec($c{2}.$c{3})))<2)?'0'.$g:$g;
            $b = (strlen($b=dechex(255-hexdec($c{4}.$c{5})))<2)?'0'.$b:$b;
            return $r.$g.$b;
    }
    
function photocrati_legacy_gallery_item_width($items_per_row, $contex = null)
{
	
}

function insertGallery($atts)
{
	$upload_dir = photocrati_gallery_wp_upload_dir();

    global $wpdb;

    $global = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1", ARRAY_A);
	foreach ($global as $key => $value) {
		$$key = $value;
	}

    $cart_settings = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_ecommerce_settings WHERE id = 1", ARRAY_A);
	foreach ($cart_settings as $key => $value) {
		$$key = $value;
	}

    $preset             = Photocrati_Style_Manager::get_active_preset();
    $style_skin           = $preset->style_skin;
    $bg_color           = $preset->bg_color;
    $container_color    = $preset->container_color;
    $content_width      = $preset->content_width;
    $container_padding  = $preset->container_padding;
    $container_border   = $preset->container_border;

	if(isset($_GET['gal_page']) && $_GET['gal_page'] == 'true'){
    $contwidth = $content_width / 100;
    } else {
    $contwidth = ($content_width - 2) / 100;
    }

	if ($container_color <> 'transparent') {
		$bg = $container_color;
	} else {
		$bg = $bg_color;
	}

	extract(shortcode_atts(array(
	"gal_id" 	=> 		'1_1',
	"gal_type" 	=> 		'1',
    ), $atts));

	$gallery_options = photocrati_gallery_option_list_get();

	$gallery = photocrati_gallery_instance_get($gal_id);
	// XXX here we pass in gallery_type of 'thumbnail' to ensure we get the smallest thumbnails, ideally PhotocratiGalleryImage would have all of these as additional fields
	$instance_list = photocrati_gallery_instance_list($gallery, array_merge($gallery_options, array('gallery_type' => 'thumbnail')));
	$count = count($instance_list);

	$insertgallery = '';
	$insertgallery .= '<div class="photocrati_nojava" id="gal_images_'.$gal_id.'">';

	for ($i = 0; $i < $count; $i++)
	{
		$instance_image = $instance_list[$i];
		$gallery_id = $instance_image->GalleryID;

		$insertgallery .= '<a href="' . $instance_image->URI . '" class="decoy"';

		if (($gal_type == '1' && $gallery_cap1 == 'ON') ||
			($gal_type == '2' && $gallery_cap2 == 'ON') ||
			($gal_type == '3' && $gallery_cap3 == 'ON') ||
			($gal_type == '4' && $gallery_cap4 == 'ON'))
		{
			$insertgallery .= ' title="' . $instance_image->DescriptionHTML . '"';
		}

		$insertgallery .= ' id="img_' . $gallery_id . '_' . ($i + 1) . '" rel="gallery_' . $gallery_id . '">';
		$insertgallery .= '<img src="' . $instance_image->ThumbURI . '" alt="' . $instance_image->TitleHTML . '" />';
		$insertgallery .= '</a>';
	}

	$insertgallery .= '</div>';

	$insertgallery .= '<script type="text/javascript">
		jQuery.noConflict();
		jQuery("#gal_images_'.$gal_id.'").hide();

		function iframe_'.$gal_id.'_loaded()
		{
			var iframe = jQuery("#g'.$gal_id.'");
			var height = iframe.contents().find(\'#content\').height();
			iframe.height(height);
		}
	</script>';

	if($gal_type == '1') {

		if(!is_page()) {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=1&amp;gal_cap='.$gallery_cap1.'&amp;gal_page=false&amp;page_template=false&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		} else if(is_page() && !is_page_template('page-with-sidebar.php')) {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=1&amp;gal_cap='.$gallery_cap1.'&amp;gal_page=true&amp;page_template=false&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		} else {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=1&amp;gal_cap='.$gallery_cap1.'&amp;gal_page=true&amp;page_template=true&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		}

	} else if($gal_type == '2') {

		if(!is_page()) {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=2&amp;gal_cap='.$gallery_cap2.'&amp;gal_page=false&amp;page_template=false&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		} else if(is_page() && !is_page_template('page-with-sidebar.php')) {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=2&amp;gal_cap='.$gallery_cap2.'&amp;gal_page=true&amp;page_template=false&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		 } else {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=2&amp;gal_cap='.$gallery_cap2.'&amp;gal_page=true&amp;page_template=true&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		}

	} else if($gal_type == '3') {

		if(!is_page()) {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=3&amp;gal_cap='.$gallery_cap3.'&amp;gal_page=false&amp;page_template=false&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		} else if(is_page() && !is_page_template('page-with-sidebar.php')) {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=3&amp;gal_cap='.$gallery_cap3.'&amp;gal_page=true&amp;page_template=false&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		 } else {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=3&amp;gal_cap='.$gallery_cap3.'&amp;gal_page=true&amp;page_template=true&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		}

    } else if($gal_type == '4') {

		if(!is_page()) {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=4&amp;gal_cap='.$gallery_cap4.'&amp;gal_page=false&amp;page_template=false&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		} else if(is_page() && !is_page_template('page-with-sidebar.php')) {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=4&amp;gal_cap='.$gallery_cap4.'&amp;gal_page=true&amp;page_template=false&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		 } else {
		$insertgallery .= '<div class="iframe_wrapper"><script type="text/javascript">
document.write (\'<iframe id="g'.$gal_id.'" allowtransparency="true" src="'.get_bloginfo ( 'wpurl' ).'/index.php?display_gallery_iframe&amp;gal_id='.$gal_id.'&amp;gal_type=4&amp;gal_cap='.$gallery_cap4.'&amp;gal_page=true&amp;page_template=true&amp;bg='.$bg.'" scrolling="no" width="100%" frameborder="0" style="margin:0 auto;padding:0;border:0;clear:both;background:transparent;"></iframe>\');
</script></div>';
		}

	} else if($gal_type == '5') {

	if(is_page() && !is_page_template('page-with-sidebar.php')){

		$maxwidth = (960 - ($container_padding * 2) - ($container_border * 2));

    } else {

		$maxwidth = floor((960 - ($container_padding * 2) - ($container_border * 2)) * $contwidth);

    }

		$layoutw = floor($maxwidth / $ecomm_per_row);
	
		if ($style_skin != 'legacy') {
			if (!$ecomm_image_width) {
				$ecomm_image_width = intval(floor(940 / $ecomm_per_row));
			}
		
			$layoutw = $ecomm_image_width;
		}
		
		$column_padding = 10;
		$column_margin = 5;
		$column_width = 0;
		$column_height = 0;
		$image_width = 0;
		$image_height = 0;
		$extra_style = '';
	
		if ($style_skin != 'legacy') {
			// remove column padding, margin and borders to find the actual width
			$column_width = $layoutw - ($column_padding * 2 + $column_margin * 2 + $ecomm_line_size * 2);
			$column_height = floor($column_width * 0.664);
	
			// remove borders again because they are applied twice
			$image_width = $column_width - ($ecomm_line_size * 2);
			$image_height = $column_height - ($ecomm_line_size * 2);
	
			$extra_style = '

			.ecommerce .image_wrapper a {
				display: inline-block;
			}';
		}
		else {
			// remove column padding, margin and borders to find the actual width
			$column_width = $layoutw - (37 + $ecomm_line_size * 2);
			$column_height = floor($layoutw * 0.664);;
	
			// remove borders again because they are applied twice
			$image_width = $layoutw - (30 + $ecomm_line_size * 4);
			$image_height = $column_height - ($ecomm_line_size * 2);
		}

		$insertgallery .= '<script src="'. get_bloginfo('template_url') .'/admin/js/jquery.tools.min.js"></script>

	<script type="text/javascript">
	jQuery.noConflict();

	window.refresh_cart_widget = function(){
	    var url = "'.photocrati_gallery_file_uri('ecomm-cart-widget.php').'";
	    jQuery.post(url, {}, function(response){
	        jQuery("#cart_widget").html(response);
	    });
	};

	jQuery(document).ready(function() {';
	
	$insertgallery .= Photocrati_Shopping_Cart::get_lightbox_opener_js('gallery', $style_skin, 'a.iframe');

	$insertgallery .= '
		window.refresh_cart_widget();
	});
	</script>

	<style type="text/css">

	.ecommerce {
		width			:	' . $column_width . 'px;
		margin			:	5px;
		padding			:	10px;
		background		:	#'.$ecomm_back_color.';
		border			:	' . $ecomm_line_size . 'px solid #' . $ecomm_line_color . ';
	}

	.ecommerce .image_wrapper {
		height			:	' . $column_height . 'px;
		z-index			:	1;
		overflow		:	hidden;
	}

	.ecommerce .image_wrapper img {
		max-width		:	' . $image_width . 'px;
		max-height		:	' . $image_height . 'px;
		border			:	' . $ecomm_line_size . 'px solid #' . $ecomm_line_color . ';
		margin: 0;
		z-index			:	1;
	}

	.ecommerce .meta_wrapper {
		height			:	30px;
		color			:	#333;
		margin-top		:	10px;
		text-align		:	left;
		z-index			:	5;
	}
	
	.photocrati-ecommerce-popup-mobile:before {
		display: none;
	}
	
	' . $extra_style . '

	</style>

	<div class="ecommerce_wrapper">

	<div class="widget_wrapper">
		<div id="cart_widget"></div>
	</div>';

	$g = 1;
	$gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '".$gal_id."' ORDER BY image_order,image_name ASC");
	foreach ($gallery as $gallery) {

	$action = '';
	$qty = explode(",", $_SESSION['cart_qty']);

	foreach ($qty as $cart_items) {
		$item = explode("|", $cart_items);
		if($item[0] == $gallery->id) {

			$action = '&actions=edit&page=gallery';

		}
	}


	$insertgallery .= '<div class="ecommerce"';

	if ($style_skin == 'legacy') {
		if($g == ($ecomm_per_row + 1)) {
			$insertgallery .= ' style="clear:both;"';
			$g = 1;
		}
	}

	$insertgallery .= '>

		<div class="image_wrapper">';


				if (file_exists($upload_dir['basedir'].'/galleries/post-'.$gallery->post_id.'/thumbnails/'.$gallery->image_name)) {

				$insertgallery .= '<a class="photocrati_lightbox_always" rel="gallery" href="'.$upload_dir['baseurl'].'/galleries/post-'.$gallery->post_id.'/full/'.str_replace("%","%25",$gallery->image_name).'"';

				if($ecomm_captions == 'ON') {
					$insertgallery .= 'title="'.$gallery->image_alt.'';
					if($gallery->image_desc) {
						$insertgallery .= ' - '.$gallery->image_desc;
					}
					$insertgallery .= '"';
				}

				$insertgallery .= '>
					<img onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false" src="'.$upload_dir['baseurl'].'/galleries/post-'.$gallery->post_id.'/thumbnails/'.str_replace("%","%25",$gallery->image_name).'" alt="'.$gallery->image_name.'">';

				} else {

				$insertgallery .= '<a class="photocrati_lightbox_always" rel="gallery" href="'.get_bloginfo('template_url').'/galleries/post-'.$gallery->post_id.'/full/'.str_replace("%","%25",$gallery->image_name).'"';

				if($ecomm_captions == 'ON') {
					$insertgallery .= 'title="'.$gallery->image_alt.'';
					if($gallery->image_desc) {
						$insertgallery .= ' - '.$gallery->image_desc;
					}
					$insertgallery .= '"';
				}
				$insertgallery .= '>
					<img onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false" src="'.get_bloginfo('template_url').'/galleries/post-'.$gallery->post_id.'/thumbnails/'.str_replace("%","%25",$gallery->image_name).'" alt="'.$gallery->image_name.'">';

				}

				$insertgallery .= '</a>

			</div>

			<div class="meta_wrapper">

				<div class="addto">';

					if(!$ecomm_but_image) {

						$insertgallery .= '<a class="iframe" id="addto_'.$gallery->id.'" href="' . photocrati_gallery_file_uri('ecomm-sizes.php') . '?prod_id='.$gallery->id.$action.'">
						<button id="addto" class="positive">
							'.$ecomm_but_text.'
						</button>
						</a>';

					} else {

						$insertgallery .= '<a class="iframe" id="addto_'.$gallery->id.'" href="' . photocrati_gallery_file_uri('ecomm-sizes.php') . '?prod_id='.$gallery->id.$action.'">
							<img src="'.$ecomm_but_image.'" id="addto">
						</a>';

					}

				$insertgallery .= '</div>

			</div>

		</div>';

	$g = $g + 1;
	}

	$insertgallery .= '</div>
	<div class="clear"></div>';


	} else if($gal_type == '6') {

	if(is_page() && !is_page_template('page-with-sidebar.php')){

		$maxwidth = (960 - ($container_padding * 2) - ($container_border * 2));

    } else {

		$maxwidth = floor((960 - ($container_padding * 2) - ($container_border * 2)) * $contwidth);

    }

		$layoutw = floor($maxwidth / $albuml_per_row);
		$layouth = floor($layoutw * .664);

	$insertgallery .= '<style type="text/css">

	.album_list {
		width			:	auto;
		min-height			:	150px;
		margin			:	10px 0;
		padding			:	10px;
		background		:	#'.$albuml_back_color.';
		border			:	'.$albuml_line_size.'px solid #'.$albuml_line_color.';
	}

	.album_list .image_wrapper {
		float			:	left;
		z-index			:	1;
		overflow		:	hidden;
	}

	.album_list .image_wrapper img {
		border			:	'.$albuml_line_size.'px solid #'.$albuml_line_color.';
		z-index			:	1;
	}

	.album_list .meta_wrapper {
		float			:	left;
		color			:	#'.$albuml_font_color.';
		text-align		:	left;
		z-index			:	5;
	}

	.album_list .meta_wrapper h4 a {
		color			:	#'.$albuml_font_color.';
		font-size		:	'.$albuml_font_size.'px;
	}

	</style>

	<div class="album_wrapper">';

	$g = 1;
	$album = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_albums WHERE album_id = '".$gal_id."' ORDER BY album_order,gallery_id ASC");
	foreach ($album as $album) {
	$postnumber = explode("_", $album->gallery_id);

		$gallery_exists = false;
		$image = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '".$album->gallery_id."' ORDER BY image_order,image_name LIMIT 1");
		foreach ($image as $image) {
			$gallery_exists = true;
		}

		if($gallery_exists) {

		$insertgallery .= '<div class="album_list"';
		if($g == ($albuml_per_row + 1)) {
			$insertgallery .= ' style="clear:both;"'; $g = 1;
		}
		$insertgallery .= '>';

			$image = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '".$album->gallery_id."' ORDER BY image_order,image_name LIMIT 1");
			foreach ($image as $image) {

				$insertgallery .= '<div class="image_wrapper">
					<a href="'.get_permalink($image->post_id).'">';
					if (file_exists($upload_dir['basedir'].'/galleries/post-'.$postnumber[0].'/thumbnails/'.$image->image_name)) {
						$insertgallery .= '<img onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false" src="'.$upload_dir['baseurl'].'/galleries/post-'.$postnumber[0].'/thumbnails/'.str_replace("%","%25",$image->image_name).'" alt="'.$image->image_name.'">';
					} else {
						$insertgallery .= '<img onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false" src="'.get_bloginfo('template_url').'/galleries/post-'.$postnumber[0].'/thumbnails/'.str_replace("%","%25",$image->image_name).'" alt="'.$image->image_name.'">';
					}
					$insertgallery .= '</a>
				</div>';

			}

				$insertgallery .= '<div class="meta_wrapper">';

					$meta = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE gallery_id = '".$album->gallery_id."' ORDER BY gallery_id LIMIT 1");
					foreach ($meta as $meta) {

						$insertgallery .= '<h4><a href="'.get_permalink($meta->post_id).'">'.stripslashes($meta->gal_title).'</a></h4>';

						$insertgallery .= stripslashes($meta->gal_desc);

					}

				$insertgallery .= '</div>

		</div>';

		}

	$g = $g + 1;
	}

	$insertgallery .= '</div>
	<div class="clear"></div>';


	} else if($gal_type == '7') {

	if(is_page() && !is_page_template('page-with-sidebar.php')){

		$maxwidth = (960 - ($container_padding * 2) - ($container_border * 2));

    } else {

		$maxwidth = floor((960 - ($container_padding * 2) - ($container_border * 2)) * $contwidth);

    }

		$layoutw = floor($maxwidth / $albumg_per_row);
	
		if ($style_skin != 'legacy') {
			if (!$albumg_image_width) {
				$albumg_image_width = intval(floor(940 / $albumg_per_row));
			}
		
			$layoutw = $albumg_image_width;
		}
		
		$column_padding = 10;
		$column_margin = 5;
		$column_width = 0;
		$column_height = 0;
		$image_width = 0;
		$image_height = 0;
		$extra_style = '';
	
		if ($style_skin != 'legacy') {
			// remove column padding, margin and borders to find the actual width
			$column_width = $layoutw - ($column_padding * 2 + $column_margin * 2 + $albumg_line_size * 2);
			$column_height = floor($column_width * 0.664) - ($albumg_line_size * 2);
	
			// remove borders again because they are applied twice
			$image_width = $column_width - ($albumg_line_size * 4);
			$image_height = $column_height - ($albumg_line_size * 2);
	
			$extra_style = '

			.ecommerce .image_wrapper a {
				display: inline-block;
			}';
		}
		else {
			// remove column padding, margin and borders to find the actual width
			$column_width = $layoutw - (30 + $albumg_line_size * 2);
			$column_height = floor($layoutw * 0.664) - ($albumg_line_size * 2);
	
			// remove borders again because they are applied twice
			$image_width = $layoutw - (30 + $albumg_line_size * 4);
			$image_height = $column_height - ($albumg_line_size * 2) + 15;
		}

		$insertgallery .= '<style type="text/css">

	.album_grid {
		width			:	' . $column_width . 'px;
		min-height		:	' . ($column_height + 40).'px;
		height			:	auto;
		float			:	left;
		margin			:	5px;
		padding			:	10px;
		background		:	#' . $albumg_back_color . ';
		border			:	' . $albumg_line_size . 'px solid #' . $albumg_line_color . ';
	}

	.album_grid .image_wrapper {
		width			:	' . $column_width . 'px;
		height			:	' . ($column_height + 15) . 'px;
		float			:	left;
		text-align		:	center;
		z-index			:	1;
		overflow		:	hidden;
	}

	.album_grid .image_wrapper img {
		max-width		:	' . $image_width . 'px;
		max-height		:	' . $image_height . 'px;
		border			:	' . $albumg_line_size . 'px solid #' . $albumg_line_color . ';
		z-index			:	1;
	}

	.album_grid .meta_wrapper {
		width			:	100%;
		clear			:	both;
		float			:	left;
		color			:	#'.$albumg_font_color.';
		text-align		:	center;
		z-index			:	5;
	}

	.album_grid .meta_wrapper h4 a {
		color			:	#'.$albumg_font_color.';
		font-size		:	'.$albumg_font_size.'px;
	}

	</style>

	<div class="album_wrapper">';

	$g = 1;
	$album = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_albums WHERE album_id = '".$gal_id."' ORDER BY album_order,gallery_id ASC");
	foreach ($album as $album) {
	$postnumber = explode("_", $album->gallery_id);

		$gallery_exists = false;
		$image = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '".$album->gallery_id."' ORDER BY image_order,image_name LIMIT 1");
		foreach ($image as $image) {
			$gallery_exists = true;
		}

		if($gallery_exists) {

		$insertgallery .= '<div class="album_grid"';
		
		if ($style_skin == 'legacy') {
			if($g == ($albumg_per_row + 1)) {
				$insertgallery .= ' style="clear:both;"'; 
				$g = 1;
			}
		}
		
		$insertgallery .= '>';

			$image = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '".$album->gallery_id."' ORDER BY image_order,image_name LIMIT 1");
			foreach ($image as $image) {

				$insertgallery .= '<div class="image_wrapper">
					<a href="'.get_permalink($image->post_id).'">';
					if (file_exists($upload_dir['basedir'].'/galleries/post-'.$postnumber[0].'/thumbnails/med-'.$image->image_name)) {
						$insertgallery .= '<img onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false" src="'.$upload_dir['baseurl'].'/galleries/post-'.$postnumber[0].'/thumbnails/med-'.str_replace("%","%25",$image->image_name).'" alt="'.$image->image_name.'">';
					} else {
						$insertgallery .= '<img onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false" src="'.get_bloginfo('template_url').'/galleries/post-'.$postnumber[0].'/thumbnails/med-'.str_replace("%","%25",$image->image_name).'" alt="'.$image->image_name.'">';
					}
					$insertgallery .= '</a>
				</div>';

			}

				$insertgallery .= '<div class="meta_wrapper">';

					$meta = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE gallery_id = '".$album->gallery_id."' ORDER BY gallery_id LIMIT 1");
					foreach ($meta as $meta) {

						$insertgallery .= '<h4><a href="'.get_permalink($meta->post_id).'">'.stripslashes($meta->gal_title).'</a></h4>';

					}

				$insertgallery .= '</div>

		</div>';

		}

	$g = $g + 1;
	}

	$insertgallery .= '</div>
	<div class="clear"></div>';

	}

    return $insertgallery;

}

add_shortcode('photocrati_gallery', 'insertGallery');


function placer_image($content) {
	global $post;
    $original = preg_replace('(<img(.*)id=\"phgallery-([^\"]+) ([^\"]+)\"(.*)/>)', '[photocrati_gallery gal_id="$2" gal_type="$3"]', $content);
	return $original;
}
add_filter('the_content', 'placer_image');


// Adds a gallery edit box to the "advanced" Post and Page edit screens
function pg_add_custom_box() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'pg_custom_box_1', __( 'Edit Photocrati Galleries / Albums', 'photocrati' ), 'pg_inner_custom_box_1', 'page', 'normal', 'high' );
		add_meta_box( 'pg_custom_box_2', __( 'Edit Photocrati Galleries / Albums', 'photocrati' ), 'pg_inner_custom_box_1', 'post', 'normal', 'high' );
	}
}

function pg_inner_custom_box_1() {
	$dir = dirname(dirname(__FILE__));
	include(photocrati_gallery_file_path('admin/display_galleries.php'));
}

add_action('admin_menu', 'pg_add_custom_box');


function delete_phpost($pid) {

	global $wpdb;
	$upload_dir = photocrati_gallery_wp_upload_dir();

	$pid = (int) $pid;

	if ($pid == 0)
		return;

	define('GALPATH', $upload_dir['basedir'].'/galleries/post-'.$pid.'/');
	$gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE post_id = '".$pid."' ORDER BY image_name ASC");
	foreach ($gallery as $gallery) {

		if (file_exists(GALPATH.$gallery->image_name)) {
			unlink(GALPATH.$gallery->image_name);
		}
		if (file_exists(GALPATH.'full/'.$gallery->image_name)) {
			unlink(GALPATH.'full/'.$gallery->image_name);
		}
		if (file_exists(GALPATH.'thumbnails/'.$gallery->image_name)) {
			unlink(GALPATH.'thumbnails/'.$gallery->image_name);
		}
		if (file_exists(GALPATH.'thumbnails/med-'.$gallery->image_name)) {
			unlink(GALPATH.'thumbnails/med-'.$gallery->image_name);
		}

	}

	$SQL = "DELETE FROM ".$wpdb->prefix."photocrati_galleries WHERE post_id = '".$pid."'";
	$wpdb->query($SQL);

	$SQL2 = "DELETE FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE post_id = '".$pid."'";
	$wpdb->query($SQL2);

	if (file_exists(GALPATH.'thumbnails/')) {
	rmdir(GALPATH.'thumbnails/');
	}
	if (file_exists(GALPATH.'full/')) {
	rmdir(GALPATH.'full/');
	}
	if (file_exists(GALPATH)) {
	rmdir(GALPATH);
	}

}

add_action('delete_post', 'delete_phpost');


// Grab iFrame contents
add_action('init', 'render_iframe_gallery');
function render_iframe_gallery()
{
  //if (preg_match("/\/?display_gallery-iframe/", $_SERVER['REQUEST_URI'])) {
  if (isset($_GET['display_gallery_iframe'])) {
    include(photocrati_gallery_file_path('gallery-iframe.php'));
    exit(0);
  }
}

?>
