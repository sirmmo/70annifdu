<?php foreach ($fonts as $font): ?>
    <div class='font-float'>
        <input
            type='radio'
            name='<?php echo esc_attr($id) ?>'
            style='vertical-align: middle'
            value='<?php echo esc_attr($font->family) ?>'
            />
        <span style="font-size: 16pt; font-family: <?php echo $font->value ?>"><?php echo esc_html($font->family)?></span>
    </div>
<?php endforeach ?>