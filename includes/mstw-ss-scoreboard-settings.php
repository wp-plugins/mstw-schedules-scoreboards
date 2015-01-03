<?php
/*----------------------------------------------------------------------------
 * mstw-ss-scoreboard-settings.php
 *	All functions for the MSTW Schedules & Scoreboards Plugin's scoreboard settings.
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
// 	scoreboard settings page setup	
//		
function mstw_ss_scoreboard_setup( ) {
	mstw_ss_general_section_setup( );
	mstw_ss_gallery_section_setup( );
	mstw_ss_ticker_section_setup( );		
}
	
//-----------------------------------------------------------------	
// 	Table (shortcode and widget) colors section setup	
// 
function mstw_ss_general_section_setup( ) {
	$display_on_page = 'mstw-ss-scoreboard-settings';
	$page_section = 'mstw-ss-general';
	
	$options = wp_parse_args( get_option( 'mstw_ss_scoreboard_options' ), mstw_ss_get_sb_defaults( ) );
	
	//mstw_log_msg( 'in mstw_ss_general_section_setup ... ' );
	//mstw_log_msg( $options );
	
	add_settings_section(
		$page_section,
		__( 'Scoreboard General Settings', 'mstw-schedules-scoreboards' ),
		'mstw_ss_general_inst',
		$display_on_page
		);

	$arguments = array(
		array( 	// TIME FORMAT
			'type' => 'time-only',
			'custom_format' => 0,			
			'id' => 'time_format',
			'name' => 'mstw_ss_scoreboard_options[time_format]',
			'value' => mstw_safe_ref( $options, 'time_format' ), //$options['ss_tbl_hdr_bkgd_color'], 
			'title'	=> __( 'Time Format:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// HIGHLIGHT WINNER
			'type' => 'checkbox', 
			'id' => 'sb_highlight_winner',
			'name' => 'mstw_ss_scoreboard_options[highlight_winner]',
			'value' => mstw_safe_ref( $options, 'highlight_winner' ),
			'title'	=> __( 'Highlight Winner:', 'mstw-schedules-scoreboards' ),
			'desc' => __( 'Gallery format uses a background color. Ticker format uses BOLD. You can change the gallery highlight color and those behaviors via the plugin stylesheet.', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// HEADER BACKGROUND COLOR
			'type' => 'color', 
			'id' => 'sb_header_bkgd_color',
			'name' => 'mstw_ss_scoreboard_options[sb_header_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'sb_header_bkgd_color' ), 
			'title'	=> __( 'Header Background Color:', 'mstw-schedules-scoreboards' ),
			'desc'	=> __( 'Applies to the ticker header and the gallery game headers.', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// HEADER TEXT COLOR
			'type' => 'color', 
			'id' => 'sb_header_text_color',
			'name' => 'mstw_ss_scoreboard_options[sb_header_text_color]',
			'value' => mstw_safe_ref( $options, 'sb_header_text_color' ), 
			'title'	=> __( 'Header Text Color:', 'mstw-schedules-scoreboards' ),
			'desc'	=> __( 'Applies to the ticker header and the gallery game headers.', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
	);
	
	mstw_build_settings_screen( $arguments );
}

//-----------------------------------------------------------------	
// 	Scoreboard General section instructions	
//	
function mstw_ss_general_inst( ) {
	echo __( "These settings apply to the gallery and ticker formats. NOTE: They will override the plugin's default settings. They can be overridden by shortcode arguments (for non-colors) and stylesheet rules (for colors).", 'mstw-schedules-scoreboards' ) . '</p>';
}
		
//-----------------------------------------------------------------	
// 	CDT (shortcode and widget) colors section setup	
//	
function mstw_ss_gallery_section_setup( ) {
	$display_on_page = 'mstw-ss-scoreboard-settings';
	$page_section = 'mstw-ss-gallery';
	
	$options = get_option( 'mstw_ss_scoreboard_options' );
	
	add_settings_section(
		$page_section,
		__( 'Gallery Settings', 'mstw-schedules-scoreboards' ),
		'mstw_ss_gallery_inst',
		$display_on_page
	);
	
	$arguments = array(
		array( 	// DATE FORMAT
			'type' => 'date-only',
			'custom_format' => 0,			
			'id' => 'date_format',
			'name' => 'mstw_ss_scoreboard_options[date_format]',
			'value' => mstw_safe_ref( $options, 'date_format' ), 
			'title'	=> __( 'Date Format:', 'mstw-schedules-scoreboards' ),
			'desc' => __( 'Format for the date header for each block of games.', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	//DISPLAY THE TEAM LOGO
		'type' => 'select-option', 
		'id' => 'show_name',
		'name' => 'mstw_ss_scoreboard_options[show_name]',
		'value' => mstw_safe_ref( $options, 'show_name' ), 
		'title'	=> __( 'Display Team Name As:', 'mstw-schedules-scoreboards' ),
		'options' => array( __( 'No Name', 'mstw-schedules-scoreboards' ) => 0,
							__( 'Team Name Only', 'mstw-schedules-scoreboards' ) => 1,
							__( 'Team Mascot Only', 'mstw-schedules-scoreboards' ) => 2,
							__( 'Team Name & Mascot', 'mstw-schedules-scoreboards' ) => 3,
							),
		'desc'	=> __( 'If "No Name" is selected, the team logo must be shown.', 'mstw-schedules-scoreboards' ),
		'default' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	// SHOW TEAM LOGO
			'type' => 'checkbox', 
			'id' => 'show_logo',
			'name' => 'mstw_ss_scoreboard_options[show_logo]',
			'value' => mstw_safe_ref( $options, 'show_logo' ),
			'title'	=> __( 'Show Team Logo:', 'mstw-schedules-scoreboards' ),
			'desc' => '',
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// WINNER HIGHLIGHT COLOR
			'type' => 'color', 
			'id' => 'sbg_winner_bkgd_color',
			'name' => 'mstw_ss_scoreboard_options[sbg_winner_bkgd_color]',
			'value' => mstw_safe_ref( $options, 'sbg_winner_bkgd_color' ),
			'desc' => __( 'Background color of winning team in "final" games.', 'mstw-schedules-scoreboards' ),			
			'title'	=> __( 'Winner Highlight Color:', 'mstw-schedules-scoreboards' ),
			'page' => $display_on_page,
			'section' => $page_section,
		),
		array( 	// TEAM BLOCK TEXT COLOR
		'type' => 'color',
		'id' => 'sbg_team_block_text_color',
		'name' => 'mstw_ss_scoreboard_options[sbg_team_block_text_color]',
		'value' => mstw_safe_ref( $options, 'sbg_team_block_text_color' ),
		'title'	=> __( 'Team Block Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	// TEAM BLOCK BACKGROUND COLOR
		'type' => 'color', 
		'id' => 'sbg_team_block_bkgd_color',
		'name' => 'mstw_ss_scoreboard_options[sbg_team_block_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'sbg_team_block_bkgd_color' ),
		'title'	=> __( 'Team Block Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	//DATE HEADER TEXT COLOR
		'type' => 'color',
		'id' => 'sbg_date_text_color',
		'name' => 'mstw_ss_scoreboard_options[sbg_date_text_color]',
		'value' => mstw_safe_ref( $options, 'sbg_date_text_color' ), 
		'title'	=> __( 'Gallery Date Header Color (Text):', 'mstw-schedules-scoreboards' ),
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
// 	Gallery section instructions	
//	
function mstw_ss_gallery_inst( ) {
	echo '<p>' . __( "These settings apply to the gallery format (only). NOTE: They will override the plugin's default settings. They can be overridden by shortcode arguments (for non-colors) and stylesheet rules (for colors).", 'mstw-schedules-scoreboards' ) . '</p>';
}
	
//-----------------------------------------------------------------	
// 	Ticker section setup	
//	
function mstw_ss_ticker_section_setup( ) {
	$display_on_page = 'mstw-ss-scoreboard-settings';
	$page_section = 'mstw-ss-ticker';
	
	$options = get_option( 'mstw_ss_scoreboard_options' );
	
	add_settings_section(
		$page_section,
		__( 'Scoreboard Ticker Settings', 'mstw-schedules-scoreboards' ),
		'mstw_ss_ticker_inst',
		$display_on_page
		);	
	
	$arguments = array(
		array( 	
		'type' => 'checkbox', 
		'id' => 'sbt_show_header',
		'name' => 'mstw_ss_scoreboard_options[sbt_show_header]',
		'value' => mstw_safe_ref( $options, 'sbt_show_header' ),
		'title'	=> __( 'Show Header:', 'mstw-schedules-scoreboards' ),
		'desc'	=> __( 'If you want to build a custom ticker header, un-check the box.', 'mstw-schedules-scoreboards' ),
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'text', 
		'id' => 'sbt_title',
		'name' => 'mstw_ss_scoreboard_options[sbt_title]',
		'value' => mstw_safe_ref( $options, 'sbt_title' ),
		'title'	=> __( 'Title:', 'mstw-schedules-scoreboards' ),
		'desc'	=> __( 'Enter an empty string to force the default display of the scoreboard name.', 'mstw-schedules-scoreboards' ),
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'text', 
		'id' => 'sbt_link_label',
		'name' => 'mstw_ss_scoreboard_options[sbt_link_label]',
		'value' => mstw_safe_ref( $options, 'sbt_link_label' ),
		'title'	=> __( 'Link Label:', 'mstw-schedules-scoreboards' ),
		'desc'	=> __( 'Enter an empty string to remove the link (left of the title).', 'mstw-schedules-scoreboards' ),
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'text', 
		'id' => 'sbt_link_url',
		'name' => 'mstw_ss_scoreboard_options[sbt_link_url]',
		'value' => mstw_safe_ref( $options, 'sbt_link_url' ),
		'title'	=> __( 'Link URL:', 'mstw-schedules-scoreboards' ),
		'desc'	=> __( 'Enter a valid URL. (check it)', 'mstw-schedules-scoreboards' ),
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'text', 
		'id' => 'sbt_message',
		'name' => 'mstw_ss_scoreboard_options[sbt_message]',
		'value' => mstw_safe_ref( $options, 'sbt_message' ),
		'title'	=> __( 'Message:', 'mstw-schedules-scoreboards' ),
		'desc'	=> __( 'Message displayed at far right of ticker header.', 'mstw-schedules-scoreboards' ),
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'sbt_game_header_bkgd_color',
		'name' => 'mstw_ss_scoreboard_options[sbt_game_header_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'sbt_game_header_bkgd_color' ),
		'title'	=> __( 'Game Header Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'sbt_game_header_text_color',
		'name' => 'mstw_ss_scoreboard_options[sbt_game_header_text_color]',
		'value' => mstw_safe_ref( $options, 'sbt_game_header_text_color' ),
		'title'	=> __( 'Game Header Text Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'sbt_game_block_bkgd_color',
		'name' => 'mstw_ss_scoreboard_options[sbt_game_block_bkgd_color]',
		'value' => mstw_safe_ref( $options, 'sbt_game_block_bkgd_color' ), 
		'title'	=> __( 'Game Block Background Color:', 'mstw-schedules-scoreboards' ),
		'desc'	=> '',
		'default' => '',
		'options' => '',
		'page' => $display_on_page,
		'section' => $page_section,
		),
		array( 	
		'type' => 'color', 
		'id' => 'sbt_game_block_text_color',
		'name' => 'mstw_ss_scoreboard_options[sbt_game_block_text_color]',
		'value' => mstw_safe_ref( $options, 'sbt_game_block_text_color' ),
		'title'	=> __( 'Game Block Text Color:', 'mstw-schedules-scoreboards' ),
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
// 	Ticker section instructions	
// ----------------------------------------------------------------	
function mstw_ss_ticker_inst( ) {
	echo __( "These settings apply to the ticker format (only). NOTE: They will override the plugin's default settings. They can be overridden by shortcode arguments (for non-colors) and stylesheet rules (for colors).", 'mstw-schedules-scoreboards' ) . '</p>';
}

// ----------------------------------------------------------------	
//	Validate user scoreboard settings input
// 
function mstw_ss_validate_scoreboards( $input ) {
	// Create our array for storing the validated options
	$output = array();
	
	//mstw_log_msg( ' in mstw_ss_validate_scoreboards ... $input= ' );
	//mstw_log_msg( $input );
	
	//special handling for the checkboxes
	$output['highlight_winner'] = isset( $input['highlight_winner'] ) ? 1 : 0;
	$output['show_logo'] = isset( $input['show_logo'] ) ? 1 : 0;
	$output['sbt_show_header'] = isset( $input['sbt_show_header'] ) ? 1 : 0;
	
	if ( array_key_exists( 'reset', $input ) ) {
		if( $input['reset'] == 'Resetting Defaults' ) {
			// reset to defaults
			$output = mstw_ss_get_sb_defaults( );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults.' );
		}
		else {
			// cancel reset; return the previous (last good) options
			$output = get_option( 'mstw_ss_scoreboard_options' );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults canceled.' );
		}
	}
	else { // validate the user entries
	
		// Pull the previous (last good) options (used in case of errors)
		$options = get_option( 'mstw_ss_scoreboard_options' );
		
		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			switch( $key ) {
				case 'sb_header_bkgd_color':
				case 'sb_header_text_color':
				case 'sbg_winner_bkgd_color':
				case 'sbg_team_block_bkgd_color':
				case 'sbg_team_block_text_color':
				case 'sbg_date_text_color':
				case 'sbt_game_header_bkgd_color':
				case 'sbt_game_header_text_color':
				case 'sbt_game_block_bkgd_color':
				case 'sbt_game_block_text_color':
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
					break;
				default:
					$output[$key] = sanitize_text_field( $input[$key] );
					break;
			} // end switch
		} // end foreach
		
		// if no team name is shown, then logo must be shown
		if( isset( $output['show_name'] ) && $output['show_name'] == 0 ){
			$output['show_logo'] = 1;
		}
		
		//mstw_log_msg( ' $output= ' );
		//mstw_log_msg( $output );

		mstw_ss_add_admin_notice( 'updated', 'Color settings updated.' );
		
	} // end else
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'mstw_ss_sanitize_color_options', $output, $input );
	
} //End: mstw_ss_validate_color_options()
?>