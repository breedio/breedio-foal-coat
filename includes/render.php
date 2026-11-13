<?php

namespace Breedio\FoalCoat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function origin() {
	return untrailingslashit( defined( 'BREEDIO_FOAL_COAT_ORIGIN' ) ? BREEDIO_FOAL_COAT_ORIGIN : 'https://embed.breedio.xyz' );
}

function language() {
	$lang = substr( get_locale(), 0, 2 );
	return in_array( $lang, array( 'fr', 'en', 'es', 'it', 'de' ), true ) ? $lang : 'en';
}

function site_language() {
	return 'fr' === language() ? 'fr' : 'en';
}

function roster_entry( $post ) {
	$coat = coat_of( $post->ID );
	if ( empty( $coat['robe'] ) ) {
		return null;
	}
	return array_filter(
		array(
			'id'   => (string) $post->ID,
			'name' => $post->post_title,
		) + $coat
	);
}

function roster_entries() {
	static $entries = null;
	if ( null === $entries ) {
		$entries = array_values( array_filter( array_map( __NAMESPACE__ . '\roster_entry', stallions() ) ) );
	}
	return $entries;
}

function modes() {
	return array(
		'predict' => __( 'Predict the foal coat with one stallion', 'breedio-foal-coat' ),
		'match'   => __( 'Find the stallions for a desired coat', 'breedio-foal-coat' ),
	);
}

function render( $stallion_id = 0, $mode = 'predict' ) {
	$entries = roster_entries();
	if ( ! $entries ) {
		return current_user_can( 'edit_posts' )
			? '<p>' . esc_html__( 'Add a stallion with its coat under Foal coat in the admin menu to show the calculator.', 'breedio-foal-coat' ) . '</p>'
			: '';
	}
	$selected = $stallion_id ? $stallion_id : (int) get_post_meta( (int) get_the_ID(), LINK_META, true );
	$settings = settings();
	$roster   = array(
		'stallions'   => $entries,
		'contact'     => array_filter(
			array(
				'phone'    => $settings['phone'],
				'email'    => $settings['email'],
				'whatsapp' => $settings['whatsapp'],
			)
		),
		'reassurance' => $settings['reassurance'],
		'stud'        => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
	);
	if ( 'match' === $mode ) {
		$roster['mode'] = 'match';
	} elseif ( $selected ) {
		$roster['selected'] = (string) $selected;
	}
	$roster = apply_filters( 'breedio_foal_coat_roster', array_filter( $roster ) );
	$src    = origin() . '/' . language() . '/embed/robe-poulain/roster#roster=' . rawurlencode( wp_json_encode( $roster ) );
	do_action( 'breedio_foal_coat_render', $roster );
	wp_enqueue_script(
		FRAME_SCRIPT,
		plugins_url( 'assets/frame.js', FILE ),
		array(),
		VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	$frame = sprintf(
		'<iframe data-breedio-foal-coat="%s" src="%s" title="%s" width="100%%" height="1400" loading="lazy" style="display:block;width:100%%;max-width:640px;border:0" allow="clipboard-write" sandbox="allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-top-navigation-by-user-activation"></iframe>',
		esc_attr( origin() ),
		esc_url( $src ),
		esc_attr__( 'Breedio foal coat calculator', 'breedio-foal-coat' )
	);
	return $settings['credit'] ? $frame . credit_html() : $frame;
}

function credit_url() {
	$path = 'fr' === site_language() ? 'fr/outils/calculateur-robe-poulain' : 'en/tools/foal-color-calculator';
	return 'https://www.breedio.xyz/' . $path . '?utm_source=wordpress&utm_medium=credit&utm_campaign=coat-calculator';
}

function credit_html() {
	return sprintf(
		'<p class="breedio-foal-coat-credit" style="max-width:640px;margin:8px 0 0;font-size:13px;text-align:center"><svg width="13" height="13" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="vertical-align:-2px;margin-right:4px"><path fill="#2E7D5B" stroke="#2E7D5B" stroke-width="2" stroke-linejoin="round" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>%s <a href="%s">Breedio</a></p>',
		esc_html__( 'Calculator by', 'breedio-foal-coat' ),
		esc_url( credit_url() )
	);
}

function allowed_html() {
	return array(
		'iframe' => array(
			'data-breedio-foal-coat' => true,
			'src'                    => true,
			'title'                  => true,
			'width'                  => true,
			'height'                 => true,
			'loading'                => true,
			'style'                  => true,
			'sandbox'                => true,
			'allow'                  => true,
		),
		'p'      => array(
			'class' => true,
			'style' => true,
		),
		'a'      => array( 'href' => true ),
		'svg'    => array(
			'width'       => true,
			'height'      => true,
			'viewbox'     => true,
			'aria-hidden' => true,
			'focusable'   => true,
			'style'       => true,
		),
		'path'   => array(
			'd'               => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linejoin' => true,
		),
	);
}

add_shortcode(
	SHORTCODE,
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'stallion' => 0,
				'mode'     => 'predict',
			),
			$atts,
			SHORTCODE
		);
		return render( absint( $atts['stallion'] ), sanitize_key( $atts['mode'] ) );
	}
);
