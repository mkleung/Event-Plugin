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

if (!function_exists('add_action')) {
	echo "Invalid Resource";
	exit;
}


if (class_exists('EventsPlugin')) {
	$eventsPlugin = new EventsPlugin();
	$eventsPlugin->registerScripts();
}

// Activation
register_activation_hook(__FILE__, array($eventsPlugin, 'activate'));


// Deletion
register_deactivation_hook(__FILE__, array($eventsPlugin,'deactivate'));





class EventsPlugin {

	function __construct() {
		add_action('init', array($this, 'event_custom_post_type'));

	}

	function registerScripts(){
		add_action('admin_enqueue_scripts', array($this, 'enqueue'));
	}

	function activate(){
		flush_rewrite_rules();
	}

	function deactivate(){
		flush_rewrite_rules();
	}

	function uninstall() {

	}




	function event_custom_post_type() {
		//register_post_type('event', ['public' => true, 'label' => 'Events']);
		
		register_post_type("event", array(
			'labels' => array(
				'name' => 'All Events',
				'singular_name' => 'Event',
			),
			'description' => 'Events which will be displayed',
			'public' => true,
			//'supports' => array('title', 'editor', 'custom-fields'),
			'register_meta_box_cb' => 'event_date_meta_box'

		));

		register_taxonomy_for_object_type('category','event');

		add_action( 'add_meta_boxes', 'event_date_meta_box' );
		add_action('save_post', 'event_date_save_postdata');


	}


	function enqueue() {
		wp_enqueue_style("mypluginstyle", plugins_url('/assets/style.css', __FILE__));
		wp_enqueue_script("mypluginscript", plugins_url('/assets/script.js', __FILE__));
		
	}
}

// https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
function event_date_meta_box() {

    add_meta_box(
        'date_id',
        'Dates',
        'event_date_html_callback',
        'event'
    );
}


function event_date_html_callback( $post ) {
	$startDate = get_post_meta($post->ID, '_event_start_meta_key');

?>
 <label for="event-start">Start Date</label>
 <input type="date" id="event-start" name="event-start" value="<?php echo $startDate[0];  ?>">
 <!--<label for="wporg_field">End Date</label>
 <input type="date" id="end-date" name="date-end" value="2019-05-12">-->
<?php
}


function event_date_save_postdata($post_id) {
    if (array_key_exists('event-start', $_POST)) {
    	if ($_POST['event-start'] != "") {
	        update_post_meta(
	            $post_id,
	            '_event_start_meta_key',
	            $_POST['event-start']
	        );
    	}

    }
}

