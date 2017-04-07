jQuery(function($){
   // The customize link should be set to Photocrati
   var link = window.location.toString().replace('/themes.php', '/admin.php?page=photocrati-customize-theme');
   $('#customize-current-theme-link').attr('href', link);

   // Any preview links for Photocrati should be hidden
   $('.load-customize').each(function(){
      var $link = $(this);
      if ($link.attr('href').indexOf('photocrati') != '-1' && $link.attr('id') != 'customize-current-theme-link') {
          $link.parent().hide();
      }
   });
});