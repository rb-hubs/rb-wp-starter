<?php
/**
 * Tracking-Infrastruktur — anonyme Counter für Konversion-Optimierung.
 *
 * DSGVO-clean: keine Cookies, keine Sessions, keine personenbezogenen Daten.
 * Reine Aggregat-Counter pro Event-Typ. Optional pro Tag (für Trend-Charts).
 *
 * @package {{SITE_SLUG}}
 */

defined( 'ABSPATH' ) || exit;

const {{SITE_PREFIX_UC}}_TRACK_RETENTION_DAYS = 90;

/**
 * Erhöht einen Event-Counter (Total + Tagesbucket).
 *
 * @param string $event   Event-Slug (whatsapp, phone, email, contact_submit, etc.)
 */
function {{SITE_PREFIX}}_track_increment( $event ) {
	$event = preg_replace( '/[^a-z0-9_-]/i', '', (string) $event );
	if ( $event === '' ) return;

	$total_key = '{{SITE_PREFIX}}_track_' . $event . '_total';
	$total     = (int) get_option( $total_key, 0 );
	update_option( $total_key, $total + 1, false );

	$day_key = '{{SITE_PREFIX}}_track_' . $event . '_' . wp_date( 'Y-m-d' );
	$day     = (int) get_option( $day_key, 0 );
	update_option( $day_key, $day + 1, false );
}

function {{SITE_PREFIX}}_track_total( $event ) {
	return (int) get_option( '{{SITE_PREFIX}}_track_' . $event . '_total', 0 );
}

function {{SITE_PREFIX}}_track_today( $event ) {
	return (int) get_option( '{{SITE_PREFIX}}_track_' . $event . '_' . wp_date( 'Y-m-d' ), 0 );
}

function {{SITE_PREFIX}}_track_last_n_days( $event, $days = 7 ) {
	$total = 0;
	for ( $i = 0; $i < $days; $i++ ) {
		$key   = '{{SITE_PREFIX}}_track_' . $event . '_' . wp_date( 'Y-m-d', strtotime( "-$i days" ) );
		$total += (int) get_option( $key, 0 );
	}
	return $total;
}

/* AJAX-Endpoint für Frontend-Click-Tracking */
add_action( 'wp_ajax_{{SITE_PREFIX}}_track',        '{{SITE_PREFIX}}_track_ajax' );
add_action( 'wp_ajax_nopriv_{{SITE_PREFIX}}_track', '{{SITE_PREFIX}}_track_ajax' );

function {{SITE_PREFIX}}_track_ajax() {
	$event = isset( $_POST['event'] ) ? sanitize_text_field( wp_unslash( $_POST['event'] ) ) : '';
	{{SITE_PREFIX}}_track_increment( $event );
	wp_send_json_success();
}

/* Cleanup: alte Tagesbuckets nach RETENTION_DAYS löschen */
add_action( '{{SITE_PREFIX}}_track_cleanup', '{{SITE_PREFIX}}_track_cleanup_run' );
if ( ! wp_next_scheduled( '{{SITE_PREFIX}}_track_cleanup' ) ) {
	wp_schedule_event( time(), 'daily', '{{SITE_PREFIX}}_track_cleanup' );
}

function {{SITE_PREFIX}}_track_cleanup_run() {
	global $wpdb;
	$cutoff = wp_date( 'Y-m-d', strtotime( '-' . {{SITE_PREFIX_UC}}_TRACK_RETENTION_DAYS . ' days' ) );
	$wpdb->query( $wpdb->prepare(
		"DELETE FROM $wpdb->options WHERE option_name LIKE %s AND option_name < %s",
		'{{SITE_PREFIX}}_track_%_2%', // Tagesbuckets enden auf YYYY-MM-DD
		'{{SITE_PREFIX}}_track_zzz_' . $cutoff
	) );
}
