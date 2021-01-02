<?php
/**
 * MD_Icons_Shortcodes class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MD_Icons_Shortcodes' ) ) {

	/**
	 * Define MD_Icons_Shortcodes class
	 */
	class MD_Icons_Shortcodes {

		/**
		 * Initialize hooks
		 *
		 * @return void
		 */
		public function __construct() {
			add_shortcode( 'md_icon', array( $this, 'do_shortcode' ) );
		}

		/**
		 * Handle shortcode
		 *
		 * @param  array  $atts
		 * @return string
		 */
		public function do_shortcode( $atts = array() ) {

			$atts = shortcode_atts( array(
				'style' => '',
				'icon'  => '',
			), $atts, 'md_icon' );

			$result = '';

			if ( empty( $atts['style'] ) || empty( $atts['icon'] ) ) {
				return $result;
			}

			$config = md_icons()->integration->get_icons_config( $atts['style'] );

			if ( ! $config ) {
				return $result;
			}

			$result = sprintf( '<div class="md-icon-wrap"><i class="%1$s %2$s"></i></div>', $config['displayPrefix'], $atts['icon'] );

			return $result;
		}
	}

}
