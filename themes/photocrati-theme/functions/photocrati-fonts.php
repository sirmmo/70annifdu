<?php

class Photocrati_Fonts
{
    static $_instance = NULL;
    var $_transient_name = 'photocrati_fonts';
    var $google_fonts = array();
    var $other_fonts  = array();
    var $universal_font_names = array(
        'Arial'             =>  array('Arial', 'Helvetica', 'sans-serif'),
        'Times New Roman'   =>  array('Times New Roman', 'Times', 'serif'),
        'Verdana'           =>  array('Verdana', 'Arial', 'Helvetica', 'sans-serif'),
        'Geneva'            =>  array('Geneva',  'Arial', 'Helvetica', 'sans-serif'),
        'Georgia'           =>  array('Georgia', 'Times New Roman', 'Times', 'serif'),
        'Times'             =>  array('Times', 'Times New Roman', 'serif'),
        'Helvetica'         =>  array('Helvetica', 'Arial', 'sans-serif'),
        'Courier'           =>  array('Courier', 'monospace', 'sans-serif'),
        'Courier New'       =>  array('Courier New', 'Courier', 'monospace', 'sans-serif'),
        'Trebuchet'         =>  array('Trebuchet', 'Tahoma', 'Helvetica', 'sans-serif'),
        'Tahoma'            =>  array('Tahoma', 'Trebuchet', 'Helvetica', 'sans-serif'),
        'Lucida'            =>  array('Lucida', 'Lucida Grande', 'Lucida Sans', 'Lucida Sans Unicode', 'sans-serif'),
    );

    static function get_instance()
    {
        if (is_null(self::$_instance)) {
            $klass = get_class();
            self::$_instance = new $klass();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->_fetch_fonts();
    }

    static function migrate_old_value($old_value)
    {
        // Migrate old values to new values
        $old_value = stripslashes($old_value);
        $old_value = array_shift(explode(',', $old_value));
        if (preg_match("/^'?G\s-\s([^']*)'?$/", $old_value, $match)) {
            $old_value = $match[1];
			$old_value = array_shift(explode(':', $old_value));
        }

        // Manual translations
        if (preg_match("/Lucida/i", $old_value)) {
            $old_value = 'Lucida';
        }
        return ucfirst($old_value);
    }

    /**
     * Gets the CSS value for a font
     * @param $font_name
     * @return string
     */
    static function get_css_value($font_name)
    {
        $font = NULL;
        $obj  = self::get_instance();
        $font_name = self::migrate_old_value($font_name);
        if (isset($obj->google_fonts[$font_name])) {
            $font = $obj->google_fonts[$font_name];
        }
        elseif (isset($obj->other_fonts[$font_name])) {
            $font = $obj->other_fonts[$font_name];
        }

        if (!is_null($font)) return $font->value;
        else return 'serif';
    }

    static function render_google_font_link($fonts=array())
    {
        $obj = self::get_instance();
        $loadable_fonts = array();
        foreach ($fonts as $font_name) {
            $font_weight = FALSE;
            $font_style  = FALSE;
            $debug       = FALSE;

            // If an array was passed, we're requesting a font
            // and a variant or two (bold/italic)
            if (is_array($font_name)) {
                $font_options = $font_name;
                $font_name    = array_shift($font_options);
                $font_weight  = array_shift($font_options);
                $font_style   = array_shift($font_options);
                $debug = TRUE;
            }

            // Migrate the old value to a new font name
            $font_name = self::migrate_old_value($font_name);

            // We only need to generate a url for Google fonts, so
            // check that the user has actually requested a Google Font
            if (isset($obj->google_fonts[$font_name])) {

                // Fetch the google font
                $google_font = clone $obj->google_fonts[$font_name];

                // Attach some meta data so that the Google Font Group object
                // knows what variants we want
                $google_font->requested_weight = $font_weight;
                $google_font->requested_style  = $font_style;
                $loadable_fonts[] = $google_font;
            }
        }

        $font_group = new Photocrati_Google_Font_Group(0, 0, $loadable_fonts);
        $url = esc_attr($font_group->url());
        echo "<link rel='stylesheet' type='text/css' href='{$url}'/>";
    }

    static function render_font_window($field_name, $current_value)
    {
        $font_group     = new Photocrati_Google_Font_Group();
        $id             = uniqid('font-window');
        $obj            = self::get_instance();
        $other_fonts    = new Photocrati_Google_Font_Group(0, 0, $obj->other_fonts);
        $current_value  = self::migrate_old_value($current_value);

        include(get_template_directory().'/admin/templates/font-window.php');
    }

    /**
     * Renders all fields related to Fonts
     * @param $label
     * @param $params
     */
    static function render_font_fields($label, $params)
    {
        $keys = array_keys($params);
        $valid_keys = array(
            'font_family_field_name',
            'font_family_value',
            'font_size_field_name',
            'font_size_value',
            'font_color_field_name',
            'font_color_value',
            'font_weight_field_name',
            'font_weight_value',
            'font_italics_field_name',
            'font_italics_value',
            'font_decoration_field_name',
            'font_decoration_value',
            'font_case_field_name',
            'font_case_value'
        );

        // Ensure that all needed params are preset
        $error = FALSE;
        foreach ($valid_keys as $key) {
            if (!in_array($key, $keys)) {
                $error = TRUE;
                echo "<p>Missing {$key}</p>";
                break;
            }
        }

        // If no error, then we can include our PHP template to render
        // all font fields
        if (!$error) {
            extract($params);
            include(get_template_directory().'/admin/templates/font-field.php');
        }
    }

    static function get_google_fonts_url()
    {
        $font_group = new Photocrati_Google_Font_Group();
        return $font_group->url();
    }

    function _fetch_fonts()
    {
        $this->google_fonts = get_transient($this->_transient_name, array());
        if (!$this->google_fonts) {
            $dir = get_template_directory().'/functions';
            $font_file = 'photocrati_fonts.json';
            if (!defined('PHOTOCRATI_ALL_GOOGLE_FONTS')) {
                define('PHOTOCRATI_ALL_GOOGLE_FONTS', false);
            }
            if (PHOTOCRATI_ALL_GOOGLE_FONTS) $font_file = 'webfonts.json';
            $webfonts_filename = path_join($dir, $font_file);

            // Load font list from file
            $json = file_get_contents($webfonts_filename);
            if (!$json) $json = wp_remote_fopen("https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBCVupcvMiOIAegMfA-a5DrH39dygUcPks");
            if ($json) {
                $google_fonts = json_decode($json);
                $google_fonts = $google_fonts->items;

                // Organize the google fonts as an associative array, and
                // include a failback font
                foreach ($google_fonts as $font) {
                    if (!isset($font->value)) {
                        $font->value = "'{$font->family}', serif";
                    }
                    $font->google = TRUE;
                    $this->google_fonts[$font->family] = $font;
                }

                set_transient($this->_transient_name, $this->google_fonts, 3600);
            }
        }

        // The following are not Google Fonts, but almost universally available
        foreach ($this->universal_font_names as $font_family => $css_values) {
            if (!isset($this->other_fonts[$font_family])) {
                $font           = new stdClass;
                $font->family   = $font_family;
                $font->kind     = 'builtin';
                $font->variants = array('regular', 'bold', 'italic');
                $font->google   = FALSE;
                $font->subsets  = array('latin');
                $values = array();
                foreach ($css_values as $value) $values[] = "'{$value}'";
                $font->value = implode(',', $values);
                $this->other_fonts[$font_family] = $font;
            }
        }
    }
}

class Photocrati_Google_Font_Group
{
    var $offset         = 0;
    var $font_count     = 100;
    var $fonts          = array();
    var $loadable_fonts = array();

    function __construct($offset=0, $font_count=100, $loadable_fonts=array())
    {
        if ($font_count == 0) $font_count = PHP_INT_MAX;

        $this->offset       = $offset;
        $this->font_count   = $font_count;

        // If no loadable fonts have been specified, then we'll assume
        // that we're to use all Google Fonts
        if (!$loadable_fonts) {
            $photocrati_fonts = Photocrati_Fonts::get_instance();
            $loadable_fonts = $photocrati_fonts->google_fonts;
        }
        elseif(!is_array($loadable_fonts)) $loadable_fonts = array($loadable_fonts);
        $this->loadable_fonts = $loadable_fonts;

        // Get fonts
        $this->fonts = array();
        foreach (array_slice($loadable_fonts, $offset, $font_count, TRUE) as $font_family => $font) {
            $this->fonts[$font_family] = $font;
        }
    }

    function id()
    {
        return 'font-group-'.$this->offset.'-'.$this->font_count;
    }

    // Get the next group of fonts
    function next()
    {
        $offset = $this->offset += $this->font_count;
        $klass = get_class();
        return new $klass($offset, $this->font_count, $this->loadable_fonts);
    }

    function is_empty()
    {
        return $this->count() <= 0;
    }

    function count()
    {
        return count($this->fonts);
    }

    function url()
    {
        // Generate a list of fonts to request
        $fonts = array();
        foreach ($this->fonts as $font) {
            $fonts[] = $this->create_font_request_param($font, FALSE);
            $fonts[] = $this->create_font_request_param($font, TRUE);
        }
        $fonts = array_unique($fonts);
        
        $is_ssl = is_ssl();
        
        if (!$is_ssl && isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        	$is_ssl = true;
        }
        
        // Create the request url
        $url = ($is_ssl ? 'https' : 'http') . '://fonts.googleapis.com/css?family=';
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
            $url = site_url().'?load_google_fonts&family=';
        }
        
        return $url.implode('|', $fonts);
    }

    function create_font_request_param($font, $with_variants=TRUE)
    {
        $retval = urlencode($font->family);

        if ($with_variants) {
            if (!isset($font->requested_weight)) $font->requested_weight = FALSE;
            if (!isset($font->requested_style))  $font->requested_style  = FALSE;

            if ($font->requested_weight || $font->requested_style) {
                $variants = array();
                $skip     = FALSE;
                if ($font->requested_weight && $font->requested_weight != 'normal') {
                    if (strpos(':', $retval) === FALSE) $retval .= ':';
                    $variants[] = $font->requested_weight;
                }
                else $skip = TRUE;

                if ($font->requested_style && $font->requested_style != 'normal') {
                    if (strpos(':', $retval) === FALSE) $retval .= ':';
                    $variants[] = $font->requested_style;
                }
                else $skip = TRUE;

                if (!$skip AND $font->requested_weight && $font->requested_style) {
                    $variants[] = $font->requested_weight.$font->requested_style;
                }

                $retval .= implode(',', $variants);
            }
        }

        return $retval;
    }

    function get_fonts()
    {
        return $this->fonts;
    }

    function render_link_tag()
    {
        $url = esc_attr($this->url());
        echo "<link rel='stylesheet' type='text/css' href='{$url}'/>";
    }

    function render_font_list($id)
    {
        $fonts = $this->get_fonts();
        include(get_template_directory().'/admin/templates/font-list.php');
        if (!$this->is_empty()) {
            $this->next()->render_font_list($id);
        }
    }
}
