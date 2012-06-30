<?php

define('SO_THEME_VERSION', 'trunk');

if(!defined('SO_IS_PREMIUM')) include get_template_directory().'/extras/premium/premium.php';
if(!defined('SO_IS_PREMIUM')) include get_template_directory().'/upgrade.php';

include get_template_directory().'/extras/admin/admin.php';
include get_template_directory().'/extras/settings/settings.php';
include get_template_directory().'/extras/support/support.php';


/**
 * General theme setup
 * 
 * @action after_setup_theme
 */
function photography_setup_theme(){
	// We're using SiteOrigin theme settings
	so_settings_init();

	if ( ! isset( $content_width ) ) $content_width = 620;
	
	// The custom header is used for the logo
	add_theme_support('custom-header', array(	
		'flex-width' => true,
		'flex-height' => true,
		'header-text' => false,
	));
	
	// Custom background images are nice
	$background = array();
	switch(so_setting('general_style')){
		case 'light' :
			$background['default-color'] = 'FEFEFE';
			break;
		case 'dark' :
			$background['default-color'] = '333';
			$background['default-image'] = get_template_directory_uri().'/images/dark/bg.png';
			break;
	}
	add_theme_support('custom-background', $background);
	
	add_theme_support('post-thumbnails');
	
	add_theme_support( 'automatic-feed-links' );
	
	set_post_thumbnail_size(310, 420, true);
	add_image_size('single-large', 960, 960, false);
	add_image_size('slider-large', 1600, 1600, false);
	
	// The navigation menus
	register_nav_menu('main-menu', __('Main Menu', 'photography'));
	
	add_editor_style();
}
add_action('after_setup_theme', 'photography_setup_theme');

/**
 * Initialize the admin settings
 * 
 * @action admin_init
 */
function photography_admin_init(){
	// General Stuff
	so_settings_add_section('general', __('General', 'photography'));

	so_settings_add_teaser('general', 'search', __('Search in Menu', 'photography'), array(
		'description' => __('Display a search link in your menu that slides out a big beautiful search bar.', 'photography')
	));
	so_settings_add_field('general', 'copyright', 'text', __('Copyright Message', 'photography'));
	so_settings_add_teaser('general', 'attribution', __('Attribution Link', 'photography'), array(
		'description' => __('Hide or display "Theme By SiteOrigin" link from your footer.', 'photography')
	));

	so_settings_add_section('appearance', __('Appearance', 'photography'));

	so_settings_add_teaser('appearance', 'style', __('Style', 'photography'), array(
		'description' => __('Choose the style of your site.', 'photography')
	));
	
	so_settings_add_field('appearance', 'link', 'color', __('Link Color', 'photography'));
	
	
	// The slider section
	so_settings_add_section('slider', __('Home Page Slider', 'photography'));
	so_settings_add_field('slider', 'enabled', 'checkbox', __('Home Page Slider', 'photography'), array());
	so_settings_add_field('slider', 'speed', 'number', __('Transition Delay', 'photography'), array(
		'description' => 'Number of milliseconds a photo is displayed for.'
	));
	so_settings_add_field('slider', 'transition', 'number', __('Transition Delay', 'photography'), array(
		'description' => 'How many milliseconds the transition takes.'
	));
	so_settings_add_teaser('slider', 'posts', __('Posts', 'photography'), array(
		'description' => __('How Photography chooses your home page slides.', 'photography')
	));
	
	// Social and sharing
	so_settings_add_section('social', __('Social', 'photography'));
	so_settings_add_field('social', 'display_share', 'checkbox', __('Share Buttons', 'photography'), array(
		'label' => __('Show share buttons next to posts', 'photography')
	));
	so_settings_add_field('social', 'twitter', 'text', __('Twitter Username', 'photography'), array('validator' => 'twitter'));
	so_settings_add_field('social', 'recommend', 'checkbox', __('Recommend SiteOrigin', 'photography'), array(
		'label' => __('Yes', 'photography'),
		'description' => __("Recommends your's and SiteOrigin's Twitter accounts after someone tweets your post.", 'photography')
	));
	
	// Comments
	so_settings_add_section('comments', __('Comments', 'photography'));
	so_settings_add_teaser('comments', 'ajax', __('Ajax Comments', 'photography'), array(
		'description' => __('Let your visitors post comments without leaving the page.', 'photography')
	));
	
	// Site messages
	so_settings_add_section('messages', __('Site Messages', 'photography'));
	so_settings_add_field('messages', '404', 'textarea', __('Error 404 Message', 'photography'));
	so_settings_add_field('messages', 'no_results', 'textarea', __('No Search Results', 'photography'));
}
add_action('admin_init', 'photography_admin_init');

/**
 * Set up the default settings
 * @param $defaults
 * @return array
 * 
 * @filter so_theme_default_settings
 */
function photography_default_settings($defaults){
	$defaults['general_search'] = true;
	$defaults['general_copyright'] = __('Copyright &copy; {sitename} {year}', 'photography');
	$defaults['general_attribution'] = true;
	
	$defaults['appearance_style'] = 'light';
	$defaults['appearance_link'] = '#dc5c3b';
	
	$defaults['slider_enabled'] = true;
	$defaults['slider_speed'] = 7500;
	$defaults['slider_transition'] = 500;

	$defaults['social_display_share'] = true;
	$defaults['social_recommend'] = true;
	
	$defaults['comments_ajax'] = true;
	
	$defaults['messages_404'] = __("We couldn't find what you were looking for.", 'photography');
	$defaults['messages_no_results'] = __("No results.", 'photography');
	
	return $defaults;
}
add_filter('so_theme_default_settings', 'photography_default_settings');

/**
 * Add the custom style CSS
 * @return mixed
 * 
 * @action wp_print_styles
 */
function photography_print_scripts(){
	if(is_admin()) return;
	?><style type="text/css" media="all">a{ color: <?php print so_setting('appearance_link') ?>; }</style><?php
}
add_action('wp_print_styles', 'photography_print_scripts');

/**
 * Setup the widgets
 * 
 * @action widgets_init
 */
function photography_setup_widgets(){
	register_sidebar(array(
		'name' => __('Site Footer', 'photography'),
		'id' => 'site-footer',
	));
}
add_action('widgets_init', 'photography_setup_widgets');

/**
 * Enqueue Photography's Scripts.
 * 
 * @action wp_enqueue_scripts
 */
function photography_enqueue_scripts(){
	if(so_setting('appearance_style') != 'light'){
		wp_enqueue_style('photography-style', get_stylesheet_directory_uri().'/style-'.so_setting('appearance_style').'.css', array(), SO_THEME_VERSION);
	}
	
	wp_enqueue_script('photography-main', get_template_directory_uri().'/js/photography.js', array('jquery'), SO_THEME_VERSION);
	
	if(is_home()){
		wp_enqueue_script('photography-home', get_template_directory_uri().'/js/photography-home.js', array('jquery'), SO_THEME_VERSION);
		wp_localize_script('photography-home', 'photographyHome', array(
			'sliderSpeed' => so_setting('slider_speed')
		));
	}
	
	if(is_single()){
		wp_enqueue_script('photography-single', get_template_directory_uri().'/js/photography-single.js', array('jquery'), SO_THEME_VERSION);
	}
	
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	
	if(is_singular()) // TODO check that sharing is enabled
		wp_enqueue_script('photography-google-plusone', get_template_directory_uri().'/js/plusone.js', array(), SO_THEME_VERSION);
		
}
add_action('wp_enqueue_scripts', 'photography_enqueue_scripts');

if(!function_exists('photography_wp_title')) :
/**
 * Filter the title
 * @param $title
 * @param $sep
 * @param $seplocation
 * @return string
 * 
 * @filter wp_title
 */
function photography_wp_title($title, $sep, $seplocation){
	if(trim($sep) != ''){
		if(!empty($title)) {
			$title_array = explode($sep, $title);
		}
		else $title_array = array();
		
		$title_array[] = get_bloginfo('title');
		if(is_home()) $title_array[] = get_bloginfo('description');

		$title_array = array_map('trim', $title_array);
		$title_array = array_filter($title_array);
		
		if($seplocation == 'left') $title_array = array_reverse($title_array);
		
		$title = implode( " $sep ", $title_array );
	}
	
	return $title;
}
endif;
add_filter('wp_title', 'photography_wp_title', 10, 3);

if(!function_exists('photography_single_comment')) :
/**
 * Display a single comment.
 * 
 * @param $comment
 * @param $depth
 * @param $args
 */
function photography_single_comment($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php print get_comment_ID() ?>" <?php comment_class() ?>>
		<?php if(empty($comment->comment_type) || $comment->comment_type == 'comment') : ?>
			<div class="comment-avatar">
				<?php print get_avatar(get_comment_author_email(), 60) ?>
			</div>
		<?php elseif($comment->comment_type == 'trackback' || $comment->comment_type == 'pingback') : ?>
			<div class="pingback-icon"></div>
		<?php endif; ?>
		
		<div class="comment-main">
			<div class="comment-info">
				<span class="author"><?php print get_comment_author_link() ?></span>
				<span class="date"><?php comment_date() ?></span>
		
				<?php comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'])) ?>
			</div>
			<div class="comment-content entry-content">
				<?php comment_text() ?>
			</div>
		</div>
	<?php
}
endif;

/**
 * Add the proper class to the posts nav link
 * @param $attr
 * @return string
 * 
 * @filter previous_posts_link_attributes
 */
function photography_previous_posts_link_attributes($attr){
	$attr = 'class="next"';
	return $attr;
}
add_filter('previous_posts_link_attributes', 'photography_previous_posts_link_attributes');

/**
 * Add the proper class to the posts nav link
 * @param $attr
 * @return string
 * 
 * @filter next_posts_link_attributes
 */
function photography_next_posts_link_attributes($attr){
	$attr = 'class="prev"';
	return $attr;
}
add_filter('next_posts_link_attributes', 'photography_next_posts_link_attributes');

/**
 * Set the widths of the footer widgets
 *
 * @param $params
 * @return mixed
 * 
 * @filter dynamic_sidebar_params
 */
function photography_footer_widget_params($params){
	// Check that this is the footer
	if($params[0]['id'] != 'site-footer') return $params;

	$sidebars_widgets = wp_get_sidebars_widgets();
	$count = count($sidebars_widgets[$params[0]['id']]);
	$params[0]['before_widget'] = preg_replace('/\>$/', ' style="width:'.round(100/$count,4).'%" >', $params[0]['before_widget']);

	return $params;
}
add_filter('dynamic_sidebar_params', 'photography_footer_widget_params');

/**
 * Add the sidebar exclude field
 * @param $fields
 * @param $post
 * @return array
 * 
 * @filter attachment_fields_to_edit
 */
function photography_attachment_fields_to_edit($fields, $post){
	$parent = get_post($post->post_parent);
	if($parent->post_type == 'post'){
		$exclude = get_post_meta($post->ID, 'sidebar_exclude', true);
		$fields['photography_exclude'] = array(
			'label' => __('Sidebar Exclude', 'photography'),
			'input' => 'html',
			'html' => '<input name="attachments['.$post->ID.'][sidebar_exclude]" id="attachment-'.$post->ID.'-sidebar_exclude" type="checkbox" '.checked(!empty($exclude), true, false).' /> <label for="attachment-'.$post->ID.'-sidebar_exclude">'.__('Exclude', 'photography').'</label>',
			'value' => !empty($exclude)
		);
	}
	
	return $fields;
}
add_filter('attachment_fields_to_edit', 'photography_attachment_fields_to_edit', 10, 2);

/**
 * Save the attachment form meta. 
 * @param $post
 * @return mixed
 * 
 * @filter attachment_fields_to_save
 */
function photography_attachment_save($post){
	$parent = get_post($post['post_parent']);
	if($parent->post_type == 'post' && !empty($_POST['attachments'][$post['ID']])){
		$current = get_post_meta($post['ID'], 'sidebar_exclude', true);
		$exclude = !empty($_POST['attachments'][$post['ID']]['sidebar_exclude']);
		update_post_meta($post['ID'], 'sidebar_exclude', $exclude, $current);
	}
	
	return $post;
}
add_filter('attachment_fields_to_save', 'photography_attachment_save', 10, 2);

/**
 * Add the relevant metaboxes.
 * 
 * @action add_meta_boxes
 */
function photography_add_meta_boxes(){
	if(defined('SO_IS_PREMIUM')) return;
	add_meta_box('photography-post-video', __('Post Video', 'photography'), 'photography_meta_box_video_render', 'post', 'side');
}
add_action('add_meta_boxes', 'photography_add_meta_boxes');

/**
 * Render the video meta box added in photography_add_meta_boxes
 */
function photography_meta_box_video_render(){
	?><p><?php printf(__('Post videos are available in <a href="%s">Photography Premium</a>.', 'photography'), admin_url('themes.php?page=premium_upgrade')) ?></p><?php
}