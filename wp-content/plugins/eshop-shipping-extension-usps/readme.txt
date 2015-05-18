=== Plugin Name ===
Contributors: useStrict
Tags: eShop, USPS, United States Postal Service, Shipping Extension, Third Party Shipping, Shipping Quotes
Requires at least: WP 3.0, eshop-shipping-extension 2.0, PHP 5
Tested up to: 3.7.1
Stable tag: 2.3.5
License: Semi-free UseStrict Consulting license
License URI: http://usestrict.net/semi-free-usestrict-consulting-license 

USPS extension to eShop Shipping Modules

== Description ==

eShop Shipping Extension framework overrides eShop's default shipping methods, interacting directly with third-party vendors for real-time shipping rates and services.

USPS module is an extension of eshop Shipping Extension which enables it  

== Installation ==

1. Install eshop-shipping-extension plugin from Wordpress.org if you haven't already done so.;
1. Upload eshop-shipping-extension-usps.zip to your blog's wp-content/plugins directory;
1. Activate the plugin in your Plugin Admin interface;
1. Set eShop shipping preferences to Mode 4 (by Weight & Zone);
1. Go to Settings -> eShop Shipping Extension to activate USPS interface;
1. Follow the instructions on how to obtain your USPS API credentials, and save your preferences.

== Frequently Asked Questions ==


== Screenshots ==

1. A few easy settings get you up and running in minutes.

== Changelog ==
= 2.3.5 =
* New: applying 'ese_usps_service_filter' filter on the returned XML

= 2.3.4 =
* Handling optional charset attribute in XML declaration.

= 2.3.3 =
* Fixed weight bug for an international edge case.

= 2.3.2 =
* Fixed Javascript error when no shipping services were found.
* Updated code to match USPS' July/2013 changes

= 2.3.1 =
* Adjust for USPS's service naming changes.

= 2.3 =
* Added support for multi-package requests in case a package is over 70 lbs or 130 total inches.

= 2.2.2 =
* Fixed bug that returned errored service messages even if some services were successful. 

= 2.2.1 =
* Added support for per-product free shipping

= 2.2 =
* Automatic Update Capability.

= 2.1.7 =
* Fixed product-option bug

= 2.1.6 =
* Added warning in case international options have not been selected.

= 2.1.5 =
* Added filter for USPS to work with Custom Handling fee add-on.

= 2.1.4 =
* Adjusted auto flat rate box sizes to use cube(-ish) boxes.

= 2.1.3 =
* Added logic for Priority Mail auto Flat Rate Box.

= 2.1.2 =
* Fixed typo on function name.

= 2.1.1 =
* Fixed missing cURL return transfer option.

= 2.1 =
* Added cURL as default transport.

= 2.0.2 =
* Improved package bundling.

= 2.0.1 =
* Fixed array union bug.

= 2.0 =
* Adapted to work with multiple vendors (eSE 2.0)

= 1.2 =
* Added support for in-store pickup option.

== 1.1 ==
* Added Advanced Packaging

= 1.0.1 =
* Added Service List for International Mail Types to better fine-tune which services the shopper sees.

= 1.0 =
* Initial release of USPS extension

== Upgrade Notice ==
