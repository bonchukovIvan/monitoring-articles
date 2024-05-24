<?php
/**
 * 
 * Template Name: SumDU Content Monitoring 
 *
 *
 * @package WordPress
 * @subpackage Sumdu_theme
 * @since Sumdu theme 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$args_links = array(
    'post_type' => 'wbsmd_mk_links',
    'post_status' => 'publish',
    'posts_per_page' => -1,
);
$links = new WP_Query( $args_links );

$args_value = array(
    'post_type' => 'wbsmd_mk_value',
    'post_status' => 'publish',
    'posts_per_page' => -1,
);
$values = new WP_Query( $args_value );

if (ini_get('max_execution_time') >= 30) {
    ini_set('max_execution_time', 9999);
}

?>

<?php get_header(); ?>

<div class="border-header">
    <h2><?php the_title(); ?> ---- В розробці -----</h2>
</div>

<section class="site-data">
<pre>

    <?php 
        // $groups = [];
        // if ( $values->have_posts() ) {
        //     while ( $values->have_posts() )  {
        //         $values->the_post();
        //         $value_group = new stdClass();
        //         $value_group->faculty = carbon_get_the_post_meta( 'mk_site_faculty' );
        //         $value_group->types = [
        //             carbon_get_the_post_meta( 'mk_site_check_faculty' ) ? 'faculty' : '' ,
        //             carbon_get_the_post_meta( 'mk_site_check_graduation' )  ? 'graduation' : '' ,
        //             carbon_get_the_post_meta( 'mk_site_check_non-graduation' )  ? 'non-graduation' : '' ,
        //         ];

        //         $value_group->values = [];
        //         $c_values = ( carbon_get_post_meta(get_the_ID(), 'mk_site_values') != [] ) ? carbon_get_post_meta(get_the_ID(), 'mk_site_values') : [];
        //         foreach($c_values as $value) {
        //             array_push($value_group->values, $value);
        //         }

        //         $value_groups[get_the_title()] = $value_group;
        //     }
        // }

        // $sites = [];

        // if ( $links->have_posts() ) {
        //     while ( $links->have_posts() )  {
        //         $links->the_post();
        //         $site = new stdClass();
        //         $site->link = get_the_title();
        //         $site->faculty = carbon_get_the_post_meta( 'mk_site_faculty' );
        //         $site->type = carbon_get_the_post_meta( 'mk_site_type' );
        //         $sites[get_the_title()] = $site;
        //     }
        // }

        // $multiHandle = curl_multi_init();

        // foreach ( $sites as $link => $info )  {
        //     $handle = init_curl_handle($link);

        //     curl_multi_add_handle($multiHandle, $handle);

        //     $handles[] = ['site' => $site, 'handle' => $handle];
        // }
        // $running = null;
        // do {
        //     curl_multi_exec($multiHandle, $running);
        // } while ($running);
 

        // foreach ($handles as $handleInfo) {

        //     $site = $handleInfo['site'];
        //     $handle = $handleInfo['handle'];
        //     $site_pages = crawl_links($handleInfo['site']->link);
        //     $test['link'] = $handleInfo['site']->link;
        //     $test['faculty'] = $handleInfo['site']->faculty;

        //     $content = curl_multi_getcontent($handle);
        //     $pages_content = get_multiple_content($site_pages);

        //     foreach ($groups as $group) {
        //         $group_arr = [];
        //         $res = [];
        //         $group_dep_id = [];
        //         foreach($group->departaments as $dep) {
        //             array_push($group_dep_id, $dep->id);
        //         }
        //     }
        // }


    ?>
</pre>
</section>

<?php get_footer(); ?>

<?php 

function init_curl_handle($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    return $ch;
}

function crawl_links($url) {
    $dom = new \DOMDocument();
    @$dom->loadHTMLFile($url);

    $xpath = new \DOMXPath($dom);

    $anchorElements = $xpath->query('//li//a');

    $site_links = [];

    foreach ($anchorElements as $anchorElement) {
        if (str_starts_with($anchorElement->getAttribute('href'), '/')) {
            array_push($site_links, $url . $anchorElement->getAttribute('href'));
        } 
        elseif (str_starts_with($anchorElement->getAttribute('href'), $url)) {
            array_push($site_links, $anchorElement->getAttribute('href'));
        }
    }

    return array_unique($site_links);
}





















function crawl_pages($pages_content, $value) {
    foreach($pages_content as $page_content) {
        $result = str_contains(mb_strtolower($page_content), mb_strtolower(trim($value->search_value)));
        if($result) return $result;
    }
}
function set_result($result, &$group_arr, $group, $value) {
    array_push($group_arr, ['name' => $group->name, 'value' => $value->search_value, 'result' => $result]);
}



function get_contents_array($handles) {
    $contents_array = [];
    foreach($handles as $handle) {
        array_push($contents_array, curl_multi_getcontent($handle));
    }
    return $contents_array;
}

function get_handles($domains) {
    $handles = array();
    foreach($domains as $domain) {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $domain);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_VERBOSE, false);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
            curl_setopt($handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }

        $handles[$domain] = $handle;
    }

    return $handles;
}

function add_handles_to_multi($mh, $handles) {
    foreach($handles as $handle) {
        curl_multi_add_handle($mh, $handle);
    }
}

function close_handle($mh, $handles) {
    foreach($handles as $handle) {
        curl_multi_remove_handle($mh, $handle);
    }
    curl_multi_close($mh);
}

function get_multiple_content($pages) {
    $handles = get_handles($pages);

    $mh = curl_multi_init();
    add_handles_to_multi($mh, $handles);

    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while($running > 0);

    close_handle($mh, $handles);
    return get_contents_array($handles);
} 