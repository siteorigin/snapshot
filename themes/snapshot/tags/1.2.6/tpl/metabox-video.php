<?php
$image = get_post_meta(get_the_ID(), 'snapshot_post_image', true);
$image = wp_parse_args($image, array(
	'size' => 'single-large',
	'url' => '',
));
?>

<p><labal><?php _e('Main Image Size', 'snapshot') ?></labal></p>
<p>
	<select name="snapshot_post_image[size]">
		<option value="single-large" <?php selected('single-large', $image['size']) ?>><?php esc_html_e('Large', 'snapshot') ?></option>
		<option value="single-large-landscape" <?php selected('single-large-landscape', $image['size']) ?>><?php esc_html_e('Landscape', 'snapshot') ?></option>
	</select>
</p>
<p><labal><?php _e('Destination URL', 'snapshot') ?></labal></p>
<p>
	<input name="snapshot_post_image[url]" value="<?php echo esc_attr($image['url']) ?>" />
</p>

<?php wp_nonce_field('save', '_snapshot_nonce') ?>