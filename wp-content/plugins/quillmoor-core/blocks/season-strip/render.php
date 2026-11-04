<?php
/**
 * Season strip front end. Works without JavaScript: it shows July, and the buttons simply do nothing.
 *
 * @var array $attributes
 */
defined( 'ABSPATH' ) || exit;

$seasons = $attributes['seasons'];
$instance = wp_unique_id( 'season-' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'hero-season season-july' ) ); ?> data-season-strip>
	<div class="window" id="<?php echo esc_attr( $instance ); ?>">
		<?php foreach ( $seasons as $season ) : ?>
			<?php
			if ( empty( $season['imageId'] ) ) {
				continue;
			}
			echo wp_get_attachment_image(
				(int) $season['imageId'],
				'full',
				false,
				array(
					'data-season' => $season['id'],
					'class'       => 'july' === $season['id'] ? 'on' : '',
					'alt'         => $season['alt'],
					'sizes'       => '95vw',
					'loading'     => 'eager', // All four run in the opening pass, and lazy loading would pick smaller files.
				)
			);
			?>
		<?php endforeach; ?>
	</div>
	<div class="strip" role="tablist" aria-label="<?php esc_attr_e( 'See this garden by season', 'quillmoor-core' ); ?>">
		<?php foreach ( $seasons as $season ) : ?>
			<button type="button" role="tab" data-season="<?php echo esc_attr( $season['id'] ); ?>" aria-controls="<?php echo esc_attr( $instance ); ?>" aria-selected="<?php echo 'july' === $season['id'] ? 'true' : 'false'; ?>">
				<strong><?php echo esc_html( $season['label'] ); ?></strong>
				<span class="latin"><?php echo esc_html( $season['plant'] ); ?></span>
			</button>
		<?php endforeach; ?>
	</div>
</div>
