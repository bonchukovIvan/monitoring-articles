<?php
/**
 * Front page
 *
 * @package WordPress
 * @subpackage Sumdu_theme
 * @since Sumdu theme 1.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$menuLocations = get_nav_menu_locations();

$menuID = $menuLocations['main_header_menu'];

$menu = wp_get_nav_menu_items($menuID);

?>

<?php get_header(); ?>
<div class="border-header">
    <h2> <?php the_title(); ?></h2>
</div>



<section class="wbsmd-cards">
    <div class="wbsmd-cards__body">
        <?php foreach ($menu as $item) : ?>  
            <a href="<?php echo $item->url; ?>" class="wbsmd-cards__link">
            <div class="wbsmd-cards__item">
                <div class="wbsmd-cards__item-body">
                    <div class="wbsmd-cards__title">
                        <h4><?php echo $item->title; ?></h4>
                    </div>
                </div>
            </div>
            </a> 
        <?php endforeach; ?>
    </div>
</section>
<?php get_footer(); ?>