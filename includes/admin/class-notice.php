<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class SMARTLY_NOTICES {

  public function __construct() {
		add_action( 'admin_notices', array( $this, 'display_notices' ) );
	}

  public function display_notices() {
    settings_errors( 'smartly-notices' );
  }

}
new SMARTLY_NOTICES;
