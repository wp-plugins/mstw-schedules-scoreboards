<?php
/*----------------------------------------------------------------------------
 * mstw-ss-schedule-cpt-admin.php
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
// Add the meta box for the Schedules custom post type
//
add_action( 'add_meta_boxes_mstw_ss_schedule', 'mstw_ss_schedule_metaboxes' );

function mstw_ss_schedule_metaboxes( ) {		
	add_meta_box('mstw-ss-schedule-meta', __( 'Schedule Data', 'mstw-schedules-scoreboards' ), 'mstw_ss_create_schedules_ui', 
					'mstw_ss_schedule', 'normal', 'high', null );		
	//remove_meta_box( 'slugdiv', 'mstw_ss_schedule', 'normal' );
}

//-----------------------------------------------------------------
// Build the meta box (controls) for the Schedules custom post type
//
function mstw_ss_create_schedules_ui( $post ) {
	
	wp_nonce_field( plugins_url(__FILE__), 'mstw_ss_schedule_nonce' );

	// pull the schedule data for the UI
	//mstw_log_msg( 'in mstw_ss_create_schedules_ui for id: ' . $post->ID );
	//mstw_log_msg( get_post_meta( $post->ID ) );
	$schedule_id = $post->post_name;
	//mstw_log_msg( '$schedule_id = ' . $schedule_id );
	$schedule_team = get_post_meta( $post->ID, 'schedule_team', true );
	//mstw_log_msg( '$schedule_team = ' . $schedule_team );
	
	$team_options = mstw_ss_build_teams_list( );
	//$options = array( ); for testing
	
	if ( $team_options ) {
		$team_type = 'select-option';
		$curr_value = $schedule_team;
	} 
	else {
		$team_type = 'label';
		$curr_value = __ ( 'Create a Team via the Teams menu before creating a schedule.', 'mstw-schedules-scoreboards' );
	}
	
	$std_length = 128;
	$std_size = 30;
	
	$admin_fields = array(  'schedule_id' => array (
							'type' 		=> 'text',
							'curr_value' => $schedule_id,
							'label' 	=> __( 'Schedule ID:', 'mstw-schedules-scoreboards' ),
							'desc' 		=> __( 'Will default to title entry converted to WP "slug" format(128 character max) E.g., "2013 Varsity Football" will be converted to "2013-varsity-football."', 'mstw-schedules-scoreboards' ),
							'maxlength' => $std_length,
							'size' => $std_size,
							),
							
							'schedule_team' => array (
							'type' 		=> $team_type,
							'curr_value' => $curr_value,
							'options' 	=> $team_options,
							'label' 	=> __( 'Schedule For:', 'mstw-schedules-scoreboards' ),
							'desc' 		=> __( 'Selected team (from the Teams DB) will be the home team for this schedule.', 'mstw-schedules-scoreboards' ),
							'maxlength' => '',
							'size' 		=> '',
							)
						);					
	?>
	
	<table class='form-table'>
	
		<?php mstw_build_admin_edit_screen( $admin_fields ); 
				
				//	echo "<label>". __ ( 'Create a Team via the Teams menu to assign it to a schedule.', 'mstw-schedules-scoreboards' ) . "</label>";
				
				?>
	</table>
<?php 
}

//-----------------------------------------------------------------
// SAVE & VALIDATE THE MSTW_SS_SCHEDULE CPT META DATA
//
add_action( 'save_post_mstw_ss_schedule', 'mstw_ss_save_schedule_meta', 20, 2 );
//add_action( 'save_post_mstw_ss_schedule', 'mstw_ss_validate_schedule_meta', 20, 2 ); 

function mstw_ss_save_schedule_meta( $post_id, $post ) {

	// check if this is an auto save routine. 
	// If it is our form has not been submitted, so don't do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || $post->post_status == 'auto-draft' || $post->post_status == 'trash' ) {
		mstw_log_msg( 'doing autosave ... nevermind!' );
		return; //$post_id;
	}
	
	//check that we are in the right context ... saving from edit page
	if( isset( $_POST['mstw_ss_schedule_nonce'] ) && 
		check_admin_referer( plugins_url(__FILE__), 'mstw_ss_schedule_nonce' ) ) { 

		//mstw_log_msg( '$post_id= ' . $post_id . ' and the new post data in $_POST ... ' );
		//mstw_log_msg( $_POST );
		
	// This is an unnecessary double-check??
	//if( isset( $_POST['post_type'] ) && 
		//( $_POST['post_type'] == 'mstw_ss_schedule' ) ) {
		
		//mstw_log_msg( 'get_the_title( $post_id ) = ' . get_the_title( $post_id ) );
		//mstw_log_msg( '$_POST[\'post_title\'] = ' . $_POST['post_title'] );		
		
		// Process the updates
		// Not saving if title is not specified
		if ( !isset( $_POST['post_title'] ) or empty( $_POST['post_title'] ) ) {
			mstw_ss_add_admin_notice( $type = 'error', __( 'A TITLE is necessary. Please enter one.', 'mstw-schedules-scoreboards' ) ); 
		}
		
		// SCHEDULE_ID / POST_NAME is handled in the name_save_pre filter callback, 
		//		mstw_ss_save_schedule_name()
		
		if ( isset( $_POST['schedule_team'] ) && !empty( $_POST['schedule_team'] ) && $_POST['schedule_team'] != -1 ) {
			//mstw_log_msg( 'schedule_team: ' . $_POST['schedule_team'] );
			update_post_meta( $post_id, 'schedule_team', 
							  sanitize_text_field( $_POST['schedule_team'] ) );
		}
		else {
			mstw_ss_add_admin_notice( 'error', __( 'A TEAM for the schedule is necessary. Please enter one.', 'mstw-schedules-scoreboards' ) );
			return;
		}
	} //End: verify nonce/context
	else {
		if ( strpos( wp_get_referer( ), 'trash' ) === FALSE ) {
			mstw_log_msg( 'Oops! In mstw_ss_save_schedule_meta() schedule nonce not valid.' );
			mstw_ss_add_admin_notice( 'error', __( 'Invalid referer. Contact system admin.', 'mstw-schedules-scoreboards') );
		}
	}
		
	//} //End: if( isset( _$POST['post_type] ) &&
	
} //End: mstw_ss_save_schedule_meta( )

function mstw_ss_validate_schedule_meta( $post_id, $post ) {
	global $wpdb;
	
	// check if this is an auto save routine. 
	// If it is our form has not been submitted, so don't do anything
	if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' ) {
		//mstw_log_msg( 'in mstw_ss_validate_schedule_meta ...' );
		//mstw_log_msg( 'doing autosave or an auto-draft ... don\'t do nothin\'' );
		return $post_id;
	}
	
	// retrieve meta to be validated
	$title = get_the_title( $post_id );
    $team = get_post_meta( $post_id, 'schedule_team', true );
    // just checking it's not empty - you could do other tests...
    if ( empty( $team ) or $team == -1 or $title == '' ) {
        $meta_missing = true;
    }
	else {
		$meta_missing = false;
	}
		
	if ( ( isset( $_POST['publish'] ) || isset( $_POST['save'] ) ) && $_POST['post_status'] == 'publish' ) {
		//mstw_log_msg( 'in mstw_ss_validate_schedule_meta ...' );
		//mstw_log_msg( '$meta_missing: ' . $meta_missing );
		if ( $meta_missing ) {
			$wpdb->update( $wpdb->posts, array( 'post_status' => 'pending' ), array( 'ID' => $post_id ) );
			// filter the query URL to change the published message
			//add_filter( 'redirect_post_location', create_function( '$location','return add_query_arg("message", "4", $location);' ) );
		}
	}
	
} //End: mstw_ss_validate_schedule_meta()

// ----------------------------------------------------------------
// MSTW_SS_SCHEDULE CPT use the schedule_id field as the post_name (slug)
//		if it's not there, the post_title is used per normal
// 
add_filter('name_save_pre', 'mstw_ss_save_schedule_name', 20, 1 );

function mstw_ss_save_schedule_name( $post_name ) {
	global $post;
	//mstw_log_msg( 'divider' );
	//mstw_log_msg( 'in mstw_ss_save_name( ) ... $post_name = ' . $post_name );
	
	//mstw_log_msg( $post );
	//mstw_log_msg( $_POST );

	
	if ( isset( $_POST['post_type'] ) &&  $_POST['post_type'] == 'mstw_ss_schedule' ) {
		if ( isset( $_POST['schedule_id'] ) and !empty( $_POST['schedule_id'] ) ) {
			//mstw_log_msg( 'setting post_name from $_POST[\'schedule_id\']' );
			$post_name = sanitize_title( $_POST['schedule_id'] );
		}
		else if ( isset( $_POST['post_title'] ) and !empty( $_POST['post_title'] ) ) {
			//mstw_log_msg( 'setting post_name from $_POST[\'post_title\']' );
			$post_name = sanitize_title( $_POST['post_title'] );
		}
		else {
			//mstw_log_msg( ' not setting post_name' );
			$post_name = 'please-specify';
		}
	} //End if ( $_POST['post_type'] == 'mstw_gs_schedules' )
	//mstw_log_msg( 'returning $post_name: ' . $post_name );
	//mstw_log_msg( 'divider' );
	return $post_name;
}
	
//-----------------------------------------------------------------
// Remove edit permalink line for the mstw_ss_schedule CPT because
//		the schedule_id field is used for the permalink
//
add_filter( 'get_sample_permalink_html', 'mstw_ss_schedule_remove_permalink', 15, 4 );

function mstw_ss_schedule_remove_permalink( $return, $post_id, $new_title, $new_slug ) {
	// Remove the line for the mstw_ss_schedules post type only
	return 'mstw_ss_schedule' === get_post_type( $post_id ) ? '' : $return;
}

// ----------------------------------------------------------------
// Remove Get Shortlink button for the mstw_gs_schedule CPT
//
add_filter( 'pre_get_shortlink', 'mstw_ss_schedule_remove_shortlink', 10, 2 );
	
function mstw_ss_schedule_remove_shortlink( $false, $post_id ) {
	return 'mstw_ss_schedule' === get_post_type( $post_id ) ? '' : $false;
}

// ----------------------------------------------------------------
// Set up the View All Schedules table
//
	add_filter( 'manage_edit-mstw_ss_schedule_columns', 
				'mstw_ss_edit_schedules_columns' ) ;

	function mstw_ss_edit_schedules_columns( $columns ) {	
		
		//$options = get_option( 'mstw_gs_options' );

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Schedule Name', 'mstw-schedules-scoreboards' ),
			//'schedule_name'		=> __( 'Schedule Name', 'mstw-schedules-scoreboards' ),
			'schedule_id' 		=> __( 'Schedule ID', 'mstw-schedules-scoreboards' ),
			'schedule_team' 	=> __( 'Schedule for Team', 'mstw-schedules-scoreboards' ),
			);

		return $columns;
	}

// ----------------------------------------------------------------
// Display the Schedules 'view all' columns
// 
add_action( 'manage_mstw_ss_schedule_posts_custom_column',
			'mstw_ss_manage_schedules_columns', 10, 2 );

function mstw_ss_manage_schedules_columns( $column, $post_id ) {
	global $post;
	//global $screen;
	
	//mstw_log_msg( get_current_screen( ) );
	
	//if ( empty( get_post_meta( $post_id, 'schedule_name', true ) ) ) {
		//return;
	//}
	switch( $column ) {	
		
		// SCHEDULE ID column (really post 'slug' or 'post_name')
		case 'schedule_id' :
			//$id = get_post_meta( $post_id, 'schedule_id', true );
			$slug = get_post( $post_id ) -> post_name;
			if( $slug != '' )
				echo ( $slug );
			else
				_e( 'No Schedule ID', 'mstw-schedules-scoreboards' ); 
				
			break;
		
		// SCHEDULE FOR TEAM column
		case 'schedule_team' :
			$team_slug = get_post_meta( $post_id, 'schedule_team', true );
			if( $team_slug != '' ) {
				$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
				echo( get_the_title( $team_obj ) );
			}
			else {
				_e( 'No Team for Schedule', 'mstw-schedules-scoreboards' ); 
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
add_filter( 'manage_edit-mstw_ss_schedule_sortable_columns', 'mstw_ss_schedule_sortable_columns' );

function mstw_ss_schedule_sortable_columns( $columns ) {
    //$columns['schedule_name'] = 'schedule_name';
	//$columns['schedule_id'] = 'schedule_id';
	$columns['schedule_team'] = 'schedule_team';
 
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}
?>