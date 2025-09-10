<?php
defined('ABSPATH') || exit;

/**
 * Query helper
 */
function tcpt_get_testimonials($args = array()){
	$defaults = array(
		'post_type'	  => TCPT_SLUG,
		'post_status'	=> 'publish',
		'posts_per_page' => 5,
		'orderby'		=> 'date',
		'order'		  => 'DESC',
	);
	$args = wp_parse_args($args, $defaults);
	return new WP_Query($args);
}

/**
 * [tcpt_testimonials] shortcode
 * Example: [tcpt_testimonials category="happy-clients" tag="enterprise" count="5" orderby="rating" order="DESC"]
 */
add_shortcode('tcpt_testimonials', function($atts){
	$atts = shortcode_atts(array(
		'category' => '',
		'tag'	  => '',
		'count'	=> 5,
		'orderby'  => 'date', // date|rating|rand
		'order'	=> 'DESC',
	), $atts, 'tcpt_testimonials');

	$query_args = array(
		'post_type'	  => TCPT_SLUG,
		'post_status'	=> 'publish',
		'posts_per_page' => (int) $atts['count'],
		'order'		  => $atts['order'] === 'ASC' ? 'ASC' : 'DESC',
	);

	if ($atts['orderby'] === 'rating'){
		$query_args['meta_key'] = 'tcpt_rating';
		$query_args['orderby']  = 'meta_value_num';
	}elseif ($atts['orderby'] === 'rand'){
		$query_args['orderby']  = 'rand';
	}else{
		$query_args['orderby']  = 'date';
	}

	$tax_query = array();
	if (!empty($atts['category'])){
		$tax_query[] = array(
			'taxonomy' => 'testimonial_category',
			'field'	=> 'slug',
			'terms'	=> array_map('sanitize_title', array_map('trim', explode(',', $atts['category']))),
		);
	}
	if (!empty($atts['tag'])){
		$tax_query[] = array(
			'taxonomy' => 'testimonial_tag',
			'field'	=> 'slug',
			'terms'	=> array_map('sanitize_title', array_map('trim', explode(',', $atts['tag']))),
		);
	}
	if (!empty($tax_query)){
		$query_args['tax_query'] = $tax_query;
	}

	$q = new WP_Query($query_args);

	ob_start();
	if ($q->have_posts()){
		echo '<div class="tcpt-list">';
		while ($q->have_posts()){ $q->the_post();
			$post_id = get_the_ID();
			$name	= get_post_meta($post_id, 'tcpt_name', true);
			$source  = get_post_meta($post_id, 'tcpt_source', true);
			$url	 = get_post_meta($post_id, 'tcpt_source_url', true);
			$rating  = (int) get_post_meta($post_id, 'tcpt_rating', true);
			?>
			<article class="tcpt-item" itemscope itemtype="https://schema.org/Review">
				<div class="tcpt-content" itemprop="reviewBody"><?php echo wpautop(esc_html(get_the_content())); ?></div>
				<div class="tcpt-meta">
					<?php if ($name): ?>
						<div class="tcpt-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
							<span itemprop="name"><?php echo esc_html($name); ?></span>
						</div>
					<?php endif; ?>
					<?php if ($source): ?>
						<div class="tcpt-source">
							<?php if ($url): ?>
								<a href="<?php echo esc_url($url); ?>" rel="nofollow noopener" target="_blank"><?php echo esc_html($source); ?></a>
							<?php else: ?>
								<span><?php echo esc_html($source); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ($rating >= 0): ?>
						<div class="tcpt-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
							<meta itemprop="bestRating" content="5" />
							<span class="tcpt-stars" aria-label="<?php echo esc_attr($rating . ' / 5'); ?>">
								<?php echo tcpt_render_stars($rating); ?>
							</span>
							<meta itemprop="ratingValue" content="<?php echo esc_attr($rating); ?>" />
						</div>
					<?php endif; ?>
				</div>
			</article>
			<?php
		}
		echo '</div>';
		wp_reset_postdata();
	}else{
		echo '<p class="tcpt-none">' . esc_html__('No testimonials found.', TCPT_TEXTDOMAIN) . '</p>';
	}
	return ob_get_clean();
});
