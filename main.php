<?php
 /*
 * Plugin Name: Basic Event Calendar - BEC
 * Description: Basic Event Calendar for a certain job position.
 * Version: 0.2
 * Author: Estevao Jadanhi Neto
 */

// Our plugin must not be directly accessed for security reasons.
defined('ABSPATH') or die('This plugin must not be directly accessed. Halting.');
// Metabox was included as a library for this example; for a production environment,
// it would be more proper to include it as a dependency.
define( 'BEC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
include_once('libraries/meta-box/meta-box.php');

/* These will set our action hooks.
 * Their names are very self-explanatory, I hope; the functions I am
 * describing here are all on create_actions.php (PSR-2 specifies we should
 * not create symbols AND alter logic in the same file).
 */
add_action('init', 'createEventCPTAndTaxonomy', 0);
add_action('init', 'setRewriteRules', 1);
add_action('wp_enqueue_scripts', 'loadFullCalendarCss'); 
add_action('wp_enqueue_scripts', 'loadFullCalendarJs');
add_action('save_post_event', 'setEventRecurrency', 1, 2);
include_once('create_actions.php');

/* Now we will set our filters.
 * Their names are very self-explanatory, I hope; the functions I am
 * describing here are all on create_filters.php (PSR-2 specifies we should
 * not create symbols AND alter logic in the same file).
 */
add_filter('archive_template', 'createEventArchive') ;
add_filter('single_template', 'createSingleEventTemplate') ;
add_filter('rwmb_meta_boxes', 'createEventMetaBoxes');
include_once('create_filters.php');
