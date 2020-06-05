<?php
/**
 * Plugin Name: Basic Event Calendar - BEC
 * Description: Basic Event Calendar for a certain job position.
 * Version: 0.1
 * Author: Estevao Jadanhi Neto
 */
defined( 'ABSPATH' ) or die( 'This plugin must not be directly accessed. Halting.' );

final class BEC{
    public function __construct() {
		$this->setHooks();
    }

	private function setHooks(){
		//Actions:
		add_action( 'the_content', array( $this, 'showCalendar' ) );
		//Filters:
	}
	public function showCalendar ( $content ) {
    	return $content .= '<input type="date"/>'; // if it shows a calendar input, it's working
	}
	
}

$GLOBALS['BEC'] = new BEC();
