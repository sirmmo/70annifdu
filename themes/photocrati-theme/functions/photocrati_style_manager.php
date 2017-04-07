<?php
define('PHOTOCRATI_ACTIVE_PRESET', '__active__');
define('PHOTOCRATI_PRESET_PREFIX', 'photocrati_preset_');

include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'photocrati_presets.php');


class Photocrati_Style_Manager extends ArrayObject
{
    static  $_cache     = array();
    var     $_settings  = array();
    var     $_aliases = array(
        'is_sidebar_enabled' => 'display_sidebar',
        'name'               => 'preset_name',
        'title'              => 'preset_title',
        'is_third_party'     => 'custom_setting'
    );

    /**
     * Constructs a new preset
     * @param array $settings
     */
    function __construct($settings=array())
    {
        if (!is_array($settings)) $settings = array();

        // Perform some translations
        $keys = array_keys($settings);

        // The default option for blog meta is enabled
        if (in_array('blog_meta', $keys)) {
            if ($settings['blog_meta'] === '') {
                $settings['blog_meta'] = TRUE;
            }
        }

        // An empty string for showing the photocrati link is equal to TRUE
        if (in_array('show_photocrati', $keys)) {
            if (is_string($settings['show_photocrati']) && $settings['show_photocrati'] == 'hide') {
                $settings['show_photocrati'] = FALSE;
            }
            else if(is_string($settings['show_photocrati']) && $settings['show_photocrati'] == '') {
                $settings['show_photocrati'] = TRUE;
            }
        }

        // Menu font case options need adjusted
        if (in_array('submenu_font_case', $keys)) {
            if ($settings['submenu_font_case'] == 'normal') $settings['submenu_font_case'] = 'none';
            if ($settings['submenu_font_case'] == '')       $settings['submenu_font_case'] = 'uppercase';
        }
        if (in_array('menu_font_case', $keys)) {
            if ($settings['menu_font_case'] == 'normal')    $settings['menu_font_case'] = 'none';
            if ($settings['menu_font_case'] == '')          $settings['menu_font_case'] = 'uppercase';
        }
        $this->_settings = $settings;

        // Set some default values
        $defaults = self::get_default_list();
        $this->_clean_up_keys();

        $keys = array_keys($this->_settings);
        
        foreach ($defaults as $key => $value) {
            if (!in_array($key, $keys)) {
            	$this->$key = $value;
            } 
        }
        
        // code to output presets code
#        {
#		      $preset_dir = get_template_directory() . DIRECTORY_SEPARATOR . 'admin/presets/import';
#		      $preset_list = glob($preset_dir . '/*');
#		      foreach ($preset_list as $preset_file) {
#		      	if (is_file($preset_file)) {
#		      		$code = self::create_preset_code($preset_file);
#		      		
#		      		echo $code;
#		      	}
#		      }
#					die();
#        }
    }
    
    static function get_default_list()
    {
        // Set some default values
        $defaults = array(
            'name'           =>  'preset-unnamed',
            'title'          =>  'Unnamed Preset',
            'style_skin' => 'legacy', /* legacy, modern */
            'dynamic_style'         =>  TRUE,
            'one_column'            =>  FALSE,
            'one_column_color'      =>  'FFFFFF',
            'one_column_opacity' => '100',
            'one_column_logo'       =>  FALSE,
            'one_column_margin'     =>  '30',
            'layout_width'          => '960',
            'layout_width_unit'     => 'px',
            'layout_width_min'          => '0',
            'layout_width_min_unit'     => 'px',
            'layout_width_max'          => '100',
            'layout_width_max_unit'     => 'percent',
            'layout_spacing'      => '0',
            'layout_alignment'      => 'left',
            'is_sidebar_enabled'    =>  TRUE,
            'sidebar_alignment'     => 'right',
            'sidebar_alignment_responsive' => 'bottom',
            'content_width'         =>  '65',
            'sidebar_width'         =>  '35',
            'logo_menu_position'    =>  'left-right',
            'menu_alignment'    =>  'default',
            'bg_color'              =>  'FFFFFF',
            'bg_opacity'              =>  '100',
            'bg_image'              =>  '',
            'bg_repeat'             =>  'repeat',
            'bg_attachment'         =>  'scroll',
            'bg_scale'         =>  'auto',
            'home_bg' => 'default',
            'home_bg_image' => '',
            'home_bg_interval_speed' => '3',
            'home_bg_transition_effect'   => '1',
            'home_bg_transition_speed'   => '1',
            'header_bg_color'       =>  'FFFFFF',
            'header_opacity'       =>  '100',
            'header_bg_image'       =>  '',
            'header_bg_repeat'      =>  'repeat',
            'header_position'       =>  'static',
            'header_margin_top'    =>  '0',
            'header_margin_left'    =>  '0',
            'header_margin_right'    =>  '0',
            'header_drop_shadow_size' =>  '0',
            'container_color'       =>  'transparent',
            'container_opacity'       =>  '100',
            'container_border'      =>  '0',
            'container_border_color'=>  'CCCCCC',
            'container_drop_shadow_size' =>  '0',
            'font_color'            =>  '666666',
            'font_size'             =>  '16',
            'font_style'            =>  'Open Sans',
            'font_italic'           =>  'normal',
            'font_weight'           =>  '',
            'font_decoration'       =>  'none',
            'font_case'             =>  'none',
            'p_line'                =>  '25',
            'p_space'               =>  '25',
            'h1_color'              =>  '7695B2',
            'h1_size'               =>  '30',
            'h1_font_style'         =>  'Open Sans',
            'h1_font_case'          =>  'none',
            'h1_font_weight'        =>  'bold',
            'h1_font_decoration'    =>  'none',
            'h1_font_italic'        =>  'normal',
            'h1_font_align'         =>  '',
            'h2_color'              =>  '333333',
            'h2_size'               =>  '26',
            'h2_font_style'         =>  'Open Sans',
            'h2_font_case'          =>  'none',
            'h2_font_weight'        =>  'bold',
            'h2_font_italic'        =>  'normal',
            'h2_font_decoration'    =>  'none',
            'h2_font_align'         =>  '',
            'h3_color'              =>  '333333',
            'h3_size'               =>  '24',
            'h3_font_style'         =>  'Open Sans',
            'h3_font_case'          =>  'none',
            'h3_font_weight'        =>  'bold',
            'h3_font_italic'        =>  '', #normal
            'h3_font_decoration'    =>  'none',
            'h3_font_align'         =>  '',
            'h4_color'              =>  '333333',
            'h4_size'               =>  '22',
            'h4_font_style'         =>  'Open Sans',
            'h4_font_case'          =>  'none',
            'h4_font_weight'        =>  'bold',
            'h4_font_italic'        =>  '', #normal
            'h4_font_decoration'    =>  'none',
            'h4_font_align'         =>  '',
            'h5_color'              =>  '333333',
            'h5_size'               =>  '20',
            'h5_font_style'         =>  'Open Sans',
            'h5_font_case'          =>  'none',
            'h5_font_weight'        =>  'bold',
            'h5_font_italic'        =>  '', # normal
            'h5_font_decoration'    =>  'none',
            'h5_font_align'         =>  '',
            'link_color'            =>  '2B5780',
            'link_hover_color'      =>  '266ead',
            'link_hover_style'      =>  'underline',
            'sidebar_font_color'    =>  '666666',
            'sidebar_font_size'     =>  '16',
            'sidebar_font_style'    =>  'Open Sans',
            'sidebar_font_weight'   =>  '', # normal
            'sidebar_font_italic'   =>  '', # normal
            'sidebar_font_decoration' => 'none',
            'sidebar_font_case'     =>  'none',
            'sidebar_bg_color'      =>  'transparent',
            'sidebar_opacity'      =>  '100',
            'sidebar_link_color'    =>  '2B5780',
            'sidebar_link_hover_color' => '2B5780',
            'sidebar_link_hover_style' => 'underline',
            'sidebar_title_color'   =>  '333333',
            'sidebar_title_size'    =>  '14',
            'sidebar_title_style'   =>  'Open Sans',
            'sidebar_title_weight'  =>  'bold', # normal
            'sidebar_title_italic'  =>  '', # normal
            'sidebar_title_decoration' =>'none',
            'sidebar_title_case'    =>  'uppercase',
            'menu_skin'             => 'default',
            'menu_container_color'  => 'transparent',
            'menu_container_opacity'  => '100',
            'menu_vertical_padding' => '10',
            'menu_style'            =>  'transparent',
            'menu_color'            =>  'FFFFFF',
            'menu_color_opacity'            =>  '100',
            'menu_hover_color'      =>  'FFFFFF',
            'menu_hover_color_opacity' =>  '100',
            'menu_font_size'        =>  '14',
            'menu_font_style'       =>  'Open Sans',
            'menu_font_color'       =>  'A8A8A8',
            'menu_font_weight'      =>  'bold',
            'menu_font_italic'      =>  '', # normal,
            'menu_font_decoration'  =>  'none',
            'menu_font_hover_color' => '2D73B6',
            'menu_font_case'        => 'uppercase',
            'submenu_color'         => 'E8E8E8',
            'submenu_color_opacity'  => '100',
            'submenu_hover_color'   => 'C8C9CB',
            'submenu_hover_color_opacity' => '100',
            'submenu_font_size'     => '12',
            'submenu_font_style'    => 'Open Sans',
            'submenu_font_color'    => '484848',
            'submenu_font_weight'   => '', # normal
            'submenu_font_italic'   => '', # normal
            'submenu_font_decoration' => 'none',
            'submenu_font_hover_color' => '2D73B6',
            'submenu_font_case'     => 'none',
            'nextgen_border'        => '5',
            'nextgen_border_color'  => 'CCCCCC',
            'custom_logo'           => 'title',
            'custom_logo_image'     => 'Logo_Steel.png',
            'footer_copy'           => '',
            'custom_sidebar'        => FALSE,
            'custom_sidebar_position' => 'ABOVE',
            'custom_sidebar_html'   => '',
            'social_media'          => FALSE,
            'social_media_title'    => 'Follow Me',
            'social_media_set'      => 'small',
            'social_rss'            => '',
            'social_email'          => '',
            'social_twitter'        => '',
            'social_facebook'       => '',
            'social_flickr'         => '',
            'google_analytics'      => '',
            'custom_js'             => '',
            'custom_css'            => 'p {
margin-bottom:0.5em;
}

#footer {
border-top:0 solid #E8E7E7;
text-align:center;
}',
            'custom_css_modern'     => 'p {
margin-bottom:0.5em;
}

#footer {
border-top:0 solid #E8E7E7;
text-align:center;
}',
            'custom_css_modern_desktop'     => '',
            'custom_css_modern_mobile'     => '',
            'header_height'         => '140',
            'header_width'         => '250',
            'header_logo_margin_above' => '25',
            'header_logo_margin_below' => '10',
            'header_logo_margin_side' => '0',
            'title_size'            => '38',
            'title_color'           => '7695b2',
            'title_font_style'      => '',
            'title_font_weight'     => 'bold',
            'title_style'           => 'Open Sans',
            'title_italic'          => '', # normal
            'title_decoration'      => 'none',
            'title_font_case'       => 'none',
            'description_size'      => '16',
            'description_color'     => '999999',
            'description_style'     => 'Open Sans',
            'description_font_weight'   => '',
            'description_font_style'    => 'normal',
            'description_font_italic'   => '',
            'description_font_decoration' => 'none',
            'bg_top_offset'         => '0',
            'container_padding'     => '10',
            'footer_font'           => '16',
            'footer_font_color'     => '333333',
            'is_third_party'        => FALSE,
            'footer_widget_placement' => '3',
            'footer_background'     => 'FFFFFF',
            'footer_opacity'     => '100',
            'footer_font_style'     => 'Open Sans',
            'footer_font_weight'    => '', # normal
            'footer_font_italic'    => '', # normal
            'footer_font_case'      => 'none', # normal
            'footer_font_decoration'=> 'none',
            'footer_widget_title'   => '14',
            'footer_widget_color'   => '7695b2',
            'footer_widget_style'   => 'Open Sans',
            'footer_widget_weight'  => 'bold', # normal
            'footer_widget_italic'  => '', # normal
            'footer_widget_decoration' => 'none',
            'footer_widget_case'    => 'none',
            'footer_link_color'     => '7695b2',
            'footer_link_hover_color' => '7695b2',
            'footer_link_hover_style' => 'none',
            'footer_height'         => '430',
            'footer_drop_shadow_size' => '0',
            'show_photocrati'       => TRUE,
            'page_comments'         => FALSE,
            'blog_meta'             => TRUE,
            'blog_meta_pre_alignment' => 'default',
            'blog_meta_post_alignment' => 'default',
        );
        
        return $defaults;
    }

    /**
     * Determines if a particular option has been set
     * @param $option
     * @return bool
     */
    function __isset($option)
    {
        return isset($this->_settings[$option]);
    }


    /**
     * Gets the value of an option
     * @param $option
     * @return null
     */
    function __get($option)
    {
        $keys = array_keys($this->_settings);
        $retval = in_array($option, $keys) ? $this->_settings[$option] : NULL;

        // If an option was unfound by name, perhaps it goes by an alias
        if (is_null($retval) && isset($this->_aliases[$option])) {
            $retval = $this->__get($this->_aliases[$option]);
        }

        // Ensure that proper boolean values are returned
        if ($retval == 'YES')       $retval = TRUE;
        elseif ($retval == 'NO')    $retval = FALSE;
        elseif ($retval == 'ON')     $retval = TRUE;
        elseif ($retval == 'OFF')   $retval = FALSE;

        return $retval;
    }


    /**
     * Sets an option to a particular value
     * @param $option
     * @param $value
     * @return mixed
     */
    function __set($option, $value)
    {
        // Ensure that proper boolean values are set
        if ($value == 'YES')    $value = TRUE;
        elseif ($value == 'NO') $value = FALSE;
        elseif ($value == 'ON') $value = TRUE;
        elseif ($value == 'OFF')$value = FALSE;

        // Remove the old option name
        if (isset($this->_aliases[$option])) {
            $old_option_name = $this->_aliases[$option];
            unset($this->_settings[$old_option_name]);
        }

        // Set the new option instead of the old option
        if (($key = array_search($option, $this->_aliases)) !== FALSE) $option = $key;

        return ($this->_settings[$option] = $value);
    }

    /**
     * Determines if a particular option has been set or not
     * @param $option_name
     * @return bool
     */
    function has_option($option_name)
    {
        return isset($this->$option_name);
    }

    /**
     * Gets the title of the preset
     * @return null
     */
    function get_title()
    {
        return $this->title;
    }

    /**
     * Gets the name of the preset
     * @return null
     */
    function get_name()
    {
        return $this->name;
    }


    /**
     * Determines if the preset was created by a third-party (such as the user)
     * @return bool
     */
    function is_third_party()
    {
        return $this->is_third_party;
    }

    /**
     * Sets the preset as the active preset for the theme
     */
    function set_as_active()
    {
        self::$_cache[PHOTOCRATI_ACTIVE_PRESET] = $this->_settings;
        $this->save(PHOTOCRATI_ACTIVE_PRESET);
    }

    /**
     * Returns TRUE if this is a new preset with no settings
     * @return bool
     */
    function is_new()
    {
        return empty($this->_settings) || $this->get_name() == 'preset-unnamed';
    }

    /**
     * Saves any changes made to the preset
     * @return bool
     */
    function save($save_as=FALSE)
    {
        $this->_clean_up_keys();
        if (!$save_as) $save_as = $this->get_name();
        update_option(PHOTOCRATI_PRESET_PREFIX.$save_as, $this->_settings);
        self::_generate_index($this->get_name(), $this->is_third_party());
    }

    function delete($key)
    {
        unset($this->_settings[$key]);
    }

    function _clean_up_keys()
    {
        // Clean up redundant keys
        $keys = array_keys($this->_settings);
        foreach ($this->_aliases as $good_key => $bad_key) {

            // We only want the aliased key (good key)
            if (in_array($bad_key, $keys)) {
                if (in_array($good_key, $keys)) {
                    unset($this->_settings[$bad_key]);
                }
                else {
                    $this->$good_key = $this->$bad_key;
                    unset($this->_settings[$bad_key]);
                }
            }
        }
    }
    
    static function color_hex2rgb($hex) {
			$hex = str_replace("#", "", $hex);

			if(strlen($hex) == 3) {
				$r = hexdec(substr($hex,0,1).substr($hex,0,1));
				$g = hexdec(substr($hex,1,1).substr($hex,1,1));
				$b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
				$r = hexdec(substr($hex,0,2));
				$g = hexdec(substr($hex,2,2));
				$b = hexdec(substr($hex,4,2));
			}
			$rgb = array($r, $g, $b);
			//return implode(",", $rgb); // returns the rgb values separated by commas
			return $rgb; // returns an array with the rgb values
		}
		
		static function build_background_color_property($color, $opacity)
		{
			$ret = null;
			
			if($color != 'transparent') {
				if ($opacity >= 100) {
					$ret .= '#' . $color; 
				} 
				else {
					$ret .= 'rgba(' . implode(',', self::color_hex2rgb($color)) . ',' . round($opacity / 100, 2) . ')'; 
				} 
			} 
			else { 
				$ret .= $color; 
			}
			
			return $ret;
		}
		
		static function build_width_property($width, $unit, $zero_value = 0)
		{
			$ret = $width;
			
			if ($unit == 'percent') {
				$unit = '%';
			}
			
			if ($width > 0) {
				$ret .= $unit;
			}
			else {
				$ret = $zero_value;
			}
			
			return $ret;
		}
		
		static function build_width_property_set($width, $unit, $min_width, $min_unit, $max_width, $max_unit, $prefix = "\t\t")
		{
			$ret = null;
			
			$ret .= $prefix . 'width: ' . Photocrati_Style_Manager::build_width_property($width, $unit) . ';' . "\n";
			$ret .= $prefix . 'min-width: ' . Photocrati_Style_Manager::build_width_property($min_width, $min_unit, 'none') . ';' . "\n";
			$ret .= $prefix . 'max-width: ' . Photocrati_Style_Manager::build_width_property($max_width, $max_unit, 'none') . ';' . "\n";
			
			return $ret;
		}
		
		static function get_menu_skin_list()
		{
			return array(
				'legacy' => __('Legacy (NOT responsive)'),
				'default' => __('Default (responsive)'),
			);
		}
		
		static function get_image_list($filter = null, $filter_exclude = null)
		{
			// each entry in the array is either 'type' of 'item' or 'group' -- we could create groups specific to headers for instance, then filter them by calling get_image_list(array('group' => 'headers'))
			$list = array(
				array(
					'type' => 'group',
					'name' => 'user',
					'label' => __('User', 'photocrati-framework'),
					'always' => true,
				),
				array(
					'type' => 'group',
					'name' => 'patterns',
					'label' => __('Patterns', 'photocrati-framework'),
				),
				array(
					'type' => 'group',
					'name' => 'images',
					'label' => __('Images', 'photocrati-framework'),
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'background_bokeh.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'BlueScratchBody2_BG.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'BlueScratchHeader_BG.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'Circles_BG.gif',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'DarkTileBG3.gif',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'DarkTileNavBG.gif',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'Green_BG.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'Grille_BG.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'header-bg-blk.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'header-bg-gr.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'header-bg.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'header_fadepink.gif',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'Muslin_BG.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'pink_stripes.gif',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'polnlig_header_bg.gif',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'WallpaperBG3.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'WallpaperNavBG2.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'Wood_BG.jpg',
				),
				
				// new set added in 4.8
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'basicblack.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'basicblue.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'basicgray.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'basicgreen.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'basiclightgreen.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'basicred.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'basicyellow.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'diagonalswhite.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'roughwhite.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'squareswhite.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'patterns',
					'file' => 'trelliswhite.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'bokehblue.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'bokehred.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'bokehgreen.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'bokehoriginal.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'roughblack.JPG',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'standardheader.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'Unsplash-01.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'Unsplash-02.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'Unsplash-03.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'Unsplash-04.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'Unsplash-06.jpg',
				),
				array(
					'type' => 'item',
					'group' => 'images',
					'file' => 'Unsplash-09.jpg',
				),
			);
			
			$user_list = self::get_user_image_list();
			
			if ($user_list != null) 
			{
				/* $list indexes are numeric so use array_values() */
				$list = array_merge(array_values($user_list), $list);
			}
		
			$list_full = $list;
			$list = array();
			$pass = true;
			
			foreach ($list_full as $item) 
			{
				if (!isset($item['name']) && isset($item['file']))
				{
					$item['name'] = $item['file'];
				}
				
				if (!isset($item['thumbnail']) && isset($item['file']))
				{
					$item['thumbnail'] = self::get_image_thumb_name($item['file']);
				}
				
				if ($filter != null)
				{
					$pass = false;
				
					foreach ($filter as $filter_prop => $filter_value) 
					{
						if (isset($item[$filter_prop]))
						{
							if (is_array($filter_value))
							{
								$pass = in_array($item[$filter_prop], $filter_value);
							}
							else if ($item[$filter_prop] == $filter_value)
							{
								$pass = true;
							}
						}
					}
				}
				
				if ($filter_exclude != null)
				{
					$pass = false;
					
					foreach ($filter_exclude as $filter_prop => $filter_value) 
					{
						if (!isset($item[$filter_prop]))
						{
							$pass = true;
						}
						else
						{
							if (is_array($filter_value))
							{
								$pass = !in_array($item[$filter_prop], $filter_value);
							}
							else if ($item[$filter_prop] != $filter_value)
							{
								$pass = true;
							}
						}
					}
				}
				
				if ($pass)
				{
					$list[] = $item;
				}
			}
			
			return $list;
		}
		
		static function get_user_image_list()
		{
			$image_list_json = get_option('photocrati-theme-image-list', '');
			$image_list = json_decode($image_list_json, true);
			
			if ($image_list != null) 
			{
				foreach ($image_list as $name => $item)
				{
					$item['name'] = $name;
					$item['type'] = 'media';
					$item['group'] = 'user';
					$item['absolute-path'] = true;
					
					if (is_numeric($name) && intval($name) > 0) 
					{
						$id = intval($name);
					
						$attachment = get_post($id);
						
						if ($attachment != null)
						{
							$item['label'] = $attachment->post_title;
						}
						
						$attachment_src = wp_get_attachment_image_src($id, 'full');
						
						if ($attachment_src != null)
						{
							$item['file'] = $attachment_src[0];
						}
						
						$attachment_src = wp_get_attachment_image_src($id, 'thumbnail');
						
						if ($attachment_src != null)
						{
							$item['thumbnail'] = $attachment_src[0];
						}
					}
				
					$image_list[$name] = $item;
				}
			}
			
			return $image_list;
		}
		
		static function set_user_image_list($image_list)
		{
			$image_list = array_filter($image_list);
			$image_list_json = json_encode($image_list);
			
			update_option('photocrati-theme-image-list', $image_list_json);
		}
		
		static function image_list_add($name, $item)
		{
			$image_list = self::get_user_image_list();
			
			if ($image_list == null) {
				$image_list = array();
			}
			
			$image_list[$name] = $item;
			
			self::set_user_image_list($image_list);
		}
		
		static function image_list_remove($name)
		{
			$image_list = self::get_user_image_list();
			
			if ($image_list != null && isset($image_list[$name])) {
				unset($image_list[$name]);
			}
			
			self::set_user_image_list($image_list);
		}
		
		static function get_image_thumb_name($file_name)
		{
			if (!is_numeric($file_name) && strpos($file_name, '_thumb') === false) {
				$path_local = get_template_directory() . str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, '/images/uploads/');
				$file_parts = explode('.', $file_name);
				$parts_len = count($file_parts);
				$parts_idx = $parts_len >= 2 ? $parts_len - 2 : 0;
			
				$file_parts[$parts_idx] .= '_thumb';
			
				$file_local = implode('.', $file_parts);
			
				if (file_exists($path_local . $file_local)) {
					$file_name = $file_local;
				}
			}
			
			return $file_name;
		}
		
		static function get_image_url($setting_value, $absolute = false, $size_name = null)
		{
			$default_path = '../images/uploads/';
			$preset = Photocrati_Style_Manager::get_active_preset();
			$return = null;
			
			if ($size_name == null) {
				$size_name = 'full';
			}
			
			if ($absolute) {
				$default_path = get_bloginfo('template_directory') . '/images/uploads/';
			}
			
			if ($setting_value != null) {
				if (is_numeric($setting_value)) {
					if (intval($setting_value) > 0) {
						$image_list = self::get_image_list(array('name' => $setting_value));
					
						if ($image_list != null) {
							$setting_attachment = wp_get_attachment_image_src(intval($setting_value), $size_name);
						
							if ($setting_attachment != null) {
								$return = $setting_attachment[0];
							}
						}
					}
				}
				else if (is_scalar($setting_value)) {
					$file_name = $setting_value;
					
					// bit of a tiny hack, given we have no information on the list item, just the value, try to guess the thumb location, this is not really used atm anyway
					if ($size_name == 'thumbnail') {
						$file_name = self::get_image_thumb_name($file_name);
					}
					
					$return = $default_path . $file_name;
				}
			}
			
			return $return;
		}
		
		static function get_setting_image_url($setting_name, $absolute = false, $size_name = null)
		{
			$preset = Photocrati_Style_Manager::get_active_preset();
			$setting_value = $preset->$setting_name;
			
			return self::get_image_url($setting_value, $absolute, $size_name);
		}
		
		static function get_font_rule_list()
		{
			$preset = Photocrati_Style_Manager::get_active_preset();
			extract($preset->to_array());
			
			/* trying to deduce a point size based on a pixel size in order to generate a valid multiplier / factor for high density displays */
			$font_size_list = array(
				array(
					'selector' => 'h1', 
					'option_name' => 'h1_size', 
					'value' => $h1_size),
				array(
					'selector' => 'h2', 
					'option_name' => 'h2_size', 
					'value' => $h2_size),
				array(
					'selector' => 'h3', 
					'option_name' => 'h3_size', 
					'value' => $h3_size),
				array(
					'selector' => 'h4', 
					'option_name' => 'h4_size', 
					'value' => $h4_size),
				array(
					'selector' => 'h5', 
					'option_name' => 'h5_size', 
					'value' => $h5_size),
				array(
					'selector' => '.photocrati-menu a', 
					'option_name' => 'menu_font_size', 
					'value' => $menu_font_size),
				array(
					'selector' => '.photocrati-menu ul li ul li a:link,
					.photocrati-menu ul li ul li a:visited,
					.photocrati-menu ul li.current_page_item ul li a:link,
					.photocrati-menu ul li.current_page_item ul li a:visited', 
					'option_name' => 'submenu_font_size', 
					'value' => $submenu_font_size),
				array(
					'selector' => '.footer_menu ul li', 
					'option_name' => '', 
					'value' => '14'),
				array(
					'selector' => '#branding h1', 
					'option_name' => 'title_size', 
					'value' => $title_size),
				array(
					'selector' => '#branding .description', 
					'option_name' => 'description_size', 
					'value' => $description_size),
				array(
					'selector' => '#content, #content-sm', 
					'option_name' => 'font_size', 
					'value' => $font_size),
				array(
					'selector' => '.entry-meta,
					.entry-utility', 
					'option_name' => 'font_size', 
					'value' => $font_size),
				array(
					'selector' => '.entry-meta,
					.entry-utility', 
					'option_name' => '', 
					'value' => '10'),
				array(
					'selector' => 'p#comment-notes', 
					'option_name' => '', 
					'value' => '13'),
				array(
					'selector' => 'div.comment-meta a.commentauthor:link, a.commentauthor:visited', 
					'option_name' => '', 
					'value' => '18'),
				array(
					'selector' => 'div.comment-meta .commentdate', 
					'option_name' => '', 
					'value' => '14'),
				array(
					'selector' => 'div.comment-meta .commentpermalink', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '.comment-reply-link', 
					'option_name' => '', 
					'value' => '14'),
				array(
					'selector' => 'div#cancel-comment-reply, span.loggedin, span.logout', 
					'option_name' => '', 
					'value' => '13'),
				array(
					'selector' => '#comments div#form-section-comment textarea', 
					'option_name' => '', 
					'value' => '14'),
				array(
					'selector' => '#comments input#submit', 
					'option_name' => '', 
					'value' => '13'),
				array(
					'selector' => '#sidebar', 
					'option_name' => 'sidebar_font_size', 
					'value' => $sidebar_font_size),
				array(
					'selector' => '.widget-title,.widgettitle', 
					'option_name' => 'sidebar_title_size', 
					'value' => $sidebar_title_size),
				array(
					'selector' => '.widget_nav_menu ul.menu li a:link, .widget_nav_menu ul.menu li a:visited, .widget_nav_menu ul.menu li .current_page_item a:visited, .widget_nav_menu ul.menu li a:hover', 
					'option_name' => 'sidebar_font_size', 
					'value' => $sidebar_font_size),
				array(
					'selector' => '#searchform input#s', 
					'option_name' => '', 
					'value' => '16'),
				array(
					'selector' => '.galleria-info-title', 
					'option_name' => 'font_size', 
					'value' => $font_size),
				array(
					'selector' => '.galleria-info-description', 
					'option_name' => 'font_size', 
					'value' => $font_size),
				array(
					'selector' => '.ecommerce .meta_wrapper .quantity input', 
					'option_name' => '', 
					'value' => '11'),
				array(
					'selector' => '.addto button, button.addto, button#addto, button#addto2, button#addto3', 
					'option_name' => '', 
					'value' => '11'),
				array(
					'selector' => '.cart_header', 
					'option_name' => '', 
					'value' => '13'),
				array(
					'selector' => '.cart_qty, .cart_desc, .cart_amt, .cart_line', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '.cart_qty input', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '.cart_line input', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '.cart_total', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '.cart_total_amount', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '.cart_total_amount input', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '#cart_widget', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '#shopping_cart_wrapper .cart_contents .cart_qty,
					#shopping_cart_wrapper .cart_contents .cart_desc,
					#shopping_cart_wrapper .cart_contents .cart_amt,
					#shopping_cart_wrapper .cart_contents .cart_line', 
					'option_name' => '', 
					'value' => '13'),
				array(
					'selector' => '#shopping_cart_wrapper .cart_total_wrapper .cart_total', 
					'option_name' => '', 
					'value' => '13'),
				array(
					'selector' => '#shopping_cart_wrapper .cart_total_wrapper .cart_total_amount', 
					'option_name' => '', 
					'value' => '13'),
				array(
					'selector' => '#shopping_cart_wrapper .cart_total_wrapper .cart_total_amount input', 
					'option_name' => '', 
					'value' => '13'),
				array(
					'selector' => '.checkout_footer', 
					'option_name' => '', 
					'value' => '12'),
				array(
					'selector' => '.footer-widget-area', 
					'option_name' => 'footer_font', 
					'value' => $footer_font),
				array(
					'selector' => '.footer-widget-container .widget-title', 
					'option_name' => 'footer_widget_title', 
					'value' => $footer_widget_title),
				array(
					'selector' => '#footer', 
					'option_name' => 'footer_font', 
					'value' => $footer_font),
				array(
					'selector' => '#footer #site-info', 
					'option_name' => '', 
					'value' => '14'),
			);
			
			return $font_size_list;
		}
		
		static function build_font_list_stylesheet($zoom = 1)
		{
			$font_size_list = self::get_font_rule_list();
			$factor = (72 / 96) * $zoom;
			$output = null;

			foreach ($font_size_list as $font_item) {
				$selector = $font_item['selector'];
				$selectors = explode(',', $selector);
				$selectors = array_map('trim', $selectors);
				$selector = implode(',' . "\n", $selectors);
				
				$font_zoom = isset($font_item['zoom']) ? $font_item['zoom'] : 1;
				
				$output .= $selector;
				$output .= ' {' . "\n";
				$output .= 'font-size: ' . round((((float) $font_item['value']) * $factor) * $font_zoom, 2) . "pt;\n";
				$output .= '}' . "\n";
			}
			
			return $output;
		}
		
    static function are_dynamic_styles_enabled()
    {
        return self::get_active_preset()->dynamics_styles;
    }

    static function enable_dynamic_stylesheet()
    {
        $preset = self::get_active_preset();
        $preset->dynamic_style = TRUE;
        $preset->save(PHOTOCRATI_PRESET_PREFIX);
    }

    static function disable_dynamic_stylesheet()
    {
        $preset = self::get_active_preset();
        $preset->dynamic_style = FALSE;
        $preset->save(PHOTOCRATI_PRESET_PREFIX);
    }

	static function generate_static_stylesheet()
	{
		$retval         = FALSE;

		// Fetch the dynamic stylesheet
		$file = get_template_directory_uri() . '/styles/dynamic-style.php';
		if (!($response = wp_remote_get($file)) instanceof WP_Error) {
			$contents = wp_remote_retrieve_body($response);

			// Write the dynamic stylesheet to a static file
			if ($contents) {
				$newfile = implode(DIRECTORY_SEPARATOR, array(
					get_stylesheet_directory(),
					'styles',
					'style.css'
				));

				if (is_writable($newfile)) {
					$wrote = @file_put_contents($newfile, $contents);
					if ($wrote > 0) $retval = TRUE;
				}

			}
		}

		return $retval;
	}

    /**
     * Sets the specified as the active preset for the theme
     * @param $preset_name
     */
    static function set_active_preset($preset_name)
    {
        $preset = self::get_preset($preset_name);
        if (!$preset->is_new()) $preset->set_as_active();
    }
    
    static function get_default_presets_display_order()
    {
    	return array('preset-transparency', 'preset-transparency-white', 'preset-overexposed', 'preset-overexposed-white', 'preset-underexposed', 'preset-underexposed-white', 'preset-sidewinder', 'preset-sidewinder-dark', 'preset-sidewinder-full', 'preset-contrast', 'preset-contrast-dark', 'preset-contrast-red', 'preset-contrast-yellow', 'preset-texture', 'preset-texture-blue', 'preset-texture-green', 'preset-bare-bulb', 'preset-bare-bulb-barndoors', 'preset-scout', 'preset-street-side', 'preset-city', 'preset-bold', 'preset-obscura', 'preset-print', 'preset-street', 'preset-autofocus', 'preset-autofocus-black-orange', 'preset-autofocus-black-yellow', 'preset-autofocus-black-blue', 'preset-autofocus-blue', 'preset-autofocus-yellow', 'preset-backlight-blue', 'preset-backlight-green', 'preset-backlight-yellow', 'preset-catchlight', 'preset-catchlight-blue', 'preset-catchlight-yellow', 'preset-canvas', 'preset-emulsion', 'preset-fstop', 'preset-lightbox', 'preset-ten-stop', 'preset-bokeh', 'preset-darkroom', 'preset-exposure', 'preset-filter', 'preset-polarized', 'preset-prime', 'preset-rangefinder', 'preset-silverhalide', 'preset-vignette', 'preset-wideangle', 'preset-signature');
    }

    /**
     * Generates an index of presets in the database
     * @param bool $new_name
     * @param bool $third_party
     * @return array
     */
    static function _generate_index($new_name=FALSE, $third_party=FALSE)
    {
        $presets = get_option('photocrati_presets', array());
        if (!$presets && self::legacy_table_exists()) {
            global $wpdb;
            $rows = $wpdb->get_results("SELECT preset_name, custom_setting FROM {$wpdb->prefix}photocrati_presets", ARRAY_A);
            foreach ($rows as $row) {
                $name           = $row['preset_name'];
                $is_third_party = $row['custom_setting'];
                $presets[$name] = $is_third_party;
            }
        }
        if ($new_name) {
        	$legacy_presets = array('preset-canvas', 'preset-emulsion', 'preset-fstop', 'preset-lightbox', 'preset-ten-stop', 'preset-bokeh', 'preset-darkroom', 'preset-exposure', 'preset-filter', 'preset-polarized', 'preset-prime', 'preset-rangefinder', 'preset-silverhalide', 'preset-vignette', 'preset-wideangle', 'preset-signature');
        	$legacy_map = array();
          
          foreach ($legacy_presets as $legacy_name) {
          	if (isset($presets[$legacy_name])) {
          		$legacy_map[$legacy_name] = $presets[$legacy_name];
          		unset($presets[$legacy_name]);
          	}
          }
      		
      		if (isset($presets[$new_name])) {
      			unset($presets[$new_name]);
      		}
      		
          $presets[$new_name] = $third_party;
          
          foreach ($legacy_map as $legacy_name => $legacy_value) {
          	$presets[$legacy_name] = $legacy_value;
          }
        }

        update_option('photocrati_presets', $presets);

        return $presets;
    }

    static function import_preset($filename, $third_party=TRUE)
    {
        $retval = FALSE;
        $contents = @file_get_contents($filename);
        if ($contents) {
            $settings = (array) json_decode($contents);
            if ($settings) {
            		// exception for menus in old presets exports as the default is Legacy and we want default when importing
            		if (!isset($settings['menu_skin'])) {
            			$settings['menu_skin'] = 'default';
            		}
            		// old presets exported before the rewrite, better to have custom css that potentially requires tweaking than nothing
            		if (!isset($settings['custom_css_modern']) && isset($settings['custom_css'])) {
            			$settings['custom_css_modern'] = $settings['custom_css'];
            		}
            		
                $preset = new Photocrati_Style_Manager($settings);
                $preset->is_third_party = $third_party;
                $preset->save();
                $retval = TRUE;
            }
        }

        return $retval;
    }

    static function legacy_table_exists()
    {
        global $wpdb;
        return is_object($wpdb->get_row("SHOW TABLES LIKE '{$wpdb->prefix}photocrati_presets'"));
    }


    /**
     * Returns the inner array
     * @return array
     */
    function to_array()
    {
        $retval = array();
        foreach ($this->_settings as $key => $value) {
            if ($value == 'YES')    $value = TRUE;
            elseif ($value == 'NO') $value = FALSE;
            elseif ($value == 'ON') $value = TRUE;
            elseif ($value == 'OFF')$value = FALSE;
            $retval[$key] = $value;
        }
        return $retval;
    }

    static function get_all_presets($return_third_party=TRUE)
    {
        $retval = array();
        foreach (self::_generate_index() as $preset_name => $third_party) {
            if (($return_third_party == FALSE && $third_party == FALSE) OR $return_third_party == TRUE) {
                $retval[$preset_name] = self::get_preset($preset_name);
            }
        }

        return $retval;

    }

    /**
     * Returns only third party presets
     * @return array
     */
    static function get_all_third_party_presets()
    {
        $retval = array();
        foreach (self::_generate_index() as $preset_name => $third_party) {
            if ($third_party) {
                $retval[] = self::get_preset($preset_name);
            }
        }

        return $retval;
    }


    /**
     * Gets a named group of style settings
     * @param $preset_name
     */
    static function get_preset($preset_name)
    {
        global $wpdb;

        $retval = array();

        // Clean the name
        if ($preset_name != PHOTOCRATI_ACTIVE_PRESET) {
            $preset_name = preg_replace("/\s+/", '_', $preset_name);
            while (TRUE) {
                if (preg_match("/[^\w-_]+/", $preset_name, $match)) {
                    $preset_name = str_replace($match[0], '', $preset_name);
                }
                else break;
            }
        }

        // Have we already retrieved the preset? If so,
        // return it from the cache
        if (isset(self::$_cache[$preset_name])) {
            $retval = self::$_cache[$preset_name];
        }

        // We need to find the preset and it's settings
        else {
            // First try to locate the preset as a WP Option
            if (($settings = get_option(PHOTOCRATI_PRESET_PREFIX.$preset_name))) {
                $retval = $settings;
            }

            // We're still using the old Photocrati tables method. The active preset
            // is in the photocrati_styles table, whereas all others are in
            // photocrati_presets table

            // Are we asking for the active preset?
            elseif (self::legacy_table_exists()) {

                if ($preset_name == PHOTOCRATI_ACTIVE_PRESET) {
                    $retval = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}photocrati_styles LIMIT 1", ARRAY_A);
                }

                // Are we asking for an inactive preset?
                else {
                    $retval = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}photocrati_presets WHERE preset_name = %s",
                        $preset_name
                    ), ARRAY_A);
                }
            }

            // Cache the preset
            self::$_cache[$preset_name] = $retval;
        }

        // Wrap the array as an object
        $klass = get_class();
        $retval = new $klass($retval);

        return $retval;
    }

    /**
     * Returns the settings for the active preset
     * @return Photocrati_Style_Manager
     */
    static function get_active_preset()
    {
        return self::get_preset(PHOTOCRATI_ACTIVE_PRESET);
    }

    static function clone_active_as($title, $third_party=FALSE)
    {
        $name   = sanitize_title_with_dashes($title);
        $new_preset     = self::get_preset($name);
        $active_preset  = self::get_active_preset();

        foreach ($active_preset->to_array() as $key => $val) {
            $new_preset->$key = $val;
        }
        $new_preset->title = $title;
        $new_preset->name  = $name;
        $new_preset->is_third_party = $third_party;
        $new_preset->save();
    }

    /**
     * Determines whether the sidebar has been enabled for the active preset
     * @return mixed
     */
    static function is_sidebar_enabled()
    {
        return self::get_active_preset()->is_sidebar_enabled;
    }

    static function delete_preset($name)
    {
        delete_option(PHOTOCRATI_PRESET_PREFIX.$name);
        $presets = self::_generate_index();
        unset($presets[$name]);
        update_option('photocrati_presets', $presets);
    }
    
    static function create_value_code($prop_value)
    {
			$prop_value_out = '\'' . $prop_value . '\'';
			
			if (is_bool($prop_value)) {
				$prop_value_out = $prop_value ? 'TRUE' : 'FALSE';
			}
			else if (is_int($prop_value) || is_float($prop_value) || is_double($prop_value)) {
				$prop_value_out = $prop_value;
			}
			else if (is_array($prop_value) || is_object($prop_value)) {
				$prop_value_out = 'array(';
				
				foreach ($prop_value as $prop_in_name => $prop_in_value) {
					if (is_string($prop_in_name)) {
						$prop_value_out .= ' ' . $prop_in_name . ' => ' . self::create_value_code($prop_in_value) . ',';
					}
					else {
						$prop_value_out .= ' ' . self::create_value_code($prop_in_value) . ',';
					}
				}
				
				$prop_value_out .= ')';
			}
			
			return $prop_value_out;
    }
    
    static function create_preset_code($filename)
    {
			$out = null;
    	$content = file_get_contents($filename);
    	
    	if ($content != null) {
    		$json = json_decode($content);
    		
    		if ($json) {
        	$defaults = self::get_default_list();
    			$out .= 
'
        $preset = new Photocrati_Style_Manager(array(';
        
    			foreach ($json as $prop_name => $prop_value) {
		  				if ($prop_name == 'is_third_party') {
		  					$prop_value = false;
		  				}
		  				else if ($prop_name == 'name') {
		  					$prop_value = strtolower($prop_value);
		  					
		  					if (substr($prop_value, 0, strlen('preset-')) != 'preset-') {
		  						$prop_value = 'preset-' . $prop_value;
		  					}
		  					if (substr($prop_value, -strlen('-new')) == '-new') {
		  						$prop_value = substr($prop_value, 0, -strlen('-new'));
		  					}
		  					
		  					$prop_value = str_ireplace('photocrati ', '', $prop_value);
		  					$prop_value = str_ireplace('blue-black', 'black-blue', $prop_value);
		  					$prop_value = str_ireplace('catch-light', 'catchlight', $prop_value);
		  				}
		  				else if ($prop_name == 'title') {
		  					if (substr(strtolower($prop_value), -strlen(' new')) == ' new') {
		  						$prop_value = substr($prop_value, 0, -strlen(' new'));
		  					}
		  					
		  					$prop_value = str_ireplace('photocrati ', '', $prop_value);
		  					$prop_value = str_ireplace('Blue Black', 'Black Blue', $prop_value);
		  					$prop_value = str_ireplace('Catch Light', 'Catchlight', $prop_value);
		  				}
		  				
    					$prop_value_out = self::create_value_code($prop_value);
    					
    					$out .= '
            \'' . $prop_name . '\'   => ' . $prop_value_out . ',';
    			}
    			
    			$out .= 
'
        ));
        $preset->save();
';
    		}
    	}
    	
    	return $out;
    }

    static function init()
    {
        // Get any presets stored from legacy tables
        self::_generate_index();
        
        Photocrati_Preset_Static::load_default_presets();

        // Import any presets bundled with the theme -- commented out as default presets are always loaded from code, safer and more reliable
#        $preset_dir = get_template_directory()."/admin/presets";
#        $presets = glob("{$preset_dir}/*.json");
#        $presets = array_merge($presets, glob("{$preset_dir}/*.crati"));
#        foreach ($presets as $filename) Photocrati_Style_Manager::import_preset($filename, FALSE);

        // Set the default preset
        $active = Photocrati_Style_Manager::get_preset(PHOTOCRATI_ACTIVE_PRESET);
        if ($active->is_new()) Photocrati_Style_Manager::set_active_preset('preset-sidewinder-full');
    }
}
