<?php
/*----------------------------------------------------------------------------
 * mstw-ss-admin.php
 *	This is the admin portion of the MSTW Schedules & Scoreboards Plugin
 *	It is loaded conditioned on is_admin() in mstw-game-schedule.php 
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
// Load the stuff admin needs
// This is called from the init hook in mstw-schedules-scoreboards.php
//
if ( is_admin( ) ) {
	add_action( 'admin_init', 'mstw_ss_admin_init' );
	//should this be -ss- or mstw-wide?
	// AND should it be -admin- or will it be used on the front end too?
	//include_once 'mstw-ss-admin-utils.php';
	
	include_once 'mstw-ss-settings.php';
	include_once 'mstw-ss-color-settings.php';
	include_once 'mstw-ss-dtg-settings.php';
	include_once 'mstw-ss-venue-settings.php';
	include_once 'mstw-ss-scoreboard-settings.php';
	include_once 'mstw-ss-schedule-cpt-admin.php';
	include_once 'mstw-ss-team-cpt-admin.php';
	include_once 'mstw-ss-game-cpt-admin.php';
	include_once 'mstw-ss-sport-cpt-admin.php';
	include_once 'mstw-ss-venue-cpt-admin.php';
	include_once 'mstw-ss-csv-import-class.php';
	
	add_action( 'admin_notices', 'mstw_ss_admin_notice' );
	
} else {
	die( 'You cheater. You are not an admin!' );
} //End: if( is_admin( ) )

//----------------------------------------------------------------
// Hide the publishing actions on the edit and new CPT screens
//
add_action( 'admin_head-post.php', 'mstw_ss_hide_publishing_actions' );
add_action( 'admin_head-post-new.php', 'mstw_ss_hide_publishing_actions' );

function mstw_ss_hide_publishing_actions( ) {

	$post_type = mstw_get_current_post_type( );
	
	//mstw_log_msg( 'in ... mstw_ss_hide_publishing_actions' );
	//mstw_log_msg( $post_type );
	if( $post_type == 'mstw_ss_schedule' or 
		$post_type == 'mstw_ss_team' or
		$post_type == 'mstw_ss_game' or
		$post_type == 'mstw_ss_sport' or 
		$post_type == 'mstw_ss_venue' ) {
		
		echo '
			<style type="text/css">
				#misc-publishing-actions,
				#minor-publishing-actions{
					display:none;
				}
				div.view-switch {
					display: none;
				
				}
				div.tablenav-pages.one-page {
					display: none;
				}
				
			</style>
		';
		
	}
} //End: mstw_ss_hide_publishing_actions( )

//----------------------------------------------------------------
// Hide the list icons on the CPT edit (all) screens
//
add_action( 'admin_head-edit.php', 'mstw_ss_hide_list_icons' );

function mstw_ss_hide_list_icons( ) {

	$post_type = mstw_get_current_post_type( );
	//mstw_log_msg( 'in ... mstw_ss_hide_list_icons' );
	//mstw_log_msg( $post_type );
	if( $post_type == 'mstw_ss_schedule' or 
		$post_type == 'mstw_ss_team' or
		$post_type == 'mstw_ss_game' or //have a single_game.php template
		$post_type == 'mstw_ss_sport' or
		$post_type == 'mstw_ss_venue' ) {
		echo '
			<style type="text/css">
	
				div.view-switch {
					display: none;
				}
				
			</style>
		';
	}
} //End: mstw_ss_hide_publishing_actions( )

// ----------------------------------------------------------------
// Remove Quick Edit Menu and the "View Post" option
//	
add_filter( 'post_row_actions', 'mstw_ss_post_row_actions', 10, 2 );

function mstw_ss_post_row_actions( $actions, $post ) {
	
	$post_type = mstw_get_current_post_type( );
	
	if ( $post_type == 'mstw_ss_schedule' or 
		 $post_type == 'mstw_ss_team' or 
		 $post_type == 'mstw_ss_schedule' or  
		 $post_type == 'mstw_ss_venue' ) {
		 
		unset( $actions['inline hide-if-no-js'] );
		unset( $actions['view'] );
		
	} 
	else if ( $post_type == 'mstw_ss_game' ) {
		// we have a single game view
		unset( $actions['inline hide-if-no-js'] );
	}
	
	return $actions;
	
} //End: mstw_ss_post_row_actions( )

// ----------------------------------------------------------------
// Remove the Bulk Actions - Edit for all CPTs
//
add_filter( 'bulk_actions-edit-mstw_ss_schedule', 
			'mstw_ss_remove_bulk_actions' );
add_filter( 'bulk_actions-edit-mstw_ss_team', 
			'mstw_ss_remove_bulk_actions' );
add_filter( 'bulk_actions-edit-mstw_ss_game', 
			'mstw_ss_remove_bulk_actions' );
add_filter( 'bulk_actions-edit-mstw_ss_sport', 
			'mstw_ss_remove_bulk_actions' );
add_filter( 'bulk_actions-edit-mstw_ss_venue', 
			'mstw_ss_remove_bulk_actions' );

function mstw_ss_remove_bulk_actions( $actions ) {
	unset( $actions['edit'] );
	return $actions;
}

//----------------------------------------------------------------
// Removing default messages and taking control of all admin messages for CPTs
//
add_filter('post_updated_messages', 'mstw_ss_updated_messages');

function mstw_ss_updated_messages( $messages ) {
	
	$messages['mstw_ss_schedule'] = array( );
	$messages['mstw_ss_team'] = array( );
	$messages['mstw_ss_game'] = array( );
	$messages['mstw_ss_sport'] = array( );
	$messages['mstw_ss_venue'] = array( );
	
	//return $messages;
	
	//
	// Just keeping all this for fun. Ya never know ...
	//
	$messages['mstw_ss_schedule'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Schedule updated.', 'mstw-schedules-scoreboards' ),
		2 => __( 'Custom field updated.', 'mstw-schedules-scoreboards'),
		3 => __( 'Custom field deleted.', 'mstw-schedules-scoreboards' ),
		4 => __( 'Schedule updated.', 'mstw-schedules-scoreboards' ),
		5 => __( 'Schedule restored to revision', 'mstw-schedules-scoreboards' ),
		6 => __( 'Schedule published.', 'mstw-schedules-scoreboards' ),
		7 => __( 'Schedule saved.', 'mstw-schedules-scoreboards' ),
		8 => __( 'Schedule submitted.', 'mstw-schedules-scoreboards' ),
		9 => __( 'Schedule scheduled for publication.', 'mstw-schedules-scoreboards' ),
		10 => __( 'Schedule draft updated.', 'mstw-schedules-scoreboards' ),
	);
	
	$messages['mstw_ss_team'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Team updated.', 'mstw-schedules-scoreboards' ),
		2 => __( 'Custom field updated.', 'mstw-schedules-scoreboards'),
		3 => __( 'Custom field deleted.', 'mstw-schedules-scoreboards' ),
		4 => __( 'Team updated.', 'mstw-schedules-scoreboards' ),
		5 => __( 'Team restored to revision', 'mstw-schedules-scoreboards' ),
		6 => __( 'Team published.', 'mstw-schedules-scoreboards' ),
		7 => __( 'Team saved.', 'mstw-schedules-scoreboards' ),
		8 => __( 'Team submitted.', 'mstw-schedules-scoreboards' ),
		9 => __( 'Team scheduled for publication.', 'mstw-schedules-scoreboards' ),
		10 => __( 'Team draft updated.', 'mstw-schedules-scoreboards' ),
	);
	
	$messages['mstw_ss_game'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Game updated.', 'mstw-schedules-scoreboards' ),
		2 => __( 'Custom field updated.', 'mstw-schedules-scoreboards'),
		3 => __( 'Custom field deleted.', 'mstw-schedules-scoreboards' ),
		4 => __( 'Game updated.', 'mstw-schedules-scoreboards' ),
		5 => __( 'Game restored to revision', 'mstw-schedules-scoreboards' ),
		6 => __( 'Game published.', 'mstw-schedules-scoreboards' ),
		7 => __( 'Game saved.', 'mstw-schedules-scoreboards' ),
		8 => __( 'Game submitted.', 'mstw-schedules-scoreboards' ),
		9 => __( 'Game scheduled for publication.', 'mstw-schedules-scoreboards' ),
		10 => __( 'Game draft updated.', 'mstw-schedules-scoreboards' ),
	);
	
	$messages['mstw_ss_sport'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Sport updated.', 'mstw-schedules-scoreboards' ),
		2 => __( 'Custom field updated.', 'mstw-schedules-scoreboards'),
		3 => __( 'Custom field deleted.', 'mstw-schedules-scoreboards' ),
		4 => __( 'Sport updated.', 'mstw-schedules-scoreboards' ),
		5 => __( 'Sport restored to revision', 'mstw-schedules-scoreboards' ),
		6 => __( 'Sport published.', 'mstw-schedules-scoreboards' ),
		7 => __( 'Sport saved.', 'mstw-schedules-scoreboards' ),
		8 => __( 'Sport submitted.', 'mstw-schedules-scoreboards' ),
		9 => __( 'Sport scheduled for publication.', 'mstw-schedules-scoreboards' ),
		10 => __( 'Sport draft updated.', 'mstw-schedules-scoreboards' ),
	);
	
	$messages['mstw_ss_venue'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Venue updated.', 'mstw-schedules-scoreboards' ),
		2 => __( 'Custom field updated.', 'mstw-schedules-scoreboards'),
		3 => __( 'Custom field deleted.', 'mstw-schedules-scoreboards' ),
		4 => __( 'Venue updated.', 'mstw-schedules-scoreboards' ),
		5 => __( 'Venue restored to revision', 'mstw-schedules-scoreboards' ),
		6 => __( 'Venue published.', 'mstw-schedules-scoreboards' ),
		7 => __( 'Venue saved.', 'mstw-schedules-scoreboards' ),
		8 => __( 'Venue submitted.', 'mstw-schedules-scoreboards' ),
		9 => __( 'Venue scheduled for publication.', 'mstw-schedules-scoreboards' ),
		10 => __( 'Venue draft updated.', 'mstw-schedules-scoreboards' ),
	);

	return $messages;
} //End: mstw_ss_updated_messages()

//
// Just keeping all this for fun. Ya never know ...
//
add_filter( 'bulk_post_updated_messages', 'mstw_ss_bulk_post_updated_messages', 10, 2 );
function mstw_ss_bulk_post_updated_messages( $messages, $bulk_counts ) {

	$messages['mstw_ss_schedule'] = array( );
	$messages['mstw_ss_team'] = array( );
	$messages['mstw_ss_game'] = array( );
	$messages['mstw_ss_sport'] = array( );
	$messages['mstw_ss_venue'] = array( );
	
    $messages['mstw_ss_schedule'] = array(
        'updated'   => _n( '%s schedule updated.', '%s schedules updated.', $bulk_counts['updated'], 'mstw-schedules-scoreboards' ),
        'locked'    => _n( '%s schedule not updated, somebody is editing it.', '%s schedules not updated, somebody is editing them.', $bulk_counts['locked'], 'mstw-schedules-scoreboards' ),
        'deleted'   => _n( '%s schedule permanently deleted.', '%s schedules permanently deleted.', $bulk_counts['deleted'], 'mstw-schedules-scoreboards' ),
        'trashed'   => _n( '%s schedule moved to the Trash.', '%s schedules moved to the Trash.', $bulk_counts['trashed'], 'mstw-schedules-scoreboards' ),
        'untrashed' => _n( '%s schedule restored from the Trash.', '%s schedules restored from the Trash.', $bulk_counts['untrashed'], 'mstw-schedules-scoreboards' ),
    );
	
	$messages['mstw_ss_team'] = array(
        'updated'   => _n( '%s team updated.', '%s teams updated.', $bulk_counts['updated'], 'mstw-schedules-scoreboards' ),
        'locked'    => _n( '%s team not updated, somebody is editing it.', '%s teams not updated, somebody is editing them.', $bulk_counts['locked'], 'mstw-schedules-scoreboards' ),
        'deleted'   => _n( '%s team permanently deleted.', '%s teams permanently deleted.', $bulk_counts['deleted'], 'mstw-schedules-scoreboards' ),
        'trashed'   => _n( '%s team moved to the Trash.', '%s teams moved to the Trash.', $bulk_counts['trashed'], 'mstw-schedules-scoreboards' ),
        'untrashed' => _n( '%s team restored from the Trash.', '%s teams restored from the Trash.', $bulk_counts['untrashed'], 'mstw-schedules-scoreboards' ),
    );
	
	$messages['mstw_ss_game'] = array(
        'updated'   => _n( '%s game updated.', '%s games updated.', $bulk_counts['updated'], 'mstw-schedules-scoreboards' ),
        'locked'    => _n( '%s game not updated, somebody is editing it.', '%s games not updated, somebody is editing them.', $bulk_counts['locked'], 'mstw-schedules-scoreboards' ),
        'deleted'   => _n( '%s game permanently deleted.', '%s games permanently deleted.', $bulk_counts['deleted'], 'mstw-schedules-scoreboards' ),
        'trashed'   => _n( '%s game moved to the Trash.', '%s games moved to the Trash.', $bulk_counts['trashed'], 'mstw-schedules-scoreboards' ),
        'untrashed' => _n( '%s game restored from the Trash.', '%s games restored from the Trash.', $bulk_counts['untrashed'], 'mstw-schedules-scoreboards' ),
    );
	
	$messages['mstw_ss_sport'] = array(
        'updated'   => _n( '%s sport updated.', '%s sports updated.', $bulk_counts['updated'], 'mstw-schedules-scoreboards' ),
        'locked'    => _n( '%s sport not updated, somebody is editing it.', '%s sports not updated, somebody is editing them.', $bulk_counts['locked'], 'mstw-schedules-scoreboards' ),
        'deleted'   => _n( '%s sport permanently deleted.', '%s sports permanently deleted.', $bulk_counts['deleted'], 'mstw-schedules-scoreboards' ),
        'trashed'   => _n( '%s sport moved to the Trash.', '%s sports moved to the Trash.', $bulk_counts['trashed'], 'mstw-schedules-scoreboards' ),
        'untrashed' => _n( '%s sport restored from the Trash.', '%s sports restored from the Trash.', $bulk_counts['untrashed'], 'mstw-schedules-scoreboards' ),
    );
	
	$messages['mstw_ss_venue'] = array(
        'updated'   => _n( '%s venue updated.', '%s venues updated.', $bulk_counts['updated'], 'mstw-schedules-scoreboards' ),
        'locked'    => _n( '%s venue not updated, somebody is editing it.', '%s venues not updated, somebody is editing them.', $bulk_counts['locked'], 'mstw-schedules-scoreboards' ),
        'deleted'   => _n( '%s venue permanently deleted.', '%s venues permanently deleted.', $bulk_counts['deleted'], 'mstw-schedules-scoreboards' ),
        'trashed'   => _n( '%s venue moved to the Trash.', '%s venues moved to the Trash.', $bulk_counts['trashed'], 'mstw-schedules-scoreboards' ),
        'untrashed' => _n( '%s venue restored from the Trash.', '%s venues restored from the Trash.', $bulk_counts['untrashed'], 'mstw-schedules-scoreboards' ),
    );

    return $messages;

} //End: mstw_ss_bulk_post_updated_messages()



//----------------------------------------------------------------	
// Enqueue styles and scripts for the color & date pickers. 
//
add_action( 'admin_enqueue_scripts', 'mstw_ss_admin_enqueue_scripts' );

function mstw_ss_admin_enqueue_scripts( $hook_suffix ) {
	global $typenow;
	
	//mstw_log_msg( '$hook_suffix: ' . $hook_suffix );
	
	// This function loads in the required media files for the media manager.
	//wp_enqueue_media();
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery');
	
	wp_enqueue_media();
	
	wp_enqueue_script( 'another-media', MSTW_SS_JS_URL . '/another-media.js', null, false, true );
	
	wp_enqueue_style('thickbox');
	
	// Register, localize and enqueue our custom JS.
	
	//enqueue the color-picker script & stylesheet
	// only if it's the settings page
	if ( $hook_suffix == 'schedules-scoreboards_page_mstw-ss-settings' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'mstw-ss-color-picker', MSTW_SS_JS_URL . '/ss-color-settings.js', array( 'wp-color-picker' ), false, true );
		wp_enqueue_script( 'mstw-ss-confirm-reset', MSTW_SS_JS_URL . '/ss-confirm-reset.js', null, false, true );
	}
	
	//mstw_log_msg( 'in mstw_ss_admin_enqueue_scripts ... ' );
	//mstw_log_msg( '$hook_suffix: ' . $hook_suffix );
	//mstw_log_msg( '$type: ' . $typenow );
	
	//unfortunately post.php is the available hook
	if ( $hook_suffix == 'post.php' || $hook_suffix == 'post-new.php' ) {
		//enqueue the datepicker script & stylesheet if it's the game edit page 
		if( $typenow == 'mstw_ss_game' ) {
			wp_enqueue_script( 'mstw-ss-date-picker', MSTW_SS_JS_URL . '/ss-date-settings.js', array( 'jquery-ui-datepicker' ), false, true );
			wp_enqueue_style('jquery-style', MSTW_SS_CSS_URL . '/jquery-ui.css' );
		}
		//enqueue the datepicker script & stylesheet if it's the game edit page 
		else if ( $typenow == 'mstw_ss_team' ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'mstw-ss-team-color-picker', MSTW_SS_JS_URL . '/ss-team-color-settings.js', array( 'wp-color-picker' ), false, true );
		}
	}
	
	//enqueue the datepicker script & stylesheet
	// only if it's the game edit page - unfortunately post.php is the available hook
	else if ( $hook_suffix == 'post.php' || $hook_suffix == 'post-new.php' ) {
		wp_enqueue_script( 'mstw-ss-date-picker', MSTW_SS_JS_URL . '/ss-date-settings.js', array( 'jquery-ui-datepicker' ), false, true );
		wp_enqueue_style('jquery-style', MSTW_SS_CSS_URL . '/jquery-ui.css' );
	}
	
    // Find the full path to the plugin's css file 
	$mstw_ss_style_url = MSTW_SS_CSS_URL . '/mstw-ss-styles.css';
	$mstw_ss_style_file = MSTW_SS_CSS_DIR . '/mstw-ss-styles.css';
	
	wp_register_style( 'mstw_ss_style', $mstw_ss_style_url );
	
	// If stylesheet exists, enqueue the style
	if ( file_exists( $mstw_ss_style_file ) ) {	
		wp_enqueue_style( 'mstw_ss_style' );			
	} 
	
} //End: mstw_ss_admin_enqueue_scripts( )

// ----------------------------------------------------------------
// Test stuff
//
add_action('admin_print_scripts', 'mstw_ss_admin_print_scripts');
add_action('admin_print_styles', 'mstw_ss_admin_print_styles');

function mstw_ss_admin_print_scripts() {
	//wp_enqueue_script('media-upload');
	wp_enqueue_script( 'media-upload', MSTW_SS_JS_URL . '/media-upload.js', array( 'thickbox', 'jquery' ), false, true );
	//wp_enqueue_script('thickbox');
	//wp_enqueue_script('jquery');
}

function mstw_ss_admin_print_styles() {
	wp_enqueue_style('thickbox');
}

// ----------------------------------------------------------------
// Add the main menu item
//
add_action( 'admin_menu', 'mstw_ss_admin_menu' );

function mstw_ss_admin_menu( ) {
	
	if ( mstw_user_has_plugin_rights( 'ss' ) ) {	
		//Top Level Menu for Schedules & Scoreboards
		add_menu_page( 'Schedules & Scoreboards', //$page_title, 
					   'Schedules & Scoreboards', //$menu_title, 
					   'read', //$capability, 
					   'edit.php?post_type=mstw_ss_game', //'mstw-ss-main-menu', //$menu_slug, 
					   null, //'mstw_ss_admin_ui', //$function, 
					   MSTW_SS_IMAGES_URL . '/mstw-admin-menu-icon.png', //$menu_icon
					   "58.55"
					 );
		//Games			 
		add_submenu_page( 	'edit.php?post_type=mstw_ss_game', 
								__( 'Games', 'mstw-schedules-scoreboards' ), //page title
								__( 'Games', 'mstw-schedules-scoreboards' ), //menu title
								'read', // Capability required to see this option.
								'edit.php?post_type=mstw_ss_game', // Slug name to refer to this menu
								null							
						); // Callback to output content
		//Teams			 
		$teams_menu = add_submenu_page( 	'edit.php?post_type=mstw_ss_game', 
								__( 'Teams', 'mstw-schedules-scoreboards' ), //page title
								__( 'Teams', 'mstw-schedules-scoreboards' ), //menu title
								'read', // Capability required to see this option.
								'edit.php?post_type=mstw_ss_team', // Slug name to refer to this menu
								null							
						); // Callback to output content

		//Sports			 
		$sports_menu = add_submenu_page( 	'edit.php?post_type=mstw_ss_game', 
								__( 'Sports', 'mstw-schedules-scoreboards' ), //page title
								__( 'Sports', 'mstw-schedules-scoreboards' ), //menu title
								'read', // Capability required to see this option.
								'edit.php?post_type=mstw_ss_sport', // Slug name to refer to this menu
								null							
						); // Callback to output content
		
		//Schedules				
		add_submenu_page( 	'edit.php?post_type=mstw_ss_game', 
								__( 'Schedules', 'mstw-schedules-scoreboards' ), //page title
								__( 'Schedules', 'mstw-schedules-scoreboards' ), //menu title
								'read', // Capability required to see this option.
								'edit.php?post_type=mstw_ss_schedule', // Slug name to refer to this menu
								null							
						); // Callback to output content
		
		//Venues				
		add_submenu_page( 	'edit.php?post_type=mstw_ss_game', 
								__( 'Venues', 'mstw-schedules-scoreboards' ), //page title
								__( 'Venues', 'mstw-schedules-scoreboards' ), //menu title
								'read', // Capability required to see this option.
								'edit.php?post_type=mstw_ss_venue', // Slug name to refer to this menu
								null							
						); // Callback to output content
						
		//Settings				
		$settings_page = add_submenu_page( 	
								'edit.php?post_type=mstw_ss_game', 
								__( 'Settings', 'mstw-schedules-scoreboards' ), //page title
								__( 'Settings', 'mstw-schedules-scoreboards' ), //menu title
								'read', //capability required to see this menu item
								'mstw-ss-settings', //slug name to refer to this menu
								'mstw_ss_settings_page' ////callback to output page content							
										); 
										
		add_action( "load-$settings_page", 'mstw_ss_settings_help' );
		
		
		//CSV Import
		$plugin = new MSTW_SS_ImporterPlugin;
		//$capability = apply_filters( 'mstw_gs_user_capability', 'edit_others_posts', 'csv_import_menu_item' );
		
		add_submenu_page( 	'edit.php?post_type=mstw_ss_game', 
								__( 'CSV Import', 'mstw-schedules-scoreboards' ), //page title
								__( 'CSV Import', 'mstw-schedules-scoreboards' ), //menu title
								'manage_options', // Capability required to see this option.
								//'edit.php?post_type=mstw_ss_schedule', // Slug name to refer to this menu
								'mstw_ss_import_csv_page',
								array( $plugin, 'form' )	// Callback to output content						
						); 
					
	} //End: if( current_user_can( )

} //End: mstw_ss_admin_menu()		 

//-----------------------------------------------------------------
// Register the settings - called by admin_init hook
// 

function mstw_ss_admin_init( ) {
	//If options do not exist, add them
	if( false == get_option( 'mstw_ss_options' ) ) {    
		add_option( 'mstw_ss_options' );  
	}  
	if( false == get_option( 'mstw_ss_dtg_options' ) ) {    
		add_option( 'mstw_ss_dtg_options' );  
	} 
	if( false == get_option( 'mstw_ss_color_options' ) ) {    
		add_option( 'mstw_ss_color_options' );  
	}
	if( false == get_option( 'mstw_ss_venue_options' ) ) {    
		add_option( 'mstw_ss_venue_options' );  
	}
	
	// Data fields & columns settings
	mstw_ss_data_fields_setup( );
	
	// Date & time format settings
	mstw_ss_dtg_format_setup( );
	
	// Colors settings
	mstw_ss_colors_setup( );
	
	// Venue settings
	mstw_ss_venue_setup( );
	
	// Scoreboard settings
	mstw_ss_scoreboard_setup( );
	
	register_setting(
		'mstw_ss_options',  		// settings group name
		'mstw_ss_options',  		// options (array) to validate
		'mstw_ss_validate_main'  	// validation function
		);
		
	register_setting(
		'mstw_ss_dtg_options',  	// settings group name
		'mstw_ss_dtg_options',  	// options (array) to validate
		'mstw_ss_validate_dtg'  	// validation function
		);
		
	register_setting(
		'mstw_ss_color_options',  	// settings group name
		'mstw_ss_color_options', 	// options (array) to validate
		'mstw_ss_validate_colors'   // validation function
		);
		
	register_setting(
		'mstw_ss_venue_options',  	// settings group name
		'mstw_ss_venue_options', 	// options (array) to validate
		'mstw_ss_validate_venues'   // validation function
		);
		
	register_setting(
		'mstw_ss_scoreboard_options',  	// settings group name
		'mstw_ss_scoreboard_options', 	// options (array) to validate
		'mstw_ss_validate_scoreboards'   // validation function
		);	
		
} //End: mstw_ss_admin_init()

//-----------------------------------------------------------------
// Add some custom styles for the SS pages
//		- Custom MSTW icon to CPT pages
//		- Hide the date filter in the 'list all' admin pages
// 		- Add a new 'warning' admin notice style
//

add_action('admin_head', 'mstw_ss_admin_head');

function mstw_ss_admin_head() { 
	?>
	<style type="text/css">
		div#icon-edit.icon32.icon32-posts-mstw_ss_game,
		div#icon-edit.icon32.icon32-posts-mstw_ss_team,
		div#icon-edit.icon32.icon32-posts-mstw_ss_schedule
		{
			background: url( <?php MSTW_SS_IMAGES_URL . '/mstw-logo-32x32.png' ?> );
			background-repeat: no-repeat;
			background-color: transparent;
		}
	</style> 
	
	<?php
	// remove the data filters for all post types
	$post_type = mstw_get_current_post_type( );

	if ( $post_type == 'mstw_ss_schedule' or $post_type == 'mstw_ss_team'
		 or $post_type == 'mstw_ss_sport' or $post_type == 'mstw_ss_sport' 
		 or $post_type == 'mstw_ss_venue' ) {
		 
		?>
		<style type="text/css">
			.tablenav select[name=m], .tablenav input#post-query-submit.button {
				display: none;
			}      
		</style>
		<?php
	}
	else if ( $post_type == 'mstw_ss_game' ) {
		?>
		<style type="text/css">
			.tablenav select[name=m] {
				display: none;
			}      
		</style>
		<?php
	}
	
	?>
	<!-- new SS admin notice 'warning' style -->
	<style type="text/css">
		div.warning {
			margin: 5px 0 15px;
			border-left: 4px solid #FEB101;
			padding: 1px 12px;
			background-color: #FFF;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
			box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
		}
	</style>
	
<?php 
} //End: mstw_ss_admin_head( )


?>