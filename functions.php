<?php

function wbsmd_get_error_message() {
    return 'Інформація відсутня ;^(';
}

function wbsmd_custom_post_types() {
	register_post_type('wbsmd_ma_links',
		array(
			'labels'      => array(
				'name'          => __('Сайти [МА]', 'textdomain'),
				'singular_name' => __('Сайт [МА]', 'textdomain'),
			),
				'public'      => true,
				'has_archive' => false,
                'supports' => array(
                    'title',
                    'excerpt',
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
    $url   = $link .'index.php?option=com_ajax&plugin=ajaxarticles&format=json';
 
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
	wp_enqueue_style( 'reset', get_template_directory_uri() . '/assets/css/reset.css' );
	wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/style.css' );
	wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap' );
}

add_action('init', 'wbsmd_custom_post_types');
add_action( 'wp_enqueue_scripts', 'wbsmd_add_theme_scripts' );

add_theme_support( 'custom-logo' );
add_theme_support( 'post-thumbnails' );