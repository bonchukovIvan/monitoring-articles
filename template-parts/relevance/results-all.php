<div class="item-list">
    <?php 
    if (isset($args['custom_date'])) {
        $custom_date = $args['custom_date'];
    }
        if ( $posts->have_posts() )  {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                // $rel_monitoring = new WbsmdRelevanceMonitoring(
                //     the_title('', '', false),
                //     carbon_get_the_post_meta( 'site_cms' ),
                //     $args['custom_date']
                // );
                // $rel_monitoring->monitoring();
                the_title();
                $html_builder = new WbsmdHtmlBuilder();
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
                echo '<pre>' . print_r($rm->monitoring(), 1) . '</pre>';
            }
        }
    ?>
</div>