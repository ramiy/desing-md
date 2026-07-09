<?php
/**
 * Plugin Name:       Design System for Elementor
 * Plugin URI:        https://wordpress.org/plugins/design-system-for-elementor/
 * Description:       Exposes an Elementor site's active Kit (Site Settings) as a machine-readable file, following Google's design.md specification.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * Author:            Rami Yushuvaev
 * Author URI:        https://rami.blog
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       design-system-for-elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DESIGN_SYSTEM_FOR_ELEMENTOR_VERSION', '1.0.0' );
define( 'DESIGN_SYSTEM_FOR_ELEMENTOR_FILE', __FILE__ );
define( 'DESIGN_SYSTEM_FOR_ELEMENTOR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Load all include files in dependency order.
 */
function design_system_for_elementor_load_files(): void {
	require_once DESIGN_SYSTEM_FOR_ELEMENTOR_PATH . 'includes/render/yaml-emitter.php';
	require_once DESIGN_SYSTEM_FOR_ELEMENTOR_PATH . 'includes/render/markdown-emitter.php';
	require_once DESIGN_SYSTEM_FOR_ELEMENTOR_PATH . 'includes/token-builder/kit-settings-reader.php';
	require_once DESIGN_SYSTEM_FOR_ELEMENTOR_PATH . 'includes/token-builder/component-builder.php';
	require_once DESIGN_SYSTEM_FOR_ELEMENTOR_PATH . 'includes/token-builder/token-builder.php';
	require_once DESIGN_SYSTEM_FOR_ELEMENTOR_PATH . 'includes/design-system-for-elementor-renderer.php';
	require_once DESIGN_SYSTEM_FOR_ELEMENTOR_PATH . 'includes/module.php';
}

register_activation_hook(
	DESIGN_SYSTEM_FOR_ELEMENTOR_FILE,
	function (): void {
		design_system_for_elementor_load_files();
		$module = new \DesignSystemForElementor\Module();
		$module->register_rewrite_rule();
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	DESIGN_SYSTEM_FOR_ELEMENTOR_FILE,
	function (): void {
		flush_rewrite_rules();
		delete_option( \DesignSystemForElementor\Module::ROUTER_OPTION_KEY );
	}
);

add_action( 'plugins_loaded', 'design_system_for_elementor_boot' );

/**
 * Boot the plugin after all plugins are loaded.
 *
 * Bails with an admin notice if Elementor is not active.
 */
function design_system_for_elementor_boot(): void {
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action(
			'admin_notices',
			function (): void {
				echo '<div class="notice notice-error"><p>';
				echo esc_html__( 'Design System for Elementor requires the Elementor plugin to be installed and active.', 'design-system-for-elementor' );
				echo '</p></div>';
			}
		);
		return;
	}

	design_system_for_elementor_load_files();
	new \DesignSystemForElementor\Module();
}
