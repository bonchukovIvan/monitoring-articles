<?php

if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('WbsmdHttp') ) {
	class WbsmdHttp {
		/*
         *  Endpoint for wordpress sites
         */
        const WORDPRESS_ENDPOINT = 'wp-json/websumdu/v1/monitoring';

        /*
         *  Endpoint for joomla sites
         */
        const JOOMLA_ENDPOINT = 'index.php?option=com_ajax&plugin=ajaxarticles&format=json&custom_date=';

		/**
		 * Site link for check relevance posts.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected $link = '';

        /**
		 * The endpoint for current site.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected $curl_opts = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => 'utf-8',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_2TLS,
			CURLOPT_HTTPHEADER     => [
				'Content-Type: application/json',
			],
		];

		/**
		 * The date of start monitoring period.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected $custom_date = '';

        /**
		 * The type of the site CMS.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected $site_cms = '';
		
		public function __construct( $link, $site_cms, $custom_date ) {
			$this->link = $link;
			$this->site_cms = $site_cms;
			$this->custom_date = $custom_date ? $custom_date : 'first day of january this year';
		}

		public function get_site_data() {
			$response = new stdClass;
			if ( $this->site_cms === 'jml' ) {
				$this->link .= self::JOOMLA_ENDPOINT . urlencode($this->custom_date);
				$response = $this->get_request($this->link);
				if (empty($response->data)) {
					return false;
				}
			}
			elseif ( $this->site_cms === 'wp' ) {
				$this->link = $this->link.self::WORDPRESS_ENDPOINT;
				$body = json_encode( ['custom_date' => $this->custom_date] );
				$response = $this->post_request($this->link, $body);
			}
			if (!isset($response->data) || !is_array($response->data)) {
				return [];
			}
			return (array) $response->data[0];
		}

		public function get_request($url) {
			$curl  = curl_init();

			curl_setopt_array( $curl, $this->curl_opts );
			curl_setopt( $curl, CURLOPT_URL, $url ); 

			$response = curl_exec( $curl );
            curl_close( $curl );
			
			return json_decode( $response );
		}

		public function post_request($url, $body = []) {
			$curl  = curl_init();

			curl_setopt_array( $curl, $this->curl_opts );
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'POST' );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $body);

			$response = json_decode( curl_exec($curl) );
            curl_close( $curl );

			return $response;
		}
	}
}