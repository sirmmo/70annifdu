<?php
// All cart information is stored in $_SESSION, which looks like this:
//$_SESSION = array(
//  'cart'		=>  '1,2,3' # image #1, #2, and #3 are added to the cart,
//  'cart_qty'	=>  '2|1|10|5.00,2|2|5|15.00' # For image #2, add 10x of option 1, for a total of $5.00.
//);
class Photocrati_Shopping_Cart
{
	/*
	 * array(
	 *	  [1 (product_id)] =>  array(

			);
	 * );
	 */
	var $contents = array();
	static $_instance = NULL;

	/**
	 * @return Photocrati_Shopping_Cart
	 */
	static function get_instance()
	{
		if (is_null(self::$_instance)) {
			$klass = get_class();
			self::$_instance = new $klass();
		}
		return self::$_instance;
	}

	/**
	 * Parses the cart contents in the session
	 */
	private function __construct()
	{
		// Parse the session information
		if (isset($_SESSION['cart']) && strlen($_SESSION['cart']) > 0)  {
			$product_ids = explode(',', $_SESSION['cart']);
			foreach ($product_ids as $product_id) {
				$this->contents[$product_id] = array();
			}

			// Parse ecommerce options (print sizes)
			if (isset($_SESSION['cart_qty'])) {
				$items = explode(',', $_SESSION['cart_qty']);
				foreach ($items as $item) {
					$parts = explode('|', $item);
					$product_id	 = $parts[0];
					$option_number  = $parts[1];
					$this->contents[$product_id][$option_number]['option_number'] = $parts[1];
					$this->contents[$product_id][$option_number]['quantity']	  = $parts[2];
					$this->contents[$product_id][$option_number]['item_total']	= $parts[3];
				}
			}
		}
		else {
			$this->contents = array();
		}
	}

	/**
	 * Writes the shopping cart contents to the session
	 */
	function write_session()
	{
		// Ensure that no product with an id of 0 is used.
		unset($this->contents[0]);

		// Create cart session variable
		$_SESSION['cart'] = implode(",", array_keys($this->contents));

		// Create item qty session variable
		$session_cart_items = array();
		foreach ($this->contents as $product_id => $items) {
			foreach ($items as $option_number => $item) {
				$session_cart_items[] = "{$product_id}|{$option_number}|{$item['quantity']}|{$item['item_total']}";
			}
		}
		$_SESSION['cart_qty'] = implode(",", $session_cart_items);
	}

	/**
	 * Returns the total value of the cart
	 * @return float
	 */
	function get_total($product_id)
	{
		$retval = 0.00;
		foreach ($this->get_options($product_id) as $number => $item) {
			$retval += (floatval($item['option_value']) * intval($item['quantity']));
		}
		return $retval;
	}

	/**
	 * Get options for a particular product
	 * @param $product_id
	 * @return array
	 */
	function get_options($product_id, $only_in_cart=FALSE)
	{
		$retval = array();

		// Get the image from the database to determine the option exclusions
		global $wpdb;
		$image = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}photocrati_galleries WHERE id = %d", $product_id));
		$image_options = explode(',', $image->ecomm_options);

		// Get all available e-commerce options
		$options = Photocrati_Ecommerce_Options::get_instance();
		$options = $options->options;

		// Only show the options which have values for option_name and option_value
		// Also, add quantity and total
		foreach ($options as $option_number => $params) {
			extract($params);
			if (strlen($option_name) > 0 && strlen($option_value) > 0 && in_array($option_number, $image_options)) {
				if (isset($this->contents[$product_id][$option_number])) {
					$cart_item = $this->contents[$product_id][$option_number];
					$params['quantity']	 = $cart_item['quantity'];
					$params['item_total']   = $cart_item['item_total'];
				}
				elseif($only_in_cart) {
					continue;
				}
				else {
					$params['quantity'] = 0;
					$params['item_total'] = 0.0;
				}
				$retval[$option_number] = $params;
			}
		}
		return $retval;
	}

	/**
	 * Gets the preferred currency symbol
	 * @param $currency_code
	 * @return string
	 */
	static function get_preferred_currency_symbol($currency_code, $currency_symbol = null)
	{
    if (isset($currency_symbol) && $currency_symbol != null)
    {
    	return $currency_symbol;
    }
    
    return self::get_currency_symbol_for($currency_code);
	}

	/**
	 * Gets the symbol for a particular currency
	 * @param $currency_code
	 * @return string
	 */
	static function get_currency_symbol_for($currency_code)
	{
		// Determine what currency symbol to use
		$currency_symbol = '$';

		switch($currency_code) {
			case 'USD':
			case 'CAD':
			case 'AUD':
			case 'NZD':
			case 'HKD':
			case 'SGD':
				$currency_symbol = '$';
				break;
			case 'EUR':
				$currency_symbol = '&euro;';
				break;
			case 'GBP':
				$currency_symbol = '&pound;';
				break;
			case 'JPY':
				$currency_symbol = '&yen;';
				break;
		}

		return $currency_symbol;
	}

	static function render_add_to_cart_form($product_id, $currency, $currency_symbol = null)
	{
		$cart		  = self::get_instance();
		$thumbnail_src = self::get_thumbnail_for($product_id);
		$options	   = $cart->get_options($product_id);
		$cart_total	= $cart->get_total($product_id);
		$currency_symbol = self::get_preferred_currency_symbol($currency, $currency_symbol);

		include('templates/edit_cart.php');
	}

	/**
	 * Gets the product name that will appear in PayPal
	 * @param $image_id
	 * @param $option_name
	 */
	static function get_product_name($image_id, $option_name=FALSE)
	{
		global $wpdb;

		$retval = '';

		// Get the image object from the database
		global $wpdb;
		$image = $wpdb->get_row($wpdb->prepare(
			"SELECT *, gallery_id, image_name AS name FROM {$wpdb->prefix}photocrati_galleries WHERE id = %d",
			intval($image_id)
		));


		if ($image) {
			// Get the gallery title
			$gallery_id = $image->gallery_id;
			$gallery = $wpdb->get_row($wpdb->prepare(
				"SELECT gal_title AS title FROM {$wpdb->prefix}photocrati_gallery_ids WHERE gallery_id = %s",
				$gallery_id
			));
			if ($gallery) {
				$retval = "Gallery: {$gallery->title} | Image: {$image->name} | {$option_name}";
			}
		}

		return $retval;
	}

	static function get_thumbnail_for($image_id)
	{
		$retval = '';

		// Get the image object from the database
		global $wpdb;
		$image = $wpdb->get_row($wpdb->prepare(
		   "SELECT gallery_id, post_id, image_name FROM {$wpdb->prefix}photocrati_galleries WHERE id = %d",
		   intval($image_id)
		));

		if ($image) {
			// Legacy code. Not entirely sure why this is required
			$image->image_name = str_replace("%", '%25', $image->image_name);

			// Find the thumbnail
			$upload_dir = photocrati_gallery_wp_upload_dir();
			$retval = "{$upload_dir['basedir']}/galleries/post-{$image->post_id}/thumbnails/{$image->image_name}";
			if (file_exists($retval)) {
				$retval = str_replace(ABSPATH, trailingslashit(site_url()), $retval);
			}
			// Use old upload directory
			else {
				$retval = get_bloginfo('template_url')."/galleries/post-{$image->post_id}}/thumbnails/{$image->image_name}";
			}
		}

		return $retval;
	}
	
	static function get_lightbox_opener_js($context = null, $type = null, $selector = null) 
	{
		$output = null;
		
		// $context is where the lightbox JS is being requested, i.e. for a gallery or for shopping cart page "gallery" or "cart"
		if ($context == null) {
			$context = 'gallery';
		}
		
		if ($type == null) {
			$type = 'modern';
		}
		
		if ($selector == null) {
			$selector = 'a.iframe';
		}
		
		if ($type != 'legacy') {
			$output .= '
		jQuery("' . $selector . '").magnificPopup({
			type			: \'iframe\',
			
			callbacks: {
				open: function() {
					var tags = jQuery("html,body");
					if (typeof(window.webkitURL) != "undefined") tags.addClass("ecommerce_lightbox");
		      tags.css("overflow", "hidden");
				},
				close: function() {
			  	jQuery("html,body").removeClass("ecommerce_lightbox").css("overflow", "auto");
				},
				resize: function() {
					  var iframe = this.content.find(\'iframe\');
					  var holder = iframe.parents(".mfp-iframe-holder");
					  
					  if (iframe.width() > 600) {
							iframe.css("min-height", "initial");
							holder.css("padding-top", "40px");
							holder.css("padding-bottom", "40px");
				  		holder.removeClass("photocrati-ecommerce-popup-mobile");
					  }
					  else {
							iframe.css("min-height", jQuery(window).height() - 45);
							holder.css("padding-top", "35px");
							holder.css("padding-bottom", "10px");
				  		holder.addClass("photocrati-ecommerce-popup-mobile");
					  }
				},
				markupParse: function(template, values, item) {
				  var iframe = template.find(\'iframe\');
				  var holder = iframe.parents(".mfp-iframe-holder");
				  holder.addClass("photocrati-ecommerce-popup");
				}
			}
		});
';
		}
		else {
			$output .= '
		jQuery("' . $selector . '").fancybox({
			\'width\'				: 650,
			\'height\'			: 500,
			\'autoScale\'     	: false,
			\'transitionIn\'	: \'elastic\',
			\'transitionOut\'	: \'elastic\',
			\'overlayColor\'	: \'#0b0b0f\',
			\'type\'			: \'iframe\',';
			
			if ($context != 'cart') {
				$output .= '
			onStart             : function(){
			  var tags = jQuery("html,body");
			  if (typeof(window.webkitURL) != "undefined") tags.addClass("ecommerce_lightbox");
              tags.css("overflow", "hidden");
			},
			onClosed            : function(){
			  jQuery("html,body").removeClass("ecommerce_lightbox").css("overflow", "auto");
			}';
			}
			
			$output .= '
		});
';
		}
		
		return $output;
	}

	/**
	 * Adds a cart item
	 * @param $product_id
	 * @param $option_id
	 * @param $quantity
	 * @return int (the number of products in the cart (not including the number of options))
	 */
	function add_item($product_id, $option_id, $quantity)
	{
		$options = Photocrati_Ecommerce_Options::get_instance();
		$price   = $options->get_unit_price_for($option_id);

		if (!is_array($this->contents[$product_id])) {
			$this->contents[$product_id] = array();
		}

		if ($quantity > 0) {
			if (!is_array($this->contents[$product_id][$option_id])) {
				$this->contents[$product_id][$option_id] = array(
					'quantity'	=>  0.0,
					'item_total'  =>  0.0
				);
			}

			if ($price !== FALSE) {
				$this->contents[$product_id][$option_id]['quantity'] = floatval($quantity);
				$this->contents[$product_id][$option_id]['item_total'] = floatval(floatval($quantity) * $price);
			}
		}
		else {
			unset($this->contents[$product_id][$option_id]);
			if (count($this->contents[$product_id]) == 0) {
				unset($this->contents[$product_id]);
			}
		}

		return count(array_keys($this->contents));
	}

	function destroy()
	{
		$this->contents = array();
		$_SESSION['cart_qty'] = $_SESSION['cart'] = '';
	}
}
