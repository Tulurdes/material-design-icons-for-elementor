<?php
/**
 * Elementor Integration class
 */

namespace MD_Icons_Integration;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Elementor Integration class
 */
class Elementor {

	/**
	 * Initialize integration hooks
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
	}

	public function add_material_icons_tabs( $tabs = array() ) {

		$icons_config = md_icons()->integration->get_icons_config();

		foreach ( $icons_config as $key => $config ) {
			if ( ! md_icons()->integration->check_if_enabled_icon_style( $key ) ) {
				continue;
			}

			$tabs[ $config['name'] ] = $config;
		}

		return $tabs;
	}
}

new Elementor();
