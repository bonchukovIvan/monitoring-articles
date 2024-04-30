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
$post_in = [];
$custom_date = '';
if (isset($_GET['foo'])) {
    foreach($_GET['foo'] as $id)  array_push($post_in, $id);
}
if (isset($_GET['custom_date'])) {
    $custom_date = $_GET['custom_date'];
}
$args = array(
    'post_type' => 'wbsmd_ma_links',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'post__in' => $post_in
);
$posts = new WP_Query( $args );

?>

<?php get_header(); ?>

<div class="border-header">
    <h2>Протокол моніторингу актуальності інформації</h2>
</div>

    <?php 

        if (empty($_GET)) {
            get_template_part('template-parts/relevance/settings', 'init');
        }
        elseif ($_GET['type'] === 'all') {
            get_template_part('template-parts/relevance/settings', 'init');
            get_template_part('template-parts/relevance/results', 'all', [
                'custom_date' => $custom_date
            ]);
        }
        elseif ($_GET['type'] === 'partially' && isset($_GET['run']) && isset($_GET['run'])) {
            get_template_part('template-parts/relevance/settings', 'init');
            get_template_part('template-parts/relevance/results', 'all',[
                'custom_date' => $custom_date
            ]);
        }
        elseif ($_GET['type'] === 'partially') {
            get_template_part('template-parts/relevance/settings', 'init');
        }

    ?>
<?php if (!empty($_GET) && $_GET['type'] === 'all') : ?>
</div>
<?php endif; ?>
<?php get_footer(); ?>