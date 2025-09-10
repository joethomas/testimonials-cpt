<?php
defined('ABSPATH') || exit;

/**
 * Register WPBakery elements (for Content Blocks / builder use).
 */
function tcpt_vc_elements_register(){
	if (!function_exists('vc_map')) return;
	$cat = __('Testimonials', TCPT_TEXTDOMAIN);
	$icon = 'dashicons-format-quote';

	vc_map(array(
		'name' => __('TCPT Name', TCPT_TEXTDOMAIN),
		'base' => 'tcpt_name',
		'icon' => $icon,
		'category' => $cat,
		'description' => __('Outputs the testimonial Name field.', TCPT_TEXTDOMAIN),
		'params' => array(),
	));

	vc_map(array(
		'name' => __('TCPT Source', TCPT_TEXTDOMAIN),
		'base' => 'tcpt_source',
		'icon' => $icon,
		'category' => $cat,
		'description' => __('Outputs the testimonial Source (optionally linked).', TCPT_TEXTDOMAIN),
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => __('Link to Source URL', TCPT_TEXTDOMAIN),
				'param_name' => 'link',
				'value' => array(__('Yes', TCPT_TEXTDOMAIN) => 'yes', __('No', TCPT_TEXTDOMAIN) => 'no'),
				'std' => 'yes',
			),
		),
	));

	vc_map(array(
		'name' => __('TCPT Rating Stars', TCPT_TEXTDOMAIN),
		'base' => 'tcpt_rating_stars',
		'icon' => $icon,
		'category' => $cat,
		'description' => __('Outputs stars based on the Rating (0–5).', TCPT_TEXTDOMAIN),
		'params' => array(),
	));
}
add_action('vc_before_init', 'tcpt_vc_elements_register');
