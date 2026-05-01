<?php
/**
 * JSON-LD strukturierte Daten — Organization + Breadcrumbs.
 *
 * @package {{SITE_SLUG}}
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Organization on every page (head).
 */
function {{SITE_PREFIX}}_jsonld_organization() {
	$logo_id = get_theme_mod( 'custom_logo' );
	$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : {{SITE_PREFIX_UC}}_URI . '/assets/img/logo.png';

	$build_address = function ( $line1, $line2 ) {
		$plz  = trim( explode( ' ', $line2 )[0] ?? '' );
		$city = trim( substr( $line2, strpos( $line2, ' ' ) + 1 ) );
		return array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $line1,
			'postalCode'      => $plz,
			'addressLocality' => $city,
			'addressCountry'  => 'DE',
		);
	};

	$locations = array();
	if ( {{SITE_PREFIX}}_loc1_line1() ) {
		$locations[] = array(
			'@type'   => 'Place',
			'name'    => {{SITE_PREFIX}}_loc1_label() ?: 'Hauptsitz',
			'address' => $build_address( {{SITE_PREFIX}}_loc1_line1(), {{SITE_PREFIX}}_loc1_line2() ),
		);
	}
	if ( {{SITE_PREFIX}}_loc2_line1() ) {
		$locations[] = array(
			'@type'   => 'Place',
			'name'    => {{SITE_PREFIX}}_loc2_label() ?: 'Standort 2',
			'address' => $build_address( {{SITE_PREFIX}}_loc2_line1(), {{SITE_PREFIX}}_loc2_line2() ),
		);
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => {{SITE_PREFIX}}_legal_name() ?: get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
		'logo'     => $logo,
		'telephone' => {{SITE_PREFIX}}_phone_raw(),
		'email'    => {{SITE_PREFIX}}_email(),
	);
	if ( $locations ) {
		$data['location'] = $locations;
	}

	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', '{{SITE_PREFIX}}_jsonld_organization', 50 );
