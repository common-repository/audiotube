<?php
/*
Plugin Name: AudioTube
Plugin URI: http://davidangel.net/photofade-wordpress-plugin/
Description: Embed YouTube videos as mere audio players on posts and pages.
Version: 0.0.3
Author: David Angel
Author URI: http://davidangel.net
License: GPL2
*/

/*  Copyright 2011  David Angel  (email : david@davidangel.net )

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
*/


function youtube_id_from_url($url) {
    $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if (false !== $result) {
        return $matches[1];
    }
    return false;
}


function audiotube_output($atts, $content = null) {
	extract(shortcode_atts(array(
		"url" => null,
		"caption" => null
		), $atts));
	
	$video_id = youtube_id_from_url($url);
	$out .= '<div class="audiotube_container" style="margin: 0 auto 20px auto;font-family:Helvetica,Verdana,sans-serif;font-size:small;">';
	if($caption != null) {
		
		$out .= '<div class="audiotube_caption" style="background-color:#212121;color:white;padding:3px 7px;width:286px;height:48px;overflow:hidden; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">'.$caption.'</div>'; }
	
	$out .= '
		<div class="audiotube" style="width:100%; margin-top:-36px;">
		<object height="25" style="width:100%;"><param name="movie" value="http://www.youtube.com/v/'.$video_id.'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><param name="color" value="white"></param></param><embed src="http://www.youtube.com/v/'.$video_id.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="25"></embed></object>
		</div></div>
	';
	
	return $out;
}
add_shortcode("audiotube", "audiotube_output");


// Button

function add_youtube_button() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
   if ( get_user_option('rich_editing') == 'true') {
     add_filter('mce_external_plugins', 'add_youtube_tinymce_plugin');
     add_filter('mce_buttons', 'register_youtube_button');
   }
}

add_action('init', 'add_youtube_button');

function register_youtube_button($buttons) {
   array_push($buttons, "|", "brettsyoutube");
   return $buttons;
}

function add_youtube_tinymce_plugin($plugin_array) {
   $plugin_array['brettsyoutube'] = get_bloginfo('wpurl').'/wp-content/plugins/audiotube/editor-plugin.js';
   return $plugin_array;
}

function audiotube_refresh_mce($ver) {
  $ver += 3;
  return $ver;
}

add_filter( 'tiny_mce_version', 'audiotube_refresh_mce');

?>