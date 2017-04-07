<?php
/**
 * The template for displaying attachments.
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
                        <div class="fullwidth">
                            <p>
                                <a href="<?php echo get_permalink($post->post_parent); ?>" title="<?php esc_attr(printf(RETURN_TO, get_the_title($post->post_parent)));
?>" rel="gallery">
                                       <?php
                                       /* translators: %s - title of parent post */
                                       printf(__('<span>&larr;</span> %s', 'photomaker'), get_the_title($post->post_parent));
                                       ?>
                                </a>
                            </p>
                            <?php
                            printf(BY_AUTHOR, 'meta-prep meta-prep-author', sprintf('<a class="url fn n" href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url(get_the_author_meta('ID')), sprintf(esc_attr__(VIEW_ALL_POST_BY), get_the_author()), get_the_author()
                            ));
                            ?>
                            <span>|</span>
                            <?php
                            printf(PUBLISHED_BY, 'meta-prep meta-prep-entry-date', sprintf('<abbr title="%1$s">%2$s</abbr>', esc_attr(get_the_time()), get_the_date()
                            ));
                            if (wp_attachment_is_image()) {
                                echo ' | ';
                                $metadata = wp_get_attachment_metadata();
                                printf(FULL_SIZE_IS_PIXEL, sprintf('<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>', wp_get_attachment_url(), esc_attr(LINK_TO_FULL_SIZE_IMAGE), $metadata['width'], $metadata['height']));
                            }
                            ?>
                            <?php
                            edit_post_link(EDIT_TEXT, '', '');
                            ?>
                            <!-- .entry-meta -->
                            <?php
                            if (wp_attachment_is_image()) {
                                $attachments = array_values(get_children(array(
                                    'post_parent' => $post->post_parent,
                                    'post_status' => 'inherit',
                                    'post_type' => 'attachment',
                                    'post_mime_type' => 'image',
                                    'order' => 'ASC',
                                    'orderby' => 'menu_order ID')));
                                foreach ($attachments as $k => $attachment) {
                                    if ($attachment->ID == $post->ID) {
                                        break;
                                    }
                                }
                                $k++;
                                // If there is more than 1 image attachment in a gallery
                                if (count($attachments) > 1) {
                                    if (isset($attachments[$k])) { // get the URL of the next image attachment
                                        $next_attachment_url = get_attachment_link($attachments[$k]->ID);
                                    } else { // or get the URL of the first image attachment
                                        $next_attachment_url = get_attachment_link($attachments[0]->ID);
                                    }
                                } else {
                                    // or, if there's only 1 image attachment, get the URL of the image
                                    $next_attachment_url = wp_get_attachment_url();
                                }
                                ?>
                                <p><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr(get_the_title()); ?>" rel="attachment">
                                        <?php
                                        $attachment_size = apply_filters('twentyten_attachment_size', 1140);
                                        echo wp_get_attachment_image($post->ID, array(
                                            $attachment_size,
                                            9999)); // filterable image width with, essentially, no limit for image height.
                                        ?>
                                    </a></p>

                                <nav id="nav-single">
                                    <span class="nav-previous"><?php previous_image_link(); ?></span>
                                    <span class="nav-next"><?php next_image_link(); ?></span>
                                </nav><!-- #nav-single -->

                            <?php } else {
                                ?>
                                <a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr(get_the_title()); ?>" rel="attachment"><?php echo basename(get_permalink()); ?></a>
                            <?php } ?>
                            <?php
                            if (!empty($post->post_excerpt)) {
                                the_excerpt();
                            }
                            ?>
                            <?php the_content(CONTINUE_READING); ?>
                            <?php
                            wp_link_pages(array(
                                'before' => '' . PAGES_COLON,
                                'after' => ''));
                            ?>
                            <?php inkthemes_posted_in(); ?>
                            <?php
                            edit_post_link(EDIT, ' ', '');
                            ?>
                            <?php comments_template(); ?>
                        </div>  
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