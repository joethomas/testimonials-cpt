<?php
defined('ABSPATH') || exit;

add_filter('manage_edit-' . TCPT_SLUG . '_columns', function($columns){
	// Insert custom columns after title
	$new = array();
	foreach ($columns as $key => $label){
		$new[$key] = $label;
		if ($key === 'title'){
			$new['tcpt_name']   = __('Name', TCPT_TEXTDOMAIN);
			$new['tcpt_source'] = __('Source', TCPT_TEXTDOMAIN);
			$new['tcpt_rating'] = __('Rating', TCPT_TEXTDOMAIN);
		}
	}
	return $new;
});

add_action('manage_' . TCPT_SLUG . '_posts_custom_column', function($column, $post_id){
	switch ($column){
		case 'tcpt_name':
			echo esc_html(get_post_meta($post_id, 'tcpt_name', true));
			break;
		case 'tcpt_source':
			$source = get_post_meta($post_id, 'tcpt_source', true);
			$url = get_post_meta($post_id, 'tcpt_source_url', true);
			if ($source && $url){
				echo '<a href="' . esc_url($url) . '" target="_blank" rel="nofollow noopener">' . esc_html($source) . '</a>';
			}else{
				echo esc_html($source);
			}
			break;
		case 'tcpt_rating':
			$rating = (int) get_post_meta($post_id, 'tcpt_rating', true);
			echo esc_html($rating) . ' / 5';
			break;
	}
}, 10, 2);

add_filter('manage_edit-' . TCPT_SLUG . '_sortable_columns', function($columns){
	$columns['tcpt_rating'] = 'tcpt_rating';
	return $columns;
});

add_action('pre_get_posts', function($query){
	if (!is_admin() || !$query->is_main_query()) return;
	$orderby = $query->get('orderby');
	if ($orderby === 'tcpt_rating'){
		$query->set('meta_key', 'tcpt_rating');
		$query->set('orderby', 'meta_value_num');
	}
});
