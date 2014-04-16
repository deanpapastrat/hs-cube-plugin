<?php

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

// Register widget classes

function hscube_register_widgets() {

  register_widget( 'hscube_video_player_widget' );

}
// register widget
add_action('widgets_init', 'hscube_register_widgets');

?>