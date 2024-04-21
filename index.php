<?php
/**
 * Template part for displaying site header
 *
 *
 * @package WordPress
 * @subpackage Sumdu_theme
 * @since Sumdu theme 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('COMPARE_DATE', '-10 days midnight')

?>

<?php get_header(); ?>

<?php 
$curl  = curl_init();

$url   = 'https://t2.sumdu.edu.ua/index.php?option=com_ajax&plugin=ajaxarticles&format=json';

$categoryId = 2;

$headers = [
'Accept: application/vnd.api+json',
'Content-Type: application/json',
];

curl_setopt_array( $curl, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => 'utf-8',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_2TLS,
        CURLOPT_CUSTOMREQUEST  => 'GET',
        CURLOPT_HTTPHEADER     => $headers,
    ]
);

$response = curl_exec( $curl );
curl_close($curl);

$response_decode = json_decode( $response );
$data = (object) $response_decode->data[0];

?>
<div class="border-header">
    <h2>t2.sumdu.edu.ua</h2>
</div>
<section class="site-data">
    <div class="item-list" id="setup_info">
        <div class="border-header">
            <h3>Налаштування плагіна на сайті</h3>
        </div>
        <?php foreach( $data->setup_info as $key => $value ) : ?>
            <div class="item">
                <div class="item__body">
                    <div class="item__group">
                        <div class="item__prop-name"><?php echo $key; ?></div>
                        <div class="item__prop"><?php if ($value) echo $value; else echo wbsmd_get_error_message(); ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <div class="item-list" id="news">
        <div class="border-header">
            <h3>Останні новини</h3>
        </div>
        <?php foreach( $data->news as $item ) : ?>
            <?php if(empty($item)) echo wbsmd_get_error_message();?>
            <div class="item<?php if (date('Y.m.d H:i:s', strtotime(COMPARE_DATE)) > date( 'Y.m.d H:i:s', strtotime($item->created))) echo '--red'; ?>">
                <div class="item__body">
                    <div class="item__group">
                        <div class="item__prop-name">ID</div>
                        <div class="item__prop"><?php echo $item->id; ?></div>
                    </div>
                    <div class="item__group">
                        <div class="item__prop-name">TITLE</div>
                        <div class="item__prop"><?php echo $item->title; ?></div>
                    </div>
                    <div class="item__group">
                        <div class="item__prop-name" >CREATED</div>
                        <div class="item__prop"><?php echo $item->created; ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(!$data->news) : ?>
            <div class="item--error">
                <div class="item__body">
                    <?php echo wbsmd_get_error_message(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <div class="item-list" id="events">
        <div class="border-header">
            <h3>Останні події</h3>
        </div>
        <?php foreach( $data->events as $item ) : ?>
            <div class="item<?php if (date('Y.m.d H:i:s', strtotime(COMPARE_DATE)) > date( 'Y.m.d H:i:s', strtotime($item->created))) echo '--red'; ?>">
                <div class="item__body">
                    <div class="item__group">
                        <div class="item__prop-name">ID</div>
                        <div class="item__prop"><?php echo $item->id; ?></div>
                    </div>
                    <div class="item__group">
                        <div class="item__prop-name">TITLE</div>
                        <div class="item__prop"><?php echo $item->title; ?></div>
                    </div>
                    <div class="item__group">
                        <div class="item__prop-name">CREATED</div>
                        <div class="item__prop"><?php echo $item->created; ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(!$data->events) : ?>
            <div class="item--error">
                <div class="item__body">
                    <?php echo   wbsmd_get_error_message(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>