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

				<form method="POST" action="options.php">
					<?php
					settings_fields( $this->key );
					do_settings_sections( $this->key );
					submit_button();
					?>
				</form>
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
	}

}