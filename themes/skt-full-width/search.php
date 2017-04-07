<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package SKT Full Width
 */

get_header(); ?>

	<div id="primary" class="content-area">
    	 <div id="content" class="site-content container">
		<main id="main" class="site-main" role="main">
			
		<?php if ( have_posts() ) : ?>

			<header>
				<h1 class="entry-title"><?php printf( __( 'Search Results for: %s', 'skt-full-width' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>
<div class="blog-post">
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'search' ); ?>

			<?php endwhile; ?>

			<?php skt_full_width_pagination(); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>

		</div><!-- blog-post --><?php get_sidebar(); ?><div class="clear"></div>
        </main><!-- #main -->
	

<?php get_footer(); ?>