<?php get_header() ?>

<?php global $paged; if(empty($paged) || $paged == 1) : get_template_part('slider', 'home'); ?>
<?php else : ?>
	<div id="page-title" class="archive-title">
		<div class="container">
			<h1><?php _e('Latest Posts', 'photography') ?></h1>
		</div>
	</div>
	<div id="home-slider-below"></div>
<?php endif; ?>

<?php get_template_part('loop', 'index') ?>

<?php get_footer() ?>
