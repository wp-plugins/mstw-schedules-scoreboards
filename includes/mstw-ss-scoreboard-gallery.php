<?php
/*---------------------------------------------------------------------------
 *	mstw-ss-scoreboard-gallery.php
 *	Contains the code for the MSTW Schedules & Scoreboards scoreboard
 *		gallery display. shortcode [mstw_ss_scoreboard format=gallery]
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

//---------------------------------------------------------------------------
// MSTW_SS_BUILD_SCOREBOARD_GALLERY
// 	Called by mstw_ss_scoreboard_shortcode_handler( )
// 	Builds the xcoreboard gallery as a string (to replace the [shortcode] in a page or post.
// 	Loops through the games on a scoreboard and formats them into a pretty HTML gallery.
// ARGUMENTS:
// 	$args - the display settings and shortcode arguments, properly combined  & defaulted by mstw_ss_scoreboard_shortcode_handler()
// RETURNS
//	HTML for scoreboard gallery as a string
//
if ( !function_exists( 'mstw_ss_build_scoreboard_gallery' ) ) {
	function mstw_ss_build_scoreboard_gallery( $args ) {

		//mstw_log_msg( " In mstw_ss_build_scoreboard_gallery ... " );
		//mstw_log_msg( $args );
		
		//cf: mstw_ss_get_sb_defaults() in mstw-ss-utility-functions.php for
		//	the resulting variable names & defaults
		extract( $args );
		
		if ( !isset( $sort_order ) or ( $sort_order != 'DESC' ) ) {
			$sort_order = 'ASC';
		}
		
		$output = ''; //This is the return string
		//$output = "<h3 class='mstw-sb-msg'>in mstw_ss_build_scoreboard_gallery() scoreboard ID: {$args['sb']}</h3>";
		//return $output;
		
		$games = get_posts( array( 'numberposts' => -1,
								  'post_type' => 'mstw_ss_game',
								  'mstw_ss_scoreboard' => $sb,  
								  'orderby' => 'meta_value', 
								  'meta_key' => 'game_unix_dtg',
								  'order' => $sort_order 
								) );						
		
		//mstw_log_msg( $games );
		if ( ( $nbr_of_games = count( $games ) ) > 0 ) {
			//schedule-container
			$output .= "\n<div class=sbg-schedule-container> \n";
				//$output .= "schedule-container. nbr_of_games = $nbr_of_games \n";
				$i = 0;
				$curr_dtg = -1;
			
				while ( $i < $nbr_of_games ) {
					
						//mstw_log_msg( 'in mstw_ss_build_scoreboard_gallery game ID: ' . $games[$i]->ID );
						
						//need only the date for date header
						$date_stamp = strtotime( date("Y-m-d", get_post_meta( $games[$i]->ID, 'game_unix_dtg', true ) ) );
						
						if ( $curr_dtg < $date_stamp ) {
							$curr_dtg = get_post_meta( $games[$i]->ID, 'game_unix_dtg', true ) ;
							//big date container for all games on a date
							//if it's not the first date, close the last date's div
							if( $i > 0 ) {
								//$output .= "</div> <!-- .sbg-games-container --> \n";
								$output .= "</div> <!-- .sbg-schedule-day-container --> \n";
							}
							
							//date header "Sunday, 14 March 2014"
							if ( !isset( $date_format ) or  empty( $date_format ) ) {
								$date_format = 'l, j F Y';
							}
							$output .= mstw_sbg_date_header( $date_format, $curr_dtg );
						
							$output .= "\n<div class=sbg-schedule-day-container> \n";
							//$output .= "<div class=sbg-games-container> \n";
						}
						
						
						//game block
						$output .= "<div class=sbg-game-block> \n";
							
							//builds the game header w/ all div tags
							$output .= mstw_sbg_game_header( $games[$i], $time_format );

							//visiting team block (row)
							$output .= mstw_sbg_visitor_row( $games[$i], $args );		
								
							//home team block
							$output .= mstw_sbg_home_row( $games[$i], $args );
							
						$output .= "</div> <!-- .sbg-game-block --> \n";

					$i++;
					
				} //End: while( $i < $nbr_of_games
			//$output .= "</div> <!-- .sbg-games-container --> \n";
			$output .= "</div> <!-- .sbg-schedule-day-container --> \n";		
			$output .= "</div> <!-- .sbg-schedule-container --> \n";
		}
		else {
			$output = "<h3 class='mstw-sb-msg'>No games found for scoreboard: $sb</h3>";
		}
		
		return $output;

	} //End function mstw_ss_build_scoreboard_gallery( )
}

//---------------------------------------------------------------------------
// MSTW_SBG_DATE_HEADER
// 	Builds the date headers for a scoreboard gallery 
// 		Called by mstw_ss_build_scoreboard_gallery( )
//
// ARGUMENTS:
// 	$curr_dtg - unix timestamp for the date header
// RETURNS
//	Scoreboard date header as an HTML string
//
if ( !function_exists( 'mstw_sbg_date_header' ) ) {
	function mstw_sbg_date_header( $date_format, $curr_dtg ) {
		$ret = "<h4 class=sbg-date-header> \n";
			$date_format = !empty( $date_format ) ? $date_format : 'l, j F Y' ; 
			$ret .= date( $date_format, $curr_dtg );
		$ret .= "</h4> <!-- .sbg-date-header --> \n";
		return $ret;
	} //End: mstw_sbg_date_header()
}

//---------------------------------------------------------------------------
// MSTW_SBG_GAME_HEADER
// 	Builds the game headers for a scoreboard gallery 
// 		Called by mstw_ss_build_scoreboard_gallery( )
//
// ARGUMENTS:
// 	$game - an mstw_ss_game CPT
//	$time_format - date() function format string for game time
// RETURNS
//	Game header as an HTML string
//
if ( !function_exists( 'mstw_sbg_game_header' ) ) {
	function mstw_sbg_game_header( $game, $time_format ) {
		$ret = "<div class=sbg-game-header> \n";
			
			$game_is_final = get_post_meta( $game->ID, 'game_is_final', true );
			$home_score = get_post_meta( $game->ID, 'game_our_score', true );
			
			// if game is not final but there's game data, show game 
			// start time but not score header (-> game not started)
			if( !$game_is_final and $home_score == '' ) {
				$ret .= "<div class=sbg-header-status> \n";
				$ret .= "<p class=sbg-header-status>";
				$game_time_tba = get_post_meta( $game->ID, 'game_time_tba', true );
				if ( !empty( $game_time_tba ) ) {
					$ret .= get_post_meta( $game->ID, 'game_time_tba', true );
				}
				else {
					$ret .= date( $time_format, get_post_meta( $game->ID, 'game_unix_dtg', true ) );	
				}
				$ret .= "</p></div> <!-- .sbg-header-status -->\n";
			}
			// if game is final, show that status and score
			else if ( $game_is_final ) {
				$ret .= "<div class=sbg-header-status> \n";
				$ret .= "<p class=sbg-header-status>";
				$ret .= __( 'FINAL', 'mstw-schedules-scoreboards' );
				$ret .= "</p></div> <!-- .sbg-header-status -->\n";
				
				$ret .= "<div class=sbg-header-score> \n";
				$ret .= "<p class=sbg-header-score>";
				$ret .= __( 'SCORE', 'mstw-schedules-scoreboards' );
				$ret .= "</p></div> <!-- .sbg-header-score -->\n";
			}
			// game is in progress, show the period and the time
			else {
				$ret .= "<div class=sbg-header-status> \n";
				$time = get_post_meta( $game->ID, 'game_curr_time', true );
				$period = get_post_meta( $game->ID, 'game_curr_period', true );
				//mstw_log_msg( 'building scoreboard header: $period = ' . $period . ' is numeric??' );
				if ( is_numeric( $period ) ) {
					
					$period = mstw_ss_numeral_to_ordinal( $period );
				}
				
				$time_label = __( 'Time:', 'mstw-schedules-scoreboards');
				$period_label = __( 'Period:', 'mstw-schedules-scoreboards' );
				$ret .= "<p class=sbg-header-status>";
				$ret .= "$time_label $time $period";
				$ret .= "</p></div> <!-- .sbg-header-status -->\n";
				
				$ret .= "<div class=sbg-header-score> \n";
				$ret .= "<p class=sbg-header-score> \n";
				$ret .= __( 'SCORE', 'mstw-schedules-scoreboards' );;
				$ret .= "</p></div> <!-- .sbg-header-score -->\n";
			}

		$ret .= "</div> <!-- .sbg-game-header --> \n";
		
		return $ret;
	} //End: mstw_sbg_game_header()
}

//---------------------------------------------------------------------------
// MSTW_SBG_VISITOR_ROW
// 	Builds the visiting team row for a scoreboard gallery 
// 		Called by mstw_ss_build_scoreboard_gallery( )
//
// ARGUMENTS:
// 	$game - an mstw_ss_game CPT
//	$args - combined shortcode args, settings, and defaults
//	
// RETURNS
//	Game header as an HTML string
//
if ( !function_exists( 'mstw_sbg_visitor_row' ) ) {
	function mstw_sbg_visitor_row( $game, $args ) {
		//find the visiting team info from $game
		$team_slug = get_post_meta( $game->ID, 'game_opponent_team', true);
		
		if ( !empty( $team_slug ) ) {
			$visitor_score = get_post_meta( $game->ID, 'game_opp_score', true );
			$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
			
			$div_str = "<div class='sbg-team sbg-visitor'> \n";
			if( $args['highlight_winner'] && get_post_meta( $game->ID, 'game_is_final', true ) ) {
				$home_score = get_post_meta( $game->ID, 'game_our_score', true );
				if ( $visitor_score > $home_score ) {
					$div_str = "<div class='sbg-team sbg-visitor sbg-winner'> \n";
				}
			}
			$ret = $div_str;
			
			if( $team_obj ) {
				$visitor_logo = get_post_meta( $team_obj->ID, 'team_logo', true );
				$visitor_name = get_post_meta( $team_obj->ID, 'team_full_name', true );
				$visitor_mascot = get_post_meta( $team_obj->ID, 'team_full_mascot', true );
				if ( $args['show_logo'] and !empty( $visitor_logo ) ) {
					$ret .= "<div class=sbg-team-logo> \n";
					$ret .= "<img src='$visitor_logo' />";
					$ret .= "</div> <!-- .sbg-team-logo --> \n";
				}
				if ( ( $args['show_name'] > 0 ) ) { 
				//anything but logo only
					$ret .= "<div class=sbg-team-name> \n";
					switch ( $args['show_name'] ) {
						case 1: //full name only
							$team_name = $visitor_name;
							break;
						case 2: //full mascot only
							$team_name = $visitor_mascot;
							break;
						case 3: //full name and mascot
						default:
							$team_name = $visitor_name . ' ' . $visitor_mascot;
							break;
					}
					$ret .= "<p class=sbg-team-name>$team_name</p>";
					$ret .= "</div> <!-- .sbg-team-name --> \n";
				}
			}
			else {
				$ret .= "<div class=sbg-team-name><p class=sbg-team-name>Team not found. Text entry?</p></div>";
			}
			if ( !empty( $visitor_score ) or $visitor_score == 0 ) {
				$ret .= "<div class=sbg-team-score> \n";
				$ret .= "<p class=sbg-team-score>$visitor_score</p>";
				$ret .= "</div> <!-- .sbg-team-score --> \n";
			}
			$ret .= "</div> <!-- .sbg-team.sbg-visitor --> \n";		
		}	
		else {
			$ret = "Schedule slug not found";
		}
		
		return $ret;
		
	} //End: mstw_sbg_visitor_row()
}

//---------------------------------------------------------------------------
// MSTW_SBG_HOME_ROW
// 	Builds the home team row for a scoreboard gallery 
// 		Called by mstw_ss_build_scoreboard_gallery( )
//
// ARGUMENTS:
// 	$game - an mstw_ss_game CPT
//	
// RETURNS
//	Game header as an HTML string
//
if ( !function_exists( 'mstw_sbg_home_row' ) ) {
	function mstw_sbg_home_row( $game, $args ) {
		//find the home team info from $game
		//first find the schedule id
		$sched_slug = get_post_meta( $game->ID, 'game_sched_id', true);
		if ( !empty( $sched_slug ) ) {
			//now find the home team info from the schedule
			$sched_obj = get_page_by_path( $sched_slug, OBJECT, 'mstw_ss_schedule' );
			if( $sched_obj ) {
				$home_team_slug = get_post_meta( $sched_obj->ID, 'schedule_team', true );
				$team_obj = get_page_by_path( $home_team_slug, OBJECT, 'mstw_ss_team' );
				
				$home_logo = get_post_meta( $team_obj->ID, 'team_logo', true );
				$home_name = get_post_meta( $team_obj->ID, 'team_full_name', true );
				$home_mascot = get_post_meta( $team_obj->ID, 'team_full_mascot', true );
				$home_score = get_post_meta( $game->ID, 'game_our_score', true );
				
				$div_str = "<div class='sbg-team sbg-home'> \n";
				if( $args['highlight_winner'] && get_post_meta( $game->ID, 'game_is_final', true ) ) {
					$visitor_score = get_post_meta( $game->ID, 'game_opp_score', true );
					if ( $visitor_score < $home_score ) {
						$div_str = "<div class='sbg-team sbg-home sbg-winner'> \n";
					}
				}
				
				$ret = $div_str;
				
				if ( $args['show_logo'] && !empty( $home_logo ) ) {
					$ret .= "<div class=sbg-team-logo> \n";
					$ret .= "<img src='$home_logo' />";
					$ret .= "</div> <!-- .sbg-team-logo --> \n";
				}
				if ( $args['show_name'] > 0 ) {
				//anything but logo only
					$ret .= "<div class=sbg-team-name> \n";
					switch ( $args['show_name'] ) {
						case 1: //full name only
							$team_name = $home_name;
							break;
						case 2: //full mascot only
							$team_name = $home_mascot;
							break;
						case 3: //full name and mascot
						default:
							$team_name = $home_name . ' ' . $home_mascot;
							break;
					}
					$ret .= "<p class=sbg-team-name>$team_name</p>";
					$ret .= "</div> <!-- .sbg-team-name --> \n";
				}
				if ( !empty( $home_score ) or $home_score == 0 ) {
					$ret .= "<div class=sbg-team-score> \n";
					$ret .= "<p class=sbg-team-score>$home_score</p>";
					$ret .= "</div> <!-- .sbg-team-score --> \n";
				}
				$ret .= "</div> <!-- .sbg-home --> \n";
			}
			else {
				$ret = "Schedule: $sched_slug has a problem.";
			}
		}
		else {
			$ret = "Schedule slug not found";
		}
		
		return $ret;
	} //End: mstw_sbg_home_row()
}	
?>