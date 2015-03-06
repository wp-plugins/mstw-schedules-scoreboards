<?php
/*
Plugin Name: MSTW Schedules & Scoreboards
Plugin URI: http://wordpress.org/extend/plugins/
Description: Replaces the MSTW Game Schedules plugin. Includes game schedules and scoreboards.
Version: 1.2
Author: Mark O'Donnell
Author URI: http://shoalsummitsolutions.com
Text Domain: mstw-schedules-scoreboards
*/

/*---------------------------------------------------------------------------
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
 *	along with this program. If not, see <http://www.gnu.org/licenses/>..
*-------------------------------------------------------------------------*/

//------------------------------------------------------------------------
// DEFINE SOME GLOBALS TO MAKE LIFE EASIER
//
include_once( WP_PLUGIN_DIR . '/mstw-schedules-scoreboards/includes/mstw-ss-globals.php' );

//------------------------------------------------------------------------
// Initialize the plugin ... include files, define globals, register CPTs
//
add_action( 'init', 'mstw_ss_init' );

function mstw_ss_init( ) {
	
	//------------------------------------------------------------------------
	// "Helper functions" used throughout the MSTW plugin family
	//
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-utility-functions.php' );

	//------------------------------------------------------------------------
	// "Helper functions" that are MSTW Schedules & Scoreboards specific
	//
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-utility-functions.php' );
	
	//------------------------------------------------------------------------
	// Functions for MSTW schedule table shortcode and widget
	//
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-schedule-table.php' );
	
	//------------------------------------------------------------------------
	// Functions for MSTW venue table shortcode
	//
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-venue-table.php' );
	
	//------------------------------------------------------------------------
	// Functions for MSTW countdown timer shortcode
	//
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-countdown-timer.php' );
	
	//------------------------------------------------------------------------
	// Functions for MSTW schedule slider shortcode
	//
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-schedule-slider.php' );
	
	//------------------------------------------------------------------------
	// Functions for MSTW scoreboard shortcode
	//
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-scoreboard.php' );
	
	//--------------------------------------------------------------------------------
	// REGISTER THE MSTW SCHEDULES & SCOREBOARDS CUSTOM POST TYPES & TAXONOMIES
	//	mstw_ss_game, mstw_ss_schedule, mstw_ss_team
	//
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-cpts.php' );
	mstw_ss_register_cpts( );

	//------------------------------------------------------------------------
	// If an admin screen, load the admin functions (gotta have 'em)
	
	if ( is_admin( ) )
		include_once ( MSTW_SS_INCLUDES_DIR . '/mstw-ss-admin.php' );
		
		
	//mstw_log_msg( 'in mstw_ss_init ... taxonomies:' );
	//mstw_log_msg( get_taxonomies( ) );

}

// ----------------------------------------------------------------
// filter so single-player template does  not need to be in the theme directory
//
	add_filter( "single_template", "mstw_ss_single_game_template" );
	
	function mstw_ss_single_game_template( $single_template ) {
		 global $post;

		 if ($post->post_type == 'mstw_ss_game') {
			  $single_template = dirname( __FILE__ ) . '/templates/single-game.php';  
		 }
		
		 return $single_template;
		 
	} //End: mstw_ss_single_game_template()

//------------------------------------------------------------------------
// Check for the right version of WP on plugin activation
//
register_activation_hook( MSTW_SS_PLUGIN_FILE, 'mstw_ss_register_activation_hook' );

function mstw_ss_register_activation_hook( ) {
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-utility-functions.php' );
	mstw_requires_wordpress_version( '4.0' );
	mstw_ss_add_user_roles( );
} //End: mstw_ss_register_activation_hook( )

//------------------------------------------------------------------------
// Creates the MSTW Schedules & Scoreboards roles and adds the MSTW capabilities
//		to those roles and the WP administrator and editor roles
//
function mstw_ss_add_user_roles( ) {
	//include_once( MSTW_SS_INCLUDES_DIR . '/mstw-utility-functions.php' );
	
	//
	// mstw_admin role - can do everything in all MSTW plugins
	//
	
	//This allows a reset of capabilities for development
	remove_role( 'mstw_admin' );
	
	$result = 	add_role( 'mstw_admin',
						  __( 'MSTW Admin', 'mstw-schedules-scoreboards' ),
						  array( 'manage_mstw_plugins'  => true,
								 'edit_posts' => true
								 //true allows; use false to deny
								) 
						 );
						 
	if ( $result != null ) {
		$result->add_cap( 'view_mstw_menus' );
		mstw_ss_add_caps( $result, null, 'schedule', 'schedules' );
		mstw_ss_add_caps( $result, null, 'team', 'teams' );
		mstw_ss_add_caps( $result, null, 'game', 'games' );
		mstw_ss_add_caps( $result, null, 'sport', 'sports' );
		mstw_ss_add_caps( $result, null, 'venue', 'venues' );
	}
	else 
		mstw_log_msg( "Oops, failed to add MSTW Admin role. Already exists?" );
	
	//
	// mstw_ss_admin role - can do everything in Schedules & Scoreboards plugin
	//
	
	//This allows a reset of capabilities for development
	remove_role( 'mstw_ss_admin' );
	
	$result = 	add_role( 'mstw_ss_admin',
						  __( 'MSTW Schedules & Scoreboards Admin', 'mstw-schedules-scoreboards' ),
						  array( 'manage_mstw_schedules'  => true, 
								  'read' => true
								  //true allows; use false to deny
								) 
						 );
	
	if ( $result != null ) {
		$result->add_cap( 'view_mstw_ss_menus' );
		mstw_ss_add_caps( $result, null, 'schedule', 'schedules' );
		mstw_ss_add_caps( $result, null, 'team', 'teams' );
		mstw_ss_add_caps( $result, null, 'game', 'games' );
		mstw_ss_add_caps( $result, null, 'sport', 'sports' );
		mstw_ss_add_caps( $result, null, 'venue', 'venues' );
	}
	else {
		mstw_log_msg( "Oops, failed to add MSTW Schedules & Scoreboards Admin role. Already exists?" );
	}
	
	//
	// site admins can play freely
	//
	$role = get_role( 'administrator' );
	
	mstw_ss_add_caps( $role, null, 'schedule', 'schedules' );
	mstw_ss_add_caps( $role, null, 'team', 'teams' );
	mstw_ss_add_caps( $role, null, 'game', 'games' );
	mstw_ss_add_caps( $role, null, 'sport', 'sports' );
	mstw_ss_add_caps( $result, null, 'venue', 'venues' );
	
	//
	// site editors can play freely
	//
	$role = get_role( 'editor' );
	
	mstw_ss_add_caps( $role, null, 'schedule', 'schedules' );
	mstw_ss_add_caps( $role, null, 'team', 'teams' );
	mstw_ss_add_caps( $role, null, 'game', 'games' );
	mstw_ss_add_caps( $role, null, 'sport', 'sports' );
	mstw_ss_add_caps( $result, null, 'venue', 'venues' );
	
} //End: mstw_ss_add_user_roles( )

//------------------------------------------------------------------------
// Adds the MSTW capabilities to either the $role_obj or $role_name using
//		the custom post type names (from the capability_type arg in
//		register_post_type( )
//
//	ARGUMENTS:
//		$role_obj: a WP role object to which to add the MSTW capabilities. Will
//					be used of $role_name is none (the default)
//		$role_name: a WP role name to which to add the MSTW capabilities. Will
//					be used if present (not null)
//		$cpt: the custom post type for the capabilities 
//				( map_meta_cap is set in register_post_type() )
//		$cpt_s: the plural of the custom post type
//				( $cpt & $cpt_s must match the capability_type argument
//					in register_post_type( ) )
//	RETURN: none
//
function mstw_ss_add_caps( $role_obj = null, $role_name = null, $cpt, $cpt_s ) {
	$cap = array( 'edit_', 'read_', 'delete_' );
	$caps = array( 'edit_', 'edit_others_', 'publish_', 'read_private_', 'delete_', 'delete_published_', 'delete_others_', 'edit_private_', 'edit_published_' );
	
	if ( $role_name != null ) {
		$role_obj = get_role( $role_name );
	}
	
	if( $role_obj != null ) {
		//'singular' capabilities
		foreach( $cap as $c ) {
			$role_obj -> add_cap( $c . $cpt );
		}
		
		//'plural' capabilities
		foreach ($caps as $c ) {
			$role_obj -> add_cap( $c . $cpt_s );
		}
		
		$role_obj -> add_cap( 'read' );
	}
	else {
		$role_name = ( $role_name == null ) ? 'null' : $role_name;
		mstw_log_msg( 'Bad args passed to mstw_ss_add_caps( ). $role_name = ' . $role_name . ' and $role_obj = null' );
	}
	
} //End: mstw_ss_add_caps( )

//------------------------------------------------------------------------
// Queue up the necessary CSS  
//
	add_action( 'wp_enqueue_scripts', 'mstw_ss_enqueue_scripts' );
	//add_action( 'wp_head', 'mstw_ss_enqueue_scripts' );
	
	function mstw_ss_enqueue_scripts( ) {
		
		// Find the full path to the plugin's css file 
		//$mstw_ss_style_url = plugins_url('/css/mstw-ss-styles.css', __FILE__);
		$mstw_ss_style_url = MSTW_SS_CSS_URL . '/mstw-ss-styles.css';
		//$mstw_ss_style_file = WP_PLUGIN_DIR . '/game-schedules/css/mstw-gs-styles.css';
		$mstw_ss_style_file = MSTW_SS_CSS_DIR . '/mstw-ss-styles.css';
		
		//wp_register_style( 'mstw_ss_style', plugins_url('/css/mstw-gs-styles.css', __FILE__ ) );
		wp_register_style( 'mstw_ss_style', $mstw_ss_style_url );
	
		// If stylesheet exists, enqueue the style
		if ( file_exists( $mstw_ss_style_file ) ) {	
			wp_enqueue_style( 'mstw_ss_style' );			
		} 

		$mstw_ss_custom_stylesheet = get_stylesheet_directory( ) . '/mstw-ss-custom-styles.css';
		
		mstw_log_msg( 'custom stylesheet path: ' . $mstw_ss_custom_stylesheet );
		
		if ( file_exists( $mstw_ss_custom_stylesheet ) ) {
			$mstw_ss_custom_stylesheet_url = get_stylesheet_directory_uri( ) . '/mstw-ss-custom-styles.css';
			mstw_log_msg( 'custom stylesheet uri: ' . $mstw_ss_custom_stylesheet_url );
			wp_register_style( 'mstw_ss_custom_style', $mstw_ss_custom_stylesheet_url );
			wp_enqueue_style( 'mstw_ss_custom_style' );
		}
		else {
			mstw_log_msg( 'custom stylesheet: ' . $mstw_ss_custom_stylesheet . ' does not exist.' );
		}
		
		//javascript for slider next and prev arrows
		wp_enqueue_script( 'ss-slider', MSTW_SS_JS_URL . '/ss-slider.js', array('jquery'), false, true );
		
		//javascript for ticker next and prev arrows
		wp_enqueue_script( 'ss-ticker', MSTW_SS_JS_URL . '/ss-ticker.js', array('jquery'), false, true );
		
	} //end mstw_ss_enqueue_styles( )

// ----------------------------------------------------------------
// Set up localization
//
add_action( 'plugins_loaded', 'mstw_ss_plugins_loaded' );
	
function mstw_ss_plugins_loaded( ) {
	//support translation with the 'mstw-schedules-scoreboards'
	load_plugin_textdomain( 'mstw-schedules-scoreboards', false, MSTW_SS_PLUGIN_DIR . '/lang/' );
	
	//register scoreboard taxonomy for mstw_ss_game CPT
	register_taxonomy_for_object_type( 'mstw_ss_scoreboard', 'mstw_ss_game' );
} 

//------------------------------------------------------------------------
// Add some links to the plugins page
//
add_filter( 'plugin_action_links', 'mstw_ss_plugin_action_links', 10, 2 );

function mstw_ss_plugin_action_links( $links, $file ) {
	static $this_plugin;

    if ( !$this_plugin ) {
        $this_plugin = plugin_basename( __FILE__ );
    }

    if ( $file == $this_plugin ) {
        // The "page" query string value must be equal to the slug
        // of the Settings admin page we defined earlier
		
		$site_url = site_url( '/wp-admin/edit.php?post_type=mstw_ss_game&page=mstw-ss-settings' );
		
		$settings_link = "<a href='$site_url'>Settings</a>";
		
        array_unshift( $links, $settings_link );
    }

    return $links;
}

// ----------------------------------------------------------------
// Add the CSS code from the settings/options to the header
//		mstw_ss_add_css_to_head is in mstw-ss-utility-functions.php
//
add_filter( 'wp_head', 'mstw_ss_add_css_to_head');

// ----------------------------------------------------------------
// register the schedule table and countdown timer widgets
// 
add_action( 'widgets_init', 'mstw_ss_register_widgets' );

function mstw_ss_register_widgets() {

	// include classes for the schedule table and countdown timer widgets
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-schedule-widget.php' );
	include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-cdt-widget.php' );
	
	//register the widgets
    register_widget( 'mstw_ss_sched_widget' );
	register_widget( 'mstw_ss_cdt_widget' );
}

?>