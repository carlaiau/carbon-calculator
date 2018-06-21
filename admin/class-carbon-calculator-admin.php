<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Carbon_Calculator
 * @subpackage Carbon_Calculator/admin
 * @author     Carl Aiau <carl@hello@carlaiau.com>
 */
class Carbon_Calculator_Admin {

	private $plugin_name;
	private $version;

	/*
		The generators are an array of power companies, this allows for dynamic
		creation of the form fields, the previews in admin
	*/
	private $generators;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->generators = array('total', 'contact', 'genesis','mercury', 'nova', 'ecotricity');
	}

	public function add_plugin_admin_menu(){
		add_options_page(
			'Ecotricity Carbon Calculator',
			'Carbon Calculator',
			'manage_options',
			$this->plugin_name,
			array($this, 'display_plugin_setup_page')
		);
	}

	public function add_action_links($links){
		$settings_link = array(
			'<a href="' .
			admin_url('options_general.php?page=' . $this->plugin_name) . '">' .
				__('Settings', $this->plugin_name) . '</a>'
		);
		return array_merge($settings_link, $links);
	}

	public function display_plugin_setup_page(){
		include_once( 'partials/carbon-calculator-admin-display.php');
	}

	public function options_update(){
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	public function enqueue_styles() {
		if ( 'settings_page_' . $this->plugin_name == get_current_screen()->id){
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/carbon-calculator-admin.css', array(), $this->version, 'all' );
        }
	}

	public function enqueue_scripts() {
		if ( 'settings_page_' . $this->plugin_name == get_current_screen()->id){
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/carbon-calculator-admin.js', array( 'jquery'), $this->version, false );
        }
	}

	public function validate($input){
		$valid_html =array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'h6' => array(),
			'br' => array(),
    		'em' => array(),
    		'strong' => array()
		);

		$valid = array();
		foreach($this->generators as $g){
			$valid[$g .'-free_text_overall'] = (isset($input[$g .'-free_text_overall']) && !empty($input[$g .'-free_text_overall'])) ?
				wp_kses($input[$g .'-free_text_overall'], $valid_html) : '';

			$valid[$g .'-free_text_current'] = (isset($input[$g .'-free_text_current']) && !empty($input[$g .'-free_text_current'])) ?
				wp_kses($input[$g .'-free_text_current'], $valid_html) : '';

			$valid[$g .'-initial_date'] = (isset($input[$g .'-initial_date']) && !empty($input[$g .'-initial_date'])) ?
				preg_replace('([^0-9\-])', '', $input[$g .'-initial_date']) : 0;
			$valid[$g .'-initial_savings'] = (isset($input[$g .'-initial_savings']) && !empty($input[$g .'-initial_savings'])) ?
				preg_replace('([^0-9.])', '', $input[$g .'-initial_savings']) : 0;
			$valid[$g .'-yearly_savings'] = (isset($input[$g .'-yearly_savings']) && !empty($input[$g .'-yearly_savings'])) ?
				preg_replace('([^0-9.])', '', $input[$g .'-yearly_savings']) : 0;
		}
		return $valid;
	}

	/*
	 *
	 * Called from the partial file to output the preview of each carbon calculator
	 *
	 */
	public function output_each_generation($gen, $inital_date, $yearly_savings, $inital_savings, $free_text_overall, $free_text_current){
		$today_datetime = new DateTime();
		$today_datetime->setTimezone(new DateTimeZone("Pacific/Auckland") );
		if($initial_date != ""){
			$initial_datetime = new DateTime($initial_date);
		}
		else{
			$initial_datetime = new DateTime();
		}
		$initial_datetime->setTimezone(new DateTimeZone("Pacific/Auckland") );
		$initial_datetime->setTime("00","00","00");
		$sec_difference = $today_datetime->getTimestamp() - $initial_datetime->getTimestamp();
		/* This is yearly */
		$secs_in_year = 365 * 24 * 3600;
		$per_sec_savings = $yearly_savings / $secs_in_year;
		$savings_to_now = $initial_savings + $per_sec_savings * $sec_difference;

		echo "<h3>[carbon gen=\"$gen\"]</h3>";
		echo "<p>";
		/* Total */
		echo "
		<span class='total'
			data-total-savings='" . $savings_to_now ."'
			data-savings-per-second='" . $per_sec_savings . "'
			data-period='1000'
		>
		";
		echo number_format( $savings_to_now ,3, ".", ",") . "</span> Kgs CO2e<br/>";
		echo $free_text_overall . "</p>";
		/* Page Load */
		echo "
		<p>
			<span class='page'
				data-total-savings='0'
				data-savings-per-second='" . $per_sec_savings . "'
				data-period='1000'
			></span> Kgs CO2e<br/>";
		echo $free_text_current . "</p>";
	}
}
