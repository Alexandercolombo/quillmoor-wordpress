<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		register_post_type(
			'quill_garden',
			array(
				'labels'       => array(
					'name'          => __( 'Gardens', 'quillmoor-core' ),
					'singular_name' => __( 'Garden', 'quillmoor-core' ),
					'add_new_item'  => __( 'Add a garden', 'quillmoor-core' ),
					'edit_item'     => __( 'Edit garden', 'quillmoor-core' ),
				),
				'public'       => true,
				'has_archive'  => 'gardens',
				'rewrite'      => array( 'slug' => 'gardens' ),
				'menu_icon'    => 'dashicons-palmtree',
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
			)
		);

		$fields = array( 'garden_town', 'garden_size', 'garden_plant', 'garden_season', 'garden_best' );
		foreach ( $fields as $key ) {
			register_post_meta(
				'quill_garden',
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => static function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
);

/** Gives each garden its season color, on its own page and in any list of gardens. */
function quillmoor_garden_season_class( $post_id ) {
	$season = get_post_meta( $post_id, 'garden_season', true );
	return in_array( $season, array( 'april', 'july', 'october', 'january' ), true ) ? 'season-' . $season : 'season-july';
}

add_filter(
	'post_class',
	static function ( $classes, $class, $post_id ) {
		if ( 'quill_garden' === get_post_type( $post_id ) ) {
			$classes[] = 'packet';
			$classes[] = quillmoor_garden_season_class( $post_id );
		}
		return $classes;
	},
	10,
	3
);

add_filter(
	'body_class',
	static function ( $classes ) {
		if ( is_singular( 'quill_garden' ) ) {
			$classes[] = quillmoor_garden_season_class( get_queried_object_id() );
		}
		return $classes;
	}
);

/** A plain "Garden details" panel in the editor sidebar, so nobody has to touch custom fields. */
add_action(
	'enqueue_block_editor_assets',
	static function () {
		if ( 'quill_garden' !== get_post_type() ) {
			return;
		}
		wp_enqueue_script(
			'quillmoor-garden-panel',
			plugins_url( 'assets/garden-panel.js', QUILLMOOR_CORE_DIR . 'quillmoor-core.php' ),
			array( 'wp-plugins', 'wp-editor', 'wp-element', 'wp-components', 'wp-data', 'wp-core-data' ),
			'1.0.0',
			true
		);
	}
);
