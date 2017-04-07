<?php
    $preset                 = Photocrati_Style_Manager::get_active_preset();
    $style_skin = $preset->style_skin;
    $footer_copy            = $preset->footer_copy;
    $show_photocrati        = $preset->show_photocrati;
    $google_analytics       = $preset->google_analytics;
    $footer_widget_placement= $preset->footer_widget_placement;
?>	
    
		</div><!-- #container -->
    </div><!-- #main -->

</div><!-- #wrapper -->	

<?php if ($style_skin == 'legacy'): ?>
</div> <!-- #main_container -->
<?php endif ?>

<?php if ($style_skin == 'modern'): ?>
<div class="footer_container">
<?php endif ?>
<div class="footer_wrapper">
	
	<?php
	
	$footerwidgetstop = '<script type="text/javascript">
	jQuery.noConflict();
	jQuery(document).ready(function($)
	{
		var adjustFooterWidgets = function () {
			var widgets = jQuery(".footer-widget-container");
			var countwidgets = widgets.size();
			var widgetarea = jQuery(".footer-widget-area").width();
			var widgetsize = Math.round(Math.floor(widgetarea / countwidgets) - 25);
			
			if (widgets.css("clear") != "both") {
				jQuery(".footer-widget-container").css("width", widgetsize+"px");
			}
			else {
				jQuery(".footer-widget-container").css("width", "auto");
			}
		};
    
		adjustFooterWidgets();
		
		$(window).on(\'resize orientationchange onfullscreenchange onmozfullscreenchange onwebkitfullscreenchange\', function (event) {
			adjustFooterWidgets();
    });
	});
	</script>
    
	<div id="footer-widgets" class="footer-widget-area">';
	$footerwidgetsbot = '</div><!-- #footer .widget-area -->';
	
	if($footer_widget_placement == '4' && is_front_page() && is_sidebar_active('footer_widget_area')) {
		echo $footerwidgetstop;
		dynamic_sidebar("footer_widget_area");
		echo $footerwidgetsbot;
	} else if($footer_widget_placement == '3' && is_sidebar_active('footer_widget_area')) {
		echo $footerwidgetstop;
		dynamic_sidebar("footer_widget_area");
		echo $footerwidgetsbot;
	} else if($footer_widget_placement == '2' && !is_front_page() && is_sidebar_active('footer_widget_area')) {
		echo $footerwidgetstop;
		dynamic_sidebar("footer_widget_area");
		echo $footerwidgetsbot;
	} else if($footer_widget_placement == '1' && !is_page() && is_sidebar_active('footer_widget_area')) {
		echo $footerwidgetstop;
		dynamic_sidebar("footer_widget_area");
		echo $footerwidgetsbot;
	} else if($footer_widget_placement == '0') {
		
	}
	?> 	
    
	<div id="footer">
		<div id="colophon">
		
        	<?php if ( function_exists( 'wp_nav_menu' ) ) { //Check if function exists for less than Wordpress 3.0 ?>
			<?php wp_nav_menu( array( 'container_class' => 'footer_menu', 'menu_class' => '', 'theme_location' => 'footer', 'fallback_cb' => '', 'depth' => 1 ) ); ?>		
        	<?php } ?>
        
			<div id="site-info">
				<p>
				<?php
				/* This inserts the footer copyright notice */
				if($footer_copy <> '') {
					echo str_replace('\"', '"', str_replace("\'", "'", $footer_copy));
					if($show_photocrati) {
					echo ' | ';
					}
				}
				?>
				<?php if($show_photocrati) { ?>
				Powered by <span id="theme-link"><a target="_blank" href="http://www.photocrati.com/" title="<?php _e( 'Photocrati Wordpress Themes', 'photocrati-framework' ) ?>" rel="designer"><?php _e( 'Photocrati', 'photocrati-framework' ) ?></a></span>
				<?php } ?>
				</p>			
			</div><!-- #site-info -->
			
		</div><!-- #colophon -->
	</div><!-- #footer -->

<?php wp_footer(); ?>
</div>
<?php if ($style_skin == 'modern'): ?>
</div> <!-- .footer_container -->
<?php endif ?>

<?php if ($style_skin == 'modern'): ?>
</div> <!-- #main_container -->
<?php endif ?>

<div id="wrapper_bottom"></div>

<?php 
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer-scripts.php');
?>
</body>
</html>
