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

if ( isset($_GET['remove']) && $_GET['remove'] === '1' && isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];
    WbsmdDB::delete_group( $group_id );

    $object_id = get_queried_object_id();
    wp_redirect(get_permalink($object_id));
}

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
                echo '<div class="group">'; 
                    echo '<a href="'.$link.'" target="_blank">';
                        $html->display_item_group($result->group_name, '', 'item--title');
                    echo '</a>';
                    echo ' <input type="hidden" id="group_id" name="group_id" value="'.$result->id.'" />';
                    echo '<button class="remove-group-record">Видалити</button>';
                echo '</div>';
        echo '</div>';
    }
?>
</div>
<?php get_footer(); ?>