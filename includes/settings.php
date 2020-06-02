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

		private $key = 'elem-material-icons-settings';

		private $default = array(
			'icon_styles' => array( 'filled' ),
		);

		/**
		 * Initialize integration hooks
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Assets
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			// ADD plugin action link.
			add_filter( 'plugin_action_links_' . elem_material_icons()->plugin_basename(),  array( $this, 'plugin_action_links' ) );
		}

		public function register_page() {
			add_submenu_page(
				'elementor',
				esc_html__( 'Material Design Icons', 'elem-material-icons' ),
				esc_html__( 'Material Design Icons', 'elem-material-icons' ),
				'manage_options',
				$this->key,
				array( $this, 'render_page' )
			);
		}

		public function render_page() {
			?>
			<div class="wrap">
				<h2><?php echo get_admin_page_title() ?></h2>

				<div class="elem-material-banner">
					<a href="<?php echo $this->get_banner_url(); ?>" target="_blank">
						<img src="<?php echo elem_material_icons()->plugin_url( 'assets/images/banner.jpg' ); ?>" alt="">
					</a>
				</div>

				<form method="POST" action="options.php">
					<?php
					settings_fields( $this->key );
					do_settings_sections( $this->key );
					submit_button();
					?>
				</form>

				<a href="https://www.paypal.me/olenabartoshchak" class="elem-material-donate" target="_blank">
					<?php esc_html_e( 'Donate', 'elem-material-icons' ); ?>
				</a>
			</div>
			<?php
		}

		public function register_settings(){
			register_setting(
				$this->key,
				$this->key
			);

			add_settings_section(
				'settings_section',
				'',
				'',
				$this->key
			);

			add_settings_field(
				'icon_styles',
				esc_html__( 'Icon Styles', 'elem-material-icons' ),
				array( $this, 'render_icon_styles_control' ),
				$this->key,
				'settings_section'
			);
		}

		public function render_icon_styles_control(){
			$settings = get_option( $this->key, $this->default );
			$icon_styles = ! empty( $settings['icon_styles'] ) ? $settings['icon_styles'] : array();
			?>
			<label>
				<input type="checkbox" name="<?php echo $this->key; ?>[icon_styles][]" value="filled" <?php checked( true, in_array( 'filled', $icon_styles ) ); ?>/>
				<?php esc_html_e( 'Filled', 'elem-material-icons' ); ?>
			</label>
			<br>
			<label>
				<input type="checkbox" name="<?php echo $this->key; ?>[icon_styles][]" value="outlined" <?php checked( true, in_array( 'outlined', $icon_styles ) ); ?>/>
				<?php esc_html_e( 'Outlined', 'elem-material-icons' ); ?>
			</label>
			<?php
		}

		public function get_settings( $key = null ) {
			$settings = get_option( $this->key, $this->default );

			if ( $key ) {
				return isset( $settings[ $key ] ) ? $settings[ $key ] : null;
			}

			return $settings;
		}

		public function enqueue_assets() {

			if ( isset( $_GET['page'] ) && 'elem-material-icons-settings' === $_GET['page'] ) {
				wp_enqueue_style(
					'elem-material-icons-admin-css',
					elem_material_icons()->plugin_url( 'assets/admin/css/admin.css' ),
					false,
					elem_material_icons()->get_version()
				);
			}
		}

		/**
		 * Plugin action links.
		 *
		 * @param  array $links An array of plugin action links.
		 * @return array An array of plugin action links.
		 */
		public function plugin_action_links( $links = array() ) {

			$links['material-icons-settings'] = sprintf('<a href="%1$s">%2$s</a>',
				'admin.php?page=elem-material-icons-settings',
				esc_html__( 'Settings', 'elem-material-icons' )
			);

			return $links;
		}

		public function get_banner_url() {
			return add_query_arg(
				array(
					'utm_source'   => 'photon-wp',
					'utm_medium'   => 'material-design-icons',
					'utm_campaign' => 'vendor-plugin',
				),
				'https://crocoblock.com/'
			);
		}
	}

}
