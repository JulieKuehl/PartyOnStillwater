<?php defined('ABSPATH') or die("No direct access allowed");
/*
* Plugin Name:   eShop Shipping Extension (USPS Module)
* Plugin URI:	 http://usestrict.net/2012/07/usps-module-for-wordpress-eshop-shipping-extension/
* Description:   USPS Module for use with eShop extension.
* Version:       2.3.5
* Author:        Vinny Alves
* Author URI:    http://www.usestrict.net
*
* License:       Semi-free Usestrict Consulting License v1.1
* License URI:   http://usestrict.net/semi-free-usestrict-consulting-license
*
* Copyright (C) 2012-2013 www.usestrict.net, released under the Semi-Free UseStrict Consulting License.
*/
define('ESHOP_SHIPPING_EXTENSION_USPS_VERSION', '2.3.5');
define('ESHOP_SHIPPING_EXTENSION_USPS_DIR', plugin_dir_path(__FILE__));
define('ESHOP_SHIPPING_EXTENSION_USPS_MODULE_DIR', ESHOP_SHIPPING_EXTENSION_USPS_DIR . 'modules/usps-module/');
defined('ESHOP_FRAMEWORK_DIR') or define('ESHOP_FRAMEWORK_DIR',  ESHOP_SHIPPING_EXTENSION_USPS_DIR . '../eshop-shipping-extension/');


class USC_eShop_Shipping_Extension_USPS 
{
	var $domain          = 'eshop-shipping-extension-usps';
	var $module_dir      = ESHOP_SHIPPING_EXTENSION_USPS_MODULE_DIR;
	var $fw_dir          = ESHOP_FRAMEWORK_DIR;
	var $fw_is_installed = false;
	private $install_files = array('USC_eShop_USPS.php');
	public 	$api_url       = 'http://usestrict.net/update_api';
	
	function __construct()
	{
		register_activation_hook(__FILE__,array(&$this, 'install'));
		register_deactivation_hook(__FILE__,array(&$this, 'uninstall'));
		
		// Load language files for admin and ajax calls
		add_action('plugins_loaded', array(&$this,'load_lang'));
		
		// Check that the framework is installed. Not checking if it's active on purpose. We don't want to nag the users.
		if (file_exists(ESHOP_FRAMEWORK_DIR . 'eshop-shipping-extension.php'))
		{
			$this->fw_is_installed = true;
		} 
		
		add_action('admin_notices', array(&$this,'admin_notices'));	

		if (is_admin())
		{
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
				'slug' => $this->domain,
				'version' => $checked_data->checked[$this->domain .'/'. $this->domain .'.php'],
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
			$checked_data->response[$this->domain .'/'. $this->domain .'.php'] = $response;
	
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
	
		if (!isset($args->slug) || ($args->slug != $this->domain))
			return $res;
	
		// Get the current version
		$plugin_info = get_site_transient('update_plugins');
		$current_version = $plugin_info->checked[$this->domain .'/'. $this->domain .'.php'];
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
	 * Method: install()
	 * Description: copies appropriate files into framework directory
	 */
	function install()
	{
		if ($this->fw_is_installed === false)
		{
			return;
		}
		
		if (! is_dir($this->fw_dir . 'includes/modules/usps-module'))
		{
			if (! @mkdir($this->fw_dir . 'includes/modules/usps-module') )
			{
				$e = error_get_last();
				$this->_set_notice(__('Failed to create directory: ', $this->domain) . sprintf(' (%s)', $e['message']), true);
			}
		}
		
		foreach ($this->install_files as $file)
		{
			if (!@copy($this->module_dir . $file, $this->fw_dir . 'includes/modules/usps-module/' . $file))
			{
				$e = error_get_last();
				$this->_set_notice(__('Failed to install file: ', $this->domain) .  sprintf(' (%s)', $e['message']), true);
			}
		}
	}
	
	
	/**
	 * Method: uninstall()
	 * Description: deletes appropriate files from framework directory
	 */
	function uninstall()
	{
		if ($this->fw_is_installed === false)
		{
			return;
		}
	
		// Clean up the files
		foreach ($this->install_files as $file)
		{
			if (! file_exists($this->fw_dir . 'includes/modules/usps-module/' . $file)) continue;
			
			if (!@unlink($this->fw_dir . 'includes/modules/usps-module/' . $file))
			{
				$e = error_get_last();
				$this->_set_notice(__('Failed to UN-install file: ', $this->domain) . sprintf(' (%s)', $e['message']), true);
			}
		}
		
		// Now try to delete the directory
		if (!@rmdir($this->fw_dir . 'includes/modules/usps-module'))
		{
			$e = error_get_last();
			$this->_set_notice(__('Failed to delete directory: ', $this->domain) . sprintf(' (%s)', $e['message']), true);
		}		
	}
	
	/**
	 * Method: load_lang
	 * Description: Loads the locale file into the domain
	 */
	function load_lang()
	{
		load_plugin_textdomain($this->domain, false, $this->domain . '/includes/Languages/' );
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
					       'eShop Shipping Extension</a> is not installed! It is required for USPS module to work!',$this->domain).
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
	 * Method: _set_notice()
	 * Description: Saves notices in $_SESSION so they can be fetched after a REQUEST->redirect
	 */
	private function _set_notice($msg, $is_error = false)
	{
		$_SESSION['usc_notices'][] = array('msg' => $msg, 'is_error' => $is_error);
	}
}


$USC_eShop_Shipping_Extension_USPS = new USC_eShop_Shipping_Extension_USPS();


/* End of file eshop-shipping-extension-usps.php */
/* Location: eshop-shipping-extension-usps/eshop-shipping-extension-usps.php */