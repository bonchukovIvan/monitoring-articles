<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

define('WEBSUMDU_THEME_URI', get_template_directory_uri());

define('WEBSUMDU_THEME_PATH', get_template_directory());

define('WBSMD_MINIMAL_POSTS_COUNT_PER_MONTHS', 3);

define("WBSMD_GREEN_ITEM","item--green");

define("WBSMD_ORANGE_ITEM","item--orange");

define("WBSMD_RED_ITEM","item--red");

require_once WEBSUMDU_THEME_PATH . '/inc/wbsmd-db.php';
require_once WEBSUMDU_THEME_PATH . '/inc/wbsmd-relevance-monitoring.php';
require_once WEBSUMDU_THEME_PATH . '/inc/wbsmd-localization-helper.php';
require_once WEBSUMDU_THEME_PATH . '/inc/wbsmd-html-builder.php';
require_once WEBSUMDU_THEME_PATH . '/inc/wbsmd-http.php';

require_once WEBSUMDU_THEME_PATH . '/inc/admin/wbsmd-customizer.php';

new Wbsmd_Customizer;
new WbsmdDB;

function restrict_access_for_non_logged_in_users() {
    if ( !is_user_logged_in() ) {
        if ( !is_page_template( 'templates/page-ra-results.php' ) ) {
            global $wp_query;
            $wp_query->set_404();
            status_header( 404 );
            get_template_part( 404 ); 
            exit();
        }
    }
}
add_action( 'template_redirect', 'restrict_access_for_non_logged_in_users' );


add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}
// Register custom fields for the 'wbsmd_ma_links' post type
add_action('carbon_fields_register_fields', 'wbsmd_register_custom_fields');
add_action('carbon_fields_register_fields', 'wbsmd_register_custom_fields_mk_faculty');
add_action('carbon_fields_register_fields', 'wbsmd_register_custom_fields_mk_type');

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

function wbsmd_register_custom_fields_mk_faculty() {
    // Define the field container
    Container::make('post_meta', __('Факультет', 'textdomain'))
        ->where('post_type', 'IN', array('wbsmd_mk_links', 'wbsmd_mk_value') )
        ->add_fields(array(
            Field::make('radio', 'mk_site_faculty', __('Оберіть факультет', 'textdomain'))->add_options( array(
                'all'   => 'Загальний',
                'teset' => 'TESET',
                'elit'  => 'ELIT',
                'biem'  => 'BIEM',
                'ifsk'  => 'IFSK',
                'nnip'  => 'NNIP',
                'nnmi'  => 'NNMI',
            ))
        ));
}

function wbsmd_register_custom_fields_mk_type() {
    // Define the field container
    Container::make('post_meta', __('Тип', 'textdomain'))
        ->where('post_type', '=', 'wbsmd_mk_links' )
        ->add_fields(array(
            Field::make('radio', 'mk_site_type', __('Оберіть тип сайту', 'textdomain'))->add_options( array(
                'faculty'   => 'Інституту/факультету',
                'graduation' => 'Випускова',
                'non-graduation'  => 'Невипускова',
            ))
        ));
}
add_action('carbon_fields_register_fields', 'wbsmd_register_custom_fields_mk_type_value');
function wbsmd_register_custom_fields_mk_type_value() {
    // Define the field container
    Container::make('post_meta', __('Тип', 'textdomain'))
        ->where('post_type', '=', 'wbsmd_mk_value' )
        ->add_fields(array(
            Field::make( 'checkbox', 'mk_site_check_faculty', 'Інституту/факультету' )
                ->set_option_value( 'yes' ),
            Field::make( 'checkbox', 'mk_site_check_graduation', 'Випускова' )
                ->set_option_value( 'yes' ),
            Field::make( 'checkbox', 'mk_site_check_non-graduation', 'Невипускова' )
                ->set_option_value( 'yes' ),
        ));
}

function wbsmd_register_custom_fields_mk_value() {
    // Define the field container
    Container::make('post_meta', __('Значення для пошуку', 'textdomain'))
        ->where('post_type', 'IN', array('wbsmd_mk_value'))
        ->add_fields(array(
            Field::make('complex', 'mk_site_values', __('Додайте значення до групи', 'textdomain'))
                ->add_fields(array(
                    Field::make('text', 'mk_site_value', __('Значення', 'textdomain')),
                ))
                ->set_header_template('<%- mk_site_value %>'), // Use field name as a template for the collapsed header
        ));
}
add_action('carbon_fields_register_fields', 'wbsmd_register_custom_fields_mk_value');
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
    register_post_type('wbsmd_mk_links',
    array(
        'labels'      => array(
            'name'          => __('Сайти [МК]', 'textdomain'),
            'singular_name' => __('Сайт [МК]', 'textdomain'),
            'add_new'       => __('Додати новий сайт', 'textdomain'),
            'add_new_item'  => __('Додати новий сайт', 'textdomain'),
            'edit_item'     => __('Редагувати сайт', 'textdomain'),
            'new_item'      => __('Новий сайт', 'textdomain'),
            'view_item'     => __('Переглянути сайт', 'textdomain'), 
            'search_items'  => __('Шукати сайти', 'textdomain'),
            'not_found'     => __('Сайтів не знайдено', 'textdomain'), 
            'not_found_in_trash' => __('Сайтів у кошику не знайдено', 'textdomain'), 
            'parent_item_colon'  => __('Батьківський сайт:', 'textdomain'),
            'menu_name'     => __('Сайти [МК]', 'textdomain'), 
        ),
            'public'      => true,
            'has_archive' => false,
            'supports' => array(
                'title',
            )    
        )
    );
    register_post_type('wbsmd_mk_value',
    array(
        'labels'      => array(
            'name'          => __('Групи значень [МК]', 'textdomain'),
            'singular_name' => __('Група [МК]', 'textdomain'),
            'add_new'       => __('Додати нову групу', 'textdomain'),
            'add_new_item'  => __('Додати нову групу', 'textdomain'),
            'edit_item'     => __('Редагувати групу', 'textdomain'),
            'new_item'      => __('Нову групу', 'textdomain'),
            'view_item'     => __('Переглянути групу', 'textdomain'), 
            'search_items'  => __('Шукати групи значень', 'textdomain'),
            'not_found'     => __('Груп не знайдено', 'textdomain'), 
            'not_found_in_trash' => __('Груп у кошику не знайдено', 'textdomain'), 
            'parent_item_colon'  => __('Батьківська група :', 'textdomain'),
            'menu_name'     => __('Групи значень [МК]', 'textdomain'), 
        ),
            'public'      => true,
            'has_archive' => false,
            'supports' => array(
                'title',
            )    
        )
    );
}

function display_array($arr) {
    echo '<pre>' . print_r($arr, 1) . '</pre>';
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