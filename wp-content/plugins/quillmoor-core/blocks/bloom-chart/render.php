<?php
/**
 * Twelve-month chart: a real table, so screen readers can read it. Each month states yes or nothing.
 *
 * @var array $attributes
 */
defined( 'ABSPATH' ) || exit;

$names = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
?>
<table <?php echo get_block_wrapper_attributes( array( 'class' => 'chart' ) ); ?>>
	<caption class="screen-reader-text"><?php esc_html_e( 'What is in flower, fruit or color each month', 'quillmoor-core' ); ?></caption>
	<thead>
		<tr>
			<th scope="col"><?php esc_html_e( 'In flower, fruit or color', 'quillmoor-core' ); ?></th>
			<?php foreach ( $names as $month ) : ?>
				<th scope="col"><abbr title="<?php echo esc_attr( $month ); ?>"><?php echo esc_html( $month[0] ); ?></abbr></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $attributes['rows'] as $row ) : ?>
			<?php $active = array_map( 'intval', explode( ',', $row['months'] ) ); ?>
			<tr>
				<th scope="row"><?php echo esc_html( $row['name'] ); ?><span class="latin"><?php echo esc_html( $row['latin'] ); ?></span></th>
				<?php foreach ( $names as $i => $month ) : ?>
					<td><?php if ( in_array( $i + 1, $active, true ) ) : ?><span class="bar season-<?php echo esc_attr( $row['season'] ); ?>"><span class="screen-reader-text"><?php echo esc_html( $month ); ?></span></span><?php endif; ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
