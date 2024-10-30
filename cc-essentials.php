<?php
/**
 * Plugin Name: CC Essentials
 * Plugin URI: https://wordpress.org/plugins/cc-essentials/
 * Description: CodeCookies Essentials: A set of powerful tools like shortcodes, widgets and font-awesome icons to extend the functionality of your WordPress site.
 * Version: 1.1.1
 * Author: CodeCooki.es
 * Author URI: http://codecooki.es
 * License: GPL2
 * Requires at least: 4.0
 * Tested up to: 4.6.1
 * Text Domain: cc-essentials
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'CCEssentials' ) ) :

/**
 * Main CC Essentials Class
 *
 * @package CCEssentials
 * @version 1.1.1
 * @author CodeCooki.es
 * @link http://codecooki.es
 */

class CCEssentials {

	/**
	* @var string Version of class
	* @since 1.0.0
	*/
	public $version = '1.1.1';

	/**
	 * @var A singleton instance of the class
	 * @since 1.0.0
	 */
	protected static $_inst = null;

	/**
	* @var string
	* @since 1.0.0
	*/
	public $plugin_url;

	/**
	* @var string
	* @since 1.0.0
	*/
	public $plugin_path;

	/**
	* @var string
	* @since 1.0.0
	*/
	public $template_url;

	/**
	 * The CCEssentials Instance
	 *
	 * Ensures only one instance of CCEssentials is loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Main instance of CCEssentials
	 */
	public static function instance() {
		if ( is_null( self::$_inst ) ) {
			self::$_inst = new self();
		}
		return self::$_inst;
	}

	/**
	 * CC Essentials constructor
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		// Define CCEssentials version constant
		define( 'CCE_VERSION', $this->version );

		// Set up plugin hooks
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'new_action_links' ) );
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'admin_menu', array( &$this, 'cce_settings_page' ) );
		add_action( 'after_setup_theme', array( &$this, 'cce_shortcode_css') );
		add_action( 'after_setup_theme', array( &$this, 'cce_loveit_class') );
		add_action( 'after_setup_theme', array( &$this, 'cce_contactme_methods') );

		// Include required files
		$this->includes();
	}
	
	/**
	 * Add direct link to CC Essentials Settings page on the Plugin listing page.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array
	 */
	public function new_action_links( $links ) {
		$action_links = array(
			'<a href="' . admin_url( 'options-general.php?page=cce' ) . '">' . __( 'Configure', 'cc-essentials' ) . '</a>'
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Main 'Init' function.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function init() {
		// Load the text-domain
		$this->cce_load_textdomain();
		
		// Append the shortcode css
		add_action( 'wp_enqueue_scripts', array( &$this, 'shortcode_css' ), 0 );
		
		// Add CCE body class for better control on CCE elements
		add_filter( 'body_class', array( &$this, 'body_class' ) );
		
	}

	/**
	 * Add CCEssentials admin options.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function cce_settings_page() {
		add_menu_page( __( 'CC Essentials - Settings', 'cc-essentials' ), __( 'CC Essentials', 'cc-essentials' ), 'update_core', 'cce', 'cce_options_page', 'dashicons-admin-plugins' );
	}
	
	/**
	 * CSS styles for CCE Shortcodes
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function cce_shortcode_css() {
		$cce_shortcode_css = $this->plugin_url() . '/assets/css/cce-shortcodes.css';
		add_editor_style( $cce_shortcode_css );
	}
	
	/**
	 * CCE Love It class load.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function cce_loveit_class() {
		include_once( 'loveit/cce-loveit.php' );
	}
	
	/**
	 * CCE Additional Contact Me methods for authors.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function cce_contactme_methods() {
		include_once( 'contact-methods/cce-contact-methods.php' );
	}

	/**
	 * Setup localisation.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function cce_load_textdomain() {
		
		// Set filter for plugin's languages directory
		$cce_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$cce_lang_dir = apply_filters( 'cce_languages_directory', $cce_lang_dir );

		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale',  get_locale(), 'cc-essentials' );
		$mofile        = sprintf( '%1$s-%2$s.mo', 'cc-essentials', $locale );

		// Setup paths to current locale file
		$mofile_local  = $cce_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/cce/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			
			// Look in global /wp-content/languages/cce folder
			load_textdomain( 'cc-essentials', $mofile_global );
		
		} elseif ( file_exists( $mofile_local ) ) {
			
			// Look in local /wp-content/plugins/cce/languages/ folder
			load_textdomain( 'cc-essentials', $mofile_local );
		
		} else {
			
			// Load the default language files
			load_plugin_textdomain( 'cc-essentials', false, $cce_lang_dir );
		
		}
	}

	/**
	 * Get CCE Settings and include backend & frontend files.
	 *
	 * @since 1.0.0
	 * @uses CCEssentials::backend_includes() Includes backend files
	 * @uses CCEssentials::frontend_includes() Includes frontend files
	 * @return void
	 */
	public function includes() {
		global $cce_options;
		
		// Get CCE Settings
		require_once( 'includes/settings/settings.php' );
		$cce_options = cce_get_settings();
		
		// Include backend admin files if current page is an admin page
		if ( is_admin() ){
			$this->backend_includes();
		}
		
		// Include frontend files on the rest of the site
		if ( ! is_admin() ){
			$this->frontend_includes();
		}
	}

	/**
	* Include admin files.
	*
	* @since 1.0.0
	* @see includes()
	* @return void
	*/
	public function backend_includes(){
		include_once( 'shortcodes/cce-shortcodes.php' );
		include_once( 'includes/settings/settings.php' );
		include_once( 'includes/tinymce.php' );
	}

	/**
	 * Include frontend files.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function frontend_includes(){
		include_once( plugin_dir_path( __FILE__ ) .'shortcodes/shortcodes.php' );
	}

	/**
	 * Add frontend scripts and styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function shortcode_css() {
		
		// Registering styles
		wp_register_style( 'font-awesome', $this->plugin_url() . '/assets/css/font-awesome.min.css' , array(), '4.7' );
		wp_register_style( 'cce-shortcode-styles', $this->plugin_url() . '/assets/css/cce-shortcodes.css' , array( 'font-awesome' ), $this->version, 'all' );

		// Enqueuing styles
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'cce-shortcode-styles' );

		// Registering scripts
		wp_register_script( 'cce-shortcode-scripts', $this->plugin_url(). '/assets/js/min/cce-shortcode-scripts.min.js', array( 'jquery', 'jquery-ui-accordion', 'jquery-ui-tabs' ), $this->version, true ); // This script will be enqueued only when necessary
	}

	/**
	 * Plugin path and url
	 *
	 * @since 1.0.0
	 * @return string Plugin path
	 */
	public function plugin_path() {
		if ( $this->plugin_path ) return $this->plugin_path;

		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	public function plugin_url() {
		if ( $this->plugin_url ) return $this->plugin_url;
		return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Add cce class to <body> on frontend
	 *
	 * @since 1.0.0
	 * @return array $classes List of classes
	 */
	public function body_class( $classes ) {
		$classes[] = 'cce';
		return $classes;
	}
}

endif;

/**
 * Returns the main instance of CCE to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return CCEssentials
 */
function cce() {
	return CCEssentials::instance();
}

// Global for backwards compatibility.
$GLOBALS['cce'] = cce();


/**
 * Flush the rewrite rules on activation
 */
function cce_activation() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cce_activation' );

/**
 * Also flush the rewrite rules on deactivation
 */
function cce_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cce_activation' );
