<?php 

$args = array(
    'post_type' => 'wbsmd_ma_links',
    'post_status' => 'publish',
    'posts_per_page' => -1
);
$checks = new WP_Query( $args );
?>
<div class="settings">
    <div class="settings__body">
    <?php if (!empty($_GET) && $_GET['type'] === 'all') : ?>
        <button type="button" id="all-sites-check">Перевірити всі сайти повторно</button>
    <?php else : ?>
        <button type="button" id="all-sites-check">Перевірити всі сайти</button>
    <?php endif; ?>     
        <button type="button" id="partially-sites-check">Перевірити вибірково</button>
    <?php if (!empty($_GET) && $_GET['type'] === 'partially') : ?>
        <button type="button" id="partially-sites-check-run">Перевірити</button>
    <?php endif; ?>  
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
                <input type="checkbox" id="<?php the_ID(); ?>" name="<?php the_title(); ?>" />
                <label for="<?php the_title(); ?>"><?php the_title(); ?></label>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</fieldset>
<?php endif; ?>