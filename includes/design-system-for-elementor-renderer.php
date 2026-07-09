<?php
namespace DesignSystemForElementor;

use Elementor\Core\Kits\Documents\Kit;
use DesignSystemForElementor\Render\Markdown_Emitter;
use DesignSystemForElementor\Render\Yaml_Emitter;
use DesignSystemForElementor\TokenBuilder\Token_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Design_System_For_Elementor_Renderer {

	public function render( Kit $kit ): string {
		$settings = $kit->get_settings();
		$tokens   = ( new Token_Builder() )->build( $settings );

		return ( new Yaml_Emitter() )->emit( $tokens ) . "\n" . ( new Markdown_Emitter() )->emit( $tokens );
	}
}
