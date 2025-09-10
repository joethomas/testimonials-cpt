<?php
/**
 * Plugin Name: Testimonials CPT
 * Description: Custom post type "Testimonials" with categories, tags, meta fields (Name, Source, Source URL, Rating), shortcode, and JSON-LD schema.
 * Version: 0.9.0
 * Author: Joe Thomas
 * Text Domain: testimonials-cpt
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL-2.0-or-later
 *
 * GitHub Plugin URI: joethomas/testimonials-cpt
 * Primary Branch: main
 */

defined('ABSPATH') || exit;

// -----------------------------------------------------------------------------
// Constants
// -----------------------------------------------------------------------------
if (!defined('TCPT_VERSION')) define('TCPT_VERSION', '1.0.0');
if (!defined('TCPT_FILE')) define('TCPT_FILE', __FILE__);
if (!defined('TCPT_DIR')) define('TCPT_DIR', plugin_dir_path(__FILE__));
if (!defined('TCPT_URL')) define('TCPT_URL', plugin_dir_url(__FILE__));
if (!defined('TCPT_SLUG')) define('TCPT_SLUG', 'testimonials');
if (!defined('TCPT_TEXTDOMAIN')) define('TCPT_TEXTDOMAIN', 'testimonials-cpt');

// -----------------------------------------------------------------------------
// Bootstrap
// -----------------------------------------------------------------------------

add_action('plugins_loaded', function(){
	// Load translations
	load_plugin_textdomain(TCPT_TEXTDOMAIN, false, dirname(plugin_basename(TCPT_FILE)) . '/languages');
});

// Includes
require_once TCPT_DIR . 'includes/cpt.php';
require_once TCPT_DIR . 'includes/taxonomies.php';
require_once TCPT_DIR . 'includes/meta.php';
require_once TCPT_DIR . 'includes/admin-columns.php';
require_once TCPT_DIR . 'includes/shortcode.php';
require_once TCPT_DIR . 'includes/schema.php';
require_once TCPT_DIR . 'includes/helpers.php';

// Activation/Deactivation hooks for rewrite rules
register_activation_hook(TCPT_FILE, function(){
	// Ensure CPT & taxonomies are registered before flushing
	tcpt_register_post_type();
	tcpt_register_taxonomies();
	flush_rewrite_rules();
});
register_deactivation_hook(TCPT_FILE, function(){
	flush_rewrite_rules();
});
