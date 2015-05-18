<?php defined('ABSPATH') or die("No direct access allowed");
/**
 * @package   USC_eShop_USPS
 * @desc      Manage eShop <-> USPS interface
 * @author    Vinny Alves (vinny@usestrict.net)
 * @copyright 2012
 */ 
defined('USC_IS_PAID') or define('USC_IS_PAID', 1); // Identify that we're using paid services
class USC_eShop_USPS extends USC_eShop_Shipping_Extension
{
    protected $my_options_name = 'usps-module';
    private $my_domain       = 'eshop-shipping-extension-usps';
    public  $version         = ESHOP_SHIPPING_EXTENSION_USPS_VERSION;
    public  $module_name     = 'US Postal Service';
    public  $options         = array();
    private $live_url        = 'http://production.shippingapis.com/ShippingAPI.dll?API=';
    private $test_url        = 'http://production.shippingapis.com/ShippingAPI.dll?API=';
//     private $test_url        = 'http://testing.shippingapis.com/ShippingAPITest.dll?API='; // USPS testing servers don't support RateV4.
    private $api;            // USPS uses different APIs depending on domestic/int'l shipping. Set by _make_xml_request
    public  $is_postal  = true; // Controls with which other modules this can be used. Canada Post/USPS/Correios are mutually exclusive.
    private $html_helper;
    private $xml_helper;
    
    function __construct()
    {
        $this->html_helper = new USC_USPS_html_helper();
        $this->xml_helper  = new USC_USPS_xml_helper();
        
        add_filter('usc_carrier_service_list',array(&$this,'_get_all_service_names'),10,1);
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  get_options()
     * @desc    Returns default/saved options for this shipping module
     * @param   bool $force - to skip the cache, forcing the reload of the DB options
     * @return  array
     */
    function get_options($force = FALSE)
    {
        if ($this->options && !$force)
        {
            return $this->options;
        }
    
        $default        = array();
        $parent_optname = parent::get_options_name();
    
        $default[$parent_optname][$this->my_options_name]['username']  = null;
        $default[$parent_optname][$this->my_options_name]['allowed_services'][] = 'all';
        $default[$parent_optname][$this->my_options_name]['intl_mail_types'][]  = 'All';
    
        $options = get_option($parent_optname, $default);
    
        if ($options)
        {
            $this->options               = $options[$this->my_options_name];
            $this->options['from_zip']   = $options['from_zip'];
            $this->options['debug_mode'] = $options['debug_mode'];
            $this->options['package_class'] = $options['package_class'];
            $this->options['in_store_pickup'] = $options['in_store_pickup'];
            $this->options['in_store_pickup_text'] = $options['in_store_pickup_text'];
        }
        else
        {
            $this->options = $default[$parent_optname][$this->my_options_name];
        }
    
        return $this->options;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  validate_input()
     * @desc    validates module-specific fields
     * @param   array $input
     * @return  array validated $input
     */
    function validate_input($input)
    {
        // Return untouched if this module isn't selected as active
        if ($input['third_party'] !== get_class($this))
        {
            return $input;
        }
    
        $this->do_recursive($input,'trim');
    
    
        foreach($input[$this->my_options_name] as $key => $val)
        {
            if (!is_array($val) && (! isset($val) || $val === ''))
            {
                add_settings_error($key,$key, sprintf(__('%s is a required value!', $this->domain), $key), 'error');
            }
        }
    
        return $input;
    
    }
    
    /**
     * @package USC_eShop_USPS
     * @method  intro_paragraph()
     * @desc    Returns the introductory paragraph for the module
     * @return  string
     */
    function intro_paragraph()
    {
        return __('<p>In order to use USPS API, you must first register at <a href="https://secure.shippingapis.com/registration/" target="_new">'.
                  'USPS Web Tools</a>. Registration is free and you will receive a userid and password by email. It is then necessary to ' . 
                  'contact USPS Internet Customer Care Center (ICCC) either by email (<a href="mailto:uspstechsupport@esecurecare.net">uspstechsupport@esecurecare.net</a>) '. 
                  'or phone (1-800-344-7779) ' . 
                  'to have them enable production access (even for testing - as their testing servers are broken). Read more at ' . 
                  '<a href="https://www.usps.com/business/webtools.htm">USPS Web Tools</a> and ' .
                  '<a href="http://stackoverflow.com/questions/7885269/response-from-usps-rate-calculator" target="_new">'.
                  'USPS Testing server issue</a>.</p>',$this->my_domain);
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  admin_form_html()
     * @desc    Returns the html that makes up the admin_form fields
     * @return  string
     */
    function admin_form_html()
    {
        $opts            = $this->get_options();
        $po              = parent::get_options_name();
        $uname           = __('User ID',$this->my_domain);
        $uname_info      = __('The User ID received from the USPS Web Tools Registration process.', $this->my_domain);
        $allowed         = __('Domestic Service Whitelist', $this->my_domain);
        $all             = __('All', $this->my_domain);
        $send_value      = __('Send Value', $this->my_domain);
        $send_value_info = __('Sends USPS the value of the package. Used to determine availability and cost of extra services (where applicable).',$this->my_domain);
        $yes             = __('Yes', $this->domain);
        $no              = __('No', $this->domain);
        $available       = $this->get_available_domestic_services();
        
        
        $send_value_array = array('No' => 'NO', 'Domestic' => 'DOMESTIC', 'International' => 'INTERNATIONAL', 'Both' => 'BOTH');
        foreach ($send_value_array as $key => $val)
        {
            $selected = (isset($opts['send_value']) && $val == $opts['send_value']) ? 'selected="selected"' : '';            
            $send_value_options .= '<option value="'.$val.'" '.$selected.'>'.$key.'</option>';
        }
        
        $services_text = __('Select one or more USPS services to get rates for. '.
                            'Note that "All" and "Online" will <strong>not</strong> return Special Services. '. 
                            '"All" will be assumed if no service is selected.' ,$this->my_domain);
        
        foreach ($available as $opt)
        {
            $selected = (isset($opts['allowed_services']) && in_array($opt, $opts['allowed_services'])) ? 'selected="selected"' : '';
            $options .= '<option value="' . $opt . '" ' . $selected .  '>' .$opt."</option>";
        }
        
        $all_selected = (isset($opts['allowed_services']) && in_array('all', $opts['allowed_services'])) ? 'selected="selected"' : '';
        
        $select  = "<select id=\"allowed_services\" name=\"{$po}[$this->my_options_name][allowed_services][]\"  multiple=\"multiple\" size=\"10\">";
        $select .= $options;
        $select .= "</select>";
        
        
        $allowed_intl_mt_array = $this->get_available_intl_services();
        
        foreach ($allowed_intl_mt_array as $key => $val)
        {
            if (isset($opts['intl_mail_types']))
            {
                $selected = in_array($val['value'], $opts['intl_mail_types']) ? 'selected="selected"' : '';
            }
            $intl_mt_options .= '<option value="'.$val['value'].'" '.$selected.'>'.$key.'</option>';
        }
        
    
        $form_html =<<<EOF
            <style type="text/css">
                .usps_opts th, .usps_opts td {vertical-align: middle}
                span.field_info {font-size: 10px;}
                h4.service_name {background-color: #ddd; padding: 10px; padding-right:0; font-size:14px; margin-top:0}
                td.top {vertical-align:top}
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    var eShopShippingModuleAdmin = {
                        max_weights : {
                            FIRST_CLASS: {
                                        LETTER   : '3.5 oz.', 
                                        POSTCARD : '3.5 oz.',
                                        default  : '13 oz.'
                                        },
                            PRIORITY_COMMERCIAL : {
                                            REGIONALRATEBOXA : '15 lbs.',
                                            REGIONALRATEBOXB : '20 lbs.',
                                            REGIONALRATEBOXC : '25 lbs.',
                                            default : '70 lbs.'
                                            },
                            PRIORITY_HFP_COMMERCIAL : {
                                            REGIONALRATEBOXA : '15 lbs.',
                                            REGIONALRATEBOXB : '20 lbs.',
                                            REGIONALRATEBOXC : '25 lbs.',
                                            default : '70 lbs.'
                                            }
                        }
                    };
                
                    eShopShippingModuleAdmin.show_hide_services = function(obj){
                        $(obj).children().each(function(){
                            var svc_id = $(this).val().replace(/ /g,''),
                                chkd   = $(this).attr('selected');
                                
                            if (chkd){
                                $("div#"+svc_id).show();
                            }
                            else {
                                $("div#"+svc_id).hide();
                            }
                        });
                    };
                    
                    // Bind changes to event list
                    $("#allowed_services, #intl_allowed_services").change(function(){
                        eShopShippingModuleAdmin.show_hide_services($(this));
                    });
                    
                    
                    $("#FIRST_CLASS_mail_type").change(function(){
                        var new_weight = typeof eShopShippingModuleAdmin.max_weights.FIRST_CLASS[$(this).val()] === 'undefined' ?
                                                eShopShippingModuleAdmin.max_weights.FIRST_CLASS.default : 
                                                eShopShippingModuleAdmin.max_weights.FIRST_CLASS[$(this).val()];
                        $("#FIRST_CLASS_mw").html(new_weight);
                    });
                    
                    
                    $.each(['PRIORITY_COMMERCIAL','PRIORITY_HFP_COMMERCIAL'], function(key,val){ 
                        
                            $("#"+val+'_container').change(function(){
                            var new_weight = typeof eShopShippingModuleAdmin.max_weights[val][$(this).val()] === 'undefined' ?
                                                    eShopShippingModuleAdmin.max_weights[val].default : 
                                                    eShopShippingModuleAdmin.max_weights[val][$(this).val()];
                            $("#"+val+"_mw").html(new_weight);
                        });
                    });
                    
                    
                });
            </script>
            <table class="usps_opts">
                <tr>
                    <th width="150">$uname:</th>
                    <td>
                        <input type="text" name="{$po}[$this->my_options_name][userid]" value="{$opts[userid]}" />
                    </td>
                    <td>
                        <span class="field_info">$uname_info</span>
                    </td>
                </tr>
                <tr>
                    <th>$send_value</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][send_value]">
                            $send_value_options
                        </select>
                    </td>
                    <td>
                        <span class="field_info">$send_value_info</span>
                    </td>
                </tr>
                <tr>
                    <th>$allowed:</th>
                    <td>
                        $select
                    </td>
                    <td><span class="field_info">$services_text</span></td>
                </tr>
                
                <tr>
                    <th>International Mail Types:</th>
                    <td>
                        <select id="intl_allowed_services" name="{$po}[$this->my_options_name][intl_mail_types][]" multiple="multiple" size="6">
                            $intl_mt_options
                        </select>
                    </td>
                </tr>
                
        </table>
EOF;
        
        foreach ($available as $svc)
        {
            $hidden = (isset($opts['allowed_services']) && in_array($svc,$opts['allowed_services'])) ? '' : 'style="display:none"';
            
            $svc_id     = str_replace(' ','',$svc);
            $form_html .= "<div $hidden id=\"$svc_id\">";
            $form_html .= '<hr />';
            $form_html .= "<div><h4 class=\"service_name\">Options for Domestic Service: \"$svc\"</h4>" . $this->_get_svc_html($svc) . '</div>';
            $form_html .= "</div>";
        }
        
        foreach ($allowed_intl_mt_array as $key => $val)
        {
            $hidden = (isset($opts['intl_mail_types']) && in_array($val['value'],$opts['intl_mail_types'])) ? '' : 'style="display:none"';
                
            $svc_id     = str_replace(' ','',$val['value']);
            $form_html .= "<div $hidden id=\"$svc_id\">";
            $form_html .= '<hr />';
            $form_html .= "<div><h4 class=\"service_name\">Options for Int'l Mail Type: \"$key\"</h4>" . 
                          $this->html_helper->_get_html_for_intl_mailtype($po,$val,$hidden) . '</div>';
            $form_html .= "</div>";
        }
        
        return $form_html;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  _get_svc_html()
     * @desc    Special fields for each service
     * @param   string $service
     * @param   bool $want_json
     */
    protected function _get_svc_html($svc,$want_json=false)
    {
        $po = parent::get_options_name();
        switch ($svc) 
        {
            case 'ALL':
            case 'ONLINE':
                $html = $this->html_helper->_get_html_for_svc_all($po,$svc);
                break; // end of "ALL"
            case 'FIRST CLASS':
            case 'FIRST CLASS COMMERCIAL':
            case 'FIRST CLASS HFP COMMERCIAL':
                $html = $this->html_helper->_get_html_for_svc_first_class($po,$svc);
                break;
            case 'EXPRESS':
            case 'EXPRESS COMMERCIAL':
            case 'EXPRESS HFP':
            case 'EXPRESS HFP COMMERCIAL':
            case 'EXPRESS SH':
            case 'EXPRESS SH COMMERCIAL':
                $html = $this->html_helper->_get_html_for_svc_express($po,$svc);
                break;
            case 'LIBRARY':
            case 'MEDIA':
            case 'PARCEL':
                $html = $this->html_helper->_get_html_for_svc_others($po,$svc);
                break;
            case 'PRIORITY':
            case 'PRIORITY COMMERCIAL':
            case 'PRIORITY HFP COMMERCIAL':
                $html =  $this->html_helper->_get_html_for_svc_priority($po,$svc);
                break;
            default:
                break;
        }
        
        return $want_json ? json_encode($html) : $html;
    }
        
    
    /**
     * @package USC_eShop_USPS
     * @method  get_rates()
     * @desc    Wrapper around actual get rates methods.
     * @param   $fields
     */
    function get_rates($input)
    {
        $fields = $this->_massage_params($input);
    
        if ($fields['success'] === false)
        {
            return $fields;
        }
    
        // Try to use the CURL method if available
        if (function_exists('curl_init'))
        {
        	$out = $this->_get_rates_curl($fields['data']);
        }
        elseif(ini_get('allow_url_fopen'))
        {
        	$out = $this->_get_rates_sock($fields['data']);
        }
        else
        {
        	$out['success'] = false;
        	$out['msgs'][] = __('Cannot communicate with Vendor! cURL or allow_url_fopen=1 is required!',$this->domain);
        }
        
        return $out;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  _get_rates_curl()
     * @desc    Sends a request to USPS using CURL
     * @param   array $input
     */
    function _get_rates_curl($input)
    {
    	$xml_arr = $this->_make_xml_request($input);
    	 
    	if ($xml_arr['success'] === false)
    	{
    		return $xml_arr;
    	} 
    	
    	$url = $this->get_mode() === 'testing' ? $this->test_url : $this->live_url;
    	$xml = urlencode($xml_arr['data']);

    	$request = $url . $this->api . '&XML=' . $xml;
    	 
    	 
    	$curl = curl_init($request); // Create REST Request
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// 		curl_setopt($curl, CURLOPT_VERBOSE, false);

    	$response = curl_exec($curl); // Execute REST Request

    	curl_close($curl);
    	 
    	return $this->_parse_xml_response($response);
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  _get_rates_sock()
     * @desc    Sends a request to USPS using file_get_contents
     * @param   array $input
     */
    function _get_rates_sock($input)
    {
    	$xml_arr = $this->_make_xml_request($input);
    	
    	if ($xml_arr['success'] === false)
    	{
    		return $xml_arr;
    	}
    	
    	$xml = urlencode($xml_arr['data']);
    	
    	$url = $this->get_mode() === 'testing' ? $this->test_url : $this->live_url;
    	
    	$request = $url . $this->api . '&XML=' . $xml;
    	
    	$response = file_get_contents($request);
    	
    	return $this->_parse_xml_response($response);
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  _make_xml_request()
     * @desc    Makes the necessary XML to send to Canada Post
     * @param   array $input
     * @return  assoc_array (success => bool, data => xml_string) 
     */
    private function _make_xml_request($input)
    {
    	global $blog_id;
    	
        $opts      = $this->get_options();
        $mailed_by = $opts['userid'];
        $from_zip  = substr($opts['from_zip'],0,5);
        $to_zip    = substr($input['zip'],0,5); // first 5 digits only
        $out       = array('success' => true);
        
        // Get total weight from cart session, as jQuery was not always passing the right value
        $total_weight = $_SESSION['eshop_totalweight'.$blog_id]['totalweight'];
        $conv         = $this->convert_to_ounces($total_weight);
        
        if ($conv['success'] === false) return $conv;

        $weight = sprintf('%.1f',$conv['data']);
    
        switch ($input['country'])
        {
            case 'US':
                $this->api   = 'RateV4';
                $xml         = new SimpleXMLElement("<{$this->api}Request USERID=\"$mailed_by\"><Revision>2</Revision></{$this->api}Request>");
                foreach ($opts['allowed_services'] as $svc)
                {
                    $xml = $this->xml_helper->_make_domestic_package_xml($xml, $svc, array('from_zip'    => $from_zip, 
                                                                                           'to_zip'      => $to_zip, 
                                                                                           'weight'      => $weight));
                }
                break;
            default:
                $this->api   = 'IntlRateV2';
                $xml         = new SimpleXMLElement("<{$this->api}Request USERID=\"$mailed_by\"><Revision>2</Revision></{$this->api}Request>");
                $country     = $this->get_country_name($input['country']);
                if ($opts['intl_mail_types'])
                {
	                foreach ($opts['intl_mail_types'] as $svc)
	                {
	                    $xml = $this->xml_helper->_make_intl_package_xml($xml, $svc, array('from_zip'    => $from_zip,
	                                                                                       'weight'      => $weight,
	                                                                                       'country'     => $country[0]));
	                }
                }
                else
                {
                	$out['success'] = false;
                	$out['msgs'][] = __('No international settings selected.', $this->my_domain);
                }
        }
        
        $out['data'] = $xml->asXML();
        
        if ($opts['debug_mode'])
        {
        	$dom = new DOMDocument('1.0');
        	$dom->preserveWhiteSpace = false;
        	$dom->formatOutput       = true;
        	$dom->loadXML(preg_replace('|USERID="[^"]+"|', 'USERID="***REMOVED***"', $out['data']));
        	$dom->save($this->debug_request_file);
        }
        
        return $out;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  convert_to_ounces()
     * @desc    Converts given input into ounces according to eshop_weight_unit
     * @param   float $input
     * @return  assoc_array (status, data)
     */
    function convert_to_ounces($input)
    {
        $eshop_opts = $this->get_eshop_options();
        $units_from = str_replace('.','',strtolower($eshop_opts['weight_unit']));
        $out        = array('success' => true);
    
        if (! is_numeric($input))
        {
            $out['success'] = false;
            $out['msgs'][] = __('Invalid value to convert into Ounces!', $this->my_domain);
                
            return $out;
        }
    
        switch ($units_from) {
            case 'k':
            case 'kg':
            case 'kilo':
            case 'kilos':
                $out['data'] = $input * 35.2739619;
                break;
            case 'l':
            case 'lb':
            case 'lbs':
            case 'pound':
            case 'pounds':
                $out['data'] = $input * 16;
                break;
            case 'g':
            case 'gr':
            case 'gram':
            case 'grams':
                $out['data'] = $input * 0.0352739619; 
                break;
            default:
                $out['data'] = $input;
        }
        
        return $out;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  get_country_name()
     * @desc    Fetches country_name from eshop tables
     * @param   string $country_cd
     * @return  string
     */
    function get_country_name($country_cd)
    {
        global $wpdb;
        
        $table = $wpdb->prefix . 'eshop_countries';
        
        $sql     = $wpdb->prepare("select country from $table where code = %s", $country_cd);
        $country = $wpdb->get_col($sql);
        
        return $country;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  _parse_xml_response()
     * @desc    Parses the XML returned by USPS
     * @param   string $response XML
     * @return  array parsed data
     */
    private function _parse_xml_response($response)
    {
        global $blog_id;
    
        libxml_use_internal_errors(true);
        $out = array(success => false);
        $xml = simplexml_load_string('<root>' . preg_replace('/<\?xml version="1.0".*?\?>/', '', $response) . '</root>');
        $api_str = $this->api . 'Response';
                
//         file_put_contents('/Sites/wordpress_plugins/myplugins/eshop-shipping-extension-usps/out.xml', $response);
        
        if (!$xml)
        {
            $out['success'] = false;
            $out['msgs'][] = __('Failed loading XML', $this->domain)  . "\n";
                
            foreach(libxml_get_errors() as $error) {
                $out['msgs'][] = $error->message;
            }
                
            return $out;
        }
        
        
        if ($this->debug_mode())
        {
        	$dom = new DOMDocument('1.0');
        	$dom->preserveWhiteSpace = false;
        	$dom->formatOutput       = true;
        	$dom->loadXML($xml->asXML());
        	$dom->save($this->debug_response_file);
        }
        
        
        if ($xml->Error)
        {
            $out['msgs'][] = (string)$xml->Error->Description;
            return $out;
        }

        $p_count = count($xml->{$api_str}->Package);
        for ($i = 0; $i< $p_count; $i++)
        {
        	$p = $xml->{$api_str}->Package[$i];
        	if ($p->Error)
        	{
        		$out['msgs'][] = (string)$p->Error->Description;
        		unset($xml->{$api_str}->Package[$i]);
        	}
        }
        
        if (0 === count($xml->{$api_str}->Package))
        {
        	return $out;
        }
        
        // No errors, so lets get the services
        if ($this->api === 'RateV4')
        {
            return $this->_extract_domestic_services($xml);
        }
        else
        {
            return $this->_extract_intl_services($xml);
        }
        
    }

    
    /**
     * @package USC_eShop_USPS
     * @method  _extract_domestic_services()
     * @desc    Parses the XML returned by USPS for domestic requests
     * @param   Object SimpleXML $xml
     * @return  array parsed data
     */
    function _extract_domestic_services($xml)
    {
        global $blog_id;
        
        $api_str       = $this->api . 'Response';
        $service_info  = array();
        $opts          = $this->get_options();
        $out           = array('success' => false);
        
        $xml = apply_filters('ese_usps_service_filter', $xml);
        
        foreach ($xml->{$api_str}->Package as $package)
        {
            foreach ($package->Postage as $p)
            {
                if ($p)
                {
                    $service_name = html_entity_decode((string)$p->MailService, ENT_COMPAT|ENT_XML1, 'UTF-8');
                    $service_name = strip_tags($service_name);
                    // replace entities, as they get messed up when used as array keys
                	$service_name = preg_replace('/&[^;]+;/',' ',$service_name);
                	$service_name = 'USPS - ' . $service_name;
        
                    $price = (string)$p->Rate;
                    if ($price == '0.00' && $p->CommercialRate)
                    {
                        $price = (string)$p->CommercialRate;
                    }
                    
                    $service_info[$service_name]['price'] = $this->convert_currency('USD',$price);
                    
                    if ($p->SpecialServices)
                    {
                        foreach ($p->SpecialServices->children() as $ss)
                        {
                            if (strtolower((string)$ss->Available) === 'false') continue; // string, not bool, on purpose
                            
                            if ((string)$ss->ServiceName == 'Collect on Delivery') continue;
                            
                            $ss_name = html_entity_decode((string)$ss->ServiceName, ENT_COMPAT|ENT_XML1, 'UTF-8');
                            $service_info[$service_name]['services'][$ss_name] = $this->convert_currency('USD',(string)$ss->Price);
                        }
                    }
                }
            }
        }
        
        // Save values in session to keep visitor from tampering
        // with the prices
        if (count($service_info))
        {
            $out['data'] = $service_info;
            $_SESSION['usc_3rd_party_shipping'.$blog_id] = (array)$_SESSION['usc_3rd_party_shipping'.$blog_id] + $service_info;
        }
        
        if (count($out['data']) == 0)
        {
            if (count($out['msgs']) == 0)
            {
                $out['msgs'][] = __('No shipping plans were found for your options!',$this->domain);
            }
        }
        else
        {
            $out['success'] = true;
        }
        
        return $out;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  _extract_domestic_services()
     * @desc    Parses the XML returned by USPS for domestic requests
     * @param   Object SimpleXML $xml
     * @return  array parsed data
     */
    function _extract_intl_services($xml)
    {
    	global $blog_id; 
    	
        $api_str       = $this->api . 'Response';
        $p             = $xml->{$api_str}->Package;
        $service_price = array();
        $service_info  = array();
        $opts          = $this->get_options();
        $intl_svcs     = $this->get_available_intl_services();
        $out           = array('success' => false);
        
        $xml = apply_filters('ese_usps_service_filter', $xml);
        
        $all_allowed_services = array();
        
        if (!$opts['intl_subservices'])
        {
        	$all_allowed_services["Any"]++;
        }
        else
        {
	        foreach ($opts['intl_mail_types'] as $svc)
	        {
	        	foreach ($intl_svcs as $i)
	        	{
	        		if ($i['value'] != $svc) continue;

	        		foreach ($i['subservices'] as $key => $val)
	        		{
	        			if (!count($opts['intl_subservices'][$svc]) || in_array($key,(array)$opts['intl_subservices'][$svc]))
	        			{
	        				$all_allowed_services[$key]++;
	        			}
	        		}
	        	}
	        }
        }
        
        if ($p)
        {
            foreach ($p->children() as $child)
            {
                if ($child->getName() !== 'Service') continue;
                
                $service_name = html_entity_decode((string)$child->SvcDescription, ENT_COMPAT|ENT_XML1, 'UTF-8');
                $service_name = strip_tags($service_name);
                $service_name = preg_replace('/&[^;]+;/',' ',$service_name);
                $service_id   = (string)$child->attributes()->ID;
                
                if (! array_key_exists('any', $all_allowed_services) && ! array_key_exists($service_id,$all_allowed_services)) 
                		continue;
                
                $service_name = "USPS - " . $service_name;
                
                $price = (string)$child->Postage;
                $service_info[$service_name]['price'] = $this->convert_currency('USD',$price);
                
                if ($child->SvcCommitments)
                {
                    $service_info[$service_name]['details']['expected-delivery'] = (string)$child->SvcCommitments;
                }
                
                if ($child->ParcelIndemnityCoverage)
                {
                    $service_info[$service_name]['details']['parcel-indemnity-coverage'] = (string)$child->ParcelIndemnityCoverage;
                }
                
                if ($child->ExtraServices)
                {
                    foreach ($child->ExtraServices->children() as $ss)
                    {
                        if (strtolower((string)$ss->Available) === 'false') continue; // string, not bool, on purpose
                        
                        $ss_name = html_entity_decode((string)$ss->ServiceName, ENT_COMPAT|ENT_XML1, 'UTF-8');
                        $service_info[$service_name]['services'][$ss_name] = $this->convert_currency('USD',(string)$ss->Price);
                    }
                }
            }
        }
        
        // Save values in session to keep visitor from tampering
        // with the prices
        if (count($service_info))
        {
            $out['data'] = $service_info;
            $_SESSION['usc_3rd_party_shipping'.$blog_id] = (array)$_SESSION['usc_3rd_party_shipping'.$blog_id] + $service_info;
        }
        
        if (count($out['data']) == 0)
        {
            if (count($out['msgs']) == 0)
            {
            	$out['msgs'][] = __('No shipping plans were found for your options!',$this->domain);
            }
        }
        else
        {
            $out['success'] = true;
        }

        return $out;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  _massage_params()
     * @desc    Validates and sanitizes input params
     * @param   $input
     * @return  array massaged data
     */
    private function _massage_params($input)
    {
        $this->do_recursive($input,'trim'); // Parent function
    
        $req_fields = array('country', 'weight', 'zip', 'amount');
    
        $opts       = $this->get_options();
        $eshop_opts = $this->get_eshop_options();
    
        $input['from_zip']    = preg_replace('/\s+/','',$opts['from_zip']); // remove inner spaces from Canadian postal codes
        $input['weight_unit'] = $eshop_opts['weight_unit'];
    
        $out['success'] = true;
    
        foreach ($req_fields as $key)
        {
            if (! $input[$key])
            {
                $out['success'] = false;
                $out['msgs'][] = sprintf(__('Required field \'%s\' is missing!', $this->my_domain),$key);
            }
        }
    
        if ($out['success'] === false)
        {
            return $out;
        }
    
        $out['data'] = $input;
    
        return $out;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  get_js_msgs()
     * @desc    Localized JavaScript messages
     * @return  array
     */
    function get_js_msgs()
    {
        $msgs = array('missing_zip'               => __('Zip code is required for US postage', $this->my_domain),
                      'invalid_weight'            => __('Invalid weight', $this->my_domain),
                      'expected-delivery'         => __('Expected Delivery', $this->my_domain),
                      'guaranteed-delivery'       => __('Guaranteed Delivery', $this->my_domain),
                      'true'                      => __('Yes', $this->my_domain),
                      'false'                      => __('No', $this->my_domain),
                      'parcel-indemnity-coverage' => __('Indemnity Coverage', $this->my_domain),
                      'Extra Services'            => __('Extra Services', $this->my_domain),
                      'Service Details'           => __('Service Details',$this->my_domain),
                );
    
        return $msgs;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  get_available_domestic_services()
     * @desc    Returns array of available USPS Domestic services
     * @return  array
     */
    function get_available_domestic_services()
    {
        return array(
                     'ALL',
                     'EXPRESS',
                     'EXPRESS COMMERCIAL',
                     'EXPRESS HFP',
                     'EXPRESS HFP COMMERCIAL',
                     'EXPRESS SH',
                     'EXPRESS SH COMMERCIAL',
                     'FIRST CLASS',
                     'FIRST CLASS COMMERCIAL',
                     'FIRST CLASS HFP COMMERCIAL',
                     'LIBRARY',
                     'MEDIA',
                     'ONLINE',
                     'PARCEL',
                     'PRIORITY',
                     'PRIORITY COMMERCIAL',
                     'PRIORITY HFP COMMERCIAL',
                );
    }
    

    /**
     * @package USC_eShop_USPS
     * @method  _get_all_service_names()
     * @desc    Returns array containing all service names
     * @return  array
     */
    public function _get_all_service_names($list=array())
    {
    	$domestic = array(
    			'Express Mail',
    			'Express Mail Hold For Pickup',
    			'Express Mail Sunday/Holiday Delivery',
    			'Express Mail Flat Rate Boxes',
    			'Express Mail Flat Rate Envelope',
    			'Express Mail Legal Flat Rate Envelope Hold For Pickup',
    			'Express Mail Sunday/Holiday Delivery Flat Rate Envelope',
    			'Express Mail Legal Flat Rate Envelope',
    			'Express Mail Flat Rate Boxes Hold For Pickup',
    			'Express Mail Flat Rate Envelope Hold For Pickup',
    			'Express Mail Padded Flat Rate Envelope',
    			'Express Mail Padded Flat Rate Envelope Hold For Pickup',
    			'Express Mail Sunday/Holiday Delivery Padded Flat Rate Envelope',
    			'Express Mail Sunday/Holiday Delivery Flat Rate Boxes',
    			'Express Mail Sunday/Holiday Delivery Legal Flat Rate Envelope',
    			'First-Class Mail',
    			'First-Class Mail Letter',
    			'First-Class Mail Parcel',
    			'First-Class Package Service',
    			'First-Class Mail Hold For Pickup',
    			'First-Class Package Service Hold For Pickup',
    			'Library Mail',
    			'Media Mail',
    			'Priority Mail',
    			'Priority Mail Hold For Pickup',
    			'Priority Mail Flat Rate Envelope',
    			'Priority Mail Flat Rate Envelope Hold For Pickup',
    			'Priority Mail Legal Flat Rate Envelope',
    			'Priority Mail Legal Flat Rate Envelope Hold For Pickup',
    			'Priority Mail Padded Flat Rate Envelope',
    			'Priority Mail Padded Flat Rate Envelope Hold For Pickup',
    			'Priority Mail Gift Card Flat Rate Envelope',
    			'Priority Mail Gift Card Flat Rate Envelope Hold For Pickup',
    			'Priority Mail Small Flat Rate Envelope',
    			'Priority Mail Small Flat Rate Envelope Hold For Pickup',
    			'Priority Mail Window Flat Rate Envelope',
    			'Priority Mail Window Flat Rate Envelope Hold For Pickup',
    			'Priority Mail Large Flat Rate Box',
    			'Priority Mail Large Flat Rate Box Hold For Pickup',
    			'Priority Mail Medium Flat Rate Box',
    			'Priority Mail Medium Flat Rate Box Hold For Pickup',
    			'Priority Mail Small Flat Rate Box',
    			'Priority Mail Small Flat Rate Box Hold For Pickup',
    			'Priority Mail Regional Rate Box A',
    			'Priority Mail Regional Rate Box A Hold For Pickup',
    			'Priority Mail Regional Rate Box B',
    			'Priority Mail Regional Rate Box B Hold For Pickup',
    			'Priority Mail Regional Rate Box C',
    			'Priority Mail Regional Rate Box C Hold For Pickup',
    			'Standard Post'
    	);
    	
    	$intl = $this->get_available_intl_services();
    	$intl = $intl['All']['subservices'];
    	unset($intl[0]);
    	
    	$all = array_merge($domestic, $intl);
    	sort($all);
    	
    	$list['USPS'] = $all;
    	
    	return $list;
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  get_available_intl_sub_services()
     * @desc    Returns array of available USPS International SUB services
     * @return  array
     */
    function get_available_intl_services()
    {
    	return array(
    		'All' => array(
    			'value' => 'All',
    			'subservices' => array(
    					'any' => "Any",
    					1 => "Priority Mail Express International",
    					2 => "Priority Mail International",
    					4 => "Global Express Guaranteed (GXG)",
    					5 => "Global Express Guaranteed - Document",
    					6 => "Global Express Guaranteed - Non-Document Rectangular",
    					7 => "Global Express Guaranteed - Non-Document Non-Rectangular",
    					8 => "Priority Mail International - Flat Rate Envelope**",
    					9 => "Priority Mail International - Medium Flat Rate Box",
    					10 => "Priority Mail Express International - Flat Rate Envelope",
    					11 => "Priority Mail International - Large Flat Rate Box",
    					12 => "USPS GXG - Envelopes**",
    					13 => "First-Class Mail International - Letter",
    					14 => "First-Class Mail International - Large Envelope",
    					15 => "First-Class Package International Service",
    					16 => "Priority Mail International - Small Flat Rate Box**",
    					17 => "Priority Mail Express International - Legal Flat Rate Envelope",
    					18 => "Priority Mail International - Gift Card Flat Rate Envelope**",
    					19 => "Priority Mail International - Window Flat Rate Envelope**",
    					20 => "Priority Mail International - Small Flat Rate Envelope**",
    					21 => "First-Class Mail - International Postcard",
    					22 => "Priority Mail International - Legal Flat Rate Envelope**",
    					23 => "Priority Mail International - Padded Flat Rate Envelope**",
    					24 => "Priority Mail International - DVD Flat Rate priced box**",
    					25 => "Priority Mail International - Large Video Flat Rate priced box**",
    					26 => "Priority Mail Express International - Flat Rate Boxes",
    					27 => "Priority Mail Express International - Padded Flat Rate Envelope"
    		)),
    		'Package' => array(
				'value' => 'Package',
    			'subservices' => array(
    					'any' => "Any",
    					1 => "Priority Mail Express International",
		    			2 => "Priority Mail International",
		    			4 => "Global Express Guaranteed (GXG)",
		    			6 => "Global Express Guaranteed - Non-Document Rectangular",
		    			7 => "Global Express Guaranteed - Non-Document Non-Rectangular",
		    			15 => "First-Class Package International Service",
			)),
    		'Envelope' => array(
				'value' => 'Envelope',
    			'subservices' => array(
    					'any' => "Any",
    					1 => "Priority Mail Express International",
    					2 => "Priority Mail International",
		    			5 => "Global Express Guaranteed - Document",
		    			8 => "Priority Mail International - Flat Rate Envelope**",
		    			12 => "USPS GXG - Envelopes**",
		    			13 => "First-Class Mail International - Letter",
		    			15 => "First-Class Package International Service",
    					)),
    		'Large Envelope' => array(
    			'value' => 'LargeEnvelope',
    			'subservices' => array(
						'any' => "Any",
		    			1 => "Priority Mail Express International",
		    			2 => "Priority Mail International",
		    			14 => "First-Class Mail International - Large Envelope",
			)),
    		'Flat Rate' => array(
    			'value' => 'FlatRate',
				'subservices' => array(
    					'any' => "Any",
    					1 => "Priority Mail Express International",
    					2 => "Priority Mail International",
    					8 => "Priority Mail International - Flat Rate Envelope**",
    					9 => "Priority Mail International - Medium Flat Rate Box",
    					10 => "Priority Mail Express International - Flat Rate Envelope",
    					11 => "Priority Mail International - Large Flat Rate Box",
    					16 => "Priority Mail International - Small Flat Rate Box**",
    					17 => "Priority Mail Express International - Legal Flat Rate Envelope",
    					18 => "Priority Mail International - Gift Card Flat Rate Envelope**",
    					19 => "Priority Mail International - Window Flat Rate Envelope**",
    					20 => "Priority Mail International - Small Flat Rate Envelope**",
    					22 => "Priority Mail International - Legal Flat Rate Envelope**",
    					23 => "Priority Mail International - Padded Flat Rate Envelope**",
    					24 => "Priority Mail International - DVD Flat Rate priced box**",
    					25 => "Priority Mail International - Large Video Flat Rate priced box**",
    					26 => "Priority Mail Express International - Flat Rate Boxes",
    					27 => "Priority Mail Express International - Padded Flat Rate Envelope",
			)),
			'Postcards or Aerogrammes' => array(
    			'value' => 'Postcards or aerogrammes',
    			'subservices' => array(
    					'any' => "Any",
    					1 => "Priority Mail Express International",
    					2 => "Priority Mail International",
    					21 => "First-Class Mail - International Postcard",
    					))
    	);
    }
    
    
    /**
     * @package USC_eShop_USPS
     * @method  get_allowed_services()
     * @desc    Returns hash of services to be displayed
     * @return  assoc_array keys => 1
     */
    function get_allowed_services()
    {
        $opts = $this->get_options();
        return $this->make_hash_from_values($opts['allowed_services']);
    }
    
}



/**
 * @package USC_USPS_html_helper
 * @desc    Helper methods to generate HTML for Admin service form
 */
class USC_USPS_html_helper extends USC_eShop_USPS
{
    private $opts;
    
    function __construct()
    {
        $this->opts = $this->get_options();
    }
    
    /**
     * @package USC_USPS_html_helper
     * @method  _get_html_for_svc_all()
     * @param   string $po - main option name from eshop-shipping-extension.php
     * @param   string $svc - Service Name
     * @desc    Returns HTML for service 'ALL'
     * @return  string
     */
    protected function _get_html_for_svc_all($po,$svc)
    {
        // Max weight ID (js will change its contents)
        $svc_id = str_replace(' ','_',$svc) . '_id';
        $mw_id  =  $svc_id . '_mw';
        
        $size_array = array('Regular (up to 12")' => 'REGULAR',
                            'Large (above 12")'   => 'LARGE');
        
        foreach ($size_array as $key => $val)
        {
            $size_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['size'],false).'>'.$key.'</option>';
        }
        
        
        $container_array = array('Variable'        => 'VARIABLE',
                                 'Rectangular'     => 'RECTANGULAR',
                                 'Non-Rectangular' => 'NONRECTANGULAR');
        foreach ($container_array as $key => $val)
        {
            $container_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['container'],false).'>'.$key.'</option>';
        }
        
        
        $machinable_array = array('Yes' => 'TRUE','No' => 'FALSE');
        foreach ($machinable_array as $key => $val)
        {
            $machinable_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['machinable'],false).'>'.$key.'</option>';
        }
        
        
        $sort_by_array = array('Letter'         => 'LETTER',
                               'Package'        => 'PACKAGE',
                               'Container'      => 'CONTAINER',
                               'Flat Rate'      => 'FLATRATE',
                               'Large Envelope' => 'LARGEENVELOPE');
        
        foreach ($sort_by_array as $key => $val)
        {
            $sort_by_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['sort_by'],false).'>'.$key.'</option>';
        }
        
        
        return <<<EOF
            <div style="float:right">Maximum Weight: <span id="$mw_id">70 lbs.</span></div>
            <table class="usps_opts">
                <tr>
                    <th width="150">Package Size</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][size]">
                            $size_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_size_info" class="field_info"></span>
                    </td>
                </tr>
                <tr>
                    <th>Container</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][container]">
                            $container_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_container_info" class="field_info"></span>
                    </td>
                </tr>
                <tr>
                    <th>Sort By</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][sort_by]">
                            $sort_by_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_sort_by_info" class="field_info"></span>
                    </td>
                </tr>
                <tr>
                    <th>Machinable</th>
                    <td colspan="2">
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][machinable]">
                            $machinable_options
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Dimensions</th>
                    <td>
                        <div><div style="float:left"><em>Width:</em></div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][width]" 
                        value="{$this->opts[service_specs][$svc][width]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                        
                        <div style="clear:both"><div style="float:left; clear:left"><em>Length:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][length]" 
                        value="{$this->opts[service_specs][$svc][length]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                        
                        <div style="clear:both"><div style="float:left; clear:left"><em>Height:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][height]" 
                        value="{$this->opts[service_specs][$svc][height]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                        
                        <div style="clear:both"><div style="float:left; clear:left"><em>Girth:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][girth]" 
                        value="{$this->opts[service_specs][$svc][girth]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_dimensions_info" class="field_info">
                            <ul>
                                <li>- All dimensions in inches.</li>
                                <li>- Height/Width/Length required if Size is "Large"</li>
                                <li>- Girth only required if Container is "Non-Rectangular"</li>
                            </ul> 
                        </span>
                    </td>
                </tr>
            </table>    
EOF;
        
    }
    
    
    /**
     * @package USC_USPS_html_helper
     * @method  _get_html_for_svc_first_class()
     * @desc    Returns HTML for services 'FIRST CLASS *'
     * @return  string
     */
    protected function _get_html_for_svc_first_class($po,$svc)
    {
        // Max weight ID (js will change its contents)
        $svc_id    = str_replace(' ','_',$svc);
        $mw_id     = $svc_id . '_mw';
        $mail_type = array();
        switch ($svc) 
        {
            case 'FIRST CLASS':
                $mail_type       = array('Parcel' => 'PARCEL','Letter' => 'LETTER','Flat' => 'FLAT','Postcard' => 'POSTCARD');
                $size_info       = 'Ignored for Mail Types "Letter" and "Flat"';
                $container_info  = 'Ignored for Mail Types "Letter", "Flat", and "Postcard"';
                $dimensions_info = '<ul>
                                        <li>- All dimensions in inches.</li>
                                        <li>- Height/Width/Length required if Size is "Large" and Mail Type is "Parcel"</li>
                                        <li>- Girth only required if Container is "Non-Rectangular"</li>
                                    </ul>';
                switch ($this->opts['service_specs'][$svc]['first_class_mail_type'])
                {
                    case 'LETTER':
                    case 'POSTCARD':
                        $max_weight = '3.5 oz.';
                        break;
                    default:
                        $max_weight = '13 oz.';
                        break;
                }
                break;
            case 'FIRST CLASS COMMERCIAL':
            case 'FIRST CLASS HFP COMMERCIAL':
                $mail_type       = array('Package Service' => 'PACKAGE SERVICE');
                $max_weight      = '13 oz.';
                $dimensions_info = '<ul>
                                        <li>- All dimensions in inches.</li>
                                        <li>- Height/Width/Length required if Size is "Large"</li>
                                        <li>- Girth only required if Container is not "Rectangular"</li>
                                    </ul>';
                break;
            default:
                break;
                
        }

        
        foreach ($mail_type as $key => $val)
        {
            $mail_type_options .= '<option value="'.$val.'" '. selected($val,$this->opts['service_specs'][$svc]['first_class_mail_type'],false) .'>'.$key.'</option>';
        }
        
        
        $size_array = array('Regular (up to 12")' => 'REGULAR',
                            'Large (above 12")'   => 'LARGE');
        
        foreach ($size_array as $key => $val)
        {
            $size_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['size'],false).'>'.$key.'</option>';
        }
        
        
        $container_array = array('Variable'        => 'VARIABLE',
                                 'Rectangular'     => 'RECTANGULAR',
                                 'Non-Rectangular' => 'NONRECTANGULAR');
        foreach ($container_array as $key => $val)
        {
            $container_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['container'],false).'>'.$key.'</option>';
        }
        
        
        $machinable_array = array('Yes' => 'TRUE', 'No' => 'FALSE');
        foreach ($machinable_array as $key => $val)
        {
            $machinable_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['machinable'],false).'>'.$key.'</option>';
        }
        
        $machinable_info = 'Required only for Mail Types "Letter", and "Flat".';
        
        
        return <<<EOF
            <div style="float:right">Maximum Weight: <span id="$mw_id">$max_weight</span></div>
            <table class="usps_opts">
                <tr>
                    <th width="150">Mail Type</th>
                    <td>
                        <select id="{$svc_id}_mail_type" name="{$po}[$this->my_options_name][service_specs][$svc][first_class_mail_type]">
                            $mail_type_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_mail_type_info" class="field_info">$mailtype_info</span>
                    </td>
                </tr>
                
                <tr>
                    <th>Package Size</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][size]">
                            $size_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_size_info" class="field_info">$size_info</span>
                    </td>
                </tr>
                <tr>
                    <th>Container</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][container]">
                            $container_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_container_info" class="field_info">$container_info</span>
                    </td>
                </tr>
                <tr>
                    <th>Machinable</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][machinable]">
                            $machinable_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_machinable_info" class="field_info">$machinable_info</span>
                    </td>
                </tr>
                <tr>
                    <th>Dimensions</th>
                    <td>
                        <div><div style="float:left"><em>Width:</em></div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][width]"
                        value="{$this->opts[service_specs][$svc][width]}" class="v_is_float" size="10" maxlength="10"/></div></div>
        
                        <div style="clear:both"><div style="float:left; clear:left"><em>Length:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][length]"
                        value="{$this->opts[service_specs][$svc][length]}" class="v_is_float" size="10" maxlength="10"/></div></div>
        
                        <div style="clear:both"><div style="float:left; clear:left"><em>Height:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][height]"
                        value="{$this->opts[service_specs][$svc][height]}" class="v_is_float" size="10" maxlength="10"/></div></div>
        
                        <div style="clear:both"><div style="float:left; clear:left"><em>Girth:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][girth]"
                        value="{$this->opts[service_specs][$svc][girth]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_dimensions_info" class="field_info">
                            $dimensions_info
                        </span>
                    </td>
                </tr>
            </table>
EOF;
    }
    
    
    /** 
    * @package USC_USPS_html_helper
    * @method  _get_html_for_svc_express()
    * @desc    Returns HTML for services 'EXPRESS *'
    * @return  string
    */
    protected function _get_html_for_svc_express($po,$svc)
    {
        // Max weight ID (js will change its contents)
        $svc_id    = str_replace(' ','_',$svc);
        $mw_id     = $svc_id . '_mw';
        $dimensions_info = '<ul>
                                <li>- All dimensions in inches.</li>
                                <li>- Height/Width/Length required if Size is "Large"</li>
                                <li>- Girth only required if Container is not "Rectangular"</li>
                            </ul>';

        $size_array = array('Regular (up to 12")' => 'REGULAR',
                            'Large (above 12")'   => 'LARGE');
        
        foreach ($size_array as $key => $val)
        {
            $size_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['size'],false).'>'.$key.'</option>';
        }
        
        
        $container_array = array('Flat Rate Envelope'   => 'FLAT RATE ENVELOPE',
                                 'Legal Flat Rate Env.' => 'LEGAL FLAT RATE ENVELOPE',
                                 'Variable'             => 'VARIABLE',
                                 'Rectangular'          => 'RECTANGULAR',
                                 'Non-Rectangular'      => 'NONRECTANGULAR');
        
        foreach ($container_array as $key => $val)
        {
            $container_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['container'],false).'>'.$key.'</option>';
        }
        
        
        if ($svc !== 'EXPRESS HFP COMMERCIAL')
        {
            $box_option = '<option value="FLAT RATE BOX" '.
                    selected('FLAT RATE BOX',$this->opts['service_specs'][$svc]['container'],false) .
                    '>Flat Rate Box</option>';
        }
        
        
        return <<<EOF
            <div style="float:right">Maximum Weight: <span id="$mw_id">70 lbs.</span></div>
            <table class="usps_opts">
                <tr>
                    <th width="150">Package Size</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][size]">
                            $size_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_size_info" class="field_info"></span>
                    </td>
                </tr>
                <tr>
                    <th>Container</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][container]">
                            $box_option
                            $container_options                    
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_container_info" class="field_info"></span>
                    </td>
                </tr>
                <tr>
                    <th>Dimensions</th>
                    <td>
                        <div><div style="float:left"><em>Width:</em></div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][width]"
                        value="{$this->opts[service_specs][$svc][width]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Length:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][length]"
                        value="{$this->opts[service_specs][$svc][length]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Height:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][height]"
                        value="{$this->opts[service_specs][$svc][height]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Girth:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][girth]"
                        value="{$this->opts[service_specs][$svc][girth]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_dimensions_info" class="field_info">
                            $dimensions_info
                        </span>
                    </td>
                </tr>
            </table>
EOF;
    }
    
    
    /**
     * @package USC_USPS_html_helper
     * @method  _get_html_for_svc_others()
     * @desc    Returns HTML for services 'LIBRARY', 'MEDIA', and 'PARCEL'
     * @return  string
     */
    protected function _get_html_for_svc_others($po,$svc)
    {
        // Max weight ID (js will change its contents)
        $svc_id    = str_replace(' ','_',$svc);
        $mw_id     = $svc_id . '_mw';
        $container_info  = 'Always uses "Variable" if Size is "Regular", regardless of selection.';
        $dimensions_info = '<ul>
                                <li>- All dimensions in inches.</li>
                                <li>- Height/Width/Length required if Size is "Large"</li>
                                <li>- Girth only required if Container is not "Rectangular"</li>
                            </ul>';
        
        if ($svc === 'PARCEL')
        {
            $svc_id = str_replace(' ', '_',$svc);
            
            $machinable_array = array('Yes' => 'TRUE','No' => 'FALSE');
            foreach ($machinable_array as $key => $val)
            {
                $machinable_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['machinable'],false).'>'.$key.'</option>';
            }
            
            
            $machinable =<<<EOF
                <tr>
                    <th>Machinable</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][machinable]">
                            $machinable_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_machinable_info" class="field_info">$container_info</span>
                    </td>
                </tr>
EOF;
        }
    
        $size_array = array('Regular (up to 12")' => 'REGULAR', 
                            'Large (above 12")' => 'LARGE');
        
        foreach ($size_array as $key => $val)
        {
            $size_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['size'],false).'>'.$key.'</option>';
        }
        
        
        
        $container_array = array('Variable'        => 'VARIABLE',
                                 'Rectangular'     => 'RECTANGULAR',
                                 'Non-Rectangular' => 'NONRECTANGULAR');
        
        foreach ($container_array as $key => $val)
        {
            $container_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['container'],false).'>'.$key.'</option>';
        }
        
        return <<<EOF
            <div style="float:right">Maximum Weight: <span id="$mw_id">70 lbs.</span></div>
            <table class="usps_opts">
            	$machinable
                <tr>
                    <th width="150">Package Size</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][size]">
                            $size_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_size_info" class="field_info"></span>
                    </td>
                </tr>
                <tr>
                    <th>Container</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][container]">
                            $container_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_container_info" class="field_info">$container_info</span>
                    </td>
                </tr>
                <tr>
                    <th>Dimensions</th>
                    <td>
                        <div><div style="float:left"><em>Width:</em></div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][width]"
                        value="{$this->opts[service_specs][$svc][width]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Length:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][length]"
                        value="{$this->opts[service_specs][$svc][length]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Height:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][height]"
                        value="{$this->opts[service_specs][$svc][height]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Girth:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][girth]"
                        value="{$this->opts[service_specs][$svc][girth]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_dimensions_info" class="field_info">
                            $dimensions_info
                        </span>
                    </td>
                </tr>
            </table>
EOF;
    }
    
    
    /**
     * @package USC_USPS_html_helper
     * @method  _get_html_for_svc_priority()
     * @desc    Returns HTML for services 'PRIORITY *'
     * @return  string
     */
    protected function _get_html_for_svc_priority($po,$svc)
    {
        // Max weight ID (js will change its contents)
        $svc_id    = str_replace(' ','_',$svc);
        $mw_id     = $svc_id . '_mw';
        
        // Common containers
        $containers = array('Auto Flat Rate Box'       => 'AUTO FLAT RATE BOX',
        					'Lg Flat Rate Box'         => 'LG FLAT RATE BOX',
                            'Md Flat Rate Box'         => 'MD FLAT RATE BOX',
                            'Sm Flat Rate Box'         => 'SM FLAT RATE BOX',
                            'Regular Flat Rate Box'    => 'FLAT RATE BOX',
                            'Flat Rate Env.'           => 'FLAT RATE ENVELOPE',
                            'Legal Flat Rate Env.'     => 'LEGAL FLAT RATE ENVELOPE',
                            'Padded Flat Rate Env.'    => 'PADDED FLAT RATE ENVELOPE',
                            'Gift Card Flat Rate Env.' => 'GIFT CARD FLAT RATE ENVELOPE',
                            'Window Flat Rate Env.'    => 'WINDOW FLAT RATE ENVELOPE',
                            'Sm Flat Rate Env.'        => 'SMALL FLAT RATE ENVELOPE',
                            'Variable'                 => 'VARIABLE',
                            'Rectangular'              => 'RECTANGULAR',
                            'Non-Rectangular'          => 'NONRECTANGULAR');
        
        switch ($svc)
        {
            case 'PRIORITY COMMERCIAL':
            case 'PRIORITY HFP COMMERCIAL':
                $containers['Regional Rate Box A'] = 'REGIONALRATEBOXA';
                $containers['Regional Rate Box B'] = 'REGIONALRATEBOXB';
                $containers['Regional Rate Box C'] = 'REGIONALRATEBOXC';
                switch ($this->opts['service_specs'][$svc]['container'])
                {
                    case 'REGIONALRATEBOXA':
                        $max_weight = '15 lbs.';
                        break;
                    case 'REGIONALRATEBOXB':
                        $max_weight = '20 lbs.';
                        break;
                    case 'REGIONALRATEBOXC':
                        $max_weight = '25 lbs.';
                        break;
                    default:
                        $max_weight = '70 lbs.';
                        break;
                }
                break;
            default:
                $max_weight = '70 lbs.';
                break;
        }
        
        
        foreach ($containers as $key => $val)
        {
            $container_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['container'],false).'>'.$key.'</option>'; 
        }
        
        $container_info  = '"Auto Flat Rate Box" will automatically choose between 
						    Small, Medium, and Large Boxes. Anything over that gets sent as Variable,
                        	returning a Priority Mail quote.';
        $dimensions_info = '<ul>
                                <li>- All dimensions in inches.</li>
                                <li>- Height/Width/Length required if Size is "Large"</li>
                                <li>- Girth only required if Container is not "Rectangular"</li>
                            </ul>';

        
        $size_array = array('Regular (up to 12")' => 'REGULAR',
                            'Large (above 12")'   => 'LARGE');
        
        foreach ($size_array as $key => $val)
        {
            $size_options .= '<option value="'.$val.'" '.selected($val,$this->opts['service_specs'][$svc]['size'],false).'>'.$key.'</option>';
        }
        
        
        return <<<EOF
            <div style="float:right">Maximum Weight: <span id="$mw_id">$max_weight</span></div>
            <table class="usps_opts">
                <tr>
                    <th width="150">Package Size</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][service_specs][$svc][size]">
                            $size_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_size_info" class="field_info"></span>
                    </td>
                </tr>
                <tr>
                    <th>Container</th>
                    <td>
                        <select id="{$svc_id}_container" name="{$po}[$this->my_options_name][service_specs][$svc][container]">
                            $container_options
                        </select>
                    </td>
                    <td>
                        <span id="{$svc_id}_container_info" class="field_info">$container_info</span>
                    </td>
                </tr>
                <tr>
                    <th>Dimensions</th>
                    <td>
                        <div><div style="float:left"><em>Width:</em></div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][width]"
                        value="{$this->opts[service_specs][$svc][width]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Length:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][length]"
                        value="{$this->opts[service_specs][$svc][length]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Height:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][height]"
                        value="{$this->opts[service_specs][$svc][height]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Girth:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][service_specs][$svc][girth]"
                        value="{$this->opts[service_specs][$svc][girth]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_dimensions_info" class="field_info">
                            $dimensions_info
                        </span>
                    </td>
                </tr>
            </table>
EOF;
    }
    
    
    /**
     * @package USC_USPS_html_helper
     * @method  _get_html_for_intl_mailtype()
     * @desc    Returns HTML for International Services
     * @return  string
     */
    protected function _get_html_for_intl_mailtype($po,$svc)
    {
        // HTML ID
        $subsvc    = $svc['subservices'];    	
    	$svc       = $svc['value'];
    	$svc_id    = str_replace(' ','_',$svc);
        $opts      = $this->get_options();
        
        
        $machinable_array = array('Yes' => 'TRUE', 'No' => 'FALSE');
        foreach ($machinable_array as $key => $val)
        {
            $selected = (isset($opts['intl_service_specs'])) ? selected($val, $opts['intl_service_specs'][$svc]['machinable'], false) : '';
            $machinable_options .= '<option value="'.$val.'" '.$selected.'>'.$key.'</option>';
        }
        $machinable_info = 'Indicates whether or not the item is machinable. A surcharge is applied to a 
                            First-Class Mail International item if it has one or more non-machinable characteristics. 
                            See <a href="http://pe.usps.com/text/imm/immc2_016.htm#ep2368090" target="_new">International Mail Manual (IMM) Section 241.217</a> 
                            for more information.';
        
        
        $gxg_info = '<a href="https://www.usps.com/ship/gxg.htm" target="_new">Global Express Guaranteed</a> - Select "Yes" if you want to get GXG rates, when applicable.';
        
        $gxg_array = array('use_gxg' => array('Yes' => '1', 'No' => '0'),
                           'gift'  => array('Yes' => 'Y', 'No' => 'N'));
        foreach ($gxg_array['use_gxg'] as $key => $val)
        {
            $selected = isset($opts['intl_service_specs']) ?  selected($val, $opts['intl_service_specs'][$svc]['use_gxg'], false) : '';
            $use_gxg_options .= '<option value="'.$val.'" '.$selected.'>'.$key.'</option>';
        }
        
        foreach ($gxg_array['gift'] as $key => $val)
        {
            $selected = isset($opts['intl_service_specs']) ?  selected($val, $opts['intl_service_specs'][$svc]['gxg_poboxflag'], false) : '';
            $gift_options .= '<option value="'.$val.'" '.$selected.'>'.$key.'</option>';
        }
        
        
        $container_array = array('Rectangular' => 'RECTANGULAR', 'Non-Rectangular' => 'NONRECTANGULAR');
        foreach ($container_array as $key => $val)
        {
            $selected = isset($opts['intl_service_specs']) ?  selected($val, $opts['intl_service_specs'][$svc]['container'], false) : '';
            $container_options .= '<option value="'.$val.'" '.$selected.'>'.$key.'</option>';
        }
        
        // Borrow gift array
        foreach ($gxg_array['gift'] as $key => $val)
        {
            $selected = isset($opts['intl_service_specs']) ?  selected($val, $opts['intl_service_specs'][$svc]['commercial_flag'], false) : '';
            $commercial_options .= '<option value="'.$val.'" '.$selected.'>'.$key.'</option>';
        }
        $commercial_info = 'Returns commercial base postage.';
        
        
        $size_array = array('Regular' => 'REGULAR', 'Large' => 'LARGE');
        $size_info  = 'Regular: package dimensions are 12" or less.';
        foreach ($size_array as $key => $val)
        {
            $selected = isset($opts['intl_service_specs']) ?  selected($val, $opts['intl_service_specs'][$svc]['size'], false) : '';
            $size_options .= '<option value="'.$val.'" '.$selected.'>'.$key.'</option>';
        }

        
        $dimensions_info = '<ul>
                                <li>- All dimensions in inches.</li>
                                <li>- Height/Width/Length required if Size is "Large" or if GXG is "Yes"</li>
                                <li>- Girth only required if Container is "Non-Rectangular"</li>
                            </ul>';
        
        
        $subservice_opts = array();
        $subsvc_size = count($subsvc) == 1 ? 1 : 10;
        foreach ($subsvc as $key => $val)
        {
        	$selected = isset($opts['intl_subservices'][$svc]) && 
        				in_array($key,$opts['intl_subservices'][$svc]) ? 'selected="selected"' : '';
        	$subservice_opts .= '<option value="'.$key.'" '.$selected.'>'.$val.'</option>'; 
        }
        
        
        return <<<EOF
            <table class="usps_opts">
                <tr>
                    <th width="150">Machinable</th>
                    <td width="140">
                        <select name="{$po}[$this->my_options_name][intl_service_specs][$svc][machinable]">
                             $machinable_options
                         </select>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_machinable_info" class="field_info">
                            $machinable_info
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>GXG</th>
                    <td>
                        <em>Use GXG? </em>
                            <div style="float:right"><select name="{$po}[$this->my_options_name][intl_service_specs][$svc][use_gxg]">
                                $use_gxg_options
                            </select></div>
                        <br style="clear:both"/>
                        <em>Send as Gift: </em>
                            <div style="float:right"><select name="{$po}[$this->my_options_name][intl_service_specs][$svc][gxg_giftflag]">
                                $gift_options
                            </select></div>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_gxg_info" class="field_info">
                            $gxg_info
                        </span>
                    </td>
                </tr>
                <tr>
                	<th>Container</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][intl_service_specs][$svc][container]">
                             $container_options
                         </select>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_container_info" class="field_info">
                            $container_info
                        </span>
                    </td>
                </tr>
                <tr>
                	<th>Size</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][intl_service_specs][$svc][size]">
                             $size_options
                         </select>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_size_info" class="field_info">
                            $size_info
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Dimensions</th>
                    <td>
                        <div><div style="float:left"><em>Width:</em></div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][intl_service_specs][$svc][width]"
                        value="{$this->opts[intl_service_specs][$svc][width]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Length:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][intl_service_specs][$svc][length]"
                        value="{$this->opts[intl_service_specs][$svc][length]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Height:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][intl_service_specs][$svc][height]"
                        value="{$this->opts[intl_service_specs][$svc][height]}" class="v_is_float" size="10" maxlength="10"/></div></div>
    
                        <div style="clear:both"><div style="float:left; clear:left"><em>Girth:</em> </div><div style="float:right; clear:right"><input type="text" name="{$po}[$this->my_options_name][intl_service_specs][$svc][girth]"
                        value="{$this->opts[intl_service_specs][$svc][girth]}" class="v_is_float" size="10" maxlength="10"/></div></div>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_dimensions_info" class="field_info">
                            $dimensions_info
                        </span>
                    </td>
                </tr>
                <tr>
                	<th>Commercial?</th>
                    <td>
                        <select name="{$po}[$this->my_options_name][intl_service_specs][$svc][commercial_flag]">
                             $commercial_options
                         </select>
                    </td>
                    <td class="top">
                        <span id="{$svc_id}_commercial_info" class="field_info">
                            $commercial_info
                        </span>
                    </td>
                </tr>
                
                <tr>
                	<th>Services</th>
                	<td colspan="2">
                		<select name="{$po}[$this->my_options_name][intl_subservices][$svc][]" multiple="multiple" size="$subsvc_size">
                			$subservice_opts
                		</select>
                	</td>
                
                </tr>
            </table>
EOF;
        
        
        
    }
    
}


/**
 * @package USC_USPS_xml_helper
 * @desc    Helper methods to generate XML for Get Rate requests
 */
class USC_USPS_xml_helper extends USC_eShop_USPS
{
    private $opts;
    private $count = 0;
    private $max_weight = 1120; // oz
    private $max_size = 130; // combined length + girth

    function __construct()
    {
        $this->opts = $this->get_options();
    }
    
    
    /**
     * @package USC_USPS_xml_helper
     * @method  _make_bundles()
     * @desc    Creates bundles of products to use in packing
     * @param   $cart
     * @param   $default_dim
     * @return  array(array (weight => $x, dim => array($l,$w,$h,$t)))
     */
	private function _make_bundles($cart,$default_dim)
    {
    	global $blog_id;
    	$opts = $this->opts;
    	
    	// Apply package class logic if needed
    	if (! $opts['package_class']) $opts['package_class'] = '';
    	
    	$bundle      = array();
    	$totalweight = $_SESSION['eshop_totalweight'.$blog_id]['totalweight']; 

    	if (! $default_dim['length']) $default_dim['length'] = ''; 
    	if (! $default_dim['width'])  $default_dim['width']  = '';   
    	if (! $default_dim['height']) $default_dim['height'] = '';
    	if (! $default_dim['girth']) $default_dim['girth'] = '';
    	
    	switch($opts['package_class'])
    	{
    		case 'global':
    			$dim['width']  = $default_dim['width'];
    			$dim['height'] = $default_dim['height'];
    			$dim['length'] = $default_dim['length'];
    			$dim['girth']  = (2 * $dim['width']) + (2 * $dim['height']);
    			$dim['total']  = $dim['length'] + $dim['girth'];
    		
    			$bundle = array('weight' => $totalweight, 'dim' => $dim);
    			break;
    		
    		case 'product':
    		case 'product_option':
    			$prod_avg = array();
    			foreach ($cart as $key => $val)
    			{
    				// Each key is a product
    				$post_id = $val['postid'];
    				$qty     = $val['qty'];
    		
    				$is_free = 'product' === $opts['package_class'] ? apply_filters('usc_ese_is_free_class', false, $post_id) :
    																  apply_filters('usc_ese_is_free_class', false, $post_id, $val['option']);
    					
    				if ($is_free === true) continue;
    		
    				$prod_meta = maybe_unserialize(get_post_meta($post_id,'_eshop_product', TRUE));
    		
    				$sel_package_class = 'product' === $opts['package_class'] ? $prod_meta['sel_package_class'] :
    				$prod_meta['products'][$val['option']]['sel_package_class'];
    				
    				if (! $sel_package_class)
    				{
    					// If at least one product doesn't have its own dimensions,
    					// fall back to global dimensions
    					$dim['width']  = $default_dim['width'];
    					$dim['height'] = $default_dim['height'];
    					$dim['length'] = $default_dim['length'];
    					$dim['girth']  = (2 * $dim['width']) + (2 * $dim['height']);
    					$dim['total']  = $dim['length'] + $dim['girth'];
    		
    					$bundle = array('weight' => $totalweight, 'dim' => $dim);

    					break;
    				}
    		
    				// Get pack class and add up the dimensions times qty
    				// Does not get called if no $sel_package_class
    				$pack_class = $this->get_package_class_by_name($sel_package_class);
    				$prod_avg[] = pow($pack_class['width'] * $pack_class['length'] * $pack_class['height'], 1/3);
    				$num_items += $qty;
    				$weight    += $val['weight'] * $qty;
    		
    			}
 				
    			$final_weight = isset($bundle['weight']) ? $bundle['weight'] : $weight;
    			
    			if (count($prod_avg))
    			{
    				// Do yet another average to make sure everything fits snugly in the
    				// end cube.
    				$sum = 0;
    				foreach ($prod_avg as $pa)
    				{
    					$sum += $pa;
    				}
    		
    				$avg = $sum / count($prod_avg);
    		
    				$dim = $this->_calc_bundle_size($avg,$num_items);
    				$dim['girth'] = (2 * $dim['width']) + (2 * $dim['height']);
    				$dim['total'] = $dim['length'] + $dim['girth'];
    		
    				$bundle = array('weight' => $final_weight,'dim' => $dim);
    			}
    			 
    			break;
    		default:
    			break;
    	} 

    	
		$bundles = array();
		$num_new_bundles = 1;
		
		$weight = $this->convert_to_ounces($bundle['weight']);
		
		if ($weight['data'] > $this->max_weight)
		{
			$num_new_bundles = ceil($weight['data'] / $this->max_weight);
		}
		elseif($bundle['dim']['total'] > $this->max_size)
		{
			$num_new_bundles = ceil($bundle['dim']['total'] / $this->max_size);
		} 
		
		$new_weight = number_format($bundle['weight'] / $num_new_bundles, 2, '.', '');
		$new_width  = number_format($bundle['dim']['width'] / $num_new_bundles, 2, '.', '');
		$new_length = number_format($bundle['dim']['length'] / $num_new_bundles, 2, '.', '');
		$new_height = number_format($bundle['dim']['height'] / $num_new_bundles, 2, '.', '');
		$new_girth  = number_format((2 * $new_width) + (2 * $new_height), 2, '.', '');
		
		$new_dim = array('length' => $new_length, 'width' => $new_width, 'height' => $new_height, 'girth' => $new_girth);
			
		for ($i = 0; $i < $num_new_bundles; $i++)
		{
			$bundles[] = array('weight' => $new_weight, 'dim' => $new_dim);
		}
		
		return $bundles;
    }
    
    /**
     * @package USC_USPS_xml_helper
     * @method  _calc_bundle_size()
     * @desc    Creates bundles of products to use in packing
     * @param   float $avg - average size of box side
     * @param   int $num_items - number of items to bundle
     * @return  assoc_array($length,$width,$height,$girth)
     */
    private function _calc_bundle_size($avg,$num_items)
    {
    	$base  = array('row' => 1, 'col' => 1, 'lev' => 1);
    	$count = 0;
    	
    	while ($count < $num_items)
    	{
    		$capacity = $base['row'] * $base['col'] * $base['lev'];
    	
//     		$sorted = $base;
//     		arsort($sorted);
//     		$highest_dim = array_shift($sorted);
// 			$cur_width = $highest_dim * $avg;
// 			$cur_girth = $highest_dim + (2 * array_shift($sorted) + 2 * array_shift($sorted));

// 			if ( $cur_width >= $max_width ||
// 				 $cur_girth >= $max_girth)
// 			{
// 				break;
// 			}
    	
    		if ($count == $capacity)
    		{
    			if ($base['lev'] == $base['row'] &&
    					$base['row'] == $base['col'])
    			{
    				$base['col']++;
    			}
    			elseif ($base['row'] < $base['col'])
    			{
    				$base['row']++;
    			}
    			elseif ($base['lev'] < $base['row'])
    			{
    				$base['lev']++;
    			}
    		}
    	
    		$count++;
    	}
    	
    	$dim = array();
    	arsort($base);
    	
    	$dim['length'] = number_format((array_shift($base) * $avg),1);
    	$dim['width']  = number_format((array_shift($base) * $avg),1);
    	$dim['height'] = number_format((array_shift($base) * $avg),1);
    	$girth         = $dim['length'] + (2*$dim['width']) + (2*$dim['height']);
    	$dim['girth']  = number_format($girth,1); 
    	
    	return $dim;
    }
    
    
    /**
     * @package USC_USPS_xml_helper
     * @method  _make_domestic_package_xml()
     * @desc    Generates Package portion of domestic XML
     * @param   SimpleXMLElement $xml
     * @param   string $svc - service name
     * @param   array $details - required non-specific details
     */
    function _make_domestic_package_xml($xml, $svc, $details)
    {
        global $blog_id;
        
        $opts = $this->opts;
        
        // get bundles
        // then pack each one in the XML
        $bundles = $this->_make_bundles($_SESSION['eshopcart'.$blog_id], $opts['service_specs'][$svc]);
        $amount_divisor = count($bundles);
        
        foreach ($bundles as $bundle)
        {
	        $xml->addChild("Package")->addAttribute('ID',$this->count);
	        $pack = $xml->Package[$this->count];
	        $pack->addChild('Service',$svc);
	        
	        if (isset($this->opts['service_specs'][$svc]['first_class_mail_type']))
	        {
	        	$pack->addChild('FirstClassMailType', $this->opts['service_specs'][$svc]['first_class_mail_type']);
	        }
	        
	        $pack->addChild('ZipOrigination',$details['from_zip']);
	        $pack->addChild('ZipDestination',$details['to_zip']);
			$pack->addChild('Pounds',0);
			
			$weight = $this->convert_to_ounces($bundle['weight']);
			$weight = number_format($weight['data'],1);
			
			$pack->addChild('Ounces',$weight);
			
			$container_value = isset($this->opts['service_specs'][$svc]['container']) ? $this->opts['service_specs'][$svc]['container'] : '';
			
			if ($container_value == 'AUTO FLAT RATE BOX') 
			{
				$volume = $bundle['dim']['width'] * $bundle['dim']['length'] * $bundle['dim']['height'];
				
				$fr_svc = $this->_flat_rate_box_matrix($volume);
				if ($fr_svc !== false) 
				{
					$container_value = $fr_svc['name'];
					$bundle['dim']['width'] = $fr_svc['width'];
					$bundle['dim']['length'] = $fr_svc['length'];
					$bundle['dim']['height'] = $fr_svc['height'];
				}
				else 
				{
					$container_value = 'VARIABLE'; // Returns Priority Mail as contents too big for a single Flat Rate box

					// Uncommenting these will also return Priority Mail, but cheaper. Dunny why. 
					// Leave them commented for now.
// 					$pack->Service = 'PARCEL'; 
// 					$this->opts['service_specs'][$svc]['machinable'] = 'TRUE';
				}
			} 
				
			
			$pack->addChild('Container', $container_value);
	        
			$pack->addChild('Size',$this->opts['service_specs'][$svc]['size']);
			
				
	        $pack->addChild('Width',$bundle['dim']['width']);
	        $pack->addChild('Length',$bundle['dim']['length']);
	        $pack->addChild('Height',$bundle['dim']['height']);
	        
	        
	        if (isset($this->opts['service_specs'][$svc]['container']))
	        {
		        if ($this->opts['service_specs'][$svc]['container'] === 'VARIABLE' ||
	                $this->opts['service_specs'][$svc]['container'] === 'NONRECTANGULAR')
	            {
	         		$pack->addChild('Girth',$bundle['dim']['girth']);
				}
	        }
	        
	        if ($this->opts['send_value'] == 'BOTH' || $this->opts['send_value'] == 'DOMESTIC')
			{
	        	$amount = number_format($_SESSION['final_price'.$blog_id] / $amount_divisor, 2,'.','');
	            $pack->addChild('Value',$amount);
			}
			
	        if (isset($this->opts['service_specs'][$svc]['sort_by']))
		    {
		        $pack->addChild('SortBy',$this->opts['service_specs'][$svc]['sort_by']);
		    }
		    
		    if (isset($this->opts['service_specs'][$svc]['machinable']))
		    {
		    	$pack->addChild('Machinable',$this->opts['service_specs'][$svc]['machinable']);
		    }
		            
	
		    $this->count++;
        }
	    
	    return $xml;
    }
    
    /**
     * @package USC_USPS_xml_helper
     * @method  _flat_rate_box_matrix()
     * @desc    Holds the volumetric matrix for auto flat rates
     * @param   float $volume
     * @param   array $svc_details
     */
    public function _flat_rate_box_matrix($volume)
    {
    	$matrix = array(
    				array('name' => 'SM FLAT RATE BOX', 'vol' => 75.33, 'length' => 8.625, 'width' => 5.375, 'height' => 1.625, 'max_weight' => 1120), // 70lbs in oz
    				array('name' => 'MD FLAT RATE BOX', 'vol' => 514.3, 'length' => 11, 'width' => 8.5, 'height' => 5.5, 'max_weight' => 1120),
    				array('name' => 'LG FLAT RATE BOX', 'vol' => 792, 'length' => 12, 'width' => 12, 'height' => 5.5, 'max_weight' => 1120)
    			);
    	
    	foreach ($matrix as $m) {
    		if ((float)$volume <= $m['vol']) return $m;
    	}
    	
    	return false; // Too big for a single flat rate box
    }
    
    
    /**
     * @package USC_USPS_xml_helper
     * @method  _make_intl_package_xml()
     * @desc    Generates Package portion of international XML
     * @param   SimpleXMLElement $xml
     * @param   string $svc - service name
     * @param   array $details - required non-specific details
     */
    function _make_intl_package_xml($xml,$svc,$details)
    {
        global $blog_id;
        
        $opts = $this->opts;
        
        // get bundles
        // then pack each one in the XML
        $bundles = $this->_make_bundles($_SESSION['eshopcart'.$blog_id], $this->opts['intl_service_specs'][$svc]);
        
        foreach ($bundles as $bundle)
        {
	        $xml->addChild("Package")->addAttribute('ID',$this->count);
	        
	        $pack = $xml->Package[$this->count];
	        
	        $pack->addChild('Pounds',0);
	        
	        $weight = $this->convert_to_ounces($bundle['weight']);
	        $weight = $weight['data'];
	        
	        $pack->addChild('Ounces',number_format($weight,1));
	        $pack->addChild('Machinable',$this->opts['intl_service_specs'][$svc]['machinable']);
	        $pack->addChild('MailType',$svc);
	        
			if ($this->opts['intl_service_specs'][$svc]['use_gxg'])
	        {
				$pack->addChild('GXG')->addChild('POBoxFlag','N');
				$pack->GXG->addChild('GiftFlag',$this->opts['intl_service_specs'][$svc]['gxg_giftflag']);
	        }
	        
			$amount = number_format($_SESSION['final_price'.$blog_id], 2,'.','');
	        $pack->addChild('ValueOfContents',$amount);
	        $pack->addChild('Country', $details['country']);
	        $pack->addChild('Container',$this->opts['intl_service_specs'][$svc]['container']);
	        $pack->addChild('Size',$this->opts['intl_service_specs'][$svc]['size']);
	        
	      	if ($this->opts['intl_service_specs'][$svc]['use_gxg'] || 
	      		$this->opts['intl_service_specs'][$svc]['container'] == 'NONRECTANGULAR')
			{
				$girth = $bundle['dim']['girth'];
			}        
	        
			$pack->addChild('Width', $bundle['dim']['width']);
			$pack->addChild('Length',$bundle['dim']['length']);
			$pack->addChild('Height',$bundle['dim']['height']);
			$pack->addChild('Girth', $girth);
			
			$pack->addChild('OriginZip',$details['from_zip']);
			$pack->addChild('CommercialFlag',$this->opts['intl_service_specs'][$svc]['commercial_flag']);
	        
	
	        $this->count++;
        }
		
		return $xml;
    
    }
    
}


/* End of file USC_eShop_USPS.php */
/* Location: eshop-shipping-extension-usps/includes/modules/usps-module/USC_eShop_USPS.php */