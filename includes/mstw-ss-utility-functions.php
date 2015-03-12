<?php
/*----------------------------------------------------------------------------
 * mstw-ss-utility-functions.php
 * 	"Helper functions" for MSTW Game Schedules & Scoreboards plugin (front & back ends)
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
 *
/*----------------------------------------------------------------------------
 *	MSTW-SS-UTILITY-FUNCTIONS
 *	These functions are included in both the front and back end.
 *
 * 1. mstw_ss_get_defaults() - returns the default mstw_ss_options[]
 * 2. mstw_ss_get_dtg_defaults() - returns default mstw_ss_dtg_options[]
 * 3. mstw_ss_get_color_defaults() - returns default mstw_ss_color_options[]
 * 3.1. mstw_ss_get_sb_defaults - returns the default scoreboard options (mstw_ss_sb_options[])
 * 3.2. mstw_ss_get_venue_defaults  - returns the default venue options (mstw_ss_venue_options[])
 * 4. mstw_ss_add_css_to_head - build CSS rules from the settings & add to the <head>
 * 5. mstw_ss_build_opponent_entry - builds the opponent entry for front-end elements
 * 5.1 mstw_ss_build_team_entry - builds a team entry for front end elements
 * 5.2 mstw_ss_build_team_link - builds a team link for front end elements
 * 6. mstw_ss_build_logo_url - builds the logo url for the schedule table & slider
 * 7. mstw_ss_build_location_entry - builds the location entry for the schedule
 *		 table & slider
 * 8. mstw_ss_build_google_map_url - builds a google maps URL
 * 9. mstw_ss_build_venues_list - Builds an array of venues from the MSTW
 *		Venues DB for use in a select-option control
 * 10. mstw_ss_build_teams_list - Builds an array of sports from the MSTW
 *		teams DB for use in a select-option control
 * 11. mstw_ss_build_sports_list - Builds an array of sports from the MSTW
 * 		sports DB for use in a select-option control
 * 12. mstw_ss_build_staffs_list - Builds an array of staffs from the MSTW 
 *		coaching staffs DB for use in a select-option control
 * 13. mstw_ss_build_rosters_list - Builds an array of rosters from the MSTW 
 *		team rosters DB for use in a select-option control
 * 14. mstw_ss_build_schedules_list - Builds an array of team schedules from
 *		the MSTW schedules DB for use in a select-option control
 * 15. mstw_ss_build_leagues_list - Builds an array of leagues from the MSTW 
 *		league standings DB for use in a select-option control
 * 16. mstw_ss_build_countdown - Builds the countdown display as a string 
 *		used by both the countdown [shortcode] and by the countdown widget.
 * 17. mstw_ss_time_difference - Converts a time (PHP timestamp in seconds) to
 *		a string of in years, months, days, hours, minutes, seconds. 
 * 18. mstw_ss_numeral_to_ordinal - Converts number to the corresponding ordinal
 * 19. mstw_ss_admin_notice - Displays all admin notices; callback for admin_notices action
 * 20. mstw_ss_add_admin_notice - Adds a notice to the list for display by mstw_ss_admin_notice
 * 21. mstw_find_game_venue - Finds the venue (CPT object) for a game (CPT object)
 *	 
 *--------------------------------------------------------------------------*/

//---------------------------------------------------------------------------------
//	1. mstw_ss_get_defaults() - returns the default mstw_ss_options[]
//
if ( !function_exists( 'mstw_ss_get_defaults' ) ) {
	function mstw_ss_get_defaults( ) {
		//Base defaults
		$defaults = array(
				//default schedule table shortcode arguments
				'sched' => '', //1,  // This is used for cdt & slider shortcodes too
				'first_dtg' => '1970:01:01 00:00:00',	// first php dtg
				'last_dtg' => '2038:01:19 00:00:00', 	// last php dtg (roughly)
				'games_to_show' => -1,
				
				//default cdt shortcode arguments
				'cd_title'			=> __( 'Countdown', 'mstw-schedules-scoreboards' ),
				'home_only' 		=> 0,
				'intro'				=> __( 'Time to kickoff', 'mstw-schedules-scoreboards' ),
				
				//default slider shortcode arguments
				'title'					=> __( 'Schedule', 'mstw-schedules-scoreboards' ),
				'link_label' 			=> '',
				'link' 					=> '',
				'show_schedule_name'	=> 0,

				//show/hide data fields and default labels FOR TABLES
				'show_date'				=> 1,
				'date_label'			=> __( 'Date', 'mstw-schedules-scoreboards' ),
				'opponent_label'		=> __( 'Opponent', 'mstw-schedules-scoreboards' ),
				'opponent_link'			=> 'no-link',
				'show_location'			=> 1,
				'location_label'		=> __( 'Location', 'mstw-schedules-scoreboards' ),
				'show_time'				=> 1,
				'time_label'			=> __( 'Time/Result', 'mstw-schedules-scoreboards' ),
				'show_media'			=> 3,
				'media_label'			=> __( 'Media Links', 'mstw-schedules-scoreboards' ),
				'table_opponent_format'	=> 'full-name',
				'slider_opponent_format'	=> 'full-name',
				'show_table_logos'		=> 'name-only', //Hide Logos
				'show_slider_logos'		=> 'name-only', //Hide Logos
				'venue_format'			=> 'name-only', //Show (location) name only
				'venue_link_format'		=> 'no-link', //No Link
				
				// show/hide data fields & default labels FOR TABLE WIDGET
				'show_widget_date'		=> 1,
				'widget_date_label'		=> __( 'Date', 'mstw-schedules-scoreboards' ),
				'widget_opponent_label'	=> __( 'Opponent', 'mstw-schedules-scoreboards' ),
				'show_widget_time'		=> 0,
				'widget_time_label'		=> __( 'Time/Result', 'mstw-schedules-scoreboards' ),
				);
				
		return $defaults;
	}
}
	
//---------------------------------------------------------------------------------
//	2. mstw_ss_get_dtg_defaults - returns the mstw_ss_dtg_options[] default values
//
if ( !function_exists( 'mstw_ss_get_dtg_defaults' ) ) {	
	function mstw_ss_get_dtg_defaults( ) {
		//Base defaults
		$defaults = array(
				//date and time format defaults
				'admin_date_format' 	=>'Y-m-d',
				'custom_admin_date_format' => '',
				'admin_time_format'		=> 'H:i',
				'custom_admin_time_format' => '',
				
				'table_date_format'		=> 'Y-m-d',
				'custom_table_date_format' => '',
				'table_time_format'		=> 'H:i',
				'custom_table_time_format' => '',
				
				'table_widget_date_format' => 'j M',
				'custom_table_widget_date_format' => '',
				
				'table_widget_time_format' => 'H:i',
				'custom_table_widget_time_format' => '',
				
				'cdt_dtg_format'		=> 'l, j M g:i a',
				'custom_cdt_dtg_format' => '',
				'cdt_date_format'		=> 'l, j M',
				'custom_cdt_date_format' => '',
				
				'slider_date_format'	=> 'D, j M',
				'custom_slider_date_format' => '',
				'slider_time_format'	=> 'g:i A',
				'custom_slider_time_format' => '',
				);
				
		return $defaults;
	}
}
	
//---------------------------------------------------------------------------------
//	3. mstw_ss_get_color_defaults - returns the mstw_ss_color_options[] default values
//	
if ( !function_exists( 'mstw_ss_get_color_defaults' ) ) {	
	function mstw_ss_get_color_defaults( ) {
		//resets all the colors to blank
		$defaults = array(
				'tbl_hdr_bkgd_color' 		=> '',
				'tbl_hdr_text_color' 		=> '',
				'tbl_border_color'			=> '',
				'tbl_odd_bkgd_color' 		=> '',
				'tbl_odd_text_color'			=> '',
				'tbl_even_bkgd_color' 		=> '',
				'tbl_even_text_color'		=> '',
				'tbl_home_bkgd_color' 		=> '',
				'tbl_home_text_color' 		=> '',
				
				'cdt_game_time_color' 		=> '',
				'cdt_opponent_color' 		=> '',
				'cdt_location_color'			=> '',
				'cdt_intro_color' 			=> '',
				'cdt_countdown_color'		=> '',
				'cdt_countdown_bkgd_color' 	=> '',
				'tbl_even_text_color'		=> '',
				'tbl_home_bkgd_color' 		=> '',
				'tbl_home_text_color' 		=> '',
				
				'sldr_hdr_bkgd_color' 		=> '',
				'sldr_game_block_bkgd_color' => '',
				'sldr_hdr_text_color'		=> '',
				'sldr_hdr_divider_color' 	=> '',
				'sldr_game_date_color'		=> '',
				'sldr_game_opponent_color' 	=> '',
				'sldr_game_location_color'	=> '',
				'sldr_game_time_color' 		=> '',
				'sldr_game_links_color' 		=> '',
				);
				
		return $defaults;
	}
}

//---------------------------------------------------------------------------------
//	3.1. mstw_ss_get_sb_defaults - returns the default scoreboard options (mstw_ss_sb_options[])
//	
if ( !function_exists( 'mstw_ss_get_sb_defaults' ) ) {	
	function mstw_ss_get_sb_defaults( ) {
		//resets all the colors to blank
		return array(
			//
			// ALL FORMATS
			//
			//THE SCOREBOARD SLUG (sb) MUST BE PROVIDED AS A SHORTCODE ARG
			'sb'		=> '',
			
			// in version 1.2 ticker|gallery
			// maybe someday single|slider|table
			'format' => 'gallery',
			
			// this is the time of the game, not the time remaining
			'time_format' => 'g:s a', 	//1:30 pm
			
			//highlight the winner for completed games 0|1
			//BOLD for the ticker, highlight color for the gallery
			'highlight_winner'	=> 1,
			
			//colors always default to '', which means the CSS stylesheet will apply
			'sb_header_bkgd_color' => '',
			'sb_header_text_color' => '',
			
			//
			// GALLERY FORMAT [ONLY]
			//
			'date_format' => 'l, j F Y', 	//Saturday, 7 April 2014
			
			//0 - display no team name or mascot(LOGO MUST BE SHOWN)
			//1 - display (full) team name
			//2 - display full team mascot (only)
			//3 - display full team name and (full) mascot
			'show_name'	=> 3,
			
			//display team logo 0|1
			'show_logo' => 1,
			
			//team block settings
			'sbg_team_block_bkgd_color' => '',
			'sbg_team_block_text_color' => '',
			
			//winner highlight color
			'sbg_winner_bkgd_color' => '',
			
			//date header text color
			'sbg_date_text_color' => '',
			
			//
			// TICKER FORMAT [ONLY]
			//
			//default forces scoreboard title to be used
			'sbt_show_header' => 1,
			'sbt_title' => '', 		//Scoreboard Name is displayed by default
			'sbt_link_label' => '', //no link will be shown next to scoreboard title
			'sbt_link_url' => '',	//"link" can have an actual URL or just be a message
			'sbt_message' => '',	//message at far right of header
			
			//game block settings
			'sbt_game_header_bkgd_color' => '',
			'sbt_game_header_text_color' => '',
			'sbt_game_block_bkgd_color' => '',
			'sbt_game_block_text_color' => '',
			
			//
			// RESERVED FOR FUTURE USE
			//
			// -1 indicates all games
			//this is only used for the slider
			'games_to_show' 		=> -1,
			
			//ASC | DESC
			//default is chronological order
			'sort_order' 		=> 'ASC',
			
			// add link from team name to team URL 0|1
			'show_team_link' => 0,
			
		);

	} //End: mstw_ss_get_sb_defaults( )
}

//---------------------------------------------------------------------------------
// 3.2. mstw_ss_get_venue_defaults - returns the default venue options (mstw_ss_venue_options[])
//
if ( !function_exists( 'mstw_ss_get_venue_defaults' ) ) {
	function mstw_ss_get_venue_defaults( ) {
		$defaults = array(	'instructions'		=> __( 'Click on map to view driving directions.', 'mstw-schedules-scoreboards' ),
							'show_instructions'	=> 0,
							'venue_label'		=> __( 'Venue', 'mstw-schedules-scoreboards' ),
							'show_venue_link'	=> 0,
							'show_address'		=> 1,
							'address_label'		=> __( 'Address', 'mstw-schedules-scoreboards' ),
							'show_map'			=> 1,
							'custom_map_url'	=> '',
							'map_label'			=> __( 'Map (Click for larger view.)', 'mstw-schedules-scoreboards' ),
							'marker_color'		=> 'blue',
							'map_icon_width'	=> 250,
							'map_icon_height'	=> 75,
							'venue_group'		=> null,
							);		
		return $defaults;
	} //End: mstw_ss_get_venue_defaults( )
}

// ----------------------------------------------------------------
// 4. mstw_ss_add_css_to_head - Build CSS rules from the settings and add to the <head>
//	 	called via filter 'wp_head' in mstw-schedules-scoreboards.php
// 
if ( !function_exists( 'mstw_ss_add_css_to_head' ) ) {			
	function mstw_ss_add_css_to_head( ) {	
	
		$colors = get_option( 'mstw_ss_color_options' );

		echo '<style type="text/css">';
		
		// SCHEDULE TABLES
		echo ".mstw-ss-table-head th, .mstw-ss-sw-tab-head th { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_hdr_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_hdr_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
		
		echo "tr.mstw-ss-odd, td.mstw-ss-odd { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_odd_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_odd_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
		
		echo "td.mstw-ss-odd a, td.mstw-ss-odd a:visited, td.mstw-ss-odd a:active { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_odd_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_odd_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
		
		echo "td.mstw-ss-sw-odd a, td.mstw-ss-sw-odd a:visited, td.mstw-ss-sw-odd a:active { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_odd_text_color', 'color' );
			//echo mstw_build_css_rule( $colors, 'ss_tbl_odd_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
		
		echo "tr.mstw-ss-sw-odd, td.mstw-ss-sw-odd a, td.mstw-ss-sw-odd { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_odd_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_odd_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
		
		echo "tr.mstw-ss-even, td.mstw-ss-even { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_even_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_even_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
		
		echo "td.mstw-ss-even a, td.mstw-ss-even a:visited, td.mstw-ss-even a:active { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_even_text_color', 'color' );
			//echo mstw_build_css_rule( $colors, 'ss_tbl_even_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
		
		echo "tr.mstw-ss-even, td.mstw-ss-even, td.mstw-ss-even a { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_even_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_even_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
		
		echo ".mstw-ss-sw-even td a, .mstw-ss-sw-even td a:visited, .mstw-ss-sw-even td a:active { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_even_text_color', 'color' );
			//echo mstw_build_css_rule( $colors, 'ss_tbl_even_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";

		echo "tr.mstw-ss-sw-even, td.mstw-ss-sw-even a, td.mstw-ss-sw-even { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_even_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_even_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_border_color', 'border-color' );
		echo "} \n";
				
		echo ".mstw-ss-even.mstw-ss-home td, .mstw-ss-odd.mstw-ss-home td { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_home_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_home_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo ".mstw-ss-odd.mstw-ss-home td a, .mstw-ss-even.mstw-ss-home td a { \n";
			echo mstw_build_css_rule( $colors, 'ss_tbl_home_text_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_tbl_home_bkgd_color', 'background-color' );
		echo "} \n";
		
		// COUNTDOWN TIMER
		echo ".mstw-ss-cdt-dtg { \n";
			echo mstw_build_css_rule( $colors, 'ss_cdt_game_time_color', 'color' );
		echo "} \n";
		
		echo ".mstw-ss-cdt-opponent, .mstw-ss-cdt-opponent a { \n";
			echo mstw_build_css_rule( $colors, 'ss_cdt_opponent_color', 'color' );
		echo "} \n";
		
		echo ".mstw-ss-cdt-location, .mstw-ss-cdt-location a { \n";
			echo mstw_build_css_rule( $colors, 'ss_cdt_location_color', 'color' );
		echo "} \n";
		
		echo ".mstw-ss-cdt-intro { \n";
			echo mstw_build_css_rule( $colors, 'ss_cdt_intro_color', 'color' );
		echo "} \n";
		
		echo ".mstw-ss-cdt-countdown { \n";
			echo mstw_build_css_rule( $colors, 'ss_cdt_countdown_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_cdt_countdown_bkgd_color', 'background-color' );
		echo "} \n";
		
		// SCHEDULE SLIDER
		echo ".ss-slider .title, .ss-slider .full-schedule-link { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_hdr_text_color', 'color' );
		echo "} \n";
		
		echo ".ss-slider .full-schedule-link a { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_hdr_text_color', 'color' );
		echo "} \n";
		
		echo ".ss-slider .box { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_hdr_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo ".ss-divider { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_hdr_divider_color', 'border-bottom-color' );
		echo "} \n";
		
		echo ".schedule-slider { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_block_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo ".game-block .date { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_date_color', 'color' );
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_date_color', 'border-bottom-color' );
		echo "} \n";
		
		echo ".game-block .opponent, .game-block .opponent a:hover { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_opponent_color', 'color' );
			echo "text-decoration: none; \n";
		echo "} \n";
		
		echo ".game-block .opponent a { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_opponent_color', 'color' );
			echo "text-decoration: underline; \n";
		echo "} \n";
		
		echo ".game-block .location { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_location_color', 'color' );
		echo "} \n";
		
		echo ".game-block .location a { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_location_color', 'color' );
			echo "text-decoration: underline; \n";
		echo "} \n";
		
		echo ".game-block .location a:hover { \n";
			echo "text-decoration: none; \n";
		echo "} \n";

		echo ".game-block .time-result { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_time_color', 'color' );
		echo "} \n";
		
		echo ".game-block .links, .ss-slider .game-block .links a  { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_links_color', 'color' );
		echo "} \n";
		
		echo "#ss-slider-right-arrow, #ss-slider-left-arrow { \n";
			echo mstw_build_css_rule( $colors, 'ss_sldr_game_location_color', 'color' );
		echo "} \n";
		
		//SCOREBOARDS
		$sb_options = get_option( 'mstw_ss_scoreboard_options' );
		//mstw_log_msg( $sb_options );
		
		//both -----------------------------------------	
		echo "div.sbt-header, div.sbt-next, div.sbt-prev, div.sbg-game-header { \n";
			echo mstw_build_css_rule( $sb_options, 'sb_header_bkgd_color', 'background-color' );
			echo mstw_build_css_rule( $sb_options, 'sb_header_text_color', 'color' );
		echo "} \n";
		
		//gallery----------------------------------------	
		echo "h4.sbg-date-header, .sbg-date-header { \n";
			echo mstw_build_css_rule( $sb_options, 'sbg_date_text_color', 'color' );
		echo "} \n";
		
		echo "p.sbg-header-status, p.sbg-header-score { \n";
			echo mstw_build_css_rule( $sb_options, 'sb_header_text_color', 'color' );
		echo "} \n";
		
		echo "p.sbg-team-name, p.sbg-team-score { \n";
			echo mstw_build_css_rule( $sb_options, 'sbg_team_block_text_color', 'color' );
		echo "} \n";
		
		echo "div.sbg-team.sbg-winner, .sbg-winner{ \n";
			echo mstw_build_css_rule( $sb_options, 'sbg_winner_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo "div.sbg-team{ \n";
			echo mstw_build_css_rule( $sb_options, 'sbg_team_block_bkgd_color', 'background-color' );
		echo "} \n";
				
		//ticker-----------------------------------------		
		echo "div.sbt-game-header { \n";
			echo mstw_build_css_rule( $sb_options, 'sbt_game_header_text_color', 'color' );
			echo mstw_build_css_rule( $sb_options, 'sbt_game_header_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo "div.sbt-link a, div.sbt-link a:active, div.sbt-link a:visited { \n";
			echo mstw_build_css_rule( $sb_options, 'sb_header_text_color', 'color' );
		echo "} \n";
		
		echo "div.sbt-link a:hover { \n";
			echo mstw_build_css_rule( $sb_options, 'sb_header_text_color', 'color' );
		echo "} \n";
		
		echo "div.sbt-team { \n";
			echo mstw_build_css_rule( $sb_options, 'sbt_game_block_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo "div.sbt-ticker-content ul li { \n";
			echo mstw_build_css_rule( $sb_options, 'sbt_game_block_bkgd_color', 'background-color' );
		echo "} \n";
		
		echo "p.sbt-team-name, p.sbt-team-score { \n";
			echo mstw_build_css_rule( $sb_options, 'sbt_game_block_text_color', 'color' );
		echo "} \n";
		
		echo '</style>';
	
	} //end mstw_ss_add_css_to_head( )
}

//---------------------------------------------------------------------	
//	5. MSTW_SS_BUILD_OPPONENT_ENTRY
//		Builds the opponent entry for the schedule table shortcode
//
//	ARGUMENTS:
//		$post - the game post
//		$options - the combined base and dtg options, args, atts
//		$entry_type - "slider" or "table" controls image used/image size
//		Defaults to "table", which is the smaller size
//
if ( !function_exists( 'mstw_ss_build_opponent_entry' ) ) {
	function mstw_ss_build_opponent_entry( $post, $options, $entry_type ) {
	
		//mstw_log_msg( $options );
		//mstw_log_msg( $post );
		
		$opponent_entry = '';  //this should never survive
		
		$team_slug = get_post_meta( $post->ID, 'game_opponent_team', true );
		//mstw_log_msg( '$team_slug: ' . $team_slug );
		
		//is an entry for the opponent in the TEAMS DB specfied?
		//the empty string is there for legacy purposes
		if ( $team_slug != '' and $team_slug != -1 ) {
		
			$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
			$team_ID = $team_obj->ID;

			//Need to check display settings for formats
			// long name + long mascot, short name + short mascot, etc.

			$team_full_name = get_post_meta( $team_ID, 'team_full_name', true );
			
			$team_short_name = get_post_meta( $team_ID, 'team_short_name', true );
			$team_short_name = ( trim( $team_short_name ) == '' ? $team_full_name : $team_short_name );
			
			$team_full_mascot = get_post_meta( $team_ID, 'team_full_mascot', true );
			$team_short_mascot = get_post_meta( $team_ID, 'team_short_mascot', true );
			$team_short_mascot = ( trim( $team_short_mascot ) == '' ? $team_full_mascot : $team_short_mascot );
			
			$opponent_format = ( $entry_type == 'slider' ? $options['slider_opponent_format'] : $options['table_opponent_format'] );
			
			switch ( $opponent_format ) {
				case 'short-name':
					$opponent_entry .= $team_short_name;
					break;
				case 'full-name':
					$opponent_entry .= $team_full_name; 
					break;
				case 'full-name-mascot':
					$opponent_entry .= "$team_full_name $team_full_mascot";
					break;
				default: //'short-name-mascot'
					$opponent_entry .= "$team_short_name $team_short_mascot";
					break;
			}
				
			//check for a link in the Teams DB, not the game post
			$opponent_link = get_post_meta( $team_ID, 'team_link', true );
			
			$opponent_link = mstw_ss_build_team_link( $post, $options['opponent_link'], 'opponent' );
			
			//$opponent_link = mstw_ss_build_team_link( $post, 'team-url', 'opponent' );
			
			//mstw_log_msg( 'in mstw_ss_build_opponent_entry ... $options[\'opponent_link\']' . $options['opponent_link'] );
			
			$show_entry_logo = ( $entry_type == "slider" ? $options['show_slider_logos'] : $options['show_table_logos'] );
			
			//get the format setting for name & logo
			if ( $show_entry_logo == 'logo-only' or $show_entry_logo == 'logo-name' ) {
				$img_url = mstw_ss_build_logo_url( $post, $entry_type );
				$img_str = "<img class=mstw-ss-$entry_type-logo src=$img_url>";
			}
			
			if ( $show_entry_logo == 'logo-only' ) {
				//$img_url = get_post_meta( $team_ID, 'team_alt_logo', true );
				if ( $opponent_link != '' and $opponent_link != -1 ) {
					$opponent_entry = "<a href='$opponent_link' target='_blank' >$img_str</a>";
				}
				else {
					$opponent_entry = $img_str;
				}
			}
			else if ( $show_entry_logo == 'logo-name' ) {
				if ( $opponent_link != '' and $opponent_link != -1 ) {
					$opponent_entry = "$img_str<a href='$opponent_link' target='_blank' >$opponent_entry</a>";
				} else {
					$opponent_entry = $img_str . $opponent_entry;
				}
				
			}
			else {
				if ( $opponent_link != '' and $opponent_link != -1 ) {
					//$team_logo_url = ( $entry_type == "slider" ? get_post_meta( $team_ID, 'team_alt_logo', true ) : get_post_meta( $team_ID, 'team_logo', true ) );
					//$opponent_entry = "<img class=mstw-ss-slider-logo src=$team_logo_url>$opponent_entry";
					$opponent_entry = "<a href='$opponent_link' target='_blank' >$opponent_entry</a>";
				} //else we'll just leave the opponent_entry as is
			
			}
		}
		else { //no entry in Teams DB specified for opponent
			$opponent_entry = get_post_meta( $post->ID, 'game_opponent', true );
			//check for a link the the game post
			if ( ( $opponent_link = get_post_meta( $post->ID, 'opponent_link', true ) ) != '' ) {
				$opponent_entry = "<a href='$opponent_link' target='_blank'>$opponent_entry</a>";
			}
		}
		
		return $opponent_entry;
	} //End: mstw_ss_build_opponent_entry()
}

//---------------------------------------------------------------------	
//	5.1 MSTW_SS_BUILD_TEAM_ENTRY
//		Builds a team entry for the schedule table or slider shortcode
//
//	ARGUMENTS:
//		$post - the game post
//		$options - the combined base and dtg options, args, atts
//		$entry_type - "slider" or "table" controls image used/image size
//		Defaults to "table", which is the smaller size
//		$team - home (schedule) team or opponent
//
if ( !function_exists( 'mstw_ss_build_team_entry' ) ) {
	function mstw_ss_build_team_entry( $post, $options, $entry_type='table', $team='opponent' ) {
	
		//mstw_log_msg( $options );
		//mstw_log_msg( $post );
		
		//this should not survive
		$team_entry = '';  
		
		$team_slug = mstw_ss_get_team_slug( $post, $team );
		
		if( $team_slug == '' ) {
			//couldn't find team in teams DB, so we are not going to play 
			return "<h2> $team team not found for game " . get_the_title( $post->ID ) . ".</h2>";
		}
		
		//is an entry for the opponent in the TEAMS DB specfied?
		//the empty string is there for legacy purposes
		if ( $team_slug != '' and $team_slug != -1 ) {
		
			$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
			$team_ID = $team_obj->ID;

			//Need to check display settings for formats
			// long name + long mascot, short name + short mascot, etc.

			$team_full_name = get_post_meta( $team_ID, 'team_full_name', true );
			
			$team_short_name = get_post_meta( $team_ID, 'team_short_name', true );
			$team_short_name = ( trim( $team_short_name ) == '' ? $team_full_name : $team_short_name );
			
			$team_full_mascot = get_post_meta( $team_ID, 'team_full_mascot', true );
			$team_short_mascot = get_post_meta( $team_ID, 'team_short_mascot', true );
			$team_short_mascot = ( trim( $team_short_mascot ) == '' ? $team_full_mascot : $team_short_mascot );
			
			$team_format = ( $entry_type == 'slider' ? $options['slider_opponent_format'] : $options['table_opponent_format'] );
			
			
			switch ( $team_format ) {
				case 'short-name':
					$team_entry .= $team_short_name;
					break;
				case 'full-name':
					$team_entry .= $team_full_name; 
					break;
				case 'full-name-mascot':
					$team_entry .= "$team_full_name $team_full_mascot";
					break;
				default: //'short-name-mascot'
					$team_entry .= "$team_short_name $team_short_mascot";
					break;
			}
				
			//check for a link in the Teams DB, not the game post
			$team_link = get_post_meta( $team_ID, 'team_link', true );
			
			$show_entry_logo = ( $entry_type == "slider" ? $options['show_slider_logos'] : $options['show_table_logos'] );
			
			//mstw_log_msg( '$show_entry_logo: ' . $show_entry_logo );
			
			//get the format setting for name & logo
			if ( $show_entry_logo == 'logo-only' or $show_entry_logo == 'logo-name' ) {
				$img_url = mstw_ss_build_logo_url( $post, $entry_type, $team );
				$img_str = "<img class=mstw-ss-$entry_type-logo src=$img_url>";
			}
			
			if ( $show_entry_logo == 'logo-only' ) {
				//$img_url = get_post_meta( $team_ID, 'team_alt_logo', true );
				if ( $team_link != '' and $team_link != -1 ) {
					$team_entry = "<a href='$team_link' target='_blank' >$img_str</a>";
				}
				else {
					$team_entry = $img_str;
				}
			}
			else if ( $show_entry_logo == 'logo-name' ) {
				if ( $team_link != '' and $team_link != -1 ) {
					$team_entry = "$img_str<a href='$team_link' target='_blank' >$team_entry</a>";
				} else {
					$team_entry = $img_str . $team_entry;
				}
				
			}
			else {
				if ( $team_link != '' and $team_link != -1 ) {
					//$team_logo_url = ( $entry_type == "slider" ? get_post_meta( $team_ID, 'team_alt_logo', true ) : get_post_meta( $team_ID, 'team_logo', true ) );
					//$team_entry = "<img class=mstw-ss-slider-logo src=$team_logo_url>$team_entry";
					$team_entry = "<a href='$team_link' target='_blank' >$team_entry</a>";
				} //else we'll just leave the team_entry as is
			
			}
		}
		else { //no entry in Teams DB specified for team
			$team_entry = get_post_meta( $post->ID, 'game_opponent', true );
			//check for a link the the game post
			if ( ( $team_link = get_post_meta( $post->ID, 'opponent_link', true ) ) != '' ) {
				$team_entry = "<a href='$team_link' target='_blank'>$team_entry</a>";
			}
		}
		
		return $team_entry;
	} //End: mstw_ss_build_team_entry()
}

//---------------------------------------------------------------------	
//	5.2 MSTW_SS_BUILD_TEAM_LINK
//		Builds a team link for the schedule table or slider shortcode
//
//	ARGUMENTS:
//		$post - the game post
//		$link_type - the link type - no-link | game-page | team-url
//		$team - home (schedule) team or opponent
//
if ( !function_exists( 'mstw_ss_build_team_link' ) ) {
	function mstw_ss_build_team_link( $post, $link_type='no-link', $team='opponent' ) {
		// default for any issues (and $link_type='no-link'
		$ret_url = '';
				
		if ( $link_type == 'game-page' ) {
			//all we need to build the link is the game slug
			$game_slug = $post->post_name;
			$ret_url = get_site_url( null, '/game/'.$game_slug, null );
			
			mstw_log_msg( 'in ...' );
			mstw_log_msg( 'site_url: ' . get_site_url( null, '/game/'.$game_slug, null ) );
			mstw_log_msg( 'home_url: ' . get_home_url( null, '/game/'.$game_slug, null ) );

		}
		else if ( $link_type == 'team-url' ) {
			$team_slug = mstw_ss_get_team_slug( $post, $team );
			if ( $team_slug != '' ) {
				//mstw_log_msg( 'in mstw_ss_build_team_link ... $team_slug= ' . $team_slug );
				$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
				$team_url = get_post_meta( $team_obj->ID, 'team_link', true );
				//mstw_log_msg( 'in mstw_ss_build_team_link ... $team_ID= ' . $team_obj->ID );
				$ret_url = $team_url;
			}
		}
		
		//mstw_log_msg( 'in mstw_ss_build_team_link ... $ret_url= ' . $ret_url );
		return $ret_url;


	} //End: mstw_ss_build_team_link()
}

//---------------------------------------------------------------------	
//	5.3 MSTW_SS_GET_TEAM_SLUG
//		Finds the slug for a team (home or opp) in a game
//
//	ARGUMENTS:
//		$post - a game post
//		$team - home (schedule) team or opponent (default)
//	RETURNS:
//		Specified team slug, or '' if not found
//
if ( !function_exists( 'mstw_ss_get_team_slug' ) ) {
	function mstw_ss_get_team_slug( $post, $team = 'opponent' ) {
		//this string will be returned
		$team_slug = '';
		
		if ( $team == 'home' or $team == 'schedule_team' ) {
			//set home slug, object, and ID
			$sched_slug = get_post_meta( $post->ID, 'game_sched_id', true );
			if ( $sched_slug != '' ) {
				$sched_obj = get_page_by_path( $sched_slug, OBJECT, 'mstw_ss_schedule' );
				if ( $sched_obj != null ) {
					$team_slug = get_post_meta( $sched_obj->ID, 'schedule_team', true );
				}
			} 
		}
		else if ( $team == 'opponent' or $team == 'opp' ) {
			$team_slug = get_post_meta( $post->ID, 'game_opponent_team', true );
		}
		
		return $team_slug;

	} //End: mstw_ss_get_team_slug( )
}

//------------------------------------------------------------------------------
// 6. MSTW_SS_BUILD_LOGO_URL - 
//		Builds the team logo and url for the schedule table & slider
//
//	ARGUMENTS:
//		$post - the game post
//		$entry_type - "slider" or "table" controls image used/image size
//			Defaults to "table", which is the smaller size
//		$team - home (schedule) team or opponent
//			Defaults to opponent
//
if ( !function_exists( 'mstw_ss_build_logo_url' ) ) {	
	function mstw_ss_build_logo_url( $post, $type, $team='opp' ) {
	
		if ( $team == 'home' or $team == 'schedule_team' ) {
			//set home slug, object, and ID
			$sched_slug = get_post_meta( $post->ID, 'game_sched_id', true );
			$sched_obj = get_page_by_path( $sched_slug, OBJECT, 'mstw_ss_schedule' );
			$team_slug = get_post_meta( $sched_obj->ID, 'schedule_team', true );
		}
		else if ( $team == 'opponent' or $team == 'opp' ) {
			$team_slug = get_post_meta( $post->ID, 'game_opponent_team', true );
		}	
		
		$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
		$team_ID = $team_obj->ID;
		
		//Set the default logo (MSTW logo)
		$default_logo_file = ( $type == 'slider' ?  'default-slider-logo.png' : 'default-table-logo.png' );
		
		$logo_url = ( $type == 'slider' ? get_post_meta( $team_ID, 'team_alt_logo', true ) : get_post_meta( $team_ID, 'team_logo', true ) );
	
		$ret_url = ( $logo_url != '' ? $logo_url : plugins_url( ) . '/game-schedules/images/logos/' . $default_logo_file );
		
		return $ret_url;
	
	} //End: mstw_ss_build_logo_url()
}	

//------------------------------------------------------------------------------	
// 7. mstw_ss_build_location_entry - builds the location entry for the 
//		schedule table & slider shortcodes
//
//	ARGUMENTS:
//		$post - the game post
//		$$options - display settings/options/arguments
//
// 	This is a bit complicated:
//		a. If there is a text entry for location (legacy, really) use it
//		b. Else if there is an entry from the Venues DB, use it (neutral sites)
//		c. Else if there is an opponent entry from the Teams DB, then
//			i. If it is an away game, use the venue from the opponent Teams DB entry
//			ii. Else, use the venue from the home team Teams DB entry
//
//	Also adds venue and map links based on data entries and display settings
//
if ( !function_exists( 'mstw_ss_build_location_entry' ) ) {
	function mstw_ss_build_location_entry( $post, $options ) {
		//May need formatting options
		$venue_format = $options['venue_format'];	//Name only  or City, ST (name)
		$venue_link_format = $options['venue_link_format']; //None, venue link, map link
	
		$location_entry = ""; 	//default return value
		$map_url = ''; 			//this prevents some php notices
		
		// text entry location in game post
		$location = get_post_meta( $post->ID, 'game_location', true );
		
		// location from Venues DB in game post
		$gl_location_slug = get_post_meta( $post->ID, 'game_gl_location', true );
		
		// if there's a location entry in game post, use it
		if ( trim( $location ) != '' and $location != -1 ) { 
			$location_entry = $location;
			//if there's a custom location link entry, use it
			$location_link = get_post_meta( $post->ID, 'game_location_link', true );
			if ( $location_link != '' ) {
				$location_entry = '<a href="' . $location_link . '" target="_blank" >' . $location_entry . '</a>';
			}
		}
		//else if there's a location entry from the Venues DB, use it
		else if ( trim( $gl_location_slug ) != '' and $gl_location_slug != -1 ) {
			
			$gl_location = get_page_by_path( $gl_location_slug, OBJECT, 'mstw_ss_venue' ); 
			$location_name = get_the_title( $gl_location );	
			$location_street = get_post_meta( $gl_location->ID, 'venue_street', true );
			$location_city = get_post_meta( $gl_location->ID, 'venue_city', true );
			$location_state = get_post_meta( $gl_location->ID, 'venue_state', true ); 
			$location_zip = get_post_meta( $gl_location->ID, 'venue_zip', true );
			$location_map_url = get_post_meta( $gl_location->ID, 'venue_map_url', true );
			$location_venue_url = get_post_meta( $gl_location->ID, 'venue_url', true );

			//if location's custom_url is not set, don't build a link
			if ( ($location_venue_url == '' or $location_venue_url == -1 ) and $venue_link_format == 'link-to-venue' ) {
				$venue_link_format = 'no-link';
			}
			
			switch ( $venue_link_format ) {
				case 'link-to-venue':
					$venue_name = "<a href='$location_venue_url' target='_blank'>$location_name</a>";
					break;
				case 'link-to-map':
					//use the venue's custom map URL if it exists
					if ( $location_map_url != "" and $location_map_url != -1 ) {
						$map_url = $location_map_url;
					}
					//otherwise build the google map url
					else {	
						$map_url = mstw_ss_build_google_map_url( $location_name, $location_street, $location_city, $location_state, $location_zip );
					}
					$venue_name = "<a href='$map_url' target='_blank'>$location_name</a>";
					break;
				default: //no-link
					$venue_name = $location_name;
					break;
			}
			//check the format setting
			if ( $venue_format == 'city-state-name' ) {
				$location_entry = "$location_city, $location_state ($venue_name)";
			}
			else { //default is venue name only
				$location_entry =  $venue_name;
			}
		}
		//if an away game, and there's an opponent entry in the TEAMS DB, use it
		else if ( !get_post_meta( $post->ID, 'game_is_home_game', true ) ) {
			//slug of opponent in Teams DB
			$team_slug = get_post_meta( $post->ID, 'game_opponent_team', true );
			//mstw_log_msg( 'in mstw_ss_build_location_entry, away game ... $team_slug= ' . $team_slug );
			
			if ( ( $team_slug != '' ) and ( $team_slug != -1 ) ) {
				$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
				$venue_slug = get_post_meta( $team_obj->ID, 'team_home_venue', true );
				//mstw_log_msg( 'in mstw_ss_build_location_entry ... $venue_slug= ' . $venue_slug );
				$venue_obj = get_page_by_path( $venue_slug, OBJECT, 'mstw_ss_venue' );
				$venue_ID = ( is_object( $venue_obj ) ) ? $venue_obj->ID : -1;
				
				if ( ( $venue_ID != '' ) and ( $venue_ID != -1 ) ) {
					$venue_name = get_the_title( $venue_ID ); //this is basically the default
					//mstw_log_msg( 'venue_name: ' . $venue_name );
					switch ( $venue_link_format ) {
						case 'link-to-venue': //venue_url
							if ( ( $venue_url = get_post_meta( $venue_ID, 'venue_url', true ) ) != '' ) {
								$location_entry = "<a href='$venue_url' target='_blank'>";
							}
							break;
						case 'link-to-map': //map_url
							//check for custom_map_url in Locations DB
							//if ( $map_url = get_post_meta( $gl_location, '_mstw_gl_custom_url', true ) != '' ) {
							if ( $map_url != '' ) {
								// if found, use
								$location_entry = "<a href='$map_url' target='_blank'>";
							}
							else {
								// else, build it 
								$center_string = $venue_name . "," .
									get_post_meta( $venue_ID, 'venue_street', true ) . ', ' .
									get_post_meta( $venue_ID, 'venue_city', true ) . ', ' .
									get_post_meta( $venue_ID, 'venue_state', true ) . ', ' . 
									get_post_meta( $venue_ID, 'venue_zip', true );
					
									$location_entry = '<a href="https://maps.google.com?q=' .$center_string . '" target="_blank" >'; 
							}
							break;
						default: //no link
							// use default venue_name set above switch
							$location_entry = '';
							break;
					}
					
					$location_end = ( $location_entry == '' ? '' : '</a>' );
					
					if ( $venue_format == 'city-state-name' ) {  //city, state (venue)
						$city = get_post_meta( $venue_ID, 'venue_city', true );
						$state = get_post_meta( $venue_ID, 'venue_state', true );
						$location_entry = "$city, $state (" . $location_entry . $venue_name . $location_end . ")"; 
					} else {  //show name only
						$location_entry = $location_entry . $venue_name . $location_end;
					}
				}
			}
		}
		
		// else it's a home game, so if there's an entry in the TEAMS DB, use it
		else {
			//From the game, find the schedule id
			
			$sched_slug = get_post_meta( $post->ID, 'game_sched_id', true );
			//mstw_log_msg( 'in mstw_ss_build_location_entry, is home game ... $schedule_slug= ' . $sched_slug );
			
			// this should always be reset
			$sched_id = '';
			// because this $sched_slug should have a value
			if ( ( $sched_slug != '' ) and ( $sched_slug != -1 ) ) {
				$sched_obj = get_page_by_path( $sched_slug, OBJECT, 'mstw_ss_schedule' );
				$sched_id = $sched_obj->ID;
				//mstw_log_msg( 'in mstw_ss_build_location_entry ... $sched_id = ' . $sched_id );
			}
			
			if( !empty( $sched_id ) ) {  //this should never, ever be empty
					$home_team_slug = get_post_meta( $sched_id, 'schedule_team', true );
					//mstw_log_msg( 'in mstw_ss_build_location_entry ... $home_team_slug= ' . $home_team_slug );
					
					$home_team_obj = get_page_by_path( $home_team_slug, OBJECT, 'mstw_ss_team' );
					$home_team_id = $home_team_obj->ID;
					//mstw_log_msg( 'in mstw_ss_build_location_entry ... $home_team_id = ' . $home_team_id ); 
					
					$home_venue_slug = get_post_meta( $home_team_id, 'team_home_venue', true );
					//mstw_log_msg( 'in mstw_ss_build_location_entry ... $home_venue_slug= ' . $home_venue_slug );
					
					$home_venue_obj = get_page_by_path( $home_venue_slug, OBJECT, 'mstw_ss_venue' );
					$home_venue_id = $home_venue_obj->ID;
					//mstw_log_msg( 'in mstw_ss_build_location_entry ... $home_venue_id = ' . $home_venue_id ); 
					
					$home_venue_name = get_the_title( $home_venue_id );
					$home_venue_street = get_post_meta( $home_venue_id, 'venue_street', true );					
					$home_venue_city = get_post_meta( $home_venue_id, 'venue_city', true );
					$home_venue_state = get_post_meta( $home_venue_id, 'venue_state', true );
					$home_venue_zip = get_post_meta( $home_venue_id, 'venue_zip', true );
					$home_venue_url = get_post_meta( $home_venue_id, 'venue_url', true );
					$home_venue_map_url = get_post_meta( $home_venue_id, 'venue_map_url', true );
					//check the link setting
					switch( $venue_link_format ) {
						case 'link-to-venue':
							$venue_name = "<a href='$home_venue_url' target='_blank'>$home_venue_name</a>";
							break;
						case 'link-to-map':
							//use the venue's custom map URL if it exists
							if ( $home_venue_map_url != "" and $home_venue_map_url != -1 ) {
								$map_url = $home_venue_map_url;
							}
							//otherwise build the google map url
							else {
								$map_url = mstw_ss_build_google_map_url( $home_venue_name, $home_venue_street, $home_venue_city, $home_venue_state, $home_venue_zip );
							}
							$venue_name = "<a href='$map_url' target='_blank'>$home_venue_name</a>";
							break;
							//break;
						default: //no-link
							$venue_name = $home_venue_name;
							break;
					}
					//check the format setting
					if ( $venue_format == 'city-state-name' ) {
						$location_entry = "$home_venue_city, $home_venue_state ($venue_name)";
					}
					else { //default is venue name only
						$location_entry =  $venue_name;
					}
					
					
			}
			else {
				mstw_log_msg( 'in mstw_ss_build_location_entry, home game ... schedule ID is empty ... this should not happen');
			}

		}
		
		return $location_entry;
		
	} //End: mstw_ss_build_location_entry()
}
	
// ------------------------------------------------------------------------------
// 8. mstw_ss_build_google_map_url - builds a google maps URL	
// 
if ( !function_exists( 'mstw_ss_build_google_map_url' ) ) {
	function mstw_ss_build_google_map_url( $name, $street, $city, $state, $zip ) {
		//don't want to add commas after blanks
		$name = ( $name == '' ) ? '' : "$name,";
		$street = ( $street == '' ) ? '' : "$street,";
		$city = ( $city == '' ) ? '' : "$city,";
		$state = ( $state == '' ) ? '' : "$state,";
		$zip = ( $zip == '' ) ? '' : "$zip";
		
		$google_url = "https://maps.google.com?q=$name $street $city $state $zip";
		
		return $google_url;
	} //End: mstw_ss_build_google_map_url()
} 

// ------------------------------------------------------------------------------
// 9. mstw_ss_build_venues_list - Builds an array of venues in the venue CPT 
//	title=>slug pairs for use in a select-option control
//
//	ARGUMENTS: 
//		None
//	RETURNS:
//		An array of venue title=>slug pairs OR
//		Empty array of no locations exist
//
if ( !function_exists( 'mstw_ss_build_venues_list' ) ) {
	function mstw_ss_build_venues_list( ) {
		//get the venues list
		$venues = get_posts(array( 'numberposts' => -1,
							  'post_type' => 'mstw_ss_venue',
							  'orderby' => 'title',
							  'order' => 'ASC' 
							));	
		
		//this array is returned
		$options = array( );
			
		if( $venues ) {
			$options['----'] = -1;
			
			foreach( $venues as $venue ) {
				$options[ get_the_title( $venue->ID ) ] = get_post( $venue->ID )->post_name;
			}		
		}

		return $options;
		
	} //End: mstw_ss_build_venues_list()
}
	
// ------------------------------------------------------------------------------
// 10. mstw_ss_build_teams_list - Builds an array of teams in team CPT 
//	title=>slug pairs for use in a select-option control
//
//	ARGUMENTS: 
//		None
//	RETURNS:
//		An array of team title=>ID pairs OR
//		Empty array of no teams exist
//
if ( !function_exists( 'mstw_ss_build_teams_list' ) ) {
	function mstw_ss_build_teams_list( ) {
		$teams = get_posts( array(  'numberposts' => -1,
										'post_type' => 'mstw_ss_team',
										'orderby' => 'title',
										'order' => 'ASC' 
									)
							);	
							
		$options = array( );
			
		if( $teams ) {
			$options['----'] = -1;
			
			foreach( $teams as $team ) {
				$options[ get_the_title( $team->ID ) ] = get_post( $team->ID )->post_name;
			}
		}
		
		return $options;
	
	} //End: mstw_ss_build_teams_list()
}

// ------------------------------------------------------------------------------
// 11. mstw_ss_build_sports_list - Builds an array of sports in sport CPT 
//	title=>slug pairs for use in a select-option control
//
//	ARGUMENTS: 
//		None
//	RETURNS:
//		An array of team title=>slug pairs OR
//		Empty array of no sports exist
//
if ( !function_exists( 'mstw_ss_build_sports_list' ) ) {
	function mstw_ss_build_sports_list( ) {
		$sports = get_posts( array( 'numberposts' => -1,
									'post_type' => 'mstw_ss_sport',
									'orderby' => 'title',
									'order' => 'ASC' 
									)
							);	
							
		$options = array( );
			
		if( $sports ) {
			$options['----'] = -1;
			
			foreach( $sports as $sport ) {
				$options[ get_the_title( $sport->ID ) ] = get_post( $sport->ID )->post_name;
			}
		}
		
		return $options;
	
	} //End: mstw_ss_build_sports_list()
}


// ------------------------------------------------------------------------------
// 12. mstw_ss_build_staffs_list - Builds an array of coaching staffs in sport CPT 
//	title=>slug pairs for use in a select-option control
//
//	ARGUMENTS: 
//		None
//	RETURNS:
//		An array of staff title=>slug pairs OR
//		Empty array of no sports exist
//
if ( !function_exists( 'mstw_ss_build_staffs_list' ) ) {
	function mstw_ss_build_staffs_list( ) {
		
		$terms = get_terms( 'staffs', array( 'hide_empty' => 0 ) );
		
		$options = array( );
		
		if ( $terms ) {
			$options['----'] = -1;
			foreach( $terms as $term ) {
				$options[$term->name] = $term->slug;
			}
		}
		
		return $options;
	
	} //End: mstw_ss_build_staffs_list()
}

// ------------------------------------------------------------------------------
// 13. mstw_ss_build_rosters_list - Builds an array of team rosters in sport CPT 
//	title=>slug pairs for use in a select-option control
//
//	ARGUMENTS: 
//		None
//	RETURNS:
//		An array of roster title=>slug pairs OR
//		Empty array of no rosters exist
//
if ( !function_exists( 'mstw_ss_build_rosters_list' ) ) {
	function mstw_ss_build_rosters_list( ) {
	
		$terms = get_terms( 'teams', array( 'hide_empty' => 0 ) );
		
		$options = array( );
		
		if ( $terms ) {
			$options['----'] = -1;
			foreach( $terms as $term ) {
				$options[$term->name] = $term->slug;
			}
		}
		
		return $options;
	
	} //End: mstw_ss_build_rosters_list()
}

// ------------------------------------------------------------------------------
// 14. mstw_ss_build_schedules_list - Builds an array of team schedules in schedule
//		 CPT title=>slug pairs for use in a select-option control
//
//	ARGUMENTS: 
//		None
//	RETURNS:
//		An array of schedule title=>slug pairs OR
//		Empty array of no rosters exist
//
if ( !function_exists( 'mstw_ss_build_schedules_list' ) ) {
	function mstw_ss_build_schedules_list( ) {
	
		$scheds = get_posts( array( 	'numberposts' => -1,
										'post_type' => 'mstw_ss_schedule',
										'orderby' => 'title',
										'order' => 'ASC' 
									  )
								);	
								
		//mstw_log_msg( 'In mstw_ss_build_schedule_input - schedules:' );
		//mstw_log_msg( $scheds );
		//die( 'check the log' );
		
		$options = array( );
		
		if ( $scheds ) {
			$options['----'] = -1;
			foreach( $scheds as $sched ) {
				$options[ get_the_title( $sched->ID ) ] = get_post( $sched->ID )->post_name;
			}
		}
		
		return $options;
	
	} //End: mstw_ss_build_schedules_list()
}

// ------------------------------------------------------------------------------
// 15. mstw_ss_build_leagues_list - Builds an array of leagues in League  
//	title=>slug pairs for use in a select-option control
//
//	ARGUMENTS: 
//		None
//	RETURNS:
//		An array of leauge title=>slug pairs OR
//		Empty array of no leagues exist
//
if ( !function_exists( 'mstw_ss_build_leagues_list' ) ) {
	function mstw_ss_build_leagues_list( ) {
		
		$terms = get_terms( 'leagues', array( 'hide_empty' => 0 ) );
		
		$options = array( );
		
		if ( $terms ) {
			$options['----'] = -1;
			foreach( $terms as $term ) {
				$options[$term->name] = $term->slug;
			}
		}
		
		return $options;
	
	} //End: mstw_ss_build_leagues_list()
}

// --------------------------------------------------------------------------------------
// 16. mstw_ss_build_countdown - Builds the countdown display as a string 
//		used by the countdown [shortcode] and by the countdown widget.
//
//	ARGUMENTS: 
//		$attribs array
//			'sched' => schedule ID, defaults to 1 (which is really meaningless)
//			'intro' => text before countdown, defaults "Time to kickoff:"
// 			'home_only' => countdown to next home game, defaults to 0 (all games)
//	RETURNS:
//		HTML string displaying countdown timer
//
if ( !function_exists( 'mstw_ss_build_countdown' ) ) {
	function mstw_ss_build_countdown( $attribs ) { 

		$sched = $attribs['sched'];
		$intro = $attribs['intro'];
		$home_only = $attribs['home_only'];

		// return string; HTML displaying countdown timer
		$ret_str = '';
		
		// First get all the games for the specified schedule id.
		$games = get_posts( array( 
						'numberposts' => -1,
						'post_type' => 'mstw_ss_game',
						'meta_query' => array( array(
											'key' => 'game_sched_id',
											'value' => $sched,
											'compare' => '='
										)),
						'orderby' => 'meta_value', 
						'meta_key' => 'game_unix_dtg',
						'order' => 'ASC' 
						));
								  
		// Get the current (WordPress) date-time stamp
		$current_dtg = current_time( 'timestamp' );  
		// indicates there are no games after the current time
		$have_games = false;	
		
		// loop thru the game posts to find the first game in the future
		foreach( $games as $game ) {
			// Find first game time after the current time, and (just to be sure) has no result
					
			if ( get_post_meta( $game->ID, 'game_unix_dtg', true ) > $current_dtg && 
					get_post_meta( $game->ID, 'game_result', true ) == '' ) {
				if ( !$home_only || get_post_meta( $game->ID, 'game_is_home_game', true ) ) {
					// Ding, ding, ding, we have a winner!
					// Grab the data needed and stop looping through the games
					$have_games = true;
					$game_dtg = get_post_meta( $game->ID, 'game_unix_dtg', true );
					$game_time_tba = get_post_meta( $game->ID, 'game_time_tba', true );
					// and stop looping through the games
					break; 
				}
			}
		}
		
		// see what was found
		if ( ! $have_games ) {
			// No games scheduled after the current time
			if ( $home_only ) {
				$ret_msg = __( 'No home games found.', 'mstw-schedules-scoreboards' );
			}
			else {
				$ret_msg = __( 'No games found.', 'mstw-schedules-scoreboards' );
			}
			$ret_str = '<span class="mstw-ss-cdt-intro">' . $ret_msg . '</span>';
		}
		else {
			// we found a game, so build the countdown display
			$home_css_tag = get_post_meta( $game->ID, 'game_is_home_game', true ) ? 'mstw-ss-home' : '';
			$ret_str .= "<div class = 'mstw-cdt-block $home_css_tag'>";
			//full date-time group format 
			$cdt_dtg_format = ( $attribs['cdt_dtg_format'] == 'custom' ? $attribs['custom_cdt_dtg_format'] : $attribs['cdt_dtg_format'] ); 
			
			//date only format
			$cdt_date_format = ( $attribs['cdt_date_format'] == 'custom' ? $attribs['custom_cdt_date_format'] : $attribs['cdt_date_format'] ); 
			
			// Game day, date, time; need to handle a TBD time
			if ( $game_time_tba != '' ) { 
				$dtg_str = mstw_date_loc( $cdt_date_format, (int)$game_dtg ) . ' Time ' . $game_time_tba; 
				//$game_date is the UNIX timestamp DATE only
			}
			else {
				$dtg_str = mstw_date_loc( $cdt_dtg_format, (int)$game_dtg ); 
				//$dtg_str = "fmt: $cdt_date_format timestamp: $game_dtg";
				//$game_dtg is the full UNIX timestamp (DATE & TIME)  
			}
			
			$dtg_span = "<span class='mstw-ss-cdt-dtg mstw-ss-cdt-dtg_$sched'>";
			$ret_str .= $dtg_span . $dtg_str . '</span><br/>';
			
			// Add the opponent & location
			$opponent_entry = mstw_ss_build_opponent_entry( $game, $attribs, 'table' );
			//$post - the game post
			//$options - the combined base and dtg options, args, atts
			//$entry_type - "slider" or "table" controls image used/image size
			//Defaults to "table", which is the smaller size);
			
			$location_entry = mstw_ss_build_location_entry( $game, $attribs );
			
			$opp_span = "<span class='mstw-ss-cdt-opponent mstw-ss-cdt-opponent_$sched'>";
			$loc_span = "<span class='mstw-ss-cdt-location mstw-ss-cdt-location_$sched'>";
		
			$ret_str .= $opp_span . $opponent_entry . '</span>' . $loc_span . ' @ ' . $location_entry .  '</span><br/>';
			
			// Add the intro text set in shortcut arg or widget setting
			$intro_span = "<span class='mstw-ss-cdt-intro mstw-ss-cdt-intro_$sched'>";
			$ret_str .= $intro_span . $intro . '</span><br/>';
			
			// Add the countdown
			settype($game_dtg, 'integer');
			$countdown_span = "<span class='mstw-ss-cdt-countdown mstw-ss-cdt-countdown_$sched'>";
			$ret_str .= $countdown_span . mstw_ss_time_difference( $game_dtg - $current_dtg ) . '</span>';
			
			$ret_str .= '</div>';
		}
							
		return $ret_str;
		
	} //End: mstw_ss_build_countdown()
}

if( !function_exists( 'mstw_ss_build_game_countdown' ) ) {	
	function mstw_ss_build_game_countdown( $game, $intro_str='Gametime in:' ) {
		// empty string is returned if there is a problem
		$diff_str = '';
		
		// Get the game dtg & the current WP time
		$current_dtg = current_time( 'timestamp' );
		$game_dtg = get_post_meta( $game->ID, 'game_unix_dtg', true );
		
		if ( $game_dtg != '' and $current_dtg < $game_dtg ) {
			// Build the countdown string
			$diff_str = mstw_ss_time_difference( $game_dtg - $current_dtg );
			// countdown string should come from the options (display settings)
			$diff_str = "$intro_str $diff_str";
		}
		
		return $diff_str;

	} //End: mstw_ss_build_game_countdown()
}

// --------------------------------------------------------------------------------------
// 17. mstw_ss_time_difference - Converts a time (PHP timestamp in seconds) to
//		a string of in years, months, days, hours, minutes, seconds. Note that
//		$endtime is normally a difference between two timestamps (in seconds)
//
//	ARGUMENTS: 
//		$endtime - time to convert in seconds (PHP timestamp)
//
//	RETURNS:
//		HTML displaying $endtime as years, months, days, hours, minutes, seconds
//
if ( !function_exists ( 'mstw_ss_time_difference' ) ) {
	function mstw_ss_time_difference( $endtime ) {
		$days = (date("j",$endtime)-1);
		$months = (date("n",$endtime)-1);
		$years = (date("Y",$endtime)-1970);
		$hours = date("G",$endtime);
		$mins = date("i",$endtime);
		$secs = date("s",$endtime);
		$diff = '';
		
		if ($years > 0 )
			$diff .= $years . ' ' . __('years', 'mstw-schedules-scoreboards') . ', ';
		if ($months > 0 )
			$diff .= $months . ' ' . __('months', 'mstw-schedules-scoreboards') . ', ';
		if ($days > 0 )
			$diff .= $days . ' ' . __('days', 'mstw-schedules-scoreboards') . ', ';
		if ($hours > 0 )
			$diff .= $hours . ' ' . __('hours', 'mstw-schedules-scoreboards') . ', ';
		
		$diff .= $mins . ' ' . __('minutes', 'mstw-schedules-scoreboards');
		
		return $diff;
	} //End: mstw_ss_time_string()
}

// --------------------------------------------------------------------------------------
// 18. mstw_ss_numeral_to_ordinal - Converts number to the corresponding ordinal 
//
//	ARGUMENTS: 
//		$nbr - numeral to convert (should be a positive integer)
//
//	RETURNS:
//		Corresponding ordinal as a string
//
if ( !function_exists ( 'mstw_ss_numeral_to_ordinal' ) ) {
	function mstw_ss_numeral_to_ordinal( $nbr ) {
		// no negative numbers allowed
		if ( $nbr < 0 ) {
			$nbr = -$nbr;
		}
		
		// integers oanly
		$nbr = floor( $nbr );
		
		if( 10 < $nbr && $nbr < 20 ) {
			$ret = $nbr . __( 'th', 'mstw-schedules-scoreboards' );
		}
		else {
			$mod_nbr = $nbr % 10;
			
			switch ( $nbr % 10 ) {
				case 1:
					$ret = $nbr . __( 'st', 'mstw-schedules-scoreboards' );
					break;
				case 2:
					$ret = $nbr . __( 'nd', 'mstw-schedules-scoreboards' );
					break;
				case 3:
					$ret = $nbr . __( 'rd', 'mstw-schedules-scoreboards' );
					break;
				default: // 0,4,5,6,7,8,9
					$ret = $nbr . __( 'th', 'mstw-schedules-scoreboards' );
					break;				
			}
		}
		
		return $ret;
		
	} //End: mstw_ss_numeral_to_ordinal()
}

//----------------------------------------------------------------
// 19. mstw_ss_admin_notice - Displays all admin notices; callback for admin_notices action
//
//	ARGUMENTS: 	None
//
//	RETURNS:	None. Displays all messages in the 'mstw_ss_admin_messages' transient
//
if ( !function_exists ( 'mstw_ss_admin_notice' ) ) {
	function mstw_ss_admin_notice( ) {
		//mstw_log_msg( 'in mstw_ss_admin_notice ... ' );
		if ( get_transient( 'mstw_ss_admin_messages' ) !== false ) {
			// get the types and messages
			$messages = get_transient( 'mstw_ss_admin_messages' );
			// display the messages
			foreach ( $messages as $message ) {
				$msg_type = $message['type'];
				$msg_notice = $message['notice'];
				
				// Kludge to get warning messages to appear after page title
				$msg_type = ( $msg_type == 'warning' ) ? $msg_type . ' updated' : $msg_type ;
			?>
				<div class="<?php echo $msg_type; ?>">
					<p><?php echo $msg_notice; ?></p>
				</div>
			
			<?php
			}
			//mstw_log_msg( 'deleting transient ... ' );
			delete_transient( 'mstw_ss_admin_messages' );
			
		} //End: if ( get_transient( sss_admin_messages ) )
	} //End: function sss_admin_notice( )
}

//----------------------------------------------------------------
//Convenience function to create admin notices with no stress
//
//----------------------------------------------------------------
// 20. mstw_ss_add_admin_notice - Adds admin notices to transient for display on admin_notices hook
//
//	ARGUMENTS: 	$type - type of notice [updated|error|update-nag|warning]
//				$notice - notice text
//
//	RETURNS:	None. Stores notice and type in transient for later display on admin_notices hook
//
if ( !function_exists ( 'mstw_ss_add_admin_notice' ) ) {
	function mstw_ss_add_admin_notice( $type = 'updated', $notice ) {
		//default type to 'updated'
		if ( !( $type == 'updated' or $type == 'error' or $type =='update-nag' or $type == 'warning' ) ) $type = 'updated';
		
		//set the admin message
		$new_msg = array( array(
							'type'	=> $type,
							'notice'	=> $notice
							)
						);

		//either create or add to the sss_admin transient
		$existing_msgs = get_transient( 'mstw_ss_admin_messages' );
		
		if ( $existing_msgs === false ) {
			// no transient exists, create it with the current message
			set_transient( 'mstw_ss_admin_messages', $new_msg, HOUR_IN_SECONDS );
		} 
		else {
			// transient exists, append current message to it
			$new_msgs = array_merge( $existing_msgs, $new_msg );
			set_transient ( 'mstw_ss_admin_messages', $new_msgs, HOUR_IN_SECONDS );
		}
	} //End: function sss_add_admin_notice( )
}

//----------------------------------------------------------------
// 21. mstw_find_game_venue - Finds the venue (CPT object) for a game (CPT object)
//
//	ARGUMENTS: 	$game_obj - an mstw_ss_game CPT object
//
//	RETURNS:	$venue_obj - an mstw_ss_venue CPT object OR null if venue is not found
//

if ( !function_exists ( 'mstw_ss_find_game_venue' ) ) {
	function mstw_ss_find_game_venue( $game_obj ) {

		$venue_slug = get_post_meta( $game_obj->ID, 'game_gl_location', true );
		
		if ( $venue_slug != -1 ) {
			//this is normally for neutral site games
			$venue_obj = get_page_by_path( $venue_slug, OBJECT, 'mstw_ss_venue' );
		}
		else {
			$is_home_game = get_post_meta( $game_obj->ID, 'game_is_home_game', true );
			//mstw_log_msg( 'in mstw_ss_find_game_venue ... $is_home_game= ' . $is_home_game );
			if ( $is_home_game ) {
				//find the game schedule
				$sched_slug = get_post_meta( $game_obj->ID, 'game_sched_id', true );
				//mstw_log_msg( 'in mstw_ss_find_game_venue ... $sched_slug= ' . $sched_slug );
				$sched_obj = get_page_by_path( $sched_slug, OBJECT, 'mstw_ss_schedule' );
				//find the team for that schedule
				$team_slug = get_post_meta( $sched_obj->ID, 'schedule_team', true );
				$team_obj =  get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
			}
			else {
				//find the opponent team
				$team_slug = get_post_meta( $game_obj->ID, 'game_opponent_team', true );
				$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
			}
			// find the team's home venue
			if ( $team_obj === null ) {
				$venue_obj = null;
			}
			else {
				$venue_slug = get_post_meta( $team_obj->ID, 'team_home_venue', true );
				$venue_obj = get_page_by_path( $venue_slug, OBJECT, 'mstw_ss_venue' );
			}
		}
		
		return $venue_obj;
		
	} //End: mstw_ss_find_game_venue()
}

?>
