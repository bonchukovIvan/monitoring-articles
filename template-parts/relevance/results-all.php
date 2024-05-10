<div class="item-list">
    <?php 
    if (isset($args['custom_date'])) {
        $custom_date = $args['custom_date'];
    }
        if ( $posts->have_posts() )  {
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

                $html = new WbsmdHtmlBuilder();
                echo '<div class="item">';  
                    $html->display_item_group( WbsmdLocalizationHelper::remove_symbol_from_url($result['link']), '', 'item--title');
                    if (isset($result['result']['error'])) {
                        $html->display_item_group('Помилка:', $result['result']['error'], WBSMD_RED_ITEM);
                        echo '</div>';
                        continue;
                    }
                    foreach ($result['result'] as $section_name => $data) {
                        $item_class = WbsmdLocalizationHelper::choice_item_class_by_coeff($data['final_coefficient']);
                        echo '<div class="item__section">';
                            $section_title = WbsmdLocalizationHelper::get_section_title($section_name);
                            $html->display_item_group($section_title.' версія вебсайту', '', 'item--title');
                            $html->display_item_group('Фінальний коефіцієнт:', $data['final_coefficient'].' ', 'item--coeff '.$item_class);
                            unset($data['final_coefficient']);
                            echo '<div class="more-btn"><button>Детальніше</button><span class="arrow down"></span></div>';
                            echo '<div class="item__expand">';
                                echo '<div class="item__expand-body">';
                                foreach($data as $category_name => $value) {
                                        $category_title = WbsmdLocalizationHelper::get_cat_title( $category_name );
                                        if (isset($value['error'])) {
                                            $html->display_item_group($category_title);
                                            $html->display_item_group('Коефіцієнт актуальності: ', $value['coefficient'].' ', WBSMD_RED_ITEM);
                                            $html->display_item_group('Помилка: ', $value['error'], WBSMD_RED_ITEM);
                                            echo '<hr>';
                                            continue;
                                        }
                         
                                        $last_post_class  = strtotime( $value['last_post']['created']) > strtotime('-10days') ? WBSMD_GREEN_ITEM : WBSMD_RED_ITEM;
                                        $post_class = WbsmdLocalizationHelper::choice_item_class($value['percentage']);
                                        $item_errors_info = $value['errors_count'] . ' з '. $value['posts_count'] . '[' . $value['percentage'] .']';

                                        $html->display_item_group($category_title.' (кількість публікацій за період: '.$value['posts_count'].')');
                                        if ($value['posts_count'] < $value['minimal_posts_count']) {
                                            $post_class = WBSMD_RED_ITEM;
                                        }
                                        $html->display_item_group('Коефіцієнт актуальності: ', $value['coefficient'].' ', $post_class);
                                        if ($value['posts_count'] < $value['minimal_posts_count']) {
                                            $post_class = WBSMD_RED_ITEM;
                                            $html->display_item_group('Кількість публікацій менша за заданий мінімум :(', '', WBSMD_RED_ITEM);
                                        }
                                        else {
                                            $html->display_item_group('Кількість порушень режиму публікації (10 днів): ', $item_errors_info, $post_class);
                                        }
                                        $html->display_item_group('Крайня публікація: <span>'.$value['last_post']['title'].'</span>', $value['last_post']['created'], $last_post_class);
                                        
                                        echo '<hr>';
                                    
                                }
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                echo '</div>';
            }
        }
    ?>
</div>