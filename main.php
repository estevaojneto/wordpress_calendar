<?php
/**
 * Plugin Name: Basic Event Calendar - BEC
 * Description: Basic Event Calendar for a certain job position.
 * Version: 0.1
 * Author: Estevao Jadanhi Neto
 */
defined( 'ABSPATH' ) or die( 'This plugin must not be directly accessed. Halting.' );
// Register Custom Post Type
include_once('libraries/meta-box/meta-box.php');

final class BEC
{
	private $pluginPath;
	private $pluginAssets;
	
	public function __construct() {
		$this->setConfig();
		$this->setHooks();
    }
	
	private function setConfig(){
		$this->pluginPath = dirname(__FILE__);
		$this->pluginPages = $this->pluginAssets . '/assets';
		return;
	}

    private function setHooks(){
        //Actions:
		//Create a custom post type ("events")
		add_action( 'init', array( $this, 'eventPostType' ) );		
		//Filters:
        //Adds custom metaboxes using Metabox.io
        add_filter( 'rwmb_meta_boxes', array( $this, 'eventMetaBoxes' ));
		add_filter( 'page_template', array( $this, 'createEventArchive' ) );
        return;
    }
	
	public function createEventArchive(){
		if ( is_page( 'events_archive' ) ) {
			$page_template = $this->pluginAssets . '/events_archive.php';
		}
		return $page_template;
	}
	
	public function eventPostType(){
		$labels = array(
			'label'           => __('Events'),
			'name'               => __( 'Events' ),
			'singular_name'      => __( 'Event' ),
			'add_new'            => __( 'Add New Event' ),
			'add_new_item'       => __( 'Add New Event' ),
			'edit_item'          => __( 'Edit Event' ),
			'new_item'           => __( 'Add New Event' ),
			'view_item'          => __( 'View Event' ),
			'search_items'       => __( 'Search Event' ),
			'not_found'          => __( 'No events found' ),
			'not_found_in_trash' => __( 'No events found in Trash' )
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'comments',
			'revisions',
		);

		$args = array(
			'labels'               => $labels,
			'supports'             => $supports,
			'public'               => true,
			'capability_type'      => 'post',
			'rewrite'              => array( 'slug' => 'events' ),
			'has_archive'          => true,
			'menu_icon'            => 'dashicons-calendar-alt',
			'register_meta_box_cb' => 'eventMetaBoxes',
		);
		
		register_taxonomy('events-category', 'events', array(
			'hierarchical'    => true,
			'label'           => __('Events'),
			'query_var'       => 'events-category',
			'rewrite'         => array('slug' => 'events' ),
			)
		);
		
		register_post_type( 'events', $args );
		
	}
	public function eventMetaBoxes(){
	    $meta_boxes[] = array(
        'title' => 'Event Information',
		'post_types' => 'events',
        'fields' => array(
            array(
                'id' => 'start_date',
                'name' => 'Starting date & time',
				'required'  => true,
				'type' => 'datetime'
            ),
			array(
                'id' => 'end_date',
                'name' => 'End date',
				'required'  => false,
				'type' => 'datetime'
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
	
}
	
$GLOBALS['BEC'] = new BEC();
