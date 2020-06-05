<?php
/**
 * Plugin Name: Basic Event Calendar - BEC
 * Description: Basic Event Calendar for a certain job position.
 * Version: 0.1
 * Author: Estevao Jadanhi Neto
 */
defined( 'ABSPATH' ) or die( 'This plugin must not be directly accessed. Halting.' );

final class BEC
{
	private $pluginPath;
	private $pluginPages;
	
	public function __construct() {
		$this->setConfig();
		$this->setHooks();
    }
	
	private function setConfig(){
		$this->pluginPath = dirname(__FILE__);
		$this->pluginPages = $this->pluginPath . '/pages';
		return;
	}

    private function setHooks(){
        //Actions:
        add_action( 'the_content', array( $this, 'showCalendar' ) );

        //Adds a menu item:
        add_action( 'admin_menu', array( $this, 'createMenuItem' ) );

        //Filters:
        return;
    }
	
	public function showCalendar ( $content ) {
        return $content .= '<input type="date"/>';
	}

    public function createMenuItem(){
        $page_title = 'BEC - Events';   
		$menu_title = 'BEC - Events';   
		$capability = 'manage_options';
		$menu_slug  = 'bec_settings';
		$function   = array($this,'createSettingsPage');
		$icon_url   = 'dashicons-media-code';   
		add_menu_page( $page_title , $menu_title , $capability , $menu_slug , $function , $icon_url );
	}
	
	public function createSettingsPage(){
		require_once($this->pluginPages . '/settings.php' );
		return;
	}
	
}
	
$GLOBALS['BEC'] = new BEC();
