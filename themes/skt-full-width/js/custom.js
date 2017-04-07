// navigation script for responsive
var ww = jQuery(window).width();
jQuery(document).ready(function() { 
	jQuery("nav#nav li a").each(function() {
		if (jQuery(this).next().length > 0) {
			jQuery(this).addClass("parent");
		};
	})
	jQuery(".mobile_nav").click(function(e) { 
		e.preventDefault();
		jQuery(this).toggleClass("active");
		jQuery("nav#nav").slideToggle('fast');
	});
	adjustMenu();
})
// navigation orientation resize callbak
jQuery(window).bind('resize orientationchange', function() {
	ww = jQuery(window).width();
	adjustMenu();
});
// navigation function for responsive
var adjustMenu = function() {
	if (ww < 768) {
		jQuery(".mobile_nav").css("display", "block");
		if (!jQuery(".mobile_nav").hasClass("active")) {
			jQuery("nav#nav").hide();
		} else {
			jQuery("nav#nav").show();
		}
		jQuery("nav#nav li").unbind('mouseenter mouseleave');
	} else {
		jQuery(".mobile_nav").css("display", "none");
		jQuery("nav#nav").show();
		jQuery("nav#nav li").removeClass("hover");
		jQuery("nav#nav li a").unbind('click');
		jQuery("nav#nav li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
			jQuery(this).toggleClass('hover');
		});
	}
}
