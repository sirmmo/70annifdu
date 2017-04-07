<!-- Start the Loop. -->
<?php
global $post;
if (have_posts()) : while (have_posts()) : the_post();
        ?>
        <!--Start post-->
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>			
            <h1 class="post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> </h1>
            <?php $format = get_post_format($post->ID);
            ?>			  
            <div class="post_content">
                <?php
                $Video_embed = get_post_meta($post->ID, 'video_url', true);
                if ($format == 'video') {
                    echo $Video_embed;
                } elseif ($format == 'gallery') {
                    $galleries_images = get_post_galleries_images($post);
                    ?>
                    <?php if ($galleries_images) { ?>
                        <div class="flexslider">                            
                            <ul class="slides">
                                <?php
                                $length = sizeof($galleries_images);
                                for ($i = 0; $i < $length; $i++) {
                                    $length_g = sizeof($galleries_images[$i]);
                                    //$image_link = wp_get_attachment_url( $attachment_id );
                                    for ($j = 0; $j < $length_g; $j++) {
                                        ?>
                                        <li><a href="<?php the_permalink() ?>"><img src="<?php echo $galleries_images[$i][$j]; ?>" /></a></li>
                                        <?php
                                    }
                                }
                                ?>             
                            </ul>                            
                        </div>
                        <?php
                    }
                } elseif ($format == 'image') {
                    ?>
                    <div class="post_thumbnil">
                        <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                            <?php
                            inkthemes_get_thumbnail(1000, 385);
                            ?>
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
                               inkthemes_get_image(1000, 385);
                               ?> 	
                        <?php } ?>
                    </div>
                <?php } elseif ($format == 'quote') { ?>
                    <div class="post_thumbnil">
                        <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                            <?php
                            inkthemes_get_thumbnail(1000, 380);
                            ?>
                        <?php } else { ?>
                            <?php
                            inkthemes_get_image(1000, 380);
                            ?>				
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="post_thumbnil">
                        <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                            <?php
                            inkthemes_get_thumbnail(1000, 380);
                            ?>
                        <?php } else { ?>
                            <?php
                            inkthemes_get_image(1000, 380);
                            ?>				
                        <?php } ?>
                    </div>
                <?php } ?>
                <ul class="post_meta">
                    <li class="post_comment"><?php
                        comments_popup_link(NO_CMNT, ONE_CMNT, '% ' . CMNT);
                        ?></li>
                    <li class="posted_on"><span></span><?php echo get_the_time('M, d, Y') ?></li>
                    <li class="posted_by"><span></span><?php the_author_posts_link(); ?></li>
                    <li class="posted_in"><span></span><?php the_category(', '); ?></li>
                </ul>
                <?php the_excerpt(); ?>               
            </div>
            <a class="read_more" href="<?php the_permalink() ?>"><?php echo READ_MORE; ?><span></span></a>
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