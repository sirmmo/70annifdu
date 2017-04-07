//Adjusting sidebar Height According to Widows Height
jQuery("#tf_bg").on("tap", function(event) {
    alert("hello");
})

//Gallery
jQuery(document).ready(function() {
    jQuery(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed: 'normal', theme: 'facebook', slideshow: 3000, autoplay_slideshow: false, social_tools: false});
});
//Flexslider
//<![CDATA[
jQuery.noConflict();
jQuery("document").ready(function() {
    jQuery('.flexslider').flexslider({
        animation: "fade", //String: Select your animation type, "fade" or "slide"
        slideDirection: "horizontal", //String: Select the sliding direction, "horizontal" or "vertical"
        slideshow: true, //Boolean: Animate slider automatically
        slideshowSpeed: 7000, //Integer: Set the speed of the slideshow cycling, in milliseconds
        animationDuration: 600, //Integer: Set the speed of animations, in milliseconds
        directionNav: true, //Boolean: Create navigation for previous/next navigation? (true/false)
        controlNav: true, //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
        keyboardNav: true, //Boolean: Allow slider navigating via keyboard left/right keys
        mousewheel: false, //Boolean: Allow slider navigating via mousewheel
        prevText: "Previous", //String: Set the text for the "previous" directionNav item
        nextText: "Next", //String: Set the text for the "next" directionNav item
        pausePlay: false, //Boolean: Create pause/play dynamic element
        pauseText: 'Pause', //String: Set the text for the "pause" pausePlay item
        playText: 'Play', //String: Set the text for the "play" pausePlay item
        randomize: false, //Boolean: Randomize slide order
        slideToStart: 0, //Integer: The slide that the slider should start on. Array notation (0 = first slide)
        animationLoop: true, //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
        pauseOnAction: true, //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
        pauseOnHover: true
    });
});
//dynamic_sidebar Tab
(function(jQuery) {
    jQuery(document).ready(function() {
        jQuery('#popular').hide();
        jQuery("#dynamic_sidebar h2 a").click(function(e) {
            e.preventDefault();
            var div = jQuery("#dynamic_sidebar");
            console.log(div.css("right"));
            if (div.css("right") === "-285px") {
                jQuery("#dynamic_sidebar").animate({
                    right: "0px"
                });
            } else {
                jQuery("#dynamic_sidebar").animate({
                    right: "-285px"
                });
            }
        });
    });
})(this.jQuery);
(function(jQuery) {
    jQuery(window).load(function() {
        jQuery("#content_1").mCustomScrollbar({
            autoHideScrollbar: true,
            scrollInertia: 500,
            theme: "light-2"
        });
    });
})(jQuery);
(function(jQuery) {
    jQuery(window).load(function() {
        jQuery("#content_2").mCustomScrollbar({
            autoHideScrollbar: true,
            scrollInertia: 500,
            theme: "light-2"
        });
    });
})(jQuery);
//dynamic_sidebar Tab
(function(jQuery) {
    jQuery(document).ready(function() {
        jQuery("#dynamic_sidebar_small h2 a").click(function(e) {
            e.preventDefault();
            var div = jQuery("#dynamic_sidebar_small");
            console.log(div.css("right"));
            if (div.css("right") === "-220px") {
                jQuery("#dynamic_sidebar_small").animate({
                    right: "0px"
                });
            } else {
                jQuery("#dynamic_sidebar_small").animate({
                    right: "-220px"
                });
            }
        });
    });
})(this.jQuery);
//Adjusting sidebar Height According to Widows Height
jQuery(document).ready(function() {
    jQuery(".sidebar_container").css("height", jQuery(window).height());
    jQuery(".sidebar_container_small").css("height", jQuery(window).height());
});
jQuery(window).resize(function() {
    jQuery(".sidebar_container").css("height", jQuery(window).height());
    jQuery(".sidebar_container_small").css("height", jQuery(window).height());
});

