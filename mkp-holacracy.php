<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wordpress.org/plugins/mkp-holacracy
 * @since             1.0.0
 * @package           MKP_Holacracy
 *
 * @wordpress-plugin
 * Plugin Name:       MKP Holacracy
 * Plugin URI:        http://wordpress.org/plugins/mkp-holacracy
 * Description:       Holacracy for MKP
 * Version:           1.0.0
 * Author:            Mickey Kay
 * Author URI:        http://mickeykaycreative.com?utm_source=mkp-holacracy&utm_medium=plugin-repo&utm_campaign=WordPress%20Plugins/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mkp-holacracy
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mkp-holacracy-activator.php
 */
function activate_mkp_holacracy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mkp-holacracy-activator.php';
	MKP_Holacracy_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mkp-holacracy-deactivator.php
 */
function deactivate_mkp_holacracy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mkp-holacracy-deactivator.php';
	MKP_Holacracy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mkp_holacracy' );
register_deactivation_hook( __FILE__, 'deactivate_mkp_holacracy' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mkp-holacracy.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mkp_holacracy() {

	// Pass main plugin file through to plugin class for later use.
	$args = array(
		'plugin_file' => __FILE__,
	);

	$plugin = MKP_Holacracy::get_instance( $args );
	$plugin->run();

}
run_mkp_holacracy();
