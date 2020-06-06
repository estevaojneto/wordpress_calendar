<?php
function createEventArchive($archive_template)
{
    global $post;
    if (is_post_type_archive('event')) {
        $archive_template = dirname(__FILE__) . '/archive-event.php';
    }
    return $archive_template;
}

function createSingleEventTemplate($single_template)
{
    global $post;
    if (is_single() && get_post_type() === 'event') {
        $single_template = dirname(__FILE__) . '/single-event.php';
    }
    return $single_template;
}


function eventMetaBoxes()
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
                'id' => 'end_date',
                'name' => 'End date',
                'required'  => false,
                'type' => 'date',
                'timestamp' => false
            ),
            array(
                'name'            => 'Recurrency',
                'desc'    => 'Selecting this setting will do stuff TODO',
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
