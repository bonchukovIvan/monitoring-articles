<header class="wbsmd-header">
    <div class="wrapper">
        <div class="wbsmd-header__body">
            <div class="wbsmd-header__logo">
                <a href="/" title="Sumy State University">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-sumdu-circle.svg" alt="Logo SumDU">
                </a>
            </div>

            <?php wp_nav_menu([
                    'menu'                 => '',
                    'container'            => 'div',
                    'container_class'      => 'wbsmd-header__menu-container',
                    'container_id'         => '',
                    'container_aria_label' => '',
                    'menu_class'           => 'wbsmd-header__menu',
                    'menu_id'              => '',
                    'echo'                 => true,
                    'fallback_cb'          => 'wp_page_menu',
                    'before'               => '',
                    'after'                => '',
                    'link_before'          => '',
                    'link_after'           => '',
                    'items_wrap'           => '<ul id="%1$s" class="%2$s wbsmd-menu">%3$s</ul>',
                    'item_spacing'         => 'preserve',
                    'depth'                => 0,
                    'walker'               => '',
                    'theme_location'       => 'main_header_menu',
            ]); ?>
        </div>
    </div>
</header>