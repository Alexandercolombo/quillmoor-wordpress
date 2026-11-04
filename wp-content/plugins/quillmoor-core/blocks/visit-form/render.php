<?php
defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Display-only values, checked against fixed lists.
$status   = isset( $_GET['visit'] ) ? sanitize_key( $_GET['visit'] ) : '';
$selected = isset( $_GET['town'] ) ? sanitize_text_field( wp_unslash( $_GET['town'] ) ) : '';
$kept     = array( 'name' => '', 'email' => '', 'town' => '', 'message' => '' );
if ( isset( $_GET['k'] ) ) {
	$saved = get_transient( 'quillmoor_visit_' . sanitize_key( $_GET['k'] ) );
	if ( is_array( $saved ) ) {
		$kept     = array_merge( $kept, $saved );
		$selected = $kept['town'];
	}
}
// phpcs:enable

$messages = array(
	'missing' => __( 'We need your name and a working email address so we can reply. Please check those two fields.', 'quillmoor-core' ),
	'expired' => __( 'This page was open too long and the form expired. Please send it again.', 'quillmoor-core' ),
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'visit-form-wrap' ) ); ?>>
	<?php if ( 'sent' === $status ) : ?>
		<p class="form-note" role="status"><strong><?php esc_html_e( 'Thank you. Your request is in.', 'quillmoor-core' ); ?></strong> <?php esc_html_e( 'On a real site this would go to the studio, who would reply within two working days.', 'quillmoor-core' ); ?></p>
	<?php else : ?>
		<?php if ( isset( $messages[ $status ] ) ) : ?>
			<p class="form-note is-error" role="alert"><?php echo esc_html( $messages[ $status ] ); ?></p>
		<?php endif; ?>
		<form class="visit-form" novalidate action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="quillmoor_visit">
			<?php wp_nonce_field( 'quillmoor_visit', 'quillmoor_visit_nonce', true ); ?>
			<p class="hp" aria-hidden="true"><label>Leave this empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
			<p><label for="visit_name"><?php esc_html_e( 'Your name', 'quillmoor-core' ); ?></label><input id="visit_name" name="visit_name" type="text" required autocomplete="name" value="<?php echo esc_attr( $kept['name'] ); ?>"></p>
			<p><label for="visit_email"><?php esc_html_e( 'Email', 'quillmoor-core' ); ?></label><input id="visit_email" name="visit_email" type="email" required autocomplete="email" value="<?php echo esc_attr( $kept['email'] ); ?>"></p>
			<p><label for="visit_town"><?php esc_html_e( 'Where is the garden?', 'quillmoor-core' ); ?></label>
				<select id="visit_town" name="visit_town">
					<option value=""><?php esc_html_e( 'Choose a town', 'quillmoor-core' ); ?></option>
					<?php foreach ( quillmoor_visit_towns() as $town ) : ?>
						<option <?php selected( $selected, $town ); ?>><?php echo esc_html( $town ); ?></option>
					<?php endforeach; ?>
				</select></p>
			<p><label for="visit_message"><?php esc_html_e( 'What is bothering you about it?', 'quillmoor-core' ); ?></label><textarea id="visit_message" name="visit_message" rows="5"><?php echo esc_textarea( $kept['message'] ); ?></textarea></p>
			<p><button class="btn" type="submit"><?php esc_html_e( 'Request a site visit', 'quillmoor-core' ); ?></button></p>
		</form>
	<?php endif; ?>
</div>
