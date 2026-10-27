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

// ACF location rule "Page path", e.g. ecd/resources. Page IDs differ between local and staging,
// so field groups for pages that are created later (like /ecd/resources/) match on the path instead.
add_action( 'acf/init', function () {
	if ( ! class_exists( 'ACF_Location' ) ) {
		return;
	}

	class KN_Location_Page_Path extends ACF_Location {
		public function initialize() {
			$this->name        = 'kn_page_path';
			$this->label       = 'Page path';
			$this->category    = 'page';
			$this->object_type = 'post';
		}

		public function match( $rule, $screen, $field_group ) {
			$post_id = $screen['post_id'] ?? 0;
			if ( ! $post_id || get_post_type( $post_id ) !== 'page' ) {
				return false;
			}
			return $this->compare_to_rule( get_page_uri( $post_id ), $rule );
		}

		public function get_values( $rule ) {
			$values = [];
			foreach ( get_pages( [ 'post_status' => [ 'publish', 'draft', 'private' ] ] ) as $page ) {
				$path            = get_page_uri( $page );
				$values[ $path ] = $path;
			}
			if ( ! empty( $rule['value'] ) ) {
				$values[ $rule['value'] ] = $rule['value'];
			}
			ksort( $values );
			return $values;
		}
	}

	acf_register_location_type( 'KN_Location_Page_Path' );
} );
