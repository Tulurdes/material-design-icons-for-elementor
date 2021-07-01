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

			$result = sprintf(
				'<div class="md-icon-wrap"><i class="%1$s %2$s" data-md-icon="%3$s"></i></div>',
				$config['displayPrefix'],
				$atts['icon'],
				str_replace( 'md-', '', $atts['icon']  )
			);

			static $added_styles = false;

			if ( ! $added_styles ) {
				add_action( 'wp_footer', array( $this, 'print_shortcode_styles' ) );
			}

			// Dequeue dependency styles.
			$config['enqueue'] = array();

			$this->enqueue_icon_assets( $config );

			return $result;
		}

		public function print_shortcode_styles() {
			?>
			<style type="text/css">
				.md-icon-wrap {
					display: inline-flex;
					font-size: 50px;
					line-height: 1;
					text-align: center;
				}
			</style>
			<?php
		}

		/**
		 * Enqueue icon assets
		 *
		 * @param array $config
		 */
		public function enqueue_icon_assets( $config ) {

			if ( ! wp_script_is( $config['name'], 'registered' ) ) {
				md_icons()->integration->register_icon_style_assets( $config );
			}

			if ( ! wp_script_is( $config['name'], 'enqueued' ) ) {
				wp_enqueue_style( $config['name'] );
			}

		}
	}

}
