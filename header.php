<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />

	<title><?php wp_title('|', true, 'right'); ?></title>

	<link rel="stylesheet" type="text/css" media="screen" href="<?php print get_stylesheet_uri(); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>

<body <?php body_class() ?>>

<div id="top-area">
	<div class="container">
		<div id="logo">
			<a href="<?php print site_url('/') ?>" title="<?php esc_attr(get_bloginfo('title', 'display') . ' - '.get_bloginfo('description', 'display')) ?>">
				<?php $header = get_custom_header(); if(!empty($header->url)) : ?>
					<img src="<?php print esc_attr($header->url) ?>" width="<?php print intval($header->width)?>" height="<?php print intval($header->height)?>" alt="<?php esc_attr(get_bloginfo('title', 'display')) ?>" />
				<?php else : ?>
					<h1><em></em><?php bloginfo('title', 'display') ?></h1>
				<?php endif ?>
			</a>
		</div>

		<?php
		wp_nav_menu(array(
			'theme_location' => 'main-menu',
			'fallback_cb' => false,
			'depth' => 2,
		));
		?>
	</div>
</div>

<?php if(so_setting('general_search')) get_template_part('premium/searchbar'); ?>