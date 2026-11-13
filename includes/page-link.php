<?php

namespace Breedio\FoalCoat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stallions() {
	return get_posts(
		array(
			'post_type'   => STALLION,
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => 'title',
			'order'       => 'ASC',
		)
	);
}

function linkable_types() {
	return array_diff( get_post_types( array( 'public' => true ) ), array( 'attachment', STALLION ) );
}

add_action(
	'add_meta_boxes',
	function ( $post_type ) {
		if ( in_array( $post_type, linkable_types(), true ) ) {
			add_meta_box( 'breedio-foal-coat-link', __( 'Foal coat calculator', 'breedio-foal-coat' ), __NAMESPACE__ . '\render_link_box', $post_type, 'side' );
		}
	}
);

function render_link_box( $post ) {
	$linked = (int) get_post_meta( $post->ID, LINK_META, true );
	wp_nonce_field( 'breedio_foal_coat_link', 'breedio_foal_coat_link_nonce' );
	?>
	<p><label for="breedio-foal-coat-stallion"><?php esc_html_e( 'Stallion of this page', 'breedio-foal-coat' ); ?></label></p>
	<select name="breedio_foal_coat_stallion" id="breedio-foal-coat-stallion" class="widefat">
		<option value="0"><?php esc_html_e( 'None', 'breedio-foal-coat' ); ?></option>
		<?php foreach ( stallions() as $stallion ) : ?>
			<option value="<?php echo esc_attr( $stallion->ID ); ?>"<?php selected( $linked, $stallion->ID ); ?>><?php echo esc_html( $stallion->post_title ); ?></option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php esc_html_e( 'The calculator on this page opens with this stallion selected.', 'breedio-foal-coat' ); ?></p>
	<?php
}

add_action(
	'save_post',
	function ( $post_id, $post ) {
		if ( ! isset( $_POST['breedio_foal_coat_link_nonce'], $_POST['breedio_foal_coat_stallion'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['breedio_foal_coat_link_nonce'] ) ), 'breedio_foal_coat_link' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) || ! in_array( $post->post_type, linkable_types(), true ) ) {
			return;
		}
		$stallion = absint( $_POST['breedio_foal_coat_stallion'] );
		if ( $stallion && STALLION === get_post_type( $stallion ) ) {
			update_post_meta( $post_id, LINK_META, $stallion );
		} else {
			delete_post_meta( $post_id, LINK_META );
		}
	},
	10,
	2
);
