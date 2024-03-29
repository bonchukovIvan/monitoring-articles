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

?>

<!DOCTYPE html>
<html lang=<?php language_attributes()?>>
<head>
    <meta charset=<?php bloginfo( 'charset' ); ?>>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo get_bloginfo( 'name' )?>">
    <!-- wp_head -->
    <?php wp_head(); ?>

	<title> 
		<?php 
            if (is_home() || is_front_page()) {
                echo bloginfo('title');
            } elseif (is_404()) {
                echo wp_title();
            } else {
                echo get_the_title();
            }	
		?> 
	</title>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">

	<?php get_template_part('template-parts/header/site', 'header'); ?>

	<div id="content" class="site-content">
		<div id="primary" class="content-area">
		<div class="wrapper">
			<main id="main" class="site-main">

		