<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}

// Register custom fields for the 'wbsmd_ma_links' post type
add_action('carbon_fields_register_fields', 'wbsmd_register_custom_fields');

function wbsmd_register_custom_fields() {
    // Define the field container
    Container::make('post_meta', __('CMS', 'textdomain'))
        ->where('post_type', '=', 'wbsmd_ma_links')
        ->add_fields(array(
            Field::make('radio', 'site_cms', __('Оберіть вид CMS', 'textdomain'))->add_options( array(
                'jml' => 'Joomla!',
                'wp' => 'WordPress',
            ))
        ));
}

function wbsmd_get_error_message() {
    return 'Інформація відсутня ;^(';
}

function wbsmd_custom_post_types() {
	register_post_type('wbsmd_ma_links',
		array(
			'labels'      => array(
                'name'          => __('Сайти [МА]', 'textdomain'),
                'singular_name' => __('Сайт [МА]', 'textdomain'),
                'add_new'       => __('Додати новий сайт', 'textdomain'),
                'add_new_item'  => __('Додати новий сайт', 'textdomain'),
                'edit_item'     => __('Редагувати сайт', 'textdomain'),
                'new_item'      => __('Новий сайт', 'textdomain'),
                'view_item'     => __('Переглянути сайт', 'textdomain'), 
                'search_items'  => __('Шукати сайти', 'textdomain'),
                'not_found'     => __('Сайтів не знайдено', 'textdomain'), 
                'not_found_in_trash' => __('Сайтів у кошику не знайдено', 'textdomain'), 
                'parent_item_colon'  => __('Батьківський сайт:', 'textdomain'),
                'menu_name'     => __('Сайти [МА]', 'textdomain'), 
			),
				'public'      => true,
				'has_archive' => false,
                'supports' => array(
                    'title',
                )    
		)
	);
}

function wbsmd_dates_check($data) {
    if (!$data) {
        return null;
    }
    $counter = 0;
    for($i = 0; $i < count($data); $i++ ) {

        if (!isset($data[$i+1])) {
            continue;
        }
        $date1 = new DateTime($data[$i]->created);
        $date2 = new DateTime($data[$i+1]->created);
    
        $interval = $date1->diff($date2);
        if ($interval->days >= 10) {
            $counter++;
        }
    }
    return $counter;
}

function wbsmd_get_request($link) {
    $curl  = curl_init();

    $site_cms = carbon_get_the_post_meta( 'site_cms' );
    $api_url_part = '';
    switch($site_cms) {
        case 'jml':
            $api_url_part = 'index.php?option=com_ajax&plugin=ajaxarticles&format=json';
            break;
        case 'wp':
            $api_url_part = '/wp-json/websumdu/v1/monitoring';
            break;
    }

    $url = $link . $api_url_part ;
 
    $headers = [
    'Accept: application/vnd.api+json',
    'Content-Type: application/json',
    ];
    
    curl_setopt_array( $curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => 'utf-8',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_2TLS,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => $headers,
        ]
    );

    curl_close($curl);
    return curl_exec( $curl );
}

function wbsmd_add_theme_scripts() {
    /* 
     * include styles
     */
	wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/style.min.css' );
	wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap' );
}

add_action('init', 'wbsmd_custom_post_types');
add_action( 'wp_enqueue_scripts', 'wbsmd_add_theme_scripts' );

add_theme_support( 'custom-logo' );
add_theme_support( 'post-thumbnails' );