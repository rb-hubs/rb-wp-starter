<?php
/**
 * {{SITE_NAME}} Theme – functions.php
 *
 * @package {{SITE_SLUG}}
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( '{{SITE_PREFIX_UC}}_VERSION', '{{SITE_VERSION}}' );
define( '{{SITE_PREFIX_UC}}_DIR',     get_template_directory() );
define( '{{SITE_PREFIX_UC}}_URI',     get_template_directory_uri() );

// Composer autoload (rb-wp-shared & dependencies).
if ( file_exists( {{SITE_PREFIX_UC}}_DIR . '/vendor/autoload.php' ) ) {
	require_once {{SITE_PREFIX_UC}}_DIR . '/vendor/autoload.php';
}

// Core includes – generic, taken from rb-wp-starter.
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/helpers.php';
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/customizer.php';
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/schema-jsonld.php';
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/contact-form.php';
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/cpt-lead.php';
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/tracking.php';
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/auto-reply.php';
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/smtp.php';
require_once {{SITE_PREFIX_UC}}_DIR . '/inc/dashboard-widget.php';

// Site-specific includes – add your own here.
// require_once {{SITE_PREFIX_UC}}_DIR . '/inc/your-feature.php';

/**
 * Theme setup.
 */
function {{SITE_PREFIX}}_theme_setup() {
	load_theme_textdomain( '{{SITE_SLUG}}', {{SITE_PREFIX_UC}}_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
		'gallery', 'caption', 'script', 'style', 'navigation-widgets',
	) );
	add_theme_support( 'custom-logo', array(
		'height' => 120, 'width' => 360, 'flex-height' => true, 'flex-width' => true,
	) );
	add_theme_support( 'align-wide' );

	// Editor stylesheet to match frontend.
	add_editor_style( 'assets/css/editor.css' );

	// Custom image sizes.
	add_image_size( '{{SITE_PREFIX}}-hero',      1920, 1080, true );
	add_image_size( '{{SITE_PREFIX}}-card',       800,  600, true );
	add_image_size( '{{SITE_PREFIX}}-card-wide', 1200,  800, true );
	add_image_size( '{{SITE_PREFIX}}-square',     600,  600, true );
}
add_action( 'after_setup_theme', '{{SITE_PREFIX}}_theme_setup' );

/**
 * Enqueue frontend assets.
 */
function {{SITE_PREFIX}}_enqueue_frontend() {
	wp_enqueue_style(
		'{{SITE_PREFIX}}-theme',
		get_stylesheet_uri(),
		array(),
		{{SITE_PREFIX_UC}}_VERSION
	);

	wp_enqueue_style(
		'{{SITE_PREFIX}}-main',
		{{SITE_PREFIX_UC}}_URI . '/assets/css/main.css',
		array( '{{SITE_PREFIX}}-theme' ),
		{{SITE_PREFIX_UC}}_VERSION
	);

	if ( file_exists( {{SITE_PREFIX_UC}}_DIR . '/assets/js/main.js' ) ) {
		wp_enqueue_script(
			'{{SITE_PREFIX}}-main',
			{{SITE_PREFIX_UC}}_URI . '/assets/js/main.js',
			array(),
			{{SITE_PREFIX_UC}}_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', '{{SITE_PREFIX}}_enqueue_frontend' );

/**
 * Enqueue editor assets.
 */
function {{SITE_PREFIX}}_enqueue_editor() {
	wp_enqueue_style(
		'{{SITE_PREFIX}}-editor',
		{{SITE_PREFIX_UC}}_URI . '/assets/css/editor.css',
		array(),
		{{SITE_PREFIX_UC}}_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', '{{SITE_PREFIX}}_enqueue_editor' );
