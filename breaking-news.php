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
 * Require Dashboard Class and Post Meta Class
 */
require_once BN_DIR . '/includes/class/class-settings-page.php';
require_once BN_DIR . '/includes/class/class-post-meta.php';
require_once BN_DIR . '/includes/class/class-frontend.php';

/**
 * Register And Load Settings Page
 */
if (class_exists('BN_Settings_Page')) {
	$get_dashboard = new BN_Settings_Page();
	$dashboard = $get_dashboard->getInstance();
	$init_settings_page = $dashboard->init();
}
/**
 * Register and load metaboxes
 */
if (class_exists('BN_Post_Metabox')) {
	$get_metaboxes = new BN_Post_Metabox();
	$metaboxes = $get_metaboxes->getInstance();
	$init_metaboxes = $metaboxes->init();
}
/**
 * Register and load front-end
 */
if (class_exists('BN_Frontend')) {
	$get_frontend = new BN_Frontend();
	$frontend_class = $get_frontend->getInstance();
	$init_frontend = $frontend_class->init();
}