<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_enqueue_style( 'quillmoor', get_theme_file_uri( 'assets/quillmoor.css' ), array(), wp_get_theme()->get( 'Version' ) );
	}
);

add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/quillmoor.css' );
	}
);

/** Lets each page carry its own season color. */
add_filter(
	'body_class',
	static function ( $classes ) {
		if ( is_page() ) {
			$classes[] = 'page-slug-' . get_post_field( 'post_name', get_queried_object_id() );
		}
		return $classes;
	}
);

/** Description, sharing image and icon. */
add_action(
	'wp_head',
	static function () {
		$description = get_bloginfo( 'description' );
		$image       = '';
		if ( is_singular() ) {
			$excerpt = wp_strip_all_tags( get_the_excerpt() );
			if ( $excerpt && ! is_front_page() ) {
				$description = $excerpt;
			}
			$image = get_the_post_thumbnail_url( null, 'large' );
		}
		if ( ! $image ) {
			$image = get_theme_file_uri( 'assets/og.jpg' );
		}
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( wp_get_document_title() ) );
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		printf( '<link rel="icon" type="image/svg+xml" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/icon.svg' ) ) );
	},
	1
);
