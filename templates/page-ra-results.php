<?php
/**
 * 
 * Template Name: SumDU Relevance Monitoring Results
 *
 *
 * @package WordPress
 * @subpackage Sumdu_theme
 * @since Sumdu theme 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function get_relevance_monitoring_records_by_group_id($group_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'relevance_monitoring';

    $sql = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE group_id = %d",
        $group_id
    );

    $results = $wpdb->get_results($sql);

    return $results;
}

$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : null;
$results = get_relevance_monitoring_records_by_group_id($group_id);
?>
<?php get_header(); ?>
<div class="border-header">
    <h2>Протокол моніторингу актуальності інформації</h2>
</div>
<?php 
    if (empty($_GET)) {
        get_template_part('template-parts/relevance/results', 'error');
    }
    else {
        get_template_part('template-parts/relevance/results', 'saved', [
            'results' => $results
        ]);
    }
?>
</div>
<?php get_footer(); ?>