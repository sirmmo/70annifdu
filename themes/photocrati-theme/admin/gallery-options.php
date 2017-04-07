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

	include "scripts/scripts-gallery.php";
	
  $preset = Photocrati_Style_Manager::get_active_preset();
  extract($preset->to_array());
  
	global $wpdb;
	$gallery = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1", ARRAY_A);
	foreach ($gallery as $key => $value) {
		$$key = $value;
	}
?>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function() {

	jQuery("[id$='_color']").ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				jQuery(el).val(hex);
				jQuery(el).ColorPickerHide();
				jQuery(el).css('background-color', '#'+hex);
			},
			onBeforeShow: function () {
				jQuery(this).ColorPickerSetColor(this.value);
				jQuery(this).css('background-color', '#'+this.value);
			}
		})
		.bind('keyup', function(){
			jQuery(this).ColorPickerSetColor(this.value);
	});
	
});
</script>

<div id="admin-wrapper">

	<div id="header-left">
	<img src="<?php bloginfo('template_directory'); ?>/admin/images/ph-logo.gif" align="absmiddle" /> &nbsp;<a id="view-theme" class="iframe" href="<?php bloginfo('wpurl'); ?>" style="text-decoration:none;" /><input type="button" class="button" value="View Theme" /></a>
	</div>
	
	<div id="header-right">
	<?php theme_version(); ?>
	</div>
	
	<?php if(!function_exists('gd_info')) { ?>
	<div style="width:100%;margin:5px 0;clear:both;">
	<p style="color:#FF0000;font-style:italic;">
	<b>Warning:</b> Your host does not have the PHP GD library installed!
	<BR>Gallery thumbnails will be generated on the fly which may affect loading speed. Contact your host for more info.
	</p>
	</div>
	<?php } ?>
	
	<div id="container">
		
	  <div class="options">General Options</div>
		
			<form name="general-options" id="general-options" method="post">
			
			<div class="sub-options"><span id="comments"><b>Image Resolution Options</b> - Use automatically re-sized images or original full sized images</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
					<div class="left" style="width:98%">
						
						<div class="inner" style="width:32%">
							<p class="titles">Image Resolution</p>
							<p>
							<select name="image_resolution" style="width: 95%;">
								<option value="0"<?php if($image_resolution == '0') { echo ' SELECTED'; } ?>>Full Resolution Images (slower)</option>
								<option value="1"<?php if($image_resolution == '1') { echo ' SELECTED'; } ?>>Re-sized Images (faster)</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:68%">
							<p class="notes">
								<b>Note:</b> <i>When you upload images, re-sized versions are created to
								make the galleries load faster. Your original images are also stored.
								You can use the re-sized images to make your galleries load faster OR you
								can use your original, uncompressed images for better quality but your
								<u>gallery load times will be slower</u> depending on the file size and
								resolution of the images.</i>
							</p>
						</div>
						
					</div>			  
					  
				</div>
		</div>
		
		
			<div class="sub-options"><span id="comments"><b>Right Click Protection</b> - Turn on and off "full site" right click protection</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
					<div class="left" style="width:99%;">
						
						<div class="inner" style="width:33%;">
							<p class="titles">Full Site Right Click Protection</p>
							<p>
							<input type="radio" name="fs_rightclick" id="fs_rightclick" value="ON" <?php if($fs_rightclick == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="fs_rightclick" id="fs_rightclick" value="OFF" <?php if($fs_rightclick == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
						<div class="inner" style="width:66%;">
							<p class="notes" style="padding-top:10px;">
								<b>Note:</b> <i>Galleries are right click protected by default. This setting will <b>disable right clicking
								on the entire website</b>.</i>
							</p>
						</div>
						
					</div>			  
					  
				</div>
					
		</div>
		
		
			<div class="sub-options"><span id="comments"><b>Lightbox Settings</b> - Select among 3 separate activation modes and 3 different lightbox styles</span></div>
			<div class="option-content" style="border-bottom:0;">
			
				<div id="option-container">
				
					<div class="left" style="width:99%;">
						
						<div class="inner" style="width:33%;">
							<p class="titles">Activation Mode</p>
							<p>
							<select name="lightbox_mode">
								<?php if ($lightbox_mode == null) $lightbox_mode = 'manual'; ?>
								<option value="always"<?php if($lightbox_mode == 'always') { echo ' selected="selected"'; } ?>>Always Enabled</option>
								<option value="manual"<?php if($lightbox_mode == 'manual') { echo ' selected="selected"'; } ?>>Enable Manually</option>
								<option value="never"<?php if($lightbox_mode == 'never') { echo ' selected="selected"'; } ?>>Never Enabled</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:66%;">
							<p class="notes" style="">
								<b>Note: Installing a lightbox plugin might not work as expected</b><br/>
								<b>Always</b> - <i>Lightbox will always be enabled, both for Photocrati galleries and for WordPress images</i><br/>
								<b>Manually</b> - <i>Lightbox will only be enabled for Photocrati galleries and for WordPress images if the &lt;a&gt; tag has a "photocrati_lightbox" class. Eg: &lt;a class="photocrati_lightbox" href="..."&gt;&lt;img ... /&gt;&lt;/a&gt;</i><br/>
								<b>Never</b> - <i>Lightbox will only be enabled for Photocrati galleries but won't be used for regular WordPress images</i>
							</p>
						</div>
						
					</div>	
							  
					<div class="left" style="width:99%;">
						
						<div class="inner" style="width:33%;">
							<p class="titles">Choose a style</p>
							<p>
							<select name="lightbox_type" style="width: 95%;">
								<option value="fancy"<?php if($lightbox_type == 'fancy') { echo ' selected="selected"'; } ?>>FancyBox Style</option>
								<option value="light"<?php if($lightbox_type == 'light') { echo ' selected="selected"'; } ?>>LightBox Style</option>
								<option value="thick"<?php if($lightbox_type == 'thick') { echo ' selected="selected"'; } ?>>Classic ThickBox Style</option>
								<option value="magnific"<?php if($lightbox_type == 'magnific') { echo ' selected="selected"'; } ?>>Magnific Popup Style (responsive)</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:66%;">
							<p class="notes" style="">
								<i>Choose between normal LightBox, ThickBox, FancyBox or Magnific Popup styles.</i>
							</p>
						</div>
						
					</div>			  
					  
				</div>
					
		</div>
			
		
		<div class="option-content" style="border-top:0;">
			<div id="option-container" style="border-top:1px solid #CCC;padding-top:10px;margin-top:10px;"> 
				<div class="submit-button-wrapper">
					<input type="button" class="button" id="update-general" value="Save General Settings" style="clear:none;" /> 
				</div>
				<div id="msg2" style="float:left;"></div>
			</div>
					
		</div>
		
		</form>
		
		</div>	
			
		
		<div id="container">
		
		<div class="options">Gallery Options</div>
		
				<form name="gallery-options" id="gallery-options" method="post">
			
			<div class="sub-options"><span id="comments"><b>Slideshow Gallery Options</b> - Maximum gallery width is 960px.</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
					<div class="left" style="width:99%;">
						
						<div class="inner" style="width:33%;">
							<p class="titles">Gallery Width</p>
							<p>
							<input type="text" name="gallery_w1" id="gallery_w1" value="<?php echo $gallery_w1; ?>" size="3" />px
							</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="titles">Display Play / Pause Button</p>
							<p>
							<input type="radio" name="gallery_buttons1" id="gallery_buttons1" value="ON" <?php if($gallery_buttons1 == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="gallery_buttons1" id="gallery_buttons1" value="OFF" <?php if($gallery_buttons1 == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
					</div>
				
					<div class="left" style="width:99%;">
						
						<div class="inner" style="width:33%;">
							<p class="titles">Image Captions</p>
							<p>
							<input type="radio" name="gallery_cap1" id="gallery_cap1" value="ON" <?php if($gallery_cap1 == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;

							<input type="radio" name="gallery_cap1" id="gallery_cap1" value="OFF" <?php if($gallery_cap1 == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
						<div class="inner" style="width:21%;">
							<p class="titles">Caption Location</p>
							<p>
							<select name="sgallery_cap_loc">
								<option value="top"<?php if($sgallery_cap_loc == 'top') { echo ' selected="selected"'; } ?>>Top</option>
								<option value="bottom"<?php if($sgallery_cap_loc == 'bottom') { echo ' selected="selected"'; } ?>>Bottom</option>
								<option value="overlay_top"<?php if($sgallery_cap_loc == 'overlay_top') { echo ' selected="selected"'; } ?>>Overlay Top</option>
								<option value="overlay_bottom"<?php if($sgallery_cap_loc == 'overlay_bottom' || $sgallery_cap_loc == null) { echo ' selected="selected"'; } ?>>Overlay Bottom</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:45%;">
							<p class="notes" style="">
								<b>Top</b> - <i>Caption at the top, before everything else</i><br/>
								<b>Bottom</b> - <i>Caption at the bottom, after everything else</i><br/>
								<b>Overlay Top</b> - <i>Caption is overlaid on the top of images</i><br/>
								<b>Overlay Bottom</b> - <i>Caption is overlaid on the bottom of images</i><br/>
							</p>
						</div>
						
					</div>
				
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Slideshow Speed</p>
							<p>
							<input type="text" name="sgallery_s" value="<?php echo $sgallery_s; ?>" size="2" /> <i>seconds</i>
							</p>
						</div>
					
						<div class="inner" style="width:33%;">
							<p class="titles">Transition Effect</p>
							<p>
							<select name="sgallery_t">
								<option value="fade"<?php if($sgallery_t == 'fade') { echo ' SELECTED'; } ?>>Fade</option>
								<option value="flash"<?php if($sgallery_t == 'flash') { echo ' SELECTED'; } ?>>Flash</option>
								<option value="slide"<?php if($sgallery_t == 'slide') { echo ' SELECTED'; } ?>>Slide</option>
							</select>
							</p>
						</div>
					
						<div class="inner" style="width:33%;">
							<p class="titles">Transition Speed</p>
							<p>
							<input type="text" name="sgallery_ts" value="<?php echo $sgallery_ts; ?>" size="3" /> <i>milliseconds</i>
							</p>
						</div>
						
					</div>	
				
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Border Size</p>
							<p>
							<input type="text" name="sgallery_b" value="<?php echo $sgallery_b; ?>" size="2" /> <i>pixels</i>
							</p>
						</div>
					
						<div class="inner" style="width:33%;">
							<p class="titles">Border Color</p>
								<p>#<input type="text" name="sgallery_b_color" id="sgallery_b_color" value="<?php echo $sgallery_b_color; ?>" size="7"  style="background:#<?php echo $sgallery_b_color; ?>;" /> Color</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="notes">
								<b>Note:</b> <i>If a border size is specified, image sizes will be slightly reduced to allow for the size
								of the image border.</i>
							</p>
						</div>
						
					</div>			  
					  
				</div>
		</div>
	
			<div class="sub-options"><span id="comments"><b>Blog Style Gallery Options</b> - Maximum gallery width is 960px.</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
					<div class="left" style="width:99%;">
						
						<div class="inner" style="width:33%;">
							<p class="titles">Image Display Size</p>
							<p>
							<input type="text" name="gallery_w2" id="gallery_w2" value="<?php echo $gallery_w2; ?>" size="3" />px
							</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="titles">Image Spacing</p>
							<p>
							<input type="text" name="gallery_pad2" id="gallery_pad2" value="<?php echo $gallery_pad2; ?>" size="3" />px
							</p>
						</div>
						
						<!--
						<div class="inner" style="width:33%;">
							<p class="titles">Max Height (blank for none)</p>
							<p>
							<input type="text" name="gallery_h2" id="gallery_h2" value="<?php echo $gallery_h2; ?>" size="3" />px
							</p>
						</div>
						-->
						
						<div class="inner" style="width:33%;">
							<p class="notes">
								<b>Note:</b> <i>All images will be set to the size specified here. It's recommended to upload images at least as wide or tall as this size setting.</i>
							</p>
						</div>
						
						<!--
						<div class="inner">
							<p class="titles">Image Captions</p>
							<p>
							<input type="radio" name="gallery_cap2" id="gallery_cap2" value="ON" <?php if($gallery_cap2 == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="gallery_cap2" id="gallery_cap2" value="OFF" <?php if($gallery_cap2 == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						-->
						
					</div>		
				
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Border Size</p>
							<p>
							<input type="text" name="bgallery_b" value="<?php echo $bgallery_b; ?>" size="2" /> <i>pixels</i>
							</p>
						</div>
					
						<div class="inner" style="width:33%;">
							<p class="titles">Border Color</p>
								<p>#<input type="text" name="bgallery_b_color" id="bgallery_b_color" value="<?php echo $bgallery_b_color; ?>" size="7"  style="background:#<?php echo $bgallery_b_color; ?>;" /> Color</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="notes">
								<b>Note:</b> <i>If a border size is specified, image sizes will be slightly reduced to allow for the size
								of the image border.</i>
							</p>
						</div>
						
					</div>		  
					  
				</div>
					
		</div>
			
			<div class="sub-options"><span id="comments"><b>Horizontal Filmstrip Gallery Options</b> - Maximum gallery width is 960px & maximum thumbnail width of 300px recommended.</span></div>
			<div class="option-content" style="border-bottom:0;">
			
				<div id="option-container">
				
					<div class="left" style="width:99%">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Thumbnail Size</p>
							<p>
							<input type="text" name="thumbnail_w3" id="thumbnail_w3" value="<?php echo $thumbnail_w3; ?>" size="2" />w
							&nbsp;
							<input type="text" name="thumbnail_h3" id="thumbnail_h3" value="<?php echo $thumbnail_h3; ?>" size="2" />h
							</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="titles">Gallery Width</p>
							<p>
							<input type="text" name="gallery_w3" id="gallery_w3" value="<?php echo $gallery_w3; ?>" size="3" />px
							</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="titles">Display Play / Pause Button</p>
							<p>
							<input type="radio" name="gallery_buttons3" id="gallery_buttons3" value="ON" <?php if($gallery_buttons3 == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="gallery_buttons3" id="gallery_buttons3" value="OFF" <?php if($gallery_buttons3 == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
					</div>		
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:33%;">
							<p class="titles">Image Captions</p>
							<p>
							<input type="radio" name="gallery_cap3" id="gallery_cap3" value="ON" <?php if($gallery_cap3 == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="gallery_cap3" id="gallery_cap3" value="OFF" <?php if($gallery_cap3 == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
						<div class="inner" style="width:21%;">
							<p class="titles">Caption Location</p>
							<p>
							<select name="hfgallery_cap_loc">
								<option value="top"<?php if($hfgallery_cap_loc == 'top') { echo ' selected="selected"'; } ?>>Top</option>
								<option value="bottom"<?php if($hfgallery_cap_loc == 'bottom') { echo ' selected="selected"'; } ?>>Bottom</option>
								<option value="middle"<?php if($hfgallery_cap_loc == 'middle') { echo ' selected="selected"'; } ?>>Middle</option>
								<option value="overlay_top"<?php if($hfgallery_cap_loc == 'overlay_top') { echo ' selected="selected"'; } ?>>Overlay Top</option>
								<option value="overlay_bottom"<?php if($hfgallery_cap_loc == 'overlay_bottom' || $hfgallery_cap_loc == null) { echo ' selected="selected"'; } ?>>Overlay Bottom</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:45%;">
							<p class="notes" style="">
								<b>Top</b> - <i>Caption at the top, before everything else</i><br/>
								<b>Bottom</b> - <i>Caption at the bottom, after everything else</i><br/>
								<b>Middle</b> - <i>Caption is in-between images and thumbnails</i><br/>
								<b>Overlay Top</b> - <i>Caption is overlaid on the top of images</i><br/>
								<b>Overlay Bottom</b> - <i>Caption is overlaid on the bottom of images</i><br/>
							</p>
						</div>
						
					</div>		  
				
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Slideshow Speed</p>
							<p>
							<input type="text" name="hfgallery_s" value="<?php echo $hfgallery_s; ?>" size="2" /> <i>seconds</i>
							</p>
						</div>
					
						<div class="inner" style="width:33%;">
							<p class="titles">Transition Effect</p>
							<p>
							<select name="hfgallery_t">
								<option value="fade"<?php if($hfgallery_t == 'fade') { echo ' SELECTED'; } ?>>Fade</option>
								<option value="flash"<?php if($hfgallery_t == 'flash') { echo ' SELECTED'; } ?>>Flash</option>
								<option value="slide"<?php if($hfgallery_t == 'slide') { echo ' SELECTED'; } ?>>Slide</option>
							</select>
							</p>
						</div>
					
						<div class="inner" style="width:33%;">
							<p class="titles">Transition Speed</p>
							<p>
							<input type="text" name="hfgallery_ts" value="<?php echo $hfgallery_ts; ?>" size="3" /> <i>milliseconds</i>
							</p>
						</div>
						
					</div>
					
					
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Thumbnail Cropping</p>
							<p>
							<input type="radio" name="film_crop" id="film_crop" value="ON" <?php if($film_crop == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="film_crop" id="film_crop" value="OFF" <?php if($film_crop == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
						<div class="inner" style="width:63%;">
							<p class="notes">
								<b>Note:</b> <i>With thumbnail cropping ON all thumbnails will be sized at the exact
								height and width specified and will be automatically cropped. Turning cropping OFF
								will display thumbnails at their original aspect ratio.</i>
							</p>
						</div>
						
					</div>		
				
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Border Size</p>
							<p>
							<input type="text" name="hfgallery_b" value="<?php echo $hfgallery_b; ?>" size="2" /> <i>pixels</i>
							</p>
						</div>
					
						<div class="inner" style="width:33%;">
							<p class="titles">Border Color</p>
								<p>#<input type="text" name="hfgallery_b_color" id="hfgallery_b_color" value="<?php echo $hfgallery_b_color; ?>" size="7"  style="background:#<?php echo $hfgallery_b_color; ?>;" /> Color</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="notes">
								<b>Note:</b> <i>If a border size is specified, image and thumbnail sizes will be slightly reduced to allow for
								the size of the border.</i>
							</p>
						</div>
						
					</div>	
										  
				</div>
		</div>
			
			<div class="sub-options"><span id="comments"><b>Thumbnail Gallery Options</b> - A maximum thumbnail width of 300px is recommended.</span></div>
			<div class="option-content" style="border-bottom:0;">
			
				<div id="option-container">
				
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Thumbnail Size</p>
							<p>
							<input type="text" name="thumbnail_w4" id="thumbnail_w4" value="<?php echo $thumbnail_w4; ?>" size="2" />w
							&nbsp;
							<input type="text" name="thumbnail_h4" id="thumbnail_h4" value="<?php echo $thumbnail_h4; ?>" size="2" />h
							</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="titles">Image Captions</p>
							<p>
							<input type="radio" name="gallery_cap4" id="gallery_cap4" value="ON" <?php if($gallery_cap4 == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="gallery_cap4" id="gallery_cap4" value="OFF" <?php if($gallery_cap4 == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
					</div>
					
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Thumbnail Cropping</p>
							<p>
							<input type="radio" name="thumb_crop" id="thumb_crop" value="ON" <?php if($thumb_crop == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="thumb_crop" id="thumb_crop" value="OFF" <?php if($thumb_crop == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
						<div class="inner" style="width:63%;">
							<p class="notes">
								<b>Note:</b> <i>With thumbnail cropping ON all thumbnails will be sized at the exact
								height and width specified and will be automatically cropped. Turning cropping OFF
								will display thumbnails at their original aspect ratio <b>BUT</b> will result in uneven
								gallery layouts if mixed sized images are used.</i>
							</p>
						</div>
						
					</div>		
				
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:33%;">
							<p class="titles">Border Size</p>
							<p>
							<input type="text" name="tgallery_b" value="<?php echo $tgallery_b; ?>" size="2" /> <i>pixels</i>
							</p>
						</div>
					
						<div class="inner" style="width:33%;">
							<p class="titles">Border Color</p>
								<p>#<input type="text" name="tgallery_b_color" id="tgallery_b_color" value="<?php echo $tgallery_b_color; ?>" size="7"  style="background:#<?php echo $tgallery_b_color; ?>;" /> Color</p>
						</div>
						
						<div class="inner" style="width:33%;">
							<p class="notes">
								<b>Note:</b> <i>If a border size is specified, image sizes will be slightly reduced to allow for the size
								of the image border.</i>
							</p>
						</div>
						
					</div>		
					  
				</div>
					
		</div>
			
			<div class="sub-options"><span id="comments"><b>Album - List View Options</b> - Set color options for list view albums</span></div>
			<div class="option-content" style="border-bottom:0;">
			
				<div id="option-container">
					
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:24%;">
							<p class="titles">Background Color</p>
							<p>#<input type="text" name="albuml_back_color" id="albuml_back_color" value="<?php echo $albuml_back_color; ?>" size="7"  style="background:#<?php echo $albuml_back_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Title/Font Color</p>
							<p>#<input type="text" name="albuml_font_color" id="albuml_font_color" value="<?php echo $albuml_font_color; ?>" size="7"  style="background:#<?php echo $albuml_font_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Title Size</p>
							<p><input type="text" name="albuml_font_size" id="albuml_font_size" value="<?php echo $albuml_font_size; ?>" size="2" />px</p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Border Color</p>
							<p>#<input type="text" name="albuml_line_color" id="albuml_line_color" value="<?php echo $albuml_line_color; ?>" size="7"  style="background:#<?php echo $albuml_line_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Border Size</p>
							<p><input type="text" name="albuml_line_size" id="albuml_line_size" value="<?php echo $albuml_line_size; ?>" size="2" />px</p>
						</div>
						
					</div>
					  
				</div>
					
		</div>
			
			<div class="sub-options"><span id="comments"><b>Album - Grid View Options</b> - Set color options for grid view albums</span></div>
			<div class="option-content" style="border-bottom:0;">
			
				<div id="option-container">
					
<?php if ($style_skin == 'legacy'): ?>
					<div class="left style-skin-legacy" style="width:99%;">
					
						<div class="inner" style="width:25%;">
							<p class="titles">Galleries Per Row</p>
							<p>
							<select name="albumg_per_row">
								<option value="2"<?php if($albumg_per_row == '2') { echo ' SELECTED'; } ?>>2</option>
								<option value="3"<?php if($albumg_per_row == '3') { echo ' SELECTED'; } ?>>3</option>
								<option value="4"<?php if($albumg_per_row == '4') { echo ' SELECTED'; } ?>>4</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:74%;">
							<p class="notes">
								<BR>
								<i>Thumbnail size will adjust automatically based on the number
								displayed per row.</i>
							</p>
						</div>
						
					</div>
<?php endif ?>
					
<?php if ($style_skin == 'modern'): ?>
					<div class="left style-skin-modern" style="width:99%;">
					
						<div class="inner" style="width:25%;">
							<p class="titles">Column Width</p>
							<p><input type="text" name="albumg_image_width" id="albumg_image_width" value="<?php echo $albumg_image_width; ?>" size="4" />px</p>
						</div>
						
						<div class="inner" style="width:74%;">
							<p class="notes">
								<BR>
								<i>The width chosen here will determine how many rows will be displayed based on the available space and it includes any padding and borders, so the thumbnail will be less wide that this value.</i>
							</p>
						</div>
						
					</div>
<?php endif ?>
					
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:24%;">
							<p class="titles">Background Color</p>
							<p>#<input type="text" name="albumg_back_color" id="albumg_back_color" value="<?php echo $albumg_back_color; ?>" size="7"  style="background:#<?php echo $albumg_back_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Title Color</p>
							<p>#<input type="text" name="albumg_font_color" id="albumg_font_color" value="<?php echo $albumg_font_color; ?>" size="7"  style="background:#<?php echo $albumg_font_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Title Size</p>
							<p><input type="text" name="albumg_font_size" id="albumg_font_size" value="<?php echo $albumg_font_size; ?>" size="2" />px</p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Border Color</p>
							<p>#<input type="text" name="albumg_line_color" id="albumg_line_color" value="<?php echo $albumg_line_color; ?>" size="7"  style="background:#<?php echo $albumg_line_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Border Size</p>
							<p><input type="text" name="albumg_line_size" id="albumg_line_size" value="<?php echo $albumg_line_size; ?>" size="2" />px</p>
						</div>
						
					</div>
					  
				</div>
					
		</div>
			
		
		<div class="option-content" style="border-top:0;">
			<div id="option-container" style="border-top:1px solid #CCC;padding-top:10px;margin-top:10px;"> 
				<div class="submit-button-wrapper">
					<input type="button" class="button" id="update-gallery" value="Save Gallery Settings" style="clear:none;" /> 
				</div>
				<div id="msg" style="float:left;"></div>
			</div>
		</div>
									  
		</form>	
	
	</div>
	
</div>
