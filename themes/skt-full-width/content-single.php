<?php
/**
 * @package SKT Full Width
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
				'before' => '<div class="page-links">' . __( 'Pages:', 'skt-full-width' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma */
			//$category_list = get_the_category_list( __( ', ', 'skt-full-width' ) );

			/* translators: used between list items, there is a space after the comma */
			//$tag_list = get_the_tag_list( '', __( ', ', 'skt-full-width' ) );

			//if ( ! skt_full_width_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				/*if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was tagged %2$s.', 'skt-full-width' );
				} else {
					$meta_text = __( '', 'skt-full-width' );
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was posted in %1$s and tagged %2$s.', 'skt-full-width' );
				} else {
					$meta_text = __( 'This entry was posted in %1$s.', 'skt-full-width' );
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list
			);*/
		?>

		<?php edit_post_link( __( 'Edit', 'skt-full-width' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

