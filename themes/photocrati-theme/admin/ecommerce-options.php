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

	include "scripts/scripts-ecommerce.php";
	
	
  $preset = Photocrati_Style_Manager::get_active_preset();
  extract($preset->to_array());
  
/**
 * @var $wpdb wpdb
 */
	global $wpdb;
	$gallery = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."photocrati_ecommerce_settings WHERE id = 1", ARRAY_A);
	foreach ($gallery as $key => $value) {
		$$key = $value;
	}
?>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function() {
	var strip_symbols = function($el){
		m_strOut = jQuery(this).val().replace(/[^0-9.]/g, '');
		jQuery(this).val(m_strOut);
	};
	jQuery('.ecomm_price').keyup(strip_symbols).blur(strip_symbols);
	
	jQuery('[id^=ecomm_cost]').blur(function (event) { 
		m_strOut = jQuery(this).val().replace(/[^0-9.]/g, '');
		jQuery(this).val(m_strOut);
	});
	
	jQuery('[id^=ecomm_ship]:text').keyup(function (event) { 
		m_strOut = jQuery(this).val().replace(/[^0-9.]/g, '');
		jQuery(this).val(m_strOut);
	});
	
	jQuery('[id^=ecomm_ship]:text').blur(function (event) { 
		m_strOut = jQuery(this).val().replace(/[^0-9.]/g, '');
		jQuery(this).val(m_strOut);
	});
	
	jQuery('#ecomm_tax').keyup(function (event) { 
		m_strOut = jQuery(this).val().replace(/[^0-9.]/g, '');
		jQuery(this).val(m_strOut);
	});
	
	jQuery('#ecomm_tax').blur(function (event) { 
		m_strOut = jQuery(this).val().replace(/[^0-9.]/g, '');
		jQuery(this).val(m_strOut);
	});
	

	jQuery("[id$='_color']").ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				jQuery(el).val(hex);
				jQuery(el).ColorPickerHide();
				jQuery(el).css('background-color', '#'+hex);
			},
			onBeforeShow: function () {
				jQuery(this).ColorPickerSetColor(this.value);
				jQuery(this).css('background-color', '#'+this.value);
			}
		})
		.bind('keyup', function(){
			jQuery(this).ColorPickerSetColor(this.value);
	});
		
	jQuery("[id^=add_image_]").on('click', function() {
		currentId = jQuery(this).attr("id");
		formfield = jQuery("#"+currentId.substr(10)).attr("name");
		tb_show("Upload a button image", "media-upload.php?type=image&amp;post_id=1&amp;TB_iframe=true");
		return false;
	});

	jQuery('#more_photocrati_options').click(function(e){
		e.preventDefault();

		// Get last option
		var $more_options_button = jQuery(this).parent();
		var last_option = parseInt(jQuery('.ecommerce_option:last').attr('id').substr(17));
		var $option_container = jQuery('.photocrati_ecommerce_options');

		for(var i=last_option+1; i<=last_option+50; i++) {
		   var $container = jQuery('<div/>').attr({
				id:	'ecommerce_option_'+i,
				class: 'ecommerce_option',
				style: 'width:48%; float:left; clear:none;'
		   });
		   $more_options_button.before($container);

		   var $inner1 = jQuery('<div/>').attr({
			  class: 'inner',
			  style: 'width:72%; margin-bottom:-10px; float:left'
		   });
		   $container.append($inner1);
		   $inner1.append("<p class='titles'>Option "+i+"</p>");
		   $inner1.append("<p><input type='text' name='options["+i+"][option_name]' value='' size='30'/></p>");

		   var $inner2 = jQuery('<div/>').addClass('inner');
		   $container.append($inner2);
		   $inner2.append("<p class='titles'>Price "+i+"</p>");
		   $inner2.append("<p><input type='text' name='options['"+i+"][option_value]' value='' size='6'/></p>");
	   }
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
			
	<form name="ecommerce-options" id="ecommerce-options" method="post">
	
	<div id="container">
		
		<div class="options">E-Commerce Settings</div>
			
			<div class="option-content">
			
				<div id="option-container">	 		
				
					<div class="left" style="width:99%">
						
						<p class="notes" style="color:#208322;font-size:13px;">
							<b>Photocrati holds no liability for processing, payment or shipment of any orders.</b> All responsibility and liability for the transaction belongs to you as site owner.
						</p>
						
						<p class="notes">
							<b>Important Note:</b> <i>This theme uses PayPal IPN notifications to send out thank you e-mails. <b>You must enable IPN notifications in your
							PayPal account for this to work</b>!
							<a href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_admin_IPNSetup#id089EG030E5Z" target="_blank">
							<BR>For instructions click here</a>.
							In the notification URL field please just enter your website URL (ie. http://www.mywebsite.com). The actual notification
							URL is passed to PayPal during the checkout process.</i>
						</p>
						
					</div>	
					  
				</div>
			
			</div>
		
			<div class="sub-options"><span id="comments"><b>PayPal Account</b> - Insert your PayPal e-mail address here and select from some advanced option</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:42%">
							<p class="titles">PayPal E-mail</p>
							<p>
							<input type="text" name="pp_account" id="pp_acount" value="<?php echo $pp_account; ?>" size="36" style="width: 95%;" />
							</p>
						</div>
						
						<div class="inner" style="width:58%">
							<p class="notes" style="padding-top:10px;">
								<b>Note:</b> <i>You must have a working PayPal account set-up for this to work. For more information
								on setting up a PayPal account <a href="http://www.paypal.com" target="_blank">please click here</a>.</i>
							</p>
						</div>
						
					</div>	
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:60%">
							<p class="titles">Return URL</p>
							<p>
							<input type="text" name="pp_return" id="pp_return" value="<?php echo $pp_return; ?>" size="55" style="width: 95%;" />
							</p>
						</div>
						
						<div class="inner" style="width:40%">
							<p class="notes">
								<b>Note:</b> <i>You can specify a custom thank you page when a customer finishes paying and
								returns to your website from PayPal. Otherwise, they will be returned to the home page.</i>
							</p>
						</div>
						
					</div>			  
					  
				</div>
		</div>
			
			
			<div class="sub-options"><span id="comments"><b>Design</b> - Set layout and color options for the gallery and checkout</span></div>
			<div class="option-content" style="border-bottom:0;">
			
				<div id="option-container">
					
<?php if ($style_skin == 'legacy'): ?>
					<div class="left style-skin-legacy" style="width:99%;">

						<div class="inner" style="width:25%;">
							<p class="titles">Images Per Row</p>
							<p>
							<select name="ecomm_per_row">
								<option value="3"<?php if($ecomm_per_row == '3') { echo ' SELECTED'; } ?>>3</option>
								<option value="4"<?php if($ecomm_per_row == '4') { echo ' SELECTED'; } ?>>4</option>
								<option value="5"<?php if($ecomm_per_row == '5') { echo ' SELECTED'; } ?>>5</option>
								<option value="6"<?php if($ecomm_per_row == '6') { echo ' SELECTED'; } ?>>6</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:74%;">
							<p class="notes">
								<BR>
								<i>Thumbnail size will adjust automatically based on the number
								displayed per row.</i>
							</p>
						</div>
						
					</div>
<?php endif ?>
					
<?php if ($style_skin == 'modern'): ?>
					<div class="left style-skin-modern" style="width:99%;">
					
						<div class="inner" style="width:25%;">
							<p class="titles">Column Width</p>
							<p><input type="text" name="ecomm_image_width" id="ecomm_image_width" value="<?php echo $ecomm_image_width; ?>" size="4" />px</p>
						</div>
						
						<div class="inner" style="width:74%;">
							<p class="notes">
								<BR>
								<i>The width chosen here will determine how many rows will be displayed based on the available space and it includes any padding and borders, so the thumbnail will be less wide that this value.</i>
							</p>
						</div>
						
					</div>
<?php endif ?>
					
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:25%;">
							<p class="titles">Images Captions</p>
							<p>
							<select name="ecomm_captions">
								<option value="ON"<?php if($ecomm_captions == 'ON') { echo ' SELECTED'; } ?>>ON</option>
								<option value="OFF"<?php if($ecomm_captions == 'OFF') { echo ' SELECTED'; } ?>>OFF</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:74%;">
							<p class="notes">
								<BR>
								<i>Image captions are displayed under the image on the lightbox window if they are turned ON.</i>
							</p>
						</div>
						
					</div>
					
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:27%;">
							<p class="titles">Background Color</p>
							<p>#<input type="text" name="ecomm_back_color" id="ecomm_back_color" value="<?php echo $ecomm_back_color; ?>" size="7"  style="background:#<?php echo $ecomm_back_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:27%;">
							<p class="titles">Font Color</p>
							<p>#<input type="text" name="ecomm_font_color" id="ecomm_font_color" value="<?php echo $ecomm_font_color; ?>" size="7"  style="background:#<?php echo $ecomm_font_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:27%;">
							<p class="titles">Border/Line Colors</p>
							<p>#<input type="text" name="ecomm_line_color" id="ecomm_line_color" value="<?php echo $ecomm_line_color; ?>" size="7"  style="background:#<?php echo $ecomm_line_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:19%;">
							<p class="titles">Border Size</p>
							<p><input type="text" name="ecomm_line_size" id="ecomm_line_size" value="<?php echo $ecomm_line_size; ?>" size="2" />px</p>
						</div>
						
					</div>
					
					<div class="left" style="width:99%;">
					
						<div class="inner" style="width:27%;">
							<p class="titles">Button Color</p>
							<p>#<input type="text" name="ecomm_but_color" id="ecomm_but_color" value="<?php echo $ecomm_but_color; ?>" size="7"  style="background:#<?php echo $ecomm_but_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:27%;">
							<p class="titles">Button Text</p>
							<p>#<input type="text" name="ecomm_buttext_color" id="ecomm_buttext_color" value="<?php echo $ecomm_buttext_color; ?>" size="7"  style="background:#<?php echo $ecomm_buttext_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:27%;">
							<p class="titles">Button Border Color</p>
							<p>#<input type="text" name="ecomm_butborder_color" id="ecomm_butborder_color" value="<?php echo $ecomm_butborder_color; ?>" size="7"  style="background:#<?php echo $ecomm_butborder_color; ?>;" /></p>
						</div>
						
						<div class="inner" style="width:19%;"> </div>
						
					</div>
					  
				</div>
					
		</div>
			
			
		<div class="sub-options"><span id="comments"><b>Print Sizes</b> - Set the print sizes and pricing</span></div>
			<div class="option-content">
			
				<div id="option-container" class='photocrati_ecommerce_options'>
				
					
					<div class="left" style="width:99%;">
						
						<div class="inner" style="width:99%;">
							<p class="titles">Print Size Options & Prices</p>
							<p class="notes">
								<b>Note: </b><i>These are global settings for all galleries
								and images. Please use only numbers in the price field. You can set the currency and other options for the
								checkout here.</i>
							</p>
						</div>
						
					</div>
					<?php Photocrati_Ecommerce_Options::render_option_field_list(50) ?>
					<p><a href='#' id='more_photocrati_options'>More options</a></p>
				</div>
		</div>
			
			
		<div class="sub-options"><span id="comments"><b>Shopping Cart Settings</b> - Set the currency and other general shopping cart / checkout options</span></div>
			<div class="option-content">
			
				<div id="option-container">
				
					<div class="left" style="width:99%">
						
						<?php
						$currencies = array(
							'USD'=>'U.S. Dollar ($)',
							'CAD'=>'Canadian Dollar ($)',
							'EUR'=>'Euros (&euro;)',
							'GBP'=>'Pound Sterling (&pound;)',
							'AUD'=>'Australian Dollar ($)',
							'NZD'=>'New Zealand Dollar ($)',
							'HKD'=>'Hong Kong Dollar ($)',
							'JPY'=>'Yen (&yen;)',
							'SGD'=>'Singapore Dollar ($)',
							'CZK'=>'Czech Koruna',
							'DKK'=>'Danish Krone',
							'HUF'=>'Hungarian Forint',
							'ILS'=>'Israeli Shekel',
							'MXN'=>'Mexican Peso',
							'NOK'=>'Norwegian Krone',
							'PHP'=>'Philippine Pesos',
							'PLN'=>'Polish Zloty',
							'SEK'=>'Swedish Krona',
							'CHF'=>'Swiss Franc',
							'TWD'=>'Taiwan New Dollars',
							'THB'=>'Thai Baht',
							'BRL'=>'Brazilian Real (only Brazilian users)',
							'MYR'=>'Malaysian Ringgits (only Malaysian users)'
							);
						?>
						
						<div class="inner" style="width:42%">
							<p class="titles">Currency</p>
							<p>
							<select name="ecomm_currency">
								<?php
								foreach ($currencies as $key => $value)
								{
									echo '<option value="'.$key.'"';
										if($ecomm_currency == $key) {
											echo ' SELECTED';
									}
									echo '>'.$value.'</option>';
								}
								?>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:58%">
							<p class="notes" style="padding-top:10px;">
								<b>Note:</b> <i>Our theme currently supports 4 currencies. If you require another currency please
								<a href="https://www.paypal.com/us/cgi-bin/?cmd=p/sell/mc/mc_intro-outside" target="_blank">check with PayPal first</a>
								to see if they support that currency before requesting support from us.</i>
							</p>
						</div>
						
					</div>
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:45%">
							<p class="titles">Currency Symbol</p>
							<p>
							<input type="text" name="ecomm_currency_symbol" id="ecomm_currency_symbol" value="<?php echo $ecomm_currency_symbol; ?>" style="width: 95%;" />
							</p>
						</div>
						
					</div>		
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:45%">
							<p class="titles">Shopping Cart Title</p>
							<p>
							<input type="text" name="ecomm_title" id="ecomm_title" value="<?php echo $ecomm_title; ?>" size="50" style="width: 95%;" />
							</p>
						</div>
						
						<div class="inner" style="width:55%">
							<p class="titles">Shopping Cart Empty Text</p>
							<p>
							<input type="text" name="ecomm_empty" id="ecomm_empty" value="<?php echo $ecomm_empty; ?>" size="60" style="width: 95%;" />
							</p>
						</div>
						
					</div>		
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:45%">
							<p class="titles">Add to Cart Button Text</p>
							<p>
							<input type="text" name="ecomm_but_text" id="ecomm_but_text" value="<?php echo $ecomm_but_text; ?>" size="45" style="width: 95%;" />
							</p>
						</div>
						
						<div class="inner" style="width:55%">
							<p class="titles">Add to Cart Button Image</p>
							<p>
							<input type="text" name="ecomm_but_image" id="ecomm_but_image" value="<?php echo $ecomm_but_image; ?>" size="40" style="width: 95%;" />
							<input type="button" class="button" id="add_image_ecomm_but_image" value="Upload Image" style="clear:none;" />
							</p>
						</div>
						
					</div>	 	
				
					<div class="left" style="width:99%">
						
						<p class="notes" style="margin-top:-5px;">
							<b>Note:</b> <i>If you upload an image and paste the image URL in the field, it will override the text button with the image.</i>
						</p>
						
					</div>	
					  
				</div>
		</div>
			
		<div class="sub-options"><span id="comments"><b>Tax Settings</b> - Set the sales tax percentage and name</span></div>
			<div class="option-content">
			
				<div id="option-container">	
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:25%">
							<p class="titles">Tax (blank for none)</p>
							<p>
							<input type="text" name="ecomm_tax" id="ecomm_tax" value="<?php echo $ecomm_tax; ?>" size="3" />%
							</p>
						</div>
						
						<div class="inner" style="width:25%">
							<p class="titles">Tax Name</p>
							<p>
							<input type="text" name="ecomm_tax_name" id="ecomm_tax_name" value="<?php echo $ecomm_tax_name; ?>" size="18" />
							</p>
						</div>
						
						<div class="inner" style="width:50%">
							<p class="titles">Tax Method</p>
							<p>
							<select name="ecomm_tax_method">
								<option value="before"<?php if ($ecomm_tax_method == "before") { echo ' SELECTED'; } ?>>Total Before Shipping</option>
								<option value="after"<?php if ($ecomm_tax_method == "after") { echo ' SELECTED'; } ?>>Total After Shipping</option>
							</select>
							</p>
						</div>
						
					</div>			
				
					<div class="left" style="width:99%">
						
						<p class="notes" style="margin-top:5px;">
							<b>Notes: <i>Use only numbers in the tax box.</b>
							Tax name is optional. If blank, The name "tax" appears in the shopping cart.</i>
						</p>
						
					</div>	 	
					  
				</div>
		
			</div>
	
	
		<div class="sub-options"><span id="comments"><b>Shipping Settings</b> - Set the global shipping options</span></div>
			<div class="option-content">
			
				<div id="option-container">	
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:25%">
							<p class="titles">Standard Price</p>
							<p>
							<input type="text" name="ecomm_ship_st" id="ecomm_ship_st" value="<?php echo $ecomm_ship_st; ?>" size="12" />
							</p>
						</div>
						
						<div class="inner" style="width:25%">
							<p class="titles">Expedited Price</p>
							<p>
							<input type="text" name="ecomm_ship_exp" id="ecomm_ship_exp" value="<?php echo $ecomm_ship_exp; ?>" size="12" />
							</p>
						</div>
						
						<div class="inner" style="width:25%">
							<p class="titles">Price Method</p>
							<p>
							<select name="ecomm_ship_method">
								<option value="total"<?php if ($ecomm_ship_method == "total") { echo ' SELECTED'; } ?>>Flat Rate</option>
								<option value="per"<?php if ($ecomm_ship_method == "per") { echo ' SELECTED'; } ?>>Per Item</option>
							</select>
							</p>
						</div>
						
						<div class="inner" style="width:25%">
							<p class="titles">Free Shipping</p>
							<p>
							Orders over <input type="text" name="ecomm_ship_free" id="ecomm_ship_free" value="<?php echo $ecomm_ship_free; ?>" size="12" />
							</p>
						</div>
						
					</div>	 	
				
					<?php
					$country_list = array(
						'US'	=>	'United States',
						'AF'	=>	'Afghanistan',
						'AL'	=>	'Albania',
						'DZ'	=>	'Algeria',
						'AS'	=>	'American Samoa',
						'AD'	=>	'Andorra',
						'AO'	=>	'Angola',
						'AI'	=>	'Anguilla',
						'AQ'	=>	'Antarctica',
						'AG'	=>	'Antigua And Barbuda',
						'AR'	=>	'Argentina',
						'AM'	=>	'Armenia',
						'AW'	=>	'Aruba',
						'AU'	=>	'Australia',
						'AT'	=>	'Austria',
						'AZ'	=>	'Azerbaijan',
						'BS'	=>	'Bahamas',
						'BH'	=>	'Bahrain',
						'BD'	=>	'Bangladesh',
						'BB'	=>	'Barbados',
						'BY'	=>	'Belarus',
						'BE'	=>	'Belgium',
						'BZ'	=>	'Belize',
						'BJ'	=>	'Benin',
						'BM'	=>	'Bermuda',
						'BT'	=>	'Bhutan',
						'BO'	=>	'Bolivia',
						'BA'	=>	'Bosnia And Herzegowina',
						'BW'	=>	'Botswana',
						'BV'	=>	'Bouvet Island',
						'BR'	=>	'Brazil',
						'IO'	=>	'British Indian Ocean Territory',
						'BN'	=>	'Brunei Darussalam',
						'BG'	=>	'Bulgaria',
						'BF'	=>	'Burkina Faso',
						'BI'	=>	'Burundi',
						'KH'	=>	'Cambodia',
						'CM'	=>	'Cameroon',
						'CA'	=>	'Canada',
						'CV'	=>	'Cape Verde',
						'KY'	=>	'Cayman Islands',
						'CF'	=>	'Central African Republic',
						'TD'	=>	'Chad',
						'CL'	=>	'Chile',
						'CN'	=>	'China',
						'CX'	=>	'Christmas Island',
						'CC'	=>	'Cocos (Keeling) Islands',
						'CO'	=>	'Colombia',
						'KM'	=>	'Comoros',
						'CG'	=>	'Congo',
						'CD'	=>	'Congo, The Democratic Republic Of The',
						'CK'	=>	'Cook Islands',
						'CR'	=>	'Costa Rica',
						'CI'	=>	'Cote D\'Ivoire',
						'HR'	=>	'Croatia (Local Name: Hrvatska)',
						'CU'	=>	'Cuba',
						'CY'	=>	'Cyprus',
						'CZ'	=>	'Czech Republic',
						'DK'	=>	'Denmark',
						'DJ'	=>	'Djibouti',
						'DM'	=>	'Dominica',
						'DO'	=>	'Dominican Republic',
						'TP'	=>	'East Timor',
						'EC'	=>	'Ecuador',
						'EG'	=>	'Egypt',
						'SV'	=>	'El Salvador',
						'GQ'	=>	'Equatorial Guinea',
						'ER'	=>	'Eritrea',
						'EE'	=>	'Estonia',
						'ET'	=>	'Ethiopia',
						'FK'	=>	'Falkland Islands (Malvinas)',
						'FO'	=>	'Faroe Islands',
						'FJ'	=>	'Fiji',
						'FI'	=>	'Finland',
						'FR'	=>	'France',
						'FX'	=>	'France, Metropolitan',
						'GF'	=>	'French Guiana',
						'PF'	=>	'French Polynesia',
						'TF'	=>	'French Southern Territories',
						'GA'	=>	'Gabon',
						'GM'	=>	'Gambia',
						'GE'	=>	'Georgia',
						'DE'	=>	'Germany',
						'GH'	=>	'Ghana',
						'GI'	=>	'Gibraltar',
						'GR'	=>	'Greece',
						'GL'	=>	'Greenland',
						'GD'	=>	'Grenada',
						'GP'	=>	'Guadeloupe',
						'GU'	=>	'Guam',
						'GT'	=>	'Guatemala',
						'GN'	=>	'Guinea',
						'GW'	=>	'Guinea-Bissau',
						'GY'	=>	'Guyana',
						'HT'	=>	'Haiti',
						'HM'	=>	'Heard And Mc Donald Islands',
						'HN'	=>	'Honduras',
						'HK'	=>	'Hong Kong',
						'HU'	=>	'Hungary',
						'IS'	=>	'Iceland',
						'IN'	=>	'India',
						'ID'	=>	'Indonesia',
						'IR'	=>	'Iran (Islamic Republic Of)',
						'IQ'	=>	'Iraq',
						'IE'	=>	'Ireland',
						'IL'	=>	'Israel',
						'IT'	=>	'Italy',
						'JM'	=>	'Jamaica',
						'JP'	=>	'Japan',
						'JO'	=>	'Jordan',
						'KZ'	=>	'Kazakhstan',
						'KE'	=>	'Kenya',
						'KI'	=>	'Kiribati',
						'KP'	=>	'Korea, Democratic People\'S Republic Of',
						'KR'	=>	'Korea, Republic Of',
						'KW'	=>	'Kuwait',
						'KG'	=>	'Kyrgyzstan',
						'LA'	=>	'Lao People\'S Democratic Republic',
						'LV'	=>	'Latvia',
						'LB'	=>	'Lebanon',
						'LS'	=>	'Lesotho',
						'LR'	=>	'Liberia',
						'LY'	=>	'Libyan Arab Jamahiriya',
						'LI'	=>	'Liechtenstein',
						'LT'	=>	'Lithuania',
						'LU'	=>	'Luxembourg',
						'MO'	=>	'Macau',
						'MK'	=>	'Macedonia, Former Yugoslav Republic Of',
						'MG'	=>	'Madagascar',
						'MW'	=>	'Malawi',
						'MY'	=>	'Malaysia',
						'MV'	=>	'Maldives',
						'ML'	=>	'Mali',
						'MT'	=>	'Malta',
						'MH'	=>	'Marshall Islands, Republic of the',
						'MQ'	=>	'Martinique',
						'MR'	=>	'Mauritania',
						'MU'	=>	'Mauritius',
						'YT'	=>	'Mayotte',
						'MX'	=>	'Mexico',
						'FM'	=>	'Micronesia, Federated States Of',
						'MD'	=>	'Moldova, Republic Of',
						'MC'	=>	'Monaco',
						'MN'	=>	'Mongolia',
						'MS'	=>	'Montserrat',
						'MA'	=>	'Morocco',
						'MZ'	=>	'Mozambique',
						'MM'	=>	'Myanmar',
						'NA'	=>	'Namibia',
						'NR'	=>	'Nauru',
						'NP'	=>	'Nepal',
						'NL'	=>	'Netherlands',
						'AN'	=>	'Netherlands Antilles',
						'NC'	=>	'New Caledonia',
						'NZ'	=>	'New Zealand',
						'NI'	=>	'Nicaragua',
						'NE'	=>	'Niger',
						'NG'	=>	'Nigeria',
						'NU'	=>	'Niue',
						'NF'	=>	'Norfolk Island',
						'MP'	=>	'Northern Mariana Islands, Commonwealth of the',
						'NO'	=>	'Norway',
						'OM'	=>	'Oman',
						'PK'	=>	'Pakistan',
						'PW'	=>	'Palau, Republic of',
						'PA'	=>	'Panama',
						'PG'	=>	'Papua New Guinea',
						'PY'	=>	'Paraguay',
						'PE'	=>	'Peru',
						'PH'	=>	'Philippines',
						'PN'	=>	'Pitcairn',
						'PL'	=>	'Poland',
						'PT'	=>	'Portugal',
						'PR'	=>	'Puerto Rico',
						'QA'	=>	'Qatar',
						'RE'	=>	'Reunion',
						'RO'	=>	'Romania',
						'RU'	=>	'Russian Federation',
						'RW'	=>	'Rwanda',
						'KN'	=>	'Saint Kitts And Nevis',
						'LC'	=>	'Saint Lucia',
						'VC'	=>	'Saint Vincent And The Grenadines',
						'WS'	=>	'Samoa',
						'SM'	=>	'San Marino',
						'ST'	=>	'Sao Tome And Principe',
						'SA'	=>	'Saudi Arabia',
						'SN'	=>	'Senegal',
						'SC'	=>	'Seychelles',
						'SL'	=>	'Sierra Leone',
						'SG'	=>	'Singapore',
						'SK'	=>	'Slovakia (Slovak Republic)',
						'SI'	=>	'Slovenia',
						'SB'	=>	'Solomon Islands',
						'SO'	=>	'Somalia',
						'ZA'	=>	'South Africa',
						'GS'	=>	'South Georgia, South Sandwich Islands',
						'ES'	=>	'Spain',
						'LK'	=>	'Sri Lanka',
						'SH'	=>	'St. Helena',
						'PM'	=>	'St. Pierre And Miquelon',
						'SD'	=>	'Sudan',
						'SR'	=>	'Suriname',
						'SJ'	=>	'Svalbard And Jan Mayen Islands',
						'SZ'	=>	'Swaziland',
						'SE'	=>	'Sweden',
						'CH'	=>	'Switzerland',
						'SY'	=>	'Syrian Arab Republic',
						'TW'	=>	'Taiwan',
						'TJ'	=>	'Tajikistan',
						'TZ'	=>	'Tanzania, United Republic Of',
						'TH'	=>	'Thailand',
						'TG'	=>	'Togo',
						'TK'	=>	'Tokelau',
						'TO'	=>	'Tonga',
						'TT'	=>	'Trinidad And Tobago',
						'TN'	=>	'Tunisia',
						'TR'	=>	'Turkey',
						'TM'	=>	'Turkmenistan',
						'TC'	=>	'Turks And Caicos Islands',
						'TV'	=>	'Tuvalu',
						'UG'	=>	'Uganda',
						'UA'	=>	'Ukraine',
						'AE'	=>	'United Arab Emirates',
						'GB'	=>	'United Kingdom',
						'UM'	=>	'United States Minor Outlying Islands',
						'UY'	=>	'Uruguay',
						'UZ'	=>	'Uzbekistan',
						'VU'	=>	'Vanuatu',
						'VA'	=>	'Vatican City, State of the',
						'VE'	=>	'Venezuela',
						'VN'	=>	'Viet Nam',
						'VG'	=>	'Virgin Islands (British)',
						'VI'	=>	'Virgin Islands (U.S.)',
						'WF'	=>	'Wallis And Futuna Islands',
						'EH'	=>	'Western Sahara',
						'YE'	=>	'Yemen',
						'YU'	=>	'Yugoslavia',
						'ZM'	=>	'Zambia',
						'ZW'	=>	'Zimbabwe'
					);
					?>
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:25%">
							<p class="titles">International Shipping</p>
							<p>
							<input type="radio" name="ecomm_ship_en" id="ecomm_ship_en" value="ON" <?php if($ecomm_ship_en === 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="ecomm_ship_en" id="ecomm_ship_en" value="OFF" <?php if($ecomm_ship_en === 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
		   			</div>
						
						<div class="inner" style="width:25%">
							<p class="titles">International Price</p>
							<p>
							<input type="text" name="ecomm_ship_int" id="ecomm_ship_int" value="<?php echo $ecomm_ship_int; ?>" size="12" />
							</p>
						</div>
						<div class="inner" style="width:45%">
							<p class="titles">International Price Method</p>
							<p>
							<select name="ecomm_ship_int_method">
								<option value="total"<?php if ($ecomm_ship_int_method == "total") { echo ' SELECTED'; } ?>>Flat Rate</option>
								<option value="per"<?php if ($ecomm_ship_int_method == "per") { echo ' SELECTED'; } ?>>Per Item</option>
							</select>
							</p>
						</div>
						
					</div>		
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:50%">
							<p class="titles">Your Country</p>
							<p>
							<select name="ecomm_country">
								<?php foreach ($country_list as $k => $v) { ?>
								
									<option value="<?php echo $k; ?>"<?php if ($ecomm_country == $k) { echo ' SELECTED'; } ?>><?php echo $v; ?></option>
									
								<?php } ?>
							</select>
							</p>
						</div>
						
							<div class="clear"></div>
						
						<p class="notes" style="margin-top:-5px; clear: both;">
							<b>Notes: <i>Use only numbers in the shipping price and free shipping boxes.</b>
							Expedited shipping will only show up on the checkout if you fill in that field. Selecting Flat Rate as the shipping
							price method will make the shipping total the amount specified. Selecting Per Item will multiply the shipping price
							by the total number of items in the cart. If you turn off International Shipping your customers will only be able
							to order and ship within your country.</i>
						</p>
						
					</div>	
					  
				</div>
		
			</div>
			
		<div class="sub-options"><span id="comments"><b>Checkout Note</b> - Set the note in the footer of the checkout</span></div>
			<div class="option-content">
			
				<div id="option-container">	
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:100%">
							<p class="titles">Checkout Note</p>
							<p>
							<textarea name="ecomm_note" id="ecomm_note" cols="80" rows="4"><?php echo stripslashes($ecomm_note); ?></textarea>
							</p>
						</div>
						
					</div>		
					  
				</div>
		
			</div>
	
			
		<div class="sub-options"><span id="comments"><b>Advanced Settings</b></span></div>
			<div class="option-content">
			
				<div id="option-container">	
				
					<div class="left" style="width:99%">
						
						<div class="inner" style="width:100%">
							<p class="titles">PayPal Profile Based Shipping</p>
							<p>
							<input type="radio" name="pp_profile" id="pp_profile" value="ON" <?php if($pp_profile == 'ON') {echo'CHECKED';} ?> /> On &nbsp;&nbsp;
							<input type="radio" name="pp_profile" id="pp_profile" value="OFF" <?php if($pp_profile == 'OFF') {echo'CHECKED';} ?> /> Off
							</p>
						</div>
						
					</div>	 		
				
					<div class="left" style="width:99%">
						
						<p class="notes" style="margin-top:-5px;">
							<b>Notes: <i>You must have Profile Based Shipping enabled in your PayPal account</b> to use this option. By turning
							on this option you will disable the shipping address fields in your checkout. All shipping information will be
							collected on PayPal.</i>
						</p>
						
					</div>	
					  
				</div>
			
			
		</div>
			
		
		<div class="option-content" style="border-top:0;">
			<div id="option-container" style="padding-top:5px;margin-top:5px;"> 
				<div class="submit-button-wrapper">
					<input type="button" class="button" id="update-global" value="Save E-Commerce Settings" style="clear:none;" /> 
				</div>
				<div id="msg" style="float:left;"></div>
			</div>
									  
			</form>	
		</div>
	
	</form>
		
</div>
