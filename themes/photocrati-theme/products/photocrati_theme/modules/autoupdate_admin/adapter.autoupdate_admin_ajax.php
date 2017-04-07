<?php

class A_AutoUpdate_Admin_Ajax extends Mixin_AutoUpdate_Admin_Ajax
{
	function photocrati_autoupdate_admin_handle()
	{
		// XXX ugly, one of the reasons to use ajax-admin.php
		include_once(ABSPATH . '/wp-admin/includes/admin.php');
		
		$params = array();
		$params['update-item'] = $this->object->param('update-item');
		$params['update-list'] = $this->object->param('update-list');
		
		return $this->_handle_action($this->object->param('update-action'), $params);
	}
}
