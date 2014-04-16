<?php
 
class hscube_video_player_widget extends WP_Widget () {
      function hscube_video_player_widget() {
        $widget_ops = array( 'classname' => 'hscube_video_player_widget', 'description' => __('A widget that displays a High School Cube video player', 'hscube_video_player_widget') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'example-widget' );
        $this->WP_Widget( 'hscube-video-player-widget', __('High School Cube Video Player', 'hscube_video_player_widget'), $widget_ops, $control_ops );
    }
}     // The example widget class 
function widget($args, $instance) {

}                        // display the widget
 
function update() {}                        // update the widget
 
function form() {}                          // and of course the form for the widget options
?>