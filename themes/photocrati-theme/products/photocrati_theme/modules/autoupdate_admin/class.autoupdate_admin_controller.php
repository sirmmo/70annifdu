<?php

// <XXX temporary
interface I_Admin_Controller 
{

}

if (class_exists('C_MVC_Controller', false)) {
	class C_AutoUpdate_Admin_Controller extends C_MVC_Controller
	{
		  function define($context = false)
		  {
		      parent::define($context);
		      
		      $this->implement('I_Admin_Controller');
		  }
		  
		  
		  function admin_page()
		  {
		      $this->render_partial('photocrati-auto_update-admin#admin_page', array());
		  }
	}
}
// XXX>
else {
	class C_Fake_MVC_Controller extends C_Component
	{
    function define($context = false)
    {
        parent::define($context);
    }
    
		function render_partial($template, $vars)
		{
			$parts = explode('#', $template);
			
			if (count($parts) > 1)
			{
				$template = $parts[1];
			}
			
			$dir = realpath(dirname(__FILE__));
			$path = $dir . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template . '.php';
			$real = realpath($path);
		
			if (file_exists($path) && dirname(dirname($real)) == $dir) {
				extract($vars);
			
				include($path);
			}
		}
	}

	class C_AutoUpdate_Admin_Controller extends C_Fake_MVC_Controller
	{
		  function define($context = false)
		  {
		      parent::define($context);
		      
		      $this->implement('I_Admin_Controller');
		  }
		  
		  
		  function admin_page()
		  {
		      $this->render_partial('photocrati-auto_update-admin#admin_page', array());
		  }
	}
}
