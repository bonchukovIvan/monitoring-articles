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

?>

<?php get_header(); ?>

<div class="border-header">
    <h2>Звіт по сайтам</h2>
</div>
<section class="site-data">

  <div class="item-list" id="news">
        <div class="border-header">
            <h3>Новини</h3>
        </div>
        <?php if ( $posts->have_posts() ) : ?>
            <?php while ( $posts->have_posts() ) : ?>
                <?php $posts->the_post();?>
                <?php 

                    $response_decode = json_decode( wbsmd_get_request(the_title('', '', false)) );
                    $data = (object) $response_decode->data[0];

                    $counter = wbsmd_dates_check($data->news);

                    $result = ($counter/count($data->news))*100;
                    $result = number_format((float)$result, 2, '.', '');

                    $add_class = '';
                    if ($result < 30) {
                        $add_class = 'item--green';
                    } 
                    elseif ($result >= 30 && $result < 90) {
                        $add_class = 'item--orange';
                    } 
                    elseif ($result > 90) {
                        $add_class = 'item--red';
                    } 

                ?>
                <div class="item <?php echo $add_class; ?>">
                    <div class="item__body">
                        <div class="item__group">
                            <div class="item__prop-name">Ресурс:</div>      
                            <div class="item__prop"><?php the_title();?></div>      
                        </div>
                        <div class="item__group">
                            <div class="item__prop-name">Коефіцієнт помилок:</div>      
                            <div class="item__prop"><?php echo $result; ?></div>      
                        </div>

                    </div>
                </div>
                <?php   endwhile; ?>
            <?php  endif; ?>
            <?php wp_reset_query(); ?>
        <?php if(!$data->news) : ?>
            <div class="item--error">
                <div class="item__body">
                    <?php echo wbsmd_get_error_message(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div> 


    <div class="item-list" id="events">
        <div class="border-header">
            <h3>Події</h3>
        </div>
        <?php if ( $posts->have_posts() ) : ?>
            <?php while ( $posts->have_posts() ) : ?>
                <?php $posts->the_post();?>
                <?php 

                    $counter = wbsmd_dates_check($data->events);
                    $result = ($counter/count($data->events))*100;
                    $result = number_format((float)$result, 2, '.', '');

                    $add_class = '';
                    if ($result < 30) {
                        $add_class = 'item--green';
                    } 
                    elseif ($result >= 30 && $result < 90) {
                        $add_class = 'item--orange';
                    } 
                    elseif ($result > 90) {
                        $add_class = 'item--red';
                    } 

                ?>
                <div class="item <?php echo $add_class; ?>">
                    <div class="item__body">
                        <div class="item__group">
                            <div class="item__prop-name">Ресурс:</div>      
                            <div class="item__prop"><?php the_title();?></div>      
                        </div>
                        <div class="item__group">
                            <div class="item__prop-name">Коефіцієнт помилок:</div>      
                            <div class="item__prop"><?php echo $result; ?>%</div>      
                        </div>

                    </div>
                </div>
                <?php   endwhile; ?>
            <?php  endif; ?>
            <?php wp_reset_query(); ?>
        <?php if(!$data->events) : ?>
            <div class="item--error">
                <div class="item__body">
                    <?php echo wbsmd_get_error_message(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>1