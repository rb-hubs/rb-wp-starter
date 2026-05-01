<?php
/**
 * Auto-Reply: einfache HTML-Mail an Kunden bei jedem Lead-Submit.
 *
 * Basis-Skeleton. Premium-HTML-Layout mit Tabellen-basiertem
 * Email-Client-Compat-Markup kommt aus rb-wp-shared.
 *
 * @package {{SITE_SLUG}}
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( '{{SITE_PREFIX}}_auto_reply', array(
		'title'       => 'Auto-Reply an Kunden',
		'description' => 'HTML-Mail nach jedem Form-Submit. Texte hier editieren.',
		'panel'       => '{{SITE_PREFIX}}_company',
		'priority'    => 60,
	) );

	$fields = array(
		'{{SITE_PREFIX}}_ar_enabled' => array( 'label' => 'Auto-Reply aktivieren', 'type' => 'checkbox', 'default' => 1 ),
		'{{SITE_PREFIX}}_ar_subject' => array( 'label' => 'Subject',                'type' => 'text',     'default' => 'Danke für deine Anfrage' ),
		'{{SITE_PREFIX}}_ar_intro'   => array( 'label' => 'Intro-Text',             'type' => 'textarea', 'default' => 'Hallo {name}, danke für deine Nachricht. Wir melden uns innerhalb von 24h.' ),
		'{{SITE_PREFIX}}_ar_signoff' => array( 'label' => 'Verabschiedung',         'type' => 'text',     'default' => 'Beste Grüße, das {{SITE_NAME}}-Team' ),
	);

	foreach ( $fields as $id => $f ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $f['default'],
			'sanitize_callback' => $f['type'] === 'checkbox' ? function ( $v ) { return $v ? 1 : 0; } : ( $f['type'] === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field' ),
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $f['label'],
			'section' => '{{SITE_PREFIX}}_auto_reply',
			'type'    => $f['type'],
		) );
	}
} );

/**
 * Hooks ans Lead-Create-Event.
 */
add_action( '{{SITE_PREFIX}}_lead_created', function ( $lead_id, $data ) {
	if ( ! get_theme_mod( '{{SITE_PREFIX}}_ar_enabled', 1 ) ) return;
	if ( empty( $data['email'] ) ) return;

	{{SITE_PREFIX}}_auto_reply_send( $lead_id, $data );
}, 20, 2 );

function {{SITE_PREFIX}}_auto_reply_send( $lead_id, $data ) {
	$subject = get_theme_mod( '{{SITE_PREFIX}}_ar_subject', 'Danke für deine Anfrage' );
	$intro   = get_theme_mod( '{{SITE_PREFIX}}_ar_intro', 'Hallo {name}, danke für deine Nachricht.' );
	$signoff = get_theme_mod( '{{SITE_PREFIX}}_ar_signoff', 'Beste Grüße, das Team' );

	$intro = str_replace( '{name}', esc_html( $data['name'] ), $intro );

	$body = '<html><body style="font-family:sans-serif;max-width:600px;margin:0 auto;padding:24px;">';
	$body .= '<p>' . nl2br( $intro ) . '</p>';
	$body .= '<p>' . nl2br( $signoff ) . '</p>';
	$body .= '</body></html>';

	wp_mail(
		$data['email'],
		$subject,
		$body,
		array( 'Content-Type: text/html; charset=UTF-8' )
	);

	update_post_meta( $lead_id, '_{{SITE_PREFIX}}_ar_sent', current_time( 'mysql' ) );
}
