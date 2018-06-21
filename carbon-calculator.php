<?php

/**
 * The plugin bootstrap file
 *
 * @link              http://carlaiau.com
 * @since             1.0.0
 * @package           Carbon Calculator
 *
 * @wordpress-plugin
 * Plugin Name:       Carbon Calculator
 * Plugin URI:        http://inbound.org.nz/
 * Description:       Custom carbon calculator. Please note the output of the requires shortcoding [carbon-calculator] into the theme
 * Version:           1.0.0
 * Author:            Inbound Marketing
 * Author URI:        http://inbound.org.nz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       carbon-calculator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'Carbon_Calculator_VERSION', '1.0.0' );

require plugin_dir_path( __FILE__ ) . 'includes/class-carbon-calculator.php';

function run_Carbon_Calculator() {

	$plugin = new Carbon_Calculator();
	$plugin->run();

}
run_Carbon_Calculator();
