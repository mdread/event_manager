<?php
/**
 * Plugin Name: Event Manager
 * Plugin URI: https://github.com/mdread/event_manager
 * Description: Manage event-type posts and show them in a calendar.
 * Version: 1.0.0
 * Author: Daniel Camarda
 * Author URI: https://github.com/mdread
 * License: MIT
 */


/*
CUSTOM TYPE - EVENT
*/

include plugin_dir_path( __FILE__ ) . 'event_list/event_list.php';
include plugin_dir_path( __FILE__ ) . 'calendar/calendar.php';

function post_type_event() {
  $labels = array(
    'name'               => _x( 'Events', 'Eventi' ),
    'singular_name'      => _x( 'Event', 'Evento' ),
    'add_new'            => _x( 'Aggiungi nuovo', 'event' ),
    'add_new_item'       => __( 'Aggiungi nuovo evento' ),
    'edit_item'          => __( 'Modifica evento' ),
    'new_item'           => __( 'Nuovo evento' ),
    'all_items'          => __( 'Tutti gli eventi' ),
    'view_item'          => __( 'Vedi evento' ),
    'search_items'       => __( 'Cerca eventi' ),
    'not_found'          => __( 'Nessun evento presente' ),
    'not_found_in_trash' => __( 'Nessun evento presente in Trash' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Eventi'
  );
  $args = array(
    'labels'            => $labels,
    'description'       => 'Informazioni riguardo gli eventi',
    'public'            => true,
    'menu_position'     => 5,
    'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
    'has_archive'       => true,
		'rewrite'           => array('slug' => 'events'),
		'capability_type'   => 'post'
  );
  register_post_type( 'event', $args );
	flush_rewrite_rules( false );
}
add_action( 'init', 'post_type_event' );


function admin_init(){
  add_meta_box("event_date-meta", "Data evento", "event_date", "event", "side", "low");
}
add_action("admin_init", "admin_init");

function event_date(){
  global $post;
  $custom = get_post_custom($post->ID);
  $event_date = $custom["event_date"][0];

  if ($event_date == null) {
    $event_date = time();
  }

  $event_date_str = date("d/m/Y", $event_date);
  $event_hour = intval(date("G", $event_date), 10);
  $event_minute = intval(date("i", $event_date), 10);
  ?>

  <div class="event_manager-meta">
    <ul>
      <li>
        <label>Data</label>
        <input id="event_date" name="event_date" value="<?php echo $event_date_str; ?>" />
      </li>
      <li>
        <label>Ora </label>
        <select name="event_time_hour">
          <?php
            for ($x = 0; $x <= 24; $x++) {
              $selected = ($x == $event_hour) ? 'selected="selected"' : '';
              echo "<option value=\"$x\" $selected>" . str_pad($x, 2, "0", STR_PAD_LEFT) . "</option>";
            }
          ?>
        </select>
        <span>:</span>
        <select name="event_time_minute">
          <?php
            for ($x = 0; $x < 60; $x += 15) {
              $selected = ($x == $event_minute) ? 'selected="selected"' : '';
              echo "<option value=\"$x\" $selected>" . str_pad($x, 2, "0", STR_PAD_LEFT) . "</option>";
            }
          ?>
        </select>
      </li>
    </ul>
  </div>
  <?php
}

function save_details(){
  global $post;

//  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
//    return $post;

  if(!isset($_POST["event_date"])):
    return $post;
  endif;

  $date = $_POST["event_date"];
  $time = str_pad($_POST["event_time_hour"], 2, "0", STR_PAD_LEFT) . ':' . str_pad($_POST["event_time_minute"], 2, "0", STR_PAD_LEFT);
  $fulldate = date_timestamp_get(date_create_from_format('d/m/Y G:i', $date . ' ' . $time));

  update_post_meta($post->ID, "event_date", $fulldate);
}
add_action('save_post', 'save_details');

function event_edit_columns($columns){
  $columns = array(
		"cb"          => '<input type="checkbox" />',
		"title"       => "Titolo",
    "event_date"  => "Data evento"
  );

  return $columns;
}
function event_custom_columns($column){
  global $post;

  switch ($column) {
    case "event_date":
			$custom = get_post_custom();
			echo date("d/m/Y G:i", $custom["event_date"][0]);
			break;
  }
}
add_action("manage_posts_custom_column",  "event_custom_columns");
add_filter("manage_edit-event_columns", "event_edit_columns");


// Datepicker for event type (admin interface)

function events_styles() {
    global $post_type;

    if( 'event' != $post_type )
        return;

    wp_enqueue_style( 'em_datepicker', plugin_dir_url( __FILE__ ) . '/jquery-ui/jquery-ui.min.css', array(), '1.11.4' );
    wp_enqueue_style( 'event_custom_css', plugin_dir_url( __FILE__ ) . '/event_manager-admin.css', array());
}

function events_scripts() {
    global $post_type;

    if( 'event' != $post_type )
        return;

    wp_enqueue_script('jquery-ui', plugin_dir_url( __FILE__ ) . 'jquery-ui/jquery-ui.min.js', array('jquery'), '1.11.4');
    wp_enqueue_script('jquery-ui_datepicker-it', plugin_dir_url( __FILE__ ) . 'jquery-ui/datepicker-it.js', array('jquery', 'jquery-ui'));
    wp_enqueue_script('event_custom_script', plugin_dir_url( __FILE__ ) . 'event_manager-admin.js', array('jquery'));
}

add_action( 'admin_print_styles-post.php', 'events_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'events_styles', 1000 );

add_action( 'admin_print_scripts-post.php', 'events_scripts', 1000 );
add_action( 'admin_print_scripts-post-new.php', 'events_scripts', 1000 );

?>
