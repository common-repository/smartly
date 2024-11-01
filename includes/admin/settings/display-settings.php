<?php
/**
 * Admin Options Page
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function smt_options_page() {
  // global $wp_settings_sections, $wp_settings_fields;
  $settings_tabs = smt_get_settings_tabs();
  $settings_tabs = empty($settings_tabs) ? array() : $settings_tabs;
  $active_tab    = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
  ob_start();
  settings_errors('edd-notices');
	?>
  <div class="wrap <?php echo 'wrap-' . $active_tab; ?>">
		<h2><?php _e( 'Settings', 'smartly' ); ?></h2>
		<h2 class="nav-tab-wrapper">
    <?php
		foreach ( $settings_tabs as $tab_id => $tab_name ) {
			$tab_url = add_query_arg( array(
				'settings-updated' => false,
				'tab'              => $tab_id,
			) );
      $tab_url = remove_query_arg( 'section', $tab_url );

      $active = $active_tab == $tab_id ? ' nav-tab-active' : '';
      echo '<a href="' . esc_url( $tab_url ) . '" class="nav-tab' . $active . '">';
      echo esc_html( $tab_name );
      echo '</a>';
    }
    ?>
    </h2>
    <div id="tab_container">
      <form method="post" action="options.php">
				<table class="form-table">
        <?php
        settings_fields( 'smt_settings' );
        do_settings_sections( 'smt_settings_' . $active_tab );
        ?>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
  </div><!-- .wrap -->
  <?php
  echo ob_get_clean();
}
