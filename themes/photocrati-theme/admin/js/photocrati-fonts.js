jQuery(function($){
    // 'Check' the currently selected font
    $('.font-window').each(function(){
        var $font_window = $(this);
        var selected_value = $font_window.find('input:first').val();
        $font_window.find("input[value='"+selected_value+"']").prop('checked', true);
    });

    // When someone selects a font, then change the form value
    $('.font-window input').on('click', function(){
       var $option = $(this);
       var $font_window = $option.parents('.font-window:first');
       $font_window.find('input:first').val($option.val());
    });
});