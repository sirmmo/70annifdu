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

	include "scripts/scripts-setup.php";
    $dynamic_style = Photocrati_Style_Manager::get_active_preset()->dynamic_style;
?>

<div id="admin-wrapper">

	<div id="header-left">
    <img src="<?php bloginfo('template_directory'); ?>/admin/images/ph-logo.gif" align="absmiddle" /> &nbsp;<a id="view-theme" class="iframe" href="<?php bloginfo('wpurl'); ?>" style="text-decoration:none;" /><input type="button" class="button" value="View Theme" /></a>
	</div>
    
    <div id="header-right">
    <?php theme_version(); ?>
    </div>
    
    <div id="container">
        
        <?php if($dynamic_style) { ?>
        
        <div class="options">Choose Theme</div>
        
        <div class="sub-options"><span id="comments">Change designs at the click of a button. <strong>Click the thumbnail for a larger preview</strong>. For full screen designs, please be sure to select the "Page Without Content" page template for your home page, and to upload your images in the <a href="admin.php?page=photocrati-customize-theme">Customize Theme</a> > Global > Homepage Background section.</span></div>
        <div class="option-content">

            <div id="option-container">

                <div class="left" id="color-choices" style="z-index:0;">

                <?php
                $preset         = Photocrati_Style_Manager::get_active_preset();
                $currentpreset  = $preset->get_name();
                $currentpre     = $preset->get_title();
                ?>

                <p style="font-size:15px;"><b>Current Theme:</b> <?php echo htmlentities(stripslashes($currentpre)); ?></p>

                    <p>

                        <?php
                        $presets = Photocrati_Style_Manager::get_all_presets(FALSE);
                        $presets_order = Photocrati_Style_Manager::get_default_presets_display_order();
                        
                        $presets_diff = array_diff(array_keys($presets), $presets_order);
                        $presets_order = array_merge($presets_order, $presets_diff);
                        
                        foreach ($presets_order as $preset_name) {
		                      
		                      $preset = $presets[$preset_name];
                        ?>

                            <div class="option" style="height: 206px;">
                            <a href="<?php echo get_template_directory_uri(); ?>/admin/images/presets/<?php echo str_replace("-", "_", $preset->get_name()); ?>.jpg" id="single_image"><img src="<?php echo get_template_directory_uri(); ?>/admin/images/presets/<?php echo str_replace("-", "_", $preset->get_name()); ?>.jpg" style="width: 225px; height; 158px;" /></a>
                            <input type="button" class="button" id="<?php echo $preset->get_name(); ?>" value="Use <?php echo $preset->get_title(); ?>" />
                            </div>

                        <?php
                        }
                        ?>

                    </p>
                </div>

            </div>
    </div>
			
		<div class="options">Save Custom Themes</div>
        
        <div class="sub-options"><span id="comments">You can save your current customizations here and re-activate them anytime.</span></div>
        <div class="option-content">

            <form name="add_custom_settings" id="add_custom_settings" method="post">
                <div style="width:36%;float:left;margin:8px 0 15px 0;">
                    <input type="text" id="add_custom_name" name="preset_name" style="padding:4px;border:1px solid #CCC;color:#999;font-size:12px;" size="40" value="Custom Setting Name...">
                </div>
                <div style="width:64%;float:left;margin:10px 0 15px 0;">
                    <input type="button" class="button" id="add_custom" value="Save Current Customizations" style="font-weight:bold;" />
                </div>
            </form>

            <div id="option-container">

                <div class="left" id="color-choices" style="z-index:0;">

                    <p>

                        <?php $presets = Photocrati_Style_Manager::get_all_third_party_presets(); ?>
                        <?php foreach ($presets as $preset): ?>

                            <div class="option">
                            <img src="<?php bloginfo('template_directory'); ?>/admin/images/custom-preset-sm.jpg" style="width:225px;" />
                            <input type="button" class="button" id="custom-<?php echo $preset->get_name(); ?>" value="Use <?php echo esc_attr(stripslashes($preset->get_title())); ?>" />
                            <?php if($preset->get_name() == $currentpreset) { ?>
                            <img src="<?php bloginfo('template_directory'); ?>/admin/images/check.png" style="border:0;" align="absmiddle" />
                            <?php } ?><br/>
                            <input type="button" class="button" id="preset_update_<?php echo esc_attr($preset->get_name()); ?>" value="Update" style="margin-top:-5px;" />
                            <input type="button" class="button" id="preset_delete_<?php echo esc_attr($preset->get_name()); ?>" value="Delete" style="margin-top:-5px;" />
                            <input type="button" class="button export_theme_btn" id="<?php echo esc_attr($preset->get_name()); ?>" value="Export" style="margin-top:-5px;" />
                            <input type="hidden" id="preset_title_<?php echo esc_attr($preset->get_name()); ?>" value="<?php echo esc_attr(stripslashes($preset->get_title())); ?>" />
                            </div>

                        <?php endforeach ?>
                    </p>
                </div>

            </div>
    </div>

        <div class="options">Import Custom Theme</div>
        <div class="sub-options">
            <span id="comments">You can upload and import other custom themes here.</span>
        </div>
        <div class='option-content'>
            <div id='option-container'>
                <form enctype="multipart/form-data" name='import_theme' id='import_theme' action='<?php echo esc_attr(admin_url())?>' method='POST'>
                    <input type='hidden' name='import_photocrati_theme' value='1'/>
                    <input type='hidden' name='nonce' value='<?php echo esc_attr(wp_create_nonce('import_photocrati_theme'))?>'/>
                    <input type="file" id="custom_theme_file" name="custom_theme_file" style="padding: 4px; border: 1px solid rgb(204, 204, 204); color: rgb(51, 51, 51); font-size: 12px;" size="40"/>
                    <input type="submit" class="button" id="import_custom_theme_btn" value="Import Custom Theme" style="font-weight:bold;"/>
                </form>
            </div>
        </div>
        
        <?php } ?>
    
    	<div class="options">Create Default Pages</div>
        
            <div class="sub-options"><span id="comments">*WARNING!* This should only be done on a fresh install of Wordpress!</span></div>
            <div class="option-content">
            
                <div id="option-container">
                
                <form name="page-options" id="page-options" method="post">
                
                    <div class="left" style="border:0;width:90%;">
                    
                        <p class="titles">Default Page Set</p>
                        <p>
                        	By clicking the "Create Pages Now" button below a default set of pages will be created for your website:
                            <BR /><BR />
                            <strong>Home</strong> | <strong>Galleries</strong> | <strong>Blog</strong> | <strong>About</strong> | <strong>Contact</strong>
                            <BR /><BR />
                            This will set the "Blog" page for your posts and the "Home" page as the default page.<BR>
							<b><i>* Please only click this button once and wait for the confirmation!</i></b>
                        </p>
                        
                    </div>
                    
                    <div class="right">
                    
                    </div>
                    
                    <div class="submit-button-wrapper">
                        <input type="button" class="button" id="update-pages" value="Create Pages Now" style="clear:none;" /> 
                    </div>
                    <div class="msg" id="msgpages" style="float:left;"></div>
                
                </form>
                
                </div>
                
        </div>
    
    </div>
    
</div>
