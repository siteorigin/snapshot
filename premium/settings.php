<?php

/**
 * Add all the settings available in the premium version.
 *
 * @action admin_init
 */
function snapshot_premium_admin_init(){

	siteorigin_settings_add_field('general', 'search', 'checkbox', __('Search in Menu', 'snapshot'), array(
		'description' => __('Display a search link in your menu that slides out a big beautiful search bar.', 'snapshot')
	));

	siteorigin_settings_add_field('general', 'search_menu_text', 'text', __('Search Text in Menu', 'snapshot'), array(
		'description' => __('The search text to display in your menu.', 'snapshot')
	));

	siteorigin_settings_add_field('general', 'attribution', 'checkbox', __('Attribution Link', 'snapshot'), array(
		'description' => __('Hide or display "Theme By SiteOrigin" link from your footer.', 'snapshot')
	));

	siteorigin_settings_add_field('appearance', 'style', 'select', __('Style', 'snapshot'), array(
		'options' => array(
			'light' => __('Light', 'snapshot'),
			'dark' => __('Dark', 'snapshot'),
		)
	));

	siteorigin_settings_add_field('slider', 'posts', 'select', __('Posts Order', 'snapshot'), array(
		'description' => __('How Snapshot chooses your home page slides.', 'snapshot'),
		'options' => array(
			'date' => __('Post Date', 'snapshot'),
			'modified' => __('Modified Date', 'snapshot'),
			'rand' => __('Random', 'snapshot'),
			'comment_count' => __('By Comment Count', 'snapshot'),
		)
	));

	$category_options = array(
		0 => __('All', 'snapshot'),
	);
	$cats = get_categories();
	if(!empty($cats)){
		foreach(get_categories() as $cat){
			$category_options[$cat->term_id] = $cat->name;
		}
	}
	siteorigin_settings_add_field('slider', 'category', 'select', __('Posts Category', 'snapshot'), array(
		'description' => __('Choose which posts are displayed on your home page slider.', 'snapshot'),
		'options' => $category_options,
	));

	siteorigin_settings_add_field('comments', 'ajax', 'checkbox', __('Ajax Comments', 'snapshot'), array(
		'description' => __('Let your visitors post comments without leaving the page.', 'snapshot')
	));
}
add_action('admin_init', 'snapshot_premium_admin_init', 11);

/**
 * Set up the default settings
 * @param $defaults
 * @return array
 *
 * @filter siteorigin_theme_default_settings
 */
function snapshot_premium_settings_default($defaults){
	$defaults['general_search_menu_text'] = __('Search', 'snapshot');
	return $defaults;
}
add_filter('siteorigin_theme_default_settings', 'snapshot_premium_settings_default');