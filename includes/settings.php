<?php
/**
 * Elem_Material_Icons_Settings class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Elem_Material_Icons_Settings' ) ) {

	/**
	 * Define Elem_Material_Icons_Settings class
	 */
	class Elem_Material_Icons_Settings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   Elem_Material_Icons_Settings
		 */
		private static $instance = null;

		/**
		 * Initialize integration hooks
		 *
		 * @return void
		 */
		public function __construct() {

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return Elem_Material_Icons_Settings
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
