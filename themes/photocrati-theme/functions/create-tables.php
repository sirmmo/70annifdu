<?php

function add_column_if_not_exist($db, $column, $column_attr, $default){
    global $wpdb;
    $columns = $wpdb->get_results("SHOW COLUMNS FROM $db LIKE '$column'");
    if(!$columns){
        $wpdb->query("ALTER TABLE `$db` ADD `$column` $column_attr");
    }
}

// Create custom admin table if it doesn't exist

function createtable_photocrati_admin() {
	global $table_prefix, $wpdb;
	
	$version = photocrati_theme_version_string();	
	$photocrati_version = $table_prefix . "photocrati_version";
	
	if($wpdb->get_var("show tables like '$photocrati_version'") == $photocrati_version) {
		
		$sql19 = "UPDATE ". $photocrati_version . " SET ";
		$sql19 .= "version = '".$version."' ";
		$sql19 .= "WHERE id = 1";
		$wpdb->query($sql19);
	
		$sql = "ALTER TABLE ". $photocrati_version . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		$wpdb->query($sql);
	
	} else {
	
		$sql18 = "CREATE TABLE `". $photocrati_version . "` ( ";
		$sql18 .= " `id` numeric NOT NULL, ";
		$sql18 .= " `version` TINYTEXT NOT NULL ";
		$sql18 .= ") ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ; ";
		
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql18);
        
        $sql19 = "INSERT INTO ". $photocrati_version . " VALUES (";
		$sql19 .= "1 "; // id
		$sql19 .= ",'".$version."' "; // version
		$sql19 .= ")";
		$wpdb->query($sql19);
		
	}

    Photocrati_Style_Manager::init();
	
	$photocrati_gallery_ids = $table_prefix . "photocrati_gallery_ids";
	
	if($wpdb->get_var("show tables like '$photocrati_gallery_ids'") != $photocrati_gallery_ids) {
	
		$sql15 = "CREATE TABLE `". $photocrati_gallery_ids . "` ( ";
		$sql15 .= " `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL, ";
		$sql15 .= " `gallery_id` TINYTEXT NOT NULL, ";
		$sql15 .= " `post_id` SMALLINT NOT NULL, ";
        $sql15 .= " `gal_height` TINYTEXT  NOT NULL, ";
        $sql15 .= " `gal_aspect_ratio` TINYTEXT  NOT NULL, ";
        $sql15 .= " `gal_title` TINYTEXT NOT NULL, ";
        $sql15 .= " `gal_desc` LONGTEXT NOT NULL, ";
		$sql15 .= " `gal_type` SMALLINT NOT NULL ";
		$sql15 .= ") ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ; ";
		
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql15);
		
	} else {
		
		add_column_if_not_exist($photocrati_gallery_ids, 'gal_desc', 'LONGTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_ids, 'gal_height', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_ids, 'gal_aspect_ratio', 'TINYTEXT NOT NULL', '');
		
		$sql = "ALTER TABLE ". $photocrati_gallery_ids . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		$wpdb->query($sql);
		
	}
	
	
	$photocrati_galleries = $table_prefix . "photocrati_galleries";
	
	if($wpdb->get_var("show tables like '$photocrati_galleries'") == $photocrati_galleries) {
		
		add_column_if_not_exist($photocrati_galleries, 'ecomm_options', 'TINYTEXT NOT NULL', '');
	
		$sql = "ALTER TABLE " . $photocrati_galleries . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		$wpdb->query($sql);
		
		$sqladd = "UPDATE ". $photocrati_galleries . " SET ";
		$sqladd .= "ecomm_options='1,2,3,4,5,6,7,8,9,10,11,12'";
		$sqladd .= " WHERE ecomm_options = ''";
		$wpdb->query($sqladd);
	
	} else {
	
		$sql16 = "CREATE TABLE `". $photocrati_galleries . "` ( ";
		$sql16 .= " `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL, ";
		$sql16 .= " `gallery_id` TINYTEXT NOT NULL, ";
		$sql16 .= " `post_id` SMALLINT NOT NULL, ";
		$sql16 .= " `gal_type` TINYTEXT NOT NULL, ";
		$sql16 .= " `image_name` TINYTEXT NOT NULL, ";
		$sql16 .= " `image_desc` LONGTEXT NOT NULL, ";
		$sql16 .= " `image_alt` LONGTEXT NOT NULL, ";
		$sql16 .= " `image_order` SMALLINT NOT NULL, ";
		$sql16 .= " `ecomm_options` TINYTEXT NOT NULL ";
		$sql16 .= ") ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ; ";
		
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql16);
		
	}
	
	
	$photocrati_albums = $table_prefix . "photocrati_albums";
	
	if($wpdb->get_var("show tables like '$photocrati_albums'") != $photocrati_albums) {
	
		$sql16 = "CREATE TABLE `". $photocrati_albums . "` ( ";
		$sql16 .= " `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL, ";
		$sql16 .= " `album_id` TINYTEXT NOT NULL, ";
		$sql16 .= " `gallery_id` TINYTEXT NOT NULL, ";
		$sql16 .= " `album_order` SMALLINT NOT NULL ";
		$sql16 .= ") ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ; ";
		
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql16);
		
	}
	else
	{
		$sql = "ALTER TABLE " . $photocrati_albums . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		$wpdb->query($sql);
	}
    
    
    $photocrati_gallery_settings = $table_prefix . "photocrati_gallery_settings";
	
	if($wpdb->get_var("show tables like '$photocrati_gallery_settings'") == $photocrati_gallery_settings) {
		
		add_column_if_not_exist($photocrati_gallery_settings, 'image_resolution', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'lightbox_mode', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'lightbox_type', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'gallery_pad2', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'sgallery_t', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'sgallery_ts', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'sgallery_s', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'sgallery_b', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'sgallery_b_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'sgallery_cap_loc', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'hfgallery_t', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'hfgallery_ts', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'hfgallery_s', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'hfgallery_b', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'hfgallery_b_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'hfgallery_cap_loc', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'bgallery_b', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'bgallery_b_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'tgallery_b', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'tgallery_b_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'thumb_crop', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'blog_crop', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'film_crop', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'fs_rightclick', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'music_blog', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'music_blog_file', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'music_blog_controls', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'music_blog_auto', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'music_cat', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'music_cat_file', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'music_cat_controls', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'music_cat_auto', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'gallery_h2', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albuml_per_row', 'LONGTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albuml_back_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albuml_font_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albuml_font_size', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albuml_line_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albuml_line_size', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albumg_per_row', 'LONGTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albumg_image_width', 'LONGTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albumg_back_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albumg_font_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albumg_font_size', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albumg_line_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'albumg_line_size', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'gallery_buttons1', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_gallery_settings, 'gallery_buttons3', 'TINYTEXT NOT NULL', '');
		
		$sql = "ALTER TABLE " . $photocrati_gallery_settings . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		$wpdb->query($sql);
		
		$check = $wpdb->get_results("SELECT image_resolution FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1");
		foreach ($check as $check) {
			$image_resolution = $check->image_resolution;
		}
		
		if($image_resolution == '' || $image_resolution == NULL) {
		
		$sqladd = "UPDATE ". $photocrati_gallery_settings . " SET ";
		$sqladd .= "image_resolution='0', ";
		$sqladd .= "sgallery_t='fade', ";
		$sqladd .= "sgallery_ts='400', ";
		$sqladd .= "sgallery_s='4', ";
		$sqladd .= "hfgallery_t='fade', ";
		$sqladd .= "hfgallery_ts='400', ";
		$sqladd .= "hfgallery_s='4', ";
		$sqladd .= "thumb_crop='ON', ";
		$sqladd .= "blog_crop='ON', ";
		$sqladd .= "film_crop='ON', ";
		$sqladd .= "fs_rightclick='ON'";
		$sqladd .= " WHERE id = 1";
		$wpdb->query($sqladd);
		
		}
		
		$check2 = $wpdb->get_results("SELECT music_blog FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1");
		foreach ($check2 as $check2) {
			$music_blog = $check2->music_blog;
		}
		
		if($music_blog == '' || $music_blog == NULL) {
		
		$sqladd = "UPDATE ". $photocrati_gallery_settings . " SET ";
		$sqladd .= "music_blog='OFF', ";
		$sqladd .= "music_blog_file='', ";
		$sqladd .= "music_blog_controls='YES', ";
		$sqladd .= "music_blog_auto='YES', ";
		$sqladd .= "music_cat='OFF', ";
		$sqladd .= "music_cat_file='', ";
		$sqladd .= "music_cat_controls='YES', ";
		$sqladd .= "music_cat_auto='YES'";
		$sqladd .= " WHERE id = 1";
		$wpdb->query($sqladd);
		
		}
		
		$check3 = $wpdb->get_results("SELECT albuml_per_row, albumg_per_row FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1");
		foreach ($check3 as $check3) {
			$albuml_per_row = $check3->albuml_per_row;
		}
		
		if($albuml_per_row == '' || $albuml_per_row == NULL) {
		
		$sqladd = "UPDATE ". $photocrati_gallery_settings . " SET ";
		$sqladd .= "albuml_per_row='1', ";
		$sqladd .= "albuml_back_color='FFFFFF', ";
		$sqladd .= "albuml_font_color='333333', ";
		$sqladd .= "albuml_font_size='16', ";
		$sqladd .= "albuml_line_color='AAAAAA', ";
		$sqladd .= "albuml_line_size='1', ";
		$sqladd .= "albumg_per_row='3', ";
		$sqladd .= "albumg_back_color='FFFFFF', ";
		$sqladd .= "albumg_font_color='333333', ";
		$sqladd .= "albumg_font_size='16', ";
		$sqladd .= "albumg_line_color='AAAAAA', ";
		$sqladd .= "albumg_line_size='1' ";
		$sqladd .= " WHERE id = 1";
		$wpdb->query($sqladd);
		
		}
		
		$check4 = $wpdb->get_results("SELECT gallery_pad2 FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1");
		foreach ($check4 as $check4) {
			$gallery_pad2 = $check4->gallery_pad2;
		}
		
		if($gallery_pad2 == '' || $gallery_pad2 == NULL) {
		
		$sqladd = "UPDATE ". $photocrati_gallery_settings . " SET ";
		$sqladd .= "gallery_pad2='10'";
		$sqladd .= " WHERE id = 1";
		$wpdb->query($sqladd);
		
		}
		
		$check5 = $wpdb->get_results("SELECT lightbox_type FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1");
		foreach ($check5 as $check5) {
			$lightbox_type = $check5->lightbox_type;
		}
		
		if($lightbox_type == '' || $lightbox_type == NULL) {
		
		$sqladd = "UPDATE ". $photocrati_gallery_settings . " SET ";
		$sqladd .= "lightbox_type='fancy'";
		$sqladd .= " WHERE id = 1";
		$wpdb->query($sqladd);
		
		}
		
		$check6 = $wpdb->get_results("SELECT gallery_buttons1 FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1");
		foreach ($check6 as $check6) {
			$gallery_buttons1 = $check6->gallery_buttons1;
		}
		
		if($gallery_buttons1 == '' || $gallery_buttons1 == NULL) {
		
		$sqladd = "UPDATE ". $photocrati_gallery_settings . " SET ";
		$sqladd .= "gallery_buttons1='OFF'";
		$sqladd .= " WHERE id = 1";
		$wpdb->query($sqladd);
		
		}
		
		$check7 = $wpdb->get_results("SELECT gallery_buttons3 FROM ".$wpdb->prefix."photocrati_gallery_settings WHERE id = 1");
		foreach ($check7 as $check7) {
			$gallery_buttons3 = $check7->gallery_buttons3;
		}
		
		if($gallery_buttons3 == '' || $gallery_buttons3 == NULL) {
		
		$sqladd = "UPDATE ". $photocrati_gallery_settings . " SET ";
		$sqladd .= "gallery_buttons3='OFF'";
		$sqladd .= " WHERE id = 1";
		$wpdb->query($sqladd);
		
		}
		
		$album_setts = $wpdb->get_row("SELECT albumg_per_row, albumg_image_width FROM " . $photocrati_gallery_settings . " WHERE id = 1", ARRAY_A);
		extract($album_setts);
		
		if($albumg_image_width == null || $albumg_image_width == 0) {
			$albumg_image_width = intval(floor(940 / $albumg_per_row));
			$sqladd = "UPDATE ". $photocrati_gallery_settings . " SET ";
			$sqladd .= "albumg_image_width='" . $albumg_image_width . "' ";
			$sqladd .= " WHERE id = 1";
			$wpdb->query($sqladd);
		}
	
	} else {
	
		$sql18 = "CREATE TABLE `". $photocrati_gallery_settings . "` ( ";
		$sql18 .= " `id` INT PRIMARY KEY NOT NULL, ";
		$sql18 .= " `thumbnail_w1` TINYTEXT NOT NULL, ";
		$sql18 .= " `thumbnail_h1` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_w1` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_cap1` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_buttons1` TINYTEXT NOT NULL, ";
		$sql18 .= " `thumbnail_w2` TINYTEXT NOT NULL, ";
		$sql18 .= " `thumbnail_h2` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_w2` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_pad2` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_h2` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_cap2` TINYTEXT NOT NULL, ";
		$sql18 .= " `thumbnail_w3` TINYTEXT NOT NULL, ";
		$sql18 .= " `thumbnail_h3` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_w3` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_cap3` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_buttons3` TINYTEXT NOT NULL, ";
		$sql18 .= " `thumbnail_w4` TINYTEXT NOT NULL, ";
		$sql18 .= " `thumbnail_h4` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_w4` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_cap4` TINYTEXT NOT NULL, ";
		$sql18 .= " `gallery_crop` TINYTEXT NOT NULL, ";
		$sql18 .= " `image_resolution` TINYTEXT NOT NULL, ";
		$sql18 .= " `lightbox_mode` TINYTEXT NOT NULL, ";
		$sql18 .= " `lightbox_type` TINYTEXT NOT NULL, ";
		$sql18 .= " `sgallery_t` TINYTEXT NOT NULL, ";
		$sql18 .= " `sgallery_ts` TINYTEXT NOT NULL, ";
		$sql18 .= " `sgallery_s` TINYTEXT NOT NULL, ";
		$sql18 .= " `sgallery_b` TINYTEXT NOT NULL, ";
		$sql18 .= " `sgallery_b_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `sgallery_cap_loc` TINYTEXT NOT NULL, ";
		$sql18 .= " `hfgallery_t` TINYTEXT NOT NULL, ";
		$sql18 .= " `hfgallery_ts` TINYTEXT NOT NULL, ";
		$sql18 .= " `hfgallery_s` TINYTEXT NOT NULL, ";
		$sql18 .= " `hfgallery_b` TINYTEXT NOT NULL, ";
		$sql18 .= " `hfgallery_b_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `hfgallery_cap_loc` TINYTEXT NOT NULL, ";
		$sql18 .= " `bgallery_b` TINYTEXT NOT NULL, ";
		$sql18 .= " `bgallery_b_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `tgallery_b` TINYTEXT NOT NULL, ";
		$sql18 .= " `tgallery_b_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `thumb_crop` TINYTEXT NOT NULL, ";
		$sql18 .= " `blog_crop` TINYTEXT NOT NULL, ";
		$sql18 .= " `film_crop` TINYTEXT NOT NULL, ";
		$sql18 .= " `fs_rightclick` TINYTEXT NOT NULL, ";
		$sql18 .= " `music_blog` TINYTEXT NOT NULL, ";
		$sql18 .= " `music_blog_file` TINYTEXT NOT NULL, ";
		$sql18 .= " `music_blog_controls` TINYTEXT NOT NULL, ";
		$sql18 .= " `music_blog_auto` TINYTEXT NOT NULL, ";
		$sql18 .= " `music_cat` TINYTEXT NOT NULL, ";
		$sql18 .= " `music_cat_file` TINYTEXT NOT NULL, ";
		$sql18 .= " `music_cat_controls` TINYTEXT NOT NULL, ";
		$sql18 .= " `music_cat_auto` TINYTEXT NOT NULL, ";
		$sql18 .= " `albuml_per_row` TINYTEXT NOT NULL, ";
		$sql18 .= " `albuml_back_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `albuml_font_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `albuml_font_size` TINYTEXT NOT NULL, ";
		$sql18 .= " `albuml_line_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `albuml_line_size` TINYTEXT NOT NULL, ";
		$sql18 .= " `albumg_per_row` TINYTEXT NOT NULL, ";
		$sql18 .= " `albumg_image_width` TINYTEXT NOT NULL, ";
		$sql18 .= " `albumg_back_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `albumg_font_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `albumg_font_size` TINYTEXT NOT NULL, ";
		$sql18 .= " `albumg_line_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `albumg_line_size` TINYTEXT NOT NULL ";
		$sql18 .= ") ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ; ";
		
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql18);
        
        $sql19 = "INSERT INTO ". $photocrati_gallery_settings . " VALUES (";
		$sql19 .= "1 "; // id
		$sql19 .= ",'110'"; // thumbnail_w1
		$sql19 .= ",'75'"; // thumbnail_h1
		$sql19 .= ",'800'"; // gallery_w1
		$sql19 .= ",'OFF'"; // gallery_cap1
		$sql19 .= ",'OFF'"; // gallery_buttons1
		$sql19 .= ",'110'"; // thumbnail_w2
		$sql19 .= ",'75'"; // thumbnail_h2
		$sql19 .= ",'800'"; // gallery_w2
		$sql19 .= ",'10'"; // gallery_pad2
		$sql19 .= ",''"; // gallery_h2
		$sql19 .= ",'OFF'"; // gallery_cap2
		$sql19 .= ",'110'"; // thumbnail_w3
		$sql19 .= ",'75'"; // thumbnail_h3
		$sql19 .= ",'800'"; // gallery_w3
		$sql19 .= ",'OFF'"; // gallery_cap3
		$sql19 .= ",'OFF'"; // gallery_buttons3
		$sql19 .= ",'140'"; // thumbnail_w4
		$sql19 .= ",'95'"; // thumbnail_h4
		$sql19 .= ",'800'"; // gallery_w4
		$sql19 .= ",'OFF'"; // gallery_cap4
		$sql19 .= ",'false'"; // gallery_crop
		$sql19 .= ",'0'"; // image_resolution
		$sql19 .= ",'manual'"; // lightbox_mode
		$sql19 .= ",'fancy'"; // lightbox_type
		$sql19 .= ",'fade'"; // sgallery_t
		$sql19 .= ",'400'"; // sgallery_ts
		$sql19 .= ",'4'"; // sgallery_s
		$sql19 .= ",'0'"; // sgallery_b
		$sql19 .= ",''"; // sgallery_b_color
		$sql19 .= ",'overlay_bottom'"; // sgallery_cap_loc
		$sql19 .= ",'fade'"; // hfgallery_t
		$sql19 .= ",'400'"; // hfgallery_ts
		$sql19 .= ",'4'"; // hfgallery_s
		$sql19 .= ",'0'"; // hfgallery_b
		$sql19 .= ",''"; // hfgallery_b_color
		$sql19 .= ",'overlay_bottom'"; // hfgallery_cap_loc
		$sql19 .= ",'0'"; // bgallery_b
		$sql19 .= ",''"; // bgallery_b_color
		$sql19 .= ",'0'"; // tgallery_b
		$sql19 .= ",''"; // tgallery_b_color
		$sql19 .= ",'ON'"; // thumb_crop
		$sql19 .= ",'ON'"; // blog_crop
		$sql19 .= ",'ON'"; // film_crop
		$sql19 .= ",'ON'"; // fs_rightclick
		$sql19 .= ",'OFF'"; // music_blog
		$sql19 .= ",''"; // music_blog_file
		$sql19 .= ",'YES'"; // music_blog_control
		$sql19 .= ",'YES'"; // music_blog_auto
		$sql19 .= ",'OFF'"; // music_cat
		$sql19 .= ",''"; // music_cat_file
		$sql19 .= ",'YES'"; // music_cat_control
		$sql19 .= ",'YES'"; // music_cat_auto
		$sql19 .= ",'1'"; // albuml_per_row
		$sql19 .= ",'FFFFFF'"; // albuml_back_color
		$sql19 .= ",'333333'"; // albuml_font_color
		$sql19 .= ",'16'"; // albuml_font_size
		$sql19 .= ",'AAAAAA'"; // albuml_line_color
		$sql19 .= ",'1'"; // albuml_line_size
		$sql19 .= ",'3'"; // albumg_per_row
		$sql19 .= ",'313'"; // albumg_image_width
		$sql19 .= ",'FFFFFF'"; // albumg_back_color
		$sql19 .= ",'333333'"; // albumg_font_color
		$sql19 .= ",'16'"; // albumg_font_size
		$sql19 .= ",'AAAAAA'"; // albumg_line_color
		$sql19 .= ",'1'"; // albumg_line_size
		$sql19 .= ")";
		$wpdb->query($sql19);
		
	}
    
    
    $photocrati_ecommerce_settings = $table_prefix . "photocrati_ecommerce_settings";
	
	if($wpdb->get_var("show tables like '$photocrati_ecommerce_settings'") == $photocrati_ecommerce_settings) {
		
		$l = 1;
		while($l < 21) {
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_op'.$l, 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_cost'.$l, 'TINYTEXT NOT NULL', '');
		$l = $l + 1;
		}
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_tax', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_tax_name', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_tax_method', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_note', 'LONGTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_per_row', 'LONGTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_back_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_font_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_line_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_line_size', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_but_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_buttext_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_butborder_color', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_captions', 'TINYTEXT NOT NULL', '');
		
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_ship_int_method', 'TINYTEXT NOT NULL', '');
		
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_currency_symbol', 'TINYTEXT NOT NULL', '');
		add_column_if_not_exist($photocrati_ecommerce_settings, 'ecomm_image_width', 'TINYTEXT NOT NULL', '');
	
		$sql = "ALTER TABLE ". $photocrati_ecommerce_settings . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci";
		$wpdb->query($sql);
		
		$ecomm_setts = $wpdb->get_row("SELECT ecomm_image_width, ecomm_per_row FROM " . $photocrati_ecommerce_settings . " WHERE id = 1", ARRAY_A);
		extract($ecomm_setts);
		
		if($ecomm_image_width == null || $ecomm_image_width == 0) {
			$ecomm_image_width = intval(floor(940 / $ecomm_per_row));
			$sqladd = "UPDATE ". $photocrati_ecommerce_settings . " SET ";
			$sqladd .= "ecomm_image_width='" . $ecomm_image_width . "' ";
			$sqladd .= " WHERE id = 1";
			$wpdb->query($sqladd);
		}
	} else {
	
		$sql18 = "CREATE TABLE `". $photocrati_ecommerce_settings . "` ( ";
		$sql18 .= " `id` INT PRIMARY KEY NOT NULL, ";
		$sql18 .= " `pp_account` TINYTEXT NOT NULL, ";
		$sql18 .= " `pp_return` TINYTEXT NOT NULL, ";
		$sql18 .= " `pp_profile` TINYTEXT NOT NULL, ";
		$l = 1;
		while($l < 21) {	
		$sql18 .= " `ecomm_op$l` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_cost$l` TINYTEXT NOT NULL, ";
		$l = $l + 1;
		}
		$sql18 .= " `ecomm_currency` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_currency_symbol` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_country` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_title` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_empty` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_but_text` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_but_image` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_tax` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_tax_name` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_tax_method` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_ship_st` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_ship_exp` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_ship_method` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_ship_free` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_ship_en` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_ship_int` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_ship_int_method` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_note` LONGTEXT NOT NULL, ";
		$sql18 .= " `ecomm_per_row` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_image_width` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_captions` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_back_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_font_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_line_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_line_size` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_but_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_buttext_color` TINYTEXT NOT NULL, ";
		$sql18 .= " `ecomm_butborder_color` TINYTEXT NOT NULL ";
		$sql18 .= ") ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ; ";
		
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql18);
        
        $sql19 = "INSERT INTO ". $photocrati_ecommerce_settings . " VALUES (";
		$sql19 .= "1 "; // id
		$sql19 .= ",''"; // pp_account
		$sql19 .= ",''"; // pp_return
		$sql19 .= ",'OFF'"; // pp_profile
		$l = 1;
		while($l < 21) {
		$sql19 .= ",''"; // ecomm_op	
		$sql19 .= ",''"; // ecomm_cost	
		$l = $l + 1;
		}
		$sql19 .= ",'USD'"; // ecomm_currency
		$sql19 .= ",''"; // ecomm_currency_symbol
		$sql19 .= ",'US'"; // ecomm_country
		$sql19 .= ",'Shopping Cart'"; // ecomm_title
		$sql19 .= ",'There are no items in your shopping cart'"; // ecomm_empty
		$sql19 .= ",'Add to Cart'"; // ecomm_but_text
		$sql19 .= ",''"; // ecomm_but_image
		$sql19 .= ",''"; // ecomm_tax
		$sql19 .= ",''"; // ecomm_tax_name
		$sql19 .= ",'before'"; // ecomm_tax_method
		$sql19 .= ",'5.00'"; // ecomm_ship_st
		$sql19 .= ",''"; // ecomm_ship_exp
		$sql19 .= ",'total'"; // ecomm_ship_method
		$sql19 .= ",''"; // ecomm_ship_free
		$sql19 .= ",'ON'"; // ecomm_ship_en
		$sql19 .= ",'10.00'"; // ecomm_ship_int
		$sql19 .= ",'total'"; // ecomm_ship_int_method
		$sql19 .= ",'Please enter your name and email address so we can get in touch if we have questions about your order. Once you click Pay Now, you\'ll be taken to Paypal for secure payment processing. Thanks very much for your purchase!'"; // ecomm_note
		$sql19 .= ",'4'"; // ecomm_per_row
		$sql19 .= ",'235'"; // ecomm_image_width
		$sql19 .= ",'ON'"; // ecomm_captions
		$sql19 .= ",'F1F1F1'"; // ecomm_back_color
		$sql19 .= ",'333333'"; // ecomm_font_color
		$sql19 .= ",'CCCCCC'"; // ecomm_line_color
		$sql19 .= ",'1'"; // ecomm_line_size
		$sql19 .= ",'CCCCCC'"; // ecomm_but_color
		$sql19 .= ",'333333'"; // ecomm_buttext_color
		$sql19 .= ",'999999'"; // ecomm_butborder_color
		$sql19 .= ")";
		$wpdb->query($sql19);
		
	}
 
 
// Schedule Gallery Move 
wp_schedule_single_event(time()+30, 'ph_move_gallery_files');
	
}


// Move all images to the uploads folder for the new gallery system

function check_for_empty_folder($folder) {
	$files = array ();
	if ( $handle = opendir ( $folder ) ) {
		while ( false !== ( $file = readdir ( $handle ) ) ) {
			if ( $file != "." && $file != ".." ) {
				$files [] = $file;
			}
		}
		closedir ( $handle );
	}
	return ( count ( $files ) > 0 ) ? FALSE : TRUE;
}

function move_galleries() {
	
	$upload_dir = wp_upload_dir();
	$path = dirname(dirname(__FILE__))."/galleries";
	$dest = $upload_dir['basedir']."/galleries";
		
	if (!is_dir($dest)) {
		photocrati_mkdir($dest);
	}

	global $wpdb;
	$gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids");
    foreach ($gallery as $gallery) {
	
		if (!is_dir($dest.'/post-'.$gallery->post_id)) {
			
			photocrati_mkdir( $dest.'/post-'.$gallery->post_id.'/');
			photocrati_mkdir( $dest.'/post-'.$gallery->post_id.'/full/');
            photocrati_mkdir( $dest.'/post-'.$gallery->post_id.'/thumbnails/');
			
		}
			
		$images = $wpdb->get_results("SELECT image_name FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '".$gallery->gallery_id."'");
		foreach ($images as $images) {
			
			if (!file_exists($dest.'/post-'.$gallery->post_id.'/'.$images->image_name)) {
					
				if (copy( $path.'/post-'.$gallery->post_id.'/'.$images->image_name, $dest.'/post-'.$gallery->post_id.'/'.$images->image_name )) {
			
					if (file_exists($dest.'/post-'.$gallery->post_id.'/'.$images->image_name)) {
						unlink($path.'/post-'.$gallery->post_id.'/'.$images->image_name);
					}
						
				}
					
				if (copy( $path.'/post-'.$gallery->post_id.'/full/'.$images->image_name, $dest.'/post-'.$gallery->post_id.'/full/'.$images->image_name )) {
			
					if (file_exists($dest.'/post-'.$gallery->post_id.'/full/'.$images->image_name)) {
						unlink( $path.'/post-'.$gallery->post_id.'/full/'.$images->image_name );
					}
						
				}
					
				if (copy( $path.'/post-'.$gallery->post_id.'/thumbnails/'.$images->image_name, $dest.'/post-'.$gallery->post_id.'/thumbnails/'.$images->image_name )) {
			
					if (file_exists($dest.'/post-'.$gallery->post_id.'/thumbnails/'.$images->image_name)) {
						unlink( $path.'/post-'.$gallery->post_id.'/thumbnails/'.$images->image_name );
					}
						
				}
					
				if (copy( $path.'/post-'.$gallery->post_id.'/thumbnails/med-'.$images->image_name, $dest.'/post-'.$gallery->post_id.'/thumbnails/med-'.$images->image_name )) {
			
					if (file_exists($dest.'/post-'.$gallery->post_id.'/thumbnails/med-'.$images->image_name)) {
						unlink( $path.'/post-'.$gallery->post_id.'/thumbnails/med-'.$images->image_name );
					}
						
				}
					
			}
		}

		// Remove old directories
		$old_dirs = array(
			$path.'/post-'.$gallery->post_id.'/thumbnails/',
			$path.'/post-'.$gallery->post_id.'/full/',
			$path.'/post-'.$gallery->post_id.'/'
		);

		foreach ($old_dirs as $dir) {
			if (check_for_empty_folder($dir)) {
				if (file_exists($dir)) rmdir($dir);
			}
		}
	
	}
	
}

add_action('ph_move_gallery_files', 'move_galleries');


function photocrati_createtables_check_upgrade()
{
	global $wpdb;
	
	$version = photocrati_theme_version_string();	
	$photocrati_version = $wpdb->prefix . "photocrati_version";
	
	$check = $wpdb->get_results("SELECT version FROM ".$wpdb->prefix."photocrati_version WHERE id = 1");
	foreach ($check as $check) {
		$dbversion = $check->version;
	}

	if($wpdb->get_var("show tables like '$photocrati_version'") != $photocrati_version || $dbversion != $version) 
	{
	
	add_action('admin_init', 'createtable_photocrati_admin');

	}
	
	$upload_dir =  dirname(dirname(__FILE__)) . '/images/uploads/';

	if(file_exists($upload_dir.'Doilly_BG2.jpg')){
	
		unlink($upload_dir.'Doilly_BG2.jpg');
	
	}
}

photocrati_createtables_check_upgrade();
