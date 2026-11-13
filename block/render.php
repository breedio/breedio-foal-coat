<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

printf(
	'<div %s>%s</div>',
	wp_kses_data( get_block_wrapper_attributes() ),
	wp_kses( \Breedio\FoalCoat\render( (int) $attributes['stallion'], $attributes['mode'] ), \Breedio\FoalCoat\allowed_html() )
);
