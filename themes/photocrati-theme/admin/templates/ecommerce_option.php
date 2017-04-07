<div id='ecommerce_option_<?php echo esc_attr($number)?>' class='ecommerce_option' style='width:48%;float:left;clear:none;'>
    <!-- Option Name -->
    <div class='inner' style='width:72%;margin-bottom:-10px;float:left;'>
        <p class='titles'>Option <?php echo $number?></p>
        <p>
            <input
                type='text'
                size='30' style="width: 95%;"
                value="<?php echo esc_attr(stripslashes($name)) ?>"
                name='options[<?php echo esc_attr($number) ?>][option_name]'
            />
        </p>
    </div>

    <!-- Option Value -->
    <div class='inner'>
        <p class='titles'>Price <?php echo $number ?></p>
        <p>
            <input
				class='ecomm_price'
                type='text'
                size='6'
                value='<?php echo esc_attr($value) ?>'
                name='options[<?php echo esc_attr($number)?>][option_value]'
            />
        </p>
    </div>
</div>
