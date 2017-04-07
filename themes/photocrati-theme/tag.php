<?php get_header(); ?>

<div id="container">
         
    <?php 
  	if (Photocrati_Style_Manager::is_sidebar_enabled() && Photocrati_Style_Manager::get_active_preset()->sidebar_alignment_responsive == 'top') {
  		get_sidebar(); 
  	}
    ?>    
    
    <div id="content-sm"><!-- Important!! If you remove the sidebar change the ID of this DIV to content -->

        <?php the_post(); ?>

        <h1 class="page-title"><?php _e( 'Tag Archives:', 'photocrati-framework' ) ?> <span><?php single_tag_title() ?></span></h1>

        <?php rewind_posts(); ?>

        <?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
            <div id="nav-above" class="navigation">
                <div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> Older posts', 'photocrati-framework' )) ?></div>
                <div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&raquo;</span>', 'photocrati-framework' )) ?></div>
            </div><!-- #nav-above -->
        <?php } ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'photocrati-framework'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

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

                <div class="entry-summary">
                    <?php the_content( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'photocrati-framework' )  ); ?>
                </div><!-- .entry-summary -->

                <div class="entry-utility">
                    <span class="cat-links"><span class="entry-utility-prep entry-utility-prep-cat-links"><?php _e( 'Posted in ', 'photocrati-framework' ); ?></span><?php echo get_the_category_list(', '); ?></span>
                    <span class="meta-sep"> | </span>
                    <?php if ( $tag_ur_it = tag_ur_it(', ') ) : // Returns tags other than the one queried ?>
                        <span class="tag-links"><?php printf( __( 'Also tagged %s', 'photocrati-framework' ), $tag_ur_it ) ?></span>
                    <?php endif; ?>
                    <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'photocrati-framework' ), __( '1 Comment', 'photocrati-framework' ), __( '% Comments', 'photocrati-framework' ) ) ?></span>
                    <?php edit_post_link( __( 'Edit', 'photocrati-framework' ), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t\n" ) ?>
                </div><!-- #entry-utility -->
            </div><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; ?>

        <?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
            <div id="nav-below" class="navigation">
                <div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> Older posts', 'photocrati-framework' )) ?></div>
                <div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&raquo;</span>', 'photocrati-framework' )) ?></div>
            </div><!-- #nav-below -->
        <?php } ?>

    </div><!-- #content -->

         
    <?php 
  	if (Photocrati_Style_Manager::is_sidebar_enabled() && Photocrati_Style_Manager::get_active_preset()->sidebar_alignment_responsive == 'bottom') {
  		get_sidebar(); 
  	}
    ?>    

<?php get_footer(); ?>
