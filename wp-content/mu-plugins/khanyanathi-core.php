<?php
/**
 * Plugin Name: Khanya Nathi Core
 * Description: Site-specific code for Khanya Nathi. Keeps ACF field groups, post types and taxonomies in Git via ACF Local JSON.
 */

defined( 'ABSPATH' ) || exit;

// Save ACF field groups, post types, taxonomies and options pages to wp-content/acf-json.
add_filter( 'acf/settings/save_json', function () {
	return WP_CONTENT_DIR . '/acf-json';
} );

// Load them from the same folder, so a pull from Git shows up under ACF > Sync.
add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = WP_CONTENT_DIR . '/acf-json';
	return $paths;
} );
