
function photocrati_theme_admin_string_format()
{
  var args = arguments;
  
  return this.replace(/{(\d+)}/g, function(match, number) { 
    return typeof args[number] != 'undefined'
      ? args[number]
      : match
    ;
  });
}

function photocrati_theme_admin_readable_size(bytes) 
{
  var sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB'];
  if (bytes == 0) return typeof(bytes) == 'number' ? '0 bytes' : 'n/a';
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

String.prototype.format = function() { return photocrati_theme_admin_string_format.apply(this, arguments); };

function photocrati_theme_admin_expand_json(json)
{
	var expanded = null;
	try
	{
		expanded = jQuery.parseJSON(json);
	}
	catch (ex) 
	{
		expanded = jQuery.parseJSON(jQuery('<div/>').html(json).text());
	}
	
	return expanded;
}

var Photocrati_ThemeOptions_Admin = {
	logMsg : function (msg)
	{
		if (console != undefined && typeof(console.log) == 'function') {
			console.log(msg);
		}
	},
	
	performRequest : function (action, params, extra) 
	{
		if (params == undefined || params == null) {
			params = {}
		}
		
		if (extra == undefined || extra == null) {
			extra = {}
		}
		
		var submitData = {};
		submitData['action'] = 'photocrati_theme_options_admin_handle';
		submitData['actionSec'] = Photocrati_ThemeOptions_Settings.actionSec;
		submitData['innerAction'] = action;
		submitData['actionParams'] = params;
		
		var settings = {
			type: 'POST', 
			url: Photocrati_ThemeOptions_Settings.ajaxurl, 
			//dataType: 'json', 
			data: submitData 
		};
		
		if (extra.success) {
			settings.success = extra.success;
		}
		if (extra.error) {
			settings.error = extra.error;
		}
		if (extra.complete) {
			settings.complete = extra.complete;
		}
		
		var jreq = jQuery.ajax(settings);
		
		return jreq;
	},
	
	displayStyleMessage : function (msg, msgTxt, msgClass)
	{
		if (msgClass != null)
			msg.addClass(msgClass);

		msg.html(msgTxt);
		msg.animate({opacity: 1.0}, 2000)
			 .fadeOut('slow', function () {
					if (msgClass != null)
						msg.removeClass(msgClass);
			 });
	},
	
	getContainerItemList : function (container)
	{
		if (container === undefined || container == null || container.size() == 0) {
			return false;
		}

		var formData = container.serializeArray();
		var itemList = {};
		var multiList = {};
	
		for (var i in formData)
		{
			var name = formData[i].name;
			var value = formData[i].value;
			var item = container.find('[name="' + formData[i].name + '"]');
			
			if (item.prop('tagName').toLowerCase() == 'select' && item.attr('multiple'))
			{
				var sortName = name + '_autoSort';
				
				if (!(name in itemList))
				{
					itemList[name] = [];
					itemList[sortName] = {};
				}
				
				itemList[name].push(value);
				
				var option = item.find('option[value="' + value + '"]');
				
				if (option.size() > 0 && option.attr('data-selectTime')) 
				{
					itemList[sortName][option.attr('data-selectTime')] = value;
				}
			}
			else
			{
				itemList[name] = value;
			}
		}
		
		return itemList;
	},
	
	getContainerElementList : function (container, itemList)
	{
		if (container === undefined || container == null || container.size() == 0) {
			return false;
		}
		
		if (itemList === undefined || itemList == null) {
			itemList = this.getContainerItemList(container);
		}
		
		var elementList = {};
		
		for (var itemKey in itemList) {
			var item = itemList[itemKey];
			var elem = container.find('[name="' + itemKey + '"]');
			
			if (elem.size() > 0) {
				elementList[itemKey] = elem;
			}
		}
		
		return elementList;
	},
	
	// container needs to be a form for now
	performRequestWithFeedback : function (action, itemList, msg, parent, elementList)
	{
		if (msg === undefined || msg == null) {
			// empty element
			msg = '<div />';
		}
		
		if (parent === undefined || parent == null) {
			// empty element
			parent = '<div />';
		}
		
		if (elementList === undefined || elementList == null) {
			// no elements to display error icons on
			elementList = {};
		}
		
		if (typeof(msg) == 'string') {
			msg = jQuery(msg);
		}
		
		if (typeof(parent) == 'string') {
			parent = jQuery(parent);
		}
		
		var msgTxt = '';
		// XXX temp exception...
		if (msg.attr('id') == 'msgpages') {
			msgTxt = 'Creating pages - please wait...';
		}
		
		msg.removeClass('msg-error');
		msg.html('<img src="' + Photocrati_ThemeOptions_Settings.ajaxloader + '"> ' + msgTxt);
		msg.show();
		
		return this.performRequest(
			action, { 'item-list' : itemList }
		)
		.done(
			function(data, textStatus, jqXHR)
			{
				var msgTxt = null;
				var msgClass = null;
			
				var failedCount = 0;
				var failedObj = {};
				var failedList = {};
			
				if ('failed-list' in data) {
					failedObj = data['failed-list'];
				
					for (var key in failedObj) {
						if (failedObj.hasOwnProperty(key)) {
							failedList[key] = failedObj[key];
							failedCount += 1;
						}
					}
				}
			
				if (data.result == 'OK' && failedCount == 0) {
					msgTxt = 'Settings Saved';
					// XXX temp exception...
					if (msg.attr('id') == 'msgpages') {
						msgTxt = 'Pages Created Successfully!';
					}
				}
				else {
					msgTxt = 'Settings Failed';
					msgClass = 'msg-error';
				}
			
				Photocrati_ThemeOptions_Admin.displayStyleMessage(msg, msgTxt, msgClass);
			
				var failedParent = parent;
				var validateCont = failedParent.find('.validation-container');
			
				if (validateCont.size() == 0) {
					validateCont = jQuery('<div class="validation-container validation-error"><div class="validation-message"></div></div>');
					failedParent.prepend(validateCont);
				}
			
				var validateMsg = validateCont.find('.validation-message');
			
				if (failedCount > 0) {
					for (var failedKey in failedList) {
						var failedItem = failedList[failedKey];
					
						if (failedKey in elementList) {
							var elem = elementList[failedKey];
						
							if (elem.size() > 0) {
								var elemParent = elem.parent();
								var label = elemParent.siblings('.titles');
								validateMsg.append(jQuery('<div />').append('Failed validating <b>' + label.text() + '</b>: <i>' + failedItem['error'] + '</i>'));
								label.addClass('validation-error-field');
							}
						}
						else {
							// XXX display alert() ?
						}
					}
				
					validateCont.show('slow');
				}
				else {
					validateCont.hide('fast');
					validateMsg.html('');
				
					for (elemKey in elementList) {
						if (elementList.hasOwnProperty(elemKey)) {
							var elem = elementList[elemKey];
					
							if (elem.size() > 0) {
								var elemParent = elem.parent();
								var label = elemParent.siblings('.titles');
								label.removeClass('validation-error-field');
							}
						}
					}
				}
				
				Photocrati_ThemeOptions_Admin.logMsg(data);
			}
		)
		.fail( 
			function(jqXHR, textStatus, errorThrown)
			{
			
			}
		)
		.always(
			function(jqXHR, textStatus)
			{
			}
		);
	},
	
	// container needs to be a form for now
	updateStyles : function (itemList, msg, parent, elementList)
	{
		if (elementList === undefined || elementList == null) {
			// no elements to display error icons on
			elementList = {};
		}
		
		var jqxhr = this.performRequestWithFeedback('update-styles', itemList, msg, parent, elementList);
		
		jqxhr.done(
			function(data, textStatus, jqXHR)
			{
				var itemList = {};
				
				if ('item-list' in data) {
					itemList = data['item-list'];
				}
				
				for (var itemKey in itemList) {
					if (itemList.hasOwnProperty(itemKey)) {
						var item = itemList[itemKey];
						var itemStrip = item;
						
						if (typeof(itemStrip) == 'string') {
							itemStrip =	itemStrip.replace(/\\(.?)/g, 
								function (s, n1) {
									switch (n1) {
										case '\\':
											return '\\';
										case '0':            
											return '\u0000';
										case '':
											return '';
										default:
											return n1;
									}
								}
							);
						}
						
						if (itemKey in elementList) {
							var elem = elementList[itemKey];
							
							if (elem.size() > 0) {
								var val = elem.val();
								
								switch (elem.attr('type')) {
									case 'radio':
									case 'checkbox':
									{
										var checked = elem.filter(':checked');
										
										if (checked.size() > 0) {
											val = checked.val();
										}
										else {
											val = null;
										}
							
										if (val != item && val != itemStrip) {
											Photocrati_ThemeOptions_Admin.logMsg("VALUE DIFFERS : " + val + ' // ' + itemStrip);
											
											var target = elem.filter('[value="' + itemStrip + '"');
											
											if (target.size() > 0) {
												target.attr('checked', 'checked');
											}
										}
										
										break;
									}
									case 'select':
									{
										val = elem.filter(':selected');
									}
									case 'text':
									default:
									{
										if (val != item && val != itemStrip) {
											Photocrati_ThemeOptions_Admin.logMsg("VALUE DIFFERS : " + val + ' // ' + itemStrip);
								
											elem.val(itemStrip);
										}
										
										break;
									}
								}
							}
						}
					}
				}
				
				// XXX temporary exceptions, remove for 4.5?
				var noImgMsg = '<em>There is currently no image uploaded</em>';
				
				if ('bg_image' in itemList && itemList['bg_image'] == '') {
					jQuery("#fileName2").html(noImgMsg);
				}
				
				if ('header_bg_image' in itemList && itemList['header_bg_image'] == '') {
					jQuery("#fileNameh").html(noImgMsg);
				}
				
				if ('custom_logo_image' in itemList && itemList['custom_logo_image'] == '') {
					jQuery("#fileNamecl").html(noImgMsg);
				}
			}
		);
		
		return jqxhr;
	},
	
	// container needs to be a form for now
	submitStyleForm : function (container, msg, error)
	{
		if (typeof(container) == 'string') {
			container = jQuery(container);
		}
		
		var itemList = this.getContainerItemList(container);
		
		if (itemList == false) {
			this.displayStyleError(msg, 'No Valid Container', 'msg-error');
			
			return;
		}
		
		var parent = container.parents('.option-content');
		var elementList = this.getContainerElementList(container, itemList);
		
		var jqxhr = this.updateStyles(itemList, msg, parent, elementList);
		
		jqxhr.done(
			function(data, textStatus, jqXHR)
			{
				// XXX temporary exceptions, remove for 4.5?
				var contId = container.attr('id');
				
				switch (contId) {
					case 'logo-options': 
					{
						if(jQuery('#custom_logo_image').length > 0) {
							jQuery("#fileName").html(jQuery('#custom_logo_image').val()+'');
						}
						
						break;
					}
					case 'header-options': 
					{
						if(jQuery('#header_bg_image').length > 0) {
							jQuery("#fileNameh").html(jQuery('#header_bg_image').val()+'');
						}
						
						break;
					}
					case 'layout-options': 
					{
						var skinSel = jQuery('form#theme-skin select[name="style_skin"]');
						var modern = skinSel.val() == 'modern';
						
						if (modern) {
							jQuery("#full_width_wrapper").hide();
						}
						else {
							if(jQuery("input[name='one_column']:checked").val() == "ON") {
								jQuery("#full_width_wrapper").show();
							} else {
								jQuery("#full_width_wrapper").hide();
							}
						}
						
						break;
					}
					case 'bg-options': 
					{
						if(jQuery('#bg_image').length > 0) {
							jQuery("#fileName").html(jQuery('#bg_image').val()+'');
						}
						
						break;
					}
					case 'dynamic-styling':
					{
						window.location = window.location;
						
						break;
					}
				}
			}
		);
		
		return jqxhr;
	},
	
	updateLogoPosition : function (position, msg) 
	{
		// legacy UI tweak
		jQuery('[id^=msgmp_]').each(function(index) {
			jQuery(this).fadeOut('fast');
		});
		
		var elementList = this.getContainerElementList(jQuery('#header-sizes'), { 'header_logo_margin_above' : '', 'header_logo_margin_below' : '' });
		
		var jqxhr = this.updateStyles({ 'logo_menu_position' : position }, null, null, elementList);
		
		jqxhr.done(
			function(data, textStatus, jqXHR)
			{
				// other UI tweaks, for legacy compatibility
				if (msg != null) {
					if (typeof(msg) == 'string') {
						msg = jQuery(msg);
					}
					
					msg.fadeIn('slow');
				}
				
				// XXX remove these exceptions?
				if (position == 'bottom-top' || position == 'top-bottom' || jQuery('#one_column_logo').val() != 'ON') {
					jQuery('#right-lg-margin').hide();
					jQuery('#left-sm-margin').hide();
				} else {
					jQuery('#right-lg-margin').show();
					jQuery('#left-sm-margin').show();
				}
			}
		);
	},
	
	createDefaultPages : function (container, msg) 
	{
		if (typeof(container) == 'string') {
			container = jQuery(container);
		}
		
		var itemList = this.getContainerItemList(container);
		
		if (itemList === false) {
			this.displayStyleError(msg, 'No Valid Container', 'msg-error');
			
			return;
		}
		
		var jqxhr = this.performRequestWithFeedback('create-default-pages', itemList, msg);
	}
};
