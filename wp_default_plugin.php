<?php
/*
Plugin Name: WordPress default plugin
Plugin URI: https://github.com/chibani/wp_default_plugin
Description: A base for making nice WordPress plugins
Author: LoicG
Author URI: http://blog.loicg.net/
 */


register_activation_hook(__FILE__, array('wp_default_plugin','plugin_activation'));
register_deactivation_hook(__FILE__, array('wp_default_plugin','plugin_deactivation'));
register_uninstall_hook(__FILE__, array('wp_default_plugin','plugin_uninstall'));

add_action('init', array('wp_default_plugin', 'init'));

class wp_default_plugin{
	
	const LANG_DIR = '/lang/'; // Defaut lang dirctory
	
	/**
	 * 
	 * The main 'loader'
	 */
	function init() {

		//Setup the translation
		load_plugin_textdomain('wp_default_plugin',false, dirname(plugin_basename( __FILE__ ) ) . self::LANG_DIR);
		
    	// admin actions and hooks
        if (is_admin()) {
            self::admin_hooks();
        }
    }
    
    /**
     * 
     * The admin hooks
     */
    public static function admin_hooks(){
    	include 'wp_default_plugin_main_settings.php';
    	
    	//Add schedules for wp_cron
    	add_filter('cron_schedules', array('wp_default_plugin','custom_cron_schedules'));
    	
    	//Javascript
    	/* /
        if (isset($_GET['page']) && $_GET['page'] == 'wp_default_plugin_settings') {
	    	wp_enqueue_script('jquery-ui-datepicker', plugins_url('/js/jquery.ui.datepicker.min.js',__FILE__), array('jquery', 'jquery-ui-core') );
			wp_enqueue_script('twitterfavs-admin-js', plugins_url('/js/admin.js',__FILE__), array('jquery-ui-datepicker') );
			
			//Smoothness style
			wp_enqueue_style('jquery.ui.smoothness', plugins_url('/css/smoothness/jquery-ui-1.8.17.custom.css',__FILE__));
		}

		/* */
    	
    }

	/**
     * 
     * Usually, here, we set-up database tables or default options

     */
    public static function plugin_activation(){
    	//Do nice things
    }
    
    /**
     * 
     * Purge cron and settings
     */
    public static function plugin_deactivation(){
    	//Do something (remove cron...)
    }
    
    /**
     * 
     * On plugin uninstallation
     */
    public static function plugin_uninstall(){
    	//May we remove plugin's options ...
    }
    
	/**
     * 
     * Add some new schedules
     * @param array $schedules
     */
    public static function custom_cron_schedules($schedules){
		//10 minutes, mainly for tests
		$schedules['10min'] = array(
			'interval'   => 60*10,// in seconds
			'display'   => __('Every 10 minutes','wp_default_plugin'), 
		);
		
		return $schedules;
	}
}