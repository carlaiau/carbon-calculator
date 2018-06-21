<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Carbon_Calculator
 * @subpackage Carbon_Calculator/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Carbon_Calculator
 * @subpackage Carbon_Calculator/includes
 * @author     Your Name <email@example.com>
 */


 /* Hello World! */
class Carbon_Calculator {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'Carbon_Calculator_VERSION' ) ) {
			$this->version = Carbon_Calculator_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'carbon-calculator';
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-carbon-calculator-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-carbon-calculator-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-carbon-calculator-public.php';
		$this->loader = new Carbon_Calculator_Loader();
	}

	private function define_admin_hooks() {
		$plugin_admin = new Carbon_Calculator_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu');
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . ".php");
		$this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');
	}

	private function define_public_hooks() {
		$plugin_public = new Carbon_Calculator_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
		$this->loader->add_action('carbon-calculator', $plugin_public, 'display_carbon_calculator_output');
	}


	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
