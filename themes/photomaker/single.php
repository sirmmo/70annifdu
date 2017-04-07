<?php
/**
 * The Template for displaying all single posts.
 *
 */
?>
<?php get_header(); ?>
<div class="page-container">
    <div class="container_24">
        <div class="grid_24">
            <div class="page-content">
                <div class="grid_20 alpha"> 
                    <div class="content-bar">  
                        <!-- Start the Loop. -->
                        <?php
                        global $post;
                        if (have_posts()) : while (have_posts()) : the_post();
                                ?>
                                <!--Start post-->
                                <div class="post single">			
                                    <h1 class="post_title"><?php the_title(); ?></h1>  
                                    <ul class="post_meta">
                                        <li class="post_comment"><?php
                                            comments_popup_link('No Comments.', '1 Comment.', '% Comments.');
                                            ?></li>
                                        <li class="posted_on"><span></span><?php echo get_the_time('M, d, Y') ?></li>
                                        <li class="posted_by"><span></span><?php the_author_posts_link(); ?></li>
                                        <li class="posted_in"><span></span><?php the_category(', '); ?></li>
                                    </ul>
                                    <div class="post_content">
                                        <?php
                                        the_content();
                                        $Video_embed = get_post_meta($post->ID, 'video_url', true);

                                        echo $Video_embed;
                                        ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <?php
                            endwhile;
                        else:
                            ?>
                            <div class="post">
                                <p>
                                    <?php echo SORRY_NO_POST_MATCHED; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <div class="clear"></div>
                        <nav id="nav-single"> <span class="nav-previous">
                                <?php previous_post_link('&laquo; %link'); ?>
                            </span> <span class="nav-next">
                                <?php next_post_link('%link &raquo;'); ?>
                            </span> </nav>
                        <div class="clear"></div>
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="clear"></div><div class="page-link"><span>' . PAGES_COLON . '</span>',
                            'after' => '</div>'));
                        ?>
                        <!--Start Comment box-->
                        <?php comments_template(); ?>
                        <!--End Comment box-->
                        <!--End post-->
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php get_footer(); ?>