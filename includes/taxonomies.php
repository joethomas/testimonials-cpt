<?php
defined('ABSPATH') || exit;

function tcpt_register_taxonomies(){
	$cat_labels = array(
		'name'			  => _x('Testimonial Categories', 'taxonomy general name', TCPT_TEXTDOMAIN),
		'singular_name'	 => _x('Testimonial Category', 'taxonomy singular name', TCPT_TEXTDOMAIN),
		'search_items'	  => __('Search Categories', TCPT_TEXTDOMAIN),
		'all_items'		 => __('All Categories', TCPT_TEXTDOMAIN),
		'parent_item'	   => __('Parent Category', TCPT_TEXTDOMAIN),
		'parent_item_colon' => __('Parent Category:', TCPT_TEXTDOMAIN),
		'edit_item'		 => __('Edit Category', TCPT_TEXTDOMAIN),
		'update_item'	   => __('Update Category', TCPT_TEXTDOMAIN),
		'add_new_item'	  => __('Add New Category', TCPT_TEXTDOMAIN),
		'new_item_name'	 => __('New Category Name', TCPT_TEXTDOMAIN),
		'menu_name'		 => __('Categories', TCPT_TEXTDOMAIN),
	);
	register_taxonomy(
		'testimonial_category',
		array(TCPT_SLUG),
		array(
			'labels' => $cat_labels,
			'hierarchical' => true,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'testimonial-category', 'with_front' => false),
		)
	);

	$tag_labels = array(
		'name'			  => _x('Testimonial Tags', 'taxonomy general name', TCPT_TEXTDOMAIN),
		'singular_name'	 => _x('Testimonial Tag', TCPT_TEXTDOMAIN),
		'search_items'	  => __('Search Tags', TCPT_TEXTDOMAIN),
		'all_items'		 => __('All Tags', TCPT_TEXTDOMAIN),
		'edit_item'		 => __('Edit Tag', TCPT_TEXTDOMAIN),
		'update_item'	   => __('Update Tag', TCPT_TEXTDOMAIN),
		'add_new_item'	  => __('Add New Tag', TCPT_TEXTDOMAIN),
		'new_item_name'	 => __('New Tag Name', TCPT_TEXTDOMAIN),
		'menu_name'		 => __('Tags', TCPT_TEXTDOMAIN),
	);
	register_taxonomy(
		'testimonial_tag',
		array(TCPT_SLUG),
		array(
			'labels' => $tag_labels,
			'hierarchical' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array('slug' => 'testimonial-tag', 'with_front' => false),
		)
	);
}
add_action('init', 'tcpt_register_taxonomies', 11);
