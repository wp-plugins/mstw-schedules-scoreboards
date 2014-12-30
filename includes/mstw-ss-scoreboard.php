<?php
 /*---------------------------------------------------------------------------
 *	mstw-ss-scoreboard.php
 *	Contains the code for the MSTW Schedules & Scoreboards scoreboard
 *		displays. shortcode [mstw_ss_scoreboard]
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

// --------------------------------------------------------------------------------------
// Add the shortcode handler, which creates the Scoreboard display on the user side.
// Handles the shortcode parameters, merges them with the defaults, then loads and calls the appropriate functions to generate the output
// 
add_shortcode( 'mstw_scoreboard', 'mstw_ss_scoreboard_shortcode_handler' );

function mstw_ss_scoreboard_shortcode_handler( $atts ){
	// get the options set in the admin display settings screen
	$options = get_option( 'mstw_ss_scoreboard_options' );

	// and merge them with the defaults
	$defaults = mstw_ss_get_sb_defaults( );
	
	$args = wp_parse_args( $options, $defaults );
	//$output .= '<pre>ARGS:' . print_r( $args, true ) . '</pre>';
	//return $output;
		
	//merge the shortcode args with the result
	$attribs = shortcode_atts( $args, $atts );
		
	$output = '';
	
	if( !isset( $attribs['sb'] ) or empty( $attribs['sb'] ) ) {
		$output = "<h3 class='mstw-sb-msg'>No scoreboard ID provided to [mstw_scoreboard].</h3>";
		return $output;
	}
	
	//mstw_log_msg( 'in mstw_ss_scoreboard_shortcode_handler()' );
	//mstw_log_msg( $attribs );
	//mstw_log_msg( 'in mstw_ss_scoreboard_shortcode_handler() ... format= ' . $attribs['format'] );
	
	switch ( $attribs['format'] ) {
		case 'table':
			$output = mstw_ss_build_scoreboard_table( $attribs );
			break;
		case 'single':
		case 'gallery':
			include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-scoreboard-gallery.php' );
			$output = mstw_ss_build_scoreboard_gallery( $attribs );
			break;
		case 'slider':
		case 'ticker':
			include_once( MSTW_SS_INCLUDES_DIR . '/mstw-ss-scoreboard-ticker.php' );
			$output = mstw_ss_build_scoreboard_ticker( $attribs );
			break;
		default:
			$output = "<h3 class='mstw-sb-msg'>[mstw_scoreboard] shortcode ... unrecognized format: {$attribs['format']}</h3";
			break;
	}
		
	return $output;
	
} //End: mstw_ss_scoreboard_shortcode_handler()

//--------------------------------------------------------------------------------------
// MSTW_SS_BUILD_SCOREBOARD_TABLE
// 	Called by mstw_ss_scoreboard_shortcode_handler( )
// 	Builds the Scoreboard table as a string (to replace the [shortcode] in a page or post.
// 	Loops through the games on a scoreboard and formats them into a pretty HTML table.
// ARGUMENTS:
// 	$args - the display settings and shortcode arguments, properly combined  & defaulted by mstw_ss_scoreboard_shortcode_handler()
// RETURNS
//	HTML for scoreboard table as a string
//
if ( !function_exists( 'mstw_ss_build_scoreboard_table' ) ) {
	function mstw_ss_build_scoreboard_table( $args ) {

		//mstw_log_msg( " In mstw_ss_build_schedule_table ... " );
		//mstw_log_msg( $args );

		$output = ''; //This is the return string
		
		//mstw_log_msg( 'in mstw_ss_build_scoreboard_table() format: ' . $args['format'] );
		$output .= "<h3 class='mstw-sb-msg'>" . __( 'The scoreboard table format is not available at the time.', 'mstw-schedules-scoreboards' ) . "</h3>";
		
		/*
		//Pull the $args array into individual variables
		extract( $args );
		
		$scheds = explode( ',', $sched );
		
		if ( $scheds[0] == '' ) {
			return '<h3>' . __( 'No schedule specified.', 'mstw-schedules-scoreboards' ) . '</h3>';
		}
		
		//This changes if and only if last_dtg == now
		$sort_order = 'ASC';
		
		//full date format 
		$dtg_format = ( $table_date_format == 'custom' ? $custom_table_date_format : $table_date_format ); 
		
		//time format
		$time_format = ( $table_time_format == 'custom' ? $custom_table_time_format : $table_time_format );

		// Need to set $first_dtg and $last_dtg by converting strings
		// OR convert $first_dtg='now' to current php DTG stamp
		if ( $first_dtg == 'now' ) {
			$first_dtg = time( );
		}
		else { 
			$first_dtg = strtotime( $first_dtg );
		}		
		$first_dtg = ( $first_dtg <= 0 ? 1 : $first_dtg );
		
		if ( $last_dtg == 'now' ) {
			$sort_order = 'DESC';
			$last_dtg = time( );
		}
		else { 
			$last_dtg = strtotime( $last_dtg );
		}

		$last_dtg = ( $last_dtg <= 0 ? PHP_INT_MAX : $last_dtg );	
		
		// Get the games posts
		$games = get_posts( array( 'numberposts' => $games_to_show,
								  'post_type' => 'mstw_ss_game',
								  'meta_query' => array(
													'relation' => 'AND',
													array(
														'key' => 'game_sched_id',
														'value' => $scheds,
														'compare' => 'IN',
													),
													array(
														'key' => 'game_unix_dtg',
														'value' => array( $first_dtg, $last_dtg),
														'type' => 'NUMERIC',
														'compare' => 'BETWEEN'
													)
												),
								  
								  'orderby' => 'meta_value', 
								  'meta_key' => 'game_unix_dtg',
								  'order' => $sort_order 
								) );						
		
		if ( $games ) {
			// Make table of posts
			// Start with the table header
			$output .= '<table class="mstw-ss-table">'; 
			$output .= "<thead class='mstw-ss-table-head mstw-ss-table-head_" . $scheds[0] . "'><tr>";
			if( $show_date ) { 
				$label = sanitize_title( $date_label );
				$output .= "<th class='col-1'>" . __( $date_label, 'mstw-schedules-scoreboards' ) . '</th>'; //'<th>'. $date_label . '</th>';
			}
			
			$output .= '<th class="col-2">'. __( $opponent_label, 'mstw-schedules-scoreboards' ) . '</th>';
			
			if( $show_location ) {
				$output .= '<th class="col-3">'. __( $location_label, 'mstw-schedules-scoreboards' ) . '</th>';
			}
			
			if( $show_time ) {
				$output .= '<th class="col-4">'. __( $time_label, 'mstw-schedules-scoreboards' ) . '</th>';
			}
			
			if ( $show_media > 0 ) { 
				$output .= '<th class="col-5">'.  __( $media_label, 'mstw-schedules-scoreboards' ) . '</th>';
			}
			
			$output .= '</tr></thead>';
			
			   
			// Keeps track of even and odd rows. Start with row 1 = odd.
			$even_and_odd = array('even', 'odd');
			$row_cnt = 1; 
		
			// Loop through the posts and make the rows
			foreach( $games as $game ) {
				// set up some housekeeping to make styling in the loop easier
				
				$even_or_odd_row = $even_and_odd[$row_cnt]; 
				$row_class = 'mstw-ss-' . $even_or_odd_row;
				$row_class .= ' ' . $row_class . '_' . $scheds[0];
				
				$is_home_game = get_post_meta($game->ID, 'game_is_home_game', true );
				if ( $is_home_game == 'home' ) 
					$row_class .= ' mstw-ss-home';
				
				$row_tr = '<tr class="' . $row_class . '">';
				//$row_td = '<td class="' . $row_class . '">';
				$td_1 = '<td class="' . $row_class . ' col-1">';
				$td_2 = '<td class="' . $row_class . ' col-2">';
				$td_3 = '<td class="' . $row_class . ' col-3">';
				$td_4 = '<td class="' . $row_class . ' col-4">';
				$td_5 = '<td class="' . $row_class . ' col-5">';
				
				// create the row
				$row_string = $row_tr;			
				
				// column 1: Build the game date in a specified format
				if ( $show_date ) {
					$new_date_string = mstw_date_loc( $dtg_format, (int)get_post_meta( $game->ID, 'game_unix_dtg', true ) );

					$row_string = $row_string. $td_1 . $new_date_string . '</td>';	
				}
				
				// column 2: create the opponent entry ALWAYS SHOWN
				$opponent_entry = mstw_ss_build_opponent_entry( $game, $args, "table" );
				$row_string =  $row_string . $td_2 . $opponent_entry . '</td>';
				
				// column 3: create the location entry
				if ( $show_location ) {
					$location_entry = mstw_ss_build_location_entry( $game, $args );
					$row_string =  $row_string . $td_3 . $location_entry . '</td>';
				}
				
				// column 4: create the time/results entry
				// 20120221-MAO: Rewritten to handle new game time entry logic
				//		and to use time format settings
				
				if ( $show_time ) {
					// $time_entry = mstw_ss_build_time_entry( $game );
					// If there is a game result, stick it in and we're done
					$game_result = get_post_meta( $game->ID, 'game_result', true); 
					if ( $game_result != '' ) {
						$row_string .=  $td_4 . $game_result . '</td>';
					}
					else {	
						// There's no game result, so add a game time
						// Check if the game time is TBA
						$time_is_tba = get_post_meta( $game->ID, 'game_time_tba', true );
						
						if ( $time_is_tba != '' ) {	
							//Time is TBA. Stick it in and we're done
							$row_string .=  $td_4 . $time_is_tba . '</td>';
						}
						else {	
							//Time is not TBA. Build the time string from the unix timestamp
							$unix_dtg = get_post_meta( $game->ID, 'game_unix_dtg', true );
							$time_str = date( $time_format, $unix_dtg );
							$row_string .=  $td_4 . $time_str . '</td>';
						}	
					}
				}
				
				// column 5: create the media listings in a pretty format 
				
				if( $show_media > 0 ) { //if ( $show_media ) {
					$media_links = $td_5 . "";
					
					$mstw_media_label_1 = trim( get_post_meta($game->ID, 'game_media_label_1', true ) );
					if ( $mstw_media_label_1 <> "" ) {
						$mstw_media_url_1 = trim( get_post_meta($game->ID, 'game_media_url_1', true ) );
						if ( $mstw_media_url_1 <> "" ) {
							// build the link
							$href = '<a href="' . $mstw_media_url_1 . '" target="_blank">' . $mstw_media_label_1 .'</a>';
						}
						else {
							$href = $mstw_media_label_1; 
						}
						$media_links = $media_links . $href;
						
						$mstw_media_label_2 = trim( get_post_meta($game->ID, 'game_media_label_2', true ) );
						if ( $show_media > 1 and $mstw_media_label_2 <> "" ) {
							$mstw_media_url_2 = trim( get_post_meta($game->ID, 'game_media_url_2', true ) );
							if ( $mstw_media_url_2 <> "" ) {
								// build the link
								$href = '<a href="' . $mstw_media_url_2 . '" target="_blank">' . $mstw_media_label_2 .'</a>';
							}
							else {
								$href = $mstw_media_label_2; 
							}
							$media_links = $media_links . " | " . $href;
							
							$mstw_media_label_3 = trim( get_post_meta($game->ID, 'game_media_label_3', true ) );
							if ( $show_media > 2 and $mstw_media_label_3 <> "" ) {
								$mstw_media_url_3 = trim( get_post_meta($game->ID, 'game_media_url_3', true ) );
								if ( $mstw_media_url_3 <> "" ) {
									// build the link
									$href = '<a href="' . $mstw_media_url_3 . '" target="_blank">' . $mstw_media_label_3 .'</a>';
								}
								else {
									$href = $mstw_media_label_3; 
								}
								$media_links = $media_links . " | " . $href;
							}
						}
					}
					
					$row_string .= $media_links . '</td>';  //			Should have a </tr> here??
				}
				
				//$output = $output . $row_string;
				$output .= $row_string . '</tr>';
				
				$row_cnt = 1- $row_cnt;  // Get the styles right
				
			} // end of foreach game
			
			$output = $output . '</table>';
		}
		else { // No posts were found
			$output =  '<h3>' . __( 'No games found for ', 'mstw-schedules-scoreboards' ) .$scheds[0] . '.</h3>';	
		}
		*/
		
		return $output;

	} //End function mstw_ss_build_scoreboard_table( )
}	
	
?>