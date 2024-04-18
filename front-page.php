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

?>

<?php get_header(); ?>

<div class="border-header">
    <h2>Звіт по сайтам</h2>
</div>

<section class="site-data">
<?php if ( $posts->have_posts() ) : ?>
    <?php while ( $posts->have_posts() ) : ?>
        <?php $posts->the_post(); ?>
        <?php 
            $response_decode = json_decode( wbsmd_get_request( the_title( '', '', false ) ) );

            if ( !isset( $response_decode->data ) ) {
                echo '<div class="item no-data">';
                    echo the_title() . '<span> Помилка :( </span>'.'<br>';
                echo '</div>';
                continue;
            }

            $data = ( object ) $response_decode->data[0];
            if (!$data->news || !$data->events) {
                echo 'no news or events data' . '<br>';
                continue;
            }
            $news_counter = wbsmd_dates_check($data->news);
            $news_result = wbsmd_convert_to_percents($news_counter, count($data->news));
            $news_class = wbsmd_choice_item_class($news_result);

            $events_counter = wbsmd_dates_check($data->events);
            $events_result = wbsmd_convert_to_percents($events_counter, count($data->events));
            $events_class = wbsmd_choice_item_class($events_result);
            $last_news_class = (strtotime($data->news[0]->created) > strtotime('-10days')) ? 'item--green' : 'item--red' ;
            $last_events_class = (strtotime($data->events[0]->created) > strtotime('-10days')) ? 'item--green' : 'item--red' ;

            switch(carbon_get_the_post_meta( 'site_cms' )) {
                case 'jml':
                    $cms = 'Joomla!';
                    break;
                case 'wp':
                    $cms = 'WordPress';
                    break;
            }
        ?>

        <div class="item">
            <div class="item__group">
                <div class="item__prop-name">Ресурс:</div>    
                <div class="item__prop"><?php the_title();?></div>      
            </div>
            <div class="item__group">
                <div class="item__prop-name">CMS:</div>
                <div class="item__prop"><?php echo $cms;?></div>      
            </div>
            <div class="item__group">
                <div class="item__prop-name">Пропущено новин:</div>      
                <div class="item__prop"><?php echo $news_counter ?> з <?php echo count($data->news); ?> [<?php echo $news_result; ?>%]</div>      
            </div>
            <div class="item__group">
                <div class="item__prop-name">Пропущено подій:</div>      
                <div class="item__prop"><?php echo $events_counter ?> з <?php echo count($data->events); ?> [<?php echo $events_result; ?>%]</div>      
            </div>
            <div class="item__group <?php echo $last_events_class; ?>">
                <div class="item__prop-name">Остання подія: <span><?php echo $data->events[0]->title; ?></span></div>      
                <div class="item__prop"><?php echo $data->events[0]->created; ?></div>      
            </div>
            <div class="item__group <?php echo $last_news_class; ?>">
                <div class="item__prop-name">Остання новина: <span><?php echo $data->news[0]->title; ?></span></div>      
                <div class="item__prop"><?php echo $data->news[0]->created; ?></div>
            </div>
        </div>

    <?php endwhile; ?>
<?php endif; ?>
</section>
<?php get_footer(); 