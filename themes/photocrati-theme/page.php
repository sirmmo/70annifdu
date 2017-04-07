<?php get_header(); ?>
<?php $page_comments = Photocrati_Style_Manager::get_active_preset()->page_comments ?>
		<div id="container">	
			<div id="content"> <!-- To use the sidebar on pages change the id to content-sm and uncomment the get_sidebar() below -->   
			
				<?php the_post(); ?>
				
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                	<?php if(!is_front_page()) { ?>
					<h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php } ?>
                    
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'photocrati-framework' ) . '&after=</div>') ?>					
                            <?php edit_post_link( __( 'Edit', 'photocrati-framework' ), '<span class="edit-link">', '</span>' ) ?>
                        </div><!-- .entry-content -->
                        
				</div><!-- #post-<?php the_ID(); ?> -->			
			
				<?php
				if (get_post_meta($post->ID, 'comments', true) == 'comments' || $page_comments) {
				
					comments_template('', true);
					// Add a custom field with Name and Value of "comments" to enable comments only on this page OR
					// turn page comments on in the theme settings under Customize Theme / Body.
				
				}
				?>			
			
			
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
			
			</div><!-- #content -->	
            
        <?php //get_sidebar(); ?> <!-- Uncomment this (remove the //) to add the sidebar to pages -->
		
<?php get_footer(); ?>