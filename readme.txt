=== WooCommerce Preset Shipping Locations ===
Contributors: wordpress.org/
Tags: WooCommerce, shipping, custom post types
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 4.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Utilises shipping location custom post types to offer predefined shipping locations to a customer for easier shipping destination entry.

== Description ==

WooCommerce Preset Shipping Locations is a plugin used to help display preset shipping locations.

As this plugin facilitates only the pulling and displaying of preset shipping location data, you'll need to use this plugin combined with two other plugins:

'Custom Post Type UI' plugin to create the shipping_address post type:
* https://github.com/WebDevStudios/custom-post-type-ui/
'Advanced Custom Fields' to create the meta data form elements to store and update data on a single Shipping Address page:
* https://www.advancedcustomfields.com/
If enough interest arose, there will be further development to add a shipping post type that facilitates adding as much or as little data to that post type as is required.

Likewise, further development to facilitate plugin settings to define the query_post value could ensure given enough interest.

If you just want to take and use the code for your own post type, do the following:
* Update the query_posts function to call what ever your post type is called.
* Update the meta fields to what ever you call them.

If you have any ideas or suggestions, please fire them through or submit a pull request.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/woocommerce-preset-shipping-locations` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin
4. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)

== Changelog ==

= 0.1.1 =
* Updated file name. Added code to WP "pluginify" the code in prep to submit it to wordpress.org. This included adding plugin infomation in the main plugin file, adding an if statement to check whether WooCommerce is in use, and adding space around function paramters etc.
