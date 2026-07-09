<?php
namespace DesignSystemForElementor;

use Elementor\Core\Kits\Documents\Kit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Module {

	const QUERY_VAR = 'design_system_for_elementor';
	const ROUTER_OPTION_KEY = 'design_system_for_elementor_router_version';

	public function __construct() {
		add_action( 'init', [ $this, 'register_rewrite_rule' ] );
		add_filter( 'query_vars', [ $this, 'add_query_var' ] );
		add_action( 'template_redirect', [ $this, 'maybe_serve_design_system_for_elementor' ], 0 );
	}

	public function register_rewrite_rule(): void {
		add_rewrite_rule( '^design\.md/?$', 'index.php?' . self::QUERY_VAR . '=1', 'top' );

		$this->maybe_flush_rewrite_rules();
	}

	public function add_query_var( array $vars ): array {
		$vars[] = self::QUERY_VAR;

		return $vars;
	}

	public function maybe_serve_design_system_for_elementor(): void {
		if ( ! $this->is_design_system_for_elementor_request() ) {
			return;
		}

		$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit_for_frontend();

		$renderer = new Design_System_For_Elementor_Renderer();
		$output = $renderer->render( $kit );

		$output = apply_filters( 'design_system_for_elementor', $output, $kit );

		nocache_headers();
		status_header( 200 );
		header( 'Content-Type: text/markdown; charset=utf-8' );
		header( 'X-Content-Type-Options: nosniff' );
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	private function is_design_system_for_elementor_request(): bool {
		if ( get_query_var( self::QUERY_VAR ) ) {
			return true;
		}

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$request_path = untrailingslashit( (string) wp_parse_url( $request_uri, PHP_URL_PATH ) );
		$expected_path = untrailingslashit( (string) wp_parse_url( home_url( '/design.md' ), PHP_URL_PATH ) );

		return '' !== $expected_path && $request_path === $expected_path;
	}

	private function maybe_flush_rewrite_rules(): void {
		if ( get_option( self::ROUTER_OPTION_KEY ) !== DESIGN_SYSTEM_FOR_ELEMENTOR_VERSION ) {
			flush_rewrite_rules();
			update_option( self::ROUTER_OPTION_KEY, DESIGN_SYSTEM_FOR_ELEMENTOR_VERSION );
		}
	}
}
