<?php
/**
 * Admin Options Page
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function smt_options_page() {
  $settings_tabs = edd_get_settings_tabs();
  $$active_tab = 'test';
  ob_start();
	?>
  <div class="wrap <?php echo 'wrap-' . $active_tab; ?>">
		<h2><?php _e( 'Smartly Settings', 'smartly'  ); ?></h2>
		<h2 class="nav-tab-wrapper">
    </h2>
  </div><!-- .wrap -->
  <?php
  echo ob_get_clean();
}
