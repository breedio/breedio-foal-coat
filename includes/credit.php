<?php

namespace Breedio\FoalCoat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function featured_url() {
	$name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	return 'mailto:wordpress.plugin@breedio.xyz?subject=' . rawurlencode( __( 'Feature my stud', 'breedio-foal-coat' ) )
		. '&body=' . rawurlencode( $name . "\n" . home_url() );
}

add_action(
	'save_post_' . STALLION,
	function ( $post_id, $post ) {
		if ( 'publish' === $post->post_status && ! get_option( CREDIT_ASKED ) ) {
			update_option( CREDIT_ASKED, 'pending', false );
		}
	},
	10,
	2
);

add_action(
	'admin_notices',
	function () {
		$screen = get_current_screen();
		if ( ! current_user_can( 'manage_options' ) || 'pending' !== get_option( CREDIT_ASKED ) || settings()['credit'] || ! $screen || STALLION !== $screen->post_type ) {
			return;
		}
		?>
		<div class="notice notice-info">
			<p><strong><?php esc_html_e( 'Show your visitors where the probabilities come from?', 'breedio-foal-coat' ); ?></strong></p>
			<p><?php esc_html_e( 'One discreet line under the calculator tells them the coats are worked out by a dedicated horse genetics tool. It helps Breedio get found and keeps the calculator free.', 'breedio-foal-coat' ); ?> <?php esc_html_e( 'Studs that show it can be featured by Breedio (articles, social media), with a link to their website.', 'breedio-foal-coat' ); ?></p>
			<div style="max-width:640px;padding:4px 12px;background:#f6f7f7;border:1px solid #dcdcde;border-radius:4px"><?php echo wp_kses( credit_html(), allowed_html() ); ?></div>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="breedio_foal_coat_credit">
				<?php wp_nonce_field( 'breedio_foal_coat_credit' ); ?>
				<p>
					<button type="submit" name="answer" value="yes" class="button button-primary"><?php esc_html_e( 'Yes, show it', 'breedio-foal-coat' ); ?></button>
					<button type="submit" name="answer" value="no" class="button"><?php esc_html_e( 'No thanks', 'breedio-foal-coat' ); ?></button>
					<a href="<?php echo esc_url( featured_url() ); ?>" style="margin-left:8px"><?php esc_html_e( 'Get your stud featured', 'breedio-foal-coat' ); ?></a>
				</p>
			</form>
		</div>
		<?php
	}
);

add_action(
	'admin_post_breedio_foal_coat_credit',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'breedio-foal-coat' ), 403 );
		}
		check_admin_referer( 'breedio_foal_coat_credit' );
		if ( isset( $_POST['answer'] ) && 'yes' === sanitize_key( wp_unslash( $_POST['answer'] ) ) ) {
			update_option( SETTINGS, array_merge( settings(), array( 'credit' => true ) ) );
		}
		update_option( CREDIT_ASKED, 'done', false );
		wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=' . STALLION ) );
		exit;
	}
);
