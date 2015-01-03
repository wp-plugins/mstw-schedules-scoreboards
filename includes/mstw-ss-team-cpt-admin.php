<?php
/*----------------------------------------------------------------------------
 * mstw-ss-team-cpt-admin.php
 *	This portion of the MSTW Schedules & Scoreboards Plugin admin handles the
 *		mstw_ss_team custom post type.
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
 
//-----------------------------------------------------------------
// Add the meta box for the mstw_ss_team custom post type
//
add_action( 'add_meta_boxes_mstw_ss_team', 'mstw_ss_team_metaboxes' );

function mstw_ss_team_metaboxes( ) {		
	add_meta_box('mstw-ss-team-meta',  __( 'Team Data', 'mstw-schedules-scoreboards' ), 'mstw_ss_create_teams_ui', 
					'mstw_ss_team', 'normal', 'high', null );			
	remove_meta_box( 'slugdiv', 'mstw_ss_team', 'normal' );
}

//-----------------------------------------------------------------
// Build the meta box (controls) for the Teams custom post type
//
function mstw_ss_create_teams_ui( $post ) {

	wp_nonce_field( plugins_url(__FILE__), 'mstw_ss_team_nonce' );
	
	// pull the team data from the UI
	$team_full_name = get_post_meta( $post->ID, 'team_full_name', true );
	$team_short_name = get_post_meta( $post->ID, 'team_short_name', true );
	$team_full_mascot = get_post_meta( $post->ID, 'team_full_mascot', true );
	$team_short_mascot = get_post_meta( $post->ID, 'team_short_mascot', true );
	$team_home_venue = get_post_meta( $post->ID, 'team_home_venue', true );
	$team_link = get_post_meta( $post->ID, 'team_link', true );
	$team_logo = get_post_meta( $post->ID, 'team_logo', true );
	$team_alt_logo = get_post_meta( $post->ID, 'team_alt_logo', true );
	
	$team_sport = get_post_meta( $post->ID, 'team_sport', true );
	$team_staff = get_post_meta( $post->ID, 'team_staff', true );
	$team_roster = get_post_meta( $post->ID, 'team_roster', true );
	$team_league = get_post_meta( $post->ID, 'team_league', true );
	
	//
	// set up the team_home_venue field
	//
	if ( $team_venue_options = mstw_ss_build_venues_list( ) ) {
		$team_venue_type = 'select-option';
		$team_venue_curr_value = $team_home_venue;
	}
	else {
		//no locations exist
		$team_venue_type = 'label';
		$team_venue_curr_value = __( 'No venues found. You must create a venue in the Game Locations DB before assigning it to a team.', 'mstw-schedules-scoreboards' );
		//$team_venue_options = array();
	}
	
	//
	// set up the team_sport field
	//
	if ( $team_sport_options = mstw_ss_build_sports_list( ) ) {
		$team_sport_type = 'select-option';
		$team_sport_curr_value = $team_sport;
	}
	else {
		// no sports exist
		$team_sport_type = 'label';
		$team_sport_curr_value = __( 'No sports found. You must create a sport in the Sports DB before assigning it to a team.', 'mstw-schedules-scoreboards' );
		$team_location_options = array();
	}
	
	//
	// set up the team_staff field
	//
	if ( !is_plugin_active('coaching-staffs/mstw-coaching-staffs.php') ) {
		$team_staff_type = 'label';
		$team_staff_curr_value = sprintf( '%s <a href="http://wordpress.org/coaching-staffs" target="_blank">%s</a> %s', __('Install the', 'mstw-schedules-scoreboards' ), __( 'MSTW Coaching Staffs plugin',  'mstw-schedules-scoreboards' ), __( 'to use this feature.', 'mstw-schedules-scoreboards') );
		$team_staff_options = array();
	}
	else if ( $team_staff_options = mstw_ss_build_staffs_list( ) ) {
		//locations plugin is active and locations exist
		//$team_location_options = mstw_ss_build_locations_list( );
		$team_staff_type = 'select-option';
		$team_staff_curr_value = $team_staff;
	}
	else {
		//locations plugin is active but no locations exist
		$team_staff_type = 'label';
		$team_staff_curr_value = __( 'No coaching staffs found. You must create a coaching staff in the Coaching Staffs DB before assigning it to a team.', 'mstw-schedules-scoreboards' );
		$team_staff_options = array();
	}
	
	//
	// set up the team_roster field
	//
	if ( !is_plugin_active('team-rosters/mstw-team-rosters.php') ) {
		// team rosters plugin is not active
		$team_roster_type = 'label';
		$team_roster_curr_value = sprintf( '%s <a href="http://wordpress.org/team-rosters" target="_blank">%s</a> %s', __('Install the', 'mstw-schedules-scoreboards' ), __( 'MSTW Team Rosters plugin',  'mstw-schedules-scoreboards' ), __( 'to use this feature.', 'mstw-schedules-scoreboards') );
		$team_roster_options = array();
	}
	else if ( $team_roster_options = mstw_ss_build_rosters_list( ) ) {
		// have some teams in team rosters plugin
		$team_roster_type = 'select-option';
		$team_roster_curr_value = $team_roster;
	}
	else {
		// team rosters plugin is active but no rosters exist
		$team_roster_type = 'label';
		$team_roster_curr_value = __( 'No team rosters found. You must create a roster in the Team Rosters DB before assigning it to a team.', 'mstw-schedules-scoreboards' );
		$team_roster_options = array();
	}
	
	//
	// set up the team_league field
	//
	if ( !is_plugin_active('league-standings/mstw-league-standings.php') ) {
		// team rosters plugin is not active
		$team_league_type = 'label';
		$team_league_curr_value = sprintf( '%s <a href="http://wordpress.org/league-standings" target="_blank">%s</a> %s', __('Install the', 'mstw-schedules-scoreboards' ), __( 'MSTW League Standings plugin',  'mstw-schedules-scoreboards' ), __( 'to use this feature.', 'mstw-schedules-scoreboards') );
		$team_league_options = array();
	}
	else if ( $team_league_options = mstw_ss_build_leagues_list( ) ) {
		// have some teams in team rosters plugin
		$team_league_type = 'select-option';
		$team_league_curr_value = $team_league;
	}
	else {
		// team rosters plugin is active but no rosters exist
		$team_league_type = 'label';
		$team_league_curr_value = __( 'No leagues found. You must create a league in the League (Standings) DB before assigning it to a team.', 'mstw-schedules-scoreboards' );
		$team_league_options = array();
	}
	
	$std_length = 128;
	$std_size = 30;
	
	$admin_fields = array(  'team_full_name' => array (
								'type'	=> 'text',
								'curr_value' => $team_full_name,
								'label' => __( 'Full Name:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'E.g., "San Francisco" or "California"', 'mstw-schedules-scoreboards' ),
								),
							'team_short_name' => array (
								'type'	=> 'text',
								'curr_value' => $team_short_name,
								'label' => __( 'Short Name:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'E.g., "SF" or "Cal". If not specified, full name will be used in it\'s place.', 'mstw-schedules-scoreboards' ),
								),
							'team_full_mascot' => array (
								'type'	=> 'text',
								'curr_value' => $team_full_mascot,
								'label' => __( 'Full Mascot Name:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'E.g., "49ers" or "Golden Bears"', 'mstw-schedules-scoreboards' ),
								),
							'team_short_mascot' => array (
								'type'	=> 'text',
								'curr_value' => $team_short_mascot,
								'label' => __( 'Short Mascot Name:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'E.g., "Niners" or "Bears". If not specified, full mascot name will be used in it\'s place.', 'mstw-schedules-scoreboards' ),
							),
							'team_link' => array (
								'type'	=> 'text',
								'curr_value' => $team_link,
								'label' => __( 'Link to Team Site:', 'mstw-schedules-scoreboards' ),
								'maxlength' => 256,
								'size' => $std_size,
								'desc' => __( 'E.g., "http://49ers.com" or "http://calbears.com"', 'mstw-schedules-scoreboards' ),
								),
							'team_logo' => array (
								'type'	=> 'media-uploader',
								//'type' => 'text',
								'curr_value' => $team_logo,
								'label' => __( 'Table Logo:', 'mstw-schedules-scoreboards' ),
								'maxlength' => 256,
								'size' => $std_size,
								'desc' => __( 'Enter the full path to any file, or click the button to access the media library. Recommended size 41x28px.', 'mstw-schedules-scoreboards' ),
								'btn_label' => __( 'Upload from Media Library', 'mstw-schedules-scoreboards' ),
								'img_width' => 41,
								),
							'team_alt_logo' => array (
								'type'	=> 'media-uploader',
								//'type' => 'text',
								'curr_value' => $team_alt_logo,
								'label' => __( 'Slider Logo:', 'mstw-schedules-scoreboards' ),
								'maxlength' => 256,
								'size' => $std_size,
								'desc' => __( 'Enter the full path to any file, or click the button to access the media library. Recommended size 125x125px.', 'mstw-schedules-scoreboards' ),
								'btn_label' => __( 'Upload from Media Library', 'mstw-schedules-scoreboards' ),
								'img_width' => 80,
								),
							'team_home_venue' => array (
								'type'	=> $team_venue_type,
								'curr_value' => $team_venue_curr_value,
								'label' => __( 'Home Venue:', 'mstw-schedules-scoreboards' ),
								'options' => $team_venue_options,
								),
							'team_divider' => array (
								'type' => 'divider',
								'curr_value' => sprintf( __( 'In this release, the following fields are for use by developers only. They have no impact on the plugin\'s current user interface. %s Read more here. %s', 'mstw-schedules-scoreboards' ), '<a href="http://shoalsummitsolutions.com" target="_blank">', "</a>" ),
								),
							'team_sport' => array (
								'type'	=> $team_sport_type,
								'curr_value' => $team_sport_curr_value,
								'label' => __( 'Team Sport/Season:', 'mstw-schedules-scoreboards' ),
								'options' => $team_sport_options,
								),
							'team_staff' => array (
								'type'	=> $team_staff_type,
								'curr_value' => $team_staff_curr_value,
								'label' => __( 'Team Coaching Staff:', 'mstw-schedules-scoreboards' ),
								'options' => $team_staff_options,
								),
							'team_roster' => array (
								'type'	=> $team_roster_type,
								'curr_value' => $team_roster_curr_value,
								'label' => __( 'Team Roster:', 'mstw-schedules-scoreboards' ),
								'options' => $team_roster_options,
								),	
							'team_league' => array (
								'type'	=> $team_league_type,
								'curr_value' => $team_league_curr_value,
								'label' => __( 'Team League:', 'mstw-schedules-scoreboards' ),
								'options' => $team_league_options,
								),
						);
						
	?> 
	<table class="form-table">
	
	<?php mstw_build_admin_edit_screen( $admin_fields ); ?>
	
	</table> <!-- End: table .form-table -->
	
<?php 
} //End: mstw_ss_create_teams_ui()



//-----------------------------------------------------------------
// SAVE THE MSTW_SS_TEAM CPT META DATA
//
add_action( 'save_post_mstw_ss_team', 'mstw_ss_save_team_meta', 20, 2 );

function mstw_ss_save_team_meta( $post_id, $post ) {

	// check if this is an auto save routine. 
	// If it is our form has not been submitted, so don't do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || $post->post_status == 'auto-draft' || $post->post_status == 'trash' ) {
		mstw_log_msg( 'doing autosave ... nevermind!' );
		return; //$post_id;
	}
	
	//check that we are in the right context ... saving from edit page
	if( isset($_POST['mstw_ss_team_nonce'] ) && 
		check_admin_referer( plugins_url(__FILE__), 'mstw_ss_team_nonce' ) ) {
		//PROCESS THE USER INPUT
		update_post_meta( $post_id, 'team_full_name', sanitize_text_field( esc_attr( $_POST['team_full_name'] ) ) );
		
		update_post_meta( $post_id, 'team_short_name', sanitize_text_field( esc_attr( $_POST['team_short_name'] ) ) );
	
		update_post_meta( $post_id, 'team_full_mascot', sanitize_text_field( esc_attr( $_POST['team_full_mascot'] ) ) );
	
		update_post_meta( $post_id, 'team_short_mascot', sanitize_text_field( esc_attr( $_POST['team_short_mascot'] ) ) );
		
		update_post_meta( $post_id, 'team_home_venue', sanitize_text_field( esc_attr( mstw_safe_ref( $_POST, 'team_home_venue' ) ) ) );
		
		update_post_meta( $post_id, 'team_sport', sanitize_text_field( esc_attr( mstw_safe_ref( $_POST, 'team_sport' ) ) ) );
		
		update_post_meta( $post_id, 'team_staff', sanitize_text_field( esc_attr( mstw_safe_ref( $_POST, 'team_staff' ) ) ) );
		
		update_post_meta( $post_id, 'team_roster', sanitize_text_field( esc_attr( mstw_safe_ref( $_POST, 'team_roster' ) ) ) );
		
		update_post_meta( $post_id, 'team_league', sanitize_text_field( esc_attr( mstw_safe_ref( $_POST, 'team_league' ) ) ) );
		
		mstw_validate_url( $_POST, 'team_link', $post_id, 'error', 
							  __( 'Invalid team link:', 'mstw-schedules-scoreboards' ) );
		
		mstw_validate_url( $_POST, 'team_logo', $post_id, 'error', 
							  __( 'Invalid team table logo:', 'mstw-schedules-scoreboards' ) );
							  
		mstw_validate_url( $_POST, 'team_alt_logo', $post_id, 'warning', 
							  __( 'Invalid team slider logo:', 'mstw-schedules-scoreboards' ) );
							  
		//mstw_ss_add_admin_notice( 'updated', __( 'Team saved.', 'mstw-schedules-scoreboards') );
	}
	else {
		if ( strpos( wp_get_referer( ), 'trash' ) === FALSE ) {
			mstw_log_msg( 'Oops! In mstw_ss_save_team_meta() team nonce not valid.' );
			mstw_ss_add_admin_notice( 'error', __( 'Invalid referer. Contact system admin.', 'mstw-schedules-scoreboards') );
		}
	}
	
} //End: mstw_ss_save_team_meta()

//-----------------------------------------------------------------
// Remove edit permalink line for the mstw_ss_team CPT because
//		the schedule_id field is used for the permalink
//
add_filter( 'get_sample_permalink_html', 'mstw_ss_team_remove_permalink', 15, 4 );

function mstw_ss_team_remove_permalink( $return, $post_id, $new_title, $new_slug ) {
	// Remove the line for the mstw_ss_schedules post type only
	return 'mstw_ss_team' === get_post_type( $post_id ) ? '' : $return;
}

// ----------------------------------------------------------------
// Remove Get Shortlink button for the mstw_gs_schedules CPT
//
add_filter( 'pre_get_shortlink', 'mstw_ss_team_remove_shortlink', 10, 2 );
	
function mstw_ss_team_remove_shortlink( $false, $post_id ) {
	return 'mstw_ss_team' === get_post_type( $post_id ) ? '' : $false;
}

// ----------------------------------------------------------------
// Set up the View All Teams table
//
add_filter( 'manage_edit-mstw_ss_team_columns', 
			'mstw_ss_edit_team_columns' ) ;

function mstw_ss_edit_team_columns( $columns ) {	
	
	//$options = get_option( 'mstw_gs_options' );

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Team Name', 'mstw-schedules-scoreboards' ),
		'team_full_name' 	=> __( 'Team Full Name', 'mstw-schedules-scoreboards' ),
		'team_short_name' 	=> __( 'Team Short Name', 'mstw-schedules-scoreboards' ),
		'team_full_mascot' 	=> __( 'Mascot Full Name', 'mstw-schedules-scoreboards' ),
		'team_short_mascot' => __( 'Mascot Short Name', 'mstw-schedules-scoreboards' ),
		'team_link' 		=> __( 'Team Link', 'mstw-schedules-scoreboards' ),
		'team_logo' 		=> __( 'Table Logo', 'mstw-schedules-scoreboards' ),
		'team_alt_logo' 	=> __( 'Slider Logo', 'mstw-schedules-scoreboards' ),
		);

	return $columns;
}

//-----------------------------------------------------------------
// Display the View All Teams table columns
// 
add_action( 'manage_mstw_ss_team_posts_custom_column',
			'mstw_ss_manage_team_columns', 10, 2 );

function mstw_ss_manage_team_columns( $column, $post_id ) {
	global $post;
	
	switch( $column ) {	
		case 'team_full_name':
			$name = get_post_meta( $post_id, 'team_full_name', true );
			if( $name != '' )
				echo ( $name );
			else
				_e( 'No Full Name', 'mstw-schedules-scoreboards' ); 
			break;
		case 'team_short_name':
			$name = get_post_meta( $post_id, 'team_short_name', true );
			if( $name != '' )
				echo ( $name );
			else
				_e( 'No Short Name. (Defaults to full name.)', 'mstw-schedules-scoreboards' ); 
			break;
		case 'team_full_mascot':
			$mascot = get_post_meta( $post_id, 'team_full_mascot', true );
			if( $mascot != '' )
				echo ( $mascot );
			else
				_e( 'No Full Mascot', 'mstw-schedules-scoreboards' ); 
			break;
		case 'team_short_mascot':
			$mascot = get_post_meta( $post_id, 'team_short_mascot', true );
			if( $mascot != '' )
				echo ( $mascot );
			else
				_e( 'No Short Mascot. (Defaults to full mascot.)', 'mstw-schedules-scoreboards' ); 
			break;
		case 'team_link':
			$link = get_post_meta( $post_id, 'team_link', true );
			if( $link != '' )
				//echo( substr( strrchr( rtrim( $link, '/' ), '/' ), 1 ) );
				echo $link;
			else
				_e( 'Not Set', 'mstw-schedules-scoreboards' ); 
			break;
		case 'team_logo':
			$logo = get_post_meta( $post_id, 'team_logo', true );
			if( $logo != '' )
				echo( substr( strrchr( rtrim( $logo, '/' ), '/' ), 1 ) );
			else
				_e( 'Not Set', 'mstw-schedules-scoreboards' ); 
			break;
		case 'team_alt_logo':
			$logo = get_post_meta( $post_id, 'team_alt_logo', true );
			if( $logo != '' )
				echo( substr( strrchr( rtrim( $logo, '/' ), '/' ), 1 ) );
			else
				_e( 'Not Set', 'mstw-schedules-scoreboards' ); 
			break;
		case 'team_home_venue':
			$venue = get_post_meta( $post_id, 'team_home_venue', true );
			if( $venue != '' )
				_e( 'SET', 'mstw-schedules-scoreboards' );
			else
				_e( 'Not Set', 'mstw-schedules-scoreboards' ); 
			break;
		default :
			/* Just break out of the switch statement for everything else. */
			break;
	}
} //End: mstw_ss_manage_team_columns( )

	

?>