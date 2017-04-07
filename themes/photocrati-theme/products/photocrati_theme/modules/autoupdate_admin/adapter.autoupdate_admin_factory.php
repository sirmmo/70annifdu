<?php

class A_AutoUpdate_Admin_Factory extends Mixin
{
    function autoupdate_admin_controller($context = null)
    {
        return new C_AutoUpdate_Admin_Controller();
    }
}
