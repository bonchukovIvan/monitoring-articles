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

$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : null;
$results = WbsmdDB::get_relevance_monitoring_records_by_group_id($group_id);
$group_name = WbsmdDB::get_relevance_monitoring_group_record($group_id)[0]->group_name;
?>
<?php get_header(); ?>
<div class="border-header">
    <h2>Протокол моніторингу актуальності інформації</h2>
</div>
<h1>
    <?php echo $group_name; ?>
</h1>
<?php  get_template_part('template-parts/relevance/settings', 'legend'); ?>
<?php 
    if (empty($_GET)) {
        get_template_part('template-parts/relevance/results', 'error');
    }
    else {
        get_template_part('template-parts/relevance/results', 'saved', [
            'results' => $results,
            'group_name' => $group_name
        ]);
    }
?>
</div>
<?php get_footer(); ?>