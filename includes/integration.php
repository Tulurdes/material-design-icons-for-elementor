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
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons-outlined',
					'url'           => md_icons()->plugin_url( 'assets/material-icons/css/material-icons-outlined.css' ),
					'enqueue'       => array( md_icons()->plugin_url( 'assets/material-icons/css/material-icons.css' ) ),
					'fetchJson'     => md_icons()->plugin_url( 'assets/material-icons/fonts/icons.json' ),
					'ver'           => md_icons()->get_version(),
				),
			);

			if ( md_icons()->has_elementor() ) {
				add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
			}

			if ( md_icons()->has_beaver() ) {
				add_filter( 'fl_builder_icon_sets',               array( $this, 'add_material_icons_to_beaver' ) );
				add_action( 'wp_enqueue_scripts',                 array( $this, 'register_icons_assets' ) );
				add_action( 'wp_enqueue_scripts',                 array( $this, 'enqueue_icons_assets_in_beaver_editor' ) );
				add_action( 'fl_builder_enqueue_styles_for_icon', array( $this, 'enqueue_icons_assets_for_beaver_module' ) );
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
					'name'   => $config['label'],
					'prefix' => $config['displayPrefix'],
					'type'   => 'mdi',
					'icons'  => $icons,
				);
			}

			return $sets;
		}

		public function enqueue_icons_assets_in_beaver_editor() {

			if ( ! FLBuilderModel::is_builder_active() ) {
				return;
			}

			foreach ( self::$icons_config as $key => $config ) {
				if ( ! $this->check_if_enabled_icon_style( $key ) ) {
					continue;
				}

				wp_enqueue_style( $config['name'] );
			}
		}

		public function enqueue_icons_assets_for_beaver_module( $icon ) {
			foreach ( self::$icons_config as $key => $config ) {

				if ( ! $this->check_if_enabled_icon_style( $key ) ) {
					continue;
				}

				if ( stristr( $icon, $config['displayPrefix'] . ' ' . $config['prefix'] ) ) {
					wp_enqueue_style( $config['name'] );

					return;
				}
			}
		}

		public function register_icons_assets() {

			$shared_styles = array();

			foreach ( self::$icons_config as $key => $config ) {

				if ( ! $this->check_if_enabled_icon_style( $key ) ) {
					continue;
				}

				$dependencies = array();

				if ( ! empty( $config['enqueue'] ) ) {

					foreach ( (array) $config['enqueue'] as $css_url ) {

						if ( ! in_array( $css_url, array_keys( $shared_styles ) ) ) {

							$count = count( $shared_styles );
							$style_handle = ( 0 < $count ) ? 'material-icons-' . count( $shared_styles ) : 'material-icons';

							wp_register_style(
								$style_handle,
								$css_url,
								false,
								$config['ver']
							);

							$shared_styles[ $css_url ] = $style_handle;
						}

						$dependencies[] = $shared_styles[ $css_url ];
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
	}

}
