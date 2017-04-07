<?php

// Class to create UI page context in the backend / theme options pages
class Photocrati_Theme_UI_Page
{
	var $_id;
	var $_fields;
	
	public function __construct($id = null)
	{
		
	}
}

// Utility class to create UI elements in the backend / theme options pages
class Photocrati_Theme_UI
{
	public static function build_dropdown_html($setting_name, $setting_value, $list, $properties = null)
	{
		$groups = array();
		
		// create labels automatically if missing and normalize $list items
		foreach ($list as $item_name => $item_array)
		{
			if (!is_array($item_array))
			{
				$item_array = array('label' => $item_array);
			}
			
			$real_name = $item_name && is_string($item_name) ? $item_name : null;
			$real_name = $real_name == null && isset($item_array['name']) ? $item_array['name'] : $real_name;
			
			if ($real_name == null) 
			{
				$real_name = strval($item_name);
			}
			
			$item_array['real_name'] = $real_name;
			
			if (!isset($item_array['label']) || $item_array['label'] == null || $item_array['label'] === true)
			{
				$item_array['label'] = ucwords(str_replace(array('-', '_'), ' ', strtolower($real_name)));
			}
			
			if (isset($item_array['type']) && $item_array['type'] == 'group')
			{
				$group_name = $real_name;
				
				$groups[$group_name] = $item_array;
			}
			
			$list[$item_name] = $item_array;
		}
		
		$class = isset($properties['class']) ? $properties['class'] : null;
		$multiple = isset($properties['multiple']) ? $properties['multiple'] : null;
		$id = isset($properties['id']) ? $properties['id'] : null;
		$data = isset($properties['data']) ? $properties['data'] : null;
		
		$select_values = $setting_value;
		
		if (!is_array($select_values))
		{
			$select_values = array($select_values);
		}
		
		$setting_value = $select_values[0];
		
		$class_attr = $class ? ' class="' . esc_attr($class) . '"' : null;
		$multiple_attr = $multiple != null ? ' multiple="multiple"' : null;
		$id_attr = $id ? ' id="' . esc_attr($id) . '"' : null;
		$value_attr = !$multiple ? ' value="' . esc_attr($setting_value) . '"' : null;
		$data_attrs = null;
		
		if ($data != null && is_array($data)) 
		{
			foreach ($data as $data_name => $data_value)
			{
				$data_attrs .= ' data-' . $data_name . '="' . esc_attr($data_value) . '"';
			}
		}
		
		$output = null;
		$output .= sprintf('<select name="%1$s"%2$s%3$s%4$s%5$s%6$s>', esc_attr($setting_name), $value_attr, $class_attr, $id_attr, $multiple_attr, $data_attrs);
		$group_output = array();
		
		foreach ($list as $item_name => $item_array)
		{
			$real_name = $item_array['real_name'];
			$item_value = isset($item_array['value']) ? $item_array['value'] : $real_name;
			$item_label = $item_array['label'];
			$item_group = isset($item_array['group']) ? $item_array['group'] : null;
			$item_data = isset($item_array['data']) ? $item_array['data'] : null;
			
			if (isset($item_array['type']) && $item_array['type'] == 'group')
			{
				if (isset($item_array['always']) && $item_array['always'] && !isset($group_output[$real_name])) 
				{
					$group_output[$real_name] = ' ';
				}
				
				continue;
			}
			
			if ($item_data == null)
			{
				$item_data = array();
			}
			
			$value_idx = array_search($item_value, $select_values);
			$selected = null;
			
			if ($value_idx !== false)
			{
				$selected = ' selected="selected"';
				$item_data['selectTime'] = $value_idx;
			}
			
			$item_data_attrs = null;
			
			if ($item_data != null && is_array($item_data)) 
			{
				foreach ($item_data as $data_name => $data_value)
				{
					$item_data_attrs .= ' data-' . $data_name . '="' . esc_attr($data_value) . '"';
				}
			}
			
			$item_output = sprintf('<option value="%1$s"%2$s%4$s>%3$s</option>', esc_attr($item_value), $selected, esc_html($item_label), $item_data_attrs);
			
			if ($item_group != null)
			{
				if (!isset($group_output[$item_group])) 
				{
					$group_output[$item_group] = '';
				}
				
				$group_output[$item_group] .= $item_output;
			}
			else 
			{
				$output .= $item_output;
			}
		}
		
		foreach ($group_output as $group_name => $group_markup)
		{
			$group = $groups[$group_name];
			$group_label = $group['label'];
			$group_markup = trim($group_markup);
			$group_style_attr = $group_markup == null ? ' style="display:none;"' : null;
			
			$output .= '
<optgroup label="' . esc_attr($group_label) . '" data-name="' . $group_name . '"' . '>';

			$output .= $group_markup;
			
			$output .= '
</optgroup>';
		}
		
		$output .= '</select>';
		
		return $output;
	}
	
	public static function build_image_dropdown_html($setting_name, $setting_value, $type = null, $properties = null)
	{
		$filter = null;
		$filter_exclude = null;
		
		switch ($type)
		{
			case 'background':
			{
				// nothing to filter
				break;
			}
			case 'background-multiple':
			{
				// nothing to filter but add multiple selector
				$properties['multiple'] = true;
				
				break;
			}
			case 'logo':
			{
				$filter_exclude = array('group' => 'patterns');
				
				break;
			}
		}
		
		$list = Photocrati_Style_Manager::get_image_list($filter, $filter_exclude);
		$data = array();
		$props = array('class' => 'image-picker', 'id' => 'image-picker-' . $setting_name);
		$props = array_merge((array)$properties, $props);
		
		if ($type != null)
		{
			$props['class'] .= ' image-picker-type-' . $type;
			$data['type'] = $type;
		}
		
		$image_src = get_bloginfo('template_url') . '/images/uploads/';
		
		$data['setting-name'] = $setting_name;
		$data['image-url'] = $image_src;
		
		$props['data'] = $data;
		
		
		if ($setting_value != null) 
		{
			$setting_set = $setting_value;
			
			if (!is_array($setting_set))
			{
				$setting_set = array($setting_set);
			}
		
			foreach ($setting_set as $set_name => $set_value)
			{
				$value_found = false;
			
				// $item_name here is actually numeric index!
				foreach ($list as $item_name => $item_array)
				{
					if ($item_array['name'] == $set_value)
					{
						$value_found = true;
					}
				}
		
				// the !is_numeric() check ensures we don't try import images that were imported from media for instance when importing a preset that used a media item as image
				if ($value_found == false && !is_numeric($set_value))
				{
					$list[] = array(
						'type' => 'legacy',
						'name' => $set_value,
						'group' => 'user',
						'file' => $set_value
					);
				}
			}
		}
		
		// $item_name here is actually numeric index!
		foreach ($list as $item_name => $item_array)
		{
			$item_type = isset($item_array['type']) ? $item_array['type'] : null;
			$item_file = isset($item_array['file']) ? $item_array['file'] : null;
			$item_thumbnail = isset($item_array['thumbnail']) ? $item_array['thumbnail'] : null;
			
			if ($item_thumbnail == null) {
				$item_thumbnail = $item_file;
			}
			
			if ($item_thumbnail != null)
			{
				$img_src = $item_thumbnail;
				$img_full = $item_file;
				
				if (!isset($item_array['absolute-path']) || $item_array['absolute-path'] == false)
				{
					$img_src = $image_src . $img_src;
					$img_full = $image_src . $img_full;
				}
				
				$item_array['data'] = array(
					'type' => $item_type,
					'img-src' => $img_src, 
					'imagesrc' => $img_src,
					'img-full' => $img_full, 
				);
			
				$list[$item_name] = $item_array;
			}
		}
		
		array_unshift($list, array('type' => 'none', 'file' => '', 'label' => __('None', 'photocrati-framework')));
		
		return self::build_dropdown_html($setting_name, $setting_value, $list, $props);
	}
	
	public static function build_image_picker_html($setting_name, $setting_value, $type = null, $properties = null)
	{
		$image_src = get_bloginfo('template_url') . '/admin/images/';
		
		$dropdown_html = self::build_image_dropdown_html($setting_name, $setting_value, $type, $properties);
		
		$output = null;
		$output .= '<div class="photocrati-image-picker-container">';
		$output .= '<div class="photocrati-image-picker-toolbar">';
		
		$output .= '<div style="float:left;">';
		$output .= '<a class="button picker-button picker-action-deselect" style="margin-right: 5px;" href="#">' . __('No Image', 'photocrati-framework') . '</a>';
		$output .= '</div>';
		
		$output .= '<div style="float:right;">';
		$output .= '<a title="' . __('View Enlarged Image', 'photocrati-framework') . '" class="button picker-button picker-action-view" style="margin-right: 5px;" href="#"><img src="' . $image_src . 'view.png"></a>';
		$output .= '<a title="' . __('Add Image', 'photocrati-framework') . '" class="button picker-button picker-action-add" style="margin-right: 5px;" href="#"><img src="' . $image_src . 'plus.gif"></a>';
		$output .= '<a title="' . __('Remove Image', 'photocrati-framework') . '" class="button picker-button picker-action-remove" href="#"><img src="' . $image_src . 'delete.png"></a>';
		$output .= '</div>';
		
		$output .= '</div>';
		
		$output .= '<div class="photocrati-image-picker-dropdown">';
		$output .= $dropdown_html;
		$output .= '</div>';
		
		$output .= '</div>';
		
		return $output;
	}
	
	// TODO not completed
	public static function build_color_picker($setting_name, $setting_value, $opacity_name, $opacity_value, $type = null, $properties = null)
	{
		if ($type == 'switch')
		{
			$list = array(
				'transparent' => __('Transparent', 'photocrati-framework'),
				'color' => __('Color', 'photocrati-framework'),
			);
		}
		else if ($type == 'input' || $type == null)
		{
			
		}
	}
}
