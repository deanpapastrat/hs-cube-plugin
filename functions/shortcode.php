<?php

// Shortcode Functions

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
  return "<iframe src='http://www.highschoolcube.com/embed/$hscube_id[0]?nobrand=true&amp;stretch=false' width='$sanitized_width' height='$sanitized_height' frameborder='0' scrolling='no' allowtransparency='true' allowfullscreen mozallowfullscreen webkitallowfullscreen seamless></iframe>";
}

function hscube_scoreboard_embed( $atts ) {

  // Attributes for shortcode
  extract( shortcode_atts(
    array(
      'url' => 'http://www.highschoolcube.com',
      'width' => '100%',
      'height' => '120px',
    ), $atts )
  );

  $sanitized_url = filter_var($url, FILTER_SANITIZE_URL);
  $sanitized_width = htmlentities($width);
  $sanitized_height = htmlentities($height);

  // Find 6 digit ID; will change RegEx if ID format changes
  preg_match('/\d{6}/', $sanitized_url, $hscube_id);

  // Get a JSON file of the event
  $hscube_json = file_get_contents('https://www.highschoolcube.com/api/v1/events/'.$hscube_id[0].'.json');

  // Decode the JSON file and save it into an array we can use without re-searching through the entire JSON file
  $hscube_decoded_json = json_decode($hscube_json, true);
  $hscube_scoreboard_data = array('home_score' => $hscube_decoded_json['home_score'], 'away_score' => $hscube_decoded_json['away_score'], 'home_name' => $hscube_decoded_json['home_name'], 'away_name' => $hscube_decoded_json['away_name'], 'home_img' => $hscube_decoded_json['home_img'], 'away_img' => $hscube_decoded_json['away_img'], 'phase_name' => $hscube_decoded_json['phase_name'], 'live' => $hscube_decoded_json['is_live']); // The last two attributes here are in preparation for an expansion of the scoreboard shortcode.

  // Check for images that don't exist, then return a div with the content we need.
  if (is_null($hscube_scoreboard_data['home_img']) or is_null($hscube_scoreboard_data['away_img'])) {
    return "<div class='hscube-scoreboard' style='width:$sanitized_width; height:$sanitized_height;'><div class='hscube-home-team hscube-team'><span class='hscube-home-team-name hscube-team-name'>".$hscube_scoreboard_data['home_name']."</span><span class='hscube-divider'>|</span><span class='hscube-home-team-score'>".$hscube_scoreboard_data['home_score']."</span></div><div class='hscube-away-team hscube-team'><span class='hscube-away-team-name hscube-team-name'>".$hscube_scoreboard_data['away_name']."</span><span class='hscube-divider'>|</span><span class='hscube-away-team-score'>".$hscube_scoreboard_data['away_score']."</span></div></div>";
  }
  else {
    return "<div class='hscube-scoreboard' style='width:$sanitized_width; height:$sanitized_height;'><div class='hscube-home-team hscube-team'><img src='".$hscube_scoreboard_data['home_img']."' class='hscube-home-team-logo hscube-team-logo'><span class='hscube-home-team-name hscube-team-name'>".$hscube_scoreboard_data['home_name']."</span><span class='hscube-divider'>|</span><span class='hscube-home-team-score'>".$hscube_scoreboard_data['home_score']."</span></div><div class='hscube-away-team hscube-team'><img src='".$hscube_scoreboard_data['away_img']."' class='hscube-away-team-logo hscube-team-logo'><span class='hscube-away-team-name hscube-team-name'>".$hscube_scoreboard_data['away_name']."</span><span class='hscube-divider'>|</span><span class='hscube-away-team-score'>".$hscube_scoreboard_data['away_score']."</span></div></div>";
  }
}


add_shortcode( 'hscube-video', 'hscube_video_embed' );
add_shortcode( 'hscube-scoreboard', 'hscube_scoreboard_embed' );


?>