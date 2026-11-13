<?php

namespace Breedio\FoalCoat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function settings() {
	$saved = get_option( SETTINGS, array() );
	return wp_parse_args(
		is_array( $saved ) ? $saved : array(),
		array(
			'phone'       => '',
			'email'       => '',
			'whatsapp'    => '',
			'reassurance' => '',
			'credit'      => false,
		)
	);
}

function valid_phone( $phone ) {
	return (bool) preg_match( '/^[\d\s+().-]{6,24}$/', $phone ) && strlen( preg_replace( '/\D/', '', $phone ) ) >= 6;
}

function sanitize_settings( $input ) {
	$input = is_array( $input ) ? $input : array();
	$phone = isset( $input['phone'] ) ? sanitize_text_field( $input['phone'] ) : '';
	return array(
		'phone'       => valid_phone( $phone ) ? $phone : '',
		'email'       => isset( $input['email'] ) ? sanitize_email( $input['email'] ) : '',
		'whatsapp'    => isset( $input['whatsapp'] ) ? preg_replace( '/\D/', '', $input['whatsapp'] ) : '',
		'reassurance' => isset( $input['reassurance'] ) ? mb_substr( sanitize_text_field( $input['reassurance'] ), 0, 140 ) : '',
		'credit'      => ! empty( $input['credit'] ),
	);
}

add_action(
	'admin_init',
	function () {
		register_setting(
			'breedio_foal_coat',
			SETTINGS,
			array(
				'type'              => 'array',
				'sanitize_callback' => 'Breedio\FoalCoat\sanitize_settings',
				'default'           => array(),
			)
		);
	}
);

add_action(
	'admin_menu',
	function () {
		add_submenu_page(
			'edit.php?post_type=' . STALLION,
			__( 'Foal coat settings', 'breedio-foal-coat' ),
			__( 'Settings', 'breedio-foal-coat' ),
			'manage_options',
			'breedio-foal-coat-settings',
			__NAMESPACE__ . '\render_settings_page'
		);
	}
);

function render_settings_page() {
	$settings = settings();
	$field    = function ( $key, $label, $type, $description ) use ( $settings ) {
		?>
		<tr>
			<th scope="row"><label for="<?php echo esc_attr( "breedio-foal-coat-$key" ); ?>"><?php echo esc_html( $label ); ?></label></th>
			<td>
				<input type="<?php echo esc_attr( $type ); ?>" class="regular-text" id="<?php echo esc_attr( "breedio-foal-coat-$key" ); ?>" name="<?php echo esc_attr( SETTINGS . "[$key]" ); ?>" value="<?php echo esc_attr( $settings[ $key ] ); ?>">
				<p class="description"><?php echo esc_html( $description ); ?></p>
			</td>
		</tr>
		<?php
	};
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Foal coat settings', 'breedio-foal-coat' ); ?></h1>
		<p><?php esc_html_e( 'These details appear under the "Book a breeding" button of the calculator.', 'breedio-foal-coat' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'breedio_foal_coat' ); ?>
			<table class="form-table" role="presentation">
				<?php
				$field( 'phone', __( 'Phone', 'breedio-foal-coat' ), 'tel', __( 'Shown as a "call" button.', 'breedio-foal-coat' ) );
				$field( 'email', __( 'Email', 'breedio-foal-coat' ), 'email', __( 'Shown as an "email" button.', 'breedio-foal-coat' ) );
				$field( 'whatsapp', __( 'WhatsApp number', 'breedio-foal-coat' ), 'tel', __( 'International format, for example +33 6 12 34 56 78.', 'breedio-foal-coat' ) );
				$field( 'reassurance', __( 'Reassurance line', 'breedio-foal-coat' ), 'text', __( 'Optional, under the button. Only write what you commit to, for example "No commitment, answer within 24 hours".', 'breedio-foal-coat' ) );
				?>
				<tr>
					<th scope="row"><?php esc_html_e( 'Breedio credit', 'breedio-foal-coat' ); ?></th>
					<td>
						<label for="breedio-foal-coat-credit">
							<input type="checkbox" id="breedio-foal-coat-credit" name="<?php echo esc_attr( SETTINGS . '[credit]' ); ?>" value="1" <?php checked( $settings['credit'] ); ?>>
							<?php esc_html_e( 'Show "Calculator by Breedio" under the calculator', 'breedio-foal-coat' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Helps Breedio get found and keeps the calculator free. Off by default.', 'breedio-foal-coat' ); ?></p>
						<p class="description"><?php esc_html_e( 'Studs that show it can be featured by Breedio (articles, social media), with a link to their website.', 'breedio-foal-coat' ); ?> <a href="<?php echo esc_url( featured_url() ); ?>"><?php esc_html_e( 'Get your stud featured', 'breedio-foal-coat' ); ?></a></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php if ( ! defined( 'Breedio\FoalCoatPro\VERSION' ) ) : ?>
			<div class="card">
				<h2>Breedio Foal Coat Pro</h2>
				<p><?php esc_html_e( 'Turn simulations into breeding requests: a call back form with the simulation attached, requests kept in WordPress and sent by email, statistics per stallion.', 'breedio-foal-coat' ); ?></p>
				<p><a class="button button-primary" href="<?php echo esc_url( pro_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Upgrade to Pro', 'breedio-foal-coat' ); ?></a></p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

function pro_url() {
	return 'https://www.breedio.xyz/' . ( 0 === strpos( get_user_locale(), 'fr' ) ? 'fr' : 'en' ) . '/wordpress';
}
