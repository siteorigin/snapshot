<?php

function photography_upgrade_text($text){
	$text['purchase_url'] = 'http://go.siteorigin.com/photography-premium';
	$text['first_line'] = __('If you’ve enjoyed using Photography, you’re going to love Photography Premium. It’s a robust upgrade to Photography that gives you loads of cool features and premium support. At just $7.50, it’s a cost effective way to give your site a professional edge.', 'photography');
	
	$text['below_first_buy'] = __("If you're not delighted with Photography Premium, I'll give you a full refund", 'photography');
	
	$text['features'] = array(
		array(
			'title' => __('Premium Support', 'photography'),
			'text' => __("New to WordPress? Need to chat to someone? There is a WordPress guru, waiting to answer your questions. You'll be familiar with our theme in no time.", 'photography'),
		),
		array(
			'title' => __('Sprite Maps', 'photography'),
			'text' => __('If you’re targeting a perfect Google PageSpeed score and all the SEO benefits it brings, then sprite maps are essential. They’ll make your site load faster and put less load on your servers - saving you cash.', 'photography'),
		),
		array(
			'title' => __('Video Posts', 'photography'),
			'text' => __('Are you more of a video guy/gal? Photography Premium makes adding videos, from any popular video sharing site, easy. Your video goes front and center - where the featured photo usually goes. Perfect for your video blog or web series.', 'photography'),
		),
		array(
			'title' => __('Ajax Comments', 'photography'),
			'text' => __('Keep the conversation flowing with ajax comments. Your visitors will be able to post comments without leaving the page or interrupting your videos. Ajax comments makes commenting more enjoyable for your visitors. So you’ll probably get more comments and more visitors.', 'photography'),
		),
		array(
			'title' => __('A Dashing Dark Style', 'photography'),
			'text' => __('Prefer to keep the lights out? Photography Premium ships with both dark and light styles. So you can set the mood you really want.', 'photography'),
		),
		array(
			'title' => __('Remove Attribution Links', 'photography'),
			'text' => __('Want to look professional? Photography Premium gives you the option to remove the “Theme by SiteOrigin” text from your footer in the options panel.', 'photography'),
		),
		array(
			'title' => __('Slick Search', 'photography'),
			'text' => __('Photography Premium has an option to add a non-obtrusive search button to your main menu. Clicking it slides out a big, beautiful search bar so your visitors can explore your content.', 'photography'),
		),
		array(
			'title' => __('Continued Updates', 'photography'),
			'text' => __("We'll keep Photography Premium up to date with new features and performance enhancements.", 'photography'),
		),
	);
	
	$text['below_second_buy'] = __("Risk Free Money Back Guarantee", 'photography');
	
	$text['promo_image'] = array(
		'http://sopremium.s3.amazonaws.com/photography-premium.jpg',
		1280,
		960
	);
	
	return $text;
}
add_filter('so_premium_page', 'photography_upgrade_text');