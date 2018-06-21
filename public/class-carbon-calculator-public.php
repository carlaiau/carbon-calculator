<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://carlaiau.com
 * @since      1.0.0
 *
 * @package    Carbon_Calculator
 * @subpackage Carbon_Calculator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Carbon_Calculator
 * @subpackage Carbon_Calculator/public
 * @author     Carl Aiau <hello@carlaiau.com>
 */

class Carbon_Calculator_Public {

	private $plugin_name;
	private $version;
	private $generators;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->generators = array('total', 'contact', 'genesis','mercury', 'nova', 'ecotricity');
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) .
			'css/carbon-calculator-public.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) .
			'js/carbon-calculator-public.js', array( 'jquery' ), $this->version, false );
	}

	public function register_shortcodes() {
		add_shortcode( 'carbon', array( $this, 'display_carbon_calculator_output' ) );
	}

	public function display_carbon_calculator_output($atts){
		$generator = $atts['gen'];
		if( in_array($generator, $this->generators)){
		    $options = get_option($this->plugin_name);
		    if($options[$generator . '-initial_date'] != ""){
		        $initial_datetime = new DateTime($options['initial_date']);
		    }
		    else{
		        $initial_datetime = new DateTime();
		    }
		    $initial_datetime->setTimezone(new DateTimeZone("Pacific/Auckland") );
		    $initial_datetime->setTime("00","00","00");
		    $today_datetime = new DateTime();
		    $today_datetime->setTimezone(new DateTimeZone("Pacific/Auckland") );
		    $sec_difference = $today_datetime->getTimestamp() - $initial_datetime->getTimestamp();

		    /* This is yearly */
		    $secs_in_year = 365 * 24 * 3600;

		    $per_sec_savings = $options[$generator . '-yearly_savings'] / $secs_in_year;
		    $savings_to_now = $options[$generator . '-initial_savings'] + $per_sec_savings * $sec_difference;

		    $output_string = '<div class="carbon-output" style="width:100%; float:left;">';

		    $output_string .=  "<p><span class='total' data-total-savings='" . $savings_to_now ."'
		        data-savings-per-second='" . $per_sec_savings . "'
		        data-period='1000'>";
		    $output_string .= number_format( $savings_to_now ,3, ".", ",") . "</span> Kgs CO2e<br/>";
		    $output_string .= apply_filters('richedit_pre', $options[$generator . '-free_text_overall']) . "</p>";
		    $output_string .= "<p><span class='page' data-total-savings='0'
		                data-savings-per-second='" . $per_sec_savings . "'
		                data-period='1000'></span> Kgs CO2e<br/>";
		    $output_string .= apply_filters('richedit_pre', $options[$generator . '-free_text_current']) . "</p>";
		    $output_string .= '</div>';
		    return $output_string;
		}
	}



}
