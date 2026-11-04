<?php
defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		register_post_type(
			'quill_visit',
			array(
				'labels'          => array(
					'name'          => __( 'Site visit requests', 'quillmoor-core' ),
					'singular_name' => __( 'Site visit request', 'quillmoor-core' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'menu_icon'       => 'dashicons-email-alt',
				'supports'        => array( 'title', 'editor' ),
				'capability_type' => 'post',
				'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'    => true,
			)
		);
	}
);

add_action( 'admin_post_nopriv_quillmoor_visit', 'quillmoor_handle_visit' );
add_action( 'admin_post_quillmoor_visit', 'quillmoor_handle_visit' );

/** Saves a site-visit request to the dashboard, then emails the studio. */
function quillmoor_handle_visit() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/site-visit/' );
	$back = remove_query_arg( array( 'visit', 'town', 'k' ), $back );

	if ( ! isset( $_POST['quillmoor_visit_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['quillmoor_visit_nonce'] ) ), 'quillmoor_visit' ) ) {
		wp_safe_redirect( add_query_arg( 'visit', 'expired', $back ) );
		exit;
	}

	// Bots fill this hidden field; people never see it. Pretend it worked.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'visit', 'sent', $back ) );
		exit;
	}

	$name    = isset( $_POST['visit_name'] ) ? sanitize_text_field( wp_unslash( $_POST['visit_name'] ) ) : '';
	$email   = isset( $_POST['visit_email'] ) ? sanitize_email( wp_unslash( $_POST['visit_email'] ) ) : '';
	$town    = isset( $_POST['visit_town'] ) ? sanitize_text_field( wp_unslash( $_POST['visit_town'] ) ) : '';
	$message = isset( $_POST['visit_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['visit_message'] ) ) : '';

	if ( '' === $name || ! is_email( $email ) ) {
		// Keep what they typed, server side, so nothing personal goes in the URL.
		$key = wp_generate_password( 12, false );
		set_transient( 'quillmoor_visit_' . $key, compact( 'name', 'email', 'town', 'message' ), 10 * MINUTE_IN_SECONDS );
		wp_safe_redirect( add_query_arg( array( 'visit' => 'missing', 'k' => $key ), $back ) );
		exit;
	}

	$body = sprintf( "Town: %s\nEmail: %s\n\n%s", $town, $email, $message );

	wp_insert_post(
		array(
			'post_type'    => 'quill_visit',
			'post_status'  => 'private',
			'post_title'   => sprintf( '%s, %s', $name, $town ),
			'post_content' => $body,
		)
	);

	wp_mail( get_option( 'admin_email' ), sprintf( 'Site visit request from %s', $name ), $body, array( 'Reply-To: ' . $email ) );

	wp_safe_redirect( add_query_arg( 'visit', 'sent', $back ) );
	exit;
}

/** Towns offered in both forms, one per line, editable under Settings > General. */
function quillmoor_visit_towns() {
	$towns = get_option( 'quillmoor_towns', "Bedford\nKatonah\nPound Ridge\nNorth Salem\nSouth Salem\nSomers\nArmonk\nSomewhere else" );
	return array_values( array_filter( array_map( 'trim', explode( "\n", $towns ) ) ) );
}

add_action(
	'admin_init',
	static function () {
		register_setting(
			'general',
			'quillmoor_towns',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);
		add_settings_field(
			'quillmoor_towns',
			__( 'Towns you serve', 'quillmoor-core' ),
			static function () {
				printf(
					'<textarea id="quillmoor_towns" name="quillmoor_towns" rows="8" class="regular-text">%s</textarea><p class="description">%s</p>',
					esc_textarea( implode( "\n", quillmoor_visit_towns() ) ),
					esc_html__( 'One per line. These appear in the site-visit forms.', 'quillmoor-core' )
				);
			},
			'general'
		);
	}
);
