<?php
/**
 * Plugin Name: Smartly!
 * Plugin URI: https://danbistore.com/item/smartly
 * Description: Smart way to show your products on website.
 * Version: 1.0.0
 * Author: Danbilabs
 * Author URI: https://danbistore.com
 * Text Domain: smartly
 * Domain Path: languages/
 *
 * @package Smartly
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Smartly' ) ) :

final class Smartly {

    private static $instance;

    public static function instance() {
      if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Smartly ) ) {
        self::$instance = new Smartly;
        self::$instance->set_constants();

        self::$instance->includes();
        self::$instance->init();

        self::$instance->load_plugin_textdomain();
        // self::$instance->set_slug();
      }
    }

    private function set_constants() {
      if ( ! defined( 'SMARTLY_VERSION' ) ) {
  			define( 'SMARTLY_VERSION', '1.0.0' );
  		}

      // Plugin Folder Path.
  		if ( ! defined( 'SMARTLY_PLUGIN_DIR' ) ) {
  			define( 'SMARTLY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
  		}

  		// Plugin Folder URL.
  		if ( ! defined( 'SMARTLY_PLUGIN_URL' ) ) {
  			define( 'SMARTLY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
  		}

  		// Plugin Root File.
  		if ( ! defined( 'SMARTLY_PLUGIN_FILE' ) ) {
  			define( 'SMARTLY_PLUGIN_FILE', __FILE__ );
  		}


    }

    private function includes() {
      global $smartly_options;
      require_once SMARTLY_PLUGIN_DIR . 'includes/post-types.php';
      require_once SMARTLY_PLUGIN_DIR . 'includes/meta-box.php';
      require_once SMARTLY_PLUGIN_DIR . 'includes/admin/admin-pages.php';
      require_once SMARTLY_PLUGIN_DIR . 'includes/admin/class-notice.php';
      require_once SMARTLY_PLUGIN_DIR . 'includes/admin/settings/set-settings.php';
      $smartly_options = smt_get_settings();
      require_once SMARTLY_PLUGIN_DIR . 'includes/admin/settings/display-settings.php';
      require_once SMARTLY_PLUGIN_DIR . 'includes/admin/settings/setting-fields.php';
    }

    private function init() {
      add_action( 'admin_enqueue_scripts', array($this, 'smartly_admin_register_scripts') );
      add_action( 'admin_enqueue_scripts', array($this, 'smartly_admin_register_styles') );
    }

    function smartly_admin_register_scripts() {
      $script_dir = SMARTLY_PLUGIN_URL . 'assets/js/';

      wp_register_script( 'smt-admin-scripts', $script_dir . 'admin-script.js', array(), SMARTLY_VERSION, false );
      wp_enqueue_script( 'smt-admin-scripts' );

    	wp_localize_script( 'smt-admin-scripts', 'smt_script_vars', array(
    		'post_id'                     => isset( $post->ID ) ? $post->ID : null,
    		'smartly_version'             => SMARTLY_VERSION,
      ));

    }

    function smartly_admin_register_styles() {
      $style_dir = SMARTLY_PLUGIN_URL . 'assets/css/';

      wp_register_style( 'smt-admin-style', $style_dir . 'admin-style.css', array(), SMARTLY_VERSION, false );
	    wp_enqueue_style( 'smt-admin-style' );
    }

    public function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'smartly' );
  		// Look for wp-content/languages/plugins/smartly-{lang}_{country}.mo
  		$mofile_global = WP_LANG_DIR . '/plugins/smartly-' . $locale . '.mo';
      if ( file_exists( $mofile_global ) ) {
        load_textdomain( 'smartly', $mofile_global );
      }else{
        //기본 폴더의 언어파일 사용
        load_plugin_textdomain( 'smartly', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
      }
		}
}

endif; // End if class_exists check.

function smartly() {
	return Smartly::instance();
}

smartly();
