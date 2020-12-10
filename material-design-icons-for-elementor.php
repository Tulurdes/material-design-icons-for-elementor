<?php
/**
 * Plugin Name: Material Design Icons for Page Builders
 * Plugin URI:  https://github.com/photon-wp/material-design-icons-for-elementor
 * Description: Google Material Design Icons for Page Builders Icons Control
 * Version:     1.2.1
 * Author:      Photon WP
 * Author URI:  https://github.com/photon-wp
 * Text Domain: md-icons
 * License:     GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `MD_Icons` doesn't exists yet.
if ( ! class_exists( 'MD_Icons' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class MD_Icons {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    MD_Icons
		 */
		private static $instance = null;

		/**
		 * Plugin version.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $version = '1.2.1';

		/**
		 * Holder for base plugin URL.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Holder for base plugin path.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Holder for base plugin name
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_basename = null;

		/**
		 * Holder for integration component.
		 *
		 * @var MD_Icons_Integration
		 */
		public $integration = null;

		/**
		 * Holder for settings component.
		 *
		 * @var MD_Icons_Settings
		 */
		public $settings = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );

			// Init required modules.
			add_action( 'init', array( $this, 'init' ), -999 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__,   array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Returns plugin version.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Manually init required modules.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function init() {
			if ( ! $this->has_elementor() && ! $this->has_beaver() ) {
				return;
			}

			$this->load_files();

			$this->integration = new MD_Icons_Integration();
			$this->settings    = new MD_Icons_Settings();
		}

		/**
		 * Check if Elementor installed and activated.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return boolean
		 */
		public function has_elementor() {
			return did_action( 'elementor/loaded' );
		}

		/**
		 * Check if Beaver Builder installed and activated.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return boolean
		 */
		public function has_beaver() {
			return defined( 'FL_BUILDER_VERSION' );
		}

		/**
		 * Returns elementor instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return \Elementor\Plugin
		 */
		public function elementor() {
			return \Elementor\Plugin::instance();
		}

		/**
		 * Load required files.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function load_files() {
			require $this->plugin_path( 'includes/integration.php' );
			require $this->plugin_path( 'includes/settings.php' );
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @since  1.0.0
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}
		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @since  1.0.0
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Get plugin base name.
		 */
		public function plugin_basename() {
			if ( ! $this->plugin_basename ) {
				$this->plugin_basename = plugin_basename( __FILE__ );
			}

			return $this->plugin_basename;
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain( 'md-icons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return MD_Icons
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

if ( ! function_exists( 'md_icons' ) ) {

	/**
	 * Returns instance of the plugin class.
	 *
	 * @since  1.0.0
	 * @return MD_Icons
	 */
	function md_icons() {
		return MD_Icons::get_instance();
	}
}

md_icons();
