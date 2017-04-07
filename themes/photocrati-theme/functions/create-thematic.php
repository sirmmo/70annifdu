<?php 

// The Photocrati SuperTheme uses wp_nav_menu() in two locations.
if ( function_exists('register_nav_menus') ) {
register_nav_menus( 
array(
	'primary' 			=> 		__( 'Primary Navigation', 'photocrati' ),
	'footer' 			=> 		__( 'Footer Navigation (links - no dropdowns)', 'photocrati' ),
) 
);
}

function photocrati_theme_wp_head() {
  if (Photocrati_Style_Manager::get_active_preset()->style_skin != 'legacy') {
		echo '<meta name="viewport" content="initial-scale=1" />';
	}
}

// Load scripts for the jquery Superfish plugin http://users.tpg.com.au/j_birch/plugins/superfish/#examples
function thematic_head_scripts() {
    $scriptdir_start = "\t";
		$scriptdir_start .= '<script type="text/javascript" src="';
    $scriptdir_start .= get_bloginfo('template_directory');
    $scriptdir_start .= '/scripts/';
    
    $scriptdir_end = '"></script>';
    
    $scripts = "\n";
    $scripts .= $scriptdir_start . 'hoverIntent.js' . $scriptdir_end . "\n";
    $scripts .= $scriptdir_start . 'superfish.js' . $scriptdir_end . "\n";
    $scripts .= $scriptdir_start . 'supersubs.js' . $scriptdir_end . "\n";
    
    if (Photocrati_Style_Manager::get_active_preset()->style_skin == 'legacy') {
    	$dropdown_options = $scriptdir_start . 'thematic-dropdowns.js' . $scriptdir_end . "\n";
    	$scripts = $scripts . apply_filters('thematic_dropdown_options', $dropdown_options);
    }

		$scripts .= "\n";
		$scripts .= "\t";
		$scripts .= '<script type="text/javascript">' . "\n";
		$scripts .= "\t\t";
		$scripts .= 'jQuery.noConflict();' . "\n";
		$scripts .= "\t";
		$scripts .= '</script>' . "\n";

    // Print filtered scripts
    print apply_filters('thematic_head_scripts', $scripts);

}
add_action('wp_head','photocrati_theme_wp_head');
add_action('wp_head','thematic_head_scripts');


function photocrati_theme_menu_fallback_create($args)
{
	$args['echo'] = false;
	$args['menu_class'] = 'menu photocrati-menu';
	
	$menu = wp_page_menu($args);
	$menu = preg_replace('/<ul>/i', '<ul class="sf-menu">', $menu, 1);
	$menu = preg_replace('/page_item([\\s"\'])/i', 'menu-item page_item$1', $menu);
	$menu = preg_replace('/page_item_has_children/i', 'menu-item-has-children page_item_has_children', $menu);
	
	return $menu;
}

?>
