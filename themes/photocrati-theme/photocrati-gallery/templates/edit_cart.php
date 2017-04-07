<form id='cart' onsubmit="return false;" data-ajax="false">
<input type='hidden' name='product_id' value='<?php echo esc_attr($product_id)?>'/>
<div id='cart_wrapper'>
    <h1 class='cart_header'>Add To Shopping Cart</h1>
    <div class='cart_image'>
        <img src='<?php echo esc_attr($thumbnail_src) ?>'/>
    </div>
    <div class='cart_data'>
        <!-- Columns -->
        <p><strong>Select the quantity of each print size:</strong></p>
        <div class='cart_qty titles'>
            <b>Qty</b>
        </div>
        <div class='cart_desc titles'>
            <b>Description</b>
        </div>
        <div class='cart_amt titles'>
            <b>Price</b>
        </div>
        <div class='cart_line titles'>
            <b>Totals</b>
        </div>
        <div class="clear"></div>

        <!-- Options/Cart Items -->
        <?php foreach ($options as $number => $option): ?>
        <div class='cart_item'>
            <?php ?>
            <div class="cart_qty">

                <input
                    class='quantity_field'
                    type="text"
                    value="<?php echo esc_attr($option['quantity'])?>"
                    name="options[<?php echo esc_attr($number)?>][quantity]"
                />
            </div>
            <div class='cart_desc'><?php echo esc_html(stripslashes($option['option_name']))?></div>
            <div class='cart_amt'>
                <?php echo $currency_symbol ?><span class='amount_field'><?php echo esc_html(number_format($option['option_value'], 2))?></span>
            </div>
            <div class='cart_amt'>
                <?php echo $currency_symbol ?><span class='total'><?php echo esc_html(number_format($cart_total, 2)) ?></span>
            </div>
        </div>
        <div class='clear cart_clear'></div>
        <?php endforeach ?>

        <!-- Summary -->
        <div class="cart_total">TOTAL:</div>
        <div class="cart_total_amount">
            <?php echo $currency_symbol ?><span id='cart_total'><?php echo number_format($cart_total, 2)?></span>
        </div>
        <div class="clear buttons_clear"></div>

        <!-- Buttons -->
        <div class="addto">

            <button style="margin:0 5px;" class="positive" id="cancel">
                Cancel
            </button>

            <button style="margin:0 5px;" id="update" class="addto">
                Update
            </button>

            <img style="display:none;padding-top:3px;margin:0 30px 0 0;" src="<?php echo get_stylesheet_directory_uri() ?>/admin/images/ajax-loader.gif" id="loader">

        </div>
    </div>
</div>
</form>
