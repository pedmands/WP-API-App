
// Create relationship selector between posts and podcasts
add_action( 'admin_init', 'add_meta_boxes' );
function add_meta_boxes() {
    add_meta_box( 'some_metabox', 'Related Podcasts', 'podcasts_field', 'post' );
}

function podcasts_field() {
    global $post;
    $selected_podcasts = get_post_meta( $post->ID, '_podcasts', true );
    $all_podcasts = get_posts( array(
        'post_type' => 'podcast',
        'numberposts' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ) );
    ?>
    <input type="hidden" name="podcasts_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
    <table class="form-table">
    <tr valign="top"><th scope="row">
    <label for="podcasts">Podcasts</label></th>
    <td><select multiple name="podcasts">
    <?php foreach ( $all_podcasts as $podcast ) : ?>
        <option value="<?php echo $podcast->ID; ?>"<?php echo (in_array( $podcast->ID, $selected_podcasts )) ? ' selected="selected"' : ''; ?>><?php echo $podcast->post_title; ?></option>
    <?php endforeach; ?>
    </select></td></tr>
    </table>
    <?php
}

add_action( 'save_post', 'save_podcast_field' );
function save_podcast_field( $post_id ) {

    // only run this for posts
    if ( 'post' != get_post_type( $post_id ) )
        return $post_id;        

    // verify nonce
    if ( empty( $_POST['podcast_nonce'] ) || !wp_verify_nonce( $_POST['podcast_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;

    // check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;

    // save
    update_post_meta( $post_id, '_podcasts', array_map( 'intval', $_POST['podcasts'] ) );

}