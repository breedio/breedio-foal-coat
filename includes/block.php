<?php

namespace Breedio\FoalCoat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const EDITOR_SCRIPT = 'breedio-foal-coat-editor';

add_action(
	'init',
	function () {
		wp_register_script( EDITOR_SCRIPT, plugins_url( 'block/index.js', FILE ), array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element' ), VERSION, true );
		register_block_type( dirname( FILE ) . '/block' );
	}
);

add_action(
	'enqueue_block_editor_assets',
	function () {
		$settings = array(
			'stallions' => array_map(
				function ( $stallion ) {
					return array(
						'id'   => $stallion->ID,
						'name' => $stallion->post_title,
					);
				},
				stallions()
			),
			'modes'     => array_map(
				function ( $value, $label ) {
					return array(
						'value' => $value,
						'label' => $label,
					);
				},
				array_keys( modes() ),
				modes()
			),
			'labels'    => array(
				'title'        => __( 'Foal coat calculator', 'breedio-foal-coat' ),
				'mode'         => __( 'Mode', 'breedio-foal-coat' ),
				'stallion'     => __( 'Stallion selected on load', 'breedio-foal-coat' ),
				'pageStallion' => __( 'Stallion of the page, otherwise the first one', 'breedio-foal-coat' ),
				'preview'      => __( 'Visitors see the calculator on the published page.', 'breedio-foal-coat' ),
				'empty'        => __( 'Add a stallion under Foal coat in the admin menu first.', 'breedio-foal-coat' ),
			),
		);
		wp_add_inline_script( EDITOR_SCRIPT, 'window.breedioFoalCoat = ' . wp_json_encode( $settings ) . ';', 'before' );
	}
);
