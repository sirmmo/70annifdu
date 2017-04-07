<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {
	// Change this to use your theme slug
	return 'optionsframework_skt_photo_world';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'skt-photo-world'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	$options = array();
	$imagepath =  get_template_directory_uri() . '/images/';
		
	
	//Basic Settings
	
	$options[] = array(
		'name' => __('Basic Settings', 'skt-photo-world'),
		'type' => 'heading');
		
		$options[] = array(
		'name' => __('Logo', 'skt-photo-world'),
		'desc' => __('Upload your logo here', 'skt-photo-world'),
		'id' => 'logo',
		'class' => '',
		'std'	=> '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => __('Copyright Text', 'skt-photo-world'),
		'desc' => __('Some Text regarding copyright of your site, you would like to display in the footer.', 'skt-photo-world'),
		'id' => 'footertext2',
		'std' => 'Photo World 2014. All Rights Reserved',
		'type' => 'text');

	$options[] = array(
		'name' => __('Featured image as background', 'skt-photo-world'),
		'desc' => __('Do not use featured image as background image.', 'skt-photo-world'),
		'id' => 'featured_as_background',
		'type' => 'checkbox',
		'std' => '' );

	$options[] = array(
		'name' => __('Color Scheme', 'skt-photo-world'),
		'desc' => __('Select the color scheme for theme', 'skt-photo-world'),
		'id' => 'navigation_color',
		'std' => '#fc0000',
		'type' => 'color');

	/*$options[] = array(
		'name' => __('Navigation Icon', 'skt-photo-world'),
		'desc' => __('Upload icon for navigation<br />(max image size 9px X 9px)', 'skt-photo-world'),
		'id' => 'navigation_icon',
		'class' => '',
		'std'	=> get_template_directory_uri()."/images/nav-icon-hover.png",
		'type' => 'upload');*/
		
	$options[] = array(
		'name' => __('Pagination Gradient Color - Top', 'skt-photo-world'),
		'desc' => __('Select the top gradient color for pagination links', 'skt-photo-world'),
		'id' => 'pagin_grad_top_color',
		'std' => '#e76a6a',
		'type' => 'color');
		
	$options[] = array(
		'name' => __('Pagination Gradient Color - Bottom', 'skt-photo-world'),
		'desc' => __('Select the bottom gradient color for pagination links', 'skt-photo-world'),
		'id' => 'pagin_grad_bottom_color',
		'std' => '#fc0000',
		'type' => 'color');

	//Layout Settings
		
	$options[] = array(
		'name' => __('Layout Settings', 'skt-photo-world'),
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
		'name' => __('Custom CSS', 'skt-photo-world'),
		'desc' => __('Some Custom Styling for your site. Place any css codes here instead of the style.css file.', 'skt-photo-world'),
		'id' => 'style2',
		'std' => '',
		'type' => 'textarea');
	
	//SLIDER SETTINGS

	$options[] = array(
		'name' => __('Homepage Slider', 'skt-photo-world'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Slider Image 1', 'skt-photo-world'),
		'desc' => __('First Slide', 'skt-photo-world'),
		'id' => 'slide1',
		'class' => '',
		'std' => get_template_directory_uri()."/images/banner_bg.jpg",
		'type' => 'upload');		
	
	$options[] = array(
		'name' => __('Slider Image 2', 'skt-photo-world'),
		'desc' => __('Second Slide', 'skt-photo-world'),
		'class' => '',
		'id' => 'slide2',
		'std' => get_template_directory_uri()."/images/banner-welcome.jpg",
		'type' => 'upload');
		
		
	$options[] = array(
		'name' => __('Slider Image 3', 'skt-photo-world'),
		'desc' => __('Third Slide', 'skt-photo-world'),
		'id' => 'slide3',
		'class' => '',
		'std' => get_template_directory_uri()."/images/banner-third.jpg",
		'type' => 'upload');		
	
	$options[] = array(
		'name' => __('Slider Image 4', 'skt-photo-world'),
		'desc' => __('Fourth Slide', 'skt-photo-world'),
		'id' => 'slide4',
		'class' => '',
		'std' => get_template_directory_uri()."/images/banner-fourth.jpg",
		'type' => 'upload');	
				
	
	$options[] = array(
		'name' => __('Slider Image 5', 'skt-photo-world'),
		'desc' => __('Fifth Slide', 'skt-photo-world'),
		'id' => 'slide5',
		'class' => '',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => __('Slider Image 6', 'skt-photo-world'),
		'desc' => __('Sixth Slide', 'skt-photo-world'),
		'id' => 'slide6',
		'class' => '',
		'std' => get_template_directory_uri().'/images/banner-sixth.jpg',
		'type' => 'upload');
		
	$options[] = array(
		'name' => __('Slider Image 7', 'skt-photo-world'),
		'desc' => __('Seventh Slide', 'skt-photo-world'),
		'id' => 'slide7',
		'class' => '',
		'std' => get_template_directory_uri().'/images/banner-seventh.jpg',
		'type' => 'upload');
		
	$options[] = array(
		'name' => __('Slider Image 8', 'skt-photo-world'),
		'desc' => __('Eighth Slide', 'skt-photo-world'),
		'id' => 'slide8',
		'class' => '',
		'std' => get_template_directory_uri().'/images/banner-eight.jpg',
		'type' => 'upload');
		
	$options[] = array(
		'name' => __('Slider Image 9', 'skt-photo-world'),
		'desc' => __('Nineth Slide', 'skt-photo-world'),
		'id' => 'slide9',
		'class' => '',
		'std' => get_template_directory_uri().'/images/banner-nine.jpg',
		'type' => 'upload');	
		
	$options[] = array(
		'name' => __('Slider Image 10', 'skt-photo-world'),
		'desc' => __('Tenth Slide', 'skt-photo-world'),
		'id' => 'slide10',
		'class' => '',
		'std' => get_template_directory_uri().'/images/banner-ten.jpg',
		'type' => 'upload');	
			
	//Social Settings
	
	$options[] = array(
		'name' => __('Social Settings', 'skt-photo-world'),
		'type' => 'heading');
	
	$options[] = array(
		'desc' => __('Please set the value of following fields, as per the instructions given along. If you do not want to use an icon, just leave it blank. If some icons are showing up, even when no value is set then make sure they are completely blank, and just save the options once. They will not be shown anymore.', 'skt-photo-world'),
		'type' => 'info');

	$options[] = array(
		'name' => __('Facebook', 'skt-photo-world'),
		'desc' => __('Facebook Profile or Page URL i.e. http://facebook.com/username/ ', 'skt-photo-world'),
		'id' => 'facebook',
		'std' => '#',
		'class' => 'mini',
		'type' => 'text',
		'subtype' => 'url');
	
	$options[] = array(
		'name' => __('Twitter', 'skt-photo-world'),
		'desc' => __('Twitter Username', 'skt-photo-world'),
		'id' => 'twitter',
		'std' => '#',
		'class' => 'mini',
		'type' => 'text');
	
	$options[] = array(
		'name' => __('Google Plus', 'skt-photo-world'),
		'desc' => __('Google Plus profile url, including "http://"', 'skt-photo-world'),
		'id' => 'google',
		'std' => '#',
		'class' => 'mini',
		'type' => 'text',
		'subtype' => 'url');
		
	$options[] = array(
		'name' => __('Linkedin', 'skt-photo-world'),
		'desc' => __('Linkedin URL', 'skt-photo-world'),
		'id' => 'linkedin',
		'std' => '#',
		'class' => 'mini',
		'type' => 'text',
		'subtype' => 'url');	

	
	// Contact Details
		$options[] = array(
		'name' => __('Contact Details for footer', 'skt-photo-world'),
		'type' => 'heading');
	
		$options[] = array(
		'desc' => __('Company Name', 'skt-photo-world'),
		'id' => 'contact1',
		'std' => 'PHOTO WORLD',
		'type' => 'text');	
		
		$options[] = array(
		'desc' => __('Address 1', 'skt-photo-world'),
		'id' => 'contact2',
		'std' => '123 Some Street',
		'type' => 'text');	
		
		$options[] = array(
		'desc' => __('Address 2', 'skt-photo-world'),
		'id' => 'contact3',
		'std' => 'California, USA',
		'type' => 'text');
		
		$options[] = array(
		'desc' => __('Phone', 'skt-photo-world'),
		'id' => 'contact4',
		'std' => '100 2000 300',
		'type' => 'text');
		
		$options[] = array(
		'desc' => __('Email', 'skt-photo-world'),
		'id' => 'contact5',
		'std' => sanitize_email( 'info@example.com' ),
		'type' => 'text',
		'subtype' => 'email');	

	// Support					
		$options[] = array(
		'name' => __('Our Themes', 'skt-photo-world'),
		'type' => 'heading');
		
	$options[] = array(
		'desc' => __('SKT Photo World WordPress theme has been Designed and Created by SKT Themes.', 'skt-photo-world'),
		'type' => 'info');	
		
	 $options[] = array(
		'desc' => __('<a href="'.esc_url(SKT_THEME_URL).'" target="_blank"><img src="'.get_template_directory_uri().'/images/sktskill.jpg"></a><p><em><a target="_blank" href="'.esc_url(SKT_THEME_URL_DIRECT).'">Buy PRO version for only $30 with more features.</a></em></p>', 'skt-photo-world'),
		'type' => 'info');	
	
	
	return $options;
}