<?php
include_once 'custom-fields/add-field.php';

add_action( 'after_setup_theme', function() {

	/* Below are 3 activated examples */

	add_field( 'subheader', __( 'Subheader', 'your-lang-domain' ), [ 'page', 'post' ], 'normal', 'textarea', 'page-widewidth' );

	// Add custom field 'customer'
	add_field( 'customer', __( 'Customer', 'your-lang-domain' ), 'project', 'after_title' );

	// Add custom field 'automation_object'
	add_field( 'integration_objects', __( 'Integration object', 'your-lang-domain' ), 'project', 'side', 'multi_input' );
});

// After title context for fields
add_action( 'edit_form_after_title', 'dts_show_metabox_after_title' );
function dts_show_metabox_after_title( $post ) {
	do_meta_boxes( get_current_screen(), 'after_title', $post );
}
?>
