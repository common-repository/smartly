<?php
/**
 * Admin Options set settings
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function smt_error_callback($args) {
	printf(
		__( 'The callback function used for the %s setting is missing.', 'easy-digital-downloads' ),
		'<strong>' . esc_attr( $args['id'] ) . '</strong>'
	);
}

function smt_date_callback( $args ) {
		smt_text_callback($args);
}

function smt_url_callback( $args ) {
		smt_text_callback( $args );
}

function smt_header_callback( $args ) {
		echo smt_get_field_description( $args );
}

function smt_text_callback( $args ) {
    $value       	= esc_attr( smt_setting_get_option( $args['id'], $args['section'], $args['std'] ) );
    $size        	= isset( $args['size'] ) && !is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
    $type        	= isset( $args['type'] ) ? esc_attr( $args['type'] ) : 'text';
    $placeholder 	= empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"';
    $conditional 	= ! empty( $args['conditional'] ) ? 'data-conditional="' . esc_attr( json_encode( $args['conditional'] ) ) . '"' : '';
    $prefix_attr 	= ! empty( $args['section'] ) ? ' data-prefix="df-' . esc_attr( $args['section'] ) . '" ' : '';
    $type_attr 		= ' data-field_type="' . esc_attr( $args['type'] ) . '" ';
    $field_id    	= ' data-field_id="' . esc_attr( $args['id'] ). '"';
    $data_attr 		= apply_filters( 'setting_'. esc_attr( $args['type'] ).'_fields_data_attr', $conditional . $prefix_attr . $type_attr . $field_id , $args );
    $html        	= sprintf( '<input type="%1$s" class="%2$s-text danbi_setting_form_field" id="%3$s-%4$s" name="smt_settings[%4$s]" value="%5$s"%6$s%7$s/>', $type, $size, esc_attr( $args['section'] ), esc_attr( $args['id'] ), $value, $placeholder, $data_attr );
    $html        .= smt_get_field_description( $args );
		echo apply_filters( 'smt_text_callback', $html );
}

function smt_number_callback( $args ) {
		$value       = esc_attr( smt_setting_get_option( $args['id'], $args['section'], $args['std'] ) );
		$size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
		$type        = isset( $args['type'] ) ? esc_attr( $args['type'] ) : 'number';
		$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"';
		$min         = empty( $args['min'] ) ? '' : ' min="' . esc_attr( $args['min'] ). '"';
		$max         = empty( $args['max'] ) ? '' : ' max="' . esc_attr( $args['max'] ). '"';
		$step        = empty( $args['max'] ) ? '' : ' step="' . esc_attr( $args['step'] ). '"';
		$field_id    = ' data-field_id="' . esc_attr( $args['id'] ) . '"';
		$html        = sprintf( '<input type="%1$s" class="%2$s-number danbi_setting_form_field" id="%3$s-%4$s" name="smt_settings[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s%10$s/>', $type, $size, esc_attr( $args['section'] ), esc_attr( $args['id'] ), $value, $placeholder, $min, $max, $step, $field_id );
		$html       .= smt_get_field_description( $args );
		echo apply_filters( 'smt_number_callback', $html );
}

function smt_checkbox_callback( $args ) {
		$value 				= esc_attr( smt_setting_get_option( $args['id'], $args['section'], $args['std'] ) );
		$conditional 	= ! empty( $args['conditional'] ) ? 'data-conditional="' . esc_attr( json_encode( $args['conditional'] ) ) . '"' : '';
		$prefix_attr 	= ! empty( $args['section'] ) ? ' data-prefix="df-' . esc_attr( $args['section'] ) . '" ' : '';
		$type_attr 		= ' data-field_type="' . esc_attr( $args['type'] ) . '" ';
		$field_id    	= ' data-field_id="' . esc_attr( $args['id'] ). '"';
		$data_attr 		= apply_filters( 'setting_'. esc_attr( $args['type'] ) .'_fields_data_attr', $conditional . $prefix_attr . $type_attr . $field_id , $args );
		$html  				= '<fieldset>';
		$html  			 .= sprintf( '<label for="df-%1$s-%2$s">', esc_attr( $args['section'] ), esc_attr( $args['id'] ) );
		$html  			 .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', esc_attr( $args['section'] ), esc_attr( $args['id'] ) );
		$html  			 .= sprintf( '<input type="checkbox" class="checkbox danbi_setting_form_field" id="%1$s-%2$s" name="smt_settings[%2$s]" value="on" %3$s%4$s />', esc_attr( $args['section'] ), esc_attr( $args['id'] ), checked( $value, 'on', false ), $data_attr );
		$html  			 .= sprintf( '%1$s</label>', $args['desc'] );
		$html  			 .= '</fieldset>';
		echo $html;
}

function smt_multicheck_callback( $args ) {
		$value 				= smt_setting_get_option( $args['id'], $args['section'], $args['std'] );
		$conditional 	= ! empty( $args['conditional'] ) ? 'data-conditional="' . esc_attr( json_encode( $args['conditional'] ) ) . '"' : '';
		$prefix_attr 	= ! empty( $args['section'] ) ? ' data-prefix="df-' . esc_attr( $args['section'] ) . '" ' : '';
		$type_attr 		= ' data-field_type="' . esc_attr( $args['type'] ) . '" ';
		$field_id    	= ' data-field_id="' . esc_attr( $args['id'] ) . '"';
		$data_attr 		= apply_filters( 'setting_'. esc_attr( $args['type'] ) .'_fields_data_attr', $conditional . $prefix_attr . $type_attr . $field_id , $args );
		$html  				= '<fieldset>';
		$html 			 .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="" />', esc_attr( $args['section'] ), esc_attr( $args['id'] ) );
		foreach ( $args['options'] as $key => $label ) {
				$checked 	= isset( $value[$key] ) ? $value[$key] : '0';
				$html    .= sprintf( '<label for="%1$s-%2$s-%3$s">', esc_attr( $args['section'] ), esc_attr( $args['id'] ), $key );
				$html    .= sprintf( '<input type="checkbox" class="checkbox danbi_setting_form_field" id="%1$s-%2$s-%3$s" name="smt_settings[%2$s][%3$s]" value="%3$s" %4$s%5$s />', esc_attr( $args['section'] ), esc_attr( $args['id'] ), $key, checked( $checked, $key, false ), $data_attr );
				$html    .= sprintf( '%1$s</label><br>',  $label );
		}
		$html .= smt_get_field_description( $args );
		$html .= '</fieldset>';
		echo $html;
}

function smt_radio_callback( $args ) {
		$value 					= smt_setting_get_option( $args['id'], $args['section'], $args['std'] );
		$conditional 		= ! empty( $args['conditional'] ) ? 'data-conditional="' . esc_attr( json_encode( $args['conditional'] ) ) . '"' : '';
		$prefix_attr 		= ! empty( $args['section'] ) ? ' data-prefix="df-' . esc_attr( $args['section'] ) . '" ' : '';
		$type_attr 			= ' data-field_type="' . esc_attr( $args['type'] ) . '" ';
		$field_id    		= ' data-field_id="' . esc_attr( $args['id'] ) . '"';
		$data_attr 			= apply_filters( 'setting_'. esc_attr( $args['type'] ) .'_fields_data_attr', $conditional . $prefix_attr . $type_attr . $field_id , $args );
		$html  					= '<fieldset>';
		foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<label for="%1$s-%2$s-%3$s" style="%4$s">',  esc_attr( $args['section'] ), esc_attr( $args['id'] ), $key, ($args['inline'] ? 'padding-right: 16px;' : 'display: block;') );
				$html .= sprintf( '<input type="radio" class="radio danbi_setting_form_field" id="%1$s-%2$s-%3$s" name="%1$s[%2$s]" value="%3$s" %4$s%5$s />', esc_attr( $args['section'] ), esc_attr( $args['id'] ), $key, checked( $value, $key, false ), $data_attr );
				$html .= sprintf( '%1$s</label>', $label );
				// $html .= ($args['inline']) ? '&nbsp;&nbsp;&nbsp;' : '<br>';
		}
		$html .= smt_get_field_description( $args );
		$html .= '</fieldset>';
		echo $html;
}

function smt_select_callback( $args ) {
		$value 				= esc_attr( smt_setting_get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  				= isset( $args['size'] ) && !is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
		$conditional 	= ! empty( $args['conditional'] ) ? 'data-conditional="' . esc_attr( json_encode( $args['conditional'] ) ) . '"' : '';
		$prefix_attr 	= ! empty( $args['section'] ) ? ' data-prefix="df-' . esc_attr( $args['section'] ) . '" ' : '';
		$type_attr 		= ' data-field_type="' . esc_attr( $args['type'] ) . '" ';
		$field_id    	= ' data-field_id="' . esc_attr( $args['id'] ) . '"';
		$data_attr 		= apply_filters( 'setting_'. esc_attr( $args['type'] ) .'_fields_data_attr', $conditional . $prefix_attr . $type_attr . $field_id , $args );
		$html  				= sprintf( '<select class="%1$s danbi_setting_form_field" name="%2$s[%3$s]" id="%2$s-%3$s" %4$s>', $size, esc_attr( $args['section'] ), esc_attr( $args['id'] ), $data_attr );
		foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
		}
		$html .= sprintf( '</select>' );
		$html .= smt_get_field_description( $args );
		echo $html;
}

function smt_get_field_description( $args ) {
    if ( ! empty( $args['desc'] ) ) {
        $desc = sprintf( '<p class="description">%s</p>', esc_attr( $args['desc'] ) );
    } else {
        $desc = '';
    }
    return $desc;
}

function smt_setting_get_option( $option, $section, $default = '' ) {
		$options = get_option( 'smt_settings' );
		if ( isset( $options[$option] ) ) {
				return $options[$option];
		}
		return $default;
}
