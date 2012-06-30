<?php if(has_post_thumbnail()) : ?>
<div id="post-single-viewer">
	<div class="container">
		<?php print wp_get_attachment_image(get_post_thumbnail_id(), 'single-large', false, array('class' => 'single-image')); ?>
	</div>
</div>
<?php endif; ?>
<div id="home-slider-below"></div>