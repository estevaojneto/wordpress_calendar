<?php
/**
 * This file has the functions for the add_actions we defined in main.php;
 * these set much of what our plugin does.
 * FUNCTIONS:
 * setRewriteRules(): sets rewrite rules to avoid problematic archiving and
 * broken links and gives us some nice readable event URLs;
 * setEventRecurrency(): creates multiple events with a certain time interval
 * between them, as requested by documentation;
 * createEventCPTAndTaxonomy(): creates a CPT called "event", and also taxonomy;
 * for simplicity, we'll create these together as they're closely linked;
 * loadFullCalendarCss(): Loads the CSS for the jQuery plugin we are using on
 * the archive page
 * loadFullCalendarJs(): same as above but with JS; this must be loaded *after*
 * the style and *after* jQuery is loaded.
 */

function setRewriteRules()
{
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%category%/%postname%');
    return;
}

function loadPluginI18N()
{
    $languageFolder = basename(dirname(__FILE__)) . '/languages/';
    load_plugin_textdomain('becTextDomain', false, $languageFolder);
    return;
}
function registerEventArchiveMenu()
{
    global $wp_admin_bar;
    $args = array(
        'id'    => 'eventArchiveBar',
        'title' => __('Navigate archive', 'becTextDomain'),
        'href'  => site_url().'/events',
    );
    $wp_admin_bar->add_node($args);
    return;
}
function setEventRecurrency($post_id, $post)
{
    if (!is_object($post) || !isset($post->post_type)) {
        return;
    }
    $recurrency_rate = get_post_meta($post->ID, 'recurrency', true);
    return;
}

function createEventCPTAndTaxonomy()
{
    $labels = array(
        'name'                  => _x('Events', 'Event General Name', 'becTextDomain'),
        'singular_name'         => _x('Event', 'Event Singular Name', 'becTextDomain'),
        'menu_name'             => __('Events', 'becTextDomain'),
        'name_admin_bar'        => __('Event', 'becTextDomain'),
        'archives'              => __('Item Archives', 'becTextDomain'),
        'attributes'            => __('Item Attributes', 'becTextDomain'),
        'parent_item_colon'     => __('Parent Item:', 'becTextDomain'),
        'all_items'             => __('All Items', 'becTextDomain'),
        'add_new_item'          => __('Add New Item', 'becTextDomain'),
        'add_new'               => __('Add New', 'becTextDomain'),
        'new_item'              => __('New Item', 'becTextDomain'),
        'edit_item'             => __('Edit Item', 'becTextDomain'),
        'update_item'           => __('Update Item', 'becTextDomain'),
        'view_item'             => __('View Item', 'becTextDomain'),
        'view_items'            => __('View Items', 'becTextDomain'),
        'search_items'          => __('Search Item', 'becTextDomain'),
        'not_found'             => __('Not found', 'becTextDomain'),
        'not_found_in_trash'    => __('Not found in Trash', 'becTextDomain'),
        'featured_image'        => __('Featured Image', 'becTextDomain'),
        'set_featured_image'    => __('Set featured image', 'becTextDomain'),
        'remove_featured_image' => __('Remove featured image', 'becTextDomain'),
        'use_featured_image'    => __('Use as featured image', 'becTextDomain'),
        'insert_into_item'      => __('Insert into item', 'becTextDomain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'becTextDomain'),
        'items_list'            => __('Items list', 'becTextDomain'),
        'items_list_navigation' => __('Items list navigation', 'becTextDomain'),
        'filter_items_list'     => __('Filter items list', 'becTextDomain'),
    );
    $args = array(
        'label'                 => __('Event', 'becTextDomain'),
        'description'           => __('Event Description', 'becTextDomain'),
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
    register_taxonomy(
        'event-category',
        'event',
        array(
            'hierarchical'    => false,
            'singular_name'   => __('Event'),
            'label'           => __('Event categories'),
            'query_var'       => 'events-category',
            'rewrite'         => array('slug' => 'events')
        )
    );
    return;
}

function loadBECStyles()
{
    wp_enqueue_style(
        'fullcalendar_css',
        'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.css',
        array(),
        '4.2.0',
        'all'
    );
    wp_enqueue_style(
        'fullcalendar_daygrid_css',
        'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.min.css',
        array(),
        '4.2.0',
        'all'
    );
    wp_enqueue_style(
        'BEC_styles',
        BEC_PLUGIN_URL.'styles/main.css',
        array(),
        '0.2',
        'all'
    );
    return;
}

function loadFullCalendarJS()
{
    wp_enqueue_script(
        'fullcalendar_js',
        'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.js',
        array('jquery'),
        '4.2.0',
        false
    );
    wp_enqueue_script(
        'fullcalendar_daygrid_js',
        'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.min.js',
        array('jquery'),
        '4.2.0',
        false
    );
    wp_enqueue_script(
        'load_calendar',
        BEC_PLUGIN_URL.'scripts/load_calendar.js',
        array('jquery'),
        '0.0.1',
        false
    );
    return;
}
