<?php
/**
 * WP-Admin Dashboard-Widget — kompakte Lead-Übersicht.
 *
 * Volle Version mit Charts, Funnel-Analyse etc. kommt aus rb-wp-shared.
 *
 * @package {{SITE_SLUG}}
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_dashboard_setup', function () {
	if ( ! current_user_can( 'edit_posts' ) ) return;
	wp_add_dashboard_widget(
		'{{SITE_PREFIX}}_lead_stats',
		'{{SITE_NAME}} · Leads',
		'{{SITE_PREFIX}}_dashboard_widget_render'
	);
} );

function {{SITE_PREFIX}}_dashboard_widget_render() {
	$leads_total = wp_count_posts( '{{SITE_PREFIX}}_lead' )->publish ?? 0;
	$leads_7d    = function_exists( '{{SITE_PREFIX}}_track_last_n_days' ) ? {{SITE_PREFIX}}_track_last_n_days( 'contact_submit', 7 ) : 0;
	?>
	<style>
	.{{SITE_PREFIX}}-dash-grid { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
	.{{SITE_PREFIX}}-dash-card { background:#F4F4F8;border-radius:6px;padding:12px 14px; }
	.{{SITE_PREFIX}}-dash-card h3 { font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#6B6660;margin:0 0 8px 0;font-weight:700; }
	.{{SITE_PREFIX}}-dash-num { font-size:24px;font-weight:900;color:#1B2A6B;line-height:1; }
	.{{SITE_PREFIX}}-dash-sub { font-size:11px;color:#6B6660;margin-top:4px; }
	</style>
	<div class="{{SITE_PREFIX}}-dash-grid">
		<div class="{{SITE_PREFIX}}-dash-card">
			<h3>Leads gesamt</h3>
			<div class="{{SITE_PREFIX}}-dash-num"><?php echo intval( $leads_total ); ?></div>
			<div class="{{SITE_PREFIX}}-dash-sub">über alle Zeiten</div>
		</div>
		<div class="{{SITE_PREFIX}}-dash-card">
			<h3>Submits letzte 7 Tage</h3>
			<div class="{{SITE_PREFIX}}-dash-num"><?php echo intval( $leads_7d ); ?></div>
			<div class="{{SITE_PREFIX}}-dash-sub">Tracking-Counter</div>
		</div>
	</div>
	<p style="margin-top:14px;"><a href="<?php echo esc_url( admin_url( 'edit.php?post_type={{SITE_PREFIX}}_lead' ) ); ?>" class="button button-primary">Alle Leads ansehen</a></p>
	<?php
}
