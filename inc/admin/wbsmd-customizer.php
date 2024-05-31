<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wbsmd_Customizer {

	public function __construct() {
		add_action('customize_register', array($this, 'register_customize_sections'));
	}

	public function register_customize_sections($wp_customize) {
		$this->general_callout_section($wp_customize);
	}

	/*
	 *	general settings 
	 */
	public function general_callout_section($wp_customize) {
		/*
		 * header setting section
		 */ 
		$wp_customize->add_section('basic-general-callout-section', array(
			'title' => 'Основні налаштування системи', 
			'priority' => 2,
			'description' => __('Налаштування системи моніторигу'),
		));
		/*
		 * youtube link setting
		 */ 
		$wp_customize->add_setting('basic-general-callout-protocol', array(
			'default' => '',
		));
		$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'basic-general-callout-protocol-control', array(
			'label' => 'Посилання на сторінку з протоколом по актуальності',
			'section' => 'basic-general-callout-section',
			'settings' => 'basic-general-callout-protocol',
			'type' => 'text',
		)));
	}


	// Sanitize
	public function sanitize_custom_url($input) {
		return filter_var($input, FILTER_SANITIZE_URL);
	}

	public function sanitize_custom_email($input) {
		return filter_var($input, FILTER_SANITIZE_EMAIL);
	}

	public function sanitize_custom_text($input) {
		return filter_string_polyfill($input);
	}

    function filter_string_polyfill(string $string): string {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }
}