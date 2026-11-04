<?php
defined( 'ABSPATH' ) || exit;
$field = wp_unique_id( 'town-' );
?>
<form <?php echo get_block_wrapper_attributes( array( 'class' => 'visit' ) ); ?> action="<?php echo esc_url( home_url( '/site-visit/' ) ); ?>" method="get">
	<label for="<?php echo esc_attr( $field ); ?>"><?php esc_html_e( 'Where is the garden?', 'quillmoor-core' ); ?></label>
	<select id="<?php echo esc_attr( $field ); ?>" name="town">
		<option value=""><?php esc_html_e( 'Choose a town', 'quillmoor-core' ); ?></option>
		<?php foreach ( quillmoor_visit_towns() as $town ) : ?>
			<option><?php echo esc_html( $town ); ?></option>
		<?php endforeach; ?>
	</select>
	<button class="btn" type="submit"><?php esc_html_e( 'Request a site visit', 'quillmoor-core' ); ?></button>
</form>
