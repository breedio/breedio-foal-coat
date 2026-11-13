<?php

namespace Breedio\FoalCoat;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function robes() {
	return array(
		'chestnut'          => __( 'Chestnut', 'breedio-foal-coat' ),
		'bay'               => __( 'Bay', 'breedio-foal-coat' ),
		'black'             => __( 'Black', 'breedio-foal-coat' ),
		'grey'              => __( 'Grey', 'breedio-foal-coat' ),
		'pie'               => __( 'Tobiano pinto', 'breedio-foal-coat' ),
		'palomino'          => __( 'Palomino', 'breedio-foal-coat' ),
		'isabelle'          => __( 'Buckskin', 'breedio-foal-coat' ),
		'souris'            => __( 'Grullo', 'breedio-foal-coat' ),
		'aubere'            => __( 'Red roan', 'breedio-foal-coat' ),
		'rouan'             => __( 'Bay roan', 'breedio-foal-coat' ),
		'rouan-noir'        => __( 'Blue roan', 'breedio-foal-coat' ),
		'bay-dun'           => __( 'Bay dun', 'breedio-foal-coat' ),
		'chestnut-dun'      => __( 'Red dun', 'breedio-foal-coat' ),
		'pie-overo'         => __( 'Frame overo pinto', 'breedio-foal-coat' ),
		'cremello'          => __( 'Cremello', 'breedio-foal-coat' ),
		'perlino'           => __( 'Perlino', 'breedio-foal-coat' ),
		'smoky-black'       => __( 'Smoky black', 'breedio-foal-coat' ),
		'smoky-cream'       => __( 'Smoky cream', 'breedio-foal-coat' ),
		'gold-champagne'    => __( 'Gold champagne', 'breedio-foal-coat' ),
		'amber-champagne'   => __( 'Amber champagne', 'breedio-foal-coat' ),
		'classic-champagne' => __( 'Classic champagne', 'breedio-foal-coat' ),
		'bay-silver'        => __( 'Silver bay', 'breedio-foal-coat' ),
		'black-silver'      => __( 'Silver black', 'breedio-foal-coat' ),
	);
}

function underlying_robes() {
	return array_intersect_key(
		robes(),
		array_flip( array( 'chestnut', 'bay', 'black', 'palomino', 'isabelle', 'cremello', 'perlino', 'smoky-black' ) )
	);
}

function loci() {
	return array(
		'E'  => array( __( 'Extension', 'breedio-foal-coat' ), 'e', 'E' ),
		'A'  => array( __( 'Agouti', 'breedio-foal-coat' ), 'a', 'A' ),
		'Cr' => array( __( 'Cream', 'breedio-foal-coat' ), 'n', 'Cr' ),
		'Ch' => array( __( 'Champagne', 'breedio-foal-coat' ), 'n', 'Ch' ),
		'Z'  => array( __( 'Silver', 'breedio-foal-coat' ), 'n', 'Z' ),
		'D'  => array( __( 'Dun', 'breedio-foal-coat' ), 'n', 'D' ),
		'G'  => array( __( 'Grey', 'breedio-foal-coat' ), 'n', 'G' ),
		'Rn' => array( __( 'Roan', 'breedio-foal-coat' ), 'n', 'Rn' ),
		'TO' => array( __( 'Tobiano', 'breedio-foal-coat' ), 'n', 'TO' ),
		'O'  => array( __( 'Frame overo', 'breedio-foal-coat' ), 'n', 'O' ),
	);
}

function notation( $recessive, $dominant, $copies ) {
	if ( 2 === $copies ) {
		return "$dominant/$dominant";
	}
	return 1 === $copies ? "$dominant/$recessive" : "$recessive/$recessive";
}

function coat_of( $post_id ) {
	$coat = get_post_meta( $post_id, COAT_META, true );
	return is_array( $coat ) ? $coat : array();
}

function sanitize_coat( $input ) {
	$robes      = robes();
	$underlying = underlying_robes();
	$pick       = function ( $key, $allowed ) use ( $input ) {
		$value = isset( $input[ $key ] ) ? sanitize_key( $input[ $key ] ) : '';
		return isset( $allowed[ $value ] ) ? $value : '';
	};
	$tests      = array();
	foreach ( array_keys( loci() ) as $locus ) {
		$copies = isset( $input['tests'][ $locus ] ) ? $input['tests'][ $locus ] : '';
		if ( in_array( $copies, array( '0', '1', '2' ), true ) ) {
			$tests[ $locus ] = (int) $copies;
		}
	}
	$booking    = isset( $input['booking'] ) ? esc_url_raw( trim( $input['booking'] ), array( 'http', 'https' ) ) : '';
	return array(
		'booking'    => $booking,
		'robe'       => $pick( 'robe', $robes ),
		'underlying' => $pick( 'underlying', $underlying ),
		'sire'       => $pick( 'sire', $robes ),
		'dam'        => $pick( 'dam', $robes ),
		'tests'      => $tests,
	);
}
