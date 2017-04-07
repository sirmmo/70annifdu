<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 */
?>
<?php get_header(); ?>  
<div class="page-container">
    <div class="container_24">
        <div class="grid_24">
            <div class="page-content">
                <div class="grid_20 alpha">
                    <div class="content-bar gallery">
                        <?php if (have_posts()) : ?>
                            <h1><?php
                                printf(CATEGORY_ARCHIVES, '' . single_cat_title('', false) . '');
                                ?></h1>
                            <?php
                            $category_description = category_description();
                            if (!empty($category_description))
                                echo '' . $category_description . '';
                            /* Run the loop for the category page to output the posts.
                             * If you want to overload this in a child theme then include a file
                             * called loop-category.php and that will be used instead.
                             */
                            ?>
                            <?php get_template_part('loop', 'category');
                            ?>
                            <div class="clear"></div>
                            <?php inkthemes_pagination(); ?>
                        <?php endif; ?>
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