<?php
/**
 * Plugin Name:       Design.md
 * Plugin URI:        https://wordpress.org/plugins/design-md/
 * Description:       Exposes an Elementor site's active Kit (Site Settings) as a machine-readable design.md file, following Google's design.md specification.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * Author:            Rami Yushuvaev
 * Author URI:        https://rami.blog
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       design-md
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DESIGN_MD_VERSION', '1.0.0' );
define( 'DESIGN_MD_FILE', __FILE__ );
define( 'DESIGN_MD_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Load all include files in dependency order.
 */
function design_md_load_files(): void {
	require_once DESIGN_MD_PATH . 'includes/render/yaml-emitter.php';
	require_once DESIGN_MD_PATH . 'includes/render/markdown-emitter.php';
	require_once DESIGN_MD_PATH . 'includes/token-builder/kit-settings-reader.php';
	require_once DESIGN_MD_PATH . 'includes/token-builder/component-builder.php';
	require_once DESIGN_MD_PATH . 'includes/token-builder/token-builder.php';
	require_once DESIGN_MD_PATH . 'includes/design-md-renderer.php';
	require_once DESIGN_MD_PATH . 'includes/module.php';
}

register_activation_hook(
	DESIGN_MD_FILE,
	function (): void {
		design_md_load_files();
		$module = new \DesignMd\Module();
		$module->register_rewrite_rule();
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	DESIGN_MD_FILE,
	function (): void {
		flush_rewrite_rules();
		delete_option( \DesignMd\Module::ROUTER_OPTION_KEY );
	}
);

add_action( 'plugins_loaded', 'design_md_boot' );

/**
 * Boot the plugin after all plugins are loaded.
 *
 * Bails with an admin notice if Elementor is not active.
 */
function design_md_boot(): void {
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action(
			'admin_notices',
			function (): void {
				echo '<div class="notice notice-error"><p>';
				echo esc_html__( 'Design.md requires the Elementor plugin to be installed and active.', 'design-md' );
				echo '</p></div>';
			}
		);
		return;
	}

	design_md_load_files();
	new \DesignMd\Module();
}
