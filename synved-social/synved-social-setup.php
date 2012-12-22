<?php

function synved_social_provider_settings()
{
	$share_providers = synved_social_service_provider_list('share', true);
	$follow_providers = synved_social_service_provider_list('follow', true);
	$provider_list = array_merge($share_providers, $follow_providers);
	$providers_settings = array();

	foreach ($provider_list as $provider_name => $provider_item)
	{
		$provider_label = ucwords(str_replace(array('-', '_'), ' ', $provider_name));
		$display_set = 'none=None';
		$display_default = 'none';
	
		if (isset($provider_item['label']))
		{
			$provider_label = $provider_item['label'];
		}
		
		if (isset($share_providers[$provider_name]))
		{
			$display_set .= ',share=Share';
			$display_default = 'share';
		}
		
		if (isset($follow_providers[$provider_name]))
		{
			$display_set .= ',follow=Follow';
			$display_default = 'follow';
			
			if (isset($share_providers[$provider_name]))
			{
				$display_set .= ',both=Share & Follow';
				$display_default = 'both';
			}
		}
		
		$providers_settings = array_merge($providers_settings, 
			array(
				$provider_name . '_display' => array(
					'default' => $display_default,
					'style' => 'group',
					'set' => $display_set,
					'label' => __($provider_label . ' Service', 'synved-social'), 
					'tip' => __('Decides for what types of services ' . $provider_label . ' will be used by default', 'synved-social')
				),
			)
		);
	
		if (isset($share_providers[$provider_name]))
		{
			$share_item = $share_providers[$provider_name];
			
			$providers_settings = array_merge($providers_settings, 
				array(
					$provider_name . '_share_link' => array(
						'label' => __($provider_label . ' Share Link', 'synved-social'), 
						'tip' => __('The link used by default for sharing content on ' . $provider_label . ' (a standard one will be used if left empty)', 'synved-social'),
						'hint' => $share_item['link']
					),
					$provider_name . '_share_title' => array(
						'label' => __($provider_label . ' Share Title', 'synved-social'), 
						'tip' => __('The title used by default for the ' . $provider_label . ' share button (a standard one will be used if left empty)', 'synved-social'),
						'hint' => $share_item['title']
					),
				)
			);
		}
	
		if (isset($follow_providers[$provider_name]))
		{
			$follow_item = $follow_providers[$provider_name];
			
			$providers_settings = array_merge($providers_settings, 
				array(
					$provider_name . '_follow_link' => array(
						'label' => __($provider_label . ' Follow Link', 'synved-social'), 
						'tip' => __('The link used by default for following you on ' . $provider_label, 'synved-social'),
						'hint' => $follow_item['link']
					),
					$provider_name . '_follow_title' => array(
						'label' => __($provider_label . ' Follow Title', 'synved-social'), 
						'tip' => __('The title used by default for the ' . $provider_label . ' follow button (a standard one will be used if left empty)', 'synved-social'),
						'hint' => $follow_item['title']
					),
				)
			);
		}
	}
	
	return $providers_settings;
}

$synved_social_options = array(
'settings' => array(
	'label' => 'Social Media',
	'title' => 'Social Media Feather',
	'tip' => synved_option_callback('synved_social_page_settings_tip'),
	'sections' => array(
		'section_general' => array(
			'label' => __('General Settings', 'synved-social'), 
			'tip' => __('Settings affecting the general behavior of the plugin', 'synved-social'),
			'settings' => array(
				'use_shortlinks' => array(
					'default' => false, 'label' => __('Use Shortlinks', 'synved-social'), 
					'tip' => __('Allows for shortened URLs to be used when sharing content if a shortening plugin is installed', 'synved-social')
				),
				'shortcode_widgets' => array(
					'default' => true, 'label' => __('Shortcodes In Widgets', 'synved-social'), 
					'tip' => __('Allow shortcodes in Text widgets', 'synved-social')
				),
				'automatic_share' => array(
					'default' => false, 'label' => __('Display Sharing Buttons', 'synved-social'), 
					'tip' => __('Tries to automatically append sharing buttons to your posts', 'synved-social')
				),
				'automatic_share_post_types' => array(
					'type' => 'custom',
					'default' => 'post',
					'set' => synved_option_callback('synved_social_automatic_share_post_types_set', array('post', 'page')),
					'label' => __('Share Post Types', 'synved-social'), 
					'tip' => __('Post types for which automatic appending should be attempted (CTRL + click to select multiple ones)', 'synved-social'),
					'render' => 'synved_social_automatic_share_post_types_render'
				),
				'show_credit' => array(
					'default' => true, 'label' => __('Show Credit', 'synved-social'), 
					'tip' => __('Display a small icon with a link to the Social Media Feather page', 'synved-social')
				),
			)
		),
		'section_customize_look' => array(
			'label' => __('Customize Look', 'synved-social'), 
			'tip' => synved_option_callback('synved_social_section_customize_look_tip', __('Customize the look & feel of Social Media Feather', 'synved-social')),
			'settings' => array(
				'icon_skin' => array(
					'default' => 'regular',
					'set' => synved_option_callback('synved_social_icon_skin_set', 'regular=Regular'),
					'label' => __('Icon Skin', 'synved-social'), 
					'tip' => synved_option_callback('synved_social_setting_icon_skin_tip',__('Select the default skin to use for the icons', 'synved-social')),
					'render' => 'synved_social_icon_skin_render'
				),
				'addon_extra_icons' => array(
					'type' => 'addon',
					'target' => SYNVED_SOCIAL_ADDON_PATH,
					'folder' => 'extra-icons',
					'style' => 'addon-important',
					'label' => __('Extra Icon Skins', 'synved-social'), 
					'tip' => synved_option_callback('synved_social_option_addon_extra_icons_tip', __('Click the button to install the "Extra Social Icons" addon, get it <a target="_blank" href="http://synved.com/product/feather-extra-social-icons/">here</a>.', 'synved-social'))
				),
				'addon_grey_fade' => array(
					'type' => 'addon',
					'target' => SYNVED_SOCIAL_ADDON_PATH,
					'folder' => 'grey-fade',
					'style' => 'addon-important',
					'label' => __('Grey Fade Effect', 'synved-social'), 
					'tip' => synved_option_callback('synved_social_option_addon_grey_fade_tip', __('Click the button to install the "Grey Fade" addon, get it <a target="_blank" href="http://synved.com/product/feather-grey-fade/">here</a>.', 'synved-social'))
				),
				'icon_size' => array(
					'default' => 48,
					'set' => '16=16x16,24=24x24,32=32x32,48=48x48,64=64x64,96=96x96',
					'label' => __('Icon Size', 'synved-social'), 
					'tip' => __('Select the size in pixels for the icons', 'synved-social')
				),
				'icon_spacing' => array(
					'default' => 5,
					'label' => __('Icon Spacing', 'synved-social'), 
					'tip' => __('Select the spacing in pixels between the icons', 'synved-social')
				),
				'custom_style' => array(
					'type' => 'style',
					'label' => __('Extra Styles', 'synved-social'), 
					'tip' => __('Any CSS styling code you type in here will be loaded after all of the Social Media Feather styles.', 'synved-social')
				),
			)
		),
		'section_service_providers' => array(
			'label' => __('Service Providers', 'synved-social'), 
			'tip' => __('Customize social sharing and following providers', 'synved-social'),
			'settings' => synved_social_provider_settings()
		)
	)
)
);


synved_option_register('synved_social', $synved_social_options);

synved_option_include_module_addon_list('synved-social');


function synved_social_page_settings_tip($tip, $item)
{
	if (!function_exists('synved_shortcode_version'))
	{
		$tip .= ' <div style="background:#f2f2f2;font-size:110%;color:#444;padding:10px 15px;"><b>' . __('Note', 'synved-social') . '</b>: ' . __('The Social Media Feather plugin is fully compatible with our <a target="_blank" href="http://synved.com/wordpress-shortcodes/">WordPress Shortcodes</a> plugin!</span>', 'synved-social') . '</div>';
	}
	
	if (function_exists('synved_connect_support_social_follow_render'))
	{
		$tip .= synved_connect_support_social_follow_render();
	}
	
	return $tip;
}

function synved_social_section_customize_look_tip($tip, $item)
{
	return $tip;
}

function synved_social_icon_skin_set($set, $item) 
{
	if ($set != null && !is_array($set))
	{
		$set = synved_option_item_set_parse($item, $set);
	}
	
	$set = array();
	$icons = synved_social_icon_skin_list();

	foreach ($icons as $icon_name => $icon_meta)
	{
		$set[][$icon_name] = $icon_meta['label'];
	}
	
	return $set;
}

function synved_social_setting_icon_skin_tip($tip, $item)
{
	$uri = synved_social_path_uri();
	
	if (!synved_option_addon_installed('synved_social', 'addon_extra_icons'))
	{
		$tip .= '<div style="clear:both"><p style="font-size:120%;"><b>Get all 8 extra icon skins you see below with the <a target="_blank" href="http://synved.com/product/feather-extra-social-icons/">Extra Social Icons addon</a></b>:</p> <a target="_blank" href="http://synved.com/product/feather-extra-social-icons/"><img src="' . $uri . '/image/social-feather-extra-icons.png" /></a></div>';
	}
	
	return $tip;
}

function synved_social_icon_skin_render($value, $params, $id, $name, $item) 
{
	$uri = synved_social_path_uri();
	$icons = synved_social_icon_skin_list();
	
	$out = null;
	$out_name = $params['output_name'];
	$set = $params['set'];
	
	$out .= '<div>';

	foreach ($set as $set_it)
	{
		$set_it_keys = array_keys($set_it);
		$selected = $set_it_keys[0] == $value ? ' checked="checked"' : null;
		$img_src = '';
		
		if (isset($icons[$set_it_keys[0]]))
		{
			$img_src = $icons[$set_it_keys[0]]['image'];
		}
		
		$out .= '<div style="text-align:center; width:260px; float:left; margin-right:20px;"><label title="Use skin=&quot;' . esc_attr($set_it_keys[0]) . '&quot; in shortcodes"><img src="' . esc_url($img_src) . '" style="border:solid 1px #bbb" /><p><input type="radio" name="' . esc_attr($out_name) . '" value="' . esc_attr($set_it_keys[0]) . '"' . $selected . '/> ' . $set_it[$set_it_keys[0]] . '</p></label></div>';
	}
	
	$out .= '</div>';
	
	return $out;
}


function synved_social_automatic_share_post_types_set($set, $item) 
{
	if ($set != null && !is_array($set))
	{
		$set = synved_option_item_set_parse($item, $set);
	}
	
	$set = array();
	$types = get_post_types(array('public' => true));

	foreach ($types as $type_name)
	{
		$set[][$type_name] = $type_name;
	}
	
	return $set;
}

function synved_social_automatic_share_post_types_render($value, $params, $id, $name, $item) 
{
	$uri = synved_social_path_uri();
	$icons = synved_social_icon_skin_list();
	
	if (!is_array($value))
	{
		if ($value != null)
		{
			$value = array($value);
		}
		else
		{
			$value = array();
		}
	}
	
	$out = null;
	$out_name = $params['output_name'];
	$set = $params['set'];
	
	$out .= '<select multiple="multiple" name="' . esc_attr($out_name . '[]') . '">';

	foreach ($set as $set_it)
	{
		$set_it_keys = array_keys($set_it);
		$selected = in_array($set_it_keys[0], $value) ? ' selected="selected"' : null;
		
		$out .= '<option value="' . esc_attr($set_it_keys[0]) . '"' . $selected . '>' . $set_it[$set_it_keys[0]] . '</option>';
	}
	
	$out .= '</select>';
	
	return $out;
}

function synved_social_option_addon_extra_icons_tip($tip, $item)
{
	if (synved_option_addon_installed('synved_social', 'addon_extra_icons'))
	{
		$tip .= ' <span style="background:#eee;padding:5px 8px;">' . __('The "Extra Social Icons" addon is already installed! You can use the button to re-install it.', 'synved-social') . '</span>';
	}
	
	return $tip;
}

function synved_social_option_addon_grey_fade_tip($tip, $item)
{
	$uri = synved_social_path_uri();
	
	if (synved_option_addon_installed('synved_social', 'addon_grey_fade'))
	{
		$tip .= ' <span style="background:#eee;padding:5px 8px;">' . __('The "Grey Fade" addon is already installed! You can use the button to re-install it.', 'synved-social') . '</span>';
	}
	else
	{
		$tip .= '<div style="clear:both"><p style="font-size:120%;"><b>The <a target="_blank" href="http://synved.com/product/feather-grey-fade/">Grey Fade addon</a> allows you to achieve the effect below, <a target="_blank" href="http://synved.com/product/feather-grey-fade/">get it now</a>!</b></p> <a target="_blank" href="http://synved.com/product/feather-grey-fade/"><img src="' . $uri . '/image/social-feather-grey-fade-demo.png" /></a></div>';
	}
	
	return $tip;
}

function synved_social_path($path = null)
{
	$root = dirname(__FILE__);
	
	if ($root != null)
	{
		if (substr($root, -1) != '/' && $path[0] != '/')
		{
			$root .= '/';
		}
		
		$root .= $path;
	}
	
	$root = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $root);
	
	return $root;
}

function synved_social_path_uri($path = null)
{
	$uri = plugins_url('/social-media-feather') . '/synved-social';
	
	if (function_exists('synved_plugout_module_uri_get'))
	{
		$mod_uri = synved_plugout_module_uri_get('synved-social');
		
		if ($mod_uri != null)
		{
			$uri = $mod_uri;
		}
	}
	
	if ($path != null)
	{
		if (substr($uri, -1) != '/' && $path[0] != '/')
		{
			$uri .= '/';
		}
		
		$uri .= $path;
	}
	
	return $uri;
}

function synved_social_wp_register_common_scripts()
{
	$uri = synved_social_path_uri();
}

function synved_social_enqueue_scripts()
{
	$uri = synved_social_path_uri();
	
	synved_social_wp_register_common_scripts();
}

function synved_social_print_styles()
{
}

function synved_social_admin_enqueue_scripts()
{
	$uri = synved_social_path_uri();
	
	synved_social_wp_register_common_scripts();
}

function synved_social_admin_print_styles()
{
}

function synved_social_wp_tinymce_plugin($plugin_array)
{
	$plugin_array['synved_social'] = synved_social_path_uri() . '/script/tinymce_plugin.js';

	return $plugin_array;
}

function synved_social_wp_tinymce_button($buttons) 
{
	array_push($buttons, '|', 'synved_social');
	
	return $buttons;
}

function synved_social_ajax_callback()
{
	check_ajax_referer('synved-social-submit-nonce', 'synvedSecurity');

	if (!isset($_POST['synvedAction']) || $_POST['synvedAction'] == null) 
	{
		return;
	}

	$action = $_POST['synvedAction'];
	$params = isset($_POST['synvedParams']) ? $_POST['synvedParams'] : null;
	$response = null;
	$response_html = null;
	
	if (is_string($params))
	{
		$parms = json_decode($params, true);
		
		if ($parms == null)
		{
			$parms = json_decode(stripslashes($params), true);
		}
		
		$params = $parms;
	}
	
	switch ($action)
	{
		case 'load-ui':
		{
			$uri = synved_social_path_uri();
			
			if (current_user_can('edit_posts') || current_user_can('edit_pages'))
			{
			}
			
			break;
		}
		case 'preview-code':
		{
			if (current_user_can('edit_posts') || current_user_can('edit_pages'))
			{
			}
			
			break;
		}
	}

	while (ob_get_level() > 0) 
	{
		ob_end_clean();
	}

	if ($response != null) 
	{
		$response = json_encode($response);

		header('Content-Type: application/json');

		echo $response;
	}
	else if ($response_html != null) 
	{
		header('Content-Type: text/html');

		echo $response_html;
	}
	else 
	{
		header('HTTP/1.1 403 Forbidden');
	}

	exit();
}

function synved_social_register_widgets() 
{
	register_widget('SynvedSocialShareWidget');
	register_widget('SynvedSocialFollowWidget');
}

function synved_social_wp_the_content($content)
{
	if (synved_option_get('synved_social', 'automatic_share'))
	{
		$post_type = get_post_type();
		$type_list = synved_option_get('synved_social', 'automatic_share_post_types');
		
		if (in_array($post_type, $type_list))
		{
			$content .= synved_social_share_markup();
		}
	}
	
	return $content;
}

function synved_social_init()
{
	if (current_user_can('edit_posts') || current_user_can('edit_pages'))
	{
		if (get_user_option('rich_editing') == 'true')
		{
			//add_filter('mce_external_plugins', 'synved_social_wp_tinymce_plugin');
			//add_filter('mce_buttons', 'synved_social_wp_tinymce_button');
		}
	}

	$priority = defined('SHORTCODE_PRIORITY') ? SHORTCODE_PRIORITY : 11;
	
	if (synved_option_get('synved_social', 'shortcode_widgets'))
	{
		remove_filter('widget_text', 'do_shortcode', $priority);
		add_filter('widget_text', 'do_shortcode', $priority);
	}
	
	if (function_exists('synved_shortcode_add'))
	{
  	synved_shortcode_add('feather_share', 'synved_social_share_shortcode');
  	synved_shortcode_add('feather_follow', 'synved_social_follow_shortcode');
  	
  	$size_set = '16,24,32,48,64,96';
  	$size_item = synved_option_item('synved_social', 'icon_size');
  	
  	if ($size_item != null)
  	{
  		$item_set = synved_option_item_set($size_item);
  		
  		if ($item_set != null)
  		{
  			$set_items = array();
  			
  			foreach ($item_set as $set_item)
  			{
  				$item_keys = array_keys($set_item);
  				
  				$set_items[] = $item_keys[0];
  			}
  			
  			$size_set = implode(',', $set_items);
  		}
  	}
  	
  	$sh_params = array(
			'skin' => __('Specify which skin to use for the icons', 'synved-social'),
			'size' => sprintf(__('Specify the size for the icons, possible values are %s', 'synved-social'), $size_set),
			'spacing' => __('Determines how much blank space there will be between the buttons, in pixels', 'synved-social'),
		);
		
  	$share_params = array(
			'url' => __('URL to use for the sharing buttons, default is the current post URL', 'synved-social'),
			'title' => __('Title to use for the sharing buttons, default is the current post title', 'synved-social'),
		);
	
		synved_shortcode_item_help_set('feather_share', array(
			'tip' => __('Creates a list of buttons for social sharing as selected in the Social Media options', 'synved-social'),
			'parameters' => array_merge($sh_params, $share_params)
		));
		synved_shortcode_item_help_set('feather_follow', array(
			'tip' => __('Creates a list of buttons for social following as selected in the Social Media options', 'synved-social'),
			'parameters' => $sh_params
		));
	}
	else
	{
  	add_shortcode('feather_share', 'synved_social_share_shortcode');
  	add_shortcode('synved_feather_share', 'synved_social_share_shortcode');
  	add_shortcode('feather_follow', 'synved_social_follow_shortcode');
  	add_shortcode('synved_feather_follow', 'synved_social_follow_shortcode');
	}
	
  //add_action('wp_ajax_synved_social', 'synved_social_ajax_callback');
  //add_action('wp_ajax_nopriv_synved_social', 'synved_social_ajax_callback');

	if (!is_admin())
	{
		add_action('wp_enqueue_scripts', 'synved_social_enqueue_scripts');
		//add_action('wp_print_styles', 'synved_social_print_styles');
	}
	
	if (synved_option_get('synved_social', 'automatic_share'))
	{
  	add_filter('the_content', 'synved_social_wp_the_content');
	}
}

add_action('init', 'synved_social_init');
add_action('admin_enqueue_scripts', 'synved_social_admin_enqueue_scripts');
add_action('admin_print_styles', 'synved_social_admin_print_styles', 1);

add_action('widgets_init', 'synved_social_register_widgets');

