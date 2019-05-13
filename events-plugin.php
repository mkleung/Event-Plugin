<?php
/**
*  @package eventsplugin
*/

/*
Plugin Name: Events Plugin
Plugin URI: 
Description: This plugin creates a custom post (ex. Events), a corresponding category (ex. Event Types), registers a metabox with a couple fields (ex. Start Date and End Date). The plugin should also create a shortcode that can be used to display a listing of all the posts of the created post type.
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
		register_post_type("event", array(
			'labels' => array(
				'name' => 'Events',
				'singular_name' => 'Event',
				'menu_name' => __( 'Events' ),
				'all_items' => __( 'All Events'),
				'add_new_item' => __('Add New Event'),
				'new_item'  => __( 'New Event'),
				'edit_item' => __( 'Edit Event' ),
				'view_item' => __( 'View Event' ),
				'not_found'          => __( 'No events found.' ),
				'not_found_in_trash' => __( 'No events found in Trash.' )

			),
			'menu_icon' => 'dashicons-calendar-alt',
			'description' => 'Events which will be displayed',
			'public' => true,
			'register_meta_box_cb' => 'event_date_meta_box',
			'capability_type'    => 'post',
			'supports'           => array( 'title', 'editor', 'thumbnail' )

		));

		register_taxonomy_for_object_type('category','event');

		add_action( 'add_meta_boxes', 'event_date_meta_box' );
		add_action('save_post', 'event_date_save_postdata');
	}


	function enqueue() {
		wp_enqueue_style("eventpluginstyle", plugins_url('/assets/style.css', __FILE__));
		wp_enqueue_script("eventpluginscript",
		plugins_url('/assets/script.js', __FILE__),
		[ 'wp-editor', 'wp-i18n', 'wp-element', 'wp-compose', 'wp-components' ]);
		
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


	$startDate = get_post_meta($post->ID, '_event_start_meta_key')[0];
	$endDate = get_post_meta($post->ID, '_event_end_meta_key')[0];

	if (empty($startDate)) {
		$formattedStart = "YYYY-MM-DD";
	}
	else {
		$startDisplaydate = new DateTime("@$startDate");
		$formattedStart = $startDisplaydate->format('Y-m-d');
	}

	if (empty($endDate)) {
		$formattedEnd = "YYYY-MM-DD";
	}
	else {
		$endDisplaydate = new DateTime("@$endDate");
		$formattedEnd = $endDisplaydate->format('Y-m-d');
	}


?>
 <label for="event-start">Start Date</label>
 <input type="date" id="event-start" name="event-start" value="<?php echo $formattedStart;  ?>">

 <label for="event-start">End Date</label>
 <input type="date" id="event-end" name="event-end" value="<?php echo $formattedEnd; ?>">

<?php
}

// Save date into database
function event_date_save_postdata($post_id) {


    if (array_key_exists('event-start', $_POST)) {
    	if ($_POST['event-start'] != "") {
	        update_post_meta(
	            $post_id,
	            '_event_start_meta_key',
	            strtotime($_POST['event-start'])
	        );
    	}
    }

    if (array_key_exists('event-end', $_POST)) {
    	if ($_POST['event-end'] != "") {
	        update_post_meta(
	            $post_id,
	            '_event_end_meta_key',
	            strtotime($_POST['event-end'])
	        );
    	}
    }

}


/*
|--------------------------------------------------------------------------
| Enable Shortcode Feature
|--------------------------------------------------------------------------
|*/

add_shortcode( 'list-events', 'events_plugin_shortcode' );
function events_plugin_shortcode( $atts ) {
    ob_start();
    $query = new WP_Query( array(
        'post_type' => 'event',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'title',

    ) );
    if ( $query->have_posts() ) { ?>

		<table style="width:100%">
			<tr>
				<th>Title</th>
				<th>Start Date</th> 
				<th>End Date</th>
			</tr>
		  
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<?php 

				$startDate = get_post_meta(get_the_id(), '_event_start_meta_key', true);
				$endDate = get_post_meta(get_the_id(), '_event_end_meta_key', true);


				if (empty($startDate)) {
					$formattedStart = "";
				}
				else {
					$startDisplaydate = new DateTime("@$startDate");
					$formattedStart = $startDisplaydate->format('Y-m-d');
				}


				if (empty($endDate)) {
					$formattedEnd = "";
				}
				else {
					$endDisplaydate = new DateTime("@$endDate");
					$formattedEnd = $endDisplaydate->format('Y-m-d');
				}


				?>
				<tr>
			    	<td><?php the_title(); ?></td>
			    	<td><?php echo $formattedStart; ?></td> 
			    	<td><?php echo $formattedEnd; ?></td>
			  	</tr>
			<?php endwhile; wp_reset_postdata(); ?>
		</table>

	<?php 
		$myvariable = ob_get_clean();
    		return $myvariable;
    	}
}
