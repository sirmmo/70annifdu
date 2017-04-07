-- MySQL dump 10.13  Distrib 5.5.43, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 70anni
-- ------------------------------------------------------
-- Server version	5.5.43-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `wp_commentmeta`
--

DROP TABLE IF EXISTS `wp_commentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_comments`
--

DROP TABLE IF EXISTS `wp_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_links`
--

DROP TABLE IF EXISTS `wp_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_options`
--

DROP TABLE IF EXISTS `wp_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6714 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_photocrati_albums`
--

DROP TABLE IF EXISTS `wp_photocrati_albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_photocrati_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_id` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `album_order` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_photocrati_ecommerce_settings`
--

DROP TABLE IF EXISTS `wp_photocrati_ecommerce_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_photocrati_ecommerce_settings` (
  `id` int(11) NOT NULL,
  `pp_account` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `pp_return` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `pp_profile` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op1` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost1` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op2` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost2` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op3` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost3` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op4` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost4` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op5` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost5` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op6` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost6` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op7` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost7` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op8` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost8` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op9` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost9` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op10` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost10` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op11` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost11` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op12` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost12` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op13` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost13` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op14` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost14` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op15` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost15` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op16` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost16` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op17` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost17` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op18` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost18` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op19` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost19` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_op20` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_cost20` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_currency` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_currency_symbol` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_country` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_title` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_empty` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_but_text` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_but_image` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_tax` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_tax_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_tax_method` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_ship_st` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_ship_exp` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_ship_method` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_ship_free` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_ship_en` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_ship_int` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_ship_int_method` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_note` longtext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_per_row` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_image_width` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_captions` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_back_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_font_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_line_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_line_size` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_but_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_buttext_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `ecomm_butborder_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_photocrati_galleries`
--

DROP TABLE IF EXISTS `wp_photocrati_galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_photocrati_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `post_id` smallint(6) NOT NULL,
  `gal_type` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `image_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `image_desc` longtext COLLATE utf8_unicode_ci NOT NULL,
  `image_alt` longtext COLLATE utf8_unicode_ci NOT NULL,
  `image_order` smallint(6) NOT NULL,
  `ecomm_options` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_photocrati_gallery_ids`
--

DROP TABLE IF EXISTS `wp_photocrati_gallery_ids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_photocrati_gallery_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `post_id` smallint(6) NOT NULL,
  `gal_height` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gal_aspect_ratio` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gal_title` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gal_desc` longtext COLLATE utf8_unicode_ci NOT NULL,
  `gal_type` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_photocrati_gallery_settings`
--

DROP TABLE IF EXISTS `wp_photocrati_gallery_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_photocrati_gallery_settings` (
  `id` int(11) NOT NULL,
  `thumbnail_w1` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_h1` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_w1` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_cap1` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_buttons1` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_w2` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_h2` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_w2` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_pad2` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_h2` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_cap2` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_w3` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_h3` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_w3` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_cap3` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_buttons3` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_w4` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_h4` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_w4` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_cap4` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gallery_crop` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `image_resolution` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `lightbox_mode` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `lightbox_type` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `sgallery_t` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `sgallery_ts` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `sgallery_s` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `sgallery_b` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `sgallery_b_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `sgallery_cap_loc` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `hfgallery_t` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `hfgallery_ts` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `hfgallery_s` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `hfgallery_b` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `hfgallery_b_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `hfgallery_cap_loc` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `bgallery_b` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `bgallery_b_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `tgallery_b` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `tgallery_b_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `thumb_crop` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `blog_crop` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `film_crop` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `fs_rightclick` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `music_blog` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `music_blog_file` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `music_blog_controls` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `music_blog_auto` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `music_cat` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `music_cat_file` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `music_cat_controls` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `music_cat_auto` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albuml_per_row` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albuml_back_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albuml_font_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albuml_font_size` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albuml_line_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albuml_line_size` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albumg_per_row` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albumg_image_width` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albumg_back_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albumg_font_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albumg_font_size` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albumg_line_color` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `albumg_line_size` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_photocrati_version`
--

DROP TABLE IF EXISTS `wp_photocrati_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_photocrati_version` (
  `id` decimal(10,0) NOT NULL,
  `version` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_postmeta`
--

DROP TABLE IF EXISTS `wp_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=781 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(20) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_term_relationships`
--

DROP TABLE IF EXISTS `wp_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_term_taxonomy`
--

DROP TABLE IF EXISTS `wp_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_terms`
--

DROP TABLE IF EXISTS `wp_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_usermeta`
--

DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-07 13:58:10
