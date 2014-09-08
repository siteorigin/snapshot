<?php

function snapshot_premium_upgrade_content($content){
	$content['premium_title'] = __('Upgrade To Snapshot Plus', 'snapshot');
	$content['premium_summary'] = __("If you've enjoyed using Snapshot, you'll going to love Snapshot Plus. It's a robust upgrade to Snapshot that gives you loads of cool features and email support. Best of all, it's free! All you need to do is sign up to our newsletter. It's a great way to give your site a professional edge.", 'snapshot');
	
	$content['free_download'] = true;
	$content['buy_url'] = 'http://siteorigin.com/theme/snapshot/?action=plus';
	$content['premium_video_poster'] = get_template_directory_uri().'/upgrade/poster.jpg';
	$content['premium_video_id'] = '60073475';

	$content['features'] = array(
		array(
			'heading' => __('Free Forum Support', 'snapshot'),
			'content' => __("New to WordPress? Need to chat to someone? The SiteOrigin support team and I are waiting to answer your questions.", 'snapshot'),
		),
		array(
			'heading' => __('Sprite Maps', 'snapshot'),
			'content' => __("If you're targeting a perfect Google PageSpeed score and all the SEO benefits it brings, then sprite maps are essential. They'll make your site load faster and put less load on your servers - saving you cash.", 'snapshot'),
		),
		array(
			'heading' => __('Video Posts', 'snapshot'),
			'content' => __('Are you more of a video creator? Snapshot Plus makes adding videos, from any popular video sharing site, easy. Your video goes front and center - where the featured photo usually goes. Perfect for your video blog or web series.', 'snapshot'),
		),
		array(
			'heading' => __('Ajax Comments', 'snapshot'),
			'content' => __("Keep the conversation flowing with ajax comments. Your visitors will be able to post comments without leaving the page or interrupting your videos. Ajax comments makes commenting more enjoyable for your visitors. So you'll probably get more comments and more visitors.", 'snapshot'),
		),
		array(
			'heading' => __('A Dashing Dark Style', 'snapshot'),
			'content' => __('Prefer to keep the lights out? Snapshot Plus ships with both dark and light styles. So you can set the mood you really want.', 'snapshot'),
		),
		array(
			'heading' => __('Remove Attribution Links', 'snapshot'),
			'content' => __('Want to look more professional? Snapshot Plus gives you the option to remove the "Theme by SiteOrigin" text from your footer in the options panel.', 'snapshot'),
		),
		array(
			'heading' => __('Slick Search', 'snapshot'),
			'content' => __('Snapshot Plus has an option to add a non-obtrusive search button to your main menu. Clicking it slides out a big, beautiful search bar so your visitors can explore your content.', 'snapshot'),
		),
		array(
			'heading' => __('Continued Updates', 'snapshot'),
			'content' => __("You'll help support the continued development of Snapshot - ensuring it works with future versions of WordPress for years to come.", 'snapshot'),
		),
	);

	$content['testimonials'] = array(
		array(
			'gravatar' => '8d520667fcdf094093565fc6c7b63ba0',
			'name' => 'Iisakki',
			'content' => __("Beautiful and works well!", 'snapshot'),
		),
		array(
			'gravatar' => 'c26b8fd861d4093acf1c6c61c0a84f3b',
			'name' => 'DFWmax',
			'content' => __("This theme incorporates the best of the themes I've seen. Thank you for your effort. It has made my job much easier.", 'snapshot'),
		),
	);

	return $content;
}
add_filter('siteorigin_premium_content', 'snapshot_premium_upgrade_content');