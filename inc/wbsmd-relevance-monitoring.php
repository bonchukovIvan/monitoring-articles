<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once WEBSUMDU_THEME_PATH . '/inc/utilities.php';

if ( ! class_exists( 'WbsmdRelevanceMonitoring' ) ) {

    class WbsmdRelevanceMonitoring {

        /**
		 * Site link for check relevance posts.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected $link = '';

        /**
		 * The data from current site.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var array
		 */
        protected $data = [];

        /**
		 * Minimal posts count
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var array
		 */
        protected $minimal_posts_count = 0;

        /**
		 * Minimal posts per months
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var array
		 */
        protected $minimal_posts_per_months = WBSMD_MINIMAL_POSTS_COUNT_PER_MONTHS;

        use WbsmdUtilities;
    
        function __construct($link, $data, $custom_date) { 
            $this->link = $link;
            $this->data = $data;
            
            $custom_date = new DateTime($custom_date);
            $today = new DateTime('today');
            $interval = $custom_date->diff($today);
            $months = $interval->format('%m');

            $this->minimal_posts_count = $months * $this->minimal_posts_per_months;
        }

        function monitoring() {
            $result = [];
            $result['link'] = $this->link;
            if ( empty($this->data) ) {
                $result['result'] = ['error' => 'Плагін не встановлено або сталась непередбачувана помилка :^('];
                return $result;
            }
            unset( $this->data['setup_info'] );
            
            $result['result'] = $this->check_categories();
            return $result;
        }

        function check_categories() {
            return [
                'uk' => $this->check_site_section([
                    'news' => $this->data['news'],
                    'events' => $this->data['events']
                ]),
                'eng' => $this->check_site_section([
                    'news' => $this->data['eng_news'],
                    'events' => $this->data['eng_events']
                ]),
            ];
        }

        function check_site_section( array $data ) {
            if (!$this->check_category_exist($data['events'])) {
                return [ 'final_coefficient' => 0, 'events_exist' => false ];
            }
            $events = $this->check_category($data['events']);
            $news   = $this->check_category($data['news']);
            return [
                    'final_coefficient' => $news['coefficient'],
                    'events_exist' => true,
                    'news' => $news,
                    'events' => $events,
                ];
        }

        function check_category_exist( $data ) {
            if ( is_object($data) ) {
                return false;
            }
            return true;
        }

        function check_category( $data ) {
            if ( is_object($data) ) {
                return $this->handle_error( $data->error );
            }

            $posts_count = count( $data );
            $errors_count = $this->wbsmd_dates_check( $data );
            $percentage = $this->wbsmd_convert_to_percents( $errors_count, $posts_count );

            $category_data = [
                'coefficient' => $this->get_coefficient( $data, $percentage ),
                'percentage'  => $percentage,
                'posts_count'  => $posts_count,
                'errors_count'  => $errors_count,
                'last_post'   => ['title' => $data[0]->title, 'created' => $data[0]->created],
                'minimal_posts_count'   => $this->minimal_posts_count,
            ];

            return $category_data;
        }
        
        private function get_coefficient( $data, $percentage ) {
            $posts_count = count($data);
            switch (true) {
                // if data have only 1 post check created
                case $posts_count === 1:
                    if (strtotime($data[0]->created) > strtotime('-10days')) {
                        return 1;
                    } else { return 0; }
                // if posts count less then minimal posts count
                case $posts_count != 1 && $posts_count < $this->minimal_posts_count:
                    return 0;
                case $percentage <= 20:
                    return 1;
                case $percentage > 20 && $percentage <= 50:
                    return 0.5;
                case $percentage > 50:
                    return 0;
                default:
                    return 0;
            }
        }

        private function handle_error( $err ) {
            return [
                'error' => $this->get_error_message( $err ),
                'coefficient' => 0
            ];
        }

        private function get_error_message( $err ) {
            switch( $err ) {
                case 'category_not_found':
                    $error = 'Категорія з заданим аліасом не знайдена :(';
                    break;
                case 'posts_not_found':
                    $error = 'Не знайдено постів за заданний період :(';
                    break;
                case 'category_not_set':
                    $error = 'Аліас не заданий :(';
                    break;
                default:
                    $error  = 'Невідома помилка :O';
            }
            return $error;
        }
    }
}