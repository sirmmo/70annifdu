<div class='font-window' id='<?php echo esc_attr($id) ?>'>
    <input type='hidden' name='<?php echo esc_attr($field_name) ?>' value='<?php echo esc_attr($current_value) ?>'/>
    <h3>Standard Fonts</h3>
    <div class='other_font_list'>
        <?php $other_fonts->render_font_list($id)?>
    </div>
    <h3>Designer Fonts</h3>
    <div class='google_font_list'>
        <?php $font_group->render_font_list($id) ?>
    </div>
</div>