<?php
/**
 * MD_Icons_Settings class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MD_Icons_Settings' ) ) {

	/**
	 * Define MD_Icons_Settings class
	 */
	class MD_Icons_Settings {

		private $key = 'elem-material-icons-settings';

		private $default = array(
			'icon_styles' => array( 'filled' ),
		);

		/**
		 * Initialize hooks
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );

			// Enqueue assets
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			// Save settings
			add_action( 'wp_ajax_md_icons_save_settings', array( $this, 'save_settings' ) );

			// ADD plugin action link.
			add_filter( 'plugin_action_links_' . md_icons()->plugin_basename(),  array( $this, 'plugin_action_links' ) );
		}

		public function register_page() {
			add_submenu_page(
				'options-general.php',
				esc_html__( 'Material Design Icons for Page Builders', 'md-icons' ),
				esc_html__( 'Material Design Icons', 'md-icons' ),
				'manage_options',
				$this->key,
				array( $this, 'render_page' )
			);
		}

		public function render_page() {
			include md_icons()->get_template( 'admin/settings.php' );
		}

		public function get_settings( $key = null ) {
			$settings = get_option( $this->key, $this->default );

			if ( $key ) {
				return isset( $settings[ $key ] ) ? $settings[ $key ] : null;
			}

			return $settings;
		}

		public function enqueue_assets() {

			if ( ! isset( $_GET['page'] ) || $this->key !== $_GET['page'] ) {
				return;
			}

			$module_data = md_icons()->modules->get_included_module_data( 'cherry-x-vue-ui.php' );
			$ui          = new CX_Vue_UI( $module_data );

			$ui->enqueue_assets();

			wp_enqueue_script(
				'md-icons-admin',
				md_icons()->plugin_url( 'assets/admin/js/admin.js' ),
				array( 'cx-vue-ui' ),
				md_icons()->get_version(),
				true
			);

			wp_localize_script(
				'md-icons-admin',
				'MDIconsConfig',
				array(
					'iconStyles'  => $this->get_settings( 'icon_styles' ),
					'iconsConfig' => md_icons()->integration->get_icons_config(),
					'ourPluginsJson' => 'https://raw.githubusercontent.com/FlexyAddons/plugins-list/main/plugins.json',
					'i18n' => array(
						'saved' => __( 'Saved!', 'md-icon' ),
						'error' => __( 'Error!', 'md-icon' ),
					),
				)
			);

			wp_enqueue_style(
				'md-icons-admin',
				md_icons()->plugin_url( 'assets/admin/css/admin.css' ),
				false,
				md_icons()->get_version()
			);
		}

		/**
		 * Save settings
		 */
		public function save_settings() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You don\'t have permissions to do this', 'md-icons' ),
				) );
			}

			$new = isset( $_REQUEST['settings'] ) ? $_REQUEST['settings'] : array();

			update_option( $this->key, $new );

			wp_send_json_success();
		}

		/**
		 * Plugin action links.
		 *
		 * @param  array $links An array of plugin action links.
		 * @return array An array of plugin action links.
		 */
		public function plugin_action_links( $links = array() ) {

			$links['material-icons-settings'] = sprintf('<a href="%1$s">%2$s</a>',
				'admin.php?page=' . $this->key,
				esc_html__( 'Settings', 'md-icons' )
			);

			return $links;
		}

		public function get_banner_url() {
			return add_query_arg(
				array(
					'utm_source'   => 'photon-wp',
					'utm_medium'   => 'mdi-banner',
					'utm_campaign' => 'vendor-plugin',
				),
				'https://crocoblock.com/plugins/'
			);
		}

		public function print_banner_html() {
			printf(
				'<div class="md-icons-banner md-icons-banner--side"><a href="%1$s" target="_blank"><img src="%2$s" width="216" height="384" alt=""></a></div>',
				$this->get_banner_url(),
				md_icons()->plugin_url( 'assets/images/banner.jpg' )
			);
		}
	}

}
