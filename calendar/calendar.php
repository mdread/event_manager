<?php
function em_calendar($atts, $content = null) {
    extract(shortcode_atts(array(
        "lang" => 'it'
    ), $atts));


    return '<div id="calendar" data-lang="'.$lang.'"></div>';
}
add_shortcode("em_calendar", "em_calendar");

function event_calendar_resources() {
  // styles
  wp_enqueue_style( 'fullcalendar-style', plugin_dir_url( __FILE__ ) . 'fullcalendar-2.4.0/fullcalendar.css', array());

  // scripts
	wp_enqueue_script( 'moment-js' , plugin_dir_url( __FILE__ ) . 'fullcalendar-2.4.0/lib/moment.min.js' , array());
  wp_enqueue_script( 'fullcalendar-js' , plugin_dir_url( __FILE__ ) . 'fullcalendar-2.4.0/fullcalendar.min.js' , array('jquery', 'moment-js'));
  wp_enqueue_script( 'fullcalendar-lang_it' , plugin_dir_url( __FILE__ ) . 'fullcalendar-2.4.0/lang/it.js' , array('jquery', 'fullcalendar-js'));
  wp_enqueue_script( 'event_manager-calendar' , plugin_dir_url( __FILE__ ) . 'calendar.js' , array('jquery', 'fullcalendar-js'));
  wp_localize_script( 'event_manager-calendar', 'EventManager', array(
    'url' => admin_url( 'admin-ajax.php' ),
    'action' => 'em_get_events'
  ));
}
add_action( 'wp_enqueue_scripts', 'event_calendar_resources' );

function calendar_get_events_ajax() {
  header( "Content-Type: application/json" );

  $data = array();

  $query_args = array( 'post_type' => 'event' );
  $events = new WP_Query( $query_args );
  if( $events -> have_posts() ) {
    while( $events -> have_posts() ) {
      $events -> the_post();

      $data[] = array(
        'title' => get_the_title(),
        'allDay' => false,
        'start' => date("c", get_post_custom()['event_date'][0]),
        'end' => date("c", get_post_custom()['event_date'][0]),
        'url' => get_permalink()
      );

    }
  }

  echo json_encode($data);

  exit;
}

add_action( 'wp_ajax_em_get_events', 'calendar_get_events_ajax' );
add_action( 'wp_ajax_nopriv_em_get_events', 'calendar_get_events_ajax' );
?>
