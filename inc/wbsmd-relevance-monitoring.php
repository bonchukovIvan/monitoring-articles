<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once WEBSUMDU_THEME_PATH . '/inc/utilities.php';

if ( ! class_exists( 'WbsmdRelevanceMonitoring' ) ) {

    class WbsmdRelevanceMonitoring {

        use WbsmdUtilities;

        /*
         *  Endpoint for wordpress sites
         */
        const WORDPRESS_ENDPOINT = '/wp-json/websumdu/v1/monitoring';

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
		 * The type of sites CMS.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected $site_cms = '';

        /**
		 * The endpoint for current site.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected $endpoint = '';

        /**
		 * The method for request to site endpoint.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected $method = 'GET';
        /**
		 * The date of start monitoring period.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var string
		 */
        protected  $custom_date = 'first day of january this year';

        /**
		 * The data from current site.
		 *
		 * @access protected
		 * @since 1.0.0
		 * @var array
		 */
        protected $data = [];
    
        function __construct(
                string $link, 
                string $site_cms,
                string $custom_date = '',
            ) {
            $this->link = $link;
            $this->site_cms = $site_cms;
            
            if ($custom_date != '') {
                $this->custom_date = $custom_date;
            }

            switch($this->site_cms) {
                case 'jml':
                    $this->endpoint = self::JOOMLA_ENDPOINT . urlencode($this->custom_date);
                    break;
                case 'wp':
                    $this->endpoint = self::WORDPRESS_ENDPOINT;
                    $this->method = 'POST';
                    break;
            }

        }
        
        function get_data() {
            return $this->data;
        }

        function get_request() {
            $curl  = curl_init();

            $url = $this->link . $this->endpoint;
            
            curl_setopt_array( $curl, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => 'utf-8',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_2TLS,
                CURLOPT_CUSTOMREQUEST  => $this->method,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                ],
            ]);
            if ($this->site_cms === 'wp') {
                $post_data = json_encode([
                    'custom_date' => $this->custom_date
                ]);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data); 
            }

            $response = json_decode( curl_exec( $curl ) );

            curl_close( $curl );

            if ($this->site_cms === 'wp' && isset($response->data->status)) {
                return false;
            }
            if ($this->site_cms === 'jml' && empty($response->data)) {
                return false;
            } else {
                $this->data = (array) $response->data[0];
                return true;
            }

        }

        function display_item_group($prop_name, $prop, $class = '') {
            echo '<div class="item__group '. $class .'">';
                echo '<div class="item__prop-name">' . $prop_name . '</div> ';
                if ($prop) {
                    echo '<div class="item__prop">' . $prop . '</div> ';
                }
            echo '</div>';
        }

        function display_setup($setup_info) {
            switch(carbon_get_the_post_meta( 'site_cms' )) {
                case 'jml':
                    $cms = 'Joomla!';
                    break;
                case 'wp':
                    $cms = 'WordPress';
                    break;
            }
            $this->display_item_group( 'CMS:', $cms );
            foreach($setup_info as $key => $value) {
                $this->display_item_group( $key, $value );
            }
        }

        private function display_setup_info($setup_info) {
            echo '<div class="more-btn">';
                echo '<button>Детальніше</button>';
                echo '<span class="arrow down"></span>';
            echo '</div>';
            echo '<div class="item__more-section" id="item-more-section">';
            $this->display_setup($setup_info);
            echo '</div>';
        }
        
        function get_cat_title( $name ) {
            switch( $name ) {
                case 'news':
                    $title = 'Новини';
                    break;
                case 'eng_news':
                    $title = 'Новини';
                    break;
                case 'events':
                    $title = 'Анонси';
                    break;
                case 'eng_events':
                    $title = 'Анонси';
                    break;
                default:
                    $title  = '';
            }
            return $title;
        }

        function check_category($data, $name) {
        
            $this->display_item_group(
                $this->get_cat_title( $name ).' (кількість публікацій за період: '.count($data).')', 
                ''
            );
        
            $last_class = (strtotime($data[0]->created) > strtotime('-10days')) 
            ? 'item--green' 
            : 'item--red' ;
        
            if ( count($data) != 1 ) {
                $counter = $this->wbsmd_dates_check($data);
                $percentage = $this->wbsmd_convert_to_percents($counter, count($data)-1);
                $class = $this->wbsmd_choice_item_class($percentage);
                
                $result = 0;

                if ($percentage <= 10) {
                    $result = 1;
                } 
            
                elseif ($percentage > 10 && $percentage < 60) {
                    $result = 0.5;
                } 
            
                elseif ($percentage > 60) {
                    $result = 0;
                } 

                $this->display_item_group(
                    'Кількість порушень режиму публікації (10 днів):', 
                    $counter.' з '.count($data)-1 . '[ ' . $result .' ]',
                    $class
                );

                $this->display_item_group(
                    'Крайня публікація: <span>'.$data[0]->title.'</span>', 
                    $data[0]->created,
                    $last_class
                );
                
            } else {
                $this->display_item_group(
                    'Знайдено 1 запис: <span>'.$data[0]->title.'</span>', 
                    $data[0]->created,
                    $last_class
                );
            }
            echo '<hr>';
        }

        function check_categories() {
            $c = 1;
            foreach ($this->data as $key => $value ) {
                if ($c === 1) {
                    echo '<div class="item__section">';
                    $this->display_item_group(
                        'Україномовна версія вебсайту',
                        '',
                        'item--title'
                    );
                } elseif($c === 3) {
                    echo '<div class="item__section">';
                    $this->display_item_group(
                        'Англомовна версія вебсайту',
                        '',
                        'item--title'
                    );
                }

                if ( isset($value->error) ) {
                    $c++;
                    switch( $value->error ) {
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
                    $this->display_item_group(
                        $this->get_cat_title( $key ),
                        ''
                    );
                    $this->display_item_group(
                        'Виникла помилка:',
                        $error,
                        'item--red'
                    );
                    echo '<hr>';
                    if ($c === 3) {
                        echo '</div>';
                    } elseif($c === 5) {
                        echo '</div>';
                    }
                    continue;
  
                }
                $this->check_category( $value, $key );
                $c++;
                if ($c === 3) {
                    echo '</div>';
                } elseif($c === 5) {
                    echo '</div>';
                }
      
            }
        }

        function monitoring($is_single = false) {
            $this->get_request();
            if ( !$this->data ) {
                echo '<div class="item no-data">';
                    echo the_title() . '<span> Помилка :( </span>'.'<br>';
                echo '</div>';

                return false;
            }
            $setup_info = $this->data['setup_info'];
            unset( $this->data['setup_info'] );

            echo '<div class="item">';
            if (!$is_single) {
                echo '<a href="'.get_permalink().'">';
                $this->display_item_group('Ресурс:', $this->link, 'item--compact');
                echo '</a>';
            }
                $this->display_item_group(
                    'Дата початку періоду моніторингу:', 
                    $setup_info->start_date, 
                    'item--compact');
                echo '<hr>';
                $this->check_categories();
            if (!$is_single) {
                $this->display_setup_info($setup_info);
            }

            echo '</div>';

            return true;
        }
    }
}
