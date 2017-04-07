<?php
/**
 * @package SKT Photo World
 */
?>



	<div class="entry-content">
     <div class="postmeta">
                        	<div class="post-date"><?php echo get_the_date(); ?></div><!-- post-date -->
                            <div class="post-comment"><?php comments_number(); ?></div><!-- post-comment --><div class="clear"></div>
						</div><!-- postmeta -->
		<div class="post-thumb">
			<?php if (has_post_thumbnail() )
				the_post_thumbnail();
				?>
		</div><br />
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'skt-photo-world' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

		<?php edit_post_link( __( 'Edit', 'skt-photo-world' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

