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

	include "scripts/scripts-update.php";
?>

<div id="admin-wrapper">
    
    <div id="container">
    
    	<div class="options">Photocrati Theme Update</div>
        
            <div class="sub-options"><span id="comments">You can automatically update your theme files if an update is available here.</span></div>
            <div class="option-content">
            
                <div id="option-container">
                
                <div class="left" style="width:100%">
                
                <p>
                
                <form name="theme-update" id="theme-update" method="post">
                                     
                    <?php 
							
						theme_updates();
					
					?>
                    
                
                </form>
                
                </p>
                
                <p style="border-top:1px solid #CCC;margin:10px 0 5px 0;padding:5px 0 0 0;clear:both;">
                
                <h3>Important Notice</h3>
                
                Please be aware that updating your theme this way <u>overwrites some core theme files</u> with the latest versions. If you have made any 
                customizations to the code in your theme files <strong>please back them up first</strong>. Any style changes you have made with the theme 
                admin <u>will <strong>not</strong> be lost</u>.
                </p>
                <p>
                <em>You can also download the latest files and manually update them yourself here: 
                <a href="http://members.photocrati.com" target="_blank">http://members.photocrati.com</a></em>
                </p>
                
                </div>
                
                </div>
                
        </div>
    
    </div>
    
</div>
