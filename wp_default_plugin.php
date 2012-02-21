<?php
/*
Plugin Name: WordPress default plugin
Plugin URI: https://github.com/chibani/wp_default_plugin
Description: Not a plugin, it's more a template
Author: LoicG
Author URI: http://blog.loicg.net/
 */


register_deactivation_hook(__FILE__, array('wp_default_plugin','plugin_desactivation'));
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
    	add_action('admin_menu', array('wp_default_plugin', 'admin_menu'));
    	
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
     * Set up the admin menu(s)
     */
    public static function admin_menu(){
    	add_options_page("Default plugin admin page", "Default plugin", 'manage_options', 'wp_default_plugin_settings', array('wp_default_plugin', "admin_settings"));
    }
    
    /**
     * 
     * The admin settings page
     */
    public static function admin_settings(){
   		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		?>
		<div class="wrap">
    		<div id="icon-options-general" class="icon32">
				<br />
			</div>
    		<h2>Default plugin</h2>
    		
    		<?php if (isset($_POST[])) :

	        	//Let's save some actions ...
	        
	            ?>
	            <div id="setting-error-settings_updated" class="updated settings-error">
					<p>
						<strong><?php _e('Settings saved.')?></strong>
					</p>
				</div>
	            
			<?php endif;?>
    		
    		
    		<form action="">
	    		<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for=""><?php _e('A label') ?></label><br />
								<em><?php _e('In case you want some options ...')?></em>
							</th>
							<td>
								<input type="text" name="" id="" value="blablabla" />
							</td>
						</tr>
					</tbody>
				</table>
			</form>
    		
    	</div>
    		
    	<?php 
    }
    
	/**
	 * 
	 * Get a plugin's specific option
	 * @param string $option_name
	 */
    public static function get_option($option_name){
    	return get_option('wp_default_plugin_'.$option_name);
    }
    
    /**
     * 
     * Set a plugin's specific option
     * @param unknown_type $option_name
     */
	public static function update_option($option_name,$option_value){
    	return update_option('wp_default_plugin_'.$option_name,$option_value);
    }
    
	/**
     * 
     * Delete a plugin's specific option
     * @param string $option_name
     */
    public static function delete_option($option_name){
    	return delete_option('wp_default_plugin_'.$option_name);
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