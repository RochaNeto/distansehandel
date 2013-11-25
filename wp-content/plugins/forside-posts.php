<?php
/**
 * Plugin Name: Forside Posts
 * Plugin URI: http://distansehandel.no
 * Description: Shorcode to show a list of posts (spoiler style)
 * Version: 0.1
 * Author: Elio Cro
 * Author URI: http://frontkom.no
 * License: MIT
 */

add_shortcode( 'forside-posts', 'forside_post_list' );
function forside_post_list ( $atts ) {
    ob_start();

    // define attributes and their defaults
    extract( shortcode_atts( array (
        'type' => 'post',
        'order' => 'DESC',
        'orderby' => 'date',
        'count' => -1,
        'category' => '',
    ), $atts ) );

    // define query parameters based on attributes
    $options = array(
        'post_type' => $type,
        'order' => $order,
        'orderby' => $orderby,
        'posts_per_page' => $count,
        'category_name' => $category,
    );
    $query = new WP_Query( $options );
    // run the loop based on the query
    if ($query->have_posts() ) {
      while ($query->have_posts()) : $query->the_post();
      ?>
        <div>
          <div class="su-spoiler-style-2 su-spoiler-open">
            <div class="su-spoiler-title">
              <i class="moon-arrow-right-2 spoiler-button"></i>
              <i class="moon-arrow-down spoiler-button spoiler-active"></i>
              <a href="<?php echo get_permalink(get_the_ID()); ?>"><?php the_title(); ?></a>
            </div>
            <div class="su-spoiler-content" style="display:block"><?php the_excerpt(); ?></div>
          </div>
        </div>
      <?php
      endwhile;
      $myvariable = ob_get_clean();
      return $myvariable;
    }
}

?>
