<?php
/**
 * 
 * Template Name: SumDU Relevance Monitoring Groups List
 *
 *
 * @package WordPress
 * @subpackage Sumdu_theme
 * @since Sumdu theme 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$protocol_link = get_theme_mod( 'basic-general-callout-protocol' );

$results = WbsmdDB::get_relevance_monitoring_records();
?>
<?php get_header(); ?>
<div class="border-header">
    <h2>Список звітів</h2>
</div>
<h3>Натисніть на назву звіту, щоб отримати версію для перегляду сторонніми користувачами</h3>
<div class="item-list">
<?php 
    $html = new WbsmdHtmlBuilder();
    foreach($results as $result) {
        echo '<div class="item">'; 
        $link = '/'.$protocol_link.'?group_id='.$result->id;
        echo '<a href="'.$link.'" target="_blank">';
        $html->display_item_group($result->group_name, '', 'item--title');
        echo '</a>';
        echo '</div>'; 
    }
?>
</div>
<?php get_footer(); ?>