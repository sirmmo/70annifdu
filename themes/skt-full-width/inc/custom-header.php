<?php
/**
 * @package SKT Full Width
 * Setup the WordPress core custom header feature.
 *
 * @uses skt_full_width_header_style()
 * @uses skt_full_width_admin_header_style()
 * @uses skt_full_width_admin_header_image()

 */
function skt_full_width_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'skt_full_width_custom_header_args', array(
		//'default-image'          => get_template_directory_uri().'/images/banner_bg.jpg',
		'default-text-color'     => 'fff',
		'width'                  => 1600,
		'height'                 => 400,
		'wp-head-callback'       => 'skt_full_width_header_style',
		'admin-head-callback'    => 'skt_full_width_admin_header_style',
		'admin-preview-callback' => 'skt_full_width_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'skt_full_width_custom_header_setup' );

if ( ! function_exists( 'skt_full_width_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see skt_full_width_custom_header_setup().
 */
function skt_full_width_header_style() {
	$header_text_color = get_header_textcolor();
	?>
	<style type="text/css">
	<?php
		//Check if user has defined any header image.
		if ( get_header_image() ) :
	?>
		#header-image {
			background: url(<?php echo get_header_image(); ?>) no-repeat #111;
			background-position: center top;
		}
	<?php endif; ?>	
	</style>
	<?php
}
endif; // skt_full_width_header_style

if ( ! function_exists( 'skt_full_width_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see skt_full_width_custom_header_setup().
 */
function skt_full_width_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			border: none;
		}
			</style>
<?php
}
endif; // skt_full_width_admin_header_style

if ( ! function_exists( 'skt_full_width_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see skt_full_width_custom_header_setup().
 */
function skt_full_width_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
<?php
}
endif; // skt_full_width_admin_header_image 


define('SKT_URL','http://www.sktthemes.net');
define('SKT_THEME_URL','http://www.sktthemes.net/themes');