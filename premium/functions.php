<?php

// This is a premium version
define('SITEORIGIN_IS_PREMIUM', true);

include get_template_directory().'/premium/settings.php';
include get_template_directory().'/premium/extras/ajax-comments/ajax-comments.php';
include get_template_directory().'/premium/extras/css/css.php';

function snapshot_premium_init(){
	if(siteorigin_setting('comments_ajax')){
		siteorigin_ajax_comments_activate();
	}
}
add_action('after_setup_theme', 'snapshot_premium_init', 11);

/**
 * Enqueue snapshot premium's scripts
 * 
 * @action wp_enqueue_scripts
 */
function snapshot_premium_enqueue_scripts(){
	wp_enqueue_style('snapshot-spritemaps', get_template_directory_uri().'/premium/sprites.css', array(), SITEORIGIN_THEME_VERSION);
	
	if(siteorigin_setting('general_search')){
		wp_enqueue_script('snapshot-search', get_template_directory_uri().'/premium/js/search.js', array('jquery'), SITEORIGIN_THEME_VERSION);
		wp_localize_script('snapshot-search', 'snapshotSearch', array(
			'menuText' => siteorigin_setting('general_search_menu_text')
		));
	}
}
add_action('wp_enqueue_scripts', 'snapshot_premium_enqueue_scripts');

/**
 * Set up the error handler.
 * 
 * @filter wp_die_handler
 */
function snapshot_premium_comment_ajax_handler($handler){
	global $pagenow;
	if($pagenow == 'wp-comments-post.php' && siteorigin_setting('comments_ajax') && !empty($_POST['is_ajax'])){
		$handler = 'snapshot_premium_comment_ajax_error_handler';
	}
	return $handler;
}
add_filter('wp_die_handler', 'snapshot_premium_comment_ajax_handler');

/**
 * Ajax error handler
 * 
 * @param $error
 */
function snapshot_premium_comment_ajax_error_handler($error){
	header('content-type: application/json', true);
	print json_encode(array(
		'status' => 'error',
		'error' => $error,
	));
	exit();
}

/**
 * Render all the ajax comments
 */
function snapshot_premium_ajax_comment_rerender($location, $comment){
	if(!siteorigin_setting('comments_ajax') || empty($_POST['is_ajax'])) return $location;
	
	$post_id = isset($_POST['comment_post_ID']) ? intval($_POST['comment_post_ID']) : '';
	
	// We're going to pretend this is a single
	$query = array('post_id' => $post_id);
	
	if(get_option('page_comments')){
		$args['per_page'] = get_option('comments_per_page');
		$cpage = get_page_of_comment( $comment->comment_ID, $args );
		$query['cpage'] = $cpage;
	}
	query_posts($query);
	
	global $wp_query;
	$wp_query->is_single = true;
	$wp_query->is_singular = true;
	
	ob_start();
	comments_template();
	$comment_html = ob_get_clean();
	
	print json_encode(array(
		'status' => 'success',
		'html' => $comment_html,
	));
	exit();
}
add_filter('comment_post_redirect', 'snapshot_premium_ajax_comment_rerender', 10, 2);

/**
 * Add video metabox
 * 
 * @action add_meta_boxes
 */
function snapshot_premium_add_meta_boxes(){
	add_meta_box('snapshot-post-video', __('Post Video', 'snapshot'), 'snapshot_premium_meta_box_video_render', 'post', 'side');
}
add_action('add_meta_boxes', 'snapshot_premium_add_meta_boxes');

/**
 * Render the video meta box
 */
function snapshot_premium_meta_box_video_render($post){
	$video = get_post_meta($post->ID, 'snapshot_post_video', true);
	?>
	<input type="text" name="snapshot_post_video" class="widefat" value="<?php echo esc_attr($video) ?>" />
	<p class="description"><?php _e('Enter a full video URL', 'snapshot') ?></p>
	<?php
}

function snapshot_premium_save_post($post_id, $post){
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( !current_user_can('edit_post', $post_id) ) return;
	if(!isset($_POST['snapshot_post_video'])) return;
	
	update_post_meta($post_id, 'snapshot_post_video', $_POST['snapshot_post_video']);
	
}
add_action('save_post', 'snapshot_premium_save_post', 10, 2);

/**
 * Add the search button to the navigation menu
 * 
 * @param $items
 * @param $args
 * @return string
 */
function snapshot_premium_wp_nav_menu_items($items, $args){
	if(siteorigin_setting('general_search') && $args->theme_location == 'main-menu'){
		$items .= '<li id="main-menu-search"><a href="#">'.siteorigin_setting('general_search_menu_text').'</a></li>';
	}
	return $items;
}
add_filter('wp_nav_menu_items', 'snapshot_premium_wp_nav_menu_items', 10, 2);

/**
 * @param $post_id
 */
function snapshot_premium_video_viewer($post_id){
	$video = get_post_meta($post_id, 'snapshot_post_video', true);
	global $wp_embed;
	
	$code = $wp_embed->shortcode(array('width' => 960), $video);
	$code = apply_filters('snapshot_video_embed_code', $code);
	echo $code;
}

function snapshot_premium_filter_video_embed_code($code){
	if(siteorigin_setting('posts_video_autoplay') || siteorigin_setting('posts_video_hide_related') || siteorigin_setting('posts_video_default_hd')) {
		$code = preg_replace_callback('/src="([^"]*)"/', 'snapshot_premium_video_change_autoplay_callback', $code);
	}
	echo $code;
}
add_filter('snapshot_video_embed_code', 'snapshot_premium_filter_video_embed_code');

function snapshot_premium_video_change_autoplay_callback($matches){
	$url = $matches[1];
	if(siteorigin_setting('posts_video_autoplay')){
		$url = add_query_arg('autoplay', 1, $url);
	}
	if(siteorigin_setting('posts_video_hide_related')){
		$url = add_query_arg('rel', 0, $url);
	}
	if(siteorigin_setting('posts_video_default_hd')){
		$url = add_query_arg('hd', 1, $url);
	}

	return 'src="' .$url. '"';
}

function snapshot_premium_slider_query_args($args){
	// Add the category setting
	$cat = siteorigin_setting('slider_category');
	if(!empty($cat)){
		$args['cat'] = intval($cat);
	}
	
	// Add the order setting
	$args['orderby'] = siteorigin_setting('slider_posts');
	
	return $args;
}
add_filter('snapshot_slider_query_args', 'snapshot_premium_slider_query_args');