<?php defined('ABSPATH') or die("No direct access allowed");
/*
* Plugin Name:   eShop Shipping Extension (Handling Fee Add-on)
* Plugin URI:	 http://usestrict.net/2012/12/handling-fee-add-on-for-eshop-shipping-extension
* Description:   Add Handling to shipping results
* Version:       2.1
* Author:        Vinny Alves
* Author URI:    http://www.usestrict.net
*
* License:       Semi-free Usestrict Consulting License v1.1
* License URI:   http://usestrict.net/semi-free-usestrict-consulting-license
*
* Copyright (C) 2012 www.usestrict.net, released under the GNU General Public License.
*/
define('ESHOP_SHIPPING_EXTENSION_HANDLING_VERSION', '2.1');
define('ESHOP_SHIPPING_EXTENSION_HANDLING_DIR', plugin_dir_path(__FILE__));
defined('ESHOP_FRAMEWORK_DIR') or define('ESHOP_FRAMEWORK_DIR',  WP_PLUGIN_DIR . '/eshop-shipping-extension/');

class USC_eShop_Shipping_Extension_Handling
{
	public  $version;
	private $fw_is_installed = false;
	private $my_domain       = 'usc-eSE-handling';
	private $options_name    = 'eshop-shipping-extension';
	private $service_list    = array();
	private $tmpl_dir;
	public 	$api_url = 'http://usestrict.net/update_api';
	public  $slug = 'eshop-shipping-extension-handling';
	
	public function __construct()
	{
		$this->tmpl_dir = ESHOP_SHIPPING_EXTENSION_HANDLING_DIR . '/includes/templates/';

		if (is_admin())
		{
			$this->version = 'v' . ESHOP_SHIPPING_EXTENSION_HANDLING_VERSION;
			
			// Check that the framework is installed. Not checking if it's active on purpose. We don't want to nag the users.
			if (file_exists(ESHOP_FRAMEWORK_DIR . 'eshop-shipping-extension.php'))
			{
				$this->fw_is_installed = true;
			}
			
			add_action('admin_notices', array(&$this,'admin_notices'));
			add_filter('usc_after_carrier_list', array(&$this,'admin_html_form'), 10, 1);
			add_filter('usc_do_handling', array(&$this,'do_handling'), 10,2); # ajax inherited from eSE, so it has to be in is_admin()
			
			// Take over the update check
			add_filter('pre_set_site_transient_update_plugins', array(&$this,'check_for_plugin_update'));
			
			// Take over the Plugin info screen
			add_filter('plugins_api', array(&$this,'plugin_api_call'), 10, 3);
		}
	}
	
	
	/**
	 * @method check_for_plugin_update
	 * @desc Checks UseStrict.net for any plugin updates
	 * @param object $checked_data
	 */
	public function check_for_plugin_update($checked_data)
	{
		global  $wp_version;
	
		//Comment out these two lines during testing.
		if (empty($checked_data->checked))
			return $checked_data;
	
		$args = array(
				'slug' => $this->slug,
				'version' => $checked_data->checked[$this->slug .'/'. $this->slug .'.php'],
		);
		$request_string = array(
				'body' => array(
						'action' => 'basic_check',
						'request' => serialize($args),
						'api-key' => md5(get_bloginfo('url'))
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
	
		// Start checking for an update
		$raw_response = wp_remote_post($this->api_url, $request_string);
	
	
		if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
			$response = unserialize($raw_response['body']);
	
		if (is_object($response) && !empty($response)) // Feed the update data into WP updater
			$checked_data->response[$this->slug .'/'. $this->slug .'.php'] = $response;
	
		return $checked_data;
	}
	
	/**
	 * @method plugin_api_call
	 * @desc Fetches updates from UseStrict.net
	 * @param $res, $action, $args
	 */
	public function plugin_api_call($res, $action, $args)
	{
		global $wp_version;
	
		if (!isset($args->slug) || ($args->slug != $this->slug))
			return $res;
	
		// Get the current version
		$plugin_info = get_site_transient('update_plugins');
		$current_version = $plugin_info->checked[$this->slug .'/'. $this->slug .'.php'];
		$args->version = $current_version;
	
		$request_string = array(
				'body' => array(
						'action' => $action,
						'request' => serialize($args),
						'api-key' => md5(get_bloginfo('url'))
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
	
		$request = wp_remote_post($this->api_url, $request_string);
	
		if (is_wp_error($request))
		{
			$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
		}
		else
		{
			$res = unserialize($request['body']);
	
			if ($res === false)
			{
				$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
			}
		}
	
		return $res;
	}
	
	/**
	 * Method: do_handling
	 * Desc: Applies handling fees according to options
	 */
	function do_handling($options=array(), $out=array())
	{
		if (! $options['add_handling'])
		{
			return $out;
		}
		
		if ($options['add_handling'] == 1)
		{
			$out = $this->do_global_handling($options, $out);
			return $out;
		}
		else 
		{
			$out = $this->do_custom_handling($options, $out);
			return $out;
		}
	}

	/**
	 * Method: format_handling_amt
	 * Desc: Replaces any x,dd for x.dd
	 */
	private function format_handling_amt($amt)
	{
		$amt = preg_replace('/,(\d{2})$/','.$1',$amt);
		
		$handling_amt = (is_numeric($amt)) ? $amt : '0.00';
		$handling_amt = number_format($handling_amt,2,'.', '');
		
		return $handling_amt;
	}
	
	/**
	 * Method: do_global_handling
	 * Desc: Applies Global Handling to SESSION and $out
	 */
	public function do_global_handling($options=array(), $out=array())
	{
		global $blog_id;
		
		if (isset($options['handling_amount']))
		{
			$handling_amt = $this->format_handling_amt($options['handling_amount']);
		}
		else
		{
			return $out;
		}
		
		foreach ($_SESSION['usc_3rd_party_shipping'.$blog_id] as $key => $val)
		{
			if (isset($_SESSION['usc_3rd_party_shipping'.$blog_id][$key]['price']))
			{
				$_SESSION['usc_3rd_party_shipping'.$blog_id][$key]['price'] += $handling_amt;
			}
		}
		
		foreach ($out as $key => $val)
		{
			if ($val['success'] === true)
			{
				foreach ($val['data'] as $svc_name => $svc)
				{
					if (isset($svc['price']))
					{
						$out[$key]['data'][$svc_name]['price'] += $handling_amt;
					}
				}
			}
		}
		
		return $out;
	}
	
	
	/**
	 * Method: do_custom_handling
	 * Desc: Applies Custom Handling to SESSION and $out
	 */
	public function do_custom_handling($options=array(), $out=array())
	{
		global $blog_id;
		
		$svcs = $options['custom_handling'];
		$handling_amts = array();
		
		foreach ($_SESSION['usc_3rd_party_shipping'.$blog_id] as $key => $val)
		{
			$handling_amt = $this->get_custom_amount($key,$svcs);
			$handling_amts[$key] = $handling_amt;
			
			if (isset($_SESSION['usc_3rd_party_shipping'.$blog_id][$key]['price']))
			{
				$_SESSION['usc_3rd_party_shipping'.$blog_id][$key]['price'] += $handling_amt;
			}
		}
		
		foreach ($out as $key => $val)
		{
			if ($val['success'] === true)
			{
				foreach ($val['data'] as $svc_name => $svc)
				{
					if (isset($svc['price']))
					{
						$out[$key]['data'][$svc_name]['price'] += $handling_amts[$svc_name];
					}
				}
			}
		}
		
		return $out;
	}
	
	/**
	 * Method: get_custom_amount
	 * Desc: Fetches the handling fee for a given service
	 */
	public function get_custom_amount($svc_name, $amounts)
	{
		$svc_name = preg_replace('/[ ]+/', ' ', $svc_name);
		
		$carrier = substr($svc_name,0,strpos($svc_name, ' - '));
		$amount = '0.00';

		if ($amounts[$carrier][$svc_name] && 
			is_numeric($amounts[$carrier][$svc_name]))
		{
			$amount = $amounts[$carrier][$svc_name];
		}
		elseif ($amounts[$carrier]['default'] && 
				is_numeric($amounts[$carrier]['default']))
		{
			$amount = $amounts[$carrier]['default'];
		}
		
		return $this->format_handling_amt($amount);
	}
	
	
	/**
	 * Method: admin_notices
	 * Desc: Shows important notices in the admin interface
	 */
	function admin_notices()
	{
		if ($this->fw_is_installed === false)
		{
			echo '<div class="error"><p><strong>'.
					__('<a href="http://usestrict.net/2012/06/eshop-shipping-extension-for-wordpress-canada-post/" target="_new">' .
							'eShop Shipping Extension</a> is not installed! It is required for the Handling module to work!',$this->domain).
							'</strong></p></div>';
		}
	
		if (isset($_SESSION['usc_notices']))
		{
			foreach ($_SESSION['usc_notices'] as $notice)
			{
				$class = $notice['is_error'] === true ? 'error' : 'updated fade';
	
				echo sprintf('<div class="%s"><p><strong>%s</strong></p></div>',$class, $notice['msg']);
			}
	
			unset($_SESSION['usc_notices']);
		}
	}
	
	
	
	/**
	 * Method: admin_html_form()
	 * Description: Method called by the usc_after_carrier_list filter
	 * @param  string $content
	 * @return string
	 */
	function admin_html_form($options=array())
	{
		$params['show_hide'] = 'style="display:none"';
		$params['custom_show_hide'] = 'style="display:none"';
		
		if (isset($options['add_handling']))
		{
			if ($options['add_handling'] == 1) 
			{
				$params['show_hide'] = '';
			}
			elseif ($options['add_handling'] == 2)
			{
				$params['custom_show_hide'] = '';
			}
		}
		else
		{
			$options['add_handling'] = 0;
		}
		
		$params['service_list'] = $this->get_service_list();
		
		$params['handling_amt'] = isset($options['handling_amount']) ? $options['handling_amount'] : '0.00';
		
		include_once($this->tmpl_dir . '/admin.tmpl.php');
	}
	
	/**
	 * Method: get_service_list()
	 * Description: Gets service names for all installed Carriers
	 * @param  bool $force - force reload of the list
	 * @return array
	 */
	public function get_service_list($force = FALSE)
	{
		if (empty($this->service_list) || $force === TRUE)
		{
			$this->service_list = apply_filters('usc_carrier_service_list', $this->service_list);
			ksort($this->service_list);
		}

		return $this->service_list;
	}
}


new USC_eShop_Shipping_Extension_Handling();

/* End of file eshop-shipping-extension-handling.php */
/* Location: eshop-shipping-extension-handling/eshop-shipping-extension-handling.php */