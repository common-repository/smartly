<?php
/**
 * Admin Pages
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function smt_add_options_link() {
  add_submenu_page( 'edit.php?post_type=smartly', __( 'Smartly Settings', 'smartly' ), __( 'Settings', 'smartly' ), 'manage_options', 'smt-settings', 'smt_options_page' );
}
add_action( 'admin_menu', 'smt_add_options_link', 10 );
