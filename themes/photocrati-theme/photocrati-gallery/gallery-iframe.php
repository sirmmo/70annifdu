<?php ob_start();
define('ABSPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/');
include_once(ABSPATH.'wp-config.php');
include_once(ABSPATH.'wp-load.php');
include_once(ABSPATH.'wp-includes/wp-db.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<title></title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/styles/dynamic-style.php" />
<script type="text/javascript" src="<?php echo includes_url('js/jquery/jquery.js')?>"></script>
<script type="text/javascript">
//<![CDATA[
jQuery.noConflict();
document.oncontextmenu=disableit

function disableit()
{
	return false;
}
//]]>
</script>
<!-- XXX remove dependencies on theme -->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory') ?>/admin/css/jquery.lightbox-0.5.css" />
<script type="text/javascript" src="<?php bloginfo('template_directory') ?>/admin/js/jquery.lightbox-0.5.js"></script>
<?php

$style_skin = class_exists('Photocrati_Style_Manager') ? Photocrati_Style_Manager::get_active_preset()->style_skin : 'legacy';

$gallery_option_list = photocrati_gallery_option_list_get();

// Parameters passed to the iframe, basic initial validation
$gallery_id = $_GET['gal_id'];
$gallery_type = photocrati_gallery_type_name($_GET['gal_type']);

$gallery = photocrati_gallery_instance_get($gallery_id);
$gallery_info = photocrati_gallery_info_get($gallery_id);


// Each item in these arrays is a CSS property eg. 'position' => 'relative'
$gallery_style = array();
$gallery_stage_style = array();
$gallery_image_style = array();
$gallery_caption_style = array();
$gallery_thumbnail_container_style = array();
$gallery_thumbnail_wrap_style = array();
$gallery_thumbnail_style = array();

// Each variable holds JS code to run as Galleria's "extend" property, with own context i.e. $gallery_image_script has the Galleria image element as the "this" variable (will be applied to all images), same for thumbnails
$gallery_script = null;
$gallery_image_script = null;
$gallery_thumbnail_script = null;

$gallery_width = 960;
$gallery_height = 0;
$gallery_height_auto = false; // Leave gallery height as 'auto' i.e. not set on the container div
$gallery_aspect_ratio = 1.5;
$gallery_galleria_theme = null;
$gallery_galleria_properties = array(); // Additional Galleria parameters
$gallery_playback_interval = 0; // Interval in milliseconds, 0 = no playback
$gallery_playback_button_show = false; // Show play/pause button
$gallery_transition = null;
$gallery_transition_speed = 0;
$gallery_carousel_show = true;

$gallery_image_resolution = $gallery_option_list['image_resolution'] == '1' ? 'resized' : 'full';
$gallery_image_border_size = 0;
$gallery_image_border_color = 0;

$gallery_caption_show = $_GET['gal_cap'] == 'ON';
$gallery_caption_location_list = array('top', 'bottom', 'middle', 'overlay_top', 'overlay_bottom');
$gallery_caption_location = null; // default is 'overlay_bottom'
$gallery_caption_height = 52; // Only used when the caption location is not 'overlay_top' or 'overlay_bottom', as we need a fixed height for the caption in all other cases to avoid having the gallery height jumping up and down as caption's text length changes
$gallery_caption_margin = 10; // space between the thumbnails and the gallery stage (where the images are shown) when caption location is 'top', 'bottom' or 'middle', ignored otherwise

$gallery_thumbnail_show = false;
$gallery_thumbnail_crop = false;
$gallery_thumbnail_width = 110;
$gallery_thumbnail_height = 75;
$gallery_thumbnail_margin = 10; // space between the thumbnails and the gallery stage (where the images are shown) OR the gallery caption when caption location is 'top', 'bottom' or 'middle'

if ($gallery_info != null)
{
	$gallery_height_user = (int) $gallery_info->gal_height;
	$gallery_aspect_ratio_user = (float) $gallery_info->gal_aspect_ratio;

	if ($gallery_height_user > 0)
	{
		$gallery_height = $gallery_height_user;
	}

	if ($gallery_aspect_ratio_user != null)
	{
		$gallery_aspect_ratio = sprintf('%.3F', $gallery_aspect_ratio_user);
	}
}

switch ($gallery_type)
{
	case 'slideshow':
	{
		$gallery_width = (int) $gallery_option_list['gallery_w1'];

		$gallery_galleria_theme = 'classic';
		$gallery_playback_button_show = $gallery_option_list['gallery_buttons1'] == 'ON';
		$gallery_playback_interval = $gallery_option_list['sgallery_s'] * 1000;
		$gallery_transition = $gallery_option_list['sgallery_t'];
		$gallery_transition_speed = (int) $gallery_option_list['sgallery_ts'];

		$gallery_image_border_size = (int) $gallery_option_list['sgallery_b'];
		$gallery_image_border_color = $gallery_option_list['sgallery_b_color'];

		$gallery_caption_location = $gallery_option_list['sgallery_cap_loc'];

		break;
	}
	case 'blog':
	{
		$gallery_width = (int) $gallery_option_list['gallery_w2'];
		$gallery_height_auto = true;

		$gallery_galleria_theme = 'lightbox';

		$gallery_image_border_size = (int) $gallery_option_list['bgallery_b'];
		$gallery_image_border_color = $gallery_option_list['bgallery_b_color'];

		$gallery_carousel_show = false;
		$gallery_thumbnail_show = true;
		$gallery_thumbnail_crop = false;

		$gallery_thumbnail_width = ($gallery_width - ($gallery_image_border_size * 2));
		$gallery_thumbnail_height = $gallery_thumbnail_width;

		//$gallery_thumbnail_container_style['width'] = $gallery_width . 'px';
		$gallery_thumbnail_wrap_style['margin-bottom'] = $gallery_option_list['gallery_pad2'] . 'px !important';
		$gallery_thumbnail_wrap_style['clear'] = 'both !important';
		$gallery_thumbnail_style['cursor'] = 'default';

		$gallery_thumbnail_style['max-width'] = $gallery_thumbnail_width . 'px !important';
		
		// fix responsiveness
		if ($style_skin != 'legacy')
		{
			$gallery_thumbnail_style['position'] = 'static !important';
			$gallery_thumbnail_style['display'] = 'inline !important';
			$gallery_thumbnail_style['width'] = 'auto !important';
		
			$gallery_thumbnail_wrap_style['max-width'] = '100% !important';
			$gallery_thumbnail_wrap_style['height'] = 'auto !important';
			$gallery_thumbnail_wrap_style['display'] = 'block !important';
			$gallery_thumbnail_wrap_style['margin-left'] = 'auto !important';
			$gallery_thumbnail_wrap_style['margin-right'] = 'auto !important';
		}

		$gallery_script .= '
			// Added to notify parent
			this.bind("thumbnail", function() {
				// Set iframe height
				parent.iframe_' . $gallery_id . '_loaded();
			});';

		$gallery_galleria_properties['height'] = 0;

		break;
	}
	case 'filmstrip':
	{
		$gallery_width = (int) $gallery_option_list['gallery_w3'];

		$gallery_galleria_theme = 'classic';
		$gallery_playback_button_show = $gallery_option_list['gallery_buttons3'] == 'ON';
		$gallery_playback_interval = $gallery_option_list['hfgallery_s'] * 1000;
		$gallery_transition = $gallery_option_list['hfgallery_t'];
		$gallery_transition_speed = (int) $gallery_option_list['hfgallery_ts'];

		$gallery_image_border_size = (int) $gallery_option_list['hfgallery_b'];
		$gallery_image_border_color = $gallery_option_list['hfgallery_b_color'];

		$gallery_caption_location = $gallery_option_list['hfgallery_cap_loc'];

		$gallery_thumbnail_show = true;
		$gallery_thumbnail_crop = $gallery_option_list['film_crop'] == 'ON' ? true : 'height';
		$gallery_thumbnail_width = (int) $gallery_option_list['thumbnail_w3'];
		$gallery_thumbnail_height = (int) $gallery_option_list['thumbnail_h3'];

		$gallery_thumbnail_width = ($gallery_thumbnail_width - ($gallery_image_border_size * 2));
		$gallery_thumbnail_height = ($gallery_thumbnail_height - ($gallery_image_border_size * 2));

		$gallery_thumbnail_container_style['height'] = ($gallery_thumbnail_height + $gallery_image_border_size * 2) . 'px';

		$gallery_script .= '
			var thumbCont = this.get(\'thumbnails-container\');
			var navList = [ this.get(\'thumb-nav-left\'), this.get(\'thumb-nav-right\') ];
			jQuery(navList).css({ display : \'none\' });
			jQuery(navList).hover(
				function () {
					if (!jQuery(this).hasClass(\'disabled\'))
					{
						jQuery(this).stop().animate({
							opacity: 1
						}, \'fast\');
					}
				},
				function () {
					if (!jQuery(this).hasClass(\'disabled\'))
					{
						jQuery(this).stop().animate({
							opacity: 0.8
						}, \'fast\');
					}
				}
			);

			jQuery(thumbCont).hover(
				this.proxy(function() {
					var navList = [ this.get(\'thumb-nav-left\'), this.get(\'thumb-nav-right\') ];
					jQuery(navList).css({ display : \'block\' });
					jQuery(navList).stop().animate({
						opacity: 0.8
					});
				}),
				this.proxy(function() {
					var navList = [ this.get(\'thumb-nav-left\'), this.get(\'thumb-nav-right\') ];
					jQuery(navList).stop().animate({
						opacity: 0
					}, function () {
						jQuery(this).css({ display: \'none\' });
					});
				})
			);';

		break;
	}
	case 'thumbnail':
	{
		$gallery_width = (int)0;
		
		$gallery_galleria_theme = 'lightbox';
		$gallery_height_auto = true;

		$gallery_image_border_size = (int) $gallery_option_list['tgallery_b'];
		$gallery_image_border_color = $gallery_option_list['tgallery_b_color'];

		$gallery_carousel_show = false;
		$gallery_thumbnail_show = true;
		$gallery_thumbnail_crop = $gallery_option_list['thumb_crop'] == 'ON' ? true : false;
		$gallery_thumbnail_width = (int) $gallery_option_list['thumbnail_w4'];
		$gallery_thumbnail_height = (int) $gallery_option_list['thumbnail_h4'];

		$gallery_thumbnail_width = ($gallery_thumbnail_width - ($gallery_image_border_size * 2));
		$gallery_thumbnail_height = ($gallery_thumbnail_height - ($gallery_image_border_size * 2));

		$gallery_thumbnail_wrap_style['margin'] = '0px';

		$gallery_script .= '
			// Added to notify parent
			this.bind("thumbnail", function() {
				// Set iframe height
				parent.iframe_' . $gallery_id . '_loaded();
			});';

		$gallery_image_script .= '
			var s = jQuery(this).attr("src");
			var search = "/thumbnails/";
			var ind = s.lastIndexOf(search);
			var before = s.substring(0, ind);
			var after = s.substring(ind+search.length);
			var finished = before + "/full/" + after;
			';

		if($gallery_image_resolution == 'resized')
		{
			$gallery_image_script .= '
			jQuery(this).attr("href", jQuery(this).attr("src"));' . "\n";
		}
		else
		{
			$gallery_image_script .= '
			jQuery(this).attr("href", finished);' . "\n";
		}

		$gallery_image_script .= '
			jQuery(this).attr("rel", "lightbox");

			var imgname = jQuery(this).attr("src").split("/");' . "\n";

		// Note: here we need this large amount of \\\\\\ because of double escaping in both PHP and then JS
		$gallery_image_script .= '
			var imgId = imgname[imgname.length-1];
			imgId = imgId.replace(/([\'"])/gi, "\\\\\\$1");
			jQuery(this).attr("title", jQuery("[id$=\'-" + imgId + "\']").val());
			var thissrc = jQuery(this).attr("src");

			//parent.jQuery("#decoys").append(\'<a href="\'+finished+\'" style="display:none;" class="decoy" id="decoy_\'+imgname[imgname.length-1]+\'" rel="' . $gallery_id . '" title="Test">\'+finished+\'</a>\');

			jQuery(this).on("click", function() {
				listItem = jQuery(this);
				currId = jQuery(".galleria-image img").index(listItem) + 1;
				parent.jQuery("a#img_' . $gallery_id . '_"+currId).trigger("click");
			});';

		break;
	}
}

if ($gallery_image_border_size > 0)
{
	$gallery_image_border = 'solid ' . $gallery_image_border_size . 'px #' . $gallery_image_border_color;
	$gallery_image_style['border'] = $gallery_image_border;
	$gallery_thumbnail_wrap_style['border'] = $gallery_image_border;
}

$gallery_caption_location = strtolower($gallery_caption_location);

if ($gallery_caption_location == null || !in_array($gallery_caption_location, $gallery_caption_location_list))
{
	$gallery_caption_location = 'overlay_bottom';
}

if ($gallery_thumbnail_height == 0)
{
	$gallery_thumbnail_height = $gallery_thumbnail_width;
}

if ($gallery_thumbnail_width > 0 && $gallery_thumbnail_height > 0)
{
	if (!isset($gallery_thumbnail_wrap_style['width']) || $gallery_thumbnail_wrap_style['width'] === 0) 
	{
		$gallery_thumbnail_wrap_style['width'] = $gallery_thumbnail_width . 'px';
	}
	
	if (!isset($gallery_thumbnail_wrap_style['height']) || $gallery_thumbnail_wrap_style['height'] === 0) 
	{
		$gallery_thumbnail_wrap_style['height'] = $gallery_thumbnail_height . 'px';
	}
}

$out = null;

//$out .= '<script type="text/javascript" src="' . photocrati_gallery_file_uri('extra/jQuery/jquery.mousewheel.js') . '"></script>' . "\n";
$gallery_galleria_theme_folder = $gallery_galleria_theme;

if ($gallery_galleria_theme == 'classic' && $style_skin == 'legacy')
{
	$gallery_galleria_theme_folder = 'classic_legacy';
}

$out .= '<link rel="stylesheet" type="text/css" href="' . photocrati_gallery_file_uri('custom/galleria/themes/' . $gallery_galleria_theme_folder . '/galleria.' . $gallery_galleria_theme . '.css') . '" />' . "\n";

if ($style_skin != 'legacy')
{
	$out .= '<script type="text/javascript" src="' . photocrati_gallery_file_uri('extra/galleria/galleria.js') . '"></script>' . "\n";
}
else 
{
	$out .= '<script type="text/javascript" src="' . photocrati_gallery_file_uri('extra/galleria_legacy/galleria.js') . '"></script>' . "\n";
}

$out .= '<script type="text/javascript">
//<![CDATA[
Galleria.loadTheme(\'' . photocrati_gallery_file_uri('custom/galleria/themes/' . $gallery_galleria_theme_folder . '/galleria.' . $gallery_galleria_theme . '.js') . '\');
//]]>
</script>' . "\n";

$out .= '<style type="text/css">body, .galleria-container { background:transparent; }</style>';

$out .= '<style type="text/css">
.gallery {
margin:0 auto;
}
.galleria-stage {
top:0;
left:0;
right:0;
bottom:0;';

foreach ($gallery_stage_style as $property_name => $property_value)
{
	$out .= "\n" . $property_name . ':' . $property_value . ';';
}

$out .= '
}
.galleria-stage .galleria-image img {';

foreach ($gallery_image_style as $property_name => $property_value)
{
	$out .= "\n" . $property_name . ':' . $property_value . ';';
}

$out .= '
}
.galleria-info {
display: none;
left:0;
top:0;
width:100%;
z-index: 2;
background-color: #000;
font-size:12px;
line-height:17px;
color: #eee;
text-align: center;
opacity: 0.75;
ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=75)";
filter:	alpha(opacity=75);';

foreach ($gallery_caption_style as $property_name => $property_value)
{
	$out .= "\n" . $property_name . ':' . $property_value . ';';
}

$out .= '
}
.galleria-info-text {
padding: 3px 10px 8px 10px;
display: none;
opacity: 1;
ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
filter:	alpha(opacity=100);
}
.galleria-info-title {
font-weight: bold;
margin: 0;
}
.galleria-info-description {
margin: 7px 0 0 0;
}
.galleria-thumbnails-container {
height:auto;
left:0;
right:0;';

foreach ($gallery_thumbnail_container_style as $property_name => $property_value)
{
	$out .= "\n" . $property_name . ':' . $property_value . ';';
}

$out .= '
}
.galleria-thumbnails {
margin:0 auto;
}
.galleria-thumbnails .galleria-image {';

foreach ($gallery_thumbnail_wrap_style as $property_name => $property_value)
{
	$out .= "\n" . $property_name . ':' . $property_value . ';';
}

$out .= '
}
.galleria-thumbnails .galleria-image img {';

foreach ($gallery_thumbnail_style as $property_name => $property_value)
{
	$out .= "\n" . $property_name . ':' . $property_value . ';';
}

$out .= '
}
.galleria-thumb-nav-left,
.galleria-thumb-nav-right {
}
.galleria-counter {
z-index: 5;
background: white;
color: #333;
padding: 3px;
opacity: .75;
ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=75)";
filter:	alpha(opacity=75);
}
.galleria-loader {
background-color:	transparent !important;
width: 50px;
height:	50px;
position:	absolute;
top: 50%;
left:	50%;
margin-left: -25px;
margin-top: -25px;
z-index: 2;
display: none;
background-image: url(\'' . photocrati_gallery_file_uri('image/gallery-ajax-loader.gif') . '\');
}
.play_wrapper,
.pause_wrapper {
position: absolute;
top: 50%;
left: 50%;
width: 60px;
height: 60px;
margin-top: -30px;
margin-left: -30px;
moz-opacity: .15;
opacity: .15;
ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=15)";
filter: alpha(opacity=15);
}
</style>' . "\n";


if ($gallery_image_script != null)
{
	$gallery_image_script = '{
	' . $gallery_image_script . '
	}';
}

$gallery_image_script = '
		function md(event)
		{
			try { if (event.button==2||event.button==3) return false; }
			catch (e) { if (event.which == 3) return false; }
		}

		jQuery(this)[0].oncontextmenu = function() {return false;};
		jQuery(this)[0].ondragstart   = function() {return false;};
		jQuery(this)[0].onmousedown   = md;
		' . $gallery_image_script;

if ($gallery_playback_button_show)
{
$gallery_script .= '
			var gallery = this;
			jQuery(".galleria-stage").append(\'<div class="play_wrapper" style="display:none;z-index:99;position:absolute;moz-opacity:.0;opacity:.0;filter:alpha(opacity=0);"><a id="play_button" style="cursor:pointer;"><img src="' . photocrati_gallery_file_uri('image/play_button.png') . '"></a></div>\');
			jQuery(".galleria-stage").append(\'<div class="pause_wrapper" style="z-index:99;position:absolute;moz-opacity:.0;opacity:.0;filter:alpha(opacity=0);"><a id="pause_button" style="cursor:pointer;"><img src="' . photocrati_gallery_file_uri('image/pause_button.png') . '"></a></div>\');

			jQuery(".galleria-container").hover(
				function () {
					jQuery(".play_wrapper").css("moz-opacity",".75").css("opacity",".75").css("filter","alpha(opacity=75)");
					jQuery(".pause_wrapper").css("moz-opacity",".75").css("opacity",".75").css("filter","alpha(opacity=75)");
				},
				function () {
					jQuery(".play_wrapper").css("moz-opacity",".0").css("opacity",".0").css("filter","alpha(opacity=0)");
					jQuery(".pause_wrapper").css("moz-opacity",".0").css("opacity",".0").css("filter","alpha(opacity=0)");
				}
			);
			jQuery(".play_wrapper").on("click", function() {
				gallery.play();
				jQuery(".play_wrapper").hide();
				jQuery(".pause_wrapper").show();
			});
			jQuery(".pause_wrapper").on("click", function() {
				gallery.pause();
				jQuery(".play_wrapper").show();
				jQuery(".pause_wrapper").hide();
			});' . "\n";
}

if ($gallery_playback_interval > 0)
{
	$gallery_script .= '
			this.play(' . $gallery_playback_interval . ');
			';
}

if ($gallery_caption_show)
{
	$gallery_script .= '

	jQuery(\'.galleria-info\').hover(
		function () {
			var self = jQuery(this);
			var text = self.find(\'.galleria-info-text\');
			var diff = self.height() - text.height();

			if (diff < 0)
			{
				self.stop().animate({ scrollTop: -diff }, ((-diff) / 17) * 450);
			}
		},
		function () {
			var self = jQuery(this);
			var text = self.find(\'.galleria-info-text\');
			var diff = self.height() - text.height();

			if (diff < 0)
			{
				self.stop().animate({ scrollTop: 0 }, \'fast\');
			}
		}
	);';
}

$gallery_script .= '
				if (jQuery(".galleria-image img").size() < gallerydata.length) {
					this.bind("thumbnail", function () {
						var imgList = jQuery(".galleria-image img:not([href])");
					
						if (imgList.size() > 0) {
							imgList.each(function(index) {
							' . $gallery_image_script . '
							});
						}
					});
				}
				else {
					jQuery(".galleria-image img").each(function(index) {
					' . $gallery_image_script . '
					});
				}';

$json = photocrati_gallery_instance_json($gallery, array_merge($gallery_option_list, array('gallery_type' => $gallery_type)));

$out .= '
<script type="text/javascript">
//<![CDATA[
parent.jQuery("iFrame#g' . $gallery_id . '").css("min-height","100px");
//parent.jQuery("iFrame#g' . $gallery_id . '").css("width","800px");
parent.jQuery("iFrame#g' . $gallery_id . '")[0].oncontextmenu = function() {return false;}

jQuery(document).ready(function($) {
	var gallery = jQuery(\'#gallery-' . $gallery_id . '\');
	
	var recalculateDimensions = function (gallery) {
	var layoutWidth = gallery.parent().width();
	var galleryWidth = ' . $gallery_width . ';
	var galleryHeight = ' . $gallery_height . ';
	var stageHeight = 0;
	var thumbWidth = ' . $gallery_thumbnail_width . ';
	var thumbHeight = ' . $gallery_thumbnail_height . ';
	var aspectRatio = ' . $gallery_aspect_ratio . ';
	var customStyle = \'\';
	var captionSpace = 0;
	var thumbSpace = 0;';

if ($gallery_thumbnail_show)
{
	$out .= '
	thumbSpace = thumbHeight + ' . ($gallery_image_border_size * 2) . ' + ' . $gallery_thumbnail_margin . ';';
}

if ($gallery_caption_show)
{
	$out .= '
	var galleryCaption = gallery.find(\'.galleria-info\');

	customStyle += \'.galleria-info { \\n\';';

	if (in_array($gallery_caption_location, array('top', 'bottom', 'middle')))
	{
		$out .= '
	captionSpace = ' . $gallery_caption_height . ' + ' . $gallery_caption_margin . ';
	customStyle += \'height: ' . $gallery_caption_height . 'px;\\n\';
	customStyle += \'overflow: hidden;\\n\';
	customStyle += \'background: transparent;\\n\';
	customStyle += \'color: \' + parent.jQuery("iFrame#g' . $gallery_id . '").parent().css(\'color\') + \';\\n\';';
	}

	if (in_array($gallery_caption_location, array('overlay_bottom', 'middle')))
	{
		$out .= '
	customStyle += \'top: auto;\\nbottom: \' + thumbSpace + \'px;\\n\';';
	}

	if ($gallery_caption_location == 'bottom')
	{
		$out .= '
	customStyle += \'top: auto;\\nbottom: 0px;\\n\';';
	}

	$out .= '
	customStyle += \'}\';';

	if ($gallery_caption_location == 'top')
	{
		$out .= '
	customStyle += \'.galleria-stage { bottom: auto; top: \' + captionSpace +\'px; }\\n\';';
	}
	else if ($gallery_caption_location == 'bottom')
	{
		$out .= '
	customStyle += \'.galleria-thumbnails-container { top: auto; bottom: \' + captionSpace +\'px; }\\n\';';
	}
}

	// Note: the following <style> tag addition is necessary because simply using .css() does not work, it seems the height gets lost (probably overwritten by Galleria)
$out .= '

	if (!galleryWidth || galleryWidth > layoutWidth)
	{
		galleryWidth = layoutWidth;
	}

	if (galleryWidth % 2 > 0)
	{
		galleryWidth -= 1;
	}';

// Small exception for blog style galleries
if ($gallery_type == 'blog')
{
	$out .= '

	customStyle += \'.galleria-thumbnails .galleria-image { max-width: \' + (galleryWidth - ' . ($gallery_image_border_size * 2) . ') + \'px !important; }\\n\';';
	
	if ($style_skin != 'legacy')
	{
		$out .= '
	customStyle += \'.galleria-thumbnails .galleria-image img { max-width: 100% !important; height: auto !important; }\\n\';';
	}
}

// Small exception for slideshow/filmstrip style galleries - used to work in legacy so we leave it like that there
if ($gallery_type == 'slideshow' || $gallery_type == 'filmstrip')
{
	if ($style_skin != 'legacy')
	{
		$out .= '
	customStyle += \'.galleria-stage .galleria-image img { margin-left: -' . ($gallery_image_border_size) . 'px; margin-top: -' . ($gallery_image_border_size) . 'px; }\\n\';';
	}
}

$out .= '

	if (galleryHeight == 0)
	{' .
		/* In order to keep the aspect ratio consistent we have to first subtract the borders from the width and then add them back again to the height */ '
		stageHeight = Math.round((galleryWidth - ' . ($gallery_image_border_size * 2) . ') / aspectRatio) + ' . ($gallery_image_border_size * 2) . ';
		galleryHeight = stageHeight + (captionSpace + thumbSpace);
	}
	else
	{
		stageHeight = galleryHeight - (captionSpace + thumbSpace);
	}

	customStyle += \'.galleria-stage { height: \' + stageHeight + \'px; }\' + \'\\n\';

	if (customStyle)
	{
		var styleElem = jQuery(\'#photocrati-galleria-custom\');
		
		if (styleElem.size() > 0) 
		{
			styleElem.remove();
		}
		
		styleElem = jQuery(\'<style type="text/css" id="photocrati-galleria-custom"></style>\').appendTo(\'head\');
		var style = styleElem.get(0);
		
		if (style.styleSheet){
			style.styleSheet.cssText = customStyle;
		} else {
			style.appendChild(document.createTextNode(customStyle));
		}
	}

	gallery.width(galleryWidth);';

if (!$gallery_height_auto)
{
$out .=
	'
	gallery.height(galleryHeight);';
}

$out .= '
	};
	
	recalculateDimensions(gallery);

  var elems = jQuery(window);
  		
  if (window.parent != null && window.parent != window) {
      elems = elems.add(jQuery(window.parent));
  }
      		
	elems.on("resize orientationchange onfullscreenchange onmozfullscreenchange onwebkitfullscreenchange", function (event) {
		
		if (typeof(window.photocratiThemeGalleryResponsiveTimer) !== "undefined") {
			clearTimeout(window.photocratiThemeGalleryResponsiveTimer);
			window.photocratiThemeGalleryResponsiveTimer = 0;
		}
		
		// note: tried to use timers to improve performance but it wasn\'t working well
		window.photocratiThemeGalleryResponsiveTimer = setTimeout(function() { 
			recalculateDimensions(gallery); 
			try { parent.iframe_' . $gallery_id . '_loaded(); } catch (ex) {}
		}, 200);
	});
	';

$out .=
	'
	var gallerydata = ' . $json . ';
	
	gallery.galleria({
		debug: false,
		carousel: ' . ($gallery_carousel_show ? 'true' : 'false') . ',
		imageMargin: ' . ($gallery_image_border_size) . ',
		imagePosition: \'center center\',
		transition: \'' . $gallery_transition . '\',
		transition_speed: ' . $gallery_transition_speed . ',
		imageCrop: false,
		dataType: \'json\',
		dataSource: gallerydata,
		maxScaleRatio: 1,
		thumbnails: ' . ($gallery_thumbnail_show ? 'true' : 'false') . ',
		thumbCrop: ' . (is_string($gallery_thumbnail_crop) ? '\'' . addslashes($gallery_thumbnail_crop) . '\'' : ($gallery_thumbnail_crop ? 'true' : 'false')) . ',
		thumbFit: true,
		responsive: true,
		lightbox: false,
		_toggleInfo: false,';

if (!$gallery_caption_show)
{
	$out .= "\n" . 'showInfo: false,';
}

$out .= '
		extend: function() {
			// Added to notify parent
			this.bind("loadfinish", function() {
				// Set iframe height
				parent.iframe_' . $gallery_id . '_loaded();
			});

			var stage = this.get("stage");

			function md(event)
			{
				try { if (event.button==2||event.button==3) return false; }
				catch (e) { if (event.which == 3) return false; }
			}
			jQuery(stage)[0].oncontextmenu = function() {return false;}
			jQuery(stage)[0].ondragstart   = function() {return false;}
			jQuery(stage)[0].onmousedown   = md;
			
			' . $gallery_script . '
		}';

foreach ($gallery_galleria_properties as $property_name => $property_value)
{
	if (is_string($property_value))
	{
		$property_value = '\'' . addslashes($property_value) . '\'';
	}

	$out .= ',' . "\n		" . $property_name . ': ' . $property_value;
}

$out .= '
	});
});';

$out .= '
//]]>
</script>' . "\n";

echo $out;

?>

</head>
<body style="margin:0;padding:0;background:transparent;">

<?php

$out = '<div id="content"><div class="gallery" id="gallery-' . $gallery_id . '"></div></div>';

echo $out;

?>
</body>
</html>
