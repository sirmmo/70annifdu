<?php 

// Register widgetized areas
function theme_widgets_init() {
	// Area 1
  register_sidebar( array (
  'name' => 'Sidebar Widget Area',
  'id' => 'primary_widget_area',
  'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
  'after_widget' => "</li>",
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
  ) );
  
  register_sidebar( array (
  'name' => 'Footer Widget Area',
  'id' => 'footer_widget_area',
  'before_widget' => '<div id="%1$s" class="footer-widget-container %2$s">',
  'after_widget' => "</div>",
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
  ) );
  
} // end theme_widgets_init

add_action( 'init', 'theme_widgets_init' );


// Check for static widgets in widget-ready areas
function is_sidebar_active( $index ){
  global $wp_registered_sidebars;

  $widgetcolums = wp_get_sidebars_widgets();
		 
  if ($widgetcolums[$index]) return true;
  
	return false;
} // end is_sidebar_active

?>