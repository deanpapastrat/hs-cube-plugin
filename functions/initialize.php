<?php

/* ----------  Front-End Stylesheets  ---------- */

function hscube_scoreboard_embed_styles() {
  wp_register_style('hscube_scoreboard_style', plugins_url('hs-cube/css/scoreboard_embed.css'));
  wp_enqueue_style('hscube_scoreboard_style');
}

/* ----------  Admin Stylesheets  ---------- */

/* ----------  Enque Stylesheets  ---------- */

add_action('wp_enqueue_scripts', 'hscube_scoreboard_embed_styles');

?>