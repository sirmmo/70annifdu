<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package SKT Photo World
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php /*<meta name="viewport" content="width=device-width, initial-scale=1">*/ ?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php 
$slAr = array();
for ($i=1;$i<10;$i++) {
	if ( of_get_option('slide'.$i, true) != "" ) {
		$imgUrl = of_get_option('slide'.$i, true);
		if ( strlen($imgUrl) > 3 ) $slAr[] = of_get_option('slide'.$i, true);
	}
}
?>
<style type="text/css">
		ul#demo-block{ margin:0 15px 15px 15px; }
			ul#demo-block li{ margin:0 0 10px 0; padding:10px; display:inline; float:left; clear:both; color:#aaa; background:url(<?php get_template_directory_uri(); ?>/images/img/bg-black.png); font:11px Helvetica, Arial, sans-serif; }
			ul#demo-block li a{ color:#eee; font-weight:bold; }
	</style>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
$front_page = get_option('page_on_front');
$post_page = get_option('page_for_posts');
?>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	
		<?php if( (is_front_page() || is_home() ) && ($front_page == 0 && $post_page == 0) ){ ?>
		<?php if( count($slAr) > 0 ){ ?>
            <div class="slider-parent">	
                <div class="slider-wrapper theme-default container <?php if( is_front_page() || is_home()  ){ echo 'home_front_wrap_main'; } ?>"> 
                
                   <!--Thumbnail Navigation-->
	<div id="prevthumb"></div>
	<div id="nextthumb"></div>
	
	<!--Arrow Navigation-->
	<a id="prevslide" class="load-item"></a>
	<a id="nextslide" class="load-item"></a>
	
    
	<div id="thumb-tray" class="load-item">
		<div id="thumb-back"></div>
		<div id="thumb-forward"></div>
	</div>

                </div><!--.container-->	
            </div><!--.slider-parent-->
        <?php } //if( count($slAr) > 0 ){ ?>
	<?php } ?>


    <div id="wrapper">
       
            <div class="header">
            		
                <div class="logo">
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    	<?php if( true == of_get_option('logo') ) { ?>
	                    	<img src="<?php echo esc_url( of_get_option('logo', true) ); ?>" />
                        <?php } else { ?>
							<?php bloginfo( 'name' ); ?>
                        <?php } ?>
                    </a></h1>
                    <?php if(of_get_option('logo', true) != '') { ?>
                    <p class="site-description" style="display:none;"><?php bloginfo( 'description' ); ?></p>
                    <?php } else { ?>
                    <p class="site-description"><?php bloginfo( 'description' ); ?></p>
                    <?php }?>
                </div>
              
                <div id="site-nav">
                    <div class="mobile_nav"><?php _e('Menu...','skt-photo-world'); ?></div>
                     <nav id="nav">
                       <?php wp_nav_menu( array('theme_location'  => 'primary' ) ); ?>
                     </nav>
                </div><!-- site-nav --><div class="clear"></div>
            </div><!-- header -->
       
          