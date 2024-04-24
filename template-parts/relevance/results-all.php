<div class="item-list">
    <?php 
        if ( $posts->have_posts() )  {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                $rel_monitoring = new WbsmdRelevanceMonitoring(
                    the_title('', '', false),
                    carbon_get_the_post_meta( 'site_cms' )
                );
                $rel_monitoring->monitoring();        
            }
        }
    ?>
</div>