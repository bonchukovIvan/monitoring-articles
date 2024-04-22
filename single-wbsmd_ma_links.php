<?php 
/**
 * 
 * Display single wbsmd_ma_links 
 *
 *
 * @package WordPress
 * @subpackage Sumdu_theme
 * @since Sumdu theme 1.0
 */

use function PHPSTORM_META\type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php get_header(); ?>

<div class="border-header">
    <h2>Подробніше про ресурс <?php the_title();?></h2>
</div>
<?php 
$site_cms = carbon_get_the_post_meta( 'site_cms' );

$rel_monitoring = new WbsmdRelevanceMonitoring(
    the_title('', '', false), 
    carbon_get_the_post_meta( 'site_cms' )
);
$rel_monitoring->get_request();
$data = $rel_monitoring->get_data();

switch(carbon_get_the_post_meta( 'site_cms' )) {
    case 'jml':
        $cms = 'Joomla!';
        break;
    case 'wp':
        $cms = 'WordPress';
        break;
}

?>
<section class="site-data">
<div class="border-header">
    <h3>Параметри:</h3>
</div>
<div class="item">
    <div class="item__body">
        <div class="item__group">
            <div class="item__prop-name">CMS:</div>
            <div class="item__prop"><?php echo $cms ?></div>
        </div>
    </div>
</div>
<div class="border-header">
    <h3>Налаштування плагіна на сайті:</h3>
</div>
<div class="item">
    <div class="item__body">
        <?php foreach($data['setup_info'] as $key => $value) :?>
            <div class="item__group">
                <div class="item__prop-name"><?php echo $key ?></div>
                <div class="item__prop"><?php echo $value ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php $rel_monitoring->monitoring(true); ?>
</section>

<?php get_footer(); ?>