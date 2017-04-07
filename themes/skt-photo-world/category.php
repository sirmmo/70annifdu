<?php
/**
 * The template for displaying all category pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package SKT Photo World
 */

get_header(); ?>

	<div id="primary" class="content-area">
      <div id="content" class="site-content container">
		<h2 id="page-title"><?php single_cat_title(); ?></h2>
			<main id="main" class="site-main" role="main">
            		
						
				<?php while ( have_posts() ) : the_post(); ?>
	
					<?php get_template_part( 'content', 'home' ); ?>
	
					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
					?>
	
				<?php endwhile; // end of the loop. ?>
				
				<?php skt_photo_world_pagination(); ?>
               
			</main><!-- #main -->
            </div><!-- #content -->
        </div><!-- #primary -->
        
        <div id="secondary" class="widget-area <?php if( is_front_page() || is_home()  ){ echo 'home_front_wrap'; } ?>" role="complementary">
        <?php get_sidebar(); ?>
       </div><!-- secondary --><div class="clear"></div>
<?php get_footer(); ?>