<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <title><?php
            wp_title('|', true, 'right');
            ?></title>		
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" /> 
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>		
        <div class="header_container">
            <div class="container_24">
                <div class="grid_24">
                    <div class="header ">
                        <div class="grid_20 alpha"> 
                            <div class="logo not_home">
                                <?php
                                $header_image = get_header_image();
                                if (!empty($header_image)):
                                    ?>
                                    <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display'));
                                    ?>" rel="home" class="header-image-link">
                                        <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
                                    </a>
                                <?php else: ?>
                                    <h1 class="site-title"><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('name'); ?></a></h1>
                                    <p class="site-description" ><?php bloginfo('description'); ?></p>
                                <?php endif; ?>
                            </div>   

                        </div> 
                    </div>   
                </div>
                <div class="clear"></div> 
            </div>
        </div>