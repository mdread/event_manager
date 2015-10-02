<?php
function event_list($atts, $content = null) {
    extract(shortcode_atts(array(
        "perpage" => 5
    ), $atts));

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

    $query_args = array(
      'post_type' => 'event',
      'order'     => 'DESC',
      'orderby'   => 'meta_value_num',
      'meta_key'  => 'event_date',
      'posts_per_page' => intval($perpage, 10),
      'paged' => $paged
    );
    $events = new WP_Query( $query_args );

    ob_start();

    if( $events -> have_posts() ) {
      while( $events -> have_posts() ) {
        $events -> the_post();
        include plugin_dir_path( __FILE__ ) . 'content.php';
      }

      ?>
      <div class="nav-previous alignleft"><?php next_posts_link( 'Eventi precedenti', $events -> max_num_pages ); ?></div>
      <div class="nav-next alignright"><?php previous_posts_link( 'Nuovi eventi' ); ?></div>
      <?php
      wp_reset_postdata();
    } else {
      echo 'Nessun evento presente';
    }

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode("event_list", "event_list");

?>
