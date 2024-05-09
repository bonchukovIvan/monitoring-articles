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
		 * The data from current site.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var array
		 */
        protected $minimal_posts_count = 0;

        /**
		 * The data from current site.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var array
		 */
        protected $minimal_posts_per_months = 3;

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
                $result['result'] = ['error' => 'empty_data'];
                return $result;
            }
            unset( $this->data['setup_info'] );
            
            $result['result'] = $this->check_categories();
            return $result;
        }

        function check_categories() {
            $uk_section = [
                'news'   => $this->check_category($this->data['news']), 
                'events' => $this->check_category($this->data['events'])
            ];
            $eng_section = [
                'eng_news'   => $this->check_category($this->data['eng_news']), 
                'eng_events' => $this->check_category($this->data['eng_events'])
            ];
            return ['uk' => $uk_section, 'eng'=> $eng_section];
        }

        function check_category( $data ) {
            if (is_object($data)) {
                return $this->handle_error($data->error);
            }
            $all_er = $this->wbsmd_dates_check($data);
            $percentage = $this->wbsmd_convert_to_percents($all_er, count($data));
            $category_data = [
                'coefficient' => $this->get_coefficient($data, $percentage),
                'percentage'  => $percentage,
                'all_er'  => $all_er,
                'post_count'  => count($data),
                'last_post'   => [ 'title' => $data[0]->title, 'created' => $data[0]->created ],
            ];
            return $category_data;
        }
        
        function get_coefficient( $data, $percentage ) {
            if ( count($data) == 1 ) {
                if (strtotime($data[0]->created) > strtotime('-10days')) {
                    return 1;
                } else {
                    return 0;
                }
            }
            if ( count($data) != 1 && count($data) < $this->minimal_posts_count ) {
                return 0;
            }
            if ($percentage <= 10) {
                return 1;
            }
            elseif ($percentage > 10 && $percentage <= 40) {
                return 0.5;
            }
            elseif ($percentage > 40) {
                return 0;
            }
        }

        function handle_error( $err ) {
            return ['error' => $this->get_error_message( $err )];
        }

        function get_error_message( $err ) {
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
