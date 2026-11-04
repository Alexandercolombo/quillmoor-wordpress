<?php
/**
 * Plugin Name: Quillmoor Core
 * Description: Garden projects, site-visit requests and custom blocks for the Quillmoor site. Keeps content safe if the theme ever changes.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Text Domain: quillmoor-core
 */

defined( 'ABSPATH' ) || exit;

define( 'QUILLMOOR_CORE_DIR', plugin_dir_path( __FILE__ ) );

require QUILLMOOR_CORE_DIR . 'inc/gardens.php';
require QUILLMOOR_CORE_DIR . 'inc/visits.php';

add_action(
	'init',
	static function () {
		foreach ( array( 'season-strip', 'bloom-chart', 'visit-start', 'visit-form' ) as $block ) {
			register_block_type( QUILLMOOR_CORE_DIR . 'blocks/' . $block );
		}
	}
);

add_filter(
	'block_categories_all',
	static function ( $categories ) {
		array_unshift(
			$categories,
			array(
				'slug'  => 'quillmoor',
				'title' => __( 'Quillmoor', 'quillmoor-core' ),
			)
		);
		return $categories;
	}
);
