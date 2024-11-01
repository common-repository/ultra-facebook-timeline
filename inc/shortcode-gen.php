<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
** Facebook Timeline Admin
*/
// Our custom post type function
function aft_create_posttype() {
  $labels = array(
    'name'                => _x( 'Facebook Timeline', 'aft' ),
    'singular_name'       => _x( 'FB Timeline', 'aft' ),
    'menu_name'           => __( 'FB Timeline', 'aft' ),
    'name_admin_bar'      => __( 'FB Timeline', 'aft' ),
    'parent_item_colon'   => __( 'Parent Timeline:', 'aft' ),
    'all_items'           => __( 'All Timelines', 'aft' ),
    'add_new_item'        => __( 'Add New Timeline', 'aft' ),
    'add_new'             => __( 'Add Timeline', 'aft' ),
    'new_item'            => __( 'New Timeline', 'aft' ),
    'edit_item'           => __( 'Edit Timeline', 'aft' ),
    'update_item'         => __( 'Update Timeline', 'aft' ),
    'view_item'           => __( 'View Timeline', 'aft' ),
    'search_items'        => __( 'Search Timeline', 'aft' ),
    'not_found'           => __( 'Not found', 'aft' ),
    'not_found_in_trash'  => __( 'Not found in Trash', 'aft' ),
  );
	register_post_type( 'facebook-timeline',
	// CPT Options
		array(
			'labels' => $labels,
			'public' => true,
      'menu_icon' => 'dashicons-facebook',
			'has_archive' => true,
			'rewrite' => array('slug' => 'facebook-timeline'),
      'supports' => array('title','custom-fields'),
		)
	);
}
add_action( 'init', 'aft_create_posttype' );

/*
Add Meta Box To facebook timeline
*/
add_action( 'add_meta_boxes', 'aft_meta_box_add' );
function aft_meta_box_add()
{
    add_meta_box( 'vsr-meta-box-id', 'Facebook Timeline Settings', 'aft_meta_box_cb', 'facebook-timeline', 'normal', 'high' );
}
function aft_meta_box_cb($post)
{
	// $post is already set, and contains an object: the WordPress post
	global $post;

	$aft_values = get_post_custom( $post->ID );
	$aft_username = isset( $aft_values['aft_meta_box_username'] ) ? esc_attr( $aft_values['aft_meta_box_username'][0] ) : "";
  $aft_limit = isset( $aft_values['aft_meta_box_limit'] ) ? esc_attr( $aft_values['aft_meta_box_limit'][0] ) : "";
  $aft_node = isset( $aft_values['aft_meta_box_node'] ) ? esc_attr( $aft_values['aft_meta_box_node'][0] ) : "";
  $aft_simpost = isset( $aft_values['aft_meta_box_simpost'] ) ? esc_attr( $aft_values['aft_meta_box_simpost'][0] ) : "";
  $aft_imgpost = isset( $aft_values['aft_meta_box_imgpost'] ) ? esc_attr( $aft_values['aft_meta_box_imgpost'][0] ) : "";
  $aft_txtcirc = isset( $aft_values['aft_meta_box_txtcirc'] ) ? esc_attr( $aft_values['aft_meta_box_txtcirc'][0] ) : "";
  $aft_video = isset( $aft_values['aft_meta_box_video'] ) ? esc_attr( $aft_values['aft_meta_box_video'][0] ) : "";
  $aft_image = isset( $aft_values['aft_meta_box_image'] ) ? esc_attr( $aft_values['aft_meta_box_image'][0] ) : "";
	// We'll use this nonce field later on when saving.
	wp_nonce_field( 'aft_meta_box_nonce', 'aft_meta_box_nonce' );
    ?>
  <div class="aftimeline-settings">
      <p>
          <label for="aft_meta_box_username">Enter facebook pages username. If its multiple please seprate them from comma.</label><br />
  				<input type="text" name="aft_meta_box_username" class="aft_text"  id="aft_meta_box_username" placeholder="photontechnologies,envato" value="<?php echo esc_html($aft_username); ?>" />
      </p>
      <p>
          <label for="aft_meta_box_limit">Post Limit</label><br />
  				<input type="number" name="aft_meta_box_limit" id="aft_meta_box_limit" class="aft_text" value="<?php echo esc_html($aft_limit); ?>" />
      </p>
      <p>
          <label for="aft_meta_box_node">Starting node value</label><br />
          <input type="text" name="aft_meta_box_node" id="aft_meta_box_node" class="aft_text" value="<?php echo esc_html($aft_node); ?>" />
      </p>
      <p>
          <label for="aft_meta_box_simpost">Number of words in simple post</label><br />
          <input type="text" name="aft_meta_box_simpost" id="aft_meta_box_simpost" class="aft_text" value="<?php echo esc_html($aft_simpost); ?>" />
      </p>
      <p>
          <label for="aft_meta_box_imgpost">Number of words in image post</label><br />
          <input type="text" name="aft_meta_box_imgpost" id="aft_meta_box_imgpost" class="aft_text" value="<?php echo esc_html($aft_imgpost); ?>" />
      </p>
  </div>
    <?php
}
add_action( 'save_post', 'aft_meta_box_save' );
function aft_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['aft_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['aft_meta_box_nonce'], 'aft_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post', $post_id ) ) return;

	 // now we can actually save the data
	 $allowed = array(
			 'a' => array( // on allow a tags
					 'href' => array() // and those anchors can only have href attribute
			 )
	 );
	 // Make sure your data is set before trying to save it
	 if( isset( $_POST['aft_meta_box_username'] ) )
			 update_post_meta( $post_id, 'aft_meta_box_username', sanitize_text_field( $_POST['aft_meta_box_username']) );

	 if( isset( $_POST['aft_meta_box_appid'] ) )
			 update_post_meta( $post_id, 'aft_meta_box_appid', sanitize_text_field( $_POST['aft_meta_box_appid']) );

 	 if( isset( $_POST['aft_meta_box_sec'] ) )
 			 update_post_meta( $post_id, 'aft_meta_box_sec', sanitize_text_field( $_POST['aft_meta_box_sec']) );

 	 if( isset( $_POST['aft_meta_box_limit'] ) )
 			 update_post_meta( $post_id, 'aft_meta_box_limit', sanitize_text_field( $_POST['aft_meta_box_limit']) );

 	 if( isset( $_POST['aft_meta_box_node'] ) )
 			 update_post_meta( $post_id, 'aft_meta_box_node', sanitize_text_field( $_POST['aft_meta_box_node']) );

 	 if( isset( $_POST['aft_meta_box_simpost'] ) )
 			 update_post_meta( $post_id, 'aft_meta_box_simpost', sanitize_text_field( $_POST['aft_meta_box_simpost']) );

   if( isset( $_POST['aft_meta_box_imgpost'] ) )
 			 update_post_meta( $post_id, 'aft_meta_box_imgpost', sanitize_text_field( $_POST['aft_meta_box_imgpost']) );

 	 if( isset( $_POST['aft_meta_box_txtcirc'] ) )
 			 update_post_meta( $post_id, 'aft_meta_box_txtcirc', sanitize_text_field( $_POST['aft_meta_box_txtcirc']) );

	 if( isset( $_POST['aft_meta_box_video'] ) )
			 update_post_meta( $post_id, 'aft_meta_box_video', sanitize_text_field( $_POST['aft_meta_box_video']) );

 	 if( isset( $_POST['aft_meta_box_image'] ) )
 			 update_post_meta( $post_id, 'aft_meta_box_image', sanitize_text_field( $_POST['aft_meta_box_image']) );

    // Styling

    if( isset( $_POST['aft_status_background'] ) )
      update_post_meta( $post_id, 'aft_status_background', sanitize_text_field( $_POST['aft_status_background']) );

    if( isset( $_POST['aft_status_btnbg'] ) )
      update_post_meta( $post_id, 'aft_status_btnbg', sanitize_text_field( $_POST['aft_status_btnbg']) );

    if( isset( $_POST['aft_status_linecolor'] ) )
      update_post_meta( $post_id, 'aft_status_linecolor', sanitize_text_field( $_POST['aft_status_linecolor']) );

    if( isset( $_POST['aft_status_btncolor'] ) )
      update_post_meta( $post_id, 'aft_status_btncolor', sanitize_text_field( $_POST['aft_status_btncolor']) );

    if( isset( $_POST['aft_status_titlecolor'] ) )
      update_post_meta( $post_id, 'aft_status_titlecolor', sanitize_text_field( $_POST['aft_status_titlecolor']) );

    if( isset( $_POST['aft_status_circolor'] ) )
      update_post_meta( $post_id, 'aft_status_circolor', sanitize_text_field( $_POST['aft_status_circolor']) );

    if( isset( $_POST['aft_status_vidcolor'] ) )
      update_post_meta( $post_id, 'aft_status_vidcolor', sanitize_text_field( $_POST['aft_status_vidcolor']) );

    if( isset( $_POST['aft_status_txtcolor'] ) )
      update_post_meta( $post_id, 'aft_status_txtcolor', sanitize_text_field( $_POST['aft_status_txtcolor']) );

    if( isset( $_POST['aft_status_shadcolor'] ) )
      update_post_meta( $post_id, 'aft_status_shadcolor', sanitize_text_field( $_POST['aft_status_shadcolor']) );

}


/*
Add Meta Box To facebook timeline
*/
add_action( 'add_meta_boxes', 'aft_shortcode_box' );
function aft_shortcode_box()
{
    add_meta_box( 'aft_shortcode', 'Shortcode', 'aft_shortcode_cb', 'facebook-timeline', 'side', 'high' );
}
function aft_shortcode_cb($post)
{
	// $post is already set, and contains an object: the WordPress post
	global $post;
  if(!empty($post->ID)){
      ?> <label>This shortcode use to display this timeline.</label> <br /><br /> <div class='aft-shortcode-box'><strong>[aft id='<?php echo esc_html( $post->ID ); ?>']</strong></div>
<?php  }
  else{
    echo esc_html("Shortcode will display here");
  }
}
/*
Add Meta Box Styling
*/
add_action( 'add_meta_boxes', 'aft_styling_box' );
function aft_styling_box()
{
    add_meta_box( 'aft_styling', 'Apperence', 'aft_styling_cb', 'facebook-timeline', 'side', 'high' );
}
function aft_styling_cb($post)
{
	// $post is already set, and contains an object: the WordPress post
	global $post;
  $aft_styleval = get_post_custom( $post->ID );
  $aft_bg = isset( $aft_styleval['aft_status_background'] ) ? esc_attr( $aft_styleval['aft_status_background'][0] ) : "";
  $aft_btnbg = isset( $aft_styleval['aft_status_btnbg'] ) ? esc_attr( $aft_styleval['aft_status_btnbg'][0] ) : "";
  $aft_linecolor = isset( $aft_styleval['aft_status_linecolor'] ) ? esc_attr( $aft_styleval['aft_status_linecolor'][0] ) : "";
  $aft_btncolor = isset( $aft_styleval['aft_status_btncolor'] ) ? esc_attr( $aft_styleval['aft_status_btncolor'][0] ) : "";
  $aft_titlecolor = isset( $aft_styleval['aft_status_titlecolor'] ) ? esc_attr( $aft_styleval['aft_status_titlecolor'][0] ) : "";
  $aft_imgcolor = isset( $aft_styleval['aft_status_circolor'] ) ? esc_attr( $aft_styleval['aft_status_circolor'][0] ) : "";
  $aft_vidcolor = isset( $aft_styleval['aft_status_vidcolor'] ) ? esc_attr( $aft_styleval['aft_status_vidcolor'][0] ) : "";
  $aft_txtcolor = isset( $aft_styleval['aft_status_txtcolor'] ) ? esc_attr( $aft_styleval['aft_status_txtcolor'][0] ) : "";
  $aft_shadcolor = isset( $aft_styleval['aft_status_shadcolor'] ) ? esc_attr( $aft_styleval['aft_status_shadcolor'][0] ) : "";
  // We'll use this nonce field later on when saving.
	wp_nonce_field( 'aft_meta_box_nonce', 'aft_meta_box_nonce' );
?>
    <label  for="aft-status-background"><strong>Post background color</strong></label> <br /><br />
    <input class="aft-color-picker"  id="aft-status-background" value="<?php echo esc_html($aft_bg); ?>" name="aft_status_background" type="text"  />
    <hr />
    <label  for="aft-status-btnbg"><strong>Button background color</strong></label> <br /><br />
    <input class="aft-color-picker"  id="aft-status-btnbg" value="<?php echo esc_html($aft_btnbg); ?>" name="aft_status_btnbg" type="text"  />
    <hr />
    <label  for="aft-status-linecolor"><strong>Line color</strong></label> <br /><br />
    <input class="aft-color-picker"  id="aft-status-linecolor" value="<?php echo esc_html($aft_linecolor); ?>" name="aft_status_linecolor" type="text"  />
    <hr />
    <label  for="aft-status-btncolor"><strong>Button Color</strong></label> <br /><br />
    <input class="aft-color-picker"  id="aft-status-btncolor" value="<?php echo esc_html($aft_btncolor); ?>" name="aft_status_btncolor" type="text"  />
    <hr />
    <label  for="aft-status-titlecolor"><strong>Text Color</strong></label> <br /><br />
    <input class="aft-color-picker"  id="aft-status-titlecolor" value="<?php echo esc_html($aft_titlecolor); ?>" name="aft_status_titlecolor" type="text"  />
    <hr />
    <label  for="aft-status-circolor"><strong>Image Circle Color</strong></label> <br /><br />
    <input class="aft-color-picker"  id="aft-status-circolor" value="<?php echo esc_html($aft_imgcolor); ?>" name="aft_status_circolor" type="text"  />
    <hr />
    <label  for="aft-status-vidcolor"><strong>Video Circle Color</strong></label> <br /><br />
    <input class="aft-color-picker"  id="aft-status-vidcolor" value="<?php echo esc_html($aft_vidcolor); ?>" name="aft_status_vidcolor" type="text"  />
    <hr />
    <label  for="aft-status-shadcolor"><strong>Starting Node Background Color</strong></label> <br /><br />
    <input class="aft-color-picker"  id="aft-status-shadcolor" value="<?php echo esc_html($aft_shadcolor); ?>" name="aft_status_shadcolor" type="text"  />

<?php
}

?>
