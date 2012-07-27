<?php get_header(); the_post(); ?>

<div id="page-title" class="post-title">
	<div class="container">
		<div class="post-info">
			<div class="date">
				<em></em>
				<a href="<?php the_permalink() ?>"><?php print get_the_date() ?></a>
			</div>
			<div class="comments">
				<em></em>
				<a href="#comments"><?php comments_number( __('No Comments', 'snapshot'), __('One Comment', 'snapshot'), __('% Comments', 'snapshot') ); ?></a>
			</div>
			
			<?php $category = get_the_category(); if(!empty($category)) : ?>
				<div class="category">
					<em></em><a href="<?php print get_term_link($category[0]) ?>"><?php print $category[0]->name ?></a>
				</div>
			<?php endif ?>
		</div>
		
		<h1><?php the_title() ?></h1>
		
		<div class="nav">
			<?php previous_post_link('%link') ?>
			<?php next_post_link('%link') ?>
		</div>
	</div>
</div>

<?php get_template_part('viewer') ?>
	
<div id="post-<?php the_ID() ?>" <?php post_class() ?>>
	<div class="container">
		<div id="post-share">
			<?php if(so_setting('social_display_share')) get_template_part('share') ?>
		</div>
		
		<div id="post-main">
			<div class="entry-content">
				<?php the_content() ?>
				
				<?php global $numpages; if(!empty($numpages) || get_the_tag_list() != '') : ?>
					<div class="clear"></div>
				<?php endif; ?>
				
				<?php wp_link_pages() ?>
				<?php the_tags() ?>
			</div>
			<div class="clear"></div>
			
			<div id="single-comments-wrapper">
				<?php comments_template() ?>
			</div>
		</div>
		
		<div id="post-images">
			<?php
				$children = get_children(array(
					'post_mime_type' => 'image',
					'post_parent' => get_the_ID(),
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'post_mime_type' => 'image', 
					'order' => 'ASC',
					'orderby' => 'menu_order ID'
				));
			
				foreach($children as $child){
					$exclude = get_post_meta($child->ID, 'sidebar_exclude', true);
					if(!empty($exclude)) continue;
					
					$src = wp_get_attachment_image_src($child->ID, 'single-large');
					?>
					<div class="image">
						<?php print '<a href="'.get_attachment_link($child->ID).'" data-width="'.$src[1].'" data-height="'.$src[2].'">' ?>
						<?php print wp_get_attachment_image($child->ID, 'post-thumbnail', false, array('class' => 'thumbnail')); ?>
						<?php print '</a>' ?>
					</div>
					<?php
					
				}
			?>
		</div>

	</div>
	<div class="clear"></div>
</div>

<?php get_footer() ?>
