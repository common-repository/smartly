<?php
/**
 * Admin Options set settings
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function smt_get_settings() {
  $settings = get_option( 'smt_settings' );
  if( empty( $settings ) ) {
		$settings = array(
      'change_single_name'  => esc_html__( 'Product', 'smartly' ),
      'change_plural_name'  => esc_html__( 'Products', 'smartly' ),
      'set_slug'            => 'smartly'
    );
		update_option( 'smt_settings', $settings );
	}
  $settings = apply_filters( 'smt_get_settings', $settings );

  if ( ! defined( 'SMARTLY_SLUG' ) && $settings['set_slug'] ) {
    define( 'SMARTLY_SLUG', $settings['set_slug'] );
  }

  if ( ! defined( 'SMARTLY_SINGULAR_LABEL' ) && $settings['change_single_name'] ) {
    define( 'SMARTLY_SINGULAR_LABEL', $settings['change_single_name'] );
  }

  if ( ! defined( 'SMARTLY_PLURAL_LABEL' ) && $settings['change_plural_name'] ) {
    define( 'SMARTLY_PLURAL_LABEL', $settings['change_plural_name'] );
  }
	return $settings;
}

function smt_get_settings_tabs() {
  $settings = smt_get_registered_settings();

  $tabs             = array();
	$tabs['general']  = esc_html__( 'General', 'smartly' );
  if( ! empty( $settings['extensions'] ) ) {
		$tabs['extensions'] = esc_html__( 'Extensions', 'smartly' );
	}
  return apply_filters( 'smt_settings_tabs', $tabs );
}


function smt_get_registered_settings() {
  $smt_settings = array(
    'general' => apply_filters( 'smt_settings_general',
      array(
        'fields' => array(
          'change_single_name' => array(
            'id'   => 'change_single_name',
						'name' => __( 'Change Product Name (Single)', 'smartly' ),
						'desc' => '',
						'type' => 'text',
						'size' => 'medium',
            'default' => __( 'Product', 'smartly' ),
          ),
          'change_plural_name' => array(
            'id'   => 'change_single_name',
						'name' => __( 'Change Product Name (Plural)', 'smartly' ),
						'desc' => '',
						'type' => 'text',
						'size' => 'medium',
            'default' => __( 'Products', 'smartly' ),
          ),
          'set_slug' => array(
            'id'   => 'change_slug',
						'name' => __( 'Change Slug', 'smartly' ),
						'desc' => '',
						'type' => 'text',
						'size' => 'medium',
            'default' => 'smartly',
          )
        )
      )
    ),
    'extensions' => apply_filters( 'smt_settings_extensions',
      array()
    )
  );
  return apply_filters( 'smt_registered_settings', $smt_settings );
}

function smt_register_settings() {
  if ( false == get_option( 'smt_settings' ) ) {
		add_option( 'smt_settings' );
	}

  foreach ( smt_get_registered_settings() as $section_id => $section) {

      add_settings_section(
				'smt_settings_' . $section_id,
				__return_null(),
				'__return_false',
				'smt_settings_' . $section_id
			);
      if(!isset($section['fields'])){
        continue;
      }

      $fields = array();
      foreach ($section['fields'] as $name => $field) {

          $type = isset( $field['type'] ) ? $field['type'] : 'text';
          $label = isset( $field['name'] ) ? $field['name'] : '';
          $callback = function_exists( 'smt_' . $type . '_callback' ) ? 'smt_' . $type . '_callback' : 'smt_error_callback';
          $args = array(
              'id'                => $name,
              'class'             => isset( $field['class'] ) ? $field['class'] : $name,
              'label_for'         => "{$section_id}[{$name}]",
              'desc'              => isset( $field['desc'] ) ? $field['desc'] : '',
              'name'              => $label,
              'section'           => $section_id,
              'size'              => isset( $field['size'] ) ? $field['size'] : null,
              'options'           => isset( $field['options'] ) ? $field['options'] : '',
              'std'               => isset( $field['default'] ) ? $field['default'] : '',
              'sanitize_callback' => isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '',
              'type'              => $type,
              'readonly'          => false,
              'placeholder'       => isset( $field['placeholder'] ) ? $field['placeholder'] : '',
              'min'               => isset( $field['min'] ) ? $field['min'] : '',
              'max'               => isset( $field['max'] ) ? $field['max'] : '',
              'step'              => isset( $field['step'] ) ? $field['step'] : '',
              'inline'            => isset( $field['inline'] ) ? $field['inline'] : false,
              'conditional'       => isset( $field['conditional']) ? $field['conditional'] : '',
          );

          // add_settings_field( $id, $title, $callback, $page, $section, $args );
          add_settings_field(
              "{$section_id}[{$name}]",
              $label,
              $callback,
              'smt_settings_' . $section_id,
              'smt_settings_' . $section_id,
              $args
          );
      }
  }

  register_setting( 'smt_settings', 'smt_settings', 'smt_settings_validate' );
}
add_action( 'admin_init', 'smt_register_settings' );


function smt_settings_validate( $input = array() ) {
  global $smartly_options;

  $doing_section = false;
	if ( ! empty( $_POST['_wp_http_referer'] ) ) {
		$doing_section = true;
	}

  $input         = $input ? $input : array();
  if ( $doing_section ) {

		parse_str( $_POST['_wp_http_referer'], $referrer );
    $referrer = array_map( 'sanitize_text_field', $referrer );
		$tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';
		$section  = isset( $referrer['section'] ) ? $referrer['section'] : 'fields';

		$setting_types = smt_get_registered_settings_types( $tab, $section );
		$input = apply_filters( 'smt_settings_' . $tab . '_sanitize', $input );
	}

	// Merge our new settings with the existing
	$output = array_merge( $smartly_options, $input );

	foreach ( $setting_types as $key => $type ) {

		if ( empty( $type ) ) {
			continue;
		}
		$non_setting_types = apply_filters( 'smt_non_setting_types', array(
			'header', 'descriptive_text', 'hook',
		) );

		if ( in_array( $type, $non_setting_types ) ) {
			continue;
		}

		if ( $doing_section ) {
			switch( $type ) {
				case 'checkbox':
				case 'multicheck':
					if ( array_key_exists( $key, $input ) && $output[ $key ] === '-1' ) {
						unset( $output[ $key ] );
					}
					break;
				case 'text':
					if ( array_key_exists( $key, $input ) && empty( $input[ $key ] ) ) {
						unset( $output[ $key ] );
					}
					break;
				default:
					if ( array_key_exists( $key, $input ) && empty( $input[ $key ] ) || ( array_key_exists( $key, $output ) && ! array_key_exists( $key, $input ) ) ) {
						unset( $output[ $key ] );
					}
					break;
			}
		} else {
			if ( empty( $input[ $key ] ) ) {
				unset( $output[ $key ] );
			}
		}
	}

	if ( $doing_section ) {
		add_settings_error( 'smartly-notices', '', __( 'Settings updated.', 'smartly'  ), 'updated' );
	}
	return $output;
}

function smt_get_registered_settings_types( $filtered_tab = false, $filtered_section = false ) {
	$settings      = smt_get_registered_settings();
	$setting_types = array();
  // print_r($settings);
	foreach ( $settings as $tab_id => $tab ) {

		if ( false !== $filtered_tab && $filtered_tab !== $tab_id ) {
			continue;
		}

		foreach ( $tab as $section_id => $section_or_setting ) {

			// See if we have a setting registered at the tab level for backwards compatibility
			if ( false !== $filtered_section && is_array( $section_or_setting ) && array_key_exists( 'type', $section_or_setting ) ) {
				$setting_types[ $section_or_setting['id'] ] = $section_or_setting['type'];
				continue;
			}

			if ( false !== $filtered_section && $filtered_section !== $section_id ) {
				continue;
			}

			foreach ( $section_or_setting as $section => $section_settings ) {
				if ( ! empty( $section_settings['type'] ) ) {
					$setting_types[ $section_settings['id'] ] = $section_settings['type'];
				}
			}
		}
	}

	return $setting_types;
}

// 빈값인 경우 기본값 채우기
function smt_settings_default_set( $input ) {
  $fields = smt_get_registered_settings();
  $general_fields = $fields['general']['fields'];
  foreach ($input as $key => $value) {
    if(empty($value)){
      $input[$key] = $general_fields[$key]['default'];
    }
  }
  return $input;
}
add_filter( 'smt_settings_general_sanitize', 'smt_settings_default_set');
