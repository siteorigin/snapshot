<?php $video = get_post_meta(get_the_ID(), 'photography_post_video', true); ?>

<?php if(!empty($video)) : ?>
	<div id="post-single-viewer">
		<div class="container">
			<?php
				global $wp_embed;
				print $wp_embed->shortcode(array('width' => 960), $video);
			?>
		</div>
	</div>
<?php elseif(has_post_thumbnail()) : ?>
	<div id="post-single-viewer">
		<div class="container">
			<?php print wp_get_attachment_image(get_post_thumbnail_id(), 'single-large', false, array('class' => 'single-image')); ?>
		</div>
	</div>
<?php endif; ?>
<div id="home-slider-below"></div>