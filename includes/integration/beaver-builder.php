<?php
/**
 * Beaver Builder Integration class
 */

namespace MD_Icons_Integration;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Beaver Builder Integration class
 */
class Beaver_Builder {

	/**
	 * Initialize integration hooks
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'fl_builder_icon_sets',               array( $this, 'add_material_icons_to_beaver' ) );
		add_action( 'wp_enqueue_scripts',                 array( $this, 'register_icons_assets' ) );
		add_action( 'wp_enqueue_scripts',                 array( $this, 'enqueue_icons_assets_in_beaver_editor' ) );
		add_action( 'fl_builder_enqueue_styles_for_icon', array( $this, 'enqueue_icons_assets_for_beaver_module' ) );
	}

	public function add_material_icons_to_beaver( $sets = array() ) {

		foreach ( md_icons()->integration->get_icons_config() as $key => $config ) {
			if ( ! md_icons()->integration->check_if_enabled_icon_style( $key ) ) {
				continue;
			}

			$icons  = array();
			$_icons = md_icons()->integration->get_json_content( $config['fetchJson'] );

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

		if ( ! \FLBuilderModel::is_builder_active() ) {
			return;
		}

		foreach ( md_icons()->integration->get_icons_config() as $key => $config ) {
			if ( ! md_icons()->integration->check_if_enabled_icon_style( $key ) ) {
				continue;
			}

			wp_enqueue_style( $config['name'] );
		}
	}

	public function enqueue_icons_assets_for_beaver_module( $icon ) {
		foreach ( md_icons()->integration->get_icons_config() as $key => $config ) {

			if ( ! md_icons()->integration->check_if_enabled_icon_style( $key ) ) {
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

		foreach ( md_icons()->integration->get_icons_config() as $key => $config ) {

			if ( ! md_icons()->integration->check_if_enabled_icon_style( $key ) ) {
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
}

new Beaver_Builder();
