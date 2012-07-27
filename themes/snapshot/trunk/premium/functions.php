<?php

// This is a premium version
define('SO_IS_PREMIUM', true);

include get_template_directory().'/extras/ajax-comments/ajax-comments.php';
include get_template_directory().'/extras/css/css.php';

/**
 * @param $output
 * @param $show
 * @return string
 * 
 * @filter stylesheet_uri
 */
function photography_premium_filter_stylesheet($output){
	// We want to use the original stylesheet
	$output = get_template_directory_uri().'/style.css';
	return $output;
}
add_filter('stylesheet_uri', 'photography_premium_filter_stylesheet', 10);

/**
 * Add all the settings available in the premium version.
 * 
 * @action admin_init
 */
function photography_premium_admin_init(){

	so_settings_add_field('general', 'search', 'checkbox', __('Search in Menu', 'photography'), array(
		'description' => __('Display a search link in your menu that slides out a big beautiful search bar.', 'photography')
	));
	
	so_settings_add_field('general', 'attribution', 'checkbox', __('Attribution Link', 'photography'), array(
		'description' => __('Hide or display "Theme By SiteOrigin" link from your footer.', 'photography')
	));

	so_settings_add_field('appearance', 'style', 'select', __('Style', 'photography'), array(
		'options' => array(
			'light' => 'Light',
			'dark' => 'Dark',
		)
	));

	so_settings_add_field('comments', 'ajax', 'checkbox', __('Ajax Comments', 'photography'), array(
		'description' => __('Let your visitors post comments without leaving the page.', 'photography')
	));
}
add_action('admin_init', 'photography_premium_admin_init', 11);

/**
 * Enqueue photography premium's scripts
 * 
 * @action wp_enqueue_scripts
 */
function photography_premium_enqueue_scripts(){
	wp_enqueue_style('photography-premium', get_stylesheet_directory_uri().'/style.css');
	
	if(so_setting('comments_ajax') && is_single() && post_type_supports( get_post_type(), 'comments' )){
		wp_enqueue_script('photography-ajax-comments', get_stylesheet_directory_uri().'/js/ajax-comments.js', array('jquery'), '1.0');
	}
}
add_action('wp_enqueue_scripts', 'photography_premium_enqueue_scripts');

/**
 * Set up the error handler.
 * 
 * @filter wp_die_handler
 */
function photography_premium_comment_ajax_handler($handler){
	global $pagenow;
	if($pagenow == 'wp-comments-post.php' && so_setting('comments_ajax') && !empty($_POST['is_ajax'])){
		$handler = 'photography_premium_comment_ajax_error_handler';
	}
	return $handler;
}
add_filter('wp_die_handler', 'photography_premium_comment_ajax_handler');

/**
 * Ajax error handler
 * 
 * @param $error
 */
function photography_premium_comment_ajax_error_handler($error){
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
function photography_premium_ajax_comment_rerender($location, $comment){
	if(!so_setting('comments_ajax') || empty($_POST['is_ajax'])) return $location;
	
	$post_id = isset($_POST['comment_post_ID']) ? intval($_POST['comment_post_ID']) : '';
	
	// We're going to pretend this is a single
	$query = array('post_id' => $post_id);
	
	if(get_option('page_comments')){
		$args['per_page'] = get_option('comments_per_page');
		$cpage = get_page_of_comment( $comment->comment_ID, $args );
		$query['cpage'] = $cpage;
		error_log($cpage);
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
add_filter('comment_post_redirect', 'photography_premium_ajax_comment_rerender', 10, 2);

/**
 * Add video metabox
 * 
 * @action add_meta_boxes
 */
function photography_premium_add_meta_boxes(){
	add_meta_box('photography-post-video', __('Post Video', 'photography'), 'photography_premium_meta_box_video_render', 'post', 'side');
}
add_action('add_meta_boxes', 'photography_premium_add_meta_boxes');

/**
 * Render the video meta box
 */
function photography_premium_meta_box_video_render($post){
	$video = get_post_meta($post->ID, 'photography_post_video', true);
	?>
	<input type="text" name="photography_post_video" class="widefat" value="<?php print esc_attr($video) ?>" />
	<p class="description"><?php _e('Enter a full video URL', 'photography') ?></p>
	<?php
}

function photography_premium_save_post($post_id, $post){
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( !current_user_can('edit_post', $post_id) ) return;
	if(!isset($_POST['photography_post_video'])) return;
	
	update_post_meta($post_id, 'photography_post_video', $_POST['photography_post_video']);
	
}
add_action('save_post', 'photography_premium_save_post', 10, 2);

/**
 * Add the search button
 * 
 * @param $items
 * @param $args
 * @return string
 */
function photography_premium_wp_nav_menu_items($items, $args){
	if(so_setting('general_search') && $args->theme_location == 'main-menu'){
		$items .= '<li id="main-menu-search"><a href="#">'.__('Search', 'photography').'</a></li>';
	}
	return $items;
}
add_filter('wp_nav_menu_items', 'photography_premium_wp_nav_menu_items', 10, 2);