<?php
$query = new WP_Query(array(
	'post_type' => 'post',
	'posts_per_page' => so_setting('slider_post_count'),
	'orderby' => so_setting('slider_posts'),
));
?>
<div id="home-slider" class="loading">
	<div class="container">
		
		<div class="post-titles">
			<?php while($query->have_posts()) : $query->the_post(); if(has_post_thumbnail()) : ?>
				<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
			<?php endif; endwhile; ?>
		</div>
		
		<div class="navigation">
			<a href="#" class="previous"></a>
			<a href="#" class="next"></a>
			<div class="clear"></div>
		</div>
	</div>
	
	<?php $query->rewind_posts(); while($query->have_posts()) : $query->the_post(); if(has_post_thumbnail()) : ?>
		<?php print wp_get_attachment_image(get_post_thumbnail_id(), 'slider-large', false, array('class' => 'slide')) ?>
	<?php endif; endwhile; ?>
</div>
<div id="home-slider-below"></div>
<?php wp_reset_postdata() ?>