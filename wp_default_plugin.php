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
add_action('init', array('wp_default_plugin', 'init'));

class wp_default_plugin{
	
	/**
	 * 
	 * The main 'loader'
	 */
	function init() {

		//Setup the translation
		load_plugin_textdomain('wp_default_plugin',false, dirname(plugin_basename( __FILE__ ) ) . '/lang/');
		
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
    	wp_enqueue_script('jquery-ui-datepicker', self::get_plugin_url() . '/js/jquery.ui.datepicker.min.js', array('jquery', 'jquery-ui-core') );
		wp_enqueue_script('twitterfavs-admin-js', self::get_plugin_url() . '/js/admin.js', array('jquery-ui-datepicker') );
		
		//Smoothness style
		wp_enqueue_style('jquery.ui.smoothness', self::get_plugin_url() . '/css/smoothness/jquery-ui-1.8.17.custom.css');
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
    public static function plugin_desactivation(){
    	//Do something (remove cron...)
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
			'display'   => __('Every 10 minutes'), 
		);
		
		return $schedules;
	}

	/**
	 * 
	 * get the plugin's path url
	 */
	public static function get_plugin_url(){
		return get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	}
}