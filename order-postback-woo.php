<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.wpconcierges.com/
 * @since             1.0.0
 * @package           order_postback_woo
 *
 * @wordpress-plugin
 * Plugin Name:       Order Postback for Woocommerce
 * Plugin URI:        https://www.wpconcierges.com/plugins/order-postback-for-woocommerce/
 * Description:       This plugin sends your order information from your Woocomerce store as a key/value pair to any url of your choice, using either a POST or GET.  This is useful if you would like to store your orders or process orders outside of your Woocommerce store.
 * Version:           1.1.1
 * Author:            WpConcierges
 * Author URI:        https://www.wpconcierges.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       order_postback_woo
 * Domain Path:       /languages
 * WC requires at least: 3.0
 * WC tested up to: 7.2.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ORDER_POSTBACK_WOO_VERSION', '1.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-order-postback-woo-activator.php
 */
function activate_order_postback_woo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-order-postback-woo-activator.php';
	order_postback_woo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-order-postback-woo-deactivator.php
 */
function deactivate_order_postback_woo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-order-postback-woo-deactivator.php';
	order_postback_woo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_order_postback_woo' );
register_deactivation_hook( __FILE__, 'deactivate_order_postback_woo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-order-postback-woo.php';

function order_postback_woo_plugin_add_settings_link( $links ) {
	$settings_link = '<a href="tools.php?page=order-postback-woo">' . __( 'Settings' ) . '</a>';
	$premium_link = '<a href="https://www.wpconcierges.com/plugin-resources/order-postback-woo/">' . __( 'Upgrade to Premium / Documentation' ) . '</a>';
	array_push( $links, $settings_link );
	array_push( $links, $premium_link );
  	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'order_postback_woo_plugin_add_settings_link' );
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_order_postback_woo() {

	$plugin = new order_postback_woo();
	$plugin->run();

}
run_order_postback_woo();
