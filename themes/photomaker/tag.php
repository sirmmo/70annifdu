<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
 * 
 */
?>
<?php get_header(); ?>  
<div class="page-container">
    <div class="container_24">
        <div class="grid_24">
            <div class="page-content">
                <div class="grid_20 alpha">
                    <div class="content-bar gallery">
                        <h1><?php
                            printf(TAG_ARCHIVES, '' . single_cat_title('', false) . '');
                            ?></h1>
                        <?php
                        get_template_part('loop', 'index');
                        ?>
                        <div class="clear"></div>
                        <?php inkthemes_pagination(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
</div>
</div>
</div>
<div class="clear"></div>
</div>
<?php get_footer(); ?>