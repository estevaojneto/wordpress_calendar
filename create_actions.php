<?php
/** This file has the functions for the add_actions we defined in main.php;
 * these set much of what our plugin does.
 * FUNCTIONS:
 * setRewriteRules(): sets rewrite rules to avoid problematic archiving and broken links,
 * and gives us some nice readable event URLs;
 * 
 * setEventRecurrency(): creates multiple events with a certain time interval between
 * them, as requested by documentation;
 * 
 * createEventCPTAndTaxonomy(): creates a CPT called "event", and also its taxonomy;
 * for simplicity, we'll create these together as their existance is very closely linked;
 * 
 * loadFullCalendarCss(): FullCalendar is the jQuery plugin we are using on the archive page,
 * and this function is responsible for loading its CSS (must be loaded before the script);
 * 
 * loadFullCalendarJs(): same as above but with JS; this must be loaded *after* the style and
 * *after* jQuery is loaded.
 */

function setRewriteRules()
{
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%category%/%postname%');
    return;
}

function setEventRecurrency($post_id, $post)
{
    if(!is_object($post) || !isset($post->post_type)) {
        return;
    }
	$recurrency_rate = get_post_meta( $post->ID, 'recurrency', true );
    return;
}

function createEventCPTAndTaxonomy()
{
    $labels = array(
        'name'                  => _x('Events', 'Event General Name', 'text_domain'),
        'singular_name'         => _x('Event', 'Event Singular Name', 'text_domain'),
        'menu_name'             => __('Events', 'text_domain'),
        'name_admin_bar'        => __('Event', 'text_domain'),
        'archives'              => __('Item Archives', 'text_domain'),
        'attributes'            => __('Item Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent Item:', 'text_domain'),
        'all_items'             => __('All Items', 'text_domain'),
        'add_new_item'          => __('Add New Item', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Item', 'text_domain'),
        'edit_item'             => __('Edit Item', 'text_domain'),
        'update_item'           => __('Update Item', 'text_domain'),
        'view_item'             => __('View Item', 'text_domain'),
        'view_items'            => __('View Items', 'text_domain'),
        'search_items'          => __('Search Item', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into item', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
        'items_list'            => __('Items list', 'text_domain'),
        'items_list_navigation' => __('Items list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter items list', 'text_domain'),
    );
    $args = array(
        'label'                 => __('Event', 'text_domain'),
        'description'           => __('Event Description', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail'),
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
        'register_meta_box_cb' => 'createEventMetaBoxes',
        'capability_type'       => 'page',
    );
    register_post_type('event', $args);
    register_taxonomy('event-category', 'event', array(
        'hierarchical'    => false,
        'singular_name'   => __('Event'),
        'label'           => __('Event categories'),
        'query_var'       => 'events-category',
        'rewrite'         => array('slug' => 'events')
       )
    );
    return;
}

function loadFullCalendarCss(){
	wp_enqueue_style('fullcalendar_css', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.css', array(), '4.2.0', 'all');
	wp_enqueue_style('fullcalendar_daygrid_css', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.min.css', array(), '4.2.0', 'all');
	return;
}

function loadFullCalendarJs() {
    wp_enqueue_script('fullcalendar_js', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.js', array( 'jquery' ), '4.2.0', false );
	wp_enqueue_script('fullcalendar_daygrid_js', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.min.js', array( 'jquery' ), '4.2.0', false );
	wp_enqueue_script('load_calendar', plugin_dir_url( __FILE__ ).'scripts/load_calendar.js', array( 'jquery' ), '0.0.1', false );
	return;
}