<form role="search" method="get" class="searchform" action="<?php echo esc_url(home_url('/')); ?>">
    <div>
        <input type="text" onfocus="if (this.value == '<?php echo FORM_SEARCH; ?>') {
                    this.value = '';
                }" onblur="if (this.value == '') {
                            this.value = '<?php echo FORM_SEARCH; ?>';
                        }"  value="<?php echo FORM_SEARCH; ?>" name="s" id="s" />
        <input type="submit" id="searchsubmit" value="" />
    </div>
</form>
<div class="clear"></div>
