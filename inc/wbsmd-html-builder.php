<?php

if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('WbsmdHtmlBuilder') ) {
	class WbsmdHtmlBuilder {
		
		public function __construct() { }

		public function display_item_group($prop_name, $prop = '', $class = '') {
            echo $this->get_item_group( $prop_name, $prop, $class );
        }

		public function get_item_group( $prop_name, $prop = '', $class = '' ) {
			$add_class = $class ? ' '.esc_attr( $class ) : '';

			$output = '';
			$output .= '<div class="item__group'. $add_class . '">';
				$output .= '<div class="item__prop-name">'.  $prop_name  . '</div> ';
				if ($prop) {
					$output .= '<div class="item__prop">'. esc_attr( $prop ) . '</div>';
				}
			$output .= '</div>';

			return $output;
		}
	}
}