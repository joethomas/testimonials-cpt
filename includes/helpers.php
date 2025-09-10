<?php
defined('ABSPATH') || exit;

function tcpt_render_stars($rating){
	$rating = (int) $rating;
	$rating = max(0, min(5, $rating));
	$out = '';
	for ($i=1; $i<=5; $i++){
		$out .= $i <= $rating ? '★' : '☆';
	}
	return esc_html($out);
}
