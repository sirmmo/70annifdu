<?php
/**
 * The template for displaying front page pages.
 *
 * */
?>
<?php get_header(); ?>
<div class="page-container">
    <div class="container_24">
        <div class="grid_24">
            <div class="page-content">
                <div class="grid_20 alpha">
                    <div class="content-bar gallery"> 
                        <!--Start Post-->
                        <?php get_template_part('loop', 'blog');
                        ?>   
                        <div class="clear"></div>
                        <div class="navigation">
                            <div class="alignleft"><?php previous_posts_link('&laquo; Previous') ?></div>
                            <div class="alignright"><?php next_posts_link('Next &raquo;', '')
                        ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php get_footer(); ?>