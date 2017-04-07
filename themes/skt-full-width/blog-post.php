<?php
/* Template Name: Blog */
get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content container">
        <main id="main" class="site-main" role="main">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	            <header><h1 class="entry-title"><?php the_title(); ?></h1></header>
            <?php endwhile; else: endif; ?>
            <div class="blog-post">
				<?php 
				if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
				elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
				else { $paged = 1; }
				$query = new WP_Query( array( 'paged' => $paged, 'posts_per_page' => 5 ) ); ?>
                <?php if( $query->have_posts() ) : ?>
					<?php while( $query->have_posts() ) : $query->the_post(); ?>
	                	<?php get_template_part( 'content', get_post_format() ); ?>
	                <?php endwhile; ?>
					<?php skt_full_width_custom_blogpost_pagination( $query ); ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
	                <?php get_template_part( 'no-results', 'index' ); ?>
                <?php endif; ?>
            </div><!-- blog-post -->
			<?php get_sidebar(); ?>
            <div class="clear"></div>
        </main><!-- main -->

<?php get_footer(); ?>