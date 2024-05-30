<div class="item-list">
<?php 
    if (isset($args['custom_date'])) {
        $custom_date = $args['custom_date'];
    }

    $group_name = 'test36e3e';
    $create_group_result = WbsmdRelevanceMonitoring::create_relevance_group($group_name);
    $is_group_exist = true;
    $group_id = 0;
    if (isset($create_group_result['errors'])) {
        $html->display_item_group($create_group_result['errors'], '', 'item--red');
        $is_group_exist = false;
    } else {
        $group_id = $create_group_result['group_id'];
    }
    if ( $posts->have_posts() && $is_group_exist)  {
        while ( $posts->have_posts() ) {
            $posts->the_post();
            print_r($group_id);
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
            WbsmdRelevanceMonitoring::save_result_to_db($group_id, $result);
        }
    }
?>

</div>