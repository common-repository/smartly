<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function smt_setup_add_post_types() {
  $icon_svg = 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKIHdpZHRoPSI5MDAuMDAwMDAwcHQiIGhlaWdodD0iOTAwLjAwMDAwMHB0IiB2aWV3Qm94PSIwIDAgOTAwLjAwMDAwMCA5MDAuMDAwMDAwIgogcHJlc2VydmVBc3BlY3RSYXRpbz0ieE1pZFlNaWQgbWVldCI+CjxtZXRhZGF0YT4KQ3JlYXRlZCBieSBwb3RyYWNlIDEuMTUsIHdyaXR0ZW4gYnkgUGV0ZXIgU2VsaW5nZXIgMjAwMS0yMDE3CjwvbWV0YWRhdGE+CjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAuMDAwMDAwLDkwMC4wMDAwMDApIHNjYWxlKDAuMTAwMDAwLC0wLjEwMDAwMCkiCmZpbGw9IiMwMDAwMDAiIHN0cm9rZT0ibm9uZSI+CjxwYXRoIGQ9Ik0wIDQ1MDAgbDAgLTQ1MDAgNDUwMCAwIDQ1MDAgMCAwIDQ1MDAgMCA0NTAwIC00NTAwIDAgLTQ1MDAgMCAwCi00NTAweiBtNTIwMiAyOTE5IGMyNDQgLTYwIDQ0NCAtMTEyIDQ0NiAtMTEzIDIgLTMgLTExNTMgLTM5MzcgLTExNzMgLTM5OTIKLTIgLTggLTk2IDExIC0yODkgNTkgLTI2NCA2NiAtMjg1IDczIC0yODEgOTIgMyAxMSAxODggOTA5IDQxMSAxOTk1IDIyMyAxMDg2CjQxMCAxOTk2IDQxNiAyMDIzIDUgMjYgMTQgNDcgMTkgNDcgNSAwIDIwOCAtNTAgNDUxIC0xMTF6IG0tMTE2NSAtNDc4MyBjMTM1Ci0zNiAyMzYgLTEwOCAzMTMgLTIyMiAxMTIgLTE2OSAxMjUgLTM2MCAzNyAtNTQzIC04MSAtMTY3IC0yNjUgLTI4NyAtNDU3Ci0yOTggLTE0NyAtOSAtMjc4IDM5IC0zODYgMTQwIC0xODcgMTc0IC0yMzQgNDIzIC0xMjIgNjQ5IDYxIDEyMiAyMTcgMjQ1IDM1NAoyNzcgNjAgMTQgMjAzIDEyIDI2MSAtM3oiLz4KPC9nPgo8L3N2Zz4=';
  $archives = defined( 'SMARTLY_DISABLE_ARCHIVE' ) && SMARTLY_DISABLE_ARCHIVE ? false : true;
	$slug     = defined( 'SMARTLY_SLUG' ) ? SMARTLY_SLUG : 'smartly';
  $rewrite  = defined( 'SMARTLY_DISABLE_REWRITE' ) && SMARTLY_DISABLE_REWRITE ? false : array('slug' => $slug, 'with_front' => false);

  $smartly_labels =  array(
		'name'                  => _x( '%2$s', 'smartly post type name', 'smartly' ),
		'singular_name'         => _x( '%1$s', 'singular smartly post type name', 'smartly' ),
		'add_new'               => __( 'Add New', 'smartly' ),
		'add_new_item'          => __( 'Add New %1$s', 'smartly' ),
		'edit_item'             => __( 'Edit %1$s', 'smartly' ),
		'new_item'              => __( 'New %1$s', 'smartly' ),
		'all_items'             => __( 'All %2$s', 'smartly' ),
		'view_item'             => __( 'View %1$s', 'smartly' ),
		'search_items'          => __( 'Search %2$s', 'smartly' ),
		'not_found'             => __( 'No %2$s found', 'smartly' ),
		'not_found_in_trash'    => __( 'No %2$s found in Trash', 'smartly' ),
		'parent_item_colon'     => '',
		'menu_name'             => _x( '%2$s', 'smartly post type menu name', 'smartly' ),
		'featured_image'        => __( '%1$s Image', 'smartly' ),
		'set_featured_image'    => __( 'Set %1$s Image', 'smartly' ),
		'remove_featured_image' => __( 'Remove %1$s Image', 'smartly' ),
		'use_featured_image'    => __( 'Use as %1$s Image', 'smartly' ),
		'attributes'            => __( '%1$s Attributes', 'smartly' ),
		'filter_items_list'     => __( 'Filter %2$s list', 'smartly' ),
		'items_list_navigation' => __( '%2$s list navigation', 'smartly' ),
		'items_list'            => __( '%2$s list', 'smartly' ),
	);

  foreach ( $smartly_labels as $key => $value ) {
		$smartly_labels[ $key ] = sprintf( $value, smt_get_label_singular(), smt_get_label_plural() );
	}

  $smartly_args = array(
		'labels'             => $smartly_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => $rewrite,
		'map_meta_cap'       => true,
    'menu_icon'          => $icon_svg,
		'has_archive'        => $archives,
		'hierarchical'       => false,
		'supports'           => apply_filters( 'smartly_supports', array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author' ) ),
	);
	register_post_type( 'smartly',  $smartly_args  );



}
add_action( 'init', 'smt_setup_add_post_types', 1 );


function smt_setup_smartly_taxonomies() {
  $slug     = defined( 'SMARTLY_SLUG' ) ? SMARTLY_SLUG : 'smartly';
  /** Categories */
	$category_labels = array(
		'name'              =>  _x( '%s Categories', 'taxonomy general name', 'smartly' ),
		'singular_name'     =>  _x( '%s Category', 'taxonomy singular name', 'smartly' ),
		'search_items'      =>  __( 'Search %s Categories', 'smartly' ),
		'all_items'         =>  __( 'All %s Categories', 'smartly' ),
		'parent_item'       =>  __( 'Parent %s Category', 'smartly' ),
		'parent_item_colon' =>  __( 'Parent %s Category:', 'smartly' ),
		'edit_item'         =>  __( 'Edit %s Category', 'smartly' ),
		'update_item'       =>  __( 'Update %s Category', 'smartly' ),
		'add_new_item'      =>  __( 'Add New %s Category', 'smartly' ),
		'new_item_name'     =>  __( 'New %s Category Name', 'smartly' ),
		'menu_name'         => __( 'Categories', 'smartly' ),
	);

  foreach ( $category_labels as $key => $value ) {
		$category_labels[ $key ] = sprintf( $value, smt_get_label_singular(), smt_get_label_plural() );
	}

	$category_args = array(
			'hierarchical' => true,
			'labels'       => $category_labels,
			'show_ui'      => true,
			'query_var'    => 'smartly_category',
			'rewrite'      => array('slug' => $slug . '/category', 'with_front' => false, 'hierarchical' => true ),
			'capabilities' => array( 'manage_terms' => 'manage_product_terms','edit_terms' => 'edit_product_terms','assign_terms' => 'assign_product_terms','delete_terms' => 'delete_product_terms' )
	);

  register_taxonomy( 'smartly_category', array('smartly'), $category_args );
  register_taxonomy_for_object_type( 'smartly_category', 'smartly' );

	/** Tags */
	$tag_labels = array(
		'name'                  => _x( '%s Tags', 'taxonomy general name', 'smartly' ),
		'singular_name'         => _x( '%s Tag', 'taxonomy singular name', 'smartly' ),
		'search_items'          => __( 'Search %s Tags', 'smartly' ),
		'all_items'             => __( 'All %s Tags', 'smartly' ),
		'parent_item'           => __( 'Parent %s Tag', 'smartly' ),
		'parent_item_colon'     => __( 'Parent %s Tag:', 'smartly' ),
		'edit_item'             => __( 'Edit %s Tag', 'smartly' ),
		'update_item'           => __( 'Update %s Tag', 'smartly' ),
		'add_new_item'          => __( 'Add New %s Tag', 'smartly' ),
		'new_item_name'         => __( 'New %s Tag Name', 'smartly' ),
		'menu_name'             => __( 'Tags', 'smartly' ),
		'choose_from_most_used' => __( 'Choose from most used %s tags', 'smartly' ),
	);

  foreach ( $tag_labels as $key => $value ) {
		$tag_labels[ $key ] = sprintf( $value, smt_get_label_singular(), smt_get_label_plural() );
	}

	$tag_args = array(
			'hierarchical' => false,
			'labels'       => $tag_labels,
			'show_ui'      => true,
			'query_var'    => 'smartly_tag',
			'rewrite'      => array( 'slug' => $slug . '/tag', 'with_front' => false, 'hierarchical' => true  ),
			'capabilities' => array( 'manage_terms' => 'manage_product_terms','edit_terms' => 'edit_product_terms','assign_terms' => 'assign_product_terms','delete_terms' => 'delete_product_terms' )
	);
	register_taxonomy( 'smartly_tag', array( 'smartly' ), $tag_args );
	register_taxonomy_for_object_type( 'smartly_tag', 'smartly' );
}
add_action( 'init', 'smt_setup_smartly_taxonomies', 0 );


function smt_get_default_labels() {
  $singular_label = defined( 'SMARTLY_SINGULAR_LABEL' ) ? SMARTLY_SINGULAR_LABEL : esc_html__( 'Product', 'smartly' );
  $plural_label = defined( 'SMARTLY_PLURAL_LABEL' ) ? SMARTLY_PLURAL_LABEL : esc_html__( 'Products', 'smartly' );
	$defaults = array(
	   'singular' => $singular_label,
	   'plural'   => $plural_label,
	);
	return apply_filters( 'smt_default_post_name', $defaults );
}

function smt_get_label_singular( $lowercase = false ) {
	$defaults = smt_get_default_labels();
	return ($lowercase) ? strtolower( $defaults['singular'] ) : $defaults['singular'];
}

function smt_get_label_plural( $lowercase = false ) {
	$defaults = smt_get_default_labels();
	return ( $lowercase ) ? strtolower( $defaults['plural'] ) : $defaults['plural'];
}


function smartly_render_columns( $column_name, $post_id ) {
	if ( get_post_type( $post_id ) == 'smartly' ) {
		switch ( $column_name ) {
			case 'smartly_category':
				echo get_the_term_list( $post_id, 'smartly_category', '', ', ', '');
				break;
			case 'smartly_tag':
				echo get_the_term_list( $post_id, 'smartly_tag', '', ', ', '');
				break;
      case 'catalogue':
        $files = get_post_meta( $post_id, 'smartly_catalogue_file', true );
        if(isset($files['file']) && !empty( $files['file'] )){
            echo sprintf("<a href='%s' target='_blank'>%s </a><span> ( %s ) </span><br> %s", $files['file'], $files['file_name'], $files['size_readable'] , $files['file_type']);
        }
				break;
		}
	}
}
add_action( 'manage_posts_custom_column', 'smartly_render_columns', 10, 2 );

function smartly_columns( $download_columns ) {

  $category_labels = smartly_get_taxonomy_labels( 'smartly_category' );
	$tag_labels      = smartly_get_taxonomy_labels( 'smartly_tag' );

  $smartly_columns = array(
		'cb'                => '<input type="checkbox"/>',
		'title'             => esc_html__( 'Name', 'smartly' ),
    'catalogue'         => esc_html__( 'File', 'smartly'),
		'smartly_category'  => $category_labels['menu_name'],
		'smartly_tag'       => $tag_labels['menu_name'],
		'date'              => esc_html__( 'Date', 'smartly' )
	);

  return $smartly_columns;
}
add_filter( 'manage_edit-smartly_columns', 'smartly_columns' );



function smartly_get_taxonomy_labels( $taxonomy = 'smartly_category' ) {
	$labels   = array();
	$taxonomy = get_taxonomy( $taxonomy );

	if ( false !== $taxonomy ) {
		$singular  = $taxonomy->labels->singular_name;
		$name      = $taxonomy->labels->name;
		$menu_name = $taxonomy->labels->menu_name;

		$labels = array(
			'name'          => $name,
			'singular_name' => $singular,
			'menu_name'     => $menu_name,
		);
	}

	return $labels;
}
