<?php
/*----------------------------------------------------------------------------
 * mstw-ss-venue-settings.php
 *	All functions for the MSTW Schedules & Scoreboards Plugin's 
 *		venue display settings.
 *	Loaded by mstw-ss-admin.php AFTER (and depends upon) mstw-ss-settings.php 
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
// Set up venues format tab
//
function mstw_ss_venue_setup( ) {
	//mstw_log_msg( "in mstw_ss_venue_setup ..." );
	mstw_ss_venue_section_setup( );
} //End: mstw_ss_venue_setup()


//----------------------------------------------------------
// Admin format settings section
//
function mstw_ss_venue_section_setup( ) {

	$display_on_page =  'mstw-ss-venue-settings';
	$page_section = 'mstw-ss-admin-venue-settings';
	
	$options = wp_parse_args( get_option( 'mstw_ss_venue_options' ), mstw_ss_get_venue_defaults( ) );
	
	// Column Visibility and Label Section
	add_settings_section(
		$page_section,
		__( 'Venues Display Settings', 'mstw-schedules-scoreboards' ),
		'mstw_ss_admin_venues_inst',
		$display_on_page
	);
	
	$arguments = array(
					array( 	// SHOW/HIDE INSTRUCTIONS ABOVE TABLE
							'type'    => 'checkbox', 
							'id' => 'show_instructions',
							'name'	=> 'mstw_ss_venue_options[show_instructions]',
							'value'	=> $options['show_instructions'],
							'desc'	=> __( 'Show instructions above venues table.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Show Instructions:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),			
					array( 	// Instructions above venues table
							'type'    => 'text', 
							'id' => 'instructions',
							'name'	=> 'mstw_ss_venue_options[instructions]',
							'value'	=> $options['instructions'],
							'desc'	=> __( 'These instructions will appear above the venues table.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Venues Table Instructions::', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
						array( 	// VENUE column label
							'type'    => 'text', 
							'id' => 'venue_label',
							'name' => 'mstw_ss_venue_options[venue_label]',
							'value' => $options['venue_label'],
							'desc'	=> __( 'Set label for venue column. NOTE that this column cannot be hidden. (Default: &quot;Venue&quot;).', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Venue Column Label:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
						array( 	// VENUE LINK
							'type'    => 'select-option', 
							'id' => 'show_venue_link',
							'name'	=> 'mstw_ss_venue_options[show_venue_link]',
							'value'	=> $options['show_venue_link'],
							'desc'	=> __( 'Either an address or a custom map URL must be specified to add a link to a map. A venue URL must be specified to add a link to a venue. (Default: No Link)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Add Link from Venue Column:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => array( 'No Link' => 0,
												'Add Link to Map' => 1,
												'Add Link to Venue' => 2,
											  ),
							'page' => $display_on_page,
							'section' => $page_section,
						),
						
						array( 	// Show/hide ADDRESS column
							'type'    => 'checkbox', 
							'id' => 'show_address',
							'name'	=> 'mstw_ss_venue_options[show_address]',
							'value'	=> $options['show_address'],
							'desc'	=> __( 'Show or hide the Address column. (Default: Show)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Show Address Column:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),	

						array( 	// ADDRESS column label
							'type'    => 'text', 
							'id' => 'address_label',
							'name'	=> 'mstw_ss_venue_options[address_label]',
							'value'	=> $options['address_label'],
							'desc'	=> __( 'Set label for address column. (Default: &quot;Address&quot;)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Address Column Label:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
						array( 	// Show/hide MAP column
							'type'    => 'checkbox', 
							'id' => 'show_map',
							'name'	=> 'mstw_ss_venue_options[show_map]',
							'value'	=> $options['show_map'],
							'desc'	=> __( 'Show or hide the Map column. (Default: Show)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Show Map Column:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),	
						array( 	// MAP column label
							'type'    => 'text', 
							'id' => 'map_label',
							'name'	=> 'mstw_ss_venue_options[map_label]',
							'value'	=> $options['map_label'],
							'desc'	=> __( 'Set label for map column. (Default: &quot;Map (Click for larger view)&quot;)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Map Column Label:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),	
						array( 	// Color for venue marker on map
							'type'    => 'select-option', 
							'id' => 'marker_color',
							'name'	=> 'mstw_ss_venue_options[marker_color]',
							'value'	=> $options['marker_color'],
							'desc'	=> __( 'Marker color on map in venues table. Standard Google Maps colors. (Default: Blue)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Map Marker Color:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => array( 'black' => 'black',
											'blue' => 'blue',
											'brown' => 'brown',
											'gray' => 'gray',
											'green' =>  'green',
											'orange' => 'orange',
											'purple' =>  'purple',
											'red' => 'red',
											'white' => 'white',
											),
							'page' => $display_on_page,
							'section' => $page_section,
						),
						array( 	// MAP ICON WIDTH in table
								'type'    => 'text', 
								'id' => 'map_icon_width',
								'name'	=> 'mstw_ss_venue_options[map_icon_width]',
								'value'	=> $options['map_icon_width'],
								'desc'	=> __( 'Width in pixels of map icon in table (Default: 250)', 'mstw-schedules-scoreboards' ),
								'title'	=> __( 'Map Icon Width:', 'mstw-schedules-scoreboards' ),
								'default' => '',
								'options' => '',
								'page' => $display_on_page,
								'section' => $page_section,
							),
						array( 	// MAP ICON HEIGHT in table
							'type'    => 'text', 
							'id' => 'map_icon_height',
							'name'	=> 'mstw_ss_venue_options[map_icon_height]',
							'value'	=> $options['map_icon_height'],
							'desc'	=> __( 'Height in pixels of map icon in table (Default: 75)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Map Icon Height:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),	
					); //End of $arguments array
					
	mstw_build_settings_screen( $arguments );
	
} //End: mstw_ss_admin_section_setup( )

//----------------------------------------------------------------------	
// Venues settings section instructions
//
function mstw_ss_admin_venues_inst( ) {

	echo '<p>' . __( 'Enter the game venues table settings. ', 'mstw-loc-domain' ) . '</p>';
	
} //End: mstw_ss_admin_venues_inst( )

//-------------------------------------------------------------------------------
// Validate the user data entries in Venues settings tab
// 
function mstw_ss_validate_venues( $input ) {
	//mstw_log_msg( 'divider' );
	//mstw_log_msg( 'in mstw_ss_validate_venues ...' );
	//mstw_log_msg( '$input[]' );
	//mstw_log_msg( $input );
	//mstw_log_msg( 'divider' );
	
	//check if the reset button was pressed and confirmed
	//array_key_exists() returns true for null, isset does not
	if ( array_key_exists( 'reset', $input ) ) {
		if( $input['reset'] == 'Resetting Defaults' ) {
			// reset to defaults
			$output = mstw_ss_get_venue_defaults( );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults.' );
		}
		else {
			// Don't change nuthin'
			$output = get_option( 'mstw_ss_venue_options' );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults canceled.' );
		}
		
		return apply_filters( 'mstw_ss_sanitize_venue_options', $output, $input );
	}
	
	// Create the array for storing the validated options
	$output = array();
	
	//special handling for the checkboxes
	$output['show_instructions'] = isset( $input['show_instructions'] ) ? 1 : 0;
	if ( isset( $input['show_instructions'] ) ) 
		unset( $input['show_instructions'] );
	$output['show_address'] = isset( $input['show_address'] ) ? 1 : 0;
	if ( isset( $input['show_address'] ) ) 
		unset( $input['show_address'] );
	$output['show_map'] = isset( $input['show_map'] ) ? 1 : 0;
	if ( isset( $input['show_map'] ) ) 
		unset( $input['show_map'] );
	
	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {
		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {
			switch ( $key ) {
				case 'map_icon_width':
				case 'map_icon_height':
					$output[$key] = intval( $input[$key] );
					break;	
				// Check all other settings
				default:
					if( isset( $input[$key] ) ) {
						$output[$key] = sanitize_text_field( $input[$key] );
					}
					// There should not be user/accidental errors in these fields
					break;	
			} // end switch
		} // end if( isset( $input[$key] )
	} // end foreach $input
	
	mstw_ss_add_admin_notice( 'updated', 'Venue table settings updated.' );
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'mstw_ss_sanitize_venue_options', $output, $input );
	
} //End: mstw_ss_validate_venues()

?>