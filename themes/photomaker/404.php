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
                    <div class="content-bar">
                        <?php the_content(); ?>	
                        <header class="entry-header">
                            <h2 class="error_pera">
                                <?php echo IT_SEEMS_WE; ?>
                            </h2>            
                            <div class="error_list">
                                <?php
                                the_widget('WP_Widget_Recent_Posts', array(
                                    'number' => 20), array(
                                    'widget_id' => '404'));
                                ?>
                                <div class="widget">
                                    <h2 class="widgettitle">
                                        <?php echo MOST_USED_CATEGORIES; ?>
                                    </h2>
                                    <ul class="error_list">
                                        <?php
                                        wp_list_categories(array(
                                            'orderby' => 'count',
                                            'order' => 'DESC',
                                            'show_count' => 1,
                                            'title_li' => '',
                                            'number' => 20));
                                        ?>
                                    </ul>
                                </div>
                                <?php
                                /* translators: %1$s: smilie */
                                $archive_content = '<p>' . sprintf(MONTHLY_ARCHIVES, convert_smilies(':)')) . '</p>';
                                the_widget('WP_Widget_Archives', array(
                                    'count' => 0), array(
                                    'after_title' => '</h2>' . $archive_content));
                                ?>
                            </div>
                            <?php get_search_form(); ?>
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