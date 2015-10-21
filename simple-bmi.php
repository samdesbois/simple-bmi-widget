<?php
include_once plugin_dir_path(__FILE__).'simple-bmi-widget.php';

class Simple_Bmi
{
	public function __construct()
	{
		add_action( 'widgets_init', function() {register_widget('Simple_Bmi_Widget');});
		
		add_action( 'plugins_loaded', function() {load_plugin_textdomain('simple-bmi-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');});
	}
}
