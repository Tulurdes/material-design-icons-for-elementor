<?php
/**
 * Elem_Material_Icons_Integration class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Elem_Material_Icons_Integration' ) ) {

	/**
	 * Define Elem_Material_Icons_Integration class
	 */
	class Elem_Material_Icons_Integration {

		/**
		 * Initialize integration hooks
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'elementor/editor/before_enqueue_styles',  array( $this, 'enqueue_editor_styles' ) );
			add_action( 'elementor/preview/enqueue_styles',        array( $this, 'enqueue_editor_styles' ) );
			add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
		}

		public function enqueue_editor_styles() {
			wp_enqueue_style(
				'material-icons-editor',
				elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons-editor.css' ),
				null,
				elem_material_icons()->get_version()
			);
		}

		public function add_material_icons_tabs( $tabs = array() ) {

			if ( $this->check_if_enabled_icon_style( 'filled' ) ) {
				$tabs['material-design-icons'] = array(
					'name'            => 'material-design-icons',
					'label'           => esc_html__( 'Material Design Icons - Filled', 'elem-material-icons' ),
					'labelIcon'       => 'fab fa-google',
					'prefix'          => 'md-',
					'displayPrefix'   => 'material-icons',
					'url'             => elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons.css' ),
					'fetchJson'       => elem_material_icons()->plugin_url( 'assets/material-icons/fonts/icons.json' ),
					'ver'             => elem_material_icons()->get_version(),
					'render_callback' => array( $this, 'render_material_icon' ),
				);
			}

			if ( $this->check_if_enabled_icon_style( 'outlined' ) ) {
				$tabs['material-design-icons-outlined'] = array(
					'name'            => 'material-design-icons-outlined',
					'label'           => esc_html__( 'Material Design Icons - Outlined', 'elem-material-icons' ),
					'labelIcon'       => 'fab fa-google',
					'prefix'          => 'md-',
					'displayPrefix'   => 'material-icons-outlined',
					'url'             => elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons-outlined.css' ),
					'fetchJson'       => elem_material_icons()->plugin_url( 'assets/material-icons/fonts/icons.json' ),
					'ver'             => elem_material_icons()->get_version(),
					'render_callback' => array( $this, 'render_material_icon_outlined' ),
				);
			}

			return $tabs;
		}

		public function check_if_enabled_icon_style( $style = 'filled' ) {
			static $icon_styles = null;

			if ( null === $icon_styles ) {
				$icon_styles = elem_material_icons()->settings->get_settings( 'icon_styles' );
			}

			if ( empty( $icon_styles ) || ! is_array( $icon_styles ) ) {
				return false;
			}

			return in_array( $style, $icon_styles );
		}

		public function render_material_icon( $icon, $attributes, $tag ) {
			return $this->get_material_icon_html( $icon, $attributes, $tag );
		}

		public function render_material_icon_outlined( $icon, $attributes, $tag ) {
			return $this->get_material_icon_html( $icon, $attributes, $tag, 'outlined' );
		}

		public function get_material_icon_html( $icon, $attributes, $tag, $style = '' ) {

			if ( empty( $attributes['class'] ) ) {
				$attributes['class'] = $icon['value'];
			} else {
				if ( is_array( $attributes['class'] ) ) {
					$attributes['class'][] = $icon['value'];
				} else {
					$attributes['class'] .= ' ' . $icon['value'];
				}
			}

			$value = '';

			if ( ! is_admin() ) {
				$style_suffix = ! empty( $style ) ? '-' . $style : '';
				$search       = sprintf( 'material-icons%s md-', $style_suffix );

				$value = str_replace( $search, '', $icon['value'] );
			}

			return '<' . $tag . ' ' . Elementor\Utils::render_html_attributes( $attributes ) . '>' . $value . '</' . $tag . '>';
		}
	}

}
