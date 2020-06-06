<?php
/**
 * Plugin Name: Basic Event Calendar - BEC
 * Description: Basic Event Calendar for a certain job position.
 * Version: 0.2
 * Author: Estevao Jadanhi Neto
 */
defined( 'ABSPATH' ) or die( 'This plugin must not be directly accessed. Halting.' );
include_once('libraries/meta-box/meta-box.php');

add_action( 'init', 'eventPostType', 0 );
add_action( 'init', function() {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%category%/%postname%' );
} );

add_filter( 'rwmb_meta_boxes', 'eventMetaBoxes' );

function createEventArchive( $archive_template ) {
     global $post;
     if ( is_post_type_archive ( 'event' ) ) {
          $archive_template = dirname( __FILE__ ) . '/archive-event.php';
     }
     return $archive_template;
}

function createSingleEventTemplate( $single_template ) {
     global $post;
     if ( is_single() && get_post_type() === 'event' ) {
          $single_template = dirname( __FILE__ ) . '/single-event.php';
     }
     return $single_template;
}


	
add_filter( 'archive_template', 'createEventArchive' ) ;
add_filter( 'single_template', 'createSingleEventTemplate' ) ;


function eventPostType() {
	$labels = array(
		'name'                  => _x( 'Events', 'Event General Name', 'text_domain' ),
		'singular_name'         => _x( 'Event', 'Event Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Events', 'text_domain' ),
		'name_admin_bar'        => __( 'Event', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Event', 'text_domain' ),
		'description'           => __( 'Event Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,		         
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'rewrite' => array('slug' => 'events'),
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'register_meta_box_cb' => 'eventMetaBoxes',
		'capability_type'       => 'page',
	);	
	register_post_type( 'event', $args );
	register_taxonomy('event-category', 'event', array(
		'hierarchical'    => false,
		'singular_name'	  => __( 'Event' ),
		'label'           => __( 'Event categories' ),
		'query_var'       => 'events-category',
		'rewrite'         => array('slug' => 'events' ),
		)
	);

}

function eventMetaBoxes(){
	    $meta_boxes[] = array(
        'title' => 'Event Information',
		'post_types' => 'event',
        'fields' => array(
            array(
                'id' => 'start_date',
                'name' => 'Starting date',
				'required'  => true,
				'type' => 'date',
				'timestamp' => false
            ),
			array(
                'id' => 'end_date',
                'name' => 'End date',
				'required'  => false,
				'type' => 'date',
				'timestamp' => false
            ),
			array(
				'name'            => 'Recurrency',
				'desc'	=> 'Selecting this setting will do stuff TODO',
				'id'              => $prefix,
				'type'            => 'select',
				'options'         => array(
					'0'       => 'Single-time event',
					'1' => 'Every day',
					'7'        => 'Every week',
					'30'     => 'Every month',
				),
				'multiple'        => false,
				'placeholder'     => '',
				'select_all_none' => true,
			),
			array(
				'id'   => 'external_link',
				'type' => 'url',
				'name' => 'External Link (ie. event website)',
			),
		),
    );
    return $meta_boxes;	
}
