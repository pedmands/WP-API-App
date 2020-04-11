<?php
/**
 * Plugin Name:       REST API Demo Plugin
 * Plugin URI:        https://linkedin.com/learning
 * Description:       Plugin to add fields to the REST API
 * Version:           1.0.0
 * Requires at least: 4.7
 * Author:            Morten Rand-Hendriksen
 * Author URI:        https://lnkd.in/mor10
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rest-demo-plugin
 */

/**
 * Add new fields to the REST API response.
 * @link https://developer.wordpress.org/reference/functions/register_rest_field/
 */
add_action( 'rest_api_init', 'RDP_add_new_fields' );

function RDP_add_new_fields() {
    register_rest_field( 
        'post',     // Object(s) the filed is being registered to
        'catlinks', // Attribute (field) name
         array(     // Array of arguments
            'get_callback'    => 'RDP_get_category_links', // Retrieves the field value.
            'update_callback' => null,                 // Updates the field value.
            'schema'          => null,                 // Creates schema for the field.
        ) 
    );
    register_rest_field(
        'post',
        'prevPostID',
        array(
            'get_callback' => 'RDP_get_prev_postID'
        )
        );
    register_rest_field(
        'post',
        'prevPostTitle',
        array(
            'get_callback' => 'RDP_get_prev_post_title'
        )
        );
    register_rest_field(
        'post',
        'prevPostLink',
        array(
            'get_callback' => 'RDP_get_prev_post_link'
        )
        );
    register_rest_field(
        'post',
        'relatedPodcastID',
        array(
            'get_callback' => 'RDP_get_related_podcastID'
        )
        );
    register_rest_field(
        'post',
        'relatedPodcastTitle',
        array(
            'get_callback' => 'RDP_get_related_podcast_title'
        )
        );
    register_rest_field(
        'post',
        'relatedPodcastLink',
        array(
            'get_callback' => 'RDP_get_related_podcast_link'
        )
        );
}

/**
 * Get all the categories for the current post.
 * Make links for each category and string them together.
 * Return string of category links.
 */
function RDP_get_category_links() {
    $categories = get_the_category();
    $separator = ', ';
    $output = '';
    if ( ! empty( $categories ) ) {
        foreach( $categories as $category ) {
            $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
        }
        $output = trim( $output, $separator );
    }
    return $output;
}

function RDP_get_prev_postID() {
    $postID = get_previous_post()->ID;

    return $postID;
}

function RDP_get_prev_post_title() {
    $previousPost = get_previous_post();

    return get_the_title($previousPost->ID);
}

function RDP_get_prev_post_link() {
    $previousPost = get_previous_post();

    return get_permalink($previousPost->ID);
}

function RDP_get_related_podcastID() {
    $ids = get_field('podcast', false, false);

    $query = new WP_Query(array(
        'post_type'      	=> 'podcast',
        'posts_per_page'	=> 1,
        'post__in'			=> $ids,
        'post_status'		=> 'any',
        'orderby'        	=> 'post__in',
    ));

    return $query->post->ID;

    $query->reset_postdata();

}

function RDP_get_related_podcast_title() {
    $ids = get_field('podcast', false, false);

    $query = new WP_Query(array(
        'post_type'      	=> 'podcast',
        'posts_per_page'	=> 1,
        'post__in'			=> $ids,
        'post_status'		=> 'any',
        'orderby'        	=> 'post__in',
    ));

    return $query->post->post_title;

    $query->reset_postdata();
}

function RDP_get_related_podcast_link() {
    $ids = get_field('podcast', false, false);

    $query = new WP_Query(array(
        'post_type'      	=> 'podcast',
        'posts_per_page'	=> 1,
        'post__in'			=> $ids,
        'post_status'		=> 'any',
        'orderby'        	=> 'post__in',
    ));

    return get_permalink($query->post->ID);

    $query->reset_postdata();
}