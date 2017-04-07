<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SKT Full Width
 */
?>

	</div><!-- #content -->
    <div class="clear"></div>
	<footer id="colophon" class="site-footer" role="contentinfo">
    	<div class="foot_col_container">
            <div class="footer-menu"><h2>Main Menu</h2>
                <?php wp_nav_menu( array('theme_location' => 'primary', 'container' => '', 'menu_class' => '') ); ?>
            </div><!-- footer-menu -->
            <div class="social"><h2>Get In Touch</h2>
                <div class="container">
                    <?php if ( of_get_option('facebook', true) != "") { ?>
                     <a target="_blank" href="<?php echo esc_url(of_get_option('facebook', true)); ?>" title="Facebook" ><div class="fb">Facebook</div></a>
                     <?php } ?>
                    <?php if ( of_get_option('twitter', true) != "") { ?>
                     <a target="_blank" href="<?php echo esc_url("http://twitter.com/". esc_attr(of_get_option('twitter', true)) ); ?>" title="Twitter" ><div class="twitt">Twitter</div></a>
                     <?php } ?>
                     <?php if ( of_get_option('google', true) != "") { ?>
                     <a target="_blank" href="<?php echo esc_url(of_get_option('google', true)); ?>" title="Google Plus" > <div class="gplus">Google +</div></a>
                     <?php } ?>
                     <?php if ( of_get_option('linkedin', true) != "") { ?>
                     <a target="_blank" href="<?php echo esc_url(of_get_option('linkedin', true)); ?>" title="Linkedin" ><div class="linkedin">Linkedin</div></a>
                     <?php } ?>
                    
                </div>
            </div><!-- social -->
            <div class="contact"><h2>Contact Info</h2>
                 <h3 class="company-title"><?php echo esc_html( of_get_option('contact1', true) ); ?></h3>
                 <p><?php echo esc_html( of_get_option('contact2', true) ); ?></p>
                 <p><?php echo esc_html( of_get_option('contact3', true) ); ?></p>
                 <p><strong>Phone :</strong> <?php echo esc_html( of_get_option('contact4', true) ); ?></p>
                 <p><strong>Email :</strong> <?php echo sanitize_email( of_get_option('contact5', true) ); ?></p>
            </div><!-- contact -->
            <div class="clear"></div>
        </div>
	</footer><!-- #colophon -->
  <div class="footer-bottom">
	  <div class="foot_col_container">
        <div class="bottom-left">
        	<?php
			if ( (function_exists( 'of_get_option' ) && (of_get_option('footertext2', true) != 1) ) ) {
			 	echo esc_html( of_get_option('footertext2', true) ); 
			} ?>
        </div><!-- bottom-left -->    
        <div class="bottom-right">
			<?php do_action( 'skt_full_width_credits' ); ?>
			Full Width Theme by <a href="<?php echo esc_url( SKT_URL ); ?>" target="_blank" rel="designer">SKT Themes</a>
		</div><!-- bottom-right --><div class="clear"></div>
        </div><!-- footer-bottom -->
	</div>
</div><!-- #page -->

</div><!-- #primary -->
  </div><!-- wrapper -->
  <?php if( is_home() ) { ?>
<div id="supersized-loader"></div>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>