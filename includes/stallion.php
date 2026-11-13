<?php

namespace Breedio\FoalCoat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'init',
	function () {
		register_post_type(
			STALLION,
			array(
				'labels'       => array(
					'name'          => __( 'Stallions', 'breedio-foal-coat' ),
					'singular_name' => __( 'Stallion', 'breedio-foal-coat' ),
					'add_new_item'  => __( 'Add a stallion', 'breedio-foal-coat' ),
					'edit_item'     => __( 'Edit stallion', 'breedio-foal-coat' ),
					'search_items'  => __( 'Search stallions', 'breedio-foal-coat' ),
					'not_found'     => __( 'No stallion yet.', 'breedio-foal-coat' ),
					'menu_name'     => __( 'Foal coat', 'breedio-foal-coat' ),
				),
				'public'       => false,
				'show_ui'      => true,
				'menu_icon'    => 'dashicons-art',
				'supports'     => array( 'title' ),
				'show_in_rest' => false,
			)
		);
	}
);

add_filter(
	'enter_title_here',
	function ( $title, $post ) {
		return STALLION === $post->post_type ? __( 'Stallion name', 'breedio-foal-coat' ) : $title;
	},
	10,
	2
);

add_action(
	'add_meta_boxes_' . STALLION,
	function () {
		add_meta_box( 'breedio-foal-coat-genetics', __( 'Coat and genetics', 'breedio-foal-coat' ), __NAMESPACE__ . '\render_coat_box', STALLION, 'normal', 'high' );
	}
);

function robe_select( $name, $value, $options, $empty_label ) {
	printf( '<select name="%s" id="%s">', esc_attr( "breedio_foal_coat[$name]" ), esc_attr( "breedio-foal-coat-$name" ) );
	if ( null !== $empty_label ) {
		printf( '<option value="">%s</option>', esc_html( $empty_label ) );
	}
	foreach ( $options as $id => $label ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $id ), selected( $value, $id, false ), esc_html( $label ) );
	}
	echo '</select>';
}

function render_coat_box( $post ) {
	$coat    = coat_of( $post->ID );
	$value   = function ( $key ) use ( $coat ) {
		return isset( $coat[ $key ] ) ? $coat[ $key ] : '';
	};
	$unknown = __( 'Unknown', 'breedio-foal-coat' );
	wp_nonce_field( 'breedio_foal_coat_save', 'breedio_foal_coat_nonce' );
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="breedio-foal-coat-robe"><?php esc_html_e( 'Coat', 'breedio-foal-coat' ); ?></label></th>
			<td><?php robe_select( 'robe', $value( 'robe' ), robes(), null ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="breedio-foal-coat-underlying"><?php esc_html_e( 'Coat under the grey or the patches', 'breedio-foal-coat' ); ?></label></th>
			<td>
				<?php robe_select( 'underlying', $value( 'underlying' ), underlying_robes(), $unknown ); ?>
				<p class="description"><?php esc_html_e( 'Only for a grey (coat at birth) or a pinto (coat of the coloured areas).', 'breedio-foal-coat' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="breedio-foal-coat-booking"><?php esc_html_e( 'Booking page', 'breedio-foal-coat' ); ?></label></th>
			<td>
				<input type="url" class="regular-text" name="breedio_foal_coat[booking]" id="breedio-foal-coat-booking" value="<?php echo esc_attr( $value( 'booking' ) ); ?>" placeholder="https://">
				<p class="description"><?php esc_html_e( 'Optional. The "Book a breeding" button opens this page; without it, it shows your contact details.', 'breedio-foal-coat' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="breedio-foal-coat-sire"><?php esc_html_e( 'Sire coat', 'breedio-foal-coat' ); ?></label></th>
			<td><?php robe_select( 'sire', $value( 'sire' ), robes(), $unknown ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="breedio-foal-coat-dam"><?php esc_html_e( 'Dam coat', 'breedio-foal-coat' ); ?></label></th>
			<td><?php robe_select( 'dam', $value( 'dam' ), robes(), $unknown ); ?></td>
		</tr>
	</table>
	<h3><?php esc_html_e( 'Genetic tests', 'breedio-foal-coat' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Fill in only the results of a laboratory test. The calculator works out the rest from the coats.', 'breedio-foal-coat' ); ?></p>
	<table class="form-table" role="presentation">
		<?php
		foreach ( loci() as $locus => list( $label, $recessive, $dominant ) ) :
			$tested = isset( $coat['tests'][ $locus ] ) ? $coat['tests'][ $locus ] : null;
			?>
			<tr>
				<th scope="row"><label for="<?php echo esc_attr( "breedio-foal-coat-test-$locus" ); ?>"><?php echo esc_html( "$label ($locus)" ); ?></label></th>
				<td>
					<select name="<?php echo esc_attr( "breedio_foal_coat[tests][$locus]" ); ?>" id="<?php echo esc_attr( "breedio-foal-coat-test-$locus" ); ?>">
						<option value=""><?php esc_html_e( 'Not tested', 'breedio-foal-coat' ); ?></option>
						<?php foreach ( array( 0, 1, 2 ) as $copies ) : ?>
							<option value="<?php echo esc_attr( $copies ); ?>"<?php selected( $tested, $copies ); ?>><?php echo esc_html( notation( $recessive, $dominant, $copies ) ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
}

add_action(
	'save_post_' . STALLION,
	function ( $post_id ) {
		if ( ! isset( $_POST['breedio_foal_coat_nonce'], $_POST['breedio_foal_coat'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['breedio_foal_coat_nonce'] ) ), 'breedio_foal_coat_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$input = map_deep( wp_unslash( (array) $_POST['breedio_foal_coat'] ), 'sanitize_text_field' );
		update_post_meta( $post_id, COAT_META, sanitize_coat( $input ) );
	}
);

add_filter(
	'manage_' . STALLION . '_posts_columns',
	function ( $columns ) {
		$added = array(
			'breedio_coat'      => __( 'Coat', 'breedio-foal-coat' ),
			'breedio_shortcode' => __( 'Shortcode', 'breedio-foal-coat' ),
		);
		return array_slice( $columns, 0, 2, true ) + $added + array_slice( $columns, 2, null, true );
	}
);

add_action(
	'manage_' . STALLION . '_posts_custom_column',
	function ( $column, $post_id ) {
		if ( 'breedio_shortcode' === $column ) {
			printf( '<code>[%s stallion="%d"]</code>', esc_html( SHORTCODE ), (int) $post_id );
			return;
		}
		if ( 'breedio_coat' !== $column ) {
			return;
		}
		$coat  = coat_of( $post_id );
		$robes = robes();
		echo esc_html( isset( $coat['robe'], $robes[ $coat['robe'] ] ) ? $robes[ $coat['robe'] ] : '' );
	},
	10,
	2
);
