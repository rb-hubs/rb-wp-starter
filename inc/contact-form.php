<?php
/**
 * Kontaktformular — generischer admin-post-Handler mit Honeypot,
 * Nonce-Check und Rate-Limit. Submission landet als CPT {{SITE_PREFIX}}_lead
 * in der Outbox (siehe inc/cpt-lead.php).
 *
 * @package {{SITE_SLUG}}
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_post_{{SITE_PREFIX}}_contact',        '{{SITE_PREFIX}}_contact_form_handler' );
add_action( 'admin_post_nopriv_{{SITE_PREFIX}}_contact', '{{SITE_PREFIX}}_contact_form_handler' );

function {{SITE_PREFIX}}_contact_form_handler() {
	$ref = wp_get_referer() ?: home_url( '/kontakt/' );

	// 1) Nonce
	if ( ! check_admin_referer( '{{SITE_PREFIX}}_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'err', 'nonce', $ref ) );
		exit;
	}

	// 2) Honeypot (bot fills hidden "website" field → success-fake)
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'ok', '1', $ref ) );
		exit;
	}

	// 3) Rate-Limit (5/IP/10min)
	$ip    = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
	$key   = '{{SITE_PREFIX}}_kf_' . md5( $ip );
	$count = (int) get_transient( $key );
	if ( $count >= 5 ) {
		wp_safe_redirect( add_query_arg( 'err', 'ratelimit', $ref ) );
		exit;
	}
	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );

	// 4) Sanitize
	$name    = isset( $_POST['name'] )    ? sanitize_text_field( wp_unslash( $_POST['name'] ) )    : '';
	$phone   = isset( $_POST['phone'] )   ? sanitize_text_field( wp_unslash( $_POST['phone'] ) )   : '';
	$email   = isset( $_POST['email'] )   ? sanitize_email( wp_unslash( $_POST['email'] ) )       : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$dsgvo   = ! empty( $_POST['dsgvo'] );

	// 5) Pflichtfelder
	if ( ! $name || ! $email || ! $dsgvo ) {
		wp_safe_redirect( add_query_arg( 'err', 'missing', $ref ) );
		exit;
	}

	// 6) Lead anlegen (siehe inc/cpt-lead.php)
	if ( function_exists( '{{SITE_PREFIX}}_lead_create' ) ) {
		{{SITE_PREFIX}}_lead_create( array(
			'source'  => 'kontakt',
			'name'    => $name,
			'phone'   => $phone,
			'email'   => $email,
			'message' => $message,
		) );
	}

	// 7) Tracking + Redirect
	if ( function_exists( '{{SITE_PREFIX}}_track_increment' ) ) {
		{{SITE_PREFIX}}_track_increment( 'contact_submit' );
	}
	wp_safe_redirect( add_query_arg( 'ok', '1', $ref ) );
	exit;
}
