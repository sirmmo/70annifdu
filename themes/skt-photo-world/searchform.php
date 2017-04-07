<?php
/**
 * The template for displaying search forms in SKT Photo World
 *
 * @package SKT Photo World
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="search-label"><?php _ex( 'Search for:', 'label', 'skt-photo-world' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'skt-photo-world' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'skt-photo-world' ); ?>">
</form>
