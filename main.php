<?php
 /*
 * Plugin Name: Basic Event Calendar - BEC
 * Description: A WordPress plugin for an event calendar.
 * Version: 0.2
 * Author: Estevao Jadanhi Neto
 * Text Domain: becTextDomain
 * Domain Path: /languages
 */

// Our plugin must not be directly accessed for security reasons.
defined('ABSPATH') or die('This plugin must not be directly accessed. Halting.');
// Declare some useful constants

// Requires useful constants
require_once('constants.php');
// Requires some useful helper functions; see helpers.php for more
require_once( BEC_PLUGIN_PATH . 'helpers.php');

// Metabox was included as a library for this example; for a production environment,
// it would be more proper to include it as a dependency using composer.
include_once('libraries/meta-box/meta-box.php');

/* These will set our action hooks.
 * Their names are very self-explanatory, I hope; the functions I am
 * describing here are all on create_actions.php (PSR-2 specifies we should
 * not create symbols AND alter logic in the same file).
 */
add_action('init', 'createEventCPTAndTaxonomy', 0);
add_action('init', 'setRewriteRules', 1);
add_action('plugins_loaded', 'loadPluginI18N');
add_action('wp_enqueue_scripts', 'loadBECStyles'); 
add_action('wp_enqueue_scripts', 'loadFullCalendarJS');
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
