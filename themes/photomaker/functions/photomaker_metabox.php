<?php
ob_start();
setcookie("name",
        "value",
        100);
/**
 * Metabox arrays for retrieving
 * Custom values
 */
$key = 'photomaker';
$meta_boxes = array(
    'video_url' => array(
        'name' => 'video_url',
        'type' => 'textarea',
        'title' => __('Embed Video',
                'photomaker'),
        'description' => __('Enter the video Embed Only',
                'photomaker')
    ),
);

/**
 * Function for create meta box
 * @global string $key 
 */
function photomaker_create_meta_box() {
    global $key;
    if (function_exists('add_meta_box'))
        add_meta_box('meta-boxes',
                __('Enter video Embed Code For Blog Post Thumbnail',
                        'photomaker'),
                'photomaker_meta_box',
                'post',
                'normal',
                'high');
}

/**
 * Function for creating UI Meta box
 * @global type $post
 * @global array $meta_boxes
 * @global string $key 
 */
function photomaker_meta_box() {
    global $post, $meta_boxes, $key;
    ?>
    <div class="panel-wrap">	
        <div class="form-wrap">
            <?php
            wp_nonce_field(plugin_basename(__FILE__),
                    $key . '_wpnonce',
                    false,
                    true);
            foreach ($meta_boxes as $meta_box) {
                $data = get_post_meta($post->ID,
                        $meta_box['name'],
                        true);
                ?>
                <div class="form-field form-required" style="margin:0; padding: 0 8px">
                    <?php
                    if ($meta_box['type'] == 'geo_map_input') {
                        
                    } else {
                        ?>
                        <label for="<?php echo $meta_box['name']; ?>" style="color: #666; padding-bottom: 8px; overflow:hidden; zoom:1; "><?php echo $meta_box['title']; ?></label>
                    <?php } ?>
                    <?php
                    if (!isset($meta_box['type']))
                        $meta_box['type'] = 'input';
                    switch ($meta_box['type']) :
                        case "datetime" :
                            if ($post->post_status <> 'publish') :
                                echo '<p>' . __('Post is not yet published',
                                        'photomaker') . '</p>';
                            else :
                                $date = $data;
                                if (!$data) {
                                    // Date is 30 days after publish date (this is for backwards compatibility)
                                    $date = strtotime('+30 day',
                                            strtotime($post->post_date));
                                }
                                ?>							
                                <div style="float:left; margin-right: 10px; min-width: 320px;"><select name="<?php echo $meta_box['name']; ?>_month">
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) :
                                            echo '<option value="' . str_pad($i,
                                                    2,
                                                    '0',
                                                    STR_PAD_LEFT) . '" ';
                                            if (date_i18n('F',
                                                            $date) == date_i18n('F',
                                                            strtotime('+' . $i . ' month',
                                                                    mktime(0,
                                                                            0,
                                                                            0,
                                                                            12,
                                                                            1,
                                                                            2010))))
                                                echo 'selected="selected"';
                                            echo '>' . date_i18n('F',
                                                    strtotime('+' . $i . ' month',
                                                            mktime(0,
                                                                    0,
                                                                    0,
                                                                    12,
                                                                    1,
                                                                    2010))) . '</option>';
                                        endfor;
                                        ?>
                                    </select>
                                    <select name="<?php echo $meta_box['name']; ?>_day">
                                        <?php
                                        for ($i = 1; $i <= 31; $i++) :
                                            echo '<option value="' . str_pad($i,
                                                    2,
                                                    '0',
                                                    STR_PAD_LEFT) . '" ';
                                            if (date_i18n('d',
                                                            $date) == str_pad($i,
                                                            2,
                                                            '0',
                                                            STR_PAD_LEFT))
                                                echo 'selected="selected"';
                                            echo '>' . str_pad($i,
                                                    2,
                                                    '0',
                                                    STR_PAD_LEFT) . '</option>';
                                        endfor;
                                        ?>
                                    </select>
                                    <select name="<?php echo $meta_box['name']; ?>_year">
                                        <?php
                                        for ($i = 2010; $i <= 2020; $i++) :
                                            echo '<option value="' . $i . '" ';
                                            if (date_i18n('Y',
                                                            $date) == $i)
                                                echo 'selected="selected"';
                                            echo '>' . $i . '</option>';
                                        endfor;
                                        ?>
                                    </select>@<input type="text" name="<?php echo $meta_box['name']; ?>_hour" size="2" maxlength="2" style="width:2.5em" value="<?php
                                    echo date_i18n('H',
                                            $date)
                                    ?>" />:<input type="text" name="<?php echo $meta_box['name']; ?>_min" size="2" maxlength="2" style="width:2.5em" value="<?php
                                                     echo date_i18n('i',
                                                             $date)
                                                     ?>" /></div><?php
                                    if ($meta_box['description'])
                                        echo wpautop(wptexturize($meta_box['description']));
                                    ?>
                                <?php
                                endif;
                                break;
                            case "geo_map" :
                                $metaboxvalue = get_post_meta($post->ID,
                                        $meta_box["name"],
                                        true);
                                if ($metaboxvalue == "" || !isset($metaboxvalue)) {
                                    $metaboxvalue = $meta_box['default'];
                                }
                                ?>
                            <div class="row">
                                <p><input id="<?php echo $meta_box['id']; ?>" size="100" style="width:320px; margin-right: 10px; float:left" type="text" value="<?php echo $metaboxvalue; ?>" name="<?php echo $meta_box['name']; ?>"/></p> 
                                <?php
                                include_once(TEMPLATEPATH . "/library/map/address_map.php");
                                echo '<p class="info">' . __('Click on "Set Address on Map" and then you can also drag pinpoint to locate the correct address',
                                        'gc') . '</p>';
                                echo "</div>";
                                break;
                            case "textarea" :
                                ?>
                                <textarea rows="4" cols="40" name="<?php echo $meta_box['name']; ?>" style="width:98%; height:75px; margin-right: 10px; none"><?php echo htmlspecialchars($data); ?> </textarea>
                                <?php
                                if ($meta_box['description'])
                                    echo wpautop(wptexturize($meta_box['description']));
                                break;
                            case "checkbox" :
                                ?>
                                <input style="float:left; width:20px;" type="checkbox" name="<?php echo $meta_box['name']; ?>"/>
                                <label class="check-label"><?php echo $meta_box['label']; ?></label>
                                <?php
                                if ($meta_box['description'])
                                    echo wpautop(wptexturize($meta_box['description']));
                                break;
                            case "geo_map_input" :
                                $ext_script = '';
                                if ($meta_box["id"] == 'geocraft_latitude' || $meta_box["id"] == 'geocraft_langitude') {
                                    $ext_script = 'onblur="changeMap();"';
                                } else {
                                    $ext_script = '';
                                }
                                $defaultvalue = get_post_meta($post->ID,
                                        $meta_box["name"],
                                        true);
                                if ($meta_box['type'] == 'geo_map_input') {
                                    if ($defaultvalue == "" || !isset($defaultvalue)) {
                                        $defaultvalue = $meta_box['default'];
                                    }
                                } else {
                                    $defaultvalue = htmlspecialchars($data);
                                }
                                ?>
                                <input id="<?php echo $meta_box['id']; ?>" type="hidden" style="width:320px; margin-right: 10px; float:left" <?php echo $ext_script; ?> name="<?php echo $meta_box['name']; ?>" value="<?php echo $defaultvalue; ?>" /><?php
                                if ($meta_box['description'])
                                    echo wpautop(wptexturize($meta_box['description']));
                                break;
                            default :
                                ?>
                                <input id="<?php echo $meta_box['id']; ?>" type="text" style="width:320px; margin-right: 10px; float:left" name="<?php echo $meta_box['name']; ?>" value="<?php echo $data; ?>" /><?php
                                if ($meta_box['description'])
                                    echo wpautop(wptexturize($meta_box['description']));
                                ?>
                                <?php
                                break;
                        endswitch;
                        ?>				
                        <div class="clear"></div>
                    </div>
                <?php } ?>
            </div>
        </div>	
        <?php
    }

    /**
     * @global type $post
     * @global array $meta_boxes
     * @global string $key
     * @param type $post_id
     * @return type 
     */
    function photomaker_save_meta_box($post_id) {
        global $post, $meta_boxes, $key, $image_meta, $checkbox_meta;
        if (!isset($_POST[$key . '_wpnonce']))
            return $post_id;
        if (!wp_verify_nonce($_POST[$key . '_wpnonce'],
                        plugin_basename(__FILE__)))
            return $post_id;
        if (!current_user_can('edit_post',
                        $post_id))
            return $post_id;
        foreach ($image_meta as $image_box) {
            update_post_meta($post_id,
                    $image_box['name'],
                    $_POST[$image_box['name']]);
        }
        foreach ($meta_boxes as $meta_box) {
            if ($meta_box['type'] == 'datetime') {
                $year = $_POST[$meta_box['name'] . '_year'];
                $month = $_POST[$meta_box['name'] . '_month'];
                $day = $_POST[$meta_box['name'] . '_day'];
                $hour = $_POST[$meta_box['name'] . '_hour'];
                $min = $_POST[$meta_box['name'] . '_min'];
                if (!$hour)
                    $hour = '00';
                if (!$min)
                    $min = '00';
                if (checkdate($month,
                                $day,
                                $year)) :
                    $date = $year . $month . $day . ' ' . $hour . ':' . $min;
                    update_post_meta($post_id,
                            $meta_box['name'],
                            strtotime($date));
                endif;
            } else {
                update_post_meta($post_id,
                        $meta_box['name'],
                        $_POST[$meta_box['name']]);
            }
        }
    }

    add_action('admin_menu',
            'photomaker_create_meta_box');
    add_action('save_post',
            'photomaker_save_meta_box');
    ?>