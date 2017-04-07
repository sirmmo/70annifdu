<?php
    $preset                 = Photocrati_Style_Manager::get_active_preset();
    $style_skin = $preset->style_skin;
    $footer_copy            = $preset->footer_copy;
    $show_photocrati        = $preset->show_photocrati;
    $google_analytics       = $preset->google_analytics;
    $footer_widget_placement= $preset->footer_widget_placement;
?>	

<?php if ($style_skin == 'modern'): ?>
<script type="text/javascript">
//<![CDATA[	
(function () {
	jQuery(function ($) {
		var checkResponsive = function () {
			var menus = $('.photocrati-menu');
			
			menus.each(function () {
				var isResponsive = false;
				var menu = $(this);
				var items = menu.find('ul > li');
				var count = items.size();
				
				if (count > 0) {
					var item = $(items.get(0));
					
					if (item.css('clear') == 'both') {
						isResponsive = true;
					}
				}
			
				if (isResponsive) {
					menu.addClass('photocrati-menu-responsive');
				}
				else {
					menu.removeClass('photocrati-menu-responsive');
				}
				
				for (var i = 0; i < count; i++) {
					var item = $(items.get(i));
					var anchor = item.find('a');
					
					if (anchor.size() > 0) {
						anchor = $(anchor.get(0));
					
						var anchorWrap = anchor.find('.photocrati-menu-item-text');
				
						if (anchorWrap.size() == 0) {
							anchorWrap = $('<span class="photocrati-menu-item-text"></span>').append(anchor.html());
							anchor.html(anchorWrap);
						}
						
						if (item.hasClass('menu-item-has-children')) {
							var expander = anchor.find('.photocrati-menu-expander');
					
							if (expander.size() == 0) {
								expander = $('<span class="photocrati-menu-expander">+</span>');
								expander.on('click', function (event) {
									var jthis = $(this);
									var jpar = jthis.parent().parent();
									var menu = jthis.parents('.photocrati-menu');
			
									if (menu.hasClass('photocrati-menu-responsive') && jpar.find('ul').size() > 0) {
										jpar.toggleClass('item-open');
									
										if (jpar.hasClass('item-open')) {
											jthis.html('-');
										}
										else {
											jthis.html('+');
										}
				
										event.preventDefault();
				
										return false;
									}
								});
							
								anchor.append(expander);
							}
						}
					}
				}
			});
		};
		
		$(window).on('resize orientationchange onfullscreenchange onmozfullscreenchange onwebkitfullscreenchange', function (event) {
			checkResponsive();
    });
    
    checkResponsive();
	});
})();
//]]>
</script>
<?php endif ?>

<?php /* This inserts the Google Analytics code */ echo str_replace('\"', '"', str_replace("\'", "'", $google_analytics)); ?>

