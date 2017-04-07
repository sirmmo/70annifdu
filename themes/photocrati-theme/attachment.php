<?php get_header(); ?>

    <div id="container">
         
    <?php 
  	if (Photocrati_Style_Manager::is_sidebar_enabled() && Photocrati_Style_Manager::get_active_preset()->sidebar_alignment_responsive == 'top') {
  		get_sidebar(); 
  	}
    ?>    
    
        <div id="content-sm"><!-- Important!! If you remove the sidebar change the ID of this DIV to content -->

            <?php the_post(); ?>

            <h1 class="page-title"><a href="<?php echo get_permalink($post->post_parent) ?>" title="<?php printf( __( 'Return to %s', 'photocrati-framework' ), wp_specialchars( get_the_title($post->post_parent), 1 ) ) ?>" rev="attachment"><span class="meta-nav">&laquo; </span><?php echo get_the_title($post->post_parent) ?></a></h1>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h2 class="entry-title"><?php the_title(); ?></h2>

                <div class="entry-meta">
                    <span class="meta-prep meta-prep-author"><?php _e('By ', 'photocrati-framework'); ?></span>
                    <span class="author vcard"><a class="url fn n" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename ); ?>" title="<?php printf( __( 'View all posts by %s', 'photocrati-framework' ), $authordata->display_name ); ?>"><?php the_author(); ?></a></span>
                    <span class="meta-sep"> | </span>
                    <span class="meta-prep meta-prep-entry-date"><?php _e('Published ', 'photocrati-framework'); ?></span>
                    <div class="entry-date">
                        <div class="month m-<?php the_time('m') ?>"><?php the_time('M') ?></div>
                        <div class="day d-<?php the_time('d') ?>"><?php the_time('d') ?></div>
                        <div class="year y-<?php the_time('Y') ?>"><?php the_time('Y') ?></div></div>
                    <?php edit_post_link( __( 'Edit', 'photocrati-framework' ), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t" ) ?>
                </div><!-- .entry-meta -->

                <div class="entry-content">
                    <div class="entry-attachment">
                        <?php if ( wp_attachment_is_image( $post->id ) ) : $att_image = wp_get_attachment_image_src( $post->id, "medium"); ?>
                            <p class="attachment"><a href="<?php echo wp_get_attachment_url($post->id); ?>" title="<?php the_title(); ?>" rel="attachment"><img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>"  class="attachment-medium" alt="<?php $post->post_excerpt; ?>" /></a>
                            </p>
                        <?php else : ?>
                            <a href="<?php echo wp_get_attachment_url($post->ID) ?>" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ) ?>" rel="attachment"><?php echo basename($post->guid) ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="entry-caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt() ?></div>


                    <?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'photocrati-framework' )  ); ?>
                    <?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'photocrati-framework' ) . '&after=</div>') ?>

                </div><!-- .entry-content -->

                <div class="entry-utility">
                    <?php printf( __( 'This entry was posted in %1$s%2$s.', 'photocrati-framework' ),
                        get_the_category_list(', '),
                        get_the_tag_list( __( ' and tagged ', 'photocrati-framework' ), ', ', '' ),
                        get_permalink(),
                        the_title_attribute('echo=0'),
                        comments_rss() ) ?>

                    <?php edit_post_link( __( 'Edit', 'photocrati-framework' ), "\n\t\t\t\t\t<span class=\"edit-link\">", "</span>" ) ?>
                </div><!-- .entry-utility -->
            </div><!-- #post-<?php the_ID(); ?> -->
            <?php comments_template(); ?>

        </div><!-- #content -->

         
    <?php 
  	if (Photocrati_Style_Manager::is_sidebar_enabled() && Photocrati_Style_Manager::get_active_preset()->sidebar_alignment_responsive == 'bottom') {
  		get_sidebar(); 
  	}
    ?>    

    </div><!-- #container -->

<?php get_footer(); ?>
