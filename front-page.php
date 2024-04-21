<?php
/**
 * Template part for displaying site header
 *
 *
 * @package WordPress
 * @subpackage Sumdu_theme
 * @since Sumdu theme 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('COMPARE_DATE', '-10 days midnight');

$args = array(
    'post_type' => 'wbsmd_ma_links',
    'post_status' => 'publish',
    'posts_per_page' => -1
);
$posts = new WP_Query( $args );

function wbsmd_choice_item_class($result) {

    if ($result < 30) {
        return 'item--green';
    } 

    elseif ($result >= 30 && $result < 90) {
        return'item--orange';
    } 

    elseif ($result > 90) {
        return 'item--red';
    } 

    return null;
}

function wbsmd_convert_to_percents($f, $s) {
    return number_format((float)($f/$s)*100, 2, '.', '');
}

function wbsmd_check_category($data, $name) {
    switch( $name ) {
        case 'news':
            $title = 'Новини';
            break;
        case 'eng_news':
            $title = 'Англомовні новини';
            break;
        case 'events':
            $title = 'Анонси';
            break;
        case 'eng_events':
            $title = 'Англомовні анонси';
            break;
        default:
            $title  = '';
    }

    wbsmd_create_item_group(
        strtoupper($title), 
        ''
    );

    $last_class = (strtotime($data[0]->created) > strtotime('-10days')) 
    ? 'item--green' : 'item--red' ;

    if ( count($data) != 1 ) {
        $counter = wbsmd_dates_check($data);
        $result = wbsmd_convert_to_percents($counter, count($data)-1);
        $class = wbsmd_choice_item_class($result);
    
        wbsmd_create_item_group(
            'Кількість інтервалів в більше ніж 10 днів:', 
            $counter.' з '.count($data)-1 . '[' . $result .'%]',
            $class
        );
        wbsmd_create_item_group(
            'Останній пост: <span>'.$data[0]->title.'</span>', 
            $data[0]->created,
            $last_class
        );
    } else {
        wbsmd_create_item_group(
            'Знайдено 1 запис:', 
            $data[0]->created,
            $last_class
        );
    }
    echo '<hr>';
}

function wbsmd_check_categories($data) {
    foreach($data as $key => $value) {
        if ( isset($value->error) ) {
            wbsmd_create_item_group(
                'Виникла помилка ['.$key.']:',
                 $value->error,
                'item--red');
            continue;
        }
        wbsmd_check_category($value, $key);
    }
}

function wbsmd_create_item_group($prop_name, $prop, $class = '') {
    echo '<div class="item__group '. $class .'">';
        echo '<div class="item__prop-name">' . $prop_name . '</div> ';
        echo '<div class="item__prop">' . $prop . '</div> ';
    echo '</div>';
}
function wbsmd_display_setup($setup_info) {
    switch(carbon_get_the_post_meta( 'site_cms' )) {
        case 'jml':
            $cms = 'Joomla!';
            break;
        case 'wp':
            $cms = 'WordPress';
            break;
    }
    wbsmd_create_item_group( 'CMS:', $cms );
    foreach($setup_info as $key => $value) {
        wbsmd_create_item_group( $key, $value );
    }
}

function wbsmd_display_more($setup_info) {
    echo '<div class="more-btn">';
        echo '<button>Детальніше</button>';
        echo '<span class="arrow down"></span>';
    echo '</div>';
    echo '<div class="item__more-section"  id="item-more-section">';
        wbsmd_display_setup($setup_info);
    echo ' </div>';
}
?>

<?php get_header(); ?>

<div class="border-header">
    <h2>Звіт по сайтам</h2>
</div>

<section class="site-data">
<?php if ( $posts->have_posts() ) : ?>
    <?php while ( $posts->have_posts() ) : ?>
        <?php $posts->the_post(); ?>
            <div class="item">
            <?php 

                $response_decode = json_decode( wbsmd_get_request( the_title( '', '', false ) ) );
                $data = (array) $response_decode->data[0];

                $setup_info = $data['setup_info'];
                unset( $data['setup_info'] );

                if ( !isset( $response_decode->data ) ) {
                    echo '<div class="item no-data">';
                        echo the_title() . '<span> Помилка :( </span>'.'<br>';
                    echo '</div>';
                    continue;
                }

                wbsmd_create_item_group('Ресурс:', the_title('', '', false));
                wbsmd_create_item_group('Дата з якої ведеться перевірка:', $setup_info->start_date);
                echo '<hr>';
                wbsmd_check_categories($data);
                wbsmd_display_more($setup_info);
            ?>
        </div>

    <?php endwhile; ?>
<?php endif; ?>
</section>
<?php get_footer(); 