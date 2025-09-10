<?php
defined('ABSPATH') || exit;

function tcpt_register_post_type(){
	$labels = array(
		'name'				  => _x('Testimonials', 'Post type general name', TCPT_TEXTDOMAIN),
		'singular_name'		 => _x('Testimonial', 'Post type singular name', TCPT_TEXTDOMAIN),
		'menu_name'			 => _x('Testimonials', 'Admin Menu text', TCPT_TEXTDOMAIN),
		'name_admin_bar'		=> _x('Testimonial', 'Add New on Toolbar', TCPT_TEXTDOMAIN),
		'add_new'			   => __('Add New', TCPT_TEXTDOMAIN),
		'add_new_item'		  => __('Add New Testimonial', TCPT_TEXTDOMAIN),
		'new_item'			  => __('New Testimonial', TCPT_TEXTDOMAIN),
		'edit_item'			 => __('Edit Testimonial', TCPT_TEXTDOMAIN),
		'view_item'			 => __('View Testimonial', TCPT_TEXTDOMAIN),
		'all_items'			 => __('All Testimonials', TCPT_TEXTDOMAIN),
		'search_items'		  => __('Search Testimonials', TCPT_TEXTDOMAIN),
		'not_found'			 => __('No testimonials found.', TCPT_TEXTDOMAIN),
		'not_found_in_trash'	=> __('No testimonials found in Trash.', TCPT_TEXTDOMAIN),
		'featured_image'		=> _x('Featured Image', 'testimonial', TCPT_TEXTDOMAIN),
		'set_featured_image'	=> _x('Set featured image', 'testimonial', TCPT_TEXTDOMAIN),
		'remove_featured_image' => _x('Remove featured image', 'testimonial', TCPT_TEXTDOMAIN),
		'use_featured_image'	=> _x('Use as featured image', 'testimonial', TCPT_TEXTDOMAIN),
		'archives'			  => _x('Testimonial archives', 'testimonial', TCPT_TEXTDOMAIN),
	);

	$args = array(
		'labels'			 => $labels,
		'public'			 => true,
		'publicly_queryable' => true,
		'show_ui'			=> true,
		'show_in_menu'	   => true,
		'show_in_rest'	   => true,
		'query_var'		  => true,
		'rewrite'			=> array('slug' => TCPT_SLUG, 'with_front' => false),
		'capability_type'	=> 'post',
		'map_meta_cap'	   => true,
		'has_archive'		=> true,
		'hierarchical'	   => false,
		'menu_position'	  => 20,
		'menu_icon'		  => 'dashicons-format-quote',
		'supports'		   => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
		'show_in_nav_menus'  => true,
	);

	register_post_type(TCPT_SLUG, $args);
}
add_action('init', 'tcpt_register_post_type');
