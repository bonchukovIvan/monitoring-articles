<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$custom_date = isset($_GET['custom_date']) ? new DateTime($_GET['custom_date']) : new DateTime('first day of december previous year');
$today = new DateTime('today');
$interval = $custom_date->diff($today);
$months = $interval->format('%m');
$minimal_posts_count = $months * WBSMD_MINIMAL_POSTS_COUNT_PER_MONTHS;
?>
<div class="item">
        <div class="item__group">
            <div class="item__prop-name">Мінімальна кількість публікацій за період: </div>
            <div class="item__prop"><?php echo $minimal_posts_count; ?></div>
        </div>
        <div class="item__group">
            <div class="item__prop-name">Дата початку періоду моніторингу: </div>
            <div class="item__prop"><?php echo $custom_date->format('Y-m-d'); ?></div>
        </div>
    <div class="item__section">
        <div class="item__group item--green">
            <div class="item__prop-name">Коєфіцієнт актуальності: 1</div>
            <div class="item__prop">кількість порушень <= 20%</div>
        </div>
        <div class="item__group item--orange">
            <div class="item__prop-name">Коєфіцієнт актуальності: 0.5</div>
            <div class="item__prop">кількість порушень > 20%та кількість порушень <= 50%</div>
        </div>
        <div class="item__group item--red">
            <div class="item__prop-name">Коєфіцієнт актуальності: 0</div>
            <div class="item__prop">кількість порушень > 50%</div>
        </div>
    </div>
</div>