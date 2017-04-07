<?php
class MR_Social_Sharing_Toolkit_AppNet extends MR_Social_Sharing_Toolkit_Button {
	function __construct() {
		$this->share_buttons[] = array('name' => 'ap_share', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons[] = array('name' => 'follow_app_net', 'id' => '@', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->title = 'App.net';
		$this->icon = 'appnet';
	}

	function ap_share($url, $title, $type, $id, $media = '', $description = '', $text = '', $icon = '') {
		$url = 'https://alpha.app.net/intent/post?text='.urlencode($url).'%20'.urlencode($title);
		$text = ($text == '') ? __('Share on','mr_social_sharing_toolkit').' App.net' : $text;
		return $this->get_icon($type, $url, $text, $icon, true);
	}	
	
	function follow_app_net($type, $id, $text = '', $icon = '') {
		$url = 'https://alpha.app.net/'.$id;
		$text = ($text == '') ? __('Follow me on','mr_social_sharing_toolkit').' App.net' : $text;
		$blank = (get_option('mr_social_sharing_follow_new') == 1) ? true : false;
		return $this->get_icon($type, $url, $text, $icon, false, $blank);
	}
}
?>