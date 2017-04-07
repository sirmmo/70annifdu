<?php

	if (!function_exists('current_user_can') || !current_user_can('manage_options'))
	{
		if (function_exists('wp_die'))
		{
			wp_die('Permission Denied.');
		}
		else
		{
			die('Permission Denied.');
		}
	}

?>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function() {

	jQuery(".options").corner("top");

	jQuery("#view-theme").fancybox({
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'width'			:	1020, 
		'height'		:	450,
		'overlayShow'	:	true
	});

});
</script>

<div id="admin-wrapper">

	<div id="header-left">
    <img src="<?php bloginfo('template_directory'); ?>/admin/images/ph-logo.gif" align="absmiddle" /> &nbsp;<a id="view-theme" class="iframe" href="<?php bloginfo('wpurl'); ?>" style="text-decoration:none;" /><input type="button" class="button" value="View Theme" /></a>
	</div>
    
    <div id="header-right">
    <?php theme_version(); ?>
    </div>
    
    <div id="container">
        
        <div class="options">Quick Set Up</div>
        
            <div class="option-content">
            
                <div id="option-container">
					
					<p>Thanks for using Photocrati! If you are new to WordPress, jump to the bottom for an orientation. Otherwise, to quickly set up your theme, do the following.</p>

					<p>
					<strong>1) CHOOSE YOUR THEME.</strong> Go to the <a href="admin.php?page=choose-theme">Choose Theme</a> page. Choose your theme style.
					</p>
					
					<p><strong>2) QUICKLY CREATE STANDARD PAGES.</strong> At the bottom of the <a href="admin.php?page=choose-theme">Choose Theme</a> page, click CREATE PAGES to quickly create a set of default pages: Home, Blog, Galleries, About, Contact. 
					<BR>
					<em>(Note: Our Create Pages button will also create a new Home page and move your blog posts to your Blog page. By default, WordPress displays blog posts on your home page. You can return to a conventional blog anytime by unpublishing the Home and Blog pages and selecting Front Page Displays Latest Posts <a href="options-reading.php">here</a>.)</em>
					</p>
					
					<p><strong>3) BASIC SETTINGS.</strong> Adjust your site's title and tag line on the <a href="options-general.php">Settings</a> page. For better SEO, click <a href="options-permalink.php">Permalinks</a> and select the Month and Name format for your URLs. Be sure to save your changes.</p>
					
					<p><strong>4) HOME PAGE.</strong> Upload a gallery to your Home page. Go to <a href="edit.php?post_type=page">Pages</a>, click Home, and click New Photocrati Gallery to upload images. Save your gallery and update your Home page.
					<BR>
<em><strong>FULL SCREEN HOME PAGE</strong></em>: If you've selected a preset with a full screen home page, first confirm that you've created and set home page under <a href="options-reading.php">Settings > Reading</a>. Then go to your home page and select the "Page Without Content" page template. You can then upload and select images in the <a href="admin.php?page=photocrati-customize-theme">Customize Theme</a> > Global > Homepage Background section.</p>
					
					<p><strong>5) ABOUT AND CONTACT PAGES.</strong> Go to <a href="edit.php?post_type=page">Pages</a> and click About and Contact to add content to each page.</p>
					
					<p><strong>6) CREATE GALLERIES.</strong> Go to <a href="edit.php?post_type=page">Pages</a> and click Add New. Give the new page a name and click New Photocrati Gallery to upload a gallery. When finished, on the far right below the Publish button, set the "Parent" page to Galleries to make the page a sub-menu item under Galleries. Repeat for as many galleries as you want.</p>
					
					<p><strong>7) ADD AN ALBUM.</strong> Add an Album - a list of thumbnails linking to each of your galleries - to your Galleries page. Go to <a href="edit.php?post_type=page">Pages</a>, click Galleries and click New Photocrati Gallery. Choose the Album option and choose which galleries you want to display in your Album. Save and update your page.</p>
					
					<p><strong>8) BLOG POSTS.</strong> Publish your first blog post by clicking <a href="edit.php">Posts</a> and then Add New.</p>
					
					<p><strong>9) SIDEBAR AND FOOTER.</strong> Add content to your blog's sidebar and your footer area by going to the <a href="widgets.php">widgets</a> page and dragging widgets to the sidebar and footer areas. To add a copyright to your footer, go to our <a href="admin.php?page=photocrati-customize-theme">Customize Footer page</a>.</p>
					
					<p><strong>10) LOGO.</strong> Upload a custom logo on the <a href="admin.php?page=photocrati-customize-theme">Customize Header page</a>.</p>
					
					<p><strong>11) CUSTOMIZE.</strong> Customize your site! The above steps will give you a website that looks like our demos. You can now customize as much as you want. Click <a href="admin.php?page=photocrati-customize-theme">Theme Options</a> to customize colors, fonts, spacing, gallery settings, and ecommerce settings. Be sure to save your settings as a custom theme on the <a href="admin.php?page=choose-theme">Choose Theme</a> page. That way, if you ever make additional changes, you can always return to your saved settings and site appearance.</p>
					
					<p>---------------------------------------------------------------</p>
					<p><strong><u>NEW TO WORDPRESS? Here are some tips.</u></strong></p>
					
					<p><strong>THE DASHBOARD.</strong> The admin area of your website is known as the WordPress dashboard. Please note that all you need to manage your site is on the menu on the left.
					
					<p>
						<strong>PAGES VS POSTS.</strong> WordPress has two main kinds of content, Pages and Posts. Any items that show up on your main menu, including galleries, are Pages. Posts, by contrast, are your blogs posts.</p>
					<p>
					<em>
					*To add and edit pages, click <a href="edit.php?post_type=page">Pages</a>. Click the name of a page or click Add New. Add a title, content, or images. You can also create a gallery, by clicking New Photocrati Gallery. Click Publish and the page will appear on your menu.
					<BR>
					*To add and edit blog posts, click <a href="edit.php">Posts</a> on the left. Click the name of a post or click Add New. Add a title, content or images. You can also add galleries to your posts by clicking New Photocrati Gallery. Click publish and the new post will appear on your blog.
					</em>
					</p>
					
					<p><strong>WIDGETS.</strong> You add content to your blog's sidebar by going to the <a href="widgets.php">Widgets</a> page and dragging widgets to the sidebar. The Photocrati theme also allows you drag widgets to your footer.</p>
					
					<p><strong>SETTINGS.</strong> You can change your site title, change how your site's URLs appear, and change other important settings by clicking the settings tab on the left.</p>
					
					<p><strong>PLUGINS.</strong> There are thousands of free third-party plugins you can add to your WordPress site. These allow you to add contact forms, integrate Twitter feeds, improve your SEO, create forums, and do hundreds of other things that are not a core part of WordPress. You can see your current plugins by clicking <a href="plugins.php">Plugins</a>. You can search the directory of plugins on the plugins page and click Install to quickly install any plugin you see there. Once installed, most plugins will add an extra menu item somewhere on the left where you can change the settings of the plugin.</p>
					
					<p><strong>PHOTOCRATI THEME OPTIONS.</strong> All the options for customizing your Photocrati Theme, including your theme style, colors, fonts, spacing, gallery settings, and ecommerce settings, are found under the <a href="admin.php?page=photocrati-customize-theme">Theme Options</a> tab on the left. </p>
					                      
        		</div>
        
			</div>
    
    </div>
    
</div>
