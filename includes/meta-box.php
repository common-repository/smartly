<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function smt_add_meta_box() {
  $label = sprintf( __( '%1$s introduce file', 'smartly' ), smt_get_label_singular(), smt_get_label_plural() );
  add_meta_box( 'smartly_catalogue', $label,  'smt_files_meta_box', 'smartly', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'smt_add_meta_box' );

function smt_files_meta_box() {
  global $post;

	do_action( 'smt_meta_box_file_field', $post->ID );
  wp_nonce_field( basename( __FILE__ ), 'smt_meta_box_nonce' );
}

function smartly_metabox_save_fields() {

	$fields = array(
    'smartly_catalogue_file' => array(
      'type'      => 'array'
    )
	);

	return apply_filters( 'smartly_metabox_save_fields', $fields );
}

function smt_smartly_type_file_field( $post_id ) {
  $files = get_post_meta( $post_id, 'smartly_catalogue_file', true );
  $defaults = array(
		'name'            => null,
		'file'            => null,
		'condition'       => null,
		'attachment_id'   => null,
    'size_readable'   => null,
		'size_bytes'      => null,
	);
  $files = wp_parse_args( $files, $defaults );

  $args = array (
        'type'        => 'input',
        'subtype'	    => 'text',
        'id'	        => 'smartly_catalogue_file_field',
        'class'	      => 'smartly_catalogue_file_field',
        'name'	      => 'smartly_catalogue_file[file]',
        'readonly'    => true,
        'required'    => '',
        'value_type'  =>'normal',
        'size'        => '80',
        'wp_data'     => 'post_meta',
        'post_id'     => $post_id,
        'file'        => $files['file'],
  );
  ?>
    <div class="smartly_attachment_field_containers">
      <div class="smartly_data_metabox">
        <input type="hidden" name="smartly_catalogue_file[attachment_id]" class="smt_attachment_id_field" value="<?php esc_attr_e( absint( $files['attachment_id'] ) ); ?>"/>
        <input type="hidden" name="smartly_catalogue_file[file_name]" class="smt_attachment_name_field" value="<?php esc_attr_e( absint( $files['file_name'] ) ); ?>"/>
        <input type="hidden" name="smartly_catalogue_file[file_type]" class="smt_attachment_type_field" value="<?php esc_attr_e( absint( $files['file_type'] ) ); ?>"/>
  			<input type="hidden" name="smartly_catalogue_file[size_readable]" class="smt_attachment_size_readable" value="<?php esc_attr_e( $files['size_readable'] ); ?>"/>
        <input type="hidden" name="smartly_catalogue_file[size_bytes]" class="smt_attachment_size_bytes" value="<?php esc_attr_e( $files['size_bytes'] ); ?>"/>
        <div class="smartly_upload_field_anchor">
          <label for="smartly_image_source"> <h2><?php  _e( 'File', 'smartly' ) ?></h2></label>
        </div>
        <div class="smartly_upload_field_container">
          <span class="smartly_files-wrap">
          <?php echo smt_smartly_render_settings_field($args , $args['file']); ?>
          </span>
          <span class="smt_upload_file">
  					<a href="#" data-uploader-title="<?php esc_html_e( 'Insert File', 'smartly' ); ?>" data-uploader-button-text="<?php esc_html_e( 'Insert', 'smartly' ); ?>" class="smartly_upload_file_button" onclick="return false;"><?php esc_html_e( 'Upload a File', 'smartly' ); ?></a>
  				</span>
        </div>
      </div>
    </div>
    <?php
}
add_action( 'smt_meta_box_file_field', 'smt_smartly_type_file_field', 10 );


function smt_smartly_render_settings_field($args, $wp_data_value) {

		switch ($args['type']) {
			case 'input':
				$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
				if($args['subtype'] != 'checkbox'){
					$prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.esc_attr( $args['prepend_value'] ).'</span>' : '';
					$prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
          $readonly   = ($args['readonly']) ? 'readonly' : '';
					$step = (isset($args['step'])) ? 'step="'.esc_attr( $args['step'] ).'"' : '';
					$min = (isset($args['min'])) ? 'min="'.esc_attr( $args['min'] ).'"' : '';
					$max = (isset($args['max'])) ? 'max="'.esc_attr( $args['max'] ).'"' : '';
					if(isset($args['disabled'])){
            echo $prependStart;
						echo '<input type="'.esc_attr__( $args['subtype'] ).'" id="'.esc_attr__( $args['id'] ).'_disabled" '.$step.' '.$max.' '.$min.' name="'.esc_attr__( $args['name'] ).'_disabled" class="'.esc_attr__( $args['class'] ).'" disabled value="' . esc_attr__($value) . '" />';
            echo '<input type="hidden" id="'.esc_attr__( $args['id'] ).'" '.$step.' '.$max.' '.$min.' name="'.esc_attr__( $args['name'] ).'" size="40" value="' . esc_attr__($value) . '" />';
            echo $prependEnd;
					} else {
            echo $prependStart;
						echo '<input type="'.esc_attr__( $args['subtype'] ).'" id="'.esc_attr__( $args['id'] ).'" "'.esc_attr__( $args['required'] ).'" '.$step.' '.$max.' '.$min.' name="'.esc_attr__( $args['name'] ).'" class="'.esc_attr__( $args['class'] ).'" value="' . esc_attr__($value) . '"  '.$readonly.' />';
            echo $prependEnd;
					}
				} else {
					$checked = ($value) ? 'checked' : '';
          $readonly   = ($args['readonly']) ? 'readonly' : '';
					echo '<input type="'.esc_attr__( $args['subtype'] ).'" id="'.esc_attr__( $args['id'] ).'" "'.esc_attr__( $args['required'] ).'" name="'.esc_attr__( $args['name'] ).'" size="40" value="1" '.$checked.' '.$readonly.'/>';
				}
				break;
			default:
				# code...
				break;
		}
}


function smt_smartly_meta_box_save( $post_id, $post ) {

  if ( ! isset( $_POST['smt_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['smt_meta_box_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
		return;
	}

	if ( isset( $post->post_type ) && 'revision' == $post->post_type ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

  $fields = smartly_metabox_save_fields();
  foreach ( $fields as $key => $field ) {
    if ( ! empty( $_POST[ $key ] ) ) {
      $field_value = '';
      switch ($field['type']) {
        case 'array':
          $field_value = sanitize_smartly_array_field(  $_POST[ $key ] );
          break;
        case 'email':
          $field_value = sanitize_email(  $_POST[ $key ] );
          break;
        default:
          $field_value = sanitize_text_field(  $_POST[ $key ] );
          break;
      }
      update_post_meta( $post_id, $key, $field_value );
    } else {
      delete_post_meta( $post_id, $key );
    }
  }
}
add_action( 'save_post', 'smt_smartly_meta_box_save', 10, 2 );


function sanitize_smartly_array_field( $input = array() ) {
  if ( is_array( $input ) ) {
    foreach ($input as $key => $value) {
      if(empty($value)){
        unset($input[$key]);
      }
      $input[$key] = sanitize_text_field(  $value );
    }
  }else{
    $input = '';
  }
  return $input;
}
