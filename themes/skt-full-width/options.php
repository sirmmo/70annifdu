<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {
	// Change this to use your theme slug
	return 'optionsframework_skt_full_width';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'skt-full-width'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	$options = array();
	$imagepath =  get_template_directory_uri() . '/images/';
		
	
	//Basic Settings
	
	$options[] = array(
		'name' => __('Basic Settings', 'skt-full-width'),
		'type' => 'heading');
		
		$options[] = array(
		'name' => __('Logo', 'skt-full-width'),
		'desc' => __('Upload your logo here', 'skt-full-width'),
		'id' => 'logo',
		'class' => '',
		'std'	=> '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => __('Copyright Text', 'skt-full-width'),
		'desc' => __('Some Text regarding copyright of your site, you would like to display in the footer.', 'skt-full-width'),
		'id' => 'footertext2',
		'std' => 'Full Width 2014. All Rights Reserved',
		'type' => 'text');

	$options[] = array(
		'name' => __('Featured image as background', 'skt-full-width'),
		'desc' => __('Do not use featured image as background image.', 'skt-full-width'),
		'id' => 'featured_as_background',
		'type' => 'checkbox',
		'std' => '' );

	$options[] = array(
		'name' => __('Color Scheme', 'skt-full-width'),
		'desc' => __('Select the color scheme for theme', 'skt-full-width'),
		'id' => 'navigation_color',
		'std' => '#7BB303',
		'type' => 'color');

	$options[] = array(
		'name' => __('Navigation Icon', 'skt-full-width'),
		'desc' => __('Upload icon for navigation<br />(max image size 9px X 9px)', 'skt-full-width'),
		'id' => 'navigation_icon',
		'class' => '',
		'std'	=> get_template_directory_uri()."/images/nav-icon-hover.png",
		'type' => 'upload');
		
	$options[] = array(
		'name' => __('Pagination Gradient Color - Top', 'skt-full-width'),
		'desc' => __('Select the top gradient color for pagination links', 'skt-full-width'),
		'id' => 'pagin_grad_top_color',
		'std' => '#89b219',
		'type' => 'color');
		
	$options[] = array(
		'name' => __('Pagination Gradient Color - Bottom', 'skt-full-width'),
		'desc' => __('Select the bottom gradient color for pagination links', 'skt-full-width'),
		'id' => 'pagin_grad_bottom_color',
		'std' => '#3f8d03',
		'type' => 'color');

	//Layout Settings
		
	$options[] = array(
		'name' => __('Layout Settings', 'skt-full-width'),
		'type' => 'heading');	
	
	$options[] = array(
		'name' => "Menu Layout",
		'desc' => "Select Layout for Menu position. It applies on inner pages only.",
		'id' => "sidebar-layout",
		'std' => "left",
		'type' => "images",
		'options' => array(
			'left' => $imagepath . '2cl.png',
			'right' => $imagepath . '2cr.png')
	);
	
	$options[] = array(
		'name' => __('Custom CSS', 'skt-full-width'),
		'desc' => __('Some Custom Styling for your site. Place any css codes here instead of the style.css file.', 'skt-full-width'),
		'id' => 'style2',
		'std' => '',
		'type' => 'textarea');
	
	//SLIDER SETTINGS

	$options[] = array(
		'name' => __('Homepage Slider', 'skt-full-width'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Slider Effects', 'skt-full-width'),
		'desc' => __('Add slider effects number eg: 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left.', 'skt-full-width'),
		'id' => 'sliderefect',
		'type' => 'text',
		'std' => '1' );
		
	$options[] = array(
		'name' => __('Slider Image 1', 'skt-full-width'),
		'desc' => __('First Slide', 'skt-full-width'),
		'id' => 'slide1',
		'class' => '',
		'std' => get_template_directory_uri()."/images/banner_bg.jpg",
		'type' => 'upload');
	
	$options[] = array(
		'desc' => __('Title', 'skt-full-width'),
		'id' => 'slidetitle1',
		'std' => 'Slider Image 1',
		'type' => 'text');
	
	$options[] = array(
		'desc' => __('Description or Tagline', 'skt-full-width'),
		'id' => 'slidedesc1',
		'std' => 'Small description for slide 1',
		'type' => 'textarea');			
		
	$options[] = array(
		'desc' => __('Url', 'skt-full-width'),
		'id' => 'slideurl1',
		'std' => '#link1',
		'type' => 'text',
		'subtype' => 'url');		
	
	$options[] = array(
		'name' => __('Slider Image 2', 'skt-full-width'),
		'desc' => __('Second Slide', 'skt-full-width'),
		'class' => '',
		'id' => 'slide2',
		'std' => get_template_directory_uri()."/images/banner-welcome.jpg",
		'type' => 'upload');
	
	$options[] = array(
		'desc' => __('Title', 'skt-full-width'),
		'id' => 'slidetitle2',
		'std' => 'Slider Image 2 ',
		'type' => 'text');	
	
	$options[] = array(
		'desc' => __('Description or Tagline', 'skt-full-width'),
		'id' => 'slidedesc2',
		'std' => 'Small description for slide 2',
		'type' => 'textarea');		
		
	$options[] = array(
		'desc' => __('Url', 'skt-full-width'),
		'id' => 'slideurl2',
		'std' => '#link2',
		'type' => 'text',
		'subtype' => 'url');	
		
	$options[] = array(
		'name' => __('Slider Image 3', 'skt-full-width'),
		'desc' => __('Third Slide', 'skt-full-width'),
		'id' => 'slide3',
		'class' => '',
		'std' => '',
		'type' => 'upload');	
	
	$options[] = array(
		'desc' => __('Title', 'skt-full-width'),
		'id' => 'slidetitle3',
		'std' => '',
		'type' => 'text');	
		
	$options[] = array(
		'desc' => __('Description or Tagline', 'skt-full-width'),
		'id' => 'slidedesc3',
		'std' => '',
		'type' => 'textarea');	
			
	$options[] = array(
		'desc' => __('Url', 'skt-full-width'),
		'id' => 'slideurl3',
		'std' => '',
		'type' => 'text',
		'subtype' => 'url');		
	
	$options[] = array(
		'name' => __('Slider Image 4', 'skt-full-width'),
		'desc' => __('Fourth Slide', 'skt-full-width'),
		'id' => 'slide4',
		'class' => '',
		'std' => '',
		'type' => 'upload');	
		
	$options[] = array(
		'desc' => __('Title', 'skt-full-width'),
		'id' => 'slidetitle4',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'desc' => __('Description or Tagline', 'skt-full-width'),
		'id' => 'slidedesc4',
		'std' => '',
		'type' => 'textarea');			
		
	$options[] = array(
		'desc' => __('Url', 'skt-full-width'),
		'id' => 'slideurl4',
		'std' => '',
		'type' => 'text',
		'subtype' => 'url');		
	
	$options[] = array(
		'name' => __('Slider Image 5', 'skt-full-width'),
		'desc' => __('Fifth Slide', 'skt-full-width'),
		'id' => 'slide5',
		'class' => '',
		'std' => '',
		'type' => 'upload');	
		
	$options[] = array(
		'desc' => __('Title', 'skt-full-width'),
		'id' => 'slidetitle5',
		'std' => '',
		'type' => 'text');	
	
	$options[] = array(
		'desc' => __('Description or Tagline', 'skt-full-width'),
		'id' => 'slidedesc5',
		'std' => '',
		'type' => 'textarea');		
		
	$options[] = array(
		'desc' => __('Url', 'skt-full-width'),
		'id' => 'slideurl5',
		'std' => '',
		'type' => 'text',
		'subtype' => 'url');	
			
	//Social Settings
	
	$options[] = array(
		'name' => __('Social Settings', 'skt-full-width'),
		'type' => 'heading');
	
	$options[] = array(
		'desc' => __('Please set the value of following fields, as per the instructions given along. If you do not want to use an icon, just leave it blank. If some icons are showing up, even when no value is set then make sure they are completely blank, and just save the options once. They will not be shown anymore.', 'skt-full-width'),
		'type' => 'info');

	$options[] = array(
		'name' => __('Facebook', 'skt-full-width'),
		'desc' => __('Facebook Profile or Page URL i.e. http://facebook.com/username/ ', 'skt-full-width'),
		'id' => 'facebook',
		'std' => '#',
		'class' => 'mini',
		'type' => 'text',
		'subtype' => 'url');
	
	$options[] = array(
		'name' => __('Twitter', 'skt-full-width'),
		'desc' => __('Twitter Username', 'skt-full-width'),
		'id' => 'twitter',
		'std' => '#',
		'class' => 'mini',
		'type' => 'text');
	
	$options[] = array(
		'name' => __('Google Plus', 'skt-full-width'),
		'desc' => __('Google Plus profile url, including "http://"', 'skt-full-width'),
		'id' => 'google',
		'std' => '#',
		'class' => 'mini',
		'type' => 'text',
		'subtype' => 'url');
		
	$options[] = array(
		'name' => __('Linkedin', 'skt-full-width'),
		'desc' => __('Linkedin URL', 'skt-full-width'),
		'id' => 'linkedin',
		'std' => '#',
		'class' => 'mini',
		'type' => 'text',
		'subtype' => 'url');	

	
	// Contact Details
		$options[] = array(
		'name' => __('Contact Details for footer', 'skt-full-width'),
		'type' => 'heading');
	
		$options[] = array(
		'desc' => __('Company Name', 'skt-full-width'),
		'id' => 'contact1',
		'std' => 'Full Width',
		'type' => 'text');	
		
		$options[] = array(
		'desc' => __('Address 1', 'skt-full-width'),
		'id' => 'contact2',
		'std' => '123 Some Street',
		'type' => 'text');	
		
		$options[] = array(
		'desc' => __('Address 2', 'skt-full-width'),
		'id' => 'contact3',
		'std' => 'California, USA',
		'type' => 'text');
		
		$options[] = array(
		'desc' => __('Phone', 'skt-full-width'),
		'id' => 'contact4',
		'std' => '100 2000 300',
		'type' => 'text');
		
		$options[] = array(
		'desc' => __('Email', 'skt-full-width'),
		'id' => 'contact5',
		'std' => sanitize_email( 'info@example.com' ),
		'type' => 'text',
		'subtype' => 'email');	

	// Support					
		$options[] = array(
		'name' => __('Our Themes', 'skt-full-width'),
		'type' => 'heading');
		
	$options[] = array(
		'desc' => __('SKT Full Width WordPress theme has been Designed and Created by SKT Themes.', 'skt-full-width'),
		'type' => 'info');	
		
	 $options[] = array(
		'desc' => __('<a href="'.SKT_THEME_URL.'" target="_blank"><img src="'.get_template_directory_uri().'/images/sktskill.jpg"></a><p><em><a target="_blank" href="http://www.sktthemes.net/themes/skt_full_width_pro/">Buy PRO version for only $30 with more features and access to our support forum.</a></em></p>', 'skt-full-width'),
		'type' => 'info');	
	
	
	return $options;
}