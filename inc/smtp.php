<?php
/**
 * SMTP-Versand — Microsoft 365 / generischer SMTP-Server.
 *
 * Konfiguration via Customizer (Section: {{SITE_PREFIX}}_smtp). Empfehlung:
 * Passwort als wp-config-Constante {{SITE_PREFIX_UC}}_SMTP_PASSWORD setzen statt in DB.
 *
 * Microsoft 365: smtp.office365.com:587 (TLS)
 * Hostinger:     smtp.hostinger.com:587 (TLS)
 *
 * @package {{SITE_SLUG}}
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( '{{SITE_PREFIX}}_smtp', array(
		'title'       => 'SMTP-Versand (Mail)',
		'description' => 'Damit Auto-Reply + Backup-Mails zuverlässig zugestellt werden. Empfehlung: Passwort per wp-config-Konstante <code>{{SITE_PREFIX_UC}}_SMTP_PASSWORD</code> setzen.',
		'panel'       => '{{SITE_PREFIX}}_company',
		'priority'    => 65,
	) );

	$fields = array(
		'{{SITE_PREFIX}}_smtp_enabled'    => array( 'label' => 'SMTP aktivieren',  'type' => 'checkbox', 'default' => 0 ),
		'{{SITE_PREFIX}}_smtp_host'       => array( 'label' => 'SMTP-Host',        'type' => 'text',     'default' => 'smtp.office365.com' ),
		'{{SITE_PREFIX}}_smtp_port'       => array( 'label' => 'SMTP-Port',        'type' => 'text',     'default' => '587' ),
		'{{SITE_PREFIX}}_smtp_encryption' => array( 'label' => 'Verschlüsselung (tls/ssl/none)', 'type' => 'text', 'default' => 'tls' ),
		'{{SITE_PREFIX}}_smtp_user'       => array( 'label' => 'SMTP-User (E-Mail)', 'type' => 'text',   'default' => '' ),
		'{{SITE_PREFIX}}_smtp_pass'       => array( 'label' => 'SMTP-Passwort (oder per Konstante)', 'type' => 'text', 'default' => '' ),
		'{{SITE_PREFIX}}_smtp_from_email' => array( 'label' => 'Absender-Mail',    'type' => 'text',     'default' => '' ),
		'{{SITE_PREFIX}}_smtp_from_name'  => array( 'label' => 'Absender-Name',    'type' => 'text',     'default' => '{{SITE_NAME}}' ),
	);

	foreach ( $fields as $id => $f ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $f['default'],
			'sanitize_callback' => $f['type'] === 'checkbox' ? function ( $v ) { return $v ? 1 : 0; } : 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $f['label'],
			'section' => '{{SITE_PREFIX}}_smtp',
			'type'    => $f['type'],
		) );
	}
} );

/**
 * PHPMailer-Hook: SMTP-Konfiguration injizieren.
 */
add_action( 'phpmailer_init', function ( $phpmailer ) {
	if ( ! get_theme_mod( '{{SITE_PREFIX}}_smtp_enabled', 0 ) ) {
		return;
	}

	$pass = defined( '{{SITE_PREFIX_UC}}_SMTP_PASSWORD' )
		? {{SITE_PREFIX_UC}}_SMTP_PASSWORD
		: get_theme_mod( '{{SITE_PREFIX}}_smtp_pass', '' );

	$phpmailer->isSMTP();
	$phpmailer->Host       = get_theme_mod( '{{SITE_PREFIX}}_smtp_host', 'smtp.office365.com' );
	$phpmailer->Port       = (int) get_theme_mod( '{{SITE_PREFIX}}_smtp_port', 587 );
	$phpmailer->SMTPAuth   = true;
	$phpmailer->Username   = get_theme_mod( '{{SITE_PREFIX}}_smtp_user', '' );
	$phpmailer->Password   = $pass;
	$phpmailer->SMTPSecure = get_theme_mod( '{{SITE_PREFIX}}_smtp_encryption', 'tls' );

	$from_email = get_theme_mod( '{{SITE_PREFIX}}_smtp_from_email', '' );
	$from_name  = get_theme_mod( '{{SITE_PREFIX}}_smtp_from_name', '{{SITE_NAME}}' );
	if ( $from_email ) {
		$phpmailer->setFrom( $from_email, $from_name, false );
	}
} );
