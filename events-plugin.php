<?php
/**
*  @package eventsplugin
*/

/*
Plugin Name: Events Plugin
Plugin URI: 
Description:<h1>WordPress Developer Test</h1> Please create a simple plugin that registers a custom post (ex. Events), a corresponding category (ex. Event Types), registers a metabox with a couple fields (ex. Start Date and End Date). The plugin should also create a shortcode that can be used to display a listing of all the posts of the created post type.
Vesion: 1.0.0
Author: Michael Leung
Author URI: https://mikeleung.ca
License: GPLV2 or later
*/


// if (!defined('ABSPATH')) {
// 	die("Invalid Resource");
// }


// or

// defined('ABSPATH') or die();

if (!function_exists('add_action')) {
	echo "Invalid Resource";
	exit;
}



class EventsPlugin {

	function __construct() {
		add_action('init', array($this, 'custom_post_type'));
	}

	function activate(){
		flush_rewrite_rules();
	}

	function deactivate(){
		flush_rewrite_rules();
	}

	function uninstall() {

	}

	function custom_post_type() {
		register_post_type('event', ['public' => true, 'label' => 'Events']);
	}

	
}

if (class_exists('EventsPlugin')) {
	$eventsPlugin = new EventsPlugin();
}

// Activation
register_activation_hook(__FILE__, array($eventsPlugin, 'activate'));


// Deletion
register_deactivation_hook(__FILE__, array($eventsPlugin,'deactivate'));
