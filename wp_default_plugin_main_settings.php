<?php
/**
 * WP Default plugin's main settings
 */

add_action('admin_menu', array('wp_default_plugin_main_settings', 'admin_menu_setup'));
add_action('admin_init', array('wp_default_plugin_main_settings', 'admin_settings_setup') );

class wp_default_plugin_main_settings{
	/**
	 * 
	 * Sets up the setting menu
	 */
	public static function admin_menu_setup(){
		add_options_page(__('Default plugin settings','wp_default_plugin'), __('Default plugin','wp_default_plugin'), 'manage_options', 'wp_default_plugin_main_settings', array(get_class(),'admin_settings_page'));
	}
	
	/**
     * 
     * The admin settings' settings
     */
    public static function admin_settings_setup(){
   		//Create a setting section
    	add_settings_section('main_settings_section', __('Default plugin settings','wp_default_plugin'), array(get_class(),'main_section_callback'), 'wp_default_settings');
    	
    	//Register the settings
    	register_setting( get_class(), get_class() );
    	
    	//Create an option (field)
    	add_settings_field('first_option', __('First option','wp_default_plugin'), array(get_class(),'first_option_callback'), 'wp_default_settings', 'main_settings_section');
    	//And a second one :)
    	add_settings_field('second_option', __('Second option','wp_default_plugin'), array(get_class(),'second_option_callback'), 'wp_default_settings', 'main_settings_section');
    }
	
    /**
     * 
     * Echoes the first options section
     */
	public static function main_section_callback(){
		echo '<p>'.__('This is our main section','wp_default_plugin').'</p>';
	}
	
	/**
	 * 
	 * Echoes our first option
	 */
	public static function first_option_callback(){
		echo '<input name="'.get_class().'[first_option]" id="" type="checkbox" value="1" class="code" ' . checked( 1, self::get_option('first_option'), false ) . ' />';
	}
	
	public static function second_option_callback(){
		echo '<input name="'.get_class().'[second_option]" id="" type="text" value="'.self::get_option('second_option'). '" />';
	}
	
	/**
	 * 
	 * The actual setting page
	 */
	public static function admin_settings_page(){
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32">
				<br />
			</div>
			<h2><?php _e('WordPress default plugin settings','wp_default_plugin') ?></h2>
			<form action="options.php" method="post">
				<?php settings_fields(get_class()); ?>
				<?php do_settings_sections('wp_default_settings'); ?>
				
				<input name="submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save settings','wp_default_plugin'); ?>" />
			</form>
		</div>
		<?php 
	}
	
	/**
	 * 
	 * Get an option for this plugin
	 * @param string $option_name
	 */
	public static function get_option($option_name){
		$options = get_option(get_class());
		if(isset($options[$option_name]))
			return esc_attr($options[$option_name]);
		return false;
	}
}