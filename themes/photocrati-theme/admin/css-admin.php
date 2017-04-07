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

	include "scripts/scripts-css.php";
	
	$preset		 = Photocrati_Style_Manager::get_active_preset();
  extract($preset->to_array());
  
	$custom_css	 = $preset->custom_css;
	$custom_css_modern = $preset->custom_css_modern;
	$dynamic_style  = $preset->dynamic_style;
?>

<div id="admin-wrapper">
	
	<div id="container">
	
		<div class="options">Dynamic Styling</div>
		
			<div class="sub-options"><span id="comments">Disable or enable the dynamic styling. If the dynamic styling is disabled you can use styles/style.css to customize your theme.</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
					<div class="left" style="border-right:0;width:30%;">
					
						<form name="dynamic-stying" id="dynamic-styling" method="post">
						
							<div class="inner" style="width:100%;">
								<p class="titles">Dynamic Styling Enabled?</p>
								<p>
								<select name="dynamic_style" id="dynamic_style">
									<option value='YES' <?php selected($dynamic_style, TRUE) ?>>YES</option>
									<option value='NO' <?php selected($dynamic_style, FALSE) ?>>NO</option>
								</select>
								</p>
							</div>
							
							<div id="msg11" style="float:left;"></div>
						
						</form>
						
					</div>
					
					<div class="left" style="border-right:0;width:60%;">
						<p><i>
							<b>Note:</b>
							When you disable the dynamic styling a static style sheet will be generated
							based on the current theme style. You can edit this file at styles/style.css.
						</i></p>
					</div>
										
				</div>
		</div>

		<?php if($dynamic_style) { ?>
		<div class="options">Custom CSS Code for Modern Skin on All Devices</div>
		
			<div class="sub-options"><span id="comments">If you insert code with custom classes you can style it here! You can also override theme styles. This CSS will apply to all devices. <strong>Note</strong>: this style will only be loaded when you have the "Modern" skin selected under Customize Theme => Global => Theme Skin.</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
				<form name="css-options-modern" id="css-options-modern" method="post">
				
					<div class="left" style="border:0;">
					
						<p class="titles">CSS Code (leave blank to exclude)</p>
						<p>
							<textarea name="custom_css_modern" cols="80" rows="10"><?php echo stripslashes(str_replace('"', '&quot;', $custom_css_modern)); ?></textarea>
						</p>
						
					</div>
					
					<div class="right">
					
					</div>
					
					<div class="submit-button-wrapper">
						<input type="button" class="button" id="update-css-modern" value="Update Custom CSS" style="clear:none;" /> 
					</div>
					<div class="request-msg" id="msg-css-modern" style="float:left;"></div>
				
				</form>
				
				</div>
				
		</div>
		
		<div class="options">Custom CSS Code for Modern Skin on Desktop Devices</div>
		
			<div class="sub-options"><span id="comments">This custom CSS will only be applied when on desktop screens (screens larger than 1024px). <strong>Note</strong>: this style will only be loaded when you have the "Modern" skin selected under Customize Theme => Global => Theme Skin.</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
				<form name="css-options-modern-desktop" id="css-options-modern-desktop" method="post">
				
					<div class="left" style="border:0;">
					
						<p class="titles">CSS Code (leave blank to exclude)</p>
						<p>
							<textarea name="custom_css_modern_desktop" cols="80" rows="10"><?php echo stripslashes(str_replace('"', '&quot;', $custom_css_modern_desktop)); ?></textarea>
						</p>
						
					</div>
					
					<div class="right">
					
					</div>
					
					<div class="submit-button-wrapper">
						<input type="button" class="button" id="update-css-modern-desktop" value="Update Custom CSS" style="clear:none;" /> 
					</div>
					<div class="request-msg" id="msg-css-modern-desktop" style="float:left;"></div>
				
				</form>
				
				</div>
				
		</div>
		
		<div class="options">Custom CSS Code for Modern Skin on Mobile Devices</div>
		
			<div class="sub-options"><span id="comments">This custom CSS will only be applied when on mobile screens (screens smaller than or equal to 1024px). <strong>Note</strong>: this style will only be loaded when you have the "Modern" skin selected under Customize Theme => Global => Theme Skin.</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
				<form name="css-options-modern-mobile" id="css-options-modern-mobile" method="post">
				
					<div class="left" style="border:0;">
					
						<p class="titles">CSS Code (leave blank to exclude)</p>
						<p>
							<textarea name="custom_css_modern_mobile" cols="80" rows="10"><?php echo stripslashes(str_replace('"', '&quot;', $custom_css_modern_mobile)); ?></textarea>
						</p>
						
					</div>
					
					<div class="right">
					
					</div>
					
					<div class="submit-button-wrapper">
						<input type="button" class="button" id="update-css-modern-mobile" value="Update Custom CSS" style="clear:none;" /> 
					</div>
					<div class="request-msg" id="msg-css-modern-mobile" style="float:left;"></div>
				
				</form>
				
				</div>
				
		</div>
		<?php } ?>
		
		<?php if($dynamic_style) { ?>
		<div class="options">Custom CSS Code for Legacy Skin</div>
		
			<div class="sub-options"><span id="comments">If you insert code with custom classes you can style it here! You can also over ride theme styles. <strong>Note</strong>: this style will only be loaded when you have the "Legacy" skin selected under Customize Theme => Global => Theme Skin.</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
				<form name="css-options" id="css-options" method="post">
				
					<div class="left" style="border:0;">
					
						<p class="titles">CSS Code (leave blank to exclude)</p>
						<p>
							<textarea name="custom_css" cols="80" rows="10"><?php echo stripslashes(str_replace('"', '&quot;', $custom_css)); ?></textarea>
						</p>
						
					</div>
					
					<div class="right">
					
					</div>
					
					<div class="submit-button-wrapper">
						<input type="button" class="button" id="update-css" value="Update Custom CSS" style="clear:none;" /> 
					</div>
					<div id="msg2" style="float:left;"></div>
				
				</form>
				
				</div>
				
		</div>
		<?php } ?>
		
	</div>
	
</div>
