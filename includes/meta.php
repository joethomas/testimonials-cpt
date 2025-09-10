<?php
defined('ABSPATH') || exit;

function tcpt_sanitize_int_rating($value){
	$val = (int)$value;
	if ($val < 0) $val = 0;
	if ($val > 5) $val = 5;
	return $val;
}
function tcpt_sanitize_text($value){ return sanitize_text_field($value); }
function tcpt_sanitize_url($value){
	$url = trim((string)$value);
	if ($url === '') return '';
	return esc_url_raw($url);
}

add_action('init', function(){
	$args_common = array(
		'single' => true,
		'show_in_rest' => array('schema' => array('type' => 'string')),
		'auth_callback' => function(){ return current_user_can('edit_posts'); },
	);
	register_post_meta(TCPT_SLUG, 'tcpt_name', array_merge($args_common, array(
		'type' => 'string',
		'sanitize_callback' => 'tcpt_sanitize_text',
	)));
	register_post_meta(TCPT_SLUG, 'tcpt_source', array_merge($args_common, array(
		'type' => 'string',
		'sanitize_callback' => 'tcpt_sanitize_text',
	)));
	register_post_meta(TCPT_SLUG, 'tcpt_source_url', array_merge($args_common, array(
		'type' => 'string',
		'sanitize_callback' => 'tcpt_sanitize_url',
	)));
	register_post_meta(TCPT_SLUG, 'tcpt_rating', array(
		'type' => 'integer',
		'single' => true,
		'show_in_rest' => array('schema' => array('type' => 'integer', 'minimum' => 0, 'maximum' => 5)),
		'default' => 0,
		'sanitize_callback' => 'tcpt_sanitize_int_rating',
		'auth_callback' => function(){ return current_user_can('edit_posts'); },
	));
});

add_action('add_meta_boxes', function(){
	add_meta_box('tcpt_details', __('Testimonial Details', TCPT_TEXTDOMAIN), 'tcpt_render_meta_box', TCPT_SLUG, 'normal', 'high');
});

function tcpt_render_meta_box($post){
	wp_nonce_field('tcpt_save_meta', 'tcpt_meta_nonce');
	$name	   = get_post_meta($post->ID, 'tcpt_name', true);
	$source	 = get_post_meta($post->ID, 'tcpt_source', true);
	$source_url = get_post_meta($post->ID, 'tcpt_source_url', true);
	$rating	 = (int) get_post_meta($post->ID, 'tcpt_rating', true); ?>
	<div class="tcpt-fields">
		<p><label for="tcpt_name"><strong><?php esc_html_e('Name', TCPT_TEXTDOMAIN); ?></strong></label><br />
		<input type="text" id="tcpt_name" name="tcpt_name" class="widefat" value="<?php echo esc_attr($name); ?>" /></p>

		<p><label for="tcpt_source"><strong><?php esc_html_e('Source', TCPT_TEXTDOMAIN); ?></strong> <span class="description">(<?php esc_html_e('e.g., Company or Publication', TCPT_TEXTDOMAIN); ?>)</span></label><br />
		<input type="text" id="tcpt_source" name="tcpt_source" class="widefat" value="<?php echo esc_attr($source); ?>" /></p>

		<p><label for="tcpt_source_url"><strong><?php esc_html_e('Source URL (optional)', TCPT_TEXTDOMAIN); ?></strong></label><br />
		<input type="url" id="tcpt_source_url" name="tcpt_source_url" class="widefat" value="<?php echo esc_attr($source_url); ?>" placeholder="https://example.com" /></p>

		<p><label for="tcpt_rating"><strong><?php esc_html_e('Rating (0–5)', TCPT_TEXTDOMAIN); ?></strong></label><br />
		<input type="number" id="tcpt_rating" name="tcpt_rating" min="0" max="5" step="1" value="<?php echo esc_attr($rating); ?>" /></p>
	</div><?php
}

add_action('save_post_' . TCPT_SLUG, function($post_id){
	if (!isset($_POST['tcpt_meta_nonce']) || !wp_verify_nonce($_POST['tcpt_meta_nonce'], 'tcpt_save_meta')) return;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	$fields = array(
		'tcpt_name'	   => 'tcpt_sanitize_text',
		'tcpt_source'	 => 'tcpt_sanitize_text',
		'tcpt_source_url' => 'tcpt_sanitize_url',
		'tcpt_rating'	 => 'tcpt_sanitize_int_rating',
	);
	foreach ($fields as $key => $cb){
		$val = isset($_POST[$key]) ? call_user_func($cb, $_POST[$key]) : '';
		if ($key === 'tcpt_rating') $val = (int) $val;
		if ($val === '' && $key !== 'tcpt_rating'){
			delete_post_meta($post_id, $key);
		}else{
			update_post_meta($post_id, $key, $val);
		}
	}
}, 10, 1);
