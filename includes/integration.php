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

		private static $icons_config = null;

		/**
		 * Initialize integration hooks
		 *
		 * @return void
		 */
		public function __construct() {

			self::$icons_config = array(
				'filled'   => array(
					'name'          => 'material-design-icons',
					'label'         => esc_html__( 'Material Design Icons - Filled', 'elem-material-icons' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons',
					'url'           => elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons-regular.css' ),
					'enqueue'       => array( elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons.css' ) ),
					'fetchJson'     => elem_material_icons()->plugin_url( 'assets/material-icons/fonts/icons.json' ),
					'ver'           => elem_material_icons()->get_version(),
				),
				'outlined' => array(
					'name'          => 'material-design-icons-outlined',
					'label'         => esc_html__( 'Material Design Icons - Outlined', 'elem-material-icons' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons-outlined',
					'url'           => elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons-outlined.css' ),
					'enqueue'       => array( elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons.css' ) ),
					'fetchJson'     => elem_material_icons()->plugin_url( 'assets/material-icons/fonts/icons.json' ),
					'ver'           => elem_material_icons()->get_version(),
				),
			);

			if ( elem_material_icons()->has_elementor() ) {
				add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
			}

			if ( elem_material_icons()->has_beaver() ) {
				add_filter( 'fl_builder_icon_sets', array( $this, 'add_material_icons_to_beaver' ) );
			}
		}

		public function add_material_icons_tabs( $tabs = array() ) {

			foreach ( self::$icons_config as $key => $config ) {
				if ( ! $this->check_if_enabled_icon_style( $key ) ) {
					continue;
				}

				$tabs[ $config['name'] ] = $config;
			}

			return $tabs;
		}

		public function add_material_icons_to_beaver( $sets = array() ) {

			foreach ( self::$icons_config as $key => $config ) {
				if ( ! $this->check_if_enabled_icon_style( $key ) ) {
					continue;
				}

				$icons  = array();
				$_icons = json_decode( file_get_contents( $config['fetchJson'] ), true );

				foreach ( $_icons['icons'] as $icon ) {
					$icons[] = $config['prefix'] . $icon;
				}

				$sets[ $config['name'] ] = array(
					'name'       => $config['label'],
					'prefix'     => $config['displayPrefix'],
					'type'       => 'mdi',
					'icons'      => $icons,
					'stylesheet' => $config['url'],
				);
			}

			//var_dump( $sets );


			return $sets;
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
