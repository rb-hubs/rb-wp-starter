<?php
/**
 * CPT {{SITE_PREFIX}}_lead — Lead-Outbox.
 *
 * Alle Form-Submissions (Kontakt, Konfigurator, etc.) landen hier
 * als Post. Vorteile gegenüber direktem Sync zu CRM:
 * - Resilienz: CRM-Outage = kein Lead-Verlust
 * - Audit-Trail: komplette Liste mit Original-Payload im Admin
 * - User-UX: Submit antwortet sofort, Sync läuft im Hintergrund
 * - DSGVO: zentrale Lead-Liste mit Lösch-Aktion
 *
 * @package {{SITE_SLUG}}
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * 1) Custom Post Type
 * ---------------------------------------------------------------------- */

add_action( 'init', function () {
	register_post_type( '{{SITE_PREFIX}}_lead', array(
		'labels' => array(
			'name'               => 'Leads',
			'singular_name'      => 'Lead',
			'menu_name'          => 'Leads',
			'edit_item'          => 'Lead ansehen',
			'view_item'          => 'Lead ansehen',
			'search_items'       => 'Leads durchsuchen',
			'not_found'          => 'Keine Leads gefunden.',
			'not_found_in_trash' => 'Keine Leads im Papierkorb.',
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => false,
		'menu_position'       => 23,
		'menu_icon'           => 'dashicons-email-alt',
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'supports'            => array( 'title' ),
		'has_archive'         => false,
		'rewrite'             => false,
		'capabilities'        => array(
			'create_posts'       => 'do_not_allow',
			'edit_post'          => 'edit_post',
			'edit_posts'         => 'edit_posts',
			'edit_others_posts'  => 'edit_others_posts',
			'delete_post'        => 'delete_post',
			'delete_posts'       => 'delete_posts',
		),
		'map_meta_cap' => true,
	) );
} );

/* -------------------------------------------------------------------------
 * 2) Lead anlegen
 * ---------------------------------------------------------------------- */

/**
 * Erzeugt einen neuen Lead.
 *
 * @param array $data {
 *     @type string $source   Quelle (kontakt, konfigurator, etc.)
 *     @type string $name     Vollständiger Name
 *     @type string $phone
 *     @type string $email
 *     @type string $message
 *     @type array  $extra    Beliebige weitere Felder (werden als Meta gespeichert)
 * }
 * @return int|WP_Error  Post-ID bei Erfolg.
 */
function {{SITE_PREFIX}}_lead_create( $data ) {
	$defaults = array(
		'source'  => 'unknown',
		'name'    => '',
		'phone'   => '',
		'email'   => '',
		'message' => '',
		'extra'   => array(),
	);
	$d = wp_parse_args( $data, $defaults );

	$title = sprintf(
		'[%s] %s · %s',
		strtoupper( $d['source'] ),
		$d['name'] ?: 'Unbekannt',
		current_time( 'Y-m-d H:i' )
	);

	$post_id = wp_insert_post( array(
		'post_type'   => '{{SITE_PREFIX}}_lead',
		'post_status' => 'publish',
		'post_title'  => $title,
	), true );

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	// Standard-Felder als Meta
	update_post_meta( $post_id, '_{{SITE_PREFIX}}_source',  $d['source'] );
	update_post_meta( $post_id, '_{{SITE_PREFIX}}_name',    $d['name'] );
	update_post_meta( $post_id, '_{{SITE_PREFIX}}_phone',   $d['phone'] );
	update_post_meta( $post_id, '_{{SITE_PREFIX}}_email',   $d['email'] );
	update_post_meta( $post_id, '_{{SITE_PREFIX}}_message', $d['message'] );
	update_post_meta( $post_id, '_{{SITE_PREFIX}}_ip',      $_SERVER['REMOTE_ADDR'] ?? '' );
	update_post_meta( $post_id, '_{{SITE_PREFIX}}_ua',      $_SERVER['HTTP_USER_AGENT'] ?? '' );
	update_post_meta( $post_id, '_{{SITE_PREFIX}}_status',  'pending' );

	// Extra-Felder
	foreach ( (array) $d['extra'] as $k => $v ) {
		update_post_meta( $post_id, '_{{SITE_PREFIX}}_extra_' . sanitize_key( $k ), $v );
	}

	// Hook für Erweiterungen (CRM-Sync, Auto-Reply, Slack-Notification, etc.)
	do_action( '{{SITE_PREFIX}}_lead_created', $post_id, $d );

	return $post_id;
}

/* -------------------------------------------------------------------------
 * 3) Admin-Spalten
 * ---------------------------------------------------------------------- */

add_filter( 'manage_{{SITE_PREFIX}}_lead_posts_columns', function ( $cols ) {
	return array(
		'cb'       => $cols['cb'] ?? '',
		'title'    => 'Lead',
		'source'   => 'Quelle',
		'contact'  => 'Kontakt',
		'status'   => 'Status',
		'date'     => 'Datum',
	);
} );

add_action( 'manage_{{SITE_PREFIX}}_lead_posts_custom_column', function ( $col, $post_id ) {
	switch ( $col ) {
		case 'source':
			echo esc_html( get_post_meta( $post_id, '_{{SITE_PREFIX}}_source', true ) );
			break;
		case 'contact':
			$email = get_post_meta( $post_id, '_{{SITE_PREFIX}}_email', true );
			$phone = get_post_meta( $post_id, '_{{SITE_PREFIX}}_phone', true );
			echo esc_html( $email );
			if ( $phone ) echo '<br><small>' . esc_html( $phone ) . '</small>';
			break;
		case 'status':
			$s = get_post_meta( $post_id, '_{{SITE_PREFIX}}_status', true );
			echo '<span style="padding:2px 8px;border-radius:4px;background:' . ( $s === 'synced' ? '#d4edda' : '#fff3cd' ) . ';">' . esc_html( $s ) . '</span>';
			break;
	}
}, 10, 2 );
