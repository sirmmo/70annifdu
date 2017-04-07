<!-- The JavaScript -->		
<section id="dynamic_sidebar" style="right: 0;">
    <h2><a href="#"></a></h2>
    <?php if (current_user_can('manage_options')) { ?>
        <style>
            #dynamic_sidebar h2 a {
                position: absolute;
                left: -37px;
                top: 32px;
            }
        </style>
    <?php } ?>
    <div class="sidebar_container" id="content_1">
        <div class="menu_wrapper">
            <div id="MainNav">
                <?php inkthemes_nav(); ?>                       
            </div>
        </div>
        <?php get_sidebar(); ?>	
        <div class="social_icon">
            <ul>
                <?php if (inkthemes_get_option('inkthemes_facebook') != '') { ?>
                    <li><a href="<?php echo inkthemes_get_option('inkthemes_facebook'); ?>" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                <?php } if (inkthemes_get_option('inkthemes_twitter') != '') { ?>
                    <li><a href="<?php echo inkthemes_get_option('inkthemes_twitter'); ?>" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                <?php } if (inkthemes_get_option('inkthemes_google') != '') { ?>
                    <li><a href="<?php echo inkthemes_get_option('inkthemes_google'); ?>" target="_blank" title="Google +"><i class="fa fa-google-plus"></i></a></li>
                <?php } if (inkthemes_get_option('inkthemes_linkedin') != '') { ?>
                    <li><a href="<?php echo inkthemes_get_option('inkthemes_linkedin'); ?>" target="_blank" title="LinkedIn"><i class="fa fa-linkedin"></i></a></li>
                <?php } if (inkthemes_get_option('inkthemes_rss') != '') { ?>
                    <li><a href="<?php echo inkthemes_get_option('inkthemes_rss'); ?>" target="_blank" title="Rss"><i class="fa fa-rss"></i></a></li>
                <?php } if (inkthemes_get_option('inkthemes_pinterest') != '') { ?>
                    <li><a href="<?php echo inkthemes_get_option('inkthemes_pinterest'); ?>" target="_blank" title="Pinterest"><i class="fa fa-pinterest"></i> </a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="copyrightinfo">
            <p class="copyright"><a href="http://wordpress.org/" rel="generator"><?php _e('Powered by WordPress', 'photomaker');
                ?></a>
                <span class="sep">&nbsp;|&nbsp;</span>
                <?php
                printf(__('%1$s by %2$s.', 'photomaker'), 'Photomaker', '<a href="http://inkthemes.com/" rel="designer">InkThemes</a>');
                ?></p>
        </div>	
    </div>
</section>
<div id="dynamic_sidebar_small" style="right: -220px;">
    <h2><a href="#"></a></h2>
    <?php if (current_user_can('manage_options')) { ?>
        <style>
            #dynamic_sidebar h2 a {
                position: absolute;
                left: -37px;
                top: 32px;
            }
        </style>
    <?php } ?>
    <div class="sidebar_container" id="content_2">
        <div class="menu_wrapper mobile">
            <?php inkthemes_nav(); ?>                       
        </div>
        <?php get_sidebar('home'); ?>	

        <div class="copyrightinfo">
            <p class="copyright"><a href="http://wordpress.org/" rel="generator"><?php _e('Powered by WordPress', 'photomaker');
        ?></a>
                <span class="sep">&nbsp;|&nbsp;</span>
                <?php
                printf(__('%1$s by %2$s.', 'photomaker'), 'Photomaker', '<a href="http://inkthemes.com/" rel="designer">InkThemes</a>');
                ?></p>
        </div>	
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>