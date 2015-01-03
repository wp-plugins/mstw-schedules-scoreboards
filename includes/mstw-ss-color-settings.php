<?php
/*----------------------------------------------------------------------------
 * mstw-ss-color-settings.php
 *	All functions for the MSTW Schedules & Scoreboards Plugin's color settings.
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
// 	Colors settings page setup	
//		
function mstw_ss_colors_setup( ) {
	mstw_ss_table_colors_section_setup( );
	mstw_ss_cdt_colors_section_setup( );
	mstw_ss_slider_colors_section_setup( );		
}
	
//-----------------------------------------------------------------	
// 	Table (shortcode and widget) colors section setup	
// 
function mstw_ss_table_colors_section_setup( ) {
	$display_on_page = 'mstw-ss-colors';
	$page_section = 'mstw-ss-table-colors';
	
	$options = get_option( 'mstw_ss_color_options' );
	
	add_settings_section(
		$page_section,
		__( 'Schedule Table Colors', 'mstw-schedules-scoreboards' ),
		'mstw_ss_colors_table_inst',
		$display_on_page
		);

	$arguments = array(
		array( 	// TABLE HEADER BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_hdr_bkgd_color',
			'name' => 'mstw_ss_color_options[ss_tbl_hdr_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_hdr_bkgd_color' ), //$options['ss_tbl_hdr_bkgd_color'], 
			'title'	=> __( 'Header Background Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),array( 	// TABLE HEADER TEXT COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_hdr_text_color',
			'name' => 'mstw_ss_color_options[ss_tbl_hdr_text_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_hdr_text_color' ), //$options['ss_tbl_hdr_text_color'],
			'title'	=> __( 'Header Text Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// TABLE BORDER COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_border_color',
			'name' => 'mstw_ss_color_options[ss_tbl_border_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_border_color' ), //$options['ss_tbl_border_color'],
			'title'	=> __( 'Table Border Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// ODD ROW BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_odd_bkgd_color',
			'name' => 'mstw_ss_color_options[ss_tbl_odd_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_odd_bkgd_color' ), //$options['ss_tbl_odd_bkgd_color'],
			'title'	=> __( 'Odd Row Background Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// ODD ROW TEXT COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_odd_text_color',
			'name' => 'mstw_ss_color_options[ss_tbl_odd_text_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_odd_text_color' ), //$options['ss_tbl_odd_text_color'],
			'title'	=> __( 'Odd Row Text Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// EVEN ROW BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_even_bkgd_color',
			'name' => 'mstw_ss_color_options[ss_tbl_even_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_even_bkgd_color' ), //$options['ss_tbl_even_bkgd_color'],
			'title'	=> __( 'Even Row Background Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// EVEN ROW TEXT COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_even_text_color',
			'name' => 'mstw_ss_color_options[ss_tbl_even_text_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_even_text_color' ), //$options['ss_tbl_even_text_color'],
			'title'	=> __( 'Even Row Text Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// HOME GAME ROW BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_home_bkgd_color',
			'name' => 'mstw_ss_color_options[ss_tbl_home_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_home_bkgd_color' ), //$options['ss_tbl_home_bkgd_color'],
			'title'	=> __( 'Home Game (row) Background Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// HOME GAME ROW TEXT COLOR
			'type' => 'color', 
			'id' => 'ss_tbl_home_text_color',
			'name' => 'mstw_ss_color_options[ss_tbl_home_text_color]',
			'value' => mstw_safe_ref( $options, 'ss_tbl_home_text_color' ), //$options['ss_tbl_home_text_color'],
			'title'	=> __( 'Home Game (row) Text Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
	);
	
	mstw_build_settings_screen( $arguments );
}

//-----------------------------------------------------------------	
// 	Colors table section instructions	
//	
function mstw_ss_colors_table_inst( ) {
	echo '<p>' . __( "Enter the default colors for the Schedule Table shortcodes and widgets. NOTE: These settings will override the default colors in the plugin's stylsheet." , 'mstw-schedules-scoreboards' ) . '</p>';
}
		
//-----------------------------------------------------------------	
// 	CDT (shortcode and widget) colors section setup	
//	
function mstw_ss_cdt_colors_section_setup( ) {
	$display_on_page = 'mstw-ss-colors';
	$page_section = 'mstw-ss-cdt-colors';
	
	$options = get_option( 'mstw_ss_color_options' );
	
	add_settings_section(
		$page_section,
		__( 'Countdown Timer Colors', 'mstw-schedules-scoreboards' ),
		'mstw_ss_colors_cdt_inst',
		$display_on_page
	);
	
	$arguments = array(
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_game_time_color',
		'name' => 'mstw_ss_color_options[ss_cdt_game_time_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_game_time_color' ), //$options['ss_cdt_game_time_color'],
		'title'	=> __( 'Game Time Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_opponent_color',
		'name' => 'mstw_ss_color_options[ss_cdt_opponent_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_opponent_color' ), //$options['ss_cdt_opponent_color'],
		'title'	=> __( 'Opponent Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_location_color',
		'name' => 'mstw_ss_color_options[ss_cdt_location_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_location_color' ), //$options['ss_cdt_location_color'],
		'title'	=> __( 'Location Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color',
		'id' => 'ss_cdt_intro_color',
		'name' => 'mstw_ss_color_options[ss_cdt_intro_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_intro_color' ), //$options['ss_cdt_intro_color'],
		'title'	=> __( 'Intro Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_countdown_color',
		'name' => 'mstw_ss_color_options[ss_cdt_countdown_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_countdown_color' ), //$options['ss_cdt_countdown_color'],
		'title'	=> __( 'Countdown Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_cdt_countdown_bkgd_color',
		'name' => 'mstw_ss_color_options[ss_cdt_countdown_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'ss_cdt_countdown_bkgd_color' ), //$options['ss_cdt_countdown_bkgd_color'],
		'title'	=> __( 'Countdown Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
	);
	
	mstw_build_settings_screen( $arguments );

} //End: mstw_ss_cdt_colors_section_setup()

//-----------------------------------------------------------------	
// 	Colors CDT section instructions	
//	
function mstw_ss_colors_cdt_inst( ) {
	echo '<p>' . __( "Enter the default colors for the countdown timer shortcodes and widgets. NOTE: These settings will override the default colors in the plugin's stylsheet.", 'mstw-schedules-scoreboards' ) . '</p>';
}
	
//-----------------------------------------------------------------	
// 	Slider colors section setup	
//	
function mstw_ss_slider_colors_section_setup( ) {
	$display_on_page = 'mstw-ss-colors';
	$page_section = 'mstw-ss-slider-colors';
	
	$options = get_option( 'mstw_ss_color_options' );
	
	add_settings_section(
		$page_section,
		__( 'Schedule Slider Colors', 'mstw-schedules-scoreboards' ),
		'mstw_ss_colors_slider_inst',
		$display_on_page
		);	
	
	$arguments = array(
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_hdr_bkgd_color',
		'name' => 'mstw_ss_color_options[ss_sldr_hdr_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_hdr_bkgd_color' ), //$options['ss_sldr_hdr_bkgd_color'],
		'title'	=> __( 'Header Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_block_bkgd_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_block_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_block_bkgd_color' ), //$options['ss_sldr_game_block_bkgd_color'],
		'title'	=> __( 'Game Block Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_hdr_text_color',
		'name' => 'mstw_ss_color_options[ss_sldr_hdr_text_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_hdr_text_color' ), //$options['ss_sldr_hdr_text_color'],
		'title'	=> __( 'Header Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_hdr_divider_color',
		'name' => 'mstw_ss_color_options[ss_sldr_hdr_divider_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_hdr_divider_color' ), //$options['ss_sldr_hdr_divider_color'],
		'title'	=> __( 'Header Divider (line) Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_date_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_date_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_date_color' ), //$options['ss_sldr_game_date_color'],
		'title'	=> __( 'Game Date Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_opponent_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_opponent_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_opponent_color' ), //$options['ss_sldr_game_opponent_color'],
		'title'	=> __( 'Opponent Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_location_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_location_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_location_color' ), //$options['ss_sldr_game_location_color'],
		'title'	=> __( 'Game Location Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'ss_sldr_game_time_color',
		'name' => 'mstw_ss_color_options[ss_sldr_game_time_color]',
		'value' => mstw_safe_ref( $options, 'ss_sldr_game_time_color' ), //$options['ss_sldr_game_time_color'],
		'title'	=> __( 'Game Time Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
	);
	
	mstw_build_settings_screen( $arguments );
	
} //End: mstw_ss_slider_colors_section_setup()
	
// ----------------------------------------------------------------	
// 	Colors Slider section instructions	
// ----------------------------------------------------------------	
function mstw_ss_colors_slider_inst( ) {
	echo '<p>' . __( "Enter the default colors for the Schedule Slider shortcodes and widgets. NOTE: These settings will override the default colors in the plugin's stylsheet.", 'mstw-schedules-scoreboards' ) . '</p>';
}

// ----------------------------------------------------------------	
//	Validate user color settings input
// 
function mstw_ss_validate_colors( $input ) {
	// Create our array for storing the validated options
	$output = array();
	
	if ( array_key_exists( 'reset', $input ) ) {
		if( $input['reset'] == 'Resetting Defaults' ) {
			// reset to defaults
			$output = mstw_ss_get_dtg_defaults( );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults.' );
		}
		else {
			// cancel reset; return the previous (last good) options
			$output = get_option( 'mstw_ss_color_options' );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults canceled.' );
		}
	}
	else { // validate the user entries
	
		// Pull the previous (last good) options (used in case of errors)
		$options = get_option( 'mstw_ss_color_options' );
		
		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {
				// validate the color for proper hex format
				// there should NEVER be a problem; js color selector should error check
				$sanitized_color = mstw_sanitize_hex_color( $input[$key] );
				
				// decide what to do - save new setting 
				// or display error & revert to last setting
				if ( isset( $sanitized_color ) ) {
					// blank input is valid
					$output[$key] = $sanitized_color;
				}
				else  {
					// there's an error. Reset to the last stored value ...
					$output[$key] = $options[$key];
					// and add error message
					$msg = sprintf( __( 'Error: %s reset to the default.', 'mstw-schedules-scoreboards' ), $key );
					mstw_ss_add_admin_notice( 'error', $msg );
				}
			} // end if
		} // end foreach
		
		mstw_ss_add_admin_notice( 'updated', 'Color settings updated.' );
		
	} // end else
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'mstw_ss_sanitize_color_options', $output, $input );
	
} //End: mstw_ss_validate_color_options()
?>