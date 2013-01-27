<?php

// Custom metaboxes
function editorial_post_meta_boxes_setup() {

    /* Add meta boxes on the 'add_meta_boxes' hook. */
    add_action( 'add_meta_boxes', 'editorial_add_post_meta_boxes');
    add_action( 'save_post', 'editorial_save_post_gallery_mode_meta', 10, 2 );
  //  add_action( 'wp_insert_post', 'editorial_save_post_gallery_mode_meta', 10, 2 );
}
function editorial_add_post_meta_boxes() {
    add_meta_box(
    'editorial-post-gallery-mode',			// Unique ID
     esc_html__( 'Display media as', 'editorial' ),		// Title
    'editorial_post_gallery_mode_meta_box',		// Callback function
    'post',					// Admin page (or post type)
    'side',					// Context
    'high'					// Priority
    );
}
/* Save the meta box's post metadata. */
function editorial_save_post_gallery_mode_meta( $post_id, $post ) {
    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['editorial_post_gallery_mode_nonce'] ) || !wp_verify_nonce( $_POST['editorial_post_gallery_mode_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['editorial-post-gallery-mode'] ) ? sanitize_html_class( $_POST['editorial-post-gallery-mode'] ) : '' );

    /* Get the meta key. */
    $meta_key = 'editorial_post_gallery_mode';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

	if ($new_meta_value == null || $new_meta_value == '') $new_meta_value = '0';
	//var_dump($new_meta_value);
    /* If a new meta value was added and there was no previous value, add it. */
    if ( ($new_meta_value === '0' || $new_meta_value)  && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( ($new_meta_value === '0' || $new_meta_value) && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}
/* Display the post meta box. */
    function editorial_post_gallery_mode_meta_box( $object, $box ) {
    	$value =  get_post_meta( $object->ID, 'editorial_post_gallery_mode', true );
    	$checked = true;
    	if (isset($value) && $value === '0') $checked = false;
        ?>

    	<?php wp_nonce_field( basename( __FILE__ ), 'editorial_post_gallery_mode_nonce' ); ?>


    		<label for="editorial-post-gallery-mode" class="checkbox toggle ios"">

    		<input type="checkbox" name="editorial-post-gallery-mode" id="editorial-post-gallery-mode-input" value="1" <?php if ($checked) echo 'checked="checked"'; ?>   />
			<p>
			<span class="on"  onclick="gallerySwitch(this)"><?php _e( "gallery", 'editorial' ); ?></span>
			<span class="off"  onclick="gallerySwitch(this)"><?php _e( "in-line", 'editorial' ); ?></span>
			</p>
			<a class="slide-button"></a>
    		</label>

    <?php }
