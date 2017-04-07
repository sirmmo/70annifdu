<?php

class Photocrati_Ecommerce_Options
{
    static $_instance   = NULL;
    var $options        = array();
    var $wp_option_name = 'photocrati_ecomm_options';

    /**
     * @return Photocrati_Ecommerce_Options
     */
    static function get_instance()
    {
        if (is_null(self::$_instance)) {
            $klass = get_class();
            self::$_instance = new $klass();
        }
        return self::$_instance;
    }

    static function render_option_field($number=1, $name='', $value='')
    {
        include(get_template_directory().'/admin/templates/ecommerce_option.php');
    }

    static function render_option_field_list($minimum_options=50)
    {
        $obj = self::get_instance();
        $options = $obj->options;
        while (count($options) < $minimum_options) {
            $options[] = array(
                'option_name'   =>  '',
                'option_value'  =>  ''
            );
        }

        foreach ($options as $key => $params) {
            self::render_option_field($key, $params['option_name'], $params['option_value']);
        }
    }

    private function __construct()
    {
        // Load the persisted options
        $this->options = get_option($this->wp_option_name, NULL);
        // Get legacy way of storing e-commerce options
        if (is_null($this->options)) {
            global $wpdb;
            $row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_ecommerce_settings WHERE id = 1", ARRAY_A);
            $options = array();
            for ($i=1; $i<=20; $i++) {
                if (isset($row["ecomm_op{$i}"])) {
                    $options[$i] = array(
                      'option_name'     =>  $row["ecomm_op{$i}"],
                      'option_value'    =>  $row["ecomm_cost{$i}"]
                    );
                }
            }
            $this->options = $options;
        }
    }

    static function update($options)
    {
        // Perform some validation
        $obj = self::get_instance();
        foreach ($options as $key => $option) {
            if (is_int($key)) {

                // Create a new option if it doesn't exist
                if (!isset($obj->options[$key])) {
                    $obj->options[$key] = array();
                }

                // Set the option parameters
                foreach (array('option_name', 'option_value') as $param) {
                    if (isset($option[$param])) $obj->options[$key][$param] = stripslashes($option[$param]);
                }
            }
        }
        update_option($obj->wp_option_name, $obj->options);
    }

    function get_unit_price_for($option_id)
    {
        $unit_price = FALSE;
        if (isset($this->options[$option_id])) {
            $unit_price = floatval($this->options[$option_id]['option_value']);
        }
        return $unit_price;
    }
}
