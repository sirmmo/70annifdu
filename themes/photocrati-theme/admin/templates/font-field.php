<!-- Font Family -->
<div class='inner' style='width:100%'>
    <p class='titles'><?php echo esc_html($label) ?> Family</p>
    <p class='note' style='color: #666; font-size: 11px; font-style: italic'>Not all Google Fonts support different font weights or italics.</p>
    <?php Photocrati_Fonts::render_font_window($font_family_field_name, $font_family_value)?>
</div>

<!-- Font Size -->
<div class='inner'>
    <p class='titles'><?php echo esc_html($label) ?> Size</p>
    <p>
        <input
            type='text'
            name='<?php echo esc_attr($font_size_field_name)?>'
            id='<?php echo esc_attr($font_size_field_name)?>'
            value='<?php echo esc_attr($font_size_value)?>'
            size='2'
        />px
    </p>
</div>

<!-- Font Color -->
<div class='inner'>
    <p class='titles'><?php echo esc_html($label)?> Color</p>
    <p>
        #<input
            type='text'
            name='<?php echo esc_attr($font_color_field_name) ?>'
            id='<?php echo esc_attr($font_color_field_name) ?>'
            value='<?php echo esc_attr($font_color_value) ?>'
            size='7'
            style='background-color: #<?php echo esc_attr($font_color_value)?>'
        />
        Color
    </p>
</div>

<!-- Font Weight -->
<div class='inner'>
    <p class='titles'><?php echo esc_html($label) ?> Weight</p>
    <p>
        <select name="<?php echo esc_attr($font_weight_field_name) ?>">
            <option value='100' <?php selected($font_weight_value, 100) ?>>100</option>
            <option value='200' <?php selected($font_weight_value, 200) ?>>200</option>
            <option value='300' <?php selected($font_weight_value, 300) ?>>300</option>
            <option value='' <?php selected($font_weight_value, '') ?>>400 (Normal)</option>
            <option value='500' <?php selected($font_weight_value, 500) ?>>500</option>
            <option value='600' <?php selected($font_weight_value, 600) ?>>600</option>
            <option value='bold' <?php selected($font_weight_value, 'bold') ?>>700 (Bold)</option>
            <option value='800' <?php selected($font_weight_value, 800) ?>>800</option>
            <option value='900' <?php selected($font_weight_value, 900) ?>>900</option>
        </select>
    </p>
</div>

<!-- Font Italics -->
<div class='inner'>
    <p class='titles'><?php echo esc_html($label) ?> Italics</p>
    <p>
        <select name='<?php echo esc_attr($font_italics_field_name) ?>'>
            <option <?php selected($font_italics_value, '') ?> value=''>No</option>
            <option <?php selected($font_italics_value, 'italic') ?> value='italic'>Yes</option>
        </select>
    </p>
</div>

<!-- Font Decoration -->
<div class='inner'>
    <p class='titles'><?php echo esc_html($label) ?> Decoration</p>
    <p>
        <select name='<?php echo esc_attr($font_decoration_field_name) ?>'>
            <option <?php selected($font_decoration_value, 'none') ?> value='none'>None</option>
            <option <?php selected($font_decoration_value, 'underline') ?> value='underline'>Underline</option>
            <option <?php selected($font_decoration_value, 'overline') ?> value='overline'>Overline</option>
            <option <?php selected($font_decoration_value, 'line-through') ?> value='line-through'>Strikethrough</option>
        </select>
    </p>
</div>

<div class='inner'>
    <p class='titles'><?php echo esc_html($label) ?> Case</p>
    <p>
        <select name='<?php echo esc_attr($font_case_field_name) ?>'>
            <option <?php selected($font_case_value, 'none') ?> value='normal'>Normal</option>
            <option <?php selected($font_case_value, 'uppercase') ?> value='uppercase'>All Uppercase</option>
        </select>
    </p>
</div>