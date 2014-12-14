<?php
 /*---------------------------------------------------------------------------
 *	mstw-ss-countdown-timer.php
 *	Contains the code for the MSTW Schedules & Scoreboards countdown timer
 *		shortcode [mstw_ss_counddown]
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
 *-------------------------------------------------------------------------*/

//--------------------------------------------------------------------------------
// Add the shortcode handler, which will create the Countdown Timer on the 
//	user side.
// Handles the shortcode parameters, if there were any, then calls 
// 	mstw_ss_build_schedule_table( ) to create the output
// 
add_shortcode( 'mstw_countdown_timer', 'mstw_ss_countdown_handler' );
// ------------------------------------------------------------------------------
// The countdown shortcode handler, parses the args, 
// 		and calls mstw_ss_build_countdown(), which creates the output
// ---------------------------------------------------------------------------
function mstw_ss_countdown_handler( $atts ){

	// get the options set in the admin display settings screen
	$base_options = get_option( 'mstw_ss_options' );
	$dtg_options = get_option( 'mstw_ss_dtg_options' );
	$options = array_merge( $base_options, (array)$dtg_options );
	
	// Remove all keys with empty values
	foreach ( $options as $k=>$v ) {
		//if ( $k == 'show_date' )
			//$output .= $k . '=> ' . $v;
		if( $v == '' ) {
			//$output .= 'unset: ' . $k . '=> ' . $v;
			unset( $options[$k] );
			
		}
	}
		
	// and merge them with the defaults
	$defaults = array_merge( mstw_ss_get_defaults( ), mstw_ss_get_dtg_defaults( ) );
	$args = wp_parse_args( $options, $defaults );

	// then merge the parameters passed to the shortcode with the result									
	$attribs = shortcode_atts( $args, $atts );
	
	// All the heavy lifting is done in mstw_ss_build_countdown()
	$mstw_ss_countdown = mstw_ss_build_countdown( $attribs  );
	
	return $mstw_ss_countdown;
}
 ?>