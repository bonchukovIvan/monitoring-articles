<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

define('WEBSUMDU_THEME_URI', get_template_directory_uri());

define('WEBSUMDU_THEME_PATH', get_template_directory());

require_once WEBSUMDU_THEME_PATH . '/inc/wbsmd-relevance-monitoring.php';

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

function wbsmd_get_error_message() {
    return 'Інформація відсутня ;^(';
}

register_nav_menus([
    'main_header_menu' => 'Головне меню сайту'
]);

add_action( 'wp_ajax_nopriv_get_data', 'my_ajax_handler' );
add_action( 'wp_ajax_get_data', 'my_ajax_handler' );

function my_ajax_handler() {
    if (!empty($_REQUEST['foo'])) {
        print 'Foo: '.$_REQUEST['foo'];
    }
    wp_die();
}
function wbsmd_add_theme_scripts() {
    /* 
     * include styles
     */
	wp_enqueue_style( 'style', WEBSUMDU_THEME_URI . '/assets/css/style.min.css' );
	wp_enqueue_style( 'lora-font', 'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap' );
    /* 
     * register jquery
     */
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', WEBSUMDU_THEME_URI . '/assets/js/src/jquery-3.7.1.min.js' );
    /* 
     * include scripts
     */
    wp_enqueue_script( 'wp-util' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'app', WEBSUMDU_THEME_URI . '/assets/js/app.min.js', ['jquery', 'wp-util'] );
}

add_action('init', 'wbsmd_custom_post_types');
add_action( 'wp_enqueue_scripts', 'wbsmd_add_theme_scripts' );

add_theme_support( 'custom-logo' );
add_theme_support( 'post-thumbnails' );
add_theme_support('menus');