<?php
/*
Plugin Name:       Breaking News
Description:       Breaking News
Version:           1.0
Author:            Waleed Omar
Author Url:        https://www.linkedin.com/in/waleed-omar-a14b53153/
 */

// Avoid direct calls to this file.
if (!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

/**
 * Define Plugin Path
 */
define('BN_DIR', untrailingslashit(dirname(__FILE__)));
define('BN_PATH', untrailingslashit(plugin_dir_url(__FILE__)));

/**
 * Plugin Translation
 * @package expandcart
 */
add_action('init', function () {
	load_plugin_textdomain('breaking_news', false, dirname(plugin_basename(__FILE__)) . '/language/');
});

/**
 * Require Database and Entires Class
 */
require_once BN_DIR . '/includes/class-settings-page.php';

/**
 * Require Dashboard Class and Post Meta Class
 */
require_once BN_DIR . '/includes/class-settings-page.php';
require_once BN_DIR . '/includes/class-post-meta.php';
require_once BN_DIR . '/includes/class-frontend.php';

/**
 * Init Plugin
 */
if (class_exists('BN_Settings_Page') && class_exists('BN_Post_Metabox')) {
	add_action('plugins_loaded', function () {
		$init_dashboard = new BN_Settings_Page();
		$init_metaboxes = BN_Post_Metabox::init();
		$init_frontend = new BN_Frontend();
	});
}
