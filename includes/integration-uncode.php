<?php
defined('ABSPATH') || exit;

/**
 * Uncode-friendly loop integration and shortcodes.
 */

function tcpt_render_signature_block($post_id = 0){
	$post_id = $post_id ? $post_id : get_the_ID();
	$name	= get_post_meta($post_id, 'tcpt_name', true);
	$source  = get_post_meta($post_id, 'tcpt_source', true);
	$url	 = get_post_meta($post_id, 'tcpt_source_url', true);
	$rating  = (int) get_post_meta($post_id, 'tcpt_rating', true);
	$rating  = max(0, min(5, $rating));
	if (!$name && !$source && !$rating){ return ''; }
	ob_start(); ?>
	<div class="tcpt-signature">
		<?php if ($name): ?><div class="tcpt-signature-name"><?php echo esc_html($name); ?></div><?php endif; ?>
		<?php if ($source): ?>
			<div class="tcpt-signature-source">
				<?php if ($url): ?>
					<a href="<?php echo esc_url($url); ?>" target="_blank" rel="nofollow noopener"><?php echo esc_html($source); ?></a>
				<?php else: ?>
					<span><?php echo esc_html($source); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="tcpt-signature-rating" aria-label="<?php echo esc_attr($rating . ' / 5'); ?>"><?php echo tcpt_render_stars($rating); ?></div>
	</div>
	<?php return ob_get_clean();
}

add_filter('the_content', function($content){
	if (get_post_type() !== TCPT_SLUG) return $content;
	if (is_singular(TCPT_SLUG)) return $content;
	$signature = tcpt_render_signature_block(get_the_ID());
	if (!$signature) return $content;
	return $content . "\n\n" . $signature;
}, 15);

add_filter('the_excerpt', 'do_shortcode', 11);

add_shortcode('tcpt_signature', function($atts){ return tcpt_render_signature_block(get_the_ID()); });
add_shortcode('tcpt_name', function($atts){ $n=get_post_meta(get_the_ID(),'tcpt_name',true); return $n?esc_html($n):''; });
add_shortcode('tcpt_source', function($atts){
	$atts = shortcode_atts(array('link'=>'yes'), $atts, 'tcpt_source');
	$s = get_post_meta(get_the_ID(),'tcpt_source',true);
	$u = get_post_meta(get_the_ID(),'tcpt_source_url',true);
	if (!$s) return '';
	if ($atts['link']==='yes' && $u){ return '<a href="'.esc_url($u).'" target="_blank" rel="nofollow noopener">'.esc_html($s).'</a>'; }
	return esc_html($s);
});
add_shortcode('tcpt_rating_stars', function($atts){ $r=(int)get_post_meta(get_the_ID(),'tcpt_rating',true); return tcpt_render_stars($r); });
