<?php
/*
Plugin Name: Front-end editing via the REST API
Description: Extend cookie authentication to the REST API to allow front-end editing.
Version:     0.1
Author:      Morten Rand-Hendriksen
Author URI:  http://lynda.com/mor10
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: restedit
*/

function restedit_scripts() {
	if( !is_admin() && is_single() ) {

		if ( is_user_logged_in() && current_user_can( 'edit_others_posts' ) ) {
			wp_enqueue_style( 'restedit_style',plugin_dir_url(__FILE__) . 'style.css' );
			wp_enqueue_script( 'restedit_script', plugin_dir_url(__FILE__) . 'js/restedit.js', array(), '0.1', true );
			wp_localize_script( 'restedit_script', 'postdata', array(
				'rest_url' => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'current_ID' => get_the_ID()
			));
		}
	}
}
add_action( 'wp_enqueue_scripts', 'restedit_scripts' );
