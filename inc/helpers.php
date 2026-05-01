<?php
/**
 * Helpers – globale Getter für Customizer-Werte.
 *
 * @package {{SITE_SLUG}}
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------
   Defaults (werden über Customizer überschreibbar).
   Beim Site-Setup hier die echten Werte eintragen
   ODER nachträglich im WP-Admin → Customizer → Firmendaten setzen.
   ------------------------------------------------------------ */
function {{SITE_PREFIX}}_defaults() {
	return array(
		'phone'          => '+49 000 000 000',
		'phone_raw'      => '+490000000000',
		'email'          => 'hallo@example.com',
		'whatsapp'       => '+490000000000',
		'loc1_label'     => 'Geschäftsadresse',
		'loc1_line1'     => 'Musterstr. 1',
		'loc1_line2'     => '00000 Musterstadt',
		'loc2_label'     => '',
		'loc2_line1'     => '',
		'loc2_line2'     => '',
		'hours'          => 'Mo–Fr 09:00–17:00',
		'legal_name'     => '{{SITE_NAME}}',
	);
}

function {{SITE_PREFIX}}_opt( $key, $fallback = '' ) {
	$defaults = {{SITE_PREFIX}}_defaults();
	$default  = $defaults[ $key ] ?? $fallback;
	return get_theme_mod( '{{SITE_PREFIX}}_' . $key, $default );
}

function {{SITE_PREFIX}}_phone()      { return {{SITE_PREFIX}}_opt( 'phone' ); }
function {{SITE_PREFIX}}_phone_raw()  { return {{SITE_PREFIX}}_opt( 'phone_raw' ); }
function {{SITE_PREFIX}}_email()      { return {{SITE_PREFIX}}_opt( 'email' ); }
function {{SITE_PREFIX}}_whatsapp()   { return {{SITE_PREFIX}}_opt( 'whatsapp' ); }
function {{SITE_PREFIX}}_loc1_label() { return {{SITE_PREFIX}}_opt( 'loc1_label' ); }
function {{SITE_PREFIX}}_loc1_line1() { return {{SITE_PREFIX}}_opt( 'loc1_line1' ); }
function {{SITE_PREFIX}}_loc1_line2() { return {{SITE_PREFIX}}_opt( 'loc1_line2' ); }
function {{SITE_PREFIX}}_loc2_label() { return {{SITE_PREFIX}}_opt( 'loc2_label' ); }
function {{SITE_PREFIX}}_loc2_line1() { return {{SITE_PREFIX}}_opt( 'loc2_line1' ); }
function {{SITE_PREFIX}}_loc2_line2() { return {{SITE_PREFIX}}_opt( 'loc2_line2' ); }
function {{SITE_PREFIX}}_hours()      { return {{SITE_PREFIX}}_opt( 'hours' ); }
function {{SITE_PREFIX}}_legal_name() { return {{SITE_PREFIX}}_opt( 'legal_name' ); }

/**
 * Customizer-Helper: Text-Field schnell registrieren.
 */
function {{SITE_PREFIX}}_add_text( $wp_customize, $id, $label, $section, $sanitize = 'sanitize_text_field' ) {
	$defaults = {{SITE_PREFIX}}_defaults();
	$key      = preg_replace( '/^{{SITE_PREFIX}}_/', '', $id );

	$wp_customize->add_setting( $id, array(
		'default'           => $defaults[ $key ] ?? '',
		'sanitize_callback' => $sanitize,
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( $id, array(
		'label'   => $label,
		'section' => $section,
		'type'    => 'text',
	) );
}

/**
 * Customizer-Helper: Textarea-Field.
 */
function {{SITE_PREFIX}}_add_textarea( $wp_customize, $id, $label, $section, $sanitize = 'sanitize_textarea_field' ) {
	$wp_customize->add_setting( $id, array(
		'default'           => '',
		'sanitize_callback' => $sanitize,
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( $id, array(
		'label'   => $label,
		'section' => $section,
		'type'    => 'textarea',
	) );
}
