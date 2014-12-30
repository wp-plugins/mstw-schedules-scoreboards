<?php
/*---------------------------------------------------------------------------
 *	mstw-ss-scoreboard-ticker.php
 *	Contains the code for the MSTW Schedules & Scoreboards scoreboard
 *		ticker display. shortcode [mstw_ss_scoreboard format=ticker]
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
// MSTW_SS_BUILD_SCOREBOARD_TICKER
// 	Called by mstw_ss_scoreboard_shortcode_handler( )
// 	Builds the xcoreboard ticker as a string (to replace the [shortcode] in a page or post.
// 	Loops through the games on a scoreboard and formats them into a pretty HTML gallery.
// ARGUMENTS:
// 	$args - the display settings and shortcode arguments, properly combined  & defaulted by mstw_ss_scoreboard_shortcode_handler()
// RETURNS
//	HTML for scoreboard gallery as a string
//
if ( !function_exists( 'mstw_ss_build_scoreboard_ticker' ) ) {
	function mstw_ss_build_scoreboard_ticker( $args ) {

		//mstw_log_msg( " In mstw_ss_build_scoreboard_ticker ... " );
		//mstw_log_msg( $args );
		
		//cf: mstw_ss_get_sb_defaults() in mstw-ss-utility-functions.php for
		//	the resulting variable names & defaults
		extract( $args );
		
		$output = ''; //This is the return string
		
		// do we want to do sort order for ticker? nah?
		//if ( !isset( $sort_order ) or ( $sort_order != 'DESC' ) ) {
			$sort_order = 'ASC';
		//}
		// Want to display games in progress, final games, future games
		
		
		$final_games = get_posts( array( 'numberposts' => -1,
								  'post_type' => 'mstw_ss_game',
								  'mstw_ss_scoreboard' => $sb,   
								  'meta_key' => 'game_is_final',
								  'meta_value' => 1, 
								) );
		
		
		$all_games = get_posts( array( 'numberposts' => -1,
								  'post_type' => 'mstw_ss_game',
								  'mstw_ss_scoreboard' => $sb,  
								  'orderby' => 'meta_value', 
								  'meta_key' => 'game_unix_dtg',
								  'order' => $sort_order 
								) );
								
		
		//mstw_log_msg( " In mstw_ss_build_scoreboard_ticker ... " );
		//mstw_log_msg( 'final_games= ' . count( $final_games ) );
		//mstw_log_msg( 'all_games= ' . count( $all_games ) );
		
		if( $final_games ) {
			$games = array_merge( $final_games, $all_games );
			$games = array_unique( $games, SORT_REGULAR );
		}
		else {
			$games = $all_games;
		}								
		
		//mstw_log_msg( $games );
		
		
		if ( ( $nbr_of_games = count( $games ) ) > 0 ) {
			//container for entire ticker
			$output .= "\n<div class='sbt-schedule-container' id='sbt-schedule-container-$sb'>\n";
			
				//ticker header
				if( $args['sbt_show_header'] ) {
					$output .= "<div class='sbt-header' id='sbt-header-$sb'>\n";
					$output .= mstw_sbt_header( $args );
					$output .= "</div> <!-- .sbt-header --> \n";
				}
				// holds next and prev buttons and ticker content (game blocks)
				$output .= "<div class='sbt-ticker-holder'>\n";
				
					$output .= "<div class='sbt-prev' id='sbt-prev-$sb' style='display:block;'>\n";
					$output .= "</div> <!-- .sbt-prev --> \n";
					
					// contains the ticker content (game blocks)
					$output .= "<div class='sbt-ticker-content' id='sbt-ticker-content-$sb'>\n";
					$output .= mstw_sbt_ticker_content( $args, $games );
					$output .= "</div> <!-- .sbt-ticker-content --> \n";
					
					$output .= "<div class='sbt-next' id='sbt-next-$sb' style='display:block;'>\n";
					$output .= "</div> <!-- .sbt-next --> \n";
					
				
				$output .= "</div> <!-- .sbt-holder --> \n";
			
			$output .= "</div> <!-- .sbt-schedule-container --> \n";
		}
		else {
			$output = "<h3 class='mstw-sb-msg'>No games found for scoreboard: $sb</h3>";
		}
		
		return $output;

	} //End function mstw_ss_build_scoreboard_ticker( )
}

//---------------------------------------------------------------------------
// MSTW_SBT_HEADER
// 	Builds the scoreboard ticker header 
// 		Called by mstw_ss_build_scoreboard_ticker( )
//
// ARGUMENTS:
// 	$args - the combined defaults, settings, and shortcode args
// RETURNS
//	Ticker header as an HTML string
//
if ( !function_exists( 'mstw_sbt_header' ) ) {
	function mstw_sbt_header( $args ) {
		//mstw_log_msg( ' in mstw_sbt_header ... $args=' );
		//mstw_log_msg( $args );
		
		$ret = ''; //default return is empty
		
		if( $args['sbt_show_header'] ) {
			$ret .= "<div class=sbt-title>\n";
				if( $args['sbt_title'] == '' ) {
					// default display the scoreboard name
					$scoreboard_obj = get_term_by( 'slug', $args['sb'], 'mstw_ss_scoreboard', OBJECT, 'raw' );
					if( $scoreboard_obj !== false ) {
						$ret .= $scoreboard_obj->name . ' ';
					}
					else {
						$ret .= $args['sb'];
					}
				}
				else {
					$ret .= $args['sbt_title'];
				}
			$ret .= "</div> <!-- .sbt-title -->\n";
			
			$ret .= "<div class=sbt-link>\n";
				if( $args['sbt_link_label'] != '' ) {
					if( $args['sbt_link_url'] != '' ) {
						$ret .= "<a href=" . $args['sbt_link_url'] . " target='_blank'>";
						$ret .= $args['sbt_link_label'] . '</a>';
					}
					else {
						$ret .= $args['sbt_link_label'];
					}
				}
			$ret .= "</div> <!-- .sbt-link -->\n"; 
			
			if( $args['sbt_message'] != '' ) {
				$ret .= '<div class=sbt-message>' . $args['sbt_message'] . '</div>';
			}
			
		}
		
		return $ret;
	} //End: mstw_sbt_header()
}

//---------------------------------------------------------------------------
// MSTW_SBT_TICKER_CONTENT
// 	Builds the ticker content for a scoreboard ticker 
// 		Called by mstw_ss_build_scoreboard_ticker( )
//
// ARGUMENTS:
//	$args - the combined defaults, settings, and shortcode args
// 	$games - array of games on a scoreboard
// RETURNS
//	Schedule ticker content as an HTML string
//

if ( !function_exists( 'mstw_sbt_ticker_content' ) ) {
	function mstw_sbt_ticker_content( $args, $games ) {
	
		$ret = '<ul>';

		foreach( $games as $game ) {
			//mstw_log_msg( $game );
			$ret .= '<li class=sbt-list-item>';
			
			$ret .= mstw_sbt_game_header( $args, $game );
			
			$ret .= mstw_sbt_opponent_line( $args, $game );
			
			$ret .= mstw_sbt_home_line( $args, $game );
			
			$ret .= '</li>';
				
		} 
		
		$ret .= '</ul>';
		
		return $ret;
		
	} //End: mstw_sbt_ticker_content()
}

//---------------------------------------------------------------------------
// MSTW_SBT_GAME_HEADER
// 	Builds the game headers for a scoreboard ticker 
// 		Called by mstw_sbt_ticker_content( )
//
// ARGUMENTS:
//	$args - the combined defaults, settings, and shortcode args
// 	$game - an mstw_ss_game CPT
// RETURNS
//	Game header as an HTML string
//
if ( !function_exists( 'mstw_sbt_game_header' ) ) {
	function mstw_sbt_game_header( $args, $game ) {
		$ret = "<div class=sbt-game-header> \n";
		
			$game_is_final = get_post_meta( $game->ID, 'game_is_final', true );
			$home_score = get_post_meta( $game->ID, 'game_our_score', true );
			
			// if game is not final but there's no game scores, show game 
			// start time but not score header (-> game not started)
			if( !$game_is_final and $home_score == '' ) {
				$ret .= "<p class=sbt-header-status>";
				$game_time_tba = get_post_meta( $game->ID, 'game_time_tba', true );
				if ( !empty( $game_time_tba ) ) {
					$ret .= get_post_meta( $game->ID, 'game_time_tba', true );
				}
				else {
					$ret .= date( $args['time_format'], get_post_meta( $game->ID, 'game_unix_dtg', true ) );	
				}
				$ret .= "</p> <!-- .sbt-header-status -->\n";
			}
			// if game is final, show that status and score
			else if ( $game_is_final ) {
				$ret .= "<p class=sbt-header-status>";
				$ret .= __( 'Final', 'mstw-schedules-scoreboards' );
				$ret .= "</p> <!-- .sbt-header-status -->\n";
			}
			// game is in progress, show the period and the time
			else {
				$time = get_post_meta( $game->ID, 'game_curr_time', true );
				$period = get_post_meta( $game->ID, 'game_curr_period', true );
				//mstw_log_msg( 'building scoreboard header: $period = ' . $period . ' is numeric??' );
				if ( is_numeric( $period ) ) {
					$period = mstw_ss_numeral_to_ordinal( $period );
				}
				
				//$time_label = __( 'Time:', 'mstw-schedules-scoreboards');
				//$period_label = __( 'Period:', 'mstw-schedules-scoreboards' );
				$ret .= "<p class=sbt-header-status>";
				$ret .= "$period $time";
				$ret .= "</p> <!-- .sbt-header-status -->\n";
			}
		
		$ret .= "</div>";
		
		return $ret;
			
			$game_is_final = get_post_meta( $game->ID, 'game_is_final', true );
			$home_score = get_post_meta( $game->ID, 'game_our_score', true );
			
			// if game is not final but there's no game scores, show game 
			// start time but not score header (-> game not started)
			if( !$game_is_final and $home_score == '' ) {
				$ret .= "<p class=sbt-header-status>";
				$game_time_tba = get_post_meta( $game->ID, 'game_time_tba', true );
				if ( !empty( $game_time_tba ) ) {
					$ret .= get_post_meta( $game->ID, 'game_time_tba', true );
				}
				else {
					$ret .= date( $args['time_format'], get_post_meta( $game->ID, 'game_unix_dtg', true ) );	
				}
				$ret .= "</p></div> <!-- .sbt-header-status -->\n";
			}
			// if game is final, show that status and score
			else if ( $game_is_final ) {
				$ret .= "<p class=sbt-header-status>";
				$ret .= __( 'Final', 'mstw-schedules-scoreboards' );
				$ret .= "</p></div> <!-- .sbt-header-status -->\n";
			}
			// game is in progress, show the period and the time
			else {
				$time = get_post_meta( $game->ID, 'game_curr_time', true );
				$period = get_post_meta( $game->ID, 'game_curr_period', true );
				//mstw_log_msg( 'building scoreboard header: $period = ' . $period . ' is numeric??' );
				if ( is_numeric( $period ) ) {
					$period = mstw_ss_numeral_to_ordinal( $period );
				}
				
				//$time_label = __( 'Time:', 'mstw-schedules-scoreboards');
				//$period_label = __( 'Period:', 'mstw-schedules-scoreboards' );
				$ret .= "<p class=sbt-header-status>";
				$ret .= "$period $time";
				$ret .= "</p></div> <!-- .sbt-header-status -->\n";
			}

		$ret .= "</div> <!-- .sbt-game-header --> \n";
		
		return $ret;
	} //End: mstw_sbt_game_header()
}

//---------------------------------------------------------------------------
// MSTW_SBT_OPPONENT_LINE
// 	Builds the opponent team row for a scoreboard ticker 
// 		Called by mstw_sbt_ticker_conent( )
//
// ARGUMENTS:
//	$args - combined shortcode args, settings, and defaults
// 	$game - an mstw_ss_game CPT
// RETURNS
//	Game header as an HTML string
//
if ( !function_exists( 'mstw_sbt_opponent_line' ) ) {
	function mstw_sbt_opponent_line( $args, $game ) {
		$ret = '';
		
		//find the opponent info from $game
		$team_slug = get_post_meta( $game->ID, 'game_opponent_team', true);
		
		if ( !empty( $team_slug ) ) {
			$opp_score = get_post_meta( $game->ID, 'game_opp_score', true );
			$team_obj = get_page_by_path( $team_slug, OBJECT, 'mstw_ss_team' );
			if( $team_obj ) {
				$opp_name = get_post_meta( $team_obj->ID, 'team_short_name', true );
			}
			else {
				$opp_name = "NTN";
			}
			
			$div_str = "<div class='sbt-team sbt-opp'> \n";
			if( $args['highlight_winner'] and get_post_meta( $game->ID, 'game_is_final', true ) ) {
				$home_score = get_post_meta( $game->ID, 'game_our_score', true );
				if ( $opp_score > $home_score ) {
					$div_str = "<div class='sbt-team sbt-opp sbt-winner'> \n";
				}
			}
			
			$ret .= $div_str;
			
			$ret .= "<p class=sbt-team-name>" . $opp_name . "</p> \n";
			
			if ( isset( $opp_score ) && ( !empty( $opp_score ) or $opp_score == 0 ) ){
				$ret .= "<p class=sbt-team-score>$opp_score</p> \n";
			}
			$ret .= "</div> <!-- .sbt-team.sbt-opp --> \n";		
		}	
		else {
			$ret = "NO SCHED SLUG";
		}
		
		return $ret;

	} //End: mstw_sbt_opponent_line()
}

//---------------------------------------------------------------------------
// MSTW_SBT_HOME_LINE
// 	Builds the home team line for a scoreboard ticker 
// 		Called by mstw_ss_build_scoreboard_gallery( )
//
// ARGUMENTS:
//	$args - combined shortcode args, settings, and defaults
// 	$game - an mstw_ss_game CPT	
// RETURNS
//	Game header as an HTML string
//
if ( !function_exists( 'mstw_sbt_home_line' ) ) {
	function mstw_sbt_home_line( $args, $game ) {
		//find the home team info from $game
		$home_score = get_post_meta( $game->ID, 'game_our_score', true );
		//first find the schedule id
		$ret = '';
		$sched_slug = get_post_meta( $game->ID, 'game_sched_id', true);
		if ( !empty( $sched_slug ) ) {
			//now find the home team info from the schedule
			$sched_obj = get_page_by_path( $sched_slug, OBJECT, 'mstw_ss_schedule' );
			if( $sched_obj ) {
				$home_team_slug = get_post_meta( $sched_obj->ID, 'schedule_team', true );
				$team_obj = get_page_by_path( $home_team_slug, OBJECT, 'mstw_ss_team' );
				if ( $team_obj ) {
					$home_name = get_post_meta( $team_obj->ID, 'team_short_name', true );
				}
				else {
					$home_name = "NTN";
				}
				
				$div_str = "<div class='sbt-team sbt-home'> \n";
				if( get_post_meta( $game->ID, 'game_is_final', true ) ) {
					
					$opp_score = get_post_meta( $game->ID, 'game_opp_score', true );
					if ( $home_score > $opp_score ) {
						$div_str = "<div class='sbt-team sbt-home sbt-winner'> \n";
					}
				}
				
				$ret .= $div_str;
				
				$ret .= "<p class=sbt-team-name>" . $home_name . "</p> \n";
				if ( isset( $home_score ) && ( !empty( $home_score ) or $home_score == 0 ) ) {
					$ret .= "<p class=sbt-team-score>$home_score</p> \n";
				}
				$ret .= "</div> <!-- .sbt-team.sbt-home --> \n";	
			}
			else {
				$ret = "No sched object";
			}	
		}
		else {
			$ret = "NO SCHED SLUG";
		}
		
		return $ret;
	} //End: mstw_sbt_home_line()
}	
?>