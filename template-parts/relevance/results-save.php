<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$custom_date = isset($args['custom_date']) ? $args['custom_date'] : '';
$group_name  = isset($_GET['group_name'])  ? $_GET['group_name']  : '';
?>
<div class="item-list">
<?php 
    $html = new WbsmdHtmlBuilder();
    $create_group_result = WbsmdDB::create_relevance_group($group_name);
    $is_group_exist = true;
    $group_id = 0;
    if (isset($create_group_result['errors'])) {
        $html->display_item_group($create_group_result['errors'], '', 'item--red');
        $is_group_exist = false;
    } else {
        $group_id = $create_group_result['group_id'];
    }
    if ( $posts->have_posts() && $is_group_exist)  {
        $err_counter = 0;
        while ( $posts->have_posts() ) {
            $posts->the_post();
            $http = new WbsmdHttp(
                the_title('', '', false),
                carbon_get_the_post_meta( 'site_cms' ),
                $args['custom_date']
            );
            $data = $http->get_site_data();

            $rm = new WbsmdRelevanceMonitoring(
                the_title('', '', false),
                $data,
                $args['custom_date']
            );
            $result = $rm->monitoring();
            if (!WbsmdDB::save_result_to_db($group_id, $result)) {
                $err_counter++;
            }
        }
        $html->display_item_group('Звіт збережно!', '', 'item--title');
        $html->display_item_group('Кількість помилок: ', $err_counter.' ', 'item--green');
    }
?>
</div>