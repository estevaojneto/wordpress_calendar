<?php
/**
 * This file has the functions for the add_filters we defined in main.php;
 * these, too, set much of what our plugin does.
 * FUNCTIONS:
 * createEventArchive(): loads the template for the event archive page;
 * 
 * createSingleEventTemplate(): loads the template for a single event page;
 * 
 * createEventMetaBoxes(): creates MetaBoxes using Metabox.io (included in main.php),
 * complying with the requests of the documentation;
 */

function createEventArchive($archive_template)
{
    if (is_post_type_archive('event')) {
        $archive_template = BEC_PLUGIN_PATH . 'templates/archive-event/index.php';
    }
    return $archive_template;
}

function createSingleEventTemplate($single_template)
{
    if (is_single() && get_post_type() === 'event') {
        $single_template = BEC_PLUGIN_PATH . 'templates/single-event/index.php';
    }
    return $single_template;
}


function createEventMetaBoxes()
{
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
                'id' => 'start_time',
                'name' => 'Starting time',
                'required'  => true,
                'type' => 'time',
            ),
            array(
                'id' => 'end_date',
                'name' => 'End date',
                'required'  => true,
                'type' => 'date',
                'timestamp' => false
            ),
        array(
                'id' => 'end_time',
                'name' => 'End time',
                'required'  => true,
                'type' => 'time',
            ),
            array(
                'name'            => 'Recurrency',
                'desc'    => 'Selecting this setting will overrule the Ending date; many events will be created as per your settings',
                'id'              => 'recurrency',
                'type'            => 'select',
                'options'         => array(
                    0       => 'Single-time event',
                    1 => 'Every day',
                    7        => 'Every week',
                    30     => 'Every month',
                ),
                'multiple'        => false,
            'required'  => true,
                'placeholder'     => 'Select',
                'select_all_none' => true,
            ),
            array(
                'id'   => 'external_link',
                'type' => 'url',
                'name' => 'External Link (ie. event website)',
            ),
            array(
                'name'        => 'Address',
                'label_description' => 'Please insert the address and venue (e.g NYC, JFK Airport, Gate 13)',
                'id'          => 'address',
        'required'  => true,
                'type'        => 'text'
        ),
        array(
                'name'        => 'Entrance Fee',
                'label_description' => 'Enter 0 for free entry/no cost event',
                'id'          => 'price',
        'required'  => true,
                'type'        => 'number',
        'step' => '0.01'
        ),
            
        ),
    );
    return $meta_boxes;
}
