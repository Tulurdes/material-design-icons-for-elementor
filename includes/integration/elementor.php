<?php
/**
 * Elementor Integration class
 */

namespace MD_Icons_Integration;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Elementor Integration class
 */
class Elementor {

	/**
	 * Initialize integration hooks
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
	}

	public function add_material_icons_tabs( $tabs = array() ) {

		$icons_config = md_icons()->integration->get_icons_config();

		foreach ( $icons_config as $key => $config ) {
			if ( ! md_icons()->integration->check_if_enabled_icon_style( $key ) ) {
				continue;
			}

			// Dequeue dependency styles on frontend.
			if ( ! \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				$config['enqueue'] = array();
			}

			$config['render_callback'] = array( $this, 'render_icon' );

			$tabs[ $config['name'] ] = $config;
		}

		return $tabs;
	}

	public function render_icon( $icon, $attributes, $tag ) {

		if ( empty( $attributes['class'] ) ) {
			$attributes['class'] = $icon['value'];
		} else {
			if ( is_array( $attributes['class'] ) ) {
				$attributes['class'][] = $icon['value'];
			} else {
				$attributes['class'] .= ' ' . $icon['value'];
			}
		}

		$value = $icon['value'];
		$value = explode( ' ', $value );

		$icon_value = ! empty( $value[1] ) ? $value[1] : false;

		if ( $icon_value ) {
			$attributes['data-md-icon'] = str_replace( 'md-', '', $icon_value );
		}

		return '<' . $tag . ' ' . \Elementor\Utils::render_html_attributes( $attributes ) . '></' . $tag . '>';
	}
}

new Elementor();
