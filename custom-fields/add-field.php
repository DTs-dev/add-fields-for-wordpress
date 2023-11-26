<?php
function add_field( $cfield_name, $cfield_title, $post_type = NULL, $context = 'side', $input = 'input', $template = FALSE ) {
	add_action( 'init', function() use( $cfield_name, $cfield_title, $post_type, $context, $input, $template ) {
		// Frontend for meta box field"
		add_action( 'add_meta_boxes', function() use( $cfield_name, $cfield_title, $post_type, $context, $input, $template ) {
			if( $template ) {
				if( get_page_template_slug( $_GET['post'] ) != $template . '.php' ) {
					return;
				}
			}
			add_meta_box( $cfield_name . '_field', $cfield_title, function( $post ) use( $cfield_name, $input ) {
				global $wpdb;
				$meta_key = '_' . $cfield_name;
				$meta_values = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s AND post_id = %d", $meta_key, $post->ID ) );
				$meta_values = implode( ',', $meta_values );
				$meta_all_values = array_unique ( $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key ) ) );

				if( $input === 'textarea' ) { ?>
					<p><label><textarea name="<?php echo $meta_key; ?>" style="width:100%"><?php echo $meta_values; ?></textarea></label></p>
				<?php } else {
					if( $input === 'multi_input' ) {
						$multiple = 'multiple';
					} else {
						$multiple = '';
					} ?>
					<p><label><input type="text" name="<?php echo $meta_key; ?>" value="<?php echo $meta_values; ?>" list="<?php echo $cfield_name; ?>" <?php echo $multiple; ?> class="<?php echo $multiple; ?>" style="width:100%" /></label></p>
					<datalist id="<?php echo $cfield_name; ?>">
					<?php foreach( $meta_all_values as $meta_all_value ) { ?>
						<option><?php echo $meta_all_value; ?></option>
					<?php } ?>
					</datalist>
				<?php } ?>
				<input type="hidden" name="<?php echo $cfield_name; ?>_field_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
			<?php }, $post_type, $context, 'high' );
		}, 1 );

		// Saving data when updating a post
		add_action( 'save_post', function( $post_id ) use( $cfield_name, $input ) {
			if( !wp_verify_nonce( $_POST[$cfield_name.'_field_nonce'], __FILE__ ) || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
				return false;
			}
			$meta_key = '_' . $cfield_name;
			if( empty( $_POST[$meta_key] ) ) {
				delete_post_meta( $post_id, $meta_key ); // remove the field if the value is empty
			} else {
				if( $input === 'multi_input' ) {
					global $wpdb;
					$current_meta_values = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s AND post_id = %d", $meta_key, $post_id ) );
					$meta_values = explode( ',', $_POST[$meta_key] );
					if( $meta_values != $current_meta_values ) {
						delete_post_meta( $post_id, $meta_key );
						foreach( $meta_values as $meta_value ) {
							add_post_meta( $post_id, $meta_key, $meta_value );
						}
					}
				} else {
					update_post_meta( $post_id, $meta_key, $_POST[$meta_key] );
				}
			}
			return $post_id;
		}, 0 );
	} );
}
?>
