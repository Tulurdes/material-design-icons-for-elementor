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
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   Elem_Material_Icons_Integration
		 */
		private static $instance = null;

		/**
		 * Initialize integration hooks
		 *
		 * @return void
		 */
		public function __construct() {
			add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
		}

		public function add_material_icons_tabs( $tabs = array() ) {

			$tabs['material-design-icons'] = array(
				'name'          => 'material-design-icons',
				'label'         => esc_html__( 'Material Design Icons', 'elem-material-icons' ),
				'labelIcon'     => 'fab fa-google',
				'prefix'        => 'md-',
				'displayPrefix' => 'material-icons',
				'url'           => elem_material_icons()->plugin_url( 'assets/material-icons/css/material-icons.css' ),
				'fetchJson'     => elem_material_icons()->plugin_url( 'assets/material-icons/fonts/material-icons.json' ),
				'ver'           => '3.0.1',
			);

			return $tabs;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return Elem_Material_Icons_Integration
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}
