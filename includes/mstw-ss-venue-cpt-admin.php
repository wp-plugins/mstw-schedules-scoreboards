<?php
/*----------------------------------------------------------------------------
 * mstw-ss-venue-cpt-admin.php
 *	This portion of the MSTW Schedules & Scoreboards Plugin admin handles the
 *		mstw_ss_schedule custom post type.
 *	It is loaded conditioned on is_admin() in mstw-ss-admin.php 
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.

 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *--------------------------------------------------------------------------*/
 
// ----------------------------------------------------------------
// Add the meta box for the venues custom post type
//
add_action( 'add_meta_boxes_mstw_ss_venue', 'mstw_ss_venue_metaboxes' );

function mstw_ss_venue_metaboxes( ) {		
	add_meta_box('mstw-ss-venue-meta',  __( 'Venue Data', 'mstw-schedules-scoreboards' ), 'mstw_ss_create_venues_ui', 
					'mstw_ss_venue', 'normal', 'high', null );		
	remove_meta_box( 'slugdiv', 'mstw_ss_venue', 'normal' );
}

//-----------------------------------------------------------------
// Build the meta box (controls) for the venues custom post type
//
function mstw_ss_create_venues_ui( $post ) {
	
	wp_nonce_field( plugins_url(__FILE__), 'mstw_ss_venue_nonce' );

	// Retrieve the metadata values if they exist
	$venue_street = get_post_meta($post->ID, 'venue_street', true );
	$venue_city  = get_post_meta($post->ID, 'venue_city', true );
	$venue_state = get_post_meta($post->ID, 'venue_state', true );
	$venue_zip = get_post_meta($post->ID, 'venue_zip', true );
	$venue_map_url = get_post_meta($post->ID, 'venue_map_url', true );  
	$venue_url = get_post_meta($post->ID, 'venue_url', true );
	
	$std_length = 128;
	$std_size = 30;
	
	$admin_fields = array(  'venue_street' => array (
								'type' 		=> 'text',
								'curr_value' => $venue_street,
								'label' 	=> __( 'Street Address:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								),
							'venue_city' => array (
								'type' 		=> 'text',
								'curr_value' => $venue_city,
								'label' 	=> __( 'City:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								),
							'venue_state' => array (
								'type' 		=> 'text',
								'curr_value' => $venue_state,
								'label' 	=> __( 'State:', 'mstw-schedules-scoreboards' ),
								'desc' => __( 'For US states use 2 letter abbreviation. Can include country, e.g, "CA, US", or use only country, e.g, "UK". Check what works with Google Maps (if you aren\'t using a custom map URL).', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								),
							'venue_zip' => array (
								'type' 		=> 'text',
								'curr_value' => $venue_zip,
								'label' 	=> __( 'Zip or Postal Code:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								),
							'venue_url' => array (
								'type' 		=> 'text',
								'curr_value' => $venue_url,
								'label' 	=> __( 'Venue URL:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								),
							'venue_map_url' => array (
								'type' 		=> 'text',
								'curr_value' => $venue_map_url,
								'label' 	=> __( 'Custom Map URL:', 'mstw-schedules-scoreboards' ),
								'desc' => __( 'Use to override the Google map link generated from the address fields. Linked from the map thumbnail in the map column.', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								),
						);					
	?>
	
	<table class='form-table'>	
		<?php mstw_build_admin_edit_screen( $admin_fields ); ?>
	</table>
	
<?php 
}

//-----------------------------------------------------------------
// SAVE & VALIDATE THE MSTW_SS_VENUE CPT META DATA
//
add_action( 'save_post_mstw_ss_venue', 'mstw_ss_save_venue_meta', 20, 2 );
//add_action( 'save_post_mstw_ss_venue', 'mstw_ss_validate_venue_meta', 10, 2 ); 

function mstw_ss_save_venue_meta( $post_id, $post ) {
	
	// Check if this is an auto save call 
	// If so, the form has not been submitted, so don't do anything
	if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' || $post->post_status == 'trash' ) {
		mstw_log_msg( 'In mstw_ss_save_venue_meta ... doing autosave ... nevermind!' );
		return; //$post_id;
	} 	
	
	if( isset($_POST['mstw_ss_venue_nonce'] ) && 
		check_admin_referer( plugins_url(__FILE__), 'mstw_ss_venue_nonce' ) ) {
		
		// Not saving if title is not specified
		if ( !isset( $_POST['post_title'] ) || empty( $_POST['post_title'] ) ) {
			mstw_ss_add_admin_notice( $type = 'error', __( 'A TITLE is necessary. Please enter one.', 'mstw-schedules-scoreboards' ) );
			return;
		}
		
		update_post_meta($post_id, 'venue_street', 
			sanitize_text_field( esc_attr( $_POST['venue_street'] ) ) );
			
		update_post_meta($post_id, 'venue_city',
			sanitize_text_field( esc_attr( $_POST['venue_city'] ) ) );

		update_post_meta($post_id, 'venue_state', 
			sanitize_text_field( esc_attr( $_POST['venue_state'] ) ) );
			
		update_post_meta($post_id, 'venue_zip',
			sanitize_text_field( esc_attr( $_POST['venue_zip'] ) ) );
			
		mstw_validate_url( $_POST, 'venue_map_url', $post_id, 'error', 
							  __( 'Invalid map URL:', 'mstw-schedules-scoreboards' ) );
		
		mstw_validate_url( $_POST, 'venue_url', $post_id, 'error', 
							  __( 'Invalid venue URL:', 'mstw-schedules-scoreboards' ) );

		//mstw_ss_add_admin_notice( 'updated', __( 'Venue saved.', 'mstw-schedules-scoreboards') );							  
		
	} //End: verify nonce/context (valid nonce)
	
	else {
		if ( strpos( wp_get_referer( ), 'trash' ) === FALSE ) {
			mstw_log_msg( 'Oops! In mstw_ss_save_venue_meta() venue nonce not valid.' );
			mstw_ss_add_admin_notice( 'error', __( 'Invalid referer. Contact system admin.', 'mstw-schedules-scoreboards') );
		}
	}
	
} //End: mstw_ss_save_venue_meta( )

// ----------------------------------------------------------------
// Remove Get Shortlink button for the mstw_gs_schedule CPT
//
//add_filter( 'pre_get_shortlink', 'mstw_ss_venue_remove_shortlink', 10, 2 );
	
function mstw_ss_venue_remove_shortlink( $false, $post_id ) {
	return 'mstw_ss_venue' === get_post_type( $post_id ) ? '' : $false;
}

// ----------------------------------------------------------------
// Set up the View All Venues table
//
add_filter( 'manage_edit-mstw_ss_venue_columns', 
			'mstw_ss_edit_venues_columns' ) ;

function mstw_ss_edit_venues_columns( $columns ) {	
	
	//$options = get_option( 'mstw_gs_options' );

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Venue', 'mstw-schedules-scoreboards' ),
		'street' => __( 'Street', 'mstw-schedules-scoreboards' ),
		'city' => __( 'City', 'mstw-schedules-scoreboards' ),
		'state' => __( 'State', 'mstw-schedules-scoreboards' ),
		'zip' => __( 'Zip/Postal Code', 'mstw-schedules-scoreboards' ),
		'venue_map_url' => __( 'Custom Map URL', 'mstw-schedules-scoreboards' ),
		'venue_url' => __( 'Venue URL', 'mstw-schedules-scoreboards' ),
		'venue_groups' => __( 'Groups', 'mstw-schedules-scoreboards' ),
		);

	return $columns;
	
} //End: mstw_ss_edit_venues_columns()

// ----------------------------------------------------------------
// Display the Venues 'view all' columns
// 
add_action( 'manage_mstw_ss_venue_posts_custom_column',
			'mstw_ss_manage_venues_columns', 10, 2 );

function mstw_ss_manage_venues_columns( $column, $post_id ) {
	
	switch( $column ) {	
		
		/* If displaying the 'street' column. */
		case 'street' :
			$venue_street = get_post_meta( $post_id, 'venue_street', true );
			if ( empty( $venue_street ) )
				echo __( 'No Street Address', 'mstw-schedules-scoreboards' );
			else
				printf( '%s', $venue_street );
			break;

		/* If displaying the 'city' column. */
		case 'city' :
			$venue_city = get_post_meta( $post_id, 'venue_city', true );
			if ( empty( $venue_city ) )
				echo __( 'No City', 'mstw-schedules-scoreboards' );
			else
				printf( '%s', $venue_city );
			break;
			
		/* If displaying the 'state' column. */
		case 'state' :
			$venue_state = get_post_meta( $post_id, 'venue_state', true );
		if ( empty( $venue_state ) )
				echo __( 'No State', 'mstw-schedules-scoreboards' );
			else
				printf( '%s', $venue_state );

			break;	
			
		/* If displaying the 'zip' column. */
		case 'zip' :
			$venue_zip = get_post_meta( $post_id, 'venue_zip', true );
			if ( empty( $venue_zip ) )
				echo __( 'No Zip', 'mstw-schedules-scoreboards' );
			else
				printf( '%s', $venue_zip );
			break;	
			
		/* If displaying the 'custom map url' column. */
		case 'venue_map_url' :
			$venue_map_url = get_post_meta( $post_id, 'venue_map_url', true );
			if ( empty( $venue_map_url ) )
				echo __( 'None (use address fields)', 'mstw-schedules-scoreboards' );
			else
				printf( '%s', $venue_map_url );
			break;	

		/* If displaying the 'venue url' column. */
		case 'venue_url' :
			/* Get the post meta. */
			$venue_url = get_post_meta( $post_id, 'venue_url', true );

			if ( empty( $venue_url ) )
				echo __( 'No Venue URL', 'mstw-schedules-scoreboards' );
			else
				printf( '%s', $venue_url );
			break;	
		
		// If displaying the groups column
		case 'venue_groups':
			$groups = get_the_terms( $post_id, 'mstw_ss_venue_group' );
			$edit_link = site_url( '/wp-admin/', null ) . 'edit-tags.php?taxonomy=mstw_ss_venue_group&post_type=mstw_ss_venue';
			if ( is_array( $groups ) && !is_wp_error( $groups ) ) {
				//mstw_log_msg( 'In mstw_ss_manage_venues_columns ...' );
				//mstw_log_msg( $groups );
				foreach( $groups as $key => $group ) {	
					$groups[$key] = '<a href="' . $edit_link . '">' . $group->name . '</a>';
				}
				echo implode( ', ', $groups );
			}
			else {
				echo '<a href="' . $edit_link . '">' . __( 'None', 'mstw-schedules-scoreboards' ) . '</a>';
			}
			break;			
			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
	
} //End: mstw_ss_manage_schedules_columns( )

//-----------------------------------------------------------------
// Set up sorting for the columns
// 
/*
add_filter( 'manage_edit-mstw_ss_schedule_sortable_columns', 'mstw_ss_schedule_sortable_columns' );

function mstw_ss_schedule_sortable_columns( $columns ) {
    //$columns['schedule_name'] = 'schedule_name';
	//$columns['schedule_id'] = 'schedule_id';
	$columns['schedule_team'] = 'schedule_team';
 
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}
*/
?>