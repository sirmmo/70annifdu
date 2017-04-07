<?php
/**
 * SKT Full Width functions and definitions
 *
 * @package SKT Full Width
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
function skt_full_width_string_limit_words($string, $word_limit){
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}

if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'skt_full_width_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function skt_full_width_setup() {
	load_theme_textdomain( 'skt-full-width', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_image_size('homepage-thumb',240,145,true);
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'skt-full-width' ),
	) );

	add_theme_support( 'custom-background', array(
		'default-color' => 'E6E1C4',
		'default-image' => get_template_directory_uri().'/images/banner_bg.jpg',
	) );
	add_editor_style( 'editor-style.css' );
}
endif; // skt_full_width_setup
add_action( 'after_setup_theme', 'skt_full_width_setup' );


function skt_full_width_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'skt-full-width' ),
		'description'   => __( 'Appears on blog page sidebar', 'skt-full-width' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	
}
add_action( 'widgets_init', 'skt_full_width_widgets_init' );

define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
require_once dirname( __FILE__ ) . '/inc/options-framework.php';

// Loads options.php from child or parent theme
$optionsfile = locate_template( 'options.php' );
load_template( $optionsfile );


function skt_full_width_scripts() {
	wp_enqueue_style( 'skt_full_width-gfonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' );
	wp_enqueue_style( 'skt_full_width-fonts', '//fonts.googleapis.com/css?family=Roboto:400,300,700');
	wp_enqueue_style( 'skt_full_width-basic-style', get_stylesheet_uri() );
	if ( (function_exists( 'of_get_option' )) && (of_get_option('sidebar-layout', true) != 1) ) {
		if (of_get_option('sidebar-layout', true) ==  'right') {
			wp_enqueue_style( 'skt_full_width-layout', get_template_directory_uri()."/css/layouts/content-sidebar.css" );
		}
		else {
			wp_enqueue_style( 'skt_full_width-layout', get_template_directory_uri()."/css/layouts/sidebar-content.css" );
		}	
	}
	else {
		wp_enqueue_style( 'skt_full_width-layout', get_template_directory_uri()."/css/layouts/content-sidebar.css" );
	}	
	
	wp_enqueue_style( 'skt_full_width-editor-style', get_template_directory_uri()."/editor-style.css", array('skt_full_width-layout') );

	wp_enqueue_style( 'skt_full_width-main-style', get_template_directory_uri()."/css/main.css", array('skt_full_width-layout') );
	
	wp_enqueue_style( 'skt_full_width-supersized-default-theme', get_template_directory_uri()."/css/supersized.css" );
	
	wp_enqueue_style( 'skt_full_width-supersized-style', get_template_directory_uri()."/css/supersized.shutter.css" );
	
	wp_enqueue_script( 'skt_full_width-supersized-slider', get_template_directory_uri() . '/js/supersized.3.2.7.min.js', array('jquery') );
	
	//wp_enqueue_script( 'skt_full_width-superfish', get_template_directory_uri() . '/js/superfish.js', array('jquery') );

	wp_enqueue_script( 'skt_full_width-supersized-shutter', get_template_directory_uri() . '/js/supersized.shutter.js', array('jquery') );
	
	wp_enqueue_script( 'skt_full_width-custom_js', get_template_directory_uri() . '/js/custom.js' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'skt_full_width_scripts' );

function skt_full_width_custom_head_codes() {
	if ( (function_exists( 'of_get_option' )) && (of_get_option('headcode1', true) != 1) ) {
		echo esc_html( of_get_option('headcode1', true) );
	}
	if ( (function_exists( 'of_get_option' )) && (of_get_option('style2', true) != 1) ) {
		echo "<style>". esc_html( of_get_option('style2', true) ) ."</style>";
	}
	//Modify CSS a little if Slider is disabled. 
	if ( ( of_get_option('slider_enabled') == 0 ) || ( (is_home() == false) ) )  {
		echo "<style>.main-navigation {	margin-bottom: -5px;}</style>";
	}
	if ( ( of_get_option('slider_enabled') == 0 ) || ( (is_front_page() == true) ) )  {
		echo "<style>.main-navigation {	margin-bottom: 15px;}</style>";
	}
	if ( function_exists( 'of_get_option' )  )  {
		echo "<style>";
		if( of_get_option('navigation_icon', true) != '' ){
			echo "#site-nav ul li a:hover, #site-nav li.current_page_item a{background-image:url(".of_get_option('navigation_icon',true).")}";
		}
		if( of_get_option('navigation_color', true) != '' ){
			echo "#site-nav ul li a:hover, #site-nav li.current_page_item a, div.slide-title a:hover{color:".of_get_option('navigation_color',true).";}mark, ins, a, h2#page-title:first-letter,.archive h1.page-title:first-letter,.page h1.entry-title:first-letter, h2#page-title:first-letter,.archive h1.page-title:first-letter,.page h1.entry-title:first-letter, .single-post h1.entry-title:first-letter, .entry-meta a, .search h1.entry-title:first-letter,.archive h1.entry-title:first-letter, .read-more a:hover, .recent-post .post-box .post-text a, aside ul li a:hover, .widget ul li a:hover, h3.company-title:first-letter, .footer-menu ul li a:hover, .social a:hover, .footer-bottom a{color:".of_get_option('navigation_color',true).";}button, html input[type=\"button\"], input[type=\"reset\"],input[type=\"submit\"]{background-color:".of_get_option('navigation_color',true).";}";
		}
		if( (of_get_option('pagin_grad_top_color',true) != '') && (of_get_option('pagin_grad_bottom_color',true) != '') ){
			echo ".pagination ul  > li  > a, .pagination ul  > li  > span{background:linear-gradient(".of_get_option('pagin_grad_top_color',true).", ".of_get_option('pagin_grad_bottom_color',true).") !important; background:-moz-linear-gradient(".of_get_option('pagin_grad_top_color',true).", ".of_get_option('pagin_grad_bottom_color',true).") !important; background:-webkit-linear-gradient(".of_get_option('pagin_grad_top_color',true).", ".of_get_option('pagin_grad_bottom_color',true).") !important; background:-o-linear-gradient(".of_get_option('pagin_grad_top_color',true).", ".of_get_option('pagin_grad_bottom_color',true).") !important;}.pagination ul  > li:hover > a, .pagination ul  > li > span.current{background:linear-gradient(".of_get_option('pagin_grad_bottom_color',true).", ".of_get_option('pagin_grad_top_color',true).") !important; background:-moz-linear-gradient(".of_get_option('pagin_grad_bottom_color',true).", ".of_get_option('pagin_grad_top_color',true).") !important; background:-webkit-linear-gradient(".of_get_option('pagin_grad_bottom_color',true).", ".of_get_option('pagin_grad_top_color',true).") !important; background:-o-linear-gradient(".of_get_option('pagin_grad_bottom_color',true).", ".of_get_option('pagin_grad_top_color',true).") !important;}";
		}
		echo "</style>";
	}	


	?><script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery.supersized({
			// Functionality
			slideshow               :   1,			// Slideshow on/off
			autoplay				:	1,			// Determines whether slideshow begins playing when page is loaded. 
			start_slide             :   1,			// Start slide (0 is random)
			stop_loop				:	0,			// Pauses slideshow on last slide
			random					: 	0,			// Randomize slide order (Ignores start slide)
			slide_interval          :   5000,		// Length between transitions
			transition              :   <?php if(of_get_option('sliderefect',true) != ''){ echo of_get_option('sliderefect',true);}; ?>,
			transition_speed		:	1000,		// Speed of transition
			new_window				:	1,			// Image links open in new window/tab
			pause_hover             :   0,			// Pause slideshow on hover
			keyboard_nav            :   1,			// Keyboard navigation on/off
			performance				:	1,			// 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
			image_protect			:	0,			// Disables image dragging and right click with Javascript
	
			// Size & Position
			min_width		        :   0,			// Min width allowed (in pixels)
			min_height		        :   0,			// Min height allowed (in pixels)
			vertical_center         :   1,			// Vertically center background
			horizontal_center       :   1,			// Horizontally center background
			fit_always				:	0,			// Image will never exceed browser width or height (Ignores min. dimensions)
			fit_portrait         	:   1,			// Portrait images will not exceed browser height
			fit_landscape			:   0,			// Landscape images will not exceed browser width
	
			// Components 				
			slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
			thumb_links				:	1,			// Individual thumb links for each slide
			thumbnail_navigation    :   0,			// Thumbnail navigation
			slides 					:  	[			// Slideshow Images
											<?php
											if( is_front_page() || is_home() ){
												for ($i=1;$i<6;$i++) {
													if ( of_get_option('slide'.$i, true) != "" ) {
														$imgUrl = esc_url( of_get_option('slide'.$i, true) );
														$imgTitle = esc_html( of_get_option('slidetitle'.$i, true) );
														$imgDesc = esc_html( of_get_option('slidedesc'.$i, true) );
														$imgHref = esc_html( of_get_option('slideurl'.$i, true) );
														if( $imgUrl != '' ){
															echo '{image : \''.$imgUrl.'\', title : \'<div class="slide-title"><span>'.( ($imgHref!='' && $imgTitle!='') ? '<a href="'.$imgHref.'">' : '').$imgTitle.( ($imgHref!='' && $imgTitle!='') ? '</a>' : '').'</span></div><div class="slide-description"><span>'.$imgDesc.'</span></div>'.( ($imgHref != '') ? '<div class="slide-description"><span><a href="'.$imgHref.'">Read More &rsaquo;</a></span></div>' : '').'\', thumb : \''.$imgUrl.'\', url : \'\'},'."\n";
														}
													}
												}
											}else{
												$featured_as_background = esc_html( of_get_option('featured_as_background', true) );
												if( $featured_as_background != 1 && has_post_thumbnail() ) {
													$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
													$imgUrl = $large_image_url[0];
													echo '{image : \''.$imgUrl.'\', title : \'\', thumb : \''.$imgUrl.'\', url : \'\'},'."\n";
												}else{
													$page_bg_image_url = get_background_image();
													echo '{image : \''.$page_bg_image_url.'\', title : \'\', thumb : \''.$page_bg_image_url.'\', url : \'\'},'."\n";
												}
											}
											?>
										],
			// Theme Options 
			progress_bar			:	1,			// Timer for each slide			
			mouse_scrub				:	0
		});
		
		// hide controller if only 1 image exist.
		var cntSlide = jQuery('ul#thumb-list li').length;
		if( cntSlide < 2 ){
			jQuery('#controls-wrapper').css('visibility','hidden');
		}
	});
	
	</script>
	<?php

}	
add_action('wp_head', 'skt_full_width_custom_head_codes');


function skt_full_width_pagination() {
	global $wp_query;
	$big = 12345678;
	$page_format = paginate_links( array(
	    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	    'format' => '?paged=%#%',
	    'current' => max( 1, get_query_var('paged') ),
	    'total' => $wp_query->max_num_pages,
	    'type'  => 'array'
	) );
	if( is_array($page_format) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		echo '<div class="pagination"><div><ul>';
		echo '<li><span>'. $paged . ' of ' . $wp_query->max_num_pages .'</span></li>';
		foreach ( $page_format as $page ) {
			echo "<li>$page</li>";
		}
		echo '</ul></div></div>';
	}
}
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/**
 * Load custom functions file.
 */
require get_template_directory() . '/inc/custom-functions.php';


function skt_full_width_custom_blogpost_pagination( $wp_query ){
	$big = 999999999; // need an unlikely integer
	if ( get_query_var('paged') ) { $pageVar = 'paged'; }
	elseif ( get_query_var('page') ) { $pageVar = 'page'; }
	else { $pageVar = 'paged'; }
	$pagin = paginate_links( array(
		'base' 			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' 		=> '?'.$pageVar.'=%#%',
		'current' 		=> max( 1, get_query_var($pageVar) ),
		'total' 		=> $wp_query->max_num_pages,
		'prev_text'		=> '&laquo; Prev',
		'next_text' 	=> 'Next &raquo;',
		'type'  => 'array'
	) ); 
	if( is_array($pagin) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		echo '<div class="pagination"><div><ul>';
		echo '<li><span>'. $paged . ' of ' . $wp_query->max_num_pages .'</span></li>';
		foreach ( $pagin as $page ) {
			echo "<li>$page</li>";
		}
		echo '</ul></div></div>';
	} 
}


function skt_full_width_get_slug_by_id($id) {
	$post_data = get_post($id, ARRAY_A);
	$slug = $post_data['post_name'];
	return $slug; 
}
