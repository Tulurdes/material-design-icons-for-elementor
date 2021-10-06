<?php
/**
 * MD_Icons_Integration class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MD_Icons_Integration' ) ) {

	/**
	 * Define MD_icons_Integration class
	 */
	class MD_Icons_Integration {

		private static $icons_config = null;

		private static $shared_styles = array();

		/**
		 * Initialize integration hooks
		 *
		 * @return void
		 */
		public function __construct() {

			self::$icons_config = array(
				'filled'   => array(
					'name'          => 'material-design-icons',
					'label'         => esc_html__( 'Material Design Icons - Filled', 'md-icons' ),
					'shortLabel'    => esc_html__( 'Filled', 'md-icons' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons',
					'url'           => md_icons()->plugin_url( 'assets/material-icons/css/material-icons-regular.css' ),
					'enqueue'       => array( md_icons()->plugin_url( 'assets/material-icons/css/material-icons.css' ) ),
					'fetchJson'     => md_icons()->plugin_url( 'assets/material-icons/fonts/icons.json' ),
					'ver'           => md_icons()->get_version(),
				),
				'outlined' => array(
					'name'          => 'material-design-icons-outlined',
					'label'         => esc_html__( 'Material Design Icons - Outlined', 'md-icons' ),
					'shortLabel'    => esc_html__( 'Outlined', 'md-icons' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons-outlined',
					'url'           => md_icons()->plugin_url( 'assets/material-icons/css/material-icons-outlined.css' ),
					'enqueue'       => array( md_icons()->plugin_url( 'assets/material-icons/css/material-icons.css' ) ),
					'fetchJson'     => md_icons()->plugin_url( 'assets/material-icons/fonts/icons-outlined.json' ),
					'ver'           => md_icons()->get_version(),
				),
			);

			add_action( 'init', array( $this, 'load_integration_packages' ) );
		}

		public function load_integration_packages() {

			$packages = array(
				'elementor.php' => array(
					'cb'   => 'did_action',
					'args' => 'elementor/loaded',
				),
				'beaver-builder.php' => array(
					'cb'   => 'defined',
					'args' => 'FL_BUILDER_VERSION',
				),
			);

			foreach ( $packages as $file => $condition ) {
				if ( call_user_func( $condition['cb'], $condition['args'] ) ) {
					require md_icons()->plugin_path( 'includes/integration/' . $file );
				}
			}
		}

		public function get_icons_config( $lib = null ) {

			if ( $lib ) {
				return isset( self::$icons_config[ $lib ] ) ? self::$icons_config[ $lib ] : null;
			}

			return self::$icons_config;
		}

		public function check_if_enabled_icon_style( $style = 'filled' ) {
			static $icon_styles = null;

			if ( null === $icon_styles ) {
				$icon_styles = md_icons()->settings->get_settings( 'icon_styles' );
			}

			if ( empty( $icon_styles ) || ! is_array( $icon_styles ) ) {
				return false;
			}

			return in_array( $style, $icon_styles );
		}

		public function get_json_content( $json_url ) {

			$json_path = str_replace( md_icons()->plugin_url(), md_icons()->plugin_path(), $json_url );

			ob_start();
			include $json_path;
			$json = ob_get_clean();

			return json_decode( $json, true );
		}

		public function register_icons_assets() {

			foreach ( $this->get_icons_config() as $key => $config ) {

				if ( ! $this->check_if_enabled_icon_style( $key ) ) {
					continue;
				}

				$this->register_icon_style_assets( $config );
			}
		}

		public function register_icon_style_assets( $config ) {

			$dependencies = array();

			if ( ! empty( $config['enqueue'] ) ) {

				foreach ( (array) $config['enqueue'] as $css_url ) {

					if ( ! in_array( $css_url, array_keys( self::$shared_styles ) ) ) {

						$count = count( self::$shared_styles );
						$style_handle = ( 0 < $count ) ? 'material-icons-' . count( self::$shared_styles ) : 'material-icons';

						wp_register_style(
							$style_handle,
							$css_url,
							false,
							$config['ver']
						);

						self::$shared_styles[ $css_url ] = $style_handle;
					}

					$dependencies[] = self::$shared_styles[ $css_url ];
				}
			}

			wp_register_style(
				$config['name'],
				$config['url'],
				$dependencies,
				$config['ver']
			);
		}
	}

}
