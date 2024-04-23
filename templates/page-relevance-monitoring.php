<?php
/**
 * 
 * Template Name: SumDU Relevance Monitoring 
 *
 *
 * @package WordPress
 * @subpackage Sumdu_theme
 * @since Sumdu theme 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$args = array(
    'post_type' => 'wbsmd_ma_links',
    'post_status' => 'publish',
    'posts_per_page' => -1
);
$posts = new WP_Query( $args );

?>

<?php get_header(); ?>

<div class="border-header">
    <h2>Протокол моніторингу актуальності інформації</h2>
</div>

<section class="site-data">
<?php if ( $posts->have_posts() ) : ?>
    <?php while ( $posts->have_posts() ) : ?>
        <?php $posts->the_post(); ?>

        <?php 
        
        $rel_monitoring = new WbsmdRelevanceMonitoring(
            the_title('', '', false),
            carbon_get_the_post_meta( 'site_cms' )
        );
        $rel_monitoring->monitoring();
        ?>

    <?php endwhile; ?>
<?php endif; ?>
</section>
<?php get_footer(); 