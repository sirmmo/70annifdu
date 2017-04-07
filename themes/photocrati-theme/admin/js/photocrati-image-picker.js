jQuery.noConflict();

jQuery(document).ready(function ($) {
	
	var refreshPickerSelection = function (picker) {
		if (typeof(picker) === 'undefined') {
			picker = jQuery('select.image-picker');
		}
		
		picker.each(function (i, el) {
			var jthis = jQuery(el);
			var pickerOb = jthis.data('picker');
		
			if (pickerOb) {
				pickerOb.sync_picker_with_select();
			}
			
			var name = jthis.val();
			var cont = jthis.parents('.photocrati-image-picker-container');
			var toolbar = cont.find('.photocrati-image-picker-toolbar');
			var option = jthis.find('option[value="' + name + '"]');
			
			if (option.parents('optgroup').attr('data-name') == 'user') {
				toolbar.find('.picker-action-remove').removeAttr('disabled').removeClass('button-disabled');
			}
			else {
				toolbar.find('.picker-action-remove').attr('disabled', 'disabled').addClass('button-disabled');
			}
			
			if (name != ''){
				toolbar.find('.picker-action-view').removeAttr('disabled');
			}
			else {
				toolbar.find('.picker-action-view').attr('disabled', 'disabled');
			}
		});
	};
	
	var refreshPicker = function (picker) {
		if (typeof(picker) === 'undefined') {
			picker = jQuery('select.image-picker');
		}
		
		if (!picker.data('picker')) {
			picker.on('change', function () {
				refreshPickerSelection(jQuery(this));
			});
		}
		
		picker.imagepicker({
		  hide_select : true,
		  show_label  : false,
		  selected: function (event) {
		  	var option = event.option;
		  	var now = new Date().getTime();
		  	option.attr('data-selectTime', now);
		  }
		});
		
		refreshPickerSelection(picker);
	};
	
	var pickerAddImage = function (name, item, picker) {
		if (typeof(picker) === 'undefined') {
			picker = jQuery('select.image-picker');
		}
		
		var donePickers = jQuery([]);
		
		picker.each(function (i, el) {
			var jthis = jQuery(el);
			var userGroup = jthis.find('optgroup[data-name="user"]');
			
			if (userGroup.size() > 0) {
				if (userGroup.find('option[value="' + name + '"]').size() == 0) {
					var option = jQuery('<option></option>');
					option.attr('value', name);
					option.attr('data-img-src', item.file);
					option.attr('data-imagesrc', item.file);
					option.append(name);
			
					userGroup.append(option);
				
					donePickers = donePickers.add(jthis);
				}
			}
		});
		
		refreshPicker(donePickers);
	};
	
	var pickerRemoveImage = function (name, picker) {
		if (typeof(picker) === 'undefined') {
			picker = jQuery('select.image-picker');
		}
		
		var donePickers = jQuery([]);
		
		picker.each(function (i, el) {
			var jthis = jQuery(el);
			var option = jthis.find('option[value="' + name + '"]');
			
			if (option.parents('optgroup').attr('data-name') == 'user') {
				if (jthis.val() == name) {
					jthis.val('');
				}
			
				option.remove();
				
				donePickers = donePickers.add(jthis);
			}
		});
		
		refreshPicker(donePickers);
	};

	$('.picker-button').on('click', function (evt) {
		evt.preventDefault();
		
		var jthis = $(this);
		var select = jthis.parents('.photocrati-image-picker-container').find('select.image-picker');
		var sync = false;
		
		if (jthis.hasClass('picker-action-deselect')) {
			select.val('0');
			
			sync = true;
		}
		else if (jthis.hasClass('picker-action-view')) {
			var current = select.val();
			
			if (typeof(current) === 'object') {
				var value = current;
				
				for (var i = 0; i < value.length; i++) {
					if (value[i]) {
						current = value[i];
						
						break;
					}
				}
			}
			
			var option = select.find('option[value="' + current + '"]');
			
			$.fancybox({
				href : option.attr('data-img-full'),
				type : 'image'
			});
		}
		else if (jthis.hasClass('picker-action-add')) {
		  var custom_uploader = wp.media({
		      title: 'Select Image',
		      button: {
		          text: 'Select Image'
		      },
		      multiple: true  // Set this to true to allow multiple files to be selected
		  })
		  .on('select', function() {
		  		var selection = custom_uploader.state().get('selection');
		  		var count = selection.size();
		  		var selectedItem = null;
		  		var itemList = [];
		  		
		  		for (var i = 0; i < count; i++)
		  		{
		      	var attachment = selection.at(i).toJSON();
				    var item = {
				    	'name' : attachment.id, 
				    	'file' : attachment.url
				    };
				    
				    itemList.push(item);
				    
				    pickerAddImage(item.name, item);
				    
				    selectedItem = item;
		  		}
		  		
			    if (typeof(Photocrati_ThemeOptions_Admin) !== 'undefined') {
			    	Photocrati_ThemeOptions_Admin.performRequest(
			    		'image-list-add',
			    		{ 'item-list' : itemList }
			    	);
			    }
		  		
		  		if (selectedItem != null && !select.attr('multiple'))
		  		{
		      	select.val(selectedItem.name);
		      	refreshPicker(select);
		  		}
		  		
		  })
		  .open();
		}
		else if (jthis.hasClass('picker-action-remove')) {
			var value = select.val();
			var itemList = [];
			
			if (typeof(value) !== 'object') {
				value = [ value ];
			}
			
			for (var i = 0; i < value.length; i++) {
				var val = value[i];
				
				if (val != '' && val != 0) {
				  var item = {
				  	'name' : val
				  };
					
					itemList.push(item);
						  
				  pickerRemoveImage(item.name);
				}
			}
			
		  if (typeof(Photocrati_ThemeOptions_Admin) !== 'undefined') {
		  	Photocrati_ThemeOptions_Admin.performRequest(
		  		'image-list-remove',
		  		{ 'item-list' : itemList }
		  	);
		  }
		}
		
		if (sync) {
			select.data('picker').sync_picker_with_select();
		}
		
		return false;
	});
	
	refreshPicker();
});
