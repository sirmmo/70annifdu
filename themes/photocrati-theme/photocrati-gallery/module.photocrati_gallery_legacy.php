<?php

/***
	{
		Module: photocrati-gallery_legacy
	}
***/

define('PHOTOCRATI_GALLERY_LEGACY_MOD_URL', path_join(PHOTOCRATI_GALLERY_MODULE_URL, basename(dirname(__FILE__))));
define('PHOTOCRATI_GALLERY_LEGACY_MOD_STATIC_URL', path_join(PHOTOCRATI_GALLERY_LEGACY_MOD_URL, 'static'));

class M_Photocrati_GalleryLegacy extends C_Base_Module
{
    function define()
    {
        parent::define(
            'photocrati-gallery_legacy',
            'Photocrati Legacy Gallery',
            "Legacy gallery functionality",
            '4.8.2',/* identical for 4.8.3 */
            'http://www.photocrati.com',
            'Photocrati Media',
            'http://www.photocrati.com'
        );

        // XXX use constants?
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

        include_once($path . 'core.php');
        include_once($path . 'ecommerce.php');
        include_once($path . 'img-manage.php');
    }
}

new M_Photocrati_GalleryLegacy();
