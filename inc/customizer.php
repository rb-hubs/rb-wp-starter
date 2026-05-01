<?php
/**
 * Theme Customizer – globale Firmendaten.
 *
 * @package {{SITE_SLUG}}
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function {{SITE_PREFIX}}_customize_register( $wp_customize ) {
	// ----- Panel -----
	$wp_customize->add_panel( '{{SITE_PREFIX}}_company', array(
		'title'       => __( '{{SITE_NAME}} – Firmendaten', '{{SITE_SLUG}}' ),
		'description' => __( 'Globale Kontaktdaten, die in Header, Footer, Kontakt und strukturierten Daten gezogen werden.', '{{SITE_SLUG}}' ),
		'priority'    => 30,
	) );

	// ----- Section: Kontakt -----
	$wp_customize->add_section( '{{SITE_PREFIX}}_contact', array(
		'title' => __( 'Kontakt', '{{SITE_SLUG}}' ),
		'panel' => '{{SITE_PREFIX}}_company',
	) );

	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_phone',     __( 'Telefon (Anzeige)', '{{SITE_SLUG}}' ),         '{{SITE_PREFIX}}_contact' );
	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_phone_raw', __( 'Telefon (tel:-Link, +49…)', '{{SITE_SLUG}}' ), '{{SITE_PREFIX}}_contact' );
	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_email',     __( 'E-Mail', '{{SITE_SLUG}}' ),                    '{{SITE_PREFIX}}_contact', 'sanitize_email' );
	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_whatsapp',  __( 'WhatsApp (intl. Nr.)', '{{SITE_SLUG}}' ),      '{{SITE_PREFIX}}_contact' );

	// ----- Section: Standorte -----
	$wp_customize->add_section( '{{SITE_PREFIX}}_address', array(
		'title' => __( 'Standorte', '{{SITE_SLUG}}' ),
		'panel' => '{{SITE_PREFIX}}_company',
	) );

	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_loc1_label', __( 'Standort 1 — Label', '{{SITE_SLUG}}' ),     '{{SITE_PREFIX}}_address' );
	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_loc1_line1', __( 'Standort 1 — Straße', '{{SITE_SLUG}}' ),    '{{SITE_PREFIX}}_address' );
	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_loc1_line2', __( 'Standort 1 — PLZ + Ort', '{{SITE_SLUG}}' ), '{{SITE_PREFIX}}_address' );

	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_loc2_label', __( 'Standort 2 — Label (optional)', '{{SITE_SLUG}}' ), '{{SITE_PREFIX}}_address' );
	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_loc2_line1', __( 'Standort 2 — Straße', '{{SITE_SLUG}}' ),          '{{SITE_PREFIX}}_address' );
	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_loc2_line2', __( 'Standort 2 — PLZ + Ort', '{{SITE_SLUG}}' ),       '{{SITE_PREFIX}}_address' );

	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_hours', __( 'Öffnungszeiten (Kurzform)', '{{SITE_SLUG}}' ), '{{SITE_PREFIX}}_address' );

	// ----- Section: Legal -----
	$wp_customize->add_section( '{{SITE_PREFIX}}_legal', array(
		'title' => __( 'Legal', '{{SITE_SLUG}}' ),
		'panel' => '{{SITE_PREFIX}}_company',
	) );

	{{SITE_PREFIX}}_add_text( $wp_customize, '{{SITE_PREFIX}}_legal_name', __( 'Rechtlicher Firmenname', '{{SITE_SLUG}}' ), '{{SITE_PREFIX}}_legal' );
}
add_action( 'customize_register', '{{SITE_PREFIX}}_customize_register' );
