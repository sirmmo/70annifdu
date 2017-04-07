<?php
/**
 * The Template for displaying all single posts.
 *
 * @package SKT Photo World
 */

get_header(); ?>

	<div id="primary" class="content-area">
    	 <div id="content" class="site-content container">
		<main id="main" class="site-main" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php //skt_photo_world_posted_on(); ?>
	</header><!-- .entry-header -->
    </article>
			<div class="blog-post">
		<?php while ( have_posts() ) : the_post(); ?>
        

			<?php get_template_part( 'content', 'single' ); ?>

			<?php skt_photo_world_content_nav( 'nav-below' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; // end of the loop. ?>
			</div><!-- blog-post -->
            <div class="clear"></div>
		</main><!-- #main -->
        </div><!-- #content -->
        </div><!-- #primary -->
        
        <div id="secondary" class="widget-area <?php if( is_front_page() || is_home()  ){ echo 'home_front_wrap'; } ?>" role="complementary">
        <?php get_sidebar(); ?>
       </div><!-- secondary --><div class="clear"></div>
	


<?php //get_sidebar('footer'); ?>
<?php get_footer(); ?>