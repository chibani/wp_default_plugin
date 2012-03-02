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
	
	const LANG = 'wp_default_plugin_lang'; // Defaut lang
	const LANG_DIR = '/lang/'; // Defaut lang dirctory
	
	/**
	 * 
	 * The main 'loader'
	 */
	function init() {

		//Setup the translation
		load_plugin_textdomain(self::LANG,false, dirname(plugin_basename( __FILE__ ) ) . self::LANG);
		
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
    	/*
        if (isset($_GET['page']) && $_GET['page'] == $this->options['wp_default_plugin_settings']) {
    	wp_enqueue_script('jquery-ui-datepicker', self::get_plugin_url() . '/js/jquery.ui.datepicker.min.js', array('jquery', 'jquery-ui-core') );
		wp_enqueue_script('twitterfavs-admin-js', self::get_plugin_url() . '/js/admin.js', array('jquery-ui-datepicker') );
		
		//Smoothness style
		wp_enqueue_style('jquery.ui.smoothness', self::get_plugin_url() . '/css/smoothness/jquery-ui-1.8.17.custom.css');
		}
		*/
    	
    }
    
    /**
     * 
     * Set up the admin menu(s)
     */
    public static function admin_menu(){
    	add_options_page("Default plugin admin page", __("Default plugin",self::LANG), 'manage_options', 'wp_default_plugin_settings', array('wp_default_plugin', "admin_settings"));
    }
    /**
	*
	* Save option
	*/
	public static function admin_setting_update() {
	
	// nonce check
	if ( !wp_verify_nonce( $_POST['_wpnonce'], plugin_basename( __FILE__ ) ) ) return;
	
		$updated = false;
		if (isset($_POST['plugin_ok'])) {
			self::update_option('my_plugin_option', $_POST['my_plugin_option']);
			$updated = true;
		}
	
		if ($updated) {
			echo '<div id="message" class="updated fade">';
			echo '<p>'.__('Settings successfully updated.', self::LANG).'</p>';
			echo '</div>';
		} else {
			echo '<div id="message" class="error fade">';
			echo '<p>'.__('Unable to update settings.', self::LANG).'</p>';
			echo '</div>';
		}
	}
    /**
     * 
     * The admin settings page
     */
    public static function admin_settings(){
   		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.',self::LANG) );
		}
		?>

<div class="wrap">
  <div id="icon-options-general" class="icon32"></div>
  <h2>
    <?php _e('WordPress default plugin',self::LANG) ?>
  </h2>
  <?php if (isset($_POST['plugin_ok'])) {
			self::admin_setting_update(); // update setting
			//Let's save some options ...
			} ?>
  <form action="" method="post">
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row"> <label for="my_plugin_option">
              <?php _e('A label',self::LANG) ?>
            </label>
            <br />
            <em>
            <?php _e('In case you want some options ...',self::LANG)?>
            </em> </th>
          <td><input type="text" name="my_plugin_option" id="my_plugin_option" value="<?php esc_attr_e( self::get_option('my_plugin_option' )); ?>" /></td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input type="submit" class="button-primary" name="plugin_ok" value="<?php esc_attr_e('Save settings',self::LANG) ?>" />
    </p>
    <?php // Use nonce for verification
wp_nonce_field( plugin_basename( __FILE__ ), '_wpnonce' );?>
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
			'display'   => __('Every 10 minutes',self::LANG), 
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
