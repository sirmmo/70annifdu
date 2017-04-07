<?php get_header(); ?>
<div id="primary" class="content-area">
    <div id="content" class="site-content container">
		<main id="main" class="site-main" role="main">

            <div class="blog-post">
			<?php woocommerce_content(); ?>
		  </div><!-- blog-post -->
            <div class="clear"></div>
        </main><!-- main -->
        	</div><!-- #content -->
        </div><!-- #primary -->
        
        <div id="secondary" class="widget-area" role="complementary">
        <?php get_sidebar(); ?>
       </div><!-- secondary --><div class="clear"></div>
<?php get_footer(); ?>