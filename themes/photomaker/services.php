<?php
/*
  Template Name: Services Page
 */
?>
<?php get_header(); ?>
<div class="page-container">
    <div class="container_24">
        <div class="grid_24">
            <div class="page-content">
                <div class="grid_20 alpha">
                    <div class="content-bar gallery services"> 
                        <h1 class="page_title"><?php the_title(); ?></h1>
                        <?php if (have_posts()) : the_post(); ?>
                            <?php the_content(); ?>
                            <?php
                        endif;
                        wp_reset_query()
                        ?>
                        <?php
                        $limit = get_option('posts_per_page');
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        query_posts('showposts=' . $limit . '&paged=' . $paged);
                        $wp_query->is_archive = true;
                        $wp_query->is_home = false;
                        ?>
                        <!--Start Post-->
                        <!-- Start the Loop. -->
                        <div class="service_content">	
                            <?php
                            global $post;
                            if (have_posts()) : while (have_posts()) : the_post();
                                    ?>
                                    <!--Start post-->
                                    <div class="post">	
                                        <?php $format = get_post_format($post->ID); ?>			  
                                        <div class="post_content">
                                            <?php if ($format == 'image') { ?>
                                                <div class="post_thumbnil">
                                                    <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                                        <?php
                                                        inkthemes_get_thumbnail(310, 220);
                                                        ?>		 
                                                        <a rel='prettyPhoto[gallery2]' href="<?php
                                                        echo wp_get_attachment_url(get_post_thumbnail_id($post->ID, 'thumbnail'));
                                                        ?>"><span class="image_link"></span></a>	
                                                           <?php
                                                       } else {
                                                           //This is required to set to Null
                                                           $img_source = '';
                                                           $permalink = get_permalink($id);
                                                           ob_start();
                                                           ob_end_clean();
                                                           $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
                                                           if (isset($matches [1] [0])) {
                                                               $img_source = $matches [1] [0];
                                                           }
                                                           ?>
                                                           <?php
                                                           inkthemes_get_image(310, 220);
                                                           ?> 		 
                                                        <a rel="prettyPhoto[gallery2]" href='<?php echo $img_source ?>'><span class="image_link"></span></a>	
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="post_thumbnil">
                                                    <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                                        <?php
                                                        inkthemes_get_thumbnail(310, 220);
                                                        ?>
                                                    <?php } else { ?>
                                                        <?php
                                                        inkthemes_get_image(310, 220);
                                                        ?> 				
                                                    <?php } ?>
                                                    <?php if ($format == 'video') { ?>
                                                        <a href="<?php the_permalink() ?>"><span class="image_link2 video"></span></a>
                                                    <?php } elseif ($format == 'gallery') { ?>
                                                        <a href="<?php the_permalink() ?>"><span class="image_link2 gallery"></span></a>
                                                    <?php } elseif ($format == 'quote') { ?>
                                                        <a href="<?php the_permalink() ?>"><span class="image_link2 quote"></span></a>
                                                    <?php } else { ?>
                                                        <a href="<?php the_permalink() ?>"><span class="image_link2"></span></a>
                                                    <?php } ?>
                                                </div>	
                                            <?php } ?>
                                            <h1 class="post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> </h1>
                                            <?php echo inkthemes_trim_excerpt(20); ?>               
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <?php
                                endwhile;
                            else:
                                ?>
                                <div class="post">
                                    <p>
                                        <?php echo SORRY_NO_POSTS_MATCHED_YOUR_CRITERIA; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <div class="clear"></div>
                            <!--End post-->  
                            <div class="clear"></div>
                            <?php inkthemes_pagination(); ?> 
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php get_footer(); ?>