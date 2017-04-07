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

include_once(dirname(__FILE__) . str_replace('/', DIRECTORY_SEPARATOR, '/../functions/admin-upload.php'));

	global $wpdb;
	$gallery = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1", ARRAY_A);
	foreach ($gallery as $key => $value) {
		$$key = $value;
	}
    $preset = Photocrati_Style_Manager::get_active_preset();
    extract($preset->to_array());

$option_disable_upload = false;
$current_version = get_bloginfo('version');

if (version_compare($current_version, '3.2', '>='))
{
	$option_disable_upload = true;
}
?>
<script type="text/javascript" src="<?php echo includes_url('js/swfobject.js')?>"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/admin/js/jquery.uploadify.v2.1.4.js"></script>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function() {

	jQuery(".options").corner("top");

	jQuery("#view-theme").fancybox({
		'speedIn'		:	600,
		'speedOut'		:	200,
		'width'			:	1020,
		'height'		:	450,
		'overlayShow'	:	true
	});

	jQuery("[id$='_preview']").fancybox();

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

	//Default Action
	jQuery(".tab_content").hide(); //Hide all content
	jQuery("ul.tabs li:last").addClass("active").show(); //Activate first tab
	jQuery(".tab_content:last").show(); //Show first tab content

	//On Click Event
	jQuery("ul.tabs li").on('click', function() {
		jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
		jQuery(this).addClass("active"); //Add "active" class to selected tab
		jQuery(".tab_content").hide(); //Hide all tab content
		var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		jQuery(activeTab).fadeIn(); //Fade in the active content
		return false;
	});

    jQuery("[id^=add_image_]").on('click', function() {
        currentId = jQuery(this).attr("id");
        formfield = jQuery("#"+currentId.substr(10)).attr("name");
        tb_show("Upload an MP3 file", "media-upload.php?type=audio&amp;post_id=1&amp;TB_iframe=true");
        return false;
    });

});
</script>

<div id="admin-wrapper">

	<div id="header-left">
    <img src="<?php bloginfo('template_directory'); ?>/admin/images/ph-logo.gif" align="absmiddle" />  &nbsp;<a id="view-theme" class="iframe" href="<?php bloginfo('wpurl'); ?>" style="text-decoration:none;" /><input type="button" class="button" value="View Theme" /></a>
    </div>

    <div id="header-right">
    <?php theme_version(); ?>
    </div>

</div>

<div style="height:132px;width:100%;margin-top:10px;clear:both;">

	<div style="float:left;width:210px;height:132px;margin-right:40px;background:url(<?php bloginfo('template_directory'); ?>/admin/images/web-layout.gif) no-repeat;"></div>
	<div style="float:left;width:525px;height:132px;text-align:right;">

		<br>
		<p style="font-size:13px;margin:20px 0 -10px 0;"><strong>Which part of your theme do you want to customize?</strong></p>
		<p style="font-size:13px;">Once finished you can save your settings as a custom theme <a href="admin.php?page=choose-theme">here</a>.</p>

	</div>

</div>

<div id="tabs_wrapper">

	<ul class="tabs">
		<li><a href="#custom_tab5">Footer</a></li>
		<li><a href="#custom_tab4">Sidebar</a></li>
		<?php if($dynamic_style) { ?>
		<li><a href="#custom_tab3">Body</a></li>
		<li><a href="#custom_tab2">Header</a></li>
		<li><a href="#custom_tab1">Global</a></li>
		<?php } ?>
	</ul>

	<div class="tab_container">
		<div id="custom_tab5" class="tab_content">

		<script type="text/javascript">
		jQuery(document).ready(function()
		{

			jQuery("#footer_background_style").change(function()
			{
				if (jQuery("#footer_background_style").val() == 'color') {
					jQuery("#footer_background_color").val('FFFFFF');
					jQuery("#footer_background_color").css('background', '#FFFFFF');
					jQuery("#footer-color").show();
				} else {
					jQuery("#footer_background_color").val('transparent');
					jQuery("#footer-color").hide();
				}
			});

			jQuery("#update-footer-font").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#footer-font', '#msgff');
			});

			jQuery("#update-footer-height").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#footer-height', '#msgfh');
			});

			jQuery("#update-footer-widgets").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#footer-widgets', '#msgfw');
			});


			jQuery("#update-footer").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#footer-options', '#msgfu');
			});

		});
		</script>

		<div id="container">

			<?php if($dynamic_style) { ?>
			<div class="options">Footer & Footer Widget Styles</div>

				<div class="sub-options"><span id="comments">Here you can set the footer font color and size</span></div>
				<div class="option-content">

					<div id="option-container">

					<form name="footer-font" id="footer-font" method="post">

						<div class="left" style="width:60%">

							<div class="inner">
								<p class="titles">Footer Background Style</p>
								<p>
								<select id="footer_background_style">
									<option value="transparent"<?php if($footer_background == 'transparent') {echo ' SELECTED'; } ?>>Transparent </option>
									<option value="color"<?php if($footer_background <> 'transparent') {echo ' SELECTED'; } ?>>Color </option>
								</select>
								</p>
							</div>
							<div class="inner" id="footer-color"<?php if($footer_background == 'transparent') {echo ' style="display:none;"'; } ?>>
								<p class="titles">Background Color</p>
								<p>#<input type="text" name="footer_background" id="footer_background_color" value="<?php if($footer_background <> 'transparent') { echo $footer_background; } else { echo 'transparent'; } ?>" size="7"  <?php if($footer_background <> 'transparent') { ?>style="background:#<?php echo $footer_background; ?>;"<?php } ?> /> Color</p>
								<p>%<input type="text" name="footer_opacity" id="footer_opacity" value="<?php echo $footer_opacity; ?>" size="7" /> Opacity</p>
							</div>
						</div>
						<div class="left style-skin-modern" style="border:0; width: 46%;">

									<p class="titles">Footer Drop Shadow</p>
									<p><input type="text" name="footer_drop_shadow_size" id="footer_drop_shadow_size" value="<?php echo $footer_drop_shadow_size; ?>" size="7" style="" /> Shadow Size</p>
						</div>

                        <div class="left" style="border:0;width:100%;">
                            <?php Photocrati_Fonts::render_font_fields('Footer', array(
                                'font_family_field_name'    =>  'footer_font_style',
                                'font_family_value'         =>  $footer_font_style,
                                'font_color_field_name'     =>  'footer_font_color',
                                'font_color_value'          =>  $footer_font_color,
                                'font_size_field_name'      =>  'footer_font',
                                'font_size_value'           =>  $footer_font,
                                'font_weight_field_name'    =>  'footer_font_weight',
                                'font_weight_value'         =>  $footer_font_weight,
                                'font_italics_field_name'   =>  'footer_font_italic',
                                'font_italics_value'        =>  $footer_font_italic,
                                'font_decoration_field_name'=>  'footer_font_decoration',
                                'font_decoration_value'     =>  $footer_font_decoration,
                                'font_case_field_name'      =>  'footer_font_case',
                                'font_case_value'           =>  $footer_font_case
                            )); ?>
                        </div>

						<div class="left-sm">

								<div class="inner">
									<p class="titles">Link Color</p>
									<p>#<input type="text" name="footer_link_color" id="footer_link_color" value="<?php echo $footer_link_color; ?>" size="7" style="background:#<?php echo $footer_link_color; ?>;" /> Color</p>
								</div>

						</div>

						<div class="right-lg" id="right-lg">

								<div class="left" style="border:0;width:50%;">
									<p class="titles">Hover Color</p>
									<p>#<input type="text" name="footer_link_hover_color" id="footer_link_hover_color" value="<?php echo $footer_link_hover_color; ?>" size="7" style="background:#<?php echo $footer_link_hover_color; ?>;" /> Color</p>
								</div>

								<div class="right" style="border:0;width:45%;">
									<p class="titles">Hover Style</p>
									<p>
									<select name="footer_link_hover_style">
										<option value="none"<?php if($footer_link_hover_style == 'none') {echo ' SELECTED'; } ?>>None </option>
										<option value="underline"<?php if($footer_link_hover_style == 'underline') {echo ' SELECTED'; } ?>>Underline </option>
										<option value="overline"<?php if($footer_link_hover_style == 'overline') {echo ' SELECTED'; } ?>>Overline </option>
									</select>
									</p>
								</div>
						</div>

                        <div class="left" style="border:0;width:100%;">
                            <?php Photocrati_Fonts::render_font_fields('Widget Title', array(
                                'font_family_field_name'    =>  'footer_widget_style',
                                'font_family_value'         =>  $footer_widget_style,
                                'font_color_field_name'     =>  'footer_widget_color',
                                'font_color_value'          =>  $footer_widget_color,
                                'font_size_field_name'      =>  'footer_widget_title',
                                'font_size_value'           =>  $footer_widget_title,
                                'font_weight_field_name'    =>  'footer_widget_weight',
                                'font_weight_value'         =>  $footer_widget_weight,
                                'font_italics_field_name'   =>  'footer_widget_italic',
                                'font_italics_value'        =>  $footer_widget_italic,
                                'font_decoration_field_name'=>  'footer_widget_decoration',
                                'font_decoration_value'     =>  $footer_widget_decoration,
                                'font_case_field_name'      =>  'footer_widget_case',
                                'font_case_value'           =>  $footer_widget_case
                            )); ?>
                        </div>

						<div class="submit-button-wrapper">
							<input type="button" class="button" id="update-footer-font" value="Save Footer Styles" style="clear:none;" />
						</div>
						<div class="msg" id="msgff" style="float:left;"></div>

					</form>

					</div>

			</div>
			<?php } ?>

			<div class="options">Footer Height</div>

				<div class="sub-options"><span id="comments">The footer is set to a fixed height. If you add more content please adjust this height to compensate.</span></div>
				<div class="option-content">

					<div id="option-container">

					<form name="footer-height" id="footer-height" method="post">

						<div class="left-sm">
							<p class="titles">Footer Height</p>
							<p>
							<input type="text" name="footer_height" id="footer_height" value="<?php echo $footer_height; ?>" size="3">px
							</p>
						</div>

						<div class="submit-button-wrapper">
							<input type="button" class="button" id="update-footer-height" value="Save Footer Height" style="clear:none;" />
						</div>
						<div class="msg" id="msgfh" style="float:left;"></div>

					</form>

					</div>

			</div>

			<div class="options">Footer Widget Placement</div>

				<div class="sub-options"><span id="comments">Here you can set where the footer widgets show</span></div>
				<div class="option-content">

					<div id="option-container">

					<form name="footer-widgets" id="footer-widgets" method="post">

						<div class="left-sm" style="width:95%;">
							<p class="titles">Widget Placement</p>
							<p>
							<select name="footer_widget_placement">
								<option value="0"<?php if($footer_widget_placement == '0') { echo ' SELECTED'; } ?>>Do Not Display</option>
								<option value="1"<?php if($footer_widget_placement == '1') { echo ' SELECTED'; } ?>>Only On Blog Pages</option>
								<option value="2"<?php if($footer_widget_placement == '2') { echo ' SELECTED'; } ?>>On All Pages Except Home</option>
								<option value="3"<?php if($footer_widget_placement == '3') { echo ' SELECTED'; } ?>>On All Pages Including Home</option>
								<option value="4"<?php if($footer_widget_placement == '4') { echo ' SELECTED'; } ?>>Only On Home</option>
							</select>
							</p>
							<p><i>To add content to your footer widget area, go to the <a href="widgets.php">widgets page</a> and drag widgets to the Footer Widget Area.</i></p>
						</div>

						<div class="submit-button-wrapper">
							<input type="button" class="button" id="update-footer-widgets" value="Save Footer Widgets" style="clear:none;" />
						</div>
						<div class="msg" id="msgfw" style="float:left;"></div>

					</form>

					</div>

			</div>

			<div class="options">Footer Copyright / Photocrati Link</div>

				<div class="sub-options"><span id="comments">Set a custom copyright to display in your footer and show or hide the Photocrati link</span></div>
				<div class="option-content">

					<div id="option-container">

					<form name="footer-options" id="footer-options" method="post">

						<div class="left" style="width:100%;">

							<p class="titles">Footer Copyright (HTML Allowed)</p>
							<p>
							<textarea name="footer_copy" cols="70" rows="4"><?php echo stripslashes(str_replace('"', '&quot;', $footer_copy)); ?></textarea>
							</p>

						</div>

						<div class="clear"></div>

						<div class="left" style="width:100%;">

							<p class="titles">Photocrati Link</p>
							<p>
								<select name="show_photocrati">
                                    <option value='YES' <?php selected($show_photocrati, TRUE)?>>Show</option>
                                    <option value='NO' <?php selected($show_photocrati, FALSE)?>>Hide</option>
								</select>
							</p>

						</div>

						<div class="submit-button-wrapper">
							<input type="button" class="button" id="update-footer" value="Save Footer Copyright" style="clear:none;" />
						</div>
						<div class="msg" id="msgfu" style="float:left;"></div>

					</form>

					</div>

			</div>

		</div>

		</div>

		<div id="custom_tab4" class="tab_content">

		<script type="text/javascript">
		jQuery(document).ready(function()
		{

			jQuery("#update-size").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#size-options', '#msg5');
			});

			jQuery("#display_sidebar").change(function()
			{
				if (jQuery("#display_sidebar").val() == 'NO') {
					var answer = confirm("Are you sure you want to disable the sidebar and use full width for your blog posts?")
					if (answer){
						jQuery("#content_width").val('100');

						Photocrati_ThemeOptions_Admin.submitStyleForm('#size-options');
					}
				} else {
					var answer = confirm("Are you sure you want to enable the sidebar?")
					if (answer){
						jQuery("#content_width").val('70');
						jQuery("#sidebar_width").val('30');
						Photocrati_ThemeOptions_Admin.submitStyleForm('#size-options');
					}
				}
			});

			jQuery("#content_width").keyup(function()
			{
				if(jQuery("#content_width").val() == '') {
				var content_width = 0;
				} else {
				var content_width = parseInt(jQuery("#content_width").val());
				}
				var	sidebar_width = 100 - content_width;
				jQuery("#sidebar_width").val(sidebar_width);
			});

			jQuery("#sidebar_width").keyup(function()
			{
				if(jQuery("#sidebar_width").val() == '') {
				var sidebar_width = 0;
				} else {
				var sidebar_width = parseInt(jQuery("#sidebar_width").val());
				}
				var	content_width = 100 - sidebar_width;
				jQuery("#content_width").val(content_width);
			});

			jQuery("#sidebar_style").change(function()
			{
				if (jQuery("#sidebar_style").val() == 'color') {
					jQuery("#sidebar_bg_color").val('FFFFFF');
					jQuery("#sidebar_bg_color").css('background', '#FFFFFF');
					jQuery("#sidebar-color").show();
				} else {
					jQuery("#sidebar_bg_color").val('transparent');
					jQuery("#sidebar-color").hide();
				}
			});

			jQuery("#update-sidebar-c").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#sidebar-bg-options', '#msg');
			});

			jQuery("#update-sidebar-f").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#sidebar-font-options', '#msg3');
			});

			jQuery("#update-sidebar-l").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#sidebar-link-options', '#msg2');
			});

			jQuery("#update-sidebar-t").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#sidebar-title-options', '#msg4');
			});

			jQuery("#custom_sidebar").change(function()
			{
				if (jQuery("#custom_sidebar").val() == 'ON') {
					jQuery("#custom-sidebar-position").show();
					jQuery("#sidebar-html").show();
				} else {
					jQuery("#custom-sidebar-position").hide();
					jQuery("#sidebar-html").hide();

					Photocrati_ThemeOptions_Admin.submitStyleForm('#sidebar-options');
				}
			});

			jQuery("#update-sidebar").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#sidebar-options', '#msg6');
			});

			jQuery("#social_media").change(function()
			{
				if (jQuery("#social_media").val() == 'ON') {
					jQuery("#social-settings").show();
					jQuery("#social-title").show();
				} else {
					jQuery("#social-settings").hide();
					jQuery("#social-title").hide();
				}
			});

			jQuery("#update-social").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#social-options', '#msg7');
			});

		});
		</script>

			<div id="container">

				<?php if($dynamic_style) { ?>
				<div class="options">Sidebar / Content Width</div>

					<div class="sub-options"><span id="comments">Change the width of the blog content & sidebar. You can also disable your sidebar and use full width for posts.</span></div>
					<div class="option-content">

						<div id="option-container">

							<form name="size-options" id="size-options" method="post">

								<div class="left" style="border-right:0;">

									<div class="inner">
										<p class="titles">Content Area Width</p>
										<p><input type="text" name="content_width" id="content_width" value="<?php echo $content_width; ?>" size="2" />%</p>
									</div>

									<div class="inner">
										<p class="titles">Use Blog Sidebar?</p>
										<p>
										<select name="is_sidebar_enabled" id="display_sidebar">
                                            <option value='YES' <?php selected($preset->is_sidebar_enabled(), TRUE)?>>YES</option>
											<option value='NO' <?php selected($preset->is_sidebar_enabled(), FALSE)?>>NO</option>
										</select>
										</p>
									</div>

								</div>

								<div class="left">
									<div class="inner"<?php if(!$preset->is_sidebar_enabled()) {echo ' style="visibility:hidden;"'; } ?>>
										<p class="titles">Sidebar Area Width</p>
										<p><input type="text" name="sidebar_width" id="sidebar_width" value="<?php echo $sidebar_width; ?>" size="2" />%</p>
									</div>

								</div>
							
							<div style="clear: both;"></div>
							<div class="left style-skin-modern" style="">
								<p class="titles">Sidebar Alignment</p>
								<p>
									Place sidebar <?php echo Photocrati_Theme_UI::build_dropdown_html('sidebar_alignment', $sidebar_alignment, array('left' => __('On the Left'), 'right' => __('On the Right'))); ?><br/>
									On small screens <?php echo Photocrati_Theme_UI::build_dropdown_html('sidebar_alignment_responsive', $sidebar_alignment_responsive, array('top' => __('Pull above content'), 'bottom' => __('Push below content'))); ?><br/>
								</p>
							</div>

							<div class="submit-button-wrapper">
								<input type="button" class="button" id="update-size" value="Save Layout Sizes" style="clear:none;" />
							</div>
							<div class="msg" id="msg5" style="float:left;"></div>

							</form>

						</div>
				</div>

				<div class="options">Background</div>

					<div class="sub-options"><span id="comments">Change the color of the sidebar background</span></div>
					<div class="option-content">

						<div id="option-container">

							<form name="sidebar-bg-options" id="sidebar-bg-options" method="post">

								<div class="left" style="border-right:0;">

									<div class="inner">
										<p class="titles">Sidebar Background Style</p>
										<p>
										<select id="sidebar_style">
											<option value="transparent"<?php if($sidebar_bg_color == 'transparent') {echo ' SELECTED'; } ?>>Transparent </option>
											<option value="color"<?php if($sidebar_bg_color <> 'transparent') {echo ' SELECTED'; } ?>>Color </option>
										</select>
										</p>
									</div>
									<div class="inner" id="sidebar-color"<?php if($sidebar_bg_color == 'transparent') {echo ' style="display:none;"'; } ?>>
										<p class="titles">Sidebar Background Color</p>
										<p>#<input type="text" name="sidebar_bg_color" id="sidebar_bg_color" value="<?php if($sidebar_bg_color <> 'transparent') { echo $sidebar_bg_color; } else { echo 'transparent'; } ?>" size="7"  <?php if($sidebar_bg_color <> 'transparent') { ?>style="background:#<?php echo $sidebar_bg_color; ?>;"<?php } ?> /> Color</p>
										<p>%<input type="text" name="sidebar_opacity" id="sidebar_opacity" value="<?php echo $sidebar_opacity; ?>" size="7" /> Opacity</p>
									</div>

									<div class="submit-button-wrapper">
										<input type="button" class="button" id="update-sidebar-c" value="Save Background" style="clear:none;" />
									</div>
									<div class="msg" id="msg" style="float:left;"></div>

								</div>

							</form>

						</div>

					</div>


				<div class="options">Font Styles</div>

					<div class="sub-options"><span id="comments">Change the color, size and style of the sidebar fonts</span></div>
					<div class="option-content">

						<div id="option-container">

							<form name="sidebar-font-options" id="sidebar-font-options" method="post">

                                <div class="left" style="border:0;width:100%;">
                                    <?php Photocrati_Fonts::render_font_fields('Sidebar', array(
                                        'font_family_field_name'    =>  'sidebar_font_style',
                                        'font_family_value'         =>  $sidebar_font_style,
                                        'font_size_field_name'      =>  'sidebar_font_size',
                                        'font_size_value'           =>  $sidebar_font_size,
                                        'font_color_field_name'     =>  'sidebar_font_color',
                                        'font_color_value'          =>  $sidebar_font_color,
                                        'font_weight_field_name'    =>  'sidebar_font_weight',
                                        'font_weight_value'         =>  $sidebar_font_weight,
                                        'font_italics_field_name'   =>  'sidebar_font_italic',
                                        'font_italics_value'        =>  $sidebar_font_italic,
                                        'font_decoration_field_name'=>  'sidebar_font_decoration',
                                        'font_decoration_value'     =>  $sidebar_font_decoration,
                                        'font_case_field_name'      =>  'sidebar_font_case',
                                        'font_case_value'           =>  $sidebar_font_case
                                    )); ?>
                                </div>

                                <div class="submit-button-wrapper">
                                    <input type="button" class="button" id="update-sidebar-f" value="Save Fonts" style="clear:none;" />
                                </div>
                                <div class="msg" id="msg3" style="float:left;"></div>

							</form>

						</div>

					</div>

				<div class="options">Title Styles</div>

                <div class="sub-options"><span id="comments">Change the color, size and style of the sidebar titles</span></div>
                <div class="option-content">

                    <div id="option-container">

                        <form name="sidebar-title-options" id="sidebar-title-options" method="post">

                            <div class="left" style="border-right:0;width:100%;">

                                <?php Photocrati_Fonts::render_font_fields('Sidebar Title', array(
                                    'font_family_field_name'        =>  'sidebar_title_style',
                                    'font_family_value'             =>  $sidebar_title_style,
                                    'font_size_field_name'          =>  'sidebar_title_size',
                                    'font_size_value'               =>  $sidebar_title_size,
                                    'font_color_field_name'         =>  'sidebar_title_color',
                                    'font_color_value'              =>  $sidebar_title_color,
                                    'font_weight_field_name'        =>  'sidebar_title_weight',
                                    'font_weight_value'             =>  $sidebar_title_weight,
                                    'font_italics_field_name'       =>  'sidebar_title_italic',
                                    'font_italics_value'            =>  $sidebar_title_italic,
                                    'font_decoration_field_name'    =>  'sidebar_title_decoration',
                                    'font_decoration_value'         =>  $sidebar_title_decoration,
                                    'font_case_field_name'          =>  'sidebar_title_case',
                                    'font_case_value'               =>  $sidebar_title_case
                                )); ?>
                            </div>

                            <div class="submit-button-wrapper">
                                <input type="button" class="button" id="update-sidebar-t" value="Save Titles" style="clear:none;" />
                            </div>
                            <div class="msg" id="msg4" style="float:left;"></div>
                        </form>
                    </div>
                </div>

				<div class="options">Link Styles</div>

                <div class="sub-options"><span id="comments">Change the color and style of the sidebar links</span></div>
                <div class="option-content">

                    <div id="option-container">

                        <form name="sidebar-link-options" id="sidebar-link-options" method="post">

                            <div class="left" style="border-right:0;">

                                    <div class="inner">
                                        <p class="titles">Link Color</p>
                                        <p>#<input type="text" name="sidebar_link_color" id="sidebar_link_color" value="<?php echo $sidebar_link_color; ?>" size="7" style="background:#<?php echo $sidebar_link_color; ?>;" /> Color</p>
                                    </div>
                                    <div class="inner">
                                        <p class="titles">Hover Color</p>
                                        <p>#<input type="text" name="sidebar_link_hover_color" id="sidebar_link_hover_color" value="<?php echo $sidebar_link_hover_color; ?>" size="7" style="background:#<?php echo $sidebar_link_hover_color; ?>;" /> Color</p>
                                    </div>

                                    <div class="submit-button-wrapper">
                                        <input type="button" class="button" id="update-sidebar-l" value="Save Links" style="clear:none;" />
                                    </div>
                                    <div class="msg" id="msg2" style="float:left;"></div>
                            </div>

                            <div class="right">

                                    <div class="inner">
                                        <p class="titles">Hover Style</p>
                                        <p>
                                        <select name="sidebar_link_hover_style">
                                            <option value="none"<?php if($sidebar_link_hover_style == 'none') {echo ' SELECTED'; } ?>>None </option>
                                            <option value="underline"<?php if($sidebar_link_hover_style == 'underline') {echo ' SELECTED'; } ?>>Underline </option>
                                            <option value="overline"<?php if($sidebar_link_hover_style == 'overline') {echo ' SELECTED'; } ?>>Overline </option>
                                        </select>
                                        </p>
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>

				<?php } ?>
					<div class="options">Social Media Settings</div>

					<div class="sub-options"><span id="comments">Enable / disable social media icons on the sidebar & choose the style</span></div>
					<div class="option-content">

						<div id="option-container">

						<form name="social-options" id="social-options" method="post">

							<div class="left" style="border:0;">

								<p class="titles">Social Media Icons</p>
								<p>
									<select name="social_media" id="social_media">
										<option value="OFF"<?php if(!$social_media) {echo ' SELECTED'; } ?>>OFF </option>
										<option value="ON"<?php if($social_media) {echo ' SELECTED'; } ?>>ON </option>
									</select>
								</p>

							</div>

							<div class="right" id="social-title" <?php if(!$social_media) { echo 'style="display:none;"'; } ?>>

								<p class="titles">Sidebar Title (leave blank for no title)</p>
								<p>
									<input type="text" name="social_media_title" value="<?php echo $social_media_title; ?>" size="50" />
								</p>

							</div>

							<div id="social-settings" style="clear:both;<?php if(!$social_media) { echo 'display:none;'; } ?>">

								<div class="left" style="border:0;">
									<p class="titles">Icon Set</p>
									<p>
									<em>Small Icons</em><BR />
									<input type="radio" name="social_media_set" value="small"<?php if($social_media_set == 'small') {echo ' CHECKED'; } ?> /> &nbsp;
									<img src="<?php bloginfo('template_directory'); ?>/images/social/small-set.png" align="absmiddle" />
									</p>
									<p>
									<em>Chrome Icons</em><BR />
									<input type="radio" name="social_media_set" value="chrome"<?php if($social_media_set == 'chrome') {echo ' CHECKED'; } ?> /> &nbsp;
									<img src="<?php bloginfo('template_directory'); ?>/images/social/chrome-set.png" align="absmiddle" />
									</p>
									<p>
									<em>Polaroid Icons</em><BR />
									<input type="radio" name="social_media_set" value="polaroid"<?php if($social_media_set == 'polaroid') {echo ' CHECKED'; } ?> /> &nbsp;
									<img src="<?php bloginfo('template_directory'); ?>/images/social/polaroid-set.png" align="absmiddle" />
									</p>
								</div>

								<div class="right">

									<p class="titles">URLs (leave blank to exclude)</p>


									<p>
									<em>RSS Feed (please include http://)</em><BR />
									<input type="text" name="social_rss" size="50" value="<?php echo $social_rss; ?>" />
									</p>

									<p>
									<em>Email Address</em><BR />
									<input type="text" name="social_email" size="50" value="<?php echo $social_email; ?>" />
									</p>

									<p>
									<em>Twitter</em><BR />
									http://www.twitter.com/<input type="text" name="social_twitter" size="25" value="<?php echo $social_twitter; ?>" />
									</p>

									<p>
									<em>Facebook</em><BR />
									http://www.facebook.com/<input type="text" name="social_facebook" size="25" value="<?php echo $social_facebook; ?>" />
									</p>

									<p>
									<em>Flickr</em><BR />
									http://www.flickr.com/<input type="text" name="social_flickr" size="25" value="<?php echo $social_flickr; ?>" />
									</p>

								</div>

							</div>

							<div class="submit-button-wrapper">
								<input type="button" class="button" id="update-social" value="Save Social Media" style="clear:none;" />
							</div>
							<div class="msg" id="msg7" style="float:left;"></div>

						</form>

						</div>

				</div>

				<form name="sidebar-options" id="sidebar-options" method="post">

					<div class="options">Custom Sidebar Content</div>

					<div class="sub-options"><span id="comments">Enable / disable custom sidebar content and set the position</span></div>
					<div class="option-content">

						<div id="option-container">

							<div class="left" style="border:0;">

								<div class="inner">

									<p class="titles">Use custom content?</p>
									<p>
										<select name="custom_sidebar" id="custom_sidebar">
											<option value="OFF"<?php if(!$custom_sidebar) {echo ' SELECTED'; } ?>>OFF </option>
											<option value="ON"<?php if($custom_sidebar) {echo ' SELECTED'; } ?>>ON </option>
										</select>
									</p>

								</div>

							</div>

							<div class="right" id="custom-sidebar-position" <?php if(!$custom_sidebar) { echo 'style="display:none;"'; } ?>>

								<div class="inner">

									<p class="titles">Above or below widgets?</p>
									<p>
										<select name="custom_sidebar_position">
											<option value="ABOVE"<?php if($custom_sidebar_position == 'ABOVE') {echo ' SELECTED'; } ?>>ABOVE </option>
											<option value="BELOW"<?php if($custom_sidebar_position == 'BELOW') {echo ' SELECTED'; } ?>>BELOW </option>
										</select>
									</p>

								</div>

							</div>

						</div>

					</div>

					<div id="sidebar-html" <?php if(!$custom_sidebar) { echo 'style="display:none;"'; } ?>>

						<div class="options">Custom Sidebar HTML</div>

						<div class="sub-options"><span id="comments">Insert HTML to appear on the sidebar</span></div>
						<div class="option-content">

							<div id="option-container">

									<div class="left" style="border:0;width:58%;">
										<p class="titles">Insert HTML</p>
										<p>

										<textarea name="custom_sidebar_html" id="custom_sidebar_html" cols="45" rows="10"><?php echo stripslashes(str_replace('\"', '"', $custom_sidebar_html)); ?></textarea>

										</p>

									</div>

									<div class="right" style="border:0;width:40%;">
										<p class="titles">Tips</p>
										<p>

										<em>Use an H3 tag with a class of "widget-title" to use the existing sidebar title style:</em>
										<BR /><BR />
										&lt;h3 class="widget-title"&gt;Title&lt;/h3&gt;

										</p>
									</div>

								<div class="submit-button-wrapper">
									<input type="button" class="button" id="update-sidebar" value="Save Custom Sidebar" style="clear:none;" />
								</div>
								<div class="msg" id="msg6" style="float:left;"></div>

							</div>

						</div>

					</div>

				</form>

			</div>

		</div>

		<?php if($dynamic_style) { ?>
		<div id="custom_tab3" class="tab_content">

			<script type="text/javascript">
			jQuery(document).ready(function()
			{

				jQuery("#container_style").change(function()
				{
					if (jQuery("#container_style").val() == 'color') {
						jQuery("#container_color").val('FFFFFF');
						jQuery("#container_color").css('background', '#FFFFFF');
						jQuery("#container-color").show();
						jQuery("#container-border").show();
						jQuery("#container-border-color").show();
					} else {
						jQuery("#container_color").val('transparent');
						jQuery("#container-color").hide();
						jQuery("#container-border").hide();
						jQuery("#container-border-color").hide();
					}
				});

				jQuery("#update-meta").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#meta-options', '#msgmeta');
				});

				jQuery("#update-container").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#container-options', '#msgcb');
				});

				jQuery("#update-font").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#font-options', '#msgcfs');
				});

				jQuery("#update-h").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#h-options', '#msgh1');
				});

				jQuery("#update-link-c").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#link-options', '#msgcl');
				});

				jQuery("#update-comments").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#page_comments', '#msgcomments');
				});

			});
			</script>

			<div id="container">

			<div class="options">Content Font / Paragraph Styles</div>

				<div class="sub-options"><span id="comments">Change the color, size and style of the content fonts (sidebar controlled separately) and paragraph spacing</span></div>
				<div class="option-content">

					<div id="option-container">

					<form name="font-options" id="font-options" method="post">
                        <div class="left" style="border:0;width:100%;">
                            <?php Photocrati_Fonts::render_font_fields('Font', array(
                                'font_family_field_name'        =>  'font_style',
                                'font_family_value'             =>  $font_style,
                                'font_color_field_name'         =>  'font_color',
                                'font_color_value'              =>  $font_color,
                                'font_size_field_name'          =>  'font_size',
                                'font_size_value'               =>  $font_size,
                                'font_weight_field_name'        =>  'font_weight',
                                'font_weight_value'             =>  $font_weight,
                                'font_italics_field_name'       =>  'font_italic',
                                'font_italics_value'            =>  $font_italic,
                                'font_decoration_field_name'    =>  'font_decoration',
                                'font_decoration_value'         =>  $font_decoration,
                                'font_case_field_name'          =>  'font_case',
                                'font_case_value'               =>  $font_case
                            )); ?>
                        </div>

						<div class="left" style="border-right:0;">

                            <div class="inner">
                                <p class="titles">Paragraph Line Height</p>
                                <p><input type="text" name="p_line" id="p_line" value="<?php echo $p_line; ?>" size="2" />px</p>
                            </div>
                            <div class="inner">
                                <p class="titles">Paragraph Spacing</p>
                                <p><input type="text" name="p_space" id="p_space" value="<?php echo $p_space; ?>" size="2" />px</p>
                            </div>
                        </div>

                        <div class="submit-button-wrapper">
                            <input type="button" class="button" id="update-font" value="Save Fonts / Paragraphs" style="clear:none;" />
                        </div>
                        <div class="msg" id="msgcfs" style="float:left;"></div>

					</form>

					</div>

				</div>


				<div class="options">Title Styles</div>

				<div class="sub-options"><span id="comments">Change the color and size of the H1-H5 tags</span></div>
				<div class="option-content">

					<form name="h-options" id="h-options" method="post">

					<div id="option-container">

						<div class="left" style="width:100%;">
                            <?php Photocrati_Fonts::render_font_fields('H1', array(
                                'font_family_field_name'    =>  'h1_font_style',
                                'font_family_value'         =>  $h1_font_style,
                                'font_size_field_name'      =>  'h1_size',
                                'font_size_value'           =>  $h1_size,
                                'font_color_field_name'     =>  'h1_color',
                                'font_color_value'          =>  $h1_color,
                                'font_weight_field_name'    =>  'h1_font_weight',
                                'font_weight_value'         =>  $h1_font_weight,
                                'font_italics_field_name'   =>  'h1_font_italic',
                                'font_italics_value'        =>  $h1_font_italic,
                                'font_decoration_field_name'=>  'h1_font_decoration',
                                'font_decoration_value'     =>  $h1_font_decoration,
                                'font_case_field_name'      =>  'h1_font_case',
                                'font_case_value'           =>  $h1_font_case
                            )); ?>

                            <div class="inner">
                                <p class="titles">H1 Align</p>
                                <p>
                                    <select name="h1_font_align">
                                        <option value=""<?php if($h1_font_align == '') { echo ' SELECTED'; } ?>>Left</option>
                                        <option value="center"<?php if($h1_font_align == 'center') { echo ' SELECTED'; } ?>>Centered</option>
                                    </select>
                                </p>
                            </div>
						</div>

                        <div class="left" style="width:100%;">
                            <?php Photocrati_Fonts::render_font_fields('H2', array(
                                'font_family_field_name'    =>  'h2_font_style',
                                'font_family_value'         =>  $h2_font_style,
                                'font_size_field_name'      =>  'h2_size',
                                'font_size_value'           =>  $h2_size,
                                'font_color_field_name'     =>  'h2_color',
                                'font_color_value'          =>  $h2_color,
                                'font_weight_field_name'    =>  'h2_font_weight',
                                'font_weight_value'         =>  $h2_font_weight,
                                'font_italics_field_name'   =>  'h2_font_italic',
                                'font_italics_value'        =>  $h2_font_italic,
                                'font_decoration_field_name'=>  'h2_font_decoration',
                                'font_decoration_value'     =>  $h2_font_decoration,
                                'font_case_field_name'      =>  'h2_font_case',
                                'font_case_value'           =>  $h2_font_case
                            )); ?>

                            <div class="inner">
                                <p class="titles">H2 Align</p>
                                <p>
                                    <select name="h2_font_align">
                                        <option value=""<?php if($h2_font_align == '') { echo ' SELECTED'; } ?>>Left</option>
                                        <option value="center"<?php if($h2_font_align == 'center') { echo ' SELECTED'; } ?>>Centered</option>
                                    </select>
                                </p>
                            </div>
                        </div>

                        <div class="left" style="width:100%;">
                            <?php Photocrati_Fonts::render_font_fields('H3', array(
                                'font_family_field_name'    =>  'h3_font_style',
                                'font_family_value'         =>  $h3_font_style,
                                'font_size_field_name'      =>  'h3_size',
                                'font_size_value'           =>  $h3_size,
                                'font_color_field_name'     =>  'h3_color',
                                'font_color_value'          =>  $h3_color,
                                'font_weight_field_name'    =>  'h3_font_weight',
                                'font_weight_value'         =>  $h3_font_weight,
                                'font_italics_field_name'   =>  'h3_font_italic',
                                'font_italics_value'        =>  $h3_font_italic,
                                'font_decoration_field_name'=>  'h3_font_decoration',
                                'font_decoration_value'     =>  $h3_font_decoration,
                                'font_case_field_name'      =>  'h3_font_case',
                                'font_case_value'           =>  $h3_font_case
                            )); ?>

                            <div class="inner">
                                <p class="titles">H3 Align</p>
                                <p>
                                    <select name="h3_font_align">
                                        <option value=""<?php if($h3_font_align == '') { echo ' SELECTED'; } ?>>Left</option>
                                        <option value="center"<?php if($h3_font_align == 'center') { echo ' SELECTED'; } ?>>Centered</option>
                                    </select>
                                </p>
                            </div>
                        </div>

                        <!-- H4 -->
                        <div class="left" style="width:100%;">
                            <?php Photocrati_Fonts::render_font_fields('H4', array(
                                'font_family_field_name'    =>  'h4_font_style',
                                'font_family_value'         =>  $h4_font_style,
                                'font_size_field_name'      =>  'h4_size',
                                'font_size_value'           =>  $h4_size,
                                'font_color_field_name'     =>  'h4_color',
                                'font_color_value'          =>  $h4_color,
                                'font_weight_field_name'    =>  'h4_font_weight',
                                'font_weight_value'         =>  $h4_font_weight,
                                'font_italics_field_name'   =>  'h4_font_italic',
                                'font_italics_value'        =>  $h4_font_italic,
                                'font_decoration_field_name'=>  'h4_font_decoration',
                                'font_decoration_value'     =>  $h4_font_decoration,
                                'font_case_field_name'      =>  'h4_font_case',
                                'font_case_value'           =>  $h4_font_case
                            )); ?>

                            <div class="inner">
                                <p class="titles">H4 Align</p>
                                <p>
                                    <select name="h4_font_align">
                                        <option value=""<?php if($h4_font_align == '') { echo ' SELECTED'; } ?>>Left</option>
                                        <option value="center"<?php if($h4_font_align == 'center') { echo ' SELECTED'; } ?>>Centered</option>
                                    </select>
                                </p>
                            </div>
                        </div>

                        <!-- H5 -->
                        <div class="left" style="width:100%;">
                            <?php Photocrati_Fonts::render_font_fields('H5', array(
                                'font_family_field_name'    =>  'h5_font_style',
                                'font_family_value'         =>  $h5_font_style,
                                'font_size_field_name'      =>  'h5_size',
                                'font_size_value'           =>  $h5_size,
                                'font_color_field_name'     =>  'h5_color',
                                'font_color_value'          =>  $h5_color,
                                'font_weight_field_name'    =>  'h5_font_weight',
                                'font_weight_value'         =>  $h5_font_weight,
                                'font_italics_field_name'   =>  'h5_font_italic',
                                'font_italics_value'        =>  $h5_font_italic,
                                'font_decoration_field_name'=>  'h5_font_decoration',
                                'font_decoration_value'     =>  $h5_font_decoration,
                                'font_case_field_name'      =>  'h5_font_case',
                                'font_case_value'           =>  $h5_font_case
                            )); ?>

                            <div class="inner">
                                <p class="titles">H5 Align</p>
                                <p>
                                    <select name="h4_font_align">
                                        <option value=""<?php if($h5_font_align == '') { echo ' SELECTED'; } ?>>Left</option>
                                        <option value="center"<?php if($h5_font_align == 'center') { echo ' SELECTED'; } ?>>Centered</option>
                                    </select>
                                </p>
                            </div>
                        </div>

                        <div class="submit-button-wrapper">
                            <input type="button" class="button" id="update-h" value="Save H1-H5 Settings" style="clear:none;" />
                        </div>
                        <div class="msg" id="msgh1" style="float:left;"></div>


					</div>

					</form>

				</div>


			<div class="options">Blog Meta</div>

				<div class="sub-options"><span id="comments">Hide or show the blog meta paragraph at the bottom of the post</span></div>
				<div class="option-content">

					<div id="option-container">

					<form name="meta-options" id="meta-options" method="post">

						<div class="left" style="border-right:0;width:100%;">

								<div class="inner" style="width: 32%;">
									<p class="titles">Meta Paragraph</p>
									<p>
									<select name="blog_meta">
                                        <option value="ON" <?php selected($blog_meta, TRUE)?>>ON</option>
                                        <option value="OFF" <?php selected($blog_meta, FALSE)?>>OFF</option>
									</select>
									</p>
								</div>
								
								<div class="inner style-skin-modern" style="width: 32%;">
									<p class="titles">Meta Before Post Alignment</p>
									<p>
									<?php echo Photocrati_Theme_UI::build_dropdown_html('blog_meta_pre_alignment', $blog_meta_pre_alignment, array('default' => __('Leave as default'), 'left' => __('On the Left'), 'right' => __('On the Right'), 'center' => __('Centered'))); ?>
									</p>
								</div>
								
								<div class="inner style-skin-modern" style="width: 32%;">
									<p class="titles">Meta After Post Alignment</p>
									<p>
									<?php echo Photocrati_Theme_UI::build_dropdown_html('blog_meta_post_alignment', $blog_meta_post_alignment, array('default' => __('Leave as default'), 'left' => __('On the Left'), 'right' => __('On the Right'), 'center' => __('Centered'))); ?>
									</p>
								</div>
						</div>

								<div class="submit-button-wrapper">
									<input type="button" class="button" id="update-meta" value="Update Meta" style="clear:none;" />
								</div>
								<div class="msg" id="msgmeta" style="float:left;"></div>


					</form>

					</div>

				</div>


			<div class="options">Link Styles</div>

				<div class="sub-options"><span id="comments">Change the color and hover style of the links</span></div>
				<div class="option-content">

					<div id="option-container">

					<form name="link-options" id="link-options" method="post">

						<div class="left" style="border-right:0;">

								<div class="inner">
									<p class="titles">Link Color</p>
									<p>#<input type="text" name="link_color" id="link_color" value="<?php echo $link_color; ?>" size="7" style="background:#<?php echo $link_color; ?>;" /> Color</p>
								</div>
								<div class="inner">
									<p class="titles">Hover Color</p>
									<p>#<input type="text" name="link_hover_color" id="link_hover_color" value="<?php echo $link_hover_color; ?>" size="7" style="background:#<?php echo $link_hover_color; ?>;" /> Color</p>
								</div>

								<div class="submit-button-wrapper">
									<input type="button" class="button" id="update-link-c" value="Save Links" style="clear:none;" />
								</div>
								<div class="msg" id="msgcl" style="float:left;"></div>

						</div>

						<div class="right">

								<div class="inner">
									<p class="titles">Hover Style</p>
									<p>
									<select name="link_hover_style">
										<option value="none"<?php if($link_hover_style == 'none') {echo ' SELECTED'; } ?>>None </option>
										<option value="underline"<?php if($link_hover_style == 'underline') {echo ' SELECTED'; } ?>>Underline </option>
										<option value="overline"<?php if($link_hover_style == 'overline') {echo ' SELECTED'; } ?>>Overline </option>
									</select>
									</p>
								</div>

						</div>

					</form>

					</div>

				</div>

			</div>

		</div>
		<?php } ?>

		<?php if($dynamic_style) { ?>
		<div id="custom_tab2" class="tab_content">

			<script type="text/javascript">
			jQuery(document).ready(function()
			{

				jQuery('#fileUploadcl').uploadify({
				'uploader'  : '<?php bloginfo('template_url'); ?>/admin/js/uploadify.swf',
				'script'    : '<?php bloginfo('template_url'); ?>/admin/scripts/uploadify.php',
				'scriptData': { 'cookie' : escape(document.cookie + ';<?php echo photocrati_upload_parameter_string(); ?>'), 'session_id' : '<?php echo session_id(); ?>' },
				'auto'      : true,
				'buttonImg'	: '<?php echo photocrati_gallery_file_uri('image/upload.jpg'); ?>',
				'folder'    : '/images/uploads',
				'onComplete': function(event, queueID, fileObj, response, data) {
					 jQuery("#filesUploadedcl").html('<input type="hidden" id="custom_logo_image" name="custom_logo_image" value="'+fileObj.name+'">');
					 jQuery("#fileNamecl")
							.fadeIn('slow')
							.html(fileObj.name+' uploaded successfully!<BR><em>Remember to save.</em>');
				}
				});

				jQuery("#custom_logo").change(function()
				{
					if (jQuery("#custom_logo").val() == 'custom') {
						jQuery("#right-lg-cl").show();
						<?php if($one_column === true) { ?>
						jQuery("#right-lg-full").show();
						jQuery("#left-sm-full").show();
						<?php } ?>
						jQuery("#right-lg-custom").hide();
						jQuery("#right-lg-custom2").hide();
						jQuery("#left-sm-custom").hide();
					} else {
						jQuery("#right-lg-cl").hide();
						<?php if($one_column === true) { ?>
						jQuery("#right-lg-full").hide();
						jQuery("#left-sm-full").hide();
						<?php } ?>
						jQuery("#right-lg-custom").show();
						jQuery("#right-lg-custom2").show();
						jQuery("#left-sm-custom").show();
					}
				});

				jQuery("#one_column_logo").change(function()
				{
					if (jQuery("#one_column_logo").val() == 'ON') {
						jQuery("#right-lg-margin").show();
						jQuery("#left-sm-margin").show();
					} else {
						jQuery("#right-lg-margin").hide();
						jQuery("#left-sm-margin").hide();
					}
				});

				jQuery("#update-logo").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#logo-options', '#msghl');
				});

				jQuery("#delete-image").on('click', function()
				{
					var answer = confirm("Are you sure you want to remove the custom logo? It will remain in your theme directory under images/uploads.")
					if (answer){
						Photocrati_ThemeOptions_Admin.updateStyles({ 'custom_logo' : 'default', 'custom_logo_image' : '' }, '#msghl');
					}
				});

				jQuery("#update-sizes").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#header-sizes', '#msghhlp');
				});

				var clicked = '';

				// XXX TODO still fix this mess for logos
				jQuery("#left-right").on('click', function()
				{
					var answer = confirm("Are you sure you want to set your logo to the left and your menu to the right?")
					if (answer){
						Photocrati_ThemeOptions_Admin.updateLogoPosition('left-right', '#msgmp_1');
					}
				});

				jQuery("#right-left").on('click', function()
				{
					var answer = confirm("Are you sure you want to set your logo to the right and your menu to the left?")
					if (answer){
						Photocrati_ThemeOptions_Admin.updateLogoPosition('right-left', '#msgmp_2');
					}
				});

				jQuery("#bottom-top").on('click', function()
				{
					var answer = confirm("Are you sure you want to set your logo to the bottom and your menu to the top?")
					if (answer){
						Photocrati_ThemeOptions_Admin.updateLogoPosition('bottom-top', '#msgmp_3');
					}
				});

				jQuery("#top-bottom").on('click', function()
				{
					var answer = confirm("Are you sure you want to set your logo to the top and your menu to the bottom?")
					if (answer){
						Photocrati_ThemeOptions_Admin.updateLogoPosition('top-bottom', '#msgmp_4');
					}
				});

				jQuery("#update-header").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#header-options', '#msghb');
				});

				jQuery("#menu_container_style").change(function()
				{
					if (jQuery("#menu_container_style").val() == 'color') {
						jQuery("#menu_container_color").val('FFFFFF');
						jQuery("#menu_container_color").css('background', '#FFFFFF');
						jQuery("#menu-container-color").show();
					} else {
						jQuery("#menu_container_color").val('transparent');
						jQuery("#menu-container-color").hide();
					}
				});
				
				jQuery("#menu_style").change(function()
				{
					if (jQuery("#menu_style").val() == 'color') {
						jQuery("#menu-style").show();
					} else {
						jQuery("#menu-style").hide();
					}
				});

				jQuery("#update-menu-global-c").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#menu-global-options', '#msgmgs');
				});

				jQuery("#update-menu-c").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#menu-options', '#msgms');
				});

				jQuery("#update-submenu-c").on('click', function()
				{
					Photocrati_ThemeOptions_Admin.submitStyleForm('#submenu-options', '#msgsms');
				});

			});
			</script>

			<div id="container">
				<div class="options">Header Options & Margins</div>

					<div class="sub-options"><span id="comments">If you are using a large logo you can adjust the header height and logo margin here</span></div>
					<div class="option-content">

						<div id="option-container">

						<form name="header-sizes" id="header-sizes" method="post">

							<div class="left-sm header-height">
								<p class="titles">Header Height</p>
								<p><input type="text" name="header_height" id="header_height" value="<?php echo $header_height; ?>" size="2" />px</p>
							</div>

							<div class="left-sm style-skin-modern header-width">
								<p class="titles">Header Width</p>
								<p><input type="text" name="header_width" id="header_width" value="<?php echo $header_width; ?>" size="2" />px</p>
							</div>
							
							<div class="right-lg" id="right-lg">
								<?php /*
								<div class="left style-skin-modern header-margin" style="border:0; width:31%;">
									<p class="titles">Header Top Margin</p>
									<p><input type="text" name="header_margin_top" id="header_margin_top" value="<?php echo $header_margin_top; ?>" size="2" />px</p>
								</div>
								*/ ?>
								<div class="left style-skin-modern header-margin" style="border:0; width:31%;">
									<p class="titles">Header Left Margin</p>
									<p><input type="text" name="header_margin_left" id="header_margin_left" value="<?php echo $header_margin_left; ?>" size="2" />px</p>
								</div>
								<div class="left style-skin-modern header-margin" style="border:0; width:31%;">
									<p class="titles">Header Right Margin</p>
									<p><input type="text" name="header_margin_right" id="header_margin_right" value="<?php echo $header_margin_right; ?>" size="2" />px</p>
								</div>
							</div>

							<div class="clear"></div>
							<div class="left-sm" id="left-sm-margin" <?php if($custom_logo <> 'custom' || $one_column_logo == 'OFF') { echo 'style="display:none;"'; } ?>>
								<p class="titles">Menu Top Margin</p>
									<p><input type="text" name="one_column_margin" id="one_column_margin" value="<?php echo $one_column_margin; ?>" size="2" />px</p>
									<p class="notes">
										You can adjust the vertical position of the menu with this setting.
									</p>
							</div>

							<div class="right-lg" id="right-lg">
								<div class="left" style="border:0; width:31%;">
									<p class="titles">Logo Top Margin</p>
									<p><input type="text" name="header_logo_margin_above" id="header_logo_margin_above" value="<?php echo $header_logo_margin_above; ?>" size="2" />px</p>
								</div>
								<div class="left" style="border:0; width:31%;">
									<p class="titles">Logo Bottom Margin</p>
									<p><input type="text" name="header_logo_margin_below" id="header_logo_margin_below" value="<?php echo $header_logo_margin_below; ?>" size="2" />px</p>
								</div>
								<?php /*now that the header is correctly aligned to the content with don't need this option... leaving it in case we still want to add it */
								?>
								<div class="right style-skin-modern" style="border:0; width:32%;">
									<p class="titles">Logo Side Margin</p>

									<p><input type="text" name="header_logo_margin_side" id="header_logo_margin_side" value="<?php echo $header_logo_margin_side; ?>" size="2" />px</p>

								</div> 
								<p class="notes" style="margin-top: 0; clear: both;">Using large Logo Top and Bottom Margins with a "fixed" header might require an increase in the Header Height to accommodate for the new space.</p>
							</div>
							
							<div class="clear"></div>
							<div class="left-sm style-skin-modern header-position" id="left-sm-margin">
								<p class="titles">Header Display</p>
									<p>
										<select type="text" name="header_position" id="header_position" value="<?php echo $header_position; ?>"> 
											<option value="static"<?php if($header_position == 'static') {echo ' selected="selected"'; } ?>>Automatic</option>
                  		<option value="fixed"<?php if($header_position == 'fixed') {echo ' selected="selected"'; } ?>>Fixed</option>
                  	</select>
                  </p>
							</div>

						<div class="left style-skin-modern" style="border:0; width: 46%;">

									<p class="titles">Header Drop Shadow</p>
									<p><input type="text" name="header_drop_shadow_size" id="header_drop_shadow_size" value="<?php echo $header_drop_shadow_size; ?>" size="7" style="" /> Shadow Size</p>
						</div>
							<div class="submit-button-wrapper">
								<input type="button" class="button" id="update-sizes" value="Save Header Options & Margins" style="clear:none;" />
							</div>
							<div class="msg" id="msghhlp" style="float:left;"></div>

						</form>

						</div>

				</div>

				<div class="options">Header Background</div>

				<div class="sub-options"><span id="comments">Change the color or image on the header background</span></div>
				<div class="option-content">

					<div id="option-container">

					<form name="header-options" id="header-options" method="post">

						<div class="left-sm">
							<p class="titles">Background Color</p>
							<p>#<input type="text" name="header_bg_color" id="header_bg_color" value="<?php echo $header_bg_color; ?>" size="7" style="background:#<?php echo $header_bg_color; ?>;" /> Color</p>
							<p>%<input type="text" name="header_opacity" id="header_opacity" value="<?php echo $header_opacity; ?>" size="7" /> Opacity</p>
						</div>

						<div class="right-lg" style="width: 69.5%;">
							<div class="left" style="border:0; width: 100%;">
								<p class="titles" style="margin-bottom:4px;">Background Image</p>
									<?php
										echo Photocrati_Theme_UI::build_image_picker_html('header_bg_image', $header_bg_image, 'background');
									?>
							</div>
						</div>
						
						<div style="clear: both;"></div>
						<div class="left" style="width:auto; float:none; clear: both;">
							<p class="titles" style="width:auto;">Background Image Settings</p>
							<div class="left-sm" style="width:32%;">
								<p>
								<i>Image Tiling:</i><br/>
								<input type="radio" name="header_bg_repeat" value="repeat"<?php if($header_bg_repeat == 'repeat') { echo ' checked'; } ?> /> Tile
								<BR /><input type="radio" name="header_bg_repeat" value="no-repeat"<?php if($header_bg_repeat == 'no-repeat') { echo ' checked'; } ?> /> No Repeat
								<BR /><input type="radio" name="header_bg_repeat" value="repeat-x"<?php if($header_bg_repeat == 'repeat-x') { echo ' checked'; } ?> /> Repeat Horizontal
								<BR /><input type="radio" name="header_bg_repeat" value="repeat-y"<?php if($header_bg_repeat == 'repeat-y') { echo ' checked'; } ?> /> Repeat Vertical
								</p>

							</div>
						</div>

						<div class="submit-button-wrapper">
							<input type="button" class="button" id="update-header" value="Save Header Background" style="clear:none;" />
						</div>
						<div class="msg" id="msghb" style="float:left;"></div>

					</form>

					</div>

				</div>
				
				<div class="options">Header Logo</div>

					<div class="sub-options"><span id="comments">Upload your own logo (225px by 90px recommended) or use the blog title / description</span></div>
					<div class="option-content">

						<div id="option-container">

						<form name="logo-options" id="logo-options" method="post">

							<div class="left">
								<p class="titles">Logo Options</p>
								<p>
									<select name="custom_logo" id="custom_logo">
										<option value="title"<?php if($custom_logo == 'title') {echo ' SELECTED'; } ?>>Wordpress Title </option>
										<option value="custom"<?php if($custom_logo == 'custom') {echo ' SELECTED'; } ?>>Custom Logo </option>
									</select>
								</p>
							</div>

                            <div class='left' id="right-lg-custom" style='border:0; width:100%; <?php if($custom_logo == 'custom'){echo 'display:none;'; }?>'>
                                <?php Photocrati_Fonts::render_font_fields('Title', array(
                                    'font_family_field_name'    =>  'title_style',
                                    'font_family_value'         =>  $title_style,
                                    'font_size_field_name'      =>  'title_size',
                                    'font_size_value'           =>  $title_size,
                                    'font_color_field_name'     =>  'title_color',
                                    'font_color_value'          =>  $title_color,
                                    'font_weight_field_name'    =>  'title_font_weight',
                                    'font_weight_value'         =>  $title_font_weight,
                                    'font_italics_field_name'   =>  'title_italic',
                                    'font_italics_value'        =>  $title_italic,
                                    'font_decoration_field_name'=>  'title_decoration',
                                    'font_decoration_value'     =>  $title_decoration,
                                    'font_case_field_name'      =>  'title_font_case',
                                    'font_case_value'           =>  $title_font_case
                                ));

                                Photocrati_Fonts::render_font_fields('Description', array(
                                    'font_family_field_name'    =>  'description_style',
                                    'font_family_value'         =>  $description_style,
                                    'font_size_field_name'      =>  'description_size',
                                    'font_size_value'           =>  $description_size,
                                    'font_color_field_name'     =>  'description_color',
                                    'font_color_value'          =>  $description_color,
                                    'font_weight_field_name'    =>  'description_font_weight',
                                    'font_weight_value'         =>  $description_font_weight,
                                    'font_italics_field_name'   =>  'description_font_italic',
                                    'font_italics_value'        =>  $description_font_italic,
                                    'font_case_field_name'      =>  'description_font_style',
                                    'font_case_value'           =>  $description_font_style == '' ? 'normal' : $description_font_style,
                                    'font_decoration_field_name'=>  'description_font_decoration',
                                    'font_decoration_value'     =>  $description_font_decoration
                                )); ?>
                            </div>

							<div class="" id="right-lg-cl" <?php if($custom_logo <> 'custom') { echo 'style="display:none;"'; } ?>>
								<div class="left" style="border:0; width: 100%;">
									<p class="titles" style="margin-bottom:4px;">Custom logo</p>
									<?php
										echo Photocrati_Theme_UI::build_image_picker_html('custom_logo_image', $custom_logo_image, 'logo');
									?>
									<p> </p>
								</div>
							</div>

							<div class="style-skin-legacy" style="" id="full_width_wrapper">

								<div class="clear"></div>
								<div class="left-sm" id="left-sm-full" <?php if($custom_logo <> 'custom') { echo 'style="display:none;"'; } ?>>
									<p class="titles">Full Width Logo / Header</p>
									<p>
										<select name="one_column_logo" id="one_column_logo">
											<option value="OFF"<?php if(!$one_column_logo) {echo ' SELECTED'; } ?>>OFF </option>
											<option value="ON"<?php if($one_column_logo) {echo ' SELECTED'; } ?>>ON </option>
										</select>
									</p>
								</div>

								<div class="right-lg" id="right-lg-full" <?php if($custom_logo <> 'custom') { echo 'style="display:none;"'; } ?>>
									<div class="left" style="border:0;width:100%;">
										<p class="notes" style="padding-top:10px;">
											If you turn Full Width Logo / Header on, you must upload an image that is <b>990px wide</b> to fill the
											entire header width!
										</p>
									</div>
								</div>

							</div>

							<div class="submit-button-wrapper">
								<input type="button" class="button" id="update-logo" value="Save Logo" style="clear:none;" />
							</div>
							<div class="msg" id="msghl" style="float:left;"></div>

						</form>

						</div>

				</div>

				<div class="options header-logo-menu-positions">Logo & Menu Positions</div>

					<div class="sub-options header-logo-menu-positions"><span id="comments">Choose the positions of the menu and logo. Click the button of choice to select the desired position.</span></div>
					<div class="option-content header-logo-menu-positions">

						<div id="option-container">

						<form name="logo-menu-options" id="logo-menu-options" method="post">

							<div class="left" id="color-choices" style="overflow:hidden;">

								<p>
									<div class="option">
									<img src="<?php bloginfo('template_directory'); ?>/admin/images/logo-left-right.jpg" />
									<input type="button" class="button" id="left-right" value="Logo Left / Menu Right" />
									<?php if($logo_menu_position == 'left-right') { ?>
									<img id="msgmp_c" src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;" align="absmiddle" />
									<?php } ?>
									<img id="msgmp_1" src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;display:none;" align="absmiddle" />
									</div>
									<div class="option">
									<img src="<?php bloginfo('template_directory'); ?>/admin/images/logo-right-left.jpg" />
									<input type="button" class="button" id="right-left" value="Logo Right / Menu Left" />
									<?php if($logo_menu_position == 'right-left') { ?>
									<img id="msgmp_c" src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;" align="absmiddle" />
									<?php } ?>
									<img id="msgmp_2" src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;display:none;" align="absmiddle" />
									</div>
									<div class="option">
									<img src="<?php bloginfo('template_directory'); ?>/admin/images/logo-bottom-top.jpg" />
									<input type="button" class="button" id="bottom-top" value="Logo Bottom / Menu Top" />
									<?php if($logo_menu_position == 'bottom-top') { ?>
									<img id="msgmp_c" src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;" align="absmiddle" />
									<?php } ?>
									<img id="msgmp_3" src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;display:none;" align="absmiddle" />
									</div>
								</p>

								<p>
									<div class="option">
									<img src="<?php bloginfo('template_directory'); ?>/admin/images/logo-top-bottom.jpg" />
									<input type="button" class="button" id="top-bottom" value="Logo Top / Menu Bottom" />
									<?php if($logo_menu_position == 'top-bottom') { ?>
									<img id="msgmp_c" src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;" align="absmiddle" />
									<?php } ?>
									<img id="msgmp_4" src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;display:none;" align="absmiddle" />
									</div>
								</p>

							</div>

						</form>

						</div>

				</div>

				<div class="options style-skin-modern">Menu Container Options</div>

					<div class="sub-options style-skin-modern"><span id="comments">General settings for the menu container.</span></div>
					<div class="option-content style-skin-modern">

						<div id="option-container">

						<form name="menu-options" id="menu-global-options" method="post">

							<div class="left" style="width: 100%;">
								<?php /*
                  <div class="inner" style="width:33%;">
                      <p class="titles">Menu Skin</p>
                      <p>
                      <?php echo Photocrati_Theme_UI::build_dropdown_html('menu_skin', $menu_skin, Photocrati_Style_Manager::get_menu_skin_list()); ?>
                      </p>
                  </div>
              	*/ ?>
                  
								<div class="inner style-skin-modern menu-alignment" style="width:33%;">
									<p class="titles">Menu Alignment</p>
									<p>
									<?php echo Photocrati_Theme_UI::build_dropdown_html('menu_alignment', $menu_alignment, array('default' => __('Leave as default'), 'left' => __('On the Left'), 'right' => __('On the Right'), 'center' => __('Centered'))); ?>
									</p>
								</div>
                  
								<div class="inner style-skin-modern menu-vertical-padding" style="width:33%;">
									<p class="titles">Menu Vertical Padding</p>
									<p><input type="text" name="menu_vertical_padding" id="menu_vertical_padding" value="<?php echo $menu_vertical_padding; ?>" size="2" style="" />px</p>
								</div>
                  
								<div class="clear"></div>
								<div class="inner" style="width:33%;">
									<p class="titles">Menu Background Style</p>
									<p>
									<select id="menu_container_style">
										<option value="transparent"<?php if($menu_container_color == 'transparent') {echo ' SELECTED'; } ?>>Transparent</option>
										<option value="color"<?php if($menu_container_color <> 'transparent') {echo ' SELECTED'; } ?>>Color</option>
									</select>
									</p>
								</div>
								
								<div class="inner" id="menu-container-color" style="width:33%;<?php if($menu_container_color == 'transparent') { echo 'display:none;'; } ?>">
									<p class="titles">Menu Background Color</p>
									<p>#<input type="text" name="menu_container_color" id="menu_container_color" value="<?php if($menu_container_color <> 'transparent') { echo $menu_container_color; } else { echo 'transparent'; } ?>" size="7"  <?php if($menu_container_color <> 'transparent') { ?>style="background:#<?php echo $menu_container_color; ?>;"<?php } ?> /> Color</p>
									<p>%<input type="text" name="menu_container_opacity" id="menu_container_opacity" value="<?php echo $menu_container_opacity; ?>" size="7" /> Opacity</p>
								</div>

							</div>
							
              <div class="submit-button-wrapper">
                  <input type="button" class="button" id="update-menu-global-c" value="Save Menu Changes" style="clear:none;" />
              </div>
              <div class="msg" id="msgmgs" style="float:left;"></div>

						</form>

						</div>

				</div>
				
				<div class="options">Parent Menu Items</div>

					<div class="sub-options"><span id="comments">Change the color of menus and menu text</span></div>
					<div class="option-content">

						<div id="option-container">

						<form name="menu-options" id="menu-options" method="post">

							<div class="left" style="width:100%;">

                                <div class="inner" style="width:33%;">
                                    <p class="titles">Menu Background Style</p>
                                    <p>
                                    <select id="menu_style" name="menu_style">
                                        <option value="transparent"<?php if($menu_style == 'transparent') {echo ' SELECTED'; } ?>>Transparent </option>
                                        <option value="color"<?php if($menu_style <> 'transparent') {echo ' SELECTED'; } ?>>Color </option>
                                    </select>
                                    </p>
                                </div>

                                <div id="menu-style"<?php if($menu_style == 'transparent') {echo ' style="display:none;"'; } ?>>
                                    <div class="inner" style="width:33%;">
                                        <p class="titles">Background Color</p>
                                        <p>#<input type="text" name="menu_color" id="menu_color" value="<?php echo $menu_color; ?>" size="7" style="background:#<?php echo $menu_color; ?>;" /> Color</p>
																				<p>%<input type="text" name="menu_color_opacity" id="menu_color_opacity" value="<?php echo $menu_color_opacity; ?>" size="7" /> Opacity</p>
                                    </div>
                                    <div class="inner" style="width:33%;">
                                        <p class="titles">Background Hover/Active Color</p>
                                        <p>#<input type="text" name="menu_hover_color" id="menu_hover_color" value="<?php echo $menu_hover_color; ?>" size="7" style="background:#<?php echo $menu_hover_color; ?>;" /> Color</p>
																				<p>%<input type="text" name="menu_hover_color_opacity" id="menu_hover_color_opacity" value="<?php echo $menu_hover_color_opacity; ?>" size="7" /> Opacity</p>
                                    </div>
                                </div>
							</div>

							<div class="left" style="width:100%;">
                                <?php Photocrati_Fonts::render_font_fields('Font', array(
                                    'font_family_field_name'    =>  'menu_font_style',
                                    'font_family_value'         =>  $menu_font_style,
                                    'font_size_field_name'      =>  'menu_font_size',
                                    'font_size_value'           =>  $menu_font_size,
                                    'font_color_field_name'     =>  'menu_font_color',
                                    'font_color_value'          =>  $menu_font_color,
                                    'font_weight_field_name'    =>  'menu_font_weight',
                                    'font_weight_value'         =>  $menu_font_weight,
                                    'font_italics_field_name'   =>  'menu_font_italic',
                                    'font_italics_value'        =>  $menu_font_italic,
                                    'font_decoration_field_name'=>  'menu_font_decoration',
                                    'font_decoration_value'     =>  $menu_font_decoration,
                                    'font_case_field_name'      =>  'menu_font_case',
                                    'font_case_value'           =>  $menu_font_case
                                )); ?>

                                <div class="inner">
                                    <p class="titles">Font Hover/Active Color</p>
                                    <p>#<input type="text" name="menu_font_hover_color" id="menu_font_hover_color" value="<?php echo $menu_font_hover_color; ?>" size="7" style="background:#<?php echo $menu_font_hover_color; ?>;" /> Color</p>
                                </div>
                            </div>


                            <div class="submit-button-wrapper">
                                <input type="button" class="button" id="update-menu-c" value="Save Menu Changes" style="clear:none;" />
                            </div>
                            <div class="msg" id="msgms" style="float:left;"></div>

				   	    </form>

						</div>

					</div>

					<div class="options">Dropdown Menu Items</div>

						<div class="sub-options"><span id="comments">Change the color of dropdown menus and dropdown menu text</span></div>
						<div class="option-content">

							<div id="option-container">

							<form name="submenu-options" id="submenu-options" method="post">

								<div class="left" style="width:100%;">
                                    <div class="inner">
                                        <p class="titles">Background Color</p>
                                        <p>#<input type="text" name="submenu_color" id="submenu_color" value="<?php echo $submenu_color; ?>" size="7" style="background:#<?php echo $submenu_color; ?>;" /> Color</p>
																				<p>%<input type="text" name="submenu_color_opacity" id="submenu_color_opacity" value="<?php echo $submenu_color_opacity; ?>" size="7" /> Opacity</p>
                                    </div>
                                    <div class="inner">
                                        <p class="titles">Background Hover / Active Color</p>
                                        <p>#<input type="text" name="submenu_hover_color" id="submenu_hover_color" value="<?php echo $submenu_hover_color; ?>" size="7" style="background:#<?php echo $submenu_hover_color; ?>;" /> Color</p>
																				<p>%<input type="text" name="submenu_hover_color_opacity" id="submenu_hover_color_opacity" value="<?php echo $submenu_hover_color_opacity; ?>" size="7" /> Opacity</p>
                                    </div>
                                </div>

                                <div class="left" style="width:100%;">
                                    <?php Photocrati_Fonts::render_font_fields('Submenu Font', array(
                                        'font_family_field_name'    =>  'submenu_font_style',
                                        'font_family_value'         =>  $submenu_font_style,
                                        'font_color_field_name'     =>  'submenu_font_color',
                                        'font_color_value'          =>  $submenu_font_color,
                                        'font_size_field_name'      =>  'submenu_font_size',
                                        'font_size_value'           =>  $submenu_font_size,
                                        'font_weight_field_name'    =>  'submenu_font_weight',
                                        'font_weight_value'         =>  $submenu_font_weight,
                                        'font_italics_field_name'   =>  'submenu_font_italic',
                                        'font_italics_value'        =>  $submenu_font_italic,
                                        'font_decoration_field_name'=>  'submenu_font_decoration',
                                        'font_decoration_value'     =>  $submenu_font_decoration,
                                        'font_case_field_name'      =>  'submenu_font_case',
                                        'font_case_value'           =>  $submenu_font_case
                                    )); ?>

                                    <div class='inner'>
                                        <p class='titles'></p>
                                        <p class="titles">Font Hover/Active Color</p>
                                        <p>#<input type="text" name="submenu_font_hover_color" id="submenu_font_hover_color" value="<?php echo $submenu_font_hover_color; ?>" size="7" style="background:#<?php echo $submenu_font_hover_color; ?>;" /> Color</p>
                                    </div>
								</div>

                                <div class="submit-button-wrapper">
                                    <input type="button" class="button" id="update-submenu-c" value="Save Dropdown Changes" style="clear:none;" />
                                </div>
                                <div class="msg" id="msgsms" style="float:left;"></div>

					   	</form>

                        </div>

                    </div>

			</div>

		</div>
		<?php } ?>

		<?php if($dynamic_style) { ?>
		<div id="custom_tab1" class="tab_content">

		<?php include "scripts/scripts-upload.php"; ?>
		<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			var switchLayout = function(val)
			{
				var jform = jQuery('form#layout-options');
				var jothers = jQuery('.header-logo-menu-positions, .header-position, .header-height, .menu-vertical-padding, .layout-spacing');
				var jleft = jQuery('.header-width, .header-margin, .menu-alignment, .layout-alignment');
				var onecol = jQuery('.layout-one-column-background, .layout-drop-shadow');
				var noOnecol = jQuery('.header-position');
				
				if (typeof(val) === 'undefined') {
					val = jform.find('input[name=\"one_column\"]:checked').val();
				}
				
				if (val == 'left_header') {
					jothers.hide('slow');
					onecol.hide('slow');
					jleft.show('fast');
					noOnecol.show('fast');
				}
				else if (val == 'ON') {
					noOnecol.hide('slow');
					onecol.show('fast');
				}
				else {
					jleft.hide('slow');
					onecol.hide('slow');
					jothers.show('fast');
					noOnecol.show('fast');
				}
			};
			
			var displayModernOptions = function () 
			{
				var skinSel = jQuery('form#theme-skin select[name="style_skin"]');
				var modern = skinSel.val() == 'modern';
				
				if (modern) {
					jQuery('.style-skin-legacy').hide('fast');
					jQuery('.style-skin-modern').show('slow');
					switchLayout();
				}
				else {
					jQuery('.style-skin-modern').hide('fast');
					jQuery('.style-skin-legacy').show('slow');
					switchLayout(false);
				}
			};
			
			switchLayout();
			displayModernOptions();
			
			jQuery('form#theme-skin select[name="style_skin"]').on('change', function()
			{
				displayModernOptions();
			});
			
			jQuery("#update-theme-skin").on('click', function()
			{
				var ask = false;
				var answer = true;
				
				if (ask) {
					answer = confirm("?");
				}
				
				if (answer){
					Photocrati_ThemeOptions_Admin.submitStyleForm('#theme-skin', '#msg-theme-skin');
				}
			});

			jQuery("#update-layout").on('click', function()
			{
				var form = jQuery(this).parents('form');
				var opts = ['layout_width', 'layout_width_max', 'layout_width_min'];
				var ask = false;
				var answer = true;
				
				for (var i = 0; i < opts.length; i++) {
					var opt = opts[i];
					var name = opt;
					var value =  form.find('[name="' + name + '"]');
					var unit = form.find('[name="' + name + '_unit"]');
					var val = parseFloat(value.val());
					
					if (unit.val() == 'percent' && val > 100) {
						ask = true;
					}
				}
				
				if (ask) {
					answer = confirm("One of your width settings is set to a value higher than 100%, this might cause layout problems. Do you want to save your settings anyway?");
				}
				
				if (answer){
					Photocrati_ThemeOptions_Admin.submitStyleForm('#layout-options', '#msglay');
				}
			});
			
			jQuery("input[name=\"one_column\"]").on('click', function()
			{
				switchLayout();
			});

			jQuery("#update-background").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#bg-options', '#msgmb');
			});

			jQuery("#update-home-options").on('click', function()
			{
				Photocrati_ThemeOptions_Admin.submitStyleForm('#home-options', '#msgmhb');
			});

			jQuery("#update-mp3").on('click', function()
			{
				jQuery("#msgmp3").html("<img src='<?php bloginfo('template_url'); ?>/admin/images/ajax-loader.gif'>");
				jQuery("#msgmp3").show();

				var str2 = jQuery("#mp3-options").serialize();
				jQuery.ajax({type: "POST", url: "<?php bloginfo('template_url'); ?>/admin/scripts/edit-gallery.php", data: str2, success: function(data)
				{
					jQuery("#msgmp3").html("Music Changes Saved");
					jQuery("#msgmp3")
						.animate({opacity: 1.0}, 2000)
						.fadeOut('slow');
				}
				});
			});

		});
		</script>

			<div id="container">

			<div class="options">Theme Skin</div>

					<div class="sub-options"><span id="comments">Set the overall theme skin</span></div>
					<div class="sub-options" style="background: #dde0aa; border-top:	1px solid #CCC;"><span id="comments" style="color: #842;">Caution: the <i>Theme Skin</i> setting has a major influence on the theme's appearance and changing it will notably affect your site design. Please be prepared to make adjustments. Also note that there are separate custom CSS boxes on the <a href="<?php echo admin_url('admin.php?page=other-options'); ?>">Other Options</a> page, so you'll need to copy any desired custom CSS from one area to the other and make tweaks as required.</span></div>
					<div class="option-content">

						<div id="option-container">

						<form name="theme-skin" id="theme-skin" method="post">

							<div class="left-sm" style="">
								<p class="titles">Theme Skin</p>
								<p>
									<?php echo Photocrati_Theme_UI::build_dropdown_html('style_skin', $style_skin, array('legacy' => __('Legacy (NOT responsive)'), 'modern' => __('Modern (responsive)'))); ?>
								</p>
							</div>

							<div class="submit-button-wrapper">
								<input type="button" class="button" id="update-theme-skin" value="Save Theme Skin" style="clear:none;" />
							</div>
							<div class="msg" id="msg-theme-skin" style="float:left;"></div>

						</form>

						</div>

				</div>

			<div class="options">Layout Style</div>

					<div class="sub-options"><span id="comments">Set the theme layout style - full width or one column blog style</span></div>
					<div class="option-content">

						<div id="option-container">

						<form name="layout-options" id="layout-options" method="post">

							<div class="left-sm" style="clear: both; width: 95%;">
								<p class="titles">Layout Style</p>
								<p>
									<label for="one_column_off" style="width: 33%; float: left; text-align:center;"><img src="<?php bloginfo('template_url'); ?>/admin/images/layout-full-width.jpg" style="border: 1px solid #bbb;"><br/>
										Full Width<br/>
										<input type="radio" name="one_column" id="one_column_off" value="OFF" <?php if($one_column === false) {echo'CHECKED';} ?> />
									</label>
									<label for="one_column_on" style="width: 33%; float: left; text-align:center;"><img src="<?php bloginfo('template_url'); ?>/admin/images/layout-one-column-center.jpg" style="border: 1px solid #bbb;"><br/>
										One Column Centered<br/>
									<input type="radio" name="one_column" id="one_column_on" value="ON" <?php if($one_column === true) {echo'CHECKED';} ?> />
									</label>
									<label class="style-skin-modern" for="layout_left_header" style="width: 33%; float: left; text-align:center;"><img src="<?php bloginfo('template_url'); ?>/admin/images/layout-left-header2.jpg" style="border: 1px solid #bbb;"><br/>
										Left Side Header<br/>
									<input type="radio" name="one_column" id="layout_left_header" value="left_header" <?php if($one_column === 'left_header') {echo'CHECKED';} ?> />
									</label>
								</p>
							</div>
							
							<div style="clear: both;"></div>
							<div class="left-sm style-skin-modern" style="float:left;width:50%;">
								<p class="titles">Layout Width</p>
								<p>
									<select name="layout_width_unit" value="<?php echo $layout_width_unit; ?>"><option value="px"<?php if($layout_width_unit == 'px') {echo ' selected="selected"'; } ?>>px</option><option value="percent"<?php if($layout_width_unit == 'percent') {echo ' selected="selected"'; } ?>>%</option></select> <input type="text" size="7" name="layout_width" id="layout_width" value="<?php echo $layout_width; ?>" /> Width<BR>
									<select name="layout_width_min_unit" value="<?php echo $layout_width_min_unit; ?>"><option value="px"<?php if($layout_width_min_unit == 'px') {echo ' selected="selected"'; } ?>>px</option><option value="percent"<?php if($layout_width_min_unit == 'percent') {echo ' selected="selected"'; } ?>>%</option></select> <input type="text" size="7" name="layout_width_min" id="layout_width_min" value="<?php echo $layout_width_min; ?>" /> Minimum Width<BR>
									<select name="layout_width_max_unit" value="<?php echo $layout_width_max_unit; ?>"><option value="px"<?php if($layout_width_max_unit == 'px') {echo ' selected="selected"'; } ?>>px</option><option value="percent"<?php if($layout_width_max_unit == 'percent') {echo ' selected="selected"'; } ?>>%</option></select> <input type="text" size="7" name="layout_width_max" id="layout_width_max" value="<?php echo $layout_width_max; ?>" /> Maximum Width<BR>
								</p>
							</div>
							
							<div class="left-sm style-skin-modern layout-spacing" style="float:left;width:49%;">
								<p class="titles">Layout Spacing</p>
								<p><input type="text" name="layout_spacing" id="layout_spacing" value="<?php echo $layout_spacing; ?>" size="2" style="" />px</p>
							</div>
							
							<div class="left-sm style-skin-modern layout-alignment" style="float:left;width:49%;">
								<p class="titles">Layout Alignment</p>
								<p>Keep Main Content 
									<?php echo Photocrati_Theme_UI::build_dropdown_html('layout_alignment', $layout_alignment, array('left' => __('On the Left'), 'center' => __('Centered'))); ?>
								</p>
							</div>

							<div class="submit-button-wrapper">
								<input type="button" class="button" id="update-layout" value="Save Layout Style" style="clear:none;" />
							</div>
							<div class="msg" id="msglay" style="float:left;"></div>

						</form>

						</div>

				</div>

				<div class="options">Background Settings</div>

					<div class="sub-options"><span id="comments">Change the color or image on the main, content and one column content backgrounds.</span></div>
					<div class="option-content">

						<div id="option-container">

						<form name="bg-options" id="bg-options" method="post">

							<div class="left-sm">
								<p class="titles">Background Color</p>
								<p>#<input type="text" name="bg_color" id="bg_color" value="<?php echo $bg_color; ?>" size="7" style="background:#<?php echo $bg_color; ?>;" /> Color</p>
								<?php /*
								<p>%<input type="text" name="bg_opacity" id="bg_opacity" value="<?php echo $bg_opacity; ?>" size="7" style="" /> Opacity</p>
								*/ ?>
							</div>

							<div class="right-lg" style="width: 69.5%;">
								<div class="left" style="border:0; width: 100%;">
									<p class="titles" style="margin-bottom:4px;">Background Image</p>
									<?php
										echo Photocrati_Theme_UI::build_image_picker_html('bg_image', $bg_image, 'background');
									?>
								</div>
							</div>

							<div style="clear: both;"></div>
							<div class="left" style="width:auto; float:none; clear: both;">
								<p class="titles" style="width:auto;">Background Image Settings</p>
								<div class="left-sm" style="width:32%;">
										<p>
										<i>Image Tiling:</i><br/>
										<input type="radio" name="bg_repeat" value="repeat"<?php if($bg_repeat == 'repeat') { echo ' checked'; } ?> /> Tile
										<BR /><input type="radio" name="bg_repeat" value="no-repeat"<?php if($bg_repeat == 'no-repeat') { echo ' checked'; } ?> /> No Repeat
										<BR /><input type="radio" name="bg_repeat" value="repeat-x"<?php if($bg_repeat == 'repeat-x') { echo ' checked'; } ?> /> Repeat Horizontal
										<BR /><input type="radio" name="bg_repeat" value="repeat-y"<?php if($bg_repeat == 'repeat-y') { echo ' checked'; } ?> /> Repeat Vertical
										</p>
								</div>
								<div class="left-sm" style="width:32%;">
										<p>
										<i>Image Anchoring:</i><br/>
										<input type="radio" name="bg_attachment" value="scroll"<?php if($bg_attachment == 'scroll') { echo ' checked'; } ?> /> Scroll with page
										<BR /><input type="radio" name="bg_attachment" value="fixed"<?php if($bg_attachment == 'fixed') { echo ' checked'; } ?> /> Fixed in place
										</p>
								</div>
								<div class="left-sm" style="width:32%;">
										<p>
										<i>Image Scaling:</i><br/>
										<input type="radio" name="bg_scale" value="auto"<?php if($bg_scale == 'auto') { echo ' checked'; } ?> /> Automatic
										<BR /><input type="radio" name="bg_scale" value="cover"<?php if($bg_scale == 'cover') { echo ' checked'; } ?> /> Cover whole area
										<BR /><input type="radio" name="bg_scale" value="contain"<?php if($bg_scale == 'contain') { echo ' checked'; } ?> /> Cover without cropping
										</p>
										
										<p>Offset <input type="text" name="bg_top_offset" id="bg_top_offset" value="<?php echo $bg_top_offset; ?>" size="2" />px From Top
										</p>
								</div>
							</div>
										
						<div style="clear: both;"></div>
						<div class="left" style="border:0; width: 46%;">

								<div class="inner">
									<p class="titles">Content Background Style</p>
									<p>
									<select id="container_style">
										<option value="transparent"<?php if($container_color == 'transparent') {echo ' SELECTED'; } ?>>Transparent </option>
										<option value="color"<?php if($container_color <> 'transparent') {echo ' SELECTED'; } ?>>Color </option>
									</select>
									</p>
								</div>
								<div class="inner" id="container-color"<?php if($container_color == 'transparent') {echo ' style="display:none;"'; } ?>>
									<p class="titles">Content Background Color</p>
									<p>#<input type="text" name="container_color" id="container_color" value="<?php if($container_color <> 'transparent') { echo $container_color; } else { echo 'transparent'; } ?>" size="7"  <?php if($container_color <> 'transparent') { ?>style="background:#<?php echo $container_color; ?>;"<?php } ?> /> Color</p>
									<p>%<input type="text" name="container_opacity" id="container_opacity" value="<?php echo $container_opacity; ?>" size="7" /> Opacity</p>
								</div>

						</div>

						<div class="right" style="border:0; width: 52%;">

								<div class="inner" style="width:30%;" id="container-border"<?php if($container_color == 'transparent') {echo ' style="display:none;"'; } ?>>
									<p class="titles">Content Border</p>
									<p><input type="text" name="container_border" id="container_border" value="<?php echo $container_border; ?>" size="2" />px</p>
								</div>

								<div class="inner" style="width:40%;" id="container-border-color"<?php if($container_color == 'transparent') {echo ' style="display:none;"'; } ?>>
									<p class="titles">Content Border Color</p>
									<p>#<input type="text" name="container_border_color" id="container_border_color" value="<?php echo $container_border_color; ?>" size="7"  style="background:#<?php echo $container_border_color; ?>;" /> Color</p>
								</div>

								<div class="inner" style="width:30%;" id="container-padding"<?php if($container_color == 'transparent') {echo ' style="display:none;"'; } ?>>
									<p class="titles">Content Padding</p>
									<p><input type="text" name="container_padding" id="container_padding" value="<?php echo $container_padding; ?>" size="2" />px</p>
								</div>

						</div>

							<div class="left-sm layout-one-column-background" style="float:left;width:66%;">
								<p class="titles">One Column Content Background Color</p>
								<p>#<input type="text" name="one_column_color" id="one_column_color" value="<?php echo $one_column_color; ?>" size="7" style="background:#<?php echo $one_column_color; ?>;" /> Color</p>
								<p>%<input type="text" name="one_column_opacity" id="one_column_opacity" value="<?php echo $one_column_opacity; ?>" size="7" style="" /> Opacity</p>
							</div>
										
						<div style="clear: both;"></div>
						<div class="left style-skin-modern layout-drop-shadow" style="border:0; width: 46%;">

									<p class="titles">Container Drop Shadow</p>
									<p><input type="text" name="container_drop_shadow_size" id="container_drop_shadow_size" value="<?php echo $container_drop_shadow_size; ?>" size="7" style="" /> Shadow Size</p>
						</div>
						
							<div class="submit-button-wrapper">
								<input type="button" class="button" id="update-background" value="Save Background Settings" style="clear:none;" />
							</div>
							<div class="msg" id="msgmb" style="float:left;"></div>

						</form>

						</div>

				</div>

				<div class="options style-skin-modern">Homepage Background</div>

					<div class="sub-options style-skin-modern"><span id="comments">Change the background options specifically for the homepage, with the option to add a full browser background slideshow. <b>Note</b>: For slideshow, please select more than one image. To remove slideshow and adopt global background settings, set main drop down to "Default". Also note that to obtain a full browser slideshow the "Template" under "Page Attributes" needs to be set to "Page Without Content".</span></div>
					<div class="option-content style-skin-modern">

						<div id="option-container">

						<form name="home-options" id="home-options" method="post">

							<div class="left-sm">
								<p class="titles">Background Type</p>
								<p>
									<?php echo Photocrati_Theme_UI::build_dropdown_html('home_bg', $home_bg, array('default' => __('Default'), 'image' => __('Single Image'), 'image_rotation' => __('Image Rotation'), 'slideshow' => __('Slideshow'))); ?>
								</p>
							</div>

							<div class="right-lg" style="width: 69.5%;">
								<div class="left" style="border:0; width: 100%;">
									<p class="titles" style="margin-bottom:4px;">Background Image</p>
									<?php
										echo Photocrati_Theme_UI::build_image_picker_html('home_bg_image', $home_bg_image, 'background-multiple');
									?>
								</div>
							</div>
							
						<div style="clear: both;"></div>
						<div class="left" style="border:0; width: 31%;">
									<p class="titles">Slideshow Effect</p>
									<p>
									<?php echo Photocrati_Theme_UI::build_dropdown_html('home_bg_transition_effect', $home_bg_transition_effect, array('0' => __('None'), '1' => __('Fade'), '2' => __('Slide Top'), '3' => __('Slide Right'), '4' => __('Slide Bottom'), '5' => __('Slide Left'), '6' => __('Carousel Right'), '7' => __('Carousel Left'))); ?>
									</p>
						</div>
						<div class="left" style="border:0; width: 31%;">

									<p class="titles">Slideshow Interval</p>
									<p><input type="text" name="home_bg_interval_speed" id="home_bg_interval_speed" value="<?php echo $home_bg_interval_speed; ?>" size="2" style="" /> Seconds</p>
						</div>
						<div class="left" style="border:0; width: 31%;">

									<p class="titles">Slideshow Speed</p>
									<p><input type="text" name="home_bg_transition_speed" id="home_bg_transition_speed" value="<?php echo $home_bg_transition_speed; ?>" size="2" style="" /> Seconds</p>
						</div>
							
							<div style="clear: both;"></div>
							<div class="submit-button-wrapper">
								<input type="button" class="button" id="update-home-options" value="Save Homepage Background Settings" style="clear:none;" />
							</div>
							<div class="msg" id="msgmhb" style="float:left;"></div>

						</form>

						</div>

				</div>

			<div class="options">Blog & Category Music</div>

				<div class="sub-options"><span id="comments">Add an MP3 file for playback on the blog and category pages</span></div>
				<div class="option-content">

					<form name="mp3-options" id="mp3-options" method="post">

					<div id="option-container">

						<div class="left" style="width:99%;">

							<div class="inner" style="width:33%;">
								<p class="titles">Blog Music</p>
								<p>
								<input type="radio" name="music_blog" id="music_blog" value="ON" <?php checked($music_blog, TRUE) ?> /> On &nbsp;&nbsp;
								<input type="radio" name="music_blog" id="music_blog" value="OFF" <?php checked($music_blog, FALSE) ?> /> Off
								</p>
							</div>

							<div class="inner" style="width:33%;">
								<p class="titles">Auto Play?</p>
								<p>
								<input type="radio" name="music_blog_auto" id="music_blog_auto" value="YES" <?php if($music_blog_auto == 'YES') {echo'CHECKED';} ?> /> Yes &nbsp;&nbsp;
								<input type="radio" name="music_blog_auto" id="music_blog_auto" value="NO" <?php if($music_blog_auto == 'NO') {echo'CHECKED';} ?> /> No
								</p>
							</div>

							<div class="inner" style="width:33%;">
								<p class="titles">Show Controls?</p>
								<p>
								<input type="radio" name="music_blog_controls" id="music_blog_controls" value="YES" <?php if($music_blog_controls == 'YES') {echo'CHECKED';} ?> /> Yes &nbsp;&nbsp;
								<input type="radio" name="music_blog_controls" id="music_blog_controls" value="NO" <?php if($music_blog_controls == 'NO') {echo'CHECKED';} ?> /> No
								</p>
							</div>

						</div>

						<div class="left" style="width:99%;">

							<div class="inner" style="width:99%;">
								<p class="titles">Blog File (mp3 format)</p>
								<p>Upload a file, copy the "Link URL", paste it into the field below and save this page/post.</p>
								<?php
								if ($option_disable_upload)
								{
									echo '<p style="color:#ee3311"><b>Note</b>: Because of incompatibilities with WordPress 3.2 and above the following Upload button functionality has been disabled, please upload your MP3 files using the <a href="' . esc_url(admin_url('upload.php')) . '">Media Library</a>, then copy and paste the URL to the file in the field below.</p>';
								}
								?>
								<p>
								<input type="text" name="music_blog_file" id="music_blog_file" value="<?php echo $music_blog_file; ?>" size="70" />
								<input type="button" class="button" id="add_image_music_blog_file" value="Upload mp3" style="clear:none;"<? echo $option_disable_upload ? ' disabled="disabled"' : null; ?> />
								</p>
							</div>

						</div>

						<div class="left" style="width:99%;">

							<div class="inner" style="width:33%;">
								<p class="titles">Category / Archive Music</p>
								<p>
								<input type="radio" name="music_cat" id="music_cat" value="ON" <?php if($music_cat == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
								<input type="radio" name="music_cat" id="music_cat" value="OFF" <?php if($music_cat == 'OFF') {echo'CHECKED';} ?> /> Off
								</p>
							</div>

							<div class="inner" style="width:33%;">
								<p class="titles">Auto Play?</p>
								<p>
								<input type="radio" name="music_cat_auto" id="music_cat_auto" value="YES" <?php if($music_cat_auto == 'YES') {echo'CHECKED';} ?> /> Yes &nbsp;&nbsp;
								<input type="radio" name="music_cat_auto" id="music_cat_auto" value="NO" <?php if($music_cat_auto == 'NO') {echo'CHECKED';} ?> /> No
								</p>
							</div>

							<div class="inner" style="width:33%;">
								<p class="titles">Show Controls?</p>
								<p>
								<input type="radio" name="music_cat_controls" id="music_cat_controls" value="YES" <?php if($music_cat_controls == 'YES') {echo'CHECKED';} ?> /> Yes &nbsp;&nbsp;
								<input type="radio" name="music_cat_controls" id="music_cat_controls" value="NO" <?php if($music_cat_controls == 'NO') {echo'CHECKED';} ?> /> No
								</p>
							</div>

						</div>

						<div class="left" style="width:99%;">

							<div class="inner" style="width:99%;">
								<p class="titles">Category File (mp3 format)</p>
								<p>Upload a file, copy the "Link URL", paste it into the field below and save this page/post.</p>
								<?php
								if ($option_disable_upload)
								{
									echo '<p style="color:#ee3311"><b>Note</b>: Because of incompatibilities with WordPress 3.2 and above the following Upload button functionality has been disabled, please upload your MP3 files using the <a href="' . esc_url(admin_url('upload.php')) . '">Media Library</a>, then copy and paste the URL to the file in the field below.</p>';
								}
								?>
								<p>
								<input type="text" name="music_cat_file" id="music_cat_file" value="<?php echo $music_cat_file; ?>" size="70" />
								<input type="button" class="button" id="add_image_music_cat_file" value="Upload mp3" style="clear:none;"<? echo $option_disable_upload ? ' disabled="disabled"' : null; ?> />
								</p>
							</div>

						</div>

						<div class="left" style="width:99%;">

							<div class="inner" style="width:99%;">
								<p class="notes">
									<b>Note:</b> <i>You can add music to individual pages or posts in the add / edit post or page
									screens.</i>
								</p>
							</div>

						</div>

						<div class="submit-button-wrapper">
							<input type="button" class="button" id="update-mp3" value="Save Music Settings" style="clear:none;" />
						</div>
						<div class="msg" id="msgmp3" style="float:left;"></div>

					</div>

					</form>

			</div>


				</div>
			</div>

		</div>
		<?php } ?>
