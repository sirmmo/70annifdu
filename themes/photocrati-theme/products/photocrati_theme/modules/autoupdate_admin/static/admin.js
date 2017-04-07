
function photocrati_autoupdate_string_format()
{
  var args = arguments;
  try {
      return this.replace(/{(\d+)}/g, function (match, number) {
          return typeof args[number] != 'undefined'
              ? args[number]
              : match
              ;
      });
  } catch(error) {
      // in case 'this' doesn't have a replace method
      return this;
  }
}

function photocrati_autoupdate_readable_size(bytes) 
{
  var sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB'];
  if (bytes == 0) return typeof(bytes) == 'number' ? '0 bytes' : 'n/a';
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  return Math.ceil(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

String.prototype.format = function() { return photocrati_autoupdate_string_format.apply(this, arguments); };

function photocrati_autoupdate_expand_json(json)
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

var Photocrati_AutoUpdate_Admin = {
	performRequest : function (action, params) {
		if (params == undefined || params == null) {
			params = {}
		}
	},
	
	updateStart : function (updater, textList) 
	{
		var updaterMessage = updater.find('.details .message');
		var updaterLog = updater.find('.details .log');
		var updaterProgress = updater.find('.progress-bar');
		
		updaterMessage.html(textList['updater_status_preparing']);
		
		updater.data('update-index', 0);
		updater.data('update-stage', 'download');
		
		updaterLog.html('');
		updaterProgress.progressbar('option', 'value', 1);
		updaterProgress.show();
		
		this.updateContinue(updater, textList);
	},
	
	updateContinue : function (updater, textList) 
	{
		var updaterMessage = updater.find('.details .message');
		var updaterLog = updater.find('.details .log');
		var updaterProgress = updater.find('.progress-bar');
		var updateList = updater.data('update-list');
		var updateIndex = updater.data('update-index') || 0;
		var updateStage = updater.data('update-stage');
		
		if (updateIndex >= updateList.length)
		{
			switch (updateStage)
			{
				case 'download':
				{
					updater.data('update-stage', 'install');
					
					break;
				}
				case 'install':
				{
					updater.data('update-stage', 'activate');
					
					break;
				}
				case 'activate':
				{
					updater.data('update-stage', 'cleanup');
					
					break;
				}
				default:
				{
					break;
				}
			}
			
			var nextStage = updater.data('update-stage');
			
			if (updateStage == nextStage)
			{
				updaterMessage.html('<span style="color:#44aa44;font-weight:bold;">' + textList['updater_status_done'] + '</span>');
				updater.find('.details .button-update-start').hide();
				updater.find('.details .button-update-done').css('display', 'inline');
				updaterLog.append('Update Completed.\n');
				updaterProgress.progressbar('option', 'value', 100);
			
				return;
			}
			
			updaterLog.append('Completed queue for stage "{0}", next queue stage is "{1}".\n'.format(updateStage, nextStage));
			
			updater.data('update-index', 0);
			updateIndex = updater.data('update-index') || 0;
			updateStage = nextStage;
		}
		
		var updateItem = updateList[updateIndex];
		var updateInfo = updateItem['info'];
		var itemStage = updateInfo['-command-stage'] || 'download';
		var stageMsgKey = 'updater_status_stage_' + updateStage;
		
		if (stageMsgKey in textList)
		{
			updaterMessage.html(textList['updater_status_stage_' + updateStage].format(updateList.length, updateIndex + 1));
		}
		
		if (itemStage == updateStage)
		{
			var submitData = updater.data('form-submit-data') || {};
			var performingMsg = 'Performing stage "{1}" request for "{0}"...';
			
			submitData['action'] = 'photocrati_autoupdate_admin_handle';
			submitData['actionSec'] = Photocrati_AutoUpdate_Admin_Settings.actionSec;
			
			if (updateStage == 'activate')
			{
				updaterLog.append(performingMsg.format('&lt;All&gt;', updateStage));
			
				submitData['update-action'] = 'handle-list';
				submitData['update-list'] = updateList;
			}
			else
			{
				updaterLog.append(performingMsg.format(updateInfo['module-id'], itemStage));
			
				submitData['update-action'] = 'handle-item';
				submitData['update-item'] = updateItem;
			}
			
			jQuery.ajax({ 
							type: 'POST', 
							url: Photocrati_AutoUpdate_Admin_Settings.ajaxurl, 
							//dataType: 'json', 
							data: submitData 
						})
			.success(function (updateList, updateIndex) {
				return function (data, textStatus, jqXHR) {
					if (typeof(data) == 'string')
					{
						updaterLog.append('Possibly Failed...wrong output generated ["{0}"].\n'.format(data));
					}
					else
					{
						updaterLog.append('Success.\n');
					
						if ('action' in data)
						{
							updateList[updateIndex] = data;
						}
						else if (data.length > 0 && 'action' in data[0])
						{
							updateList.splice(0, updateList.length);
						
							for (var i = 0; i < data.length; i++)
							{
								updateList.push(data[i]);
							}
						}
					}
				}
			}(updateList, updateIndex))
			.error(function (updateList, updateIndex) {
				return function (jqXHR, textStatus, errorThrown) {
					updaterLog.append('Failed ["{0}"].\n'.format(errorThrown));
				}
			}(updateList, updateIndex))
			.complete(function (updateList, updateIndex) {
				var completeItem = function (updateList, updateIndex) {
					var updateStage = updater.data('update-stage');
					var updateItem = updateList[updateIndex];
					var updateInfo = updateItem['info'];
					var commandError = null;
					var keepGoing = true;
					
					if (updateInfo['-command-error'])
					{
						commandError = updateInfo['-command-error'];
						
						updaterLog.append('An error occurred ["{2}"] at stage "{0}" for "{1}" {3}.\n'.format(updateStage, updateInfo['module-id'], commandError, updateInfo['module-version']));
						
						if (updateInfo['-command-form'])
						{
							keepGoing = false;
							
							updaterLog.append('Displaying FTP credentials form...');
						
							var form = jQuery('<div>' + updateInfo['-command-form'] + '</div>').find('form');
							var submitData = updater.data('form-submit-data');
						
							if (submitData)
							{
								for (var i in submitData)
								{
									form.find('[name=' + i + ']').val(submitData[i]);
								}
							}
						
							form.data('submit-ok', false);
							form.submit(function () {
								form.data('submit-ok', true);
								form.dialog('close');
							
								return false;
							});
						
							// XXX not sure why this is necessary... set_current_screen() seems broken
							form.find('.wrap .icon32').attr('id', 'icon-index');
						
							form.dialog({
								title: 'Connection Details',
								width: 550,
								modal: true,
								resizable: false,
								close: function() {
									if (form.data('submit-ok') == true)
									{
										updaterLog.append('Done.\n');
								
										var formData = form.serializeArray();
										var submitData = {};
									
										for (var i in formData)
										{
											submitData[formData[i].name] = formData[i].value;
										}
									
										updater.data('form-submit-data', submitData);
										Photocrati_AutoUpdate_Admin.updateContinue(updater, textList);
									}
									else
									{
										updaterLog.append('Canceled.\n');
										updaterMessage.html(textList['updater_status_cancel']);
										updaterProgress.progressbar('option', 'value', 0);
									}
								}
							});
						}
					}
					
					if (keepGoing)
					{
						var progressMultiplier = { 'download' : 0.6, 'install' : 0.3, 'activate' : 0.05, 'cleanup' : 0.05 };
						var progress = updaterProgress.progressbar('option', 'value');
						var increment = ((99 * progressMultiplier[updateStage]) / updateList.length);
						// Logging utils
						var progressMsg = '{0} stage "{3}" for ({2}) "{1}"{5}. Next stage is "{4}".\n';
						var progressVerb = commandError ? 'Skipped' : 'Completed';
						var downloaded = updateStage == 'download' && !commandError;
						var downloadMsg = downloaded ? ', stored into "{0}"'.format(updateInfo['-command-package-file']) : '';
						
						updaterProgress.progressbar('option', 'value', progress + increment);
						
						updaterLog.append(progressMsg.format(progressVerb, updateInfo['module-id'], updateIndex, updateStage, updateInfo['-command-stage'], downloadMsg));
						
						updater.data('update-index', updateIndex + 1);
						Photocrati_AutoUpdate_Admin.updateContinue(updater, textList);
					}
				}
				
				return function (jqXHR, textStatus) {
					var updateStage = updater.data('update-stage');
				
					completeItem(updateList, updateIndex);
				}
			}(updateList, updateIndex));
		}
		else
		{
			updaterLog.append('Skipping ({1}) "{0}" in stage "{2}" while performing stage "{3}"...\n'.format(updateInfo['module-id'], updateIndex, itemStage, updateStage));
			
			updater.data('update-index', updateIndex + 1);
			this.updateContinue(updater, textList);
		}
	}
};

jQuery(document).ready(function () {
	var root = jQuery('#update-content');
	var loader = jQuery('#update-loader');
	
	if (root.length == 0)
	{
		return;
	}
	
	var content = jQuery('<div />');
	var updateList = photocrati_autoupdate_expand_json(Photocrati_AutoUpdate_Admin_Settings.update_list);
	var textList = photocrati_autoupdate_expand_json(Photocrati_AutoUpdate_Admin_Settings.text_list);
	var updater = null;
	
	if (updateList.length > 0)
	{
		var updateCount = 0;
		var invalidCount = 0;
		var expiredCount = 0;
		var invalidLink = null;
		var expiredLink = null;
		var totalSize = 0;
		var downloadSize = 0;
		
		for (var i = 0; i < updateList.length; i++)
		{
			var updateItem = updateList[i];
			var updateInfo = updateItem['info'];
			
			if ('product-invalid-license' in updateInfo)
			{
				invalidCount++;
				
				if ('product-invalid-license-link' in updateInfo)
				{
					var link = updateInfo['product-invalid-license-link'];
					
					if (invalidLink == null)
					{
						invalidLink = link;
					}
					else if (invalidLink != link)
					{
						// XXX this is not supported yet! links must be the same for all products
					}
				}
			}
			else if ('product-expired' in updateInfo)
			{
				expiredCount++;
				
				if ('product-expired-link' in updateInfo)
				{
					var link = updateInfo['product-expired-link'];
					
					if (expiredLink == null)
					{
						expiredLink = link;
					}
					else if (expiredLink != link)
					{
						// XXX this is not supported yet! links must be the same for all products
					}
				}
			}
			else
			{
				downloadSize += updateInfo['module-package-size'];
			}
			
			totalSize += updateInfo['module-package-size'];
			updateCount++;
		}
		
		if (updateCount > 0)
		{
			var installCount = updateCount - (invalidCount + expiredCount);
			totalSize = photocrati_autoupdate_readable_size(totalSize);
			downloadSize = photocrati_autoupdate_readable_size(downloadSize);
			
			content.append('<div class="details"><span class="update-info">' + textList['updates_available'].format(updateCount, installCount) /* + ' ' + textList['updates_sizes'].format(totalSize, downloadSize) */ + '</span></div>');
		
			if (invalidCount > 0)
			{
				invalidLink += invalidLink.indexOf('?') == -1 ? '?' : '&';
				invalidLink += 'pclst' + '=' + escape(Photocrati_AutoUpdate_Admin_Settings.request_site);
				
				content.append('<div class="details details-alert"><span class="message">' + textList['updates_license_invalid'].format(invalidCount, updateCount, '') + '</span> &nbsp; <a href="' + invalidLink + '" class="button-secondary" target="_blank">' + textList['updates_license_get'] + '</a></div>');
			}
		
			if (expiredCount > 0)
			{
				expiredLink += expiredLink.indexOf('?') == -1 ? '?' : '&';
				expiredLink += 'pclst' + '=' + escape(Photocrati_AutoUpdate_Admin_Settings.request_site);
				
				content.append('<div class="details details-alert"><span class="message">' + textList['updates_expired'].format(expiredCount, updateCount, '') + '</span> &nbsp; <a href="' + expiredLink + '" class="button-secondary" target="_blank">' + textList['updates_renew'] + '</a></div>');
			}
			
			if (installCount > 0)
			{
				updater = jQuery('<div class="updater" id="update-updater" />');
				var updaterMessage = jQuery('<span class="message"></span>');
				var updaterLog = jQuery('<pre class="log"></pre>');
				var updaterProgress = jQuery('<div class="progress-bar" />').progressbar();
				var logContainer = null;
				
				updater.data('update-list', updateList);
				
				updaterMessage.html(textList['updater_status_start']);
				
				var elem = null;
				
				elem = jQuery('<div class="details"></div>');
				elem.append(updaterMessage);
				updater.append(elem);
				
				elem = jQuery('<div class="details"></div>');
				var downloadLogBtn = jQuery('<button class="button-secondary download-log" style="visibility:hidden">' + textList['updater_logger_download'] + '</button>');
				var showLogBtn = jQuery('<span class="show-log-button">' + textList['updater_logger_title'] + '</span>').css({ cursor : 'pointer' }).hide().click(function (e) {
					e.preventDefault();
					jQuery(this).parent().find('.log').slideToggle();
					return false;
				});
				elem.append(jQuery('<h4 class="show-log">' + '</h4>').append(showLogBtn).append(downloadLogBtn));
				elem.css({ overflow: 'auto' });
				updaterLog.css({ height: 300 }).hide(); 
				elem.append(updaterLog);
				logContainer = elem;
				
				downloadLogBtn.click(function (e) {
					e.preventDefault();
					
					var submitData = updater.data('form-submit-data') || {};
			
					submitData['action'] = 'photocrati_autoupdate_admin_handle';
					submitData['actionSec'] = Photocrati_AutoUpdate_Admin_Settings.actionSec;
					submitData['update-action'] = 'download-log';
					submitData['update-log'] = updaterLog.text();
			
					var form = jQuery('<form />');
					form.attr('method', 'POST');
					form.attr('action', Photocrati_AutoUpdate_Admin_Settings.ajaxurl);
					form.attr('target', '_blank');
					form.width(1).height(1);
		
					for (var i in submitData)
					{
						var elem = jQuery('<input type="hidden" />');
						elem.attr('name', i).val(submitData[i]);
						form.append(elem);
					}
					
					content.append(form);
					form.submit();
					
					return false;
				});
				
				updaterProgress.hide();
				updaterProgress.bind('progressbarchange', function(event, ui) {
					var val = jQuery(this).progressbar( "option", "value" );
					
					if (val == 0 || val == 100)
						downloadLogBtn.css('visibility', 'visible');
				});
				
				elem = jQuery('<div class="details"></div>');
				elem.append(updaterProgress);
				updater.append(elem);
				
				elem = jQuery('<div class="details"></div>');
				elem.append(jQuery('<button class="button-primary button-update-start">' + textList['updater_button_start'] + '</button>').click(function (e) {
					e.preventDefault();
					var jthis = jQuery(this);
					if (!jthis.attr('disabled'))
					{
						jthis.attr('disabled', 'disabled');
						Photocrati_AutoUpdate_Admin.updateStart(updater, textList);
					}
					return false;
				}));
				elem.append(jQuery('<a class="button-primary button-update-done" href="' + Photocrati_AutoUpdate_Admin_Settings.adminurl + '">' + textList['updater_button_done'] + '</a>').hide());
				updater.append(elem);
				
				updater.append(logContainer);
				
				content.append(updater);
			}
		}
	}
	else
	{
		content.append('<span class="message">' + textList['no_updates'] + '</span>');
	}
	
	loader.remove();
	root.append(content);
	
	if (updater != null)
	{
		// start with button, not timeout
		// setTimeout(function () { updateStart(updater, textList); }, 500);
	}
});
