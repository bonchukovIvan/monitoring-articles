<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$args = array(
    'post_type' => 'wbsmd_ma_links',
    'post_status' => 'publish',
    'posts_per_page' => -1
);
$is_date = isset($_GET['custom_date']);
$is_foo = isset($_GET['foo']);
$checks = new WP_Query( $args );
?>
<?php  get_template_part('template-parts/relevance/settings', 'legend'); ?>
<div class="settings">
    <div class="settings__body">
        <?php if (!empty($_GET) && $_GET['type'] === 'all') : ?>
            <button type="button" id="saved-btn">Збереження звіту</button>
            <button type="button" id="all-sites-check">Перевірити всі сайти повторно</button>
            
        <?php else : ?>
            <input type="text" id="group_name" placeholder="Введіть назву звіту">
            <button type="button" id="saved-btn">Збереження звіту</button>

        <?php endif; ?>     
            <button type="button" id="partially-sites-check">Вибрати сайти</button>

        <?php if (!empty($_GET) && $_GET['type'] === 'partially') : ?>
            <button type="button" id="partially-sites-check-run">Перевірити</button>

        <?php endif; ?>  
        <div class="settings__selected">
            <select name="custom_date" id="custom_date">
                <option value="first day of december previous year" selected>1 грудня минулого року</option>
                <option value="first day of january this year" 
                <?php if ($is_date && $_GET['custom_date'] === "first day of january this year") echo 'selected'?>
                >Початок року</option>
                <option value="first day of june this year"
                <?php if ($is_date && $_GET['custom_date'] === "first day of june this year") echo 'selected'?>
                >Середина року (1 червня)</option>
            </select>
        </div>
    </div>
</div>
<?php if (!empty($_GET) && $_GET['type'] != 'all') : ?>
<div class="content-wrap">
<?php endif; ?>
<?php if (!empty($_GET) && $_GET['type'] === 'partially') : ?>
<fieldset id="partially-checkeded">
    <div class="settings__field">
        <input type="checkbox" name="select-all" id="select-all" />
        <label for="<?php the_title(); ?>">Вибрати всі</label>
    </div>
    <?php if ( $checks->have_posts() ) : ?>
        <?php while ( $checks->have_posts() ) : ?>
            <?php $checks->the_post(); ?>
            <div class="settings__field">
                <input type="checkbox" id="<?php the_ID(); ?>" name="<?php the_title(); ?>"
                <?php if ($is_foo && in_array(get_the_ID(), $_GET['foo'])) checked(1, true, true);; ?>
                />
                <label for="<?php the_title(); ?>"><?php echo WbsmdLocalizationHelper::remove_symbol_from_url(the_title('', '', false)); ?></label>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</fieldset>
<?php endif; ?>