<?php

// This is a premium version
define('SO_IS_PREMIUM', true);

include get_template_directory().'/premium/extras/ajax-comments/ajax-comments.php';
include get_template_directory().'/premium/extras/css/css.php';

function snapshot_premium_init(){
	if(so_setting('comments_ajax')){
		so_ajax_comments_activate();
	}
}
add_action('after_setup_theme', 'snapshot_premium_init', 11);

/**
 * Add all the settings available in the premium version.
 * 
 * @action admin_init
 */
function snapshot_premium_admin_init(){

	so_settings_add_field('general', 'search', 'checkbox', __('Search in Menu', 'snapshot'), array(
		'description' => __('Display a search link in your menu that slides out a big beautiful search bar.', 'snapshot')
	));

	so_settings_add_field('general', 'search_menu_text', 'checkbox', __('Search Text in Menu', 'snapshot'), array(
		'description' => __('The search text to display in your menu.', 'snapshot')
	));

	so_settings_add_field('general', 'attribution', 'checkbox', __('Attribution Link', 'snapshot'), array(
		'description' => __('Hide or display "Theme By SiteOrigin" link from your footer.', 'snapshot')
	));

	so_settings_add_field('appearance', 'style', 'select', __('Style', 'snapshot'), array(
		'options' => array(
			'light' => __('Light', 'snapshot'),
			'dark' => __('Dark', 'snapshot'),
		)
	));

	so_settings_add_field('slider', 'posts', 'select', __('Posts', 'snapshot'), array(
		'description' => __('How Snapshot chooses your home page slides.', 'snapshot'),
		'options' => array(
			'date' => __('Post Date', 'snapshot'),
			'modified' => __('Modified Date', 'snapshot'),
			'rand' => __('Random', 'snapshot'),
			'comment_count' => __('By Comment Count', 'snapshot'),
		)
	));

	so_settings_add_field('comments', 'ajax', 'checkbox', __('Ajax Comments', 'snapshot'), array(
		'description' => __('Let your visitors post comments without leaving the page.', 'snapshot')
	));
}
add_action('admin_init', 'snapshot_premium_admin_init', 11);

/**
 * Set up the default settings
 * @param $defaults
 * @return array
 *
 * @filter so_theme_default_settings
 */
function snapshot_premium_settings_default($defaults){
	$defaults['general_search_menu_text'] = __('Search', 'snapshot');
	return $defaults;
}
add_filter('so_theme_default_settings', 'snapshot_premium_settings_default');

/**
 * Enqueue snapshot premium's scripts
 * 
 * @action wp_enqueue_scripts
 */
function snapshot_premium_enqueue_scripts(){
	wp_enqueue_style('snapshot-spritemaps', get_stylesheet_directory_uri().'/premium/sprites.css', array(), SO_THEME_VERSION);
	
	if(so_setting('general_search')){
		wp_enqueue_script('snapshot-search', get_stylesheet_directory_uri().'/premium/js/search.js', array('jquery'), SO_THEME_VERSION);
		wp_localize_script('snapshot-search', 'snapshotSearch', array(
			'menuText' => so_setting('general_search_menu_text')
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
	if($pagenow == 'wp-comments-post.php' && so_setting('comments_ajax') && !empty($_POST['is_ajax'])){
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
	if(!so_setting('comments_ajax') || empty($_POST['is_ajax'])) return $location;
	
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
	<input type="text" name="snapshot_post_video" class="widefat" value="<?php print esc_attr($video) ?>" />
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
	if(so_setting('general_search') && $args->theme_location == 'main-menu'){
		$items .= '<li id="main-menu-search"><a href="#">'.so_setting('general_search_menu_text').'</a></li>';
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
	print $wp_embed->shortcode(array('width' => 960), $video);
}