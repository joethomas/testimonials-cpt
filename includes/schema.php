<?php
defined('ABSPATH') || exit;

add_action('wp_head', function(){
	if (!is_singular(TCPT_SLUG)) return;
	$enabled = apply_filters('tcpt_schema_enabled', true);
	if (!$enabled) return;

	global $post;
	$post_id = $post->ID;
	$name	= get_post_meta($post_id, 'tcpt_name', true);
	$source  = get_post_meta($post_id, 'tcpt_source', true);
	$url	 = get_post_meta($post_id, 'tcpt_source_url', true);
	$rating  = (int) get_post_meta($post_id, 'tcpt_rating', true);
	$rating  = max(0, min(5, $rating));

	$data = array(
		'@context'	=> 'https://schema.org',
		'@type'	   => 'Review',
		'name'		=> get_the_title($post_id),
		'reviewBody'  => wp_strip_all_tags(get_the_content(null, false, $post_id)),
		'author'	  => array('@type' => 'Person','name'  => $name ? $name : __('Anonymous', TCPT_TEXTDOMAIN)),
		'reviewRating' => array('@type' => 'Rating','ratingValue' => (int) $rating,'bestRating'  => 5),
	);
	if ($source){
		$data['publisher'] = array('@type' => 'Organization','name'  => $source);
		if ($url){ $data['publisher']['url'] = esc_url($url); }
	}
	echo '<script type="application/ld+json">' . wp_json_encode($data) . '</script>';
}, 99);
