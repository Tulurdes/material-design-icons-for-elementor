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
			add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
		}

		public function add_material_icons_tabs( $tabs = array() ) {

			if ( $this->check_if_enabled_icon_style( 'filled' ) ) {
				$tabs['material-design-icons'] = array(
					'name'          => 'material-design-icons',
					'label'         => esc_html__( 'Material Design Icons - Filled', 'elem-material-icons' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons',
					'url'           => elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons.css' ),
					'enqueue'       => array( elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons-codes.css' ) ),
					'fetchJson'     => elem_material_icons()->plugin_url( 'assets/material-icons/fonts/icons.json' ),
					'ver'           => elem_material_icons()->get_version(),
				);
			}

			if ( $this->check_if_enabled_icon_style( 'outlined' ) ) {
				$tabs['material-design-icons-outlined'] = array(
					'name'          => 'material-design-icons-outlined',
					'label'         => esc_html__( 'Material Design Icons - Outlined', 'elem-material-icons' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons-outlined',
					'url'           => elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons-outlined.css' ),
					'enqueue'       => array( elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons-codes.css' ) ),
					'fetchJson'     => elem_material_icons()->plugin_url( 'assets/material-icons/fonts/icons.json' ),
					'ver'           => elem_material_icons()->get_version(),
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
	}

}
