<?php

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

    $plugin_array['hscube_button'] = plugins_url( 'js/hscube_tinymce_button.js', dirname(__FILE__) );
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