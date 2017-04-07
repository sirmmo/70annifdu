<?php
    $preset             = Photocrati_Style_Manager::get_active_preset();
    $blog_meta          = $preset->blog_meta;
    $display_sidebar    = $preset->is_sidebar_enabled;
?>

<?php get_header(); ?>

<div id="container">
         
    <?php 
  	if (Photocrati_Style_Manager::is_sidebar_enabled() && Photocrati_Style_Manager::get_active_preset()->sidebar_alignment_responsive == 'top') {
  		get_sidebar(); 
  	}
    ?>    
    
    <div id="content-sm"><!-- Important!! If you remove the sidebar change the ID of this DIV to content -->

        <?php the_post(); ?>

        <div id="nav-above" class="navigation">
            <div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ) ?></div>
            <div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ) ?></div>
        </div><!-- #nav-above -->

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h1 class="entry-title"><?php the_title(); ?></h1>

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
                <?php the_content(); ?>
                <?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'photocrati-framework' ) . '&after=</div>') ?>
            </div><!-- .entry-content -->

            <div class="entry-utility"<?php if(!$blog_meta) { echo ' style="display:none;"'; } ?>>
                <?php printf( __( 'This entry was posted in %1$s%2$s.', 'photocrati-framework' ),
                    get_the_category_list(', '),
                    get_the_tag_list( __( ' and tagged ', 'photocrati-framework' ), ', ', '' ),
                    get_permalink(),
                    the_title_attribute('echo=0'),
                    comments_rss() ) ?>

                <?php edit_post_link( __( 'Edit', 'photocrati-framework' ), "\n\t\t\t\t\t<span class=\"edit-link\">", "</span>" ) ?>

            </div><!-- .entry-utility -->
        </div><!-- #post-<?php the_ID(); ?> -->

        <div id="nav-below" class="navigation">
            <div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ) ?></div>
            <div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ) ?></div>
        </div><!-- #nav-below -->

        <?php if(get_post_meta($post->ID, 'music', true) == "YES") { ?>

            <div id="jquery_jplayer"></div>

            <div class="jp-single-player">
                <div class="jp-interface">
                    <ul class="jp-controls">
                        <li><a href="#" id="jplayer_play" class="jp-play" tabindex="1">play</a></li>
                        <li><a href="#" id="jplayer_pause" class="jp-pause" tabindex="1">pause</a></li>
                        <li><a href="#" id="jplayer_stop" class="jp-stop" tabindex="1">stop</a></li>
                    </ul>
                    <div class="jp-progress">
                        <div id="jplayer_load_bar" class="jp-load-bar">
                            <div id="jplayer_play_bar" class="jp-play-bar"></div>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php comments_template('', true); ?>
    </div><!-- #content -->

         
    <?php 
  	if (Photocrati_Style_Manager::is_sidebar_enabled() && Photocrati_Style_Manager::get_active_preset()->sidebar_alignment_responsive == 'bottom') {
  		get_sidebar(); 
  	}
    ?>    

<?php get_footer(); ?>
