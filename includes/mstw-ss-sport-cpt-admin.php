<?php
/*----------------------------------------------------------------------------
 * mstw-ss-sport-cpt-admin.php
 *	This portion of the MSTW Schedules & Scoreboards Plugin admin handles the
 *		mstw_ss_sport custom post type.
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
 //mstw_log_msg( 'in mstw-ss-game-cpt-admin .... ' );
 
//-----------------------------------------------------------------
// Add the meta box for the mstw_ss_game custom post type
//
add_action( 'add_meta_boxes_mstw_ss_sport', 'mstw_ss_sport_metaboxes' );

function mstw_ss_sport_metaboxes( ) {
		
	add_meta_box('mstw-ss-sport-meta',  __( 'Sport Data', 'mstw-schedules-scoreboards' ), 'mstw_ss_create_sports_ui', 
					'mstw_ss_sport', 'normal', 'high', null );
					
} //End: mstw_ss_sport_metaboxes( )

//-----------------------------------------------------------------
// Build the meta box (controls) for the Games custom post type
//
function mstw_ss_create_sports_ui( $post ) {
	
	wp_nonce_field( plugins_url(__FILE__), 'mstw_ss_sport_nonce' );
	
										  
	// Retrieve the metadata values if they exist
	$sport_season = get_post_meta( $post->ID, 'sport_season', true );
	
	$sport_gender = get_post_meta( $post->ID, 'sport_gender', true ); 
	
	?>
	
   <table class="form-table">
	
	<?php
	$seasons = mstw_ss_get_sport_seasons( );
						
	$genders = mstw_ss_get_sport_genders( );
					
	$admin_fields = array( 	'sport_season' 	=> array (
								'type' 			=> 'select-option',
								'curr_value' 	=> $sport_season,
								'options' 		=> $seasons,
								'label' 		=> __( 'Season:', 'mstw-schedules-scoreboards' ),
								'desc' 			=> __( 'Select the season of the sport.', 'mstw-schedules-scoreboards' ),
								//'maxlength' 	=> '',
								//'size' 		=> '',
								//'id'		=> 'sport_season',
								//'name'		=> 'sport_season',
								//'class'		=> '',
								),
							'sport_gender' => array (
								'type' 			=> 'select-option',
								'curr_value' 	=> $sport_gender,
								'options' 		=> $genders,
								'label' 		=> __( 'Gender:', 'mstw-schedules-scoreboards' ),
								'desc' 		=> __( 'Select the gender for the sport.', 'mstw-schedules-scoreboards' ),
								//'maxlength' 	=> '',
								//'size' 		=> '',
								//'id'		=> 'sport_gender',
								//'name'		=> 'sport_gender',
								//'class'		=> 'twenty',
								),
						 );
						
		mstw_build_admin_edit_screen( $admin_fields ); 
	?>
	
	</table>
	
<?php        	
}

//-----------------------------------------------------------------
// SAVE THE MSTW_SS_SPORT CPT META DATA
//
add_action( 'save_post_mstw_ss_sport', 'mstw_ss_save_sport_meta', 20, 2 );

function mstw_ss_save_sport_meta( $post_id, $post ) {

	// check if this is an auto save routine. 
	// If it is our form has not been submitted, so don't do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || $post->post_status == 'auto-draft' || $post->post_status == 'trash' ) {
		mstw_log_msg( 'doing autosave ... nevermind!' );
		return; //$post_id;
	}
	
	//check that we are in the right context ... saving from edit page
	if( isset( $_POST['mstw_ss_sport_nonce'] ) && 
		check_admin_referer( plugins_url(__FILE__), 'mstw_ss_sport_nonce' ) ) {
		
		//PROCESS THE USER INPUT
		//mstw_log_msg( 'Saving sport data ... ' );
		
		update_post_meta( $post_id, 'sport_season', sanitize_text_field( $_POST['sport_season'] ) );
		
		update_post_meta( $post_id, 'sport_gender', sanitize_text_field( $_POST['sport_gender'] ) );
	
	}
	else {
	
		if ( strpos( wp_get_referer( ), 'trash' ) === FALSE ) {
			mstw_log_msg( 'Oops! In mstw_ss_save_venue_meta() sport nonce not valid.' );
			mstw_ss_add_admin_notice( 'error', __( 'Invalid referer. Contact system admin.', 'mstw-schedules-scoreboards') );
		}
	}
	
} //End: mstw_ss_save_sport_meta()

// ----------------------------------------------------------------
// Set up the View All Sports table
//
add_filter( 'manage_edit-mstw_ss_sport_columns', 'mstw_ss_edit_sport_columns' ) ;

function mstw_ss_edit_sport_columns( $columns ) {	

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Sport', 'mstw-schedules-scoreboards' ),
		'sport_season' 	=> __( 'Season', 'mstw-schedules-scoreboards' ),
		'sport_gender' 	=> __( 'Gender', 'mstw-schedules-scoreboards' ),
		);

	return $columns;
	
} //End: mstw_ss_edit_sport_columns( )

//-----------------------------------------------------------------
// Display the View All Sports table columns
// 
add_action( 'manage_mstw_ss_sport_posts_custom_column',
			'mstw_ss_manage_sport_columns', 10, 2 );

function mstw_ss_manage_sport_columns( $column, $post_id ) {
	//global $post; ??
	//Need the admin time and date formats
	
	switch( $column ) {	
		case 'sport_season':
			$sport_season = get_post_meta( $post_id, 'sport_season', true );
			if ( empty( $sport_season ) )
				_e( 'Season not specified.', 'mstw-schedules-scoreboards' );
			else {
				printf( '%s', $sport_season );
			}
			break;

		case 'sport_gender':
			$sport_gender = get_post_meta( $post_id, 'sport_gender', true );
			if ( empty( $sport_gender ) )
				_e( 'Gender not specified.', 'mstw-schedules-scoreboards' );
			else {
				printf( '%s', $sport_gender );
			}
			break;
		
		default :
			/* Just break out of the switch statement for everything else. */
			break;
	}
} //End: mstw_ss_manage_sport_columns( ) 

// ----------------------------------------------------------------
// Add a filter to sort all sports table on ??
//
add_filter("manage_edit-mstw_ss_sport_sortable_columns", 'mstw_ss_sports_columns_sort');

function mstw_ss_sports_columns_sort( $columns ) {
	$custom = array(
		'sport_season' => 'sport_season',
		'sport_gender' 	=> 'sport_gender'
	);
	return wp_parse_args( $custom, $columns );
} //End: mstw_ss_sports_columns_sort()

// ----------------------------------------------------------------
// Convenience functions to centralize sports & gender arrays
//
function mstw_ss_get_sport_seasons( ) {
	return array (  __( 'Fall', 'mstw-schedules-scoreboards' ) => 'fall', 
					__( 'Winter', 'mstw-schedules-scoreboards' ) =>'winter', 
					__( 'Spring', 'mstw-schedules-scoreboards' ) => 'spring',
					__( 'Summer', 'mstw-schedules-scoreboards' ) => 'summer', 
					);
} //End: mstw_ss_get_sport_seasons( )
			
function mstw_ss_get_sport_genders( ) {
	return array ( 	__( 'Male', 'mstw-schedules-scoreboards' ) => 'male', 
					__( 'Female', 'mstw-schedules-scoreboards' ) =>'female', 
					__( 'Coed', 'mstw-schedules-scoreboards' ) => 'coed', 
					);
}
 ?>