<?php
/*

*************************************************************************************

Plugin Name: High School Cube
Plugin URI: http://inviziodesign.com/plugins/hs-cube
Description: Allows you to directly integrate High School Cube into WordPress.
Version: 0.1.0
Author: Dean Papastrat
Author URI: http://inviziodesign.com
License: GPL2

*************************************************************************************

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*************************************************************************************

*/

// Initialize.php

function hscube_scoreboard_embed_styles() {
  wp_register_style('hscube_scoreboard_style', plugins_url('hs-cube/main.css'));
  wp_enqueue_style('hscube_scoreboard_style');
}

add_action('wp_enqueue_scripts', 'hscube_scoreboard_embed_styles');


// Shortcodes.php

// Video Player Embed Shortcode
function hscube_video_embed( $atts ) {

  // Attributes for shortcode
  extract( shortcode_atts(
    array(
      'url' => 'http://www.highschoolcube.com',
      'width' => '100%',
      'height' => '460px',
    ), $atts )
  );

  $sanitized_url = filter_var($url, FILTER_SANITIZE_URL);
  $sanitized_width = htmlentities($width);
  $sanitized_height = htmlentities($height);

  // Find 6 digit ID; will change RegEx if ID format changes
  preg_match('/\d{6}/', $sanitized_url, $hscube_id);

  // Return an iframe using the parameters defined.
  return "<iframe src='http://www.highschoolcube.com/embed/".$hscube_id[0]."?nobrand=true&amp;stretch=false' width='$sanitized_width' height='$sanitized_height' frameborder='0' scrolling='no' allowtransparency='true' allowfullscreen mozallowfullscreen webkitallowfullscreen seamless></iframe>";
}

function hscube_scoreboard_embed( $atts ) {

  // Attributes for shortcode
  extract( shortcode_atts(
    array(
      'url' => 'http://www.highschoolcube.com',
      'width' => '100%',
      'height' => '120px',
      'style_type' => 'hscube-scoreboard',
    ), $atts )
  );

  $sanitized_url = filter_var($url, FILTER_SANITIZE_URL);
  $sanitized_width = htmlentities($width);
  $sanitized_height = htmlentities($height);
  $sanitized_style_type = htmlentities($style_type);

  // Find 6 digit ID; will change RegEx if ID format changes
  preg_match('/\d{6}/', $sanitized_url, $hscube_id);

  // Look for cached data with the correct ID
  if ( false === ( $hscube_decoded_json = get_transient( 'hscube_json_decoded'.$hscube_id[0] ) ) ) {
    
    // It wasn't there, so regenerate the data and save the transient; get the data from HS cube
    $hscube_json = file_get_contents('https://www.highschoolcube.com/api/v1/events/'.$hscube_id[0].'.json');

  // Decode the JSON file and save it into an array we can use without re-searching through the entire JSON file
    $hscube_decoded_json = json_decode($hscube_json, true);

    // Save data for later; will unload from cache after a minute so the scoreboard isn't too out of date
    set_transient( 'hscube_json_decoded'.$hscube_id[0], $hscube_decoded_json, 60 );

  }

  $hscube_scoreboard_data = array('home_score' => $hscube_decoded_json['home_score'], 'away_score' => $hscube_decoded_json['away_score'], 'home_name' => $hscube_decoded_json['home_name'], 'away_name' => $hscube_decoded_json['away_name'], 'home_img' => $hscube_decoded_json['home_img'], 'away_img' => $hscube_decoded_json['away_img'], 'phase_name' => $hscube_decoded_json['phase_name'], 'live' => $hscube_decoded_json['is_live']); // The last two attributes here are in preparation for an expansion of the scoreboard shortcode.

  // Check for images that don't exist, then return a div with the content we need.
  if (is_null($hscube_scoreboard_data['home_img']) or is_null($hscube_scoreboard_data['away_img'])) {
    return "<div class='$sanitized_style_type' style='width:$sanitized_width; height:$sanitized_height;'><div class='hscube-home-team hscube-team'><span class='hscube-home-team-name hscube-team-name'>".$hscube_scoreboard_data['home_name']."</span><span class='hscube-divider'>|</span><span class='hscube-home-team-score'>".$hscube_scoreboard_data['home_score']."</span></div><div class='hscube-away-team hscube-team'><span class='hscube-away-team-name hscube-team-name'>".$hscube_scoreboard_data['away_name']."</span><span class='hscube-divider'>|</span><span class='hscube-away-team-score'>".$hscube_scoreboard_data['away_score']."</span></div></div>";
  }
  else {
    return "<div class='$sanitized_style_type' style='width:$sanitized_width; height:$sanitized_height;'><div class='hscube-home-team hscube-team'><img src='".$hscube_scoreboard_data['home_img']."' class='hscube-home-team-logo hscube-team-logo'><span class='hscube-home-team-name hscube-team-name'>".$hscube_scoreboard_data['home_name']."</span><span class='hscube-divider'>|</span><span class='hscube-home-team-score'>".$hscube_scoreboard_data['home_score']."</span></div><div class='hscube-away-team hscube-team'><img src='".$hscube_scoreboard_data['away_img']."' class='hscube-away-team-logo hscube-team-logo'><span class='hscube-away-team-name hscube-team-name'>".$hscube_scoreboard_data['away_name']."</span><span class='hscube-divider'>|</span><span class='hscube-away-team-score'>".$hscube_scoreboard_data['away_score']."</span></div></div>";
  }
}

// Load the shortcodes
add_shortcode( 'hscube-video', 'hscube_video_embed' );
add_shortcode( 'hscube-scoreboard', 'hscube_scoreboard_embed' );


// Widgets.php

class hscube_video_player_widget extends WP_Widget {

  // Constructor
  function hscube_video_player_widget() {

    // Set basic widget options
    $widget_ops = array('classname' => 'hscube-widget', 'description' => __('Adds a High School Cube video player to your sidebar or other widgetized area.', 'hscube_video_player_widget'));
    parent::WP_Widget(false, $name = __('HS Cube Video Player', 'hscube_video_player_widget'), $widget_ops );

  }

  // Widget form creation
  function form($instance) {

    // Check values
    if( $instance) {
         $title = esc_attr($instance['title']);
         $url = esc_attr($instance['url']);
         $width = esc_attr($instance['width']);
         $height = esc_attr($instance['height']);
    } else {
         $title = '';
         $url = '';
         $width = '100%';
         $height = '180px';
    }
    ?>

    <p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'hscube_video_player_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Video URL', 'hscube_video_player_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="url" value="<?php echo $url; ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'hscube_video_player_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>"/>
    </p>

    <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'hscube_video_player_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />

    <?php
  }

  // widget update
  function update($new_instance, $old_instance) {
      if ( !current_user_can('edit_theme_options') ) {
        return;
      }
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['url'] = strip_tags($new_instance['url']);
      $instance['width'] = strip_tags($new_instance['width']);
      $instance['height'] = strip_tags($new_instance['height']);
     return $instance;
  }

  // widget display
  function widget($args, $instance) {
    extract( $args );
    // these are the widget options
    $title = apply_filters('widget_title', $instance['title']);
    $url = $instance['url'];
    $width = $instance['width'];
    $height = $instance['height'];
    
    echo $before_widget;

    // Display the widget
    echo '<div class="widget-video-player hscube_video_player_widget_box">';

    // Check if title is set
    if ( $title ) {
      echo $before_title . $title . $after_title;
    }

    // Check if URL is set, then set it and add the shortcode
    if( is_null($url) ) {
      echo 'URL missing';
    }
    elseif ( is_null($height) and is_null($width) ) {
      echo do_shortcode('[hscube-video url="'.$url.'"]');
    }
    elseif ( is_null($height) ) {
      echo do_shortcode('[hscube-video url="'.$url.'" width="'.$width.'"]');
    }
    elseif ( is_null($width) ) {
      echo do_shortcode('[hscube-video url="'.$url.'" height="'.$height.'"]');
    }
    else {
      echo do_shortcode('[hscube-video url="'.$url.'" width="'.$width.'" height="'.$height.'"]');
    }

    echo '</div>';
    echo $after_widget;
  }
}

class hscube_scoreboard_widget extends WP_Widget {

  // Constructor
  function hscube_scoreboard_widget() {

    // Set basic widget options
    $widget_ops = array('classname' => 'hscube-widget', 'description' => __('Adds a High School Cube scoreboard to your sidebar or other widgetized area.', 'hscube_scoreboard_widget'));
    parent::WP_Widget(false, $name = __('HS Cube Scoreboard', 'hscube_scoreboard_widget'), $widget_ops );

  }

  // Widget form creation
  function form($instance) {

    // Check values
    if( $instance) {
         $title = esc_attr($instance['title']);
         $url = esc_attr($instance['url']);
         $width = esc_attr($instance['width']);
         $height = esc_attr($instance['height']);
    } else {
         $title = '';
         $url = '';
         $width = '100%';
         $height = '180px';
    }
    ?>

    <p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'hscube_scoreboard_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Video URL', 'hscube_scoreboard_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="url" value="<?php echo $url; ?>" />
    </p>

    <p>
    <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'hscube_scoreboard_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>"/>
    </p>

    <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'hscube_scoreboard_widget'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />

    <?php
  }

  // widget update
  function update($new_instance, $old_instance) {
      if ( !current_user_can('edit_theme_options') ) {
        return;
      }
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['url'] = strip_tags($new_instance['url']);
      $instance['width'] = strip_tags($new_instance['width']);
      $instance['height'] = strip_tags($new_instance['height']);
     return $instance;
  }

  // widget display
  function widget($args, $instance) {
    extract( $args );
    // these are the widget options
    $title = apply_filters('widget_title', $instance['title']);
    $url = $instance['url'];
    $width = $instance['width'];
    $height = $instance['height'];
    
    echo $before_widget;

    // Display the widget
    echo '<div class="widget-scoreboard hscube_scoreboard_widget_box">';

    // Check if title is set
    if ( $title ) {
      echo $before_title . $title . $after_title;
    }

    // Check if URL is set, then set it and add the shortcode
    if( is_null($url) ) {
      echo 'URL missing';
    }
    elseif ( is_null($height) and is_null($width) ) {
      echo do_shortcode('[hscube-scoreboard url="'.$url.'" style_type="hscube-scoreboard-widget"]');
    }
    elseif ( is_null($height) ) {
      echo do_shortcode('[hscube-scoreboard url="'.$url.'" width="'.$width.'" style_type="hscube-scoreboard-widget"]');
    }
    elseif ( is_null($width) ) {
      echo do_shortcode('[hscube-scoreboard url="'.$url.'" height="'.$height.'" style_type="hscube-scoreboard-widget"]');
    }
    else {
      echo do_shortcode('[hscube-scoreboard url="'.$url.'" width="'.$width.'" height="'.$height.'" style_type="hscube-scoreboard-widget"]');
    }

    echo '</div>';
    echo $after_widget;
  }
}
// Register widget classes

function hscube_register_widgets() {

  register_widget( 'hscube_video_player_widget' );
  register_widget( 'hscube_scoreboard_widget' );

}
// register widget
add_action('widgets_init', 'hscube_register_widgets');

// Add button to TinyMCE Editor

// Add High School Cube Button to TinyMCE editor

add_action( 'admin_head', 'hscube_add_tinymce' );

// Loads JS and permissions

function hscube_add_tinymce() {
    // Only allow people who can edit posts/pages to use this function
    global $typenow;
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
      return;
    }
    // only on Post Type: post and page
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return ;

    add_filter( 'mce_external_plugins', 'hscube_add_tinymce_plugin' );
    add_filter( 'mce_buttons', 'hscube_add_tinymce_button' );
}

// inlcude the js for tinymce

function hscube_add_tinymce_plugin( $plugin_array ) {

    $plugin_array['hscube_button'] = plugins_url( 'main.js', dirname(__FILE__) );
    // Print all plugin js path
    return $plugin_array;
}

// Add the button key for address via JS
function hscube_add_tinymce_button( $buttons ) {

    array_push( $buttons, 'hscube_tinymce_button_key' );
    // Print all buttons
    return $buttons;
}


?>