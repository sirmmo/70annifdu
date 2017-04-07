<?php
/*
Template Name: Full Width(No Sidebar)
*/

get_header(); ?>

    <div id="content" class="site-content container">
		<main id="main" class="site-main" role="main">

            <div class="blog-post">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					 //If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>

			<?php endwhile; // end of the loop. ?>

		   </div><!-- blog-post -->
            <div class="clear"></div>
        </main><!-- main -->
        	</div><!-- #content -->

<?php get_footer(); ?>