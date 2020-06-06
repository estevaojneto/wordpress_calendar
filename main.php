<?php
 /*
 * Plugin Name: Basic Event Calendar - BEC
 * Description: Basic Event Calendar for a certain job position.
 * Version: 0.2
 * Author: Estevao Jadanhi Neto
 */
defined('ABSPATH') or die('This plugin must not be directly accessed. Halting.');
include_once('libraries/meta-box/meta-box.php');

add_action('init', 'eventPostType', 0);
add_action('init', 'setRewrite', 1);
add_action('init', 'loadFullCalendarCss');
add_action('wp_enqueue_scripts', 'loadFullCalendarJs');
include_once('create_actions.php');

add_filter('archive_template', 'createEventArchive') ;
add_filter('single_template', 'createSingleEventTemplate') ;
add_filter('rwmb_meta_boxes', 'eventMetaBoxes');
include_once('create_filters.php');
