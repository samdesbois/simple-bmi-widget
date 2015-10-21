<?php
/*
Plugin Name: Simple BMI widget
Plugin URI: https://github.com/samdesbois/simple-bmi-widget
Description: A widget to calculate your BMI.
Version: 0.1
Author: Samdesbois	
Author URI: http://yev0n.fr
Text Domain: simple-bmi-widget
Domain Path: /languages
License: GLPL2
*/


//Enlever commentaire pour faire apparraître les erreurs php
//ini_set('display_errors', 'on');
//error_reporting(E_ALL);

class Simple_Bmi_Plugin
{
	public function __construct()
	{
		include_once plugin_dir_path(__FILE__).'simple-bmi.php';
		new Simple_Bmi();
	}
}

new Simple_Bmi_Plugin();
