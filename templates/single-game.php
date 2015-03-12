<?php
/**
 * MSTW Schedules & Scoreboards single game template.
 *
 * NOTE: Plugin users may have to modify this template to fit their 
 * individual themes. This template has been tested in the WordPress 
 * Underscores Theme. 
 *
 * @package Underscores
 * @subpackage MSTW_Schedules_Scoreboards
 * @since MSTW Schedules & Scoreboards 1.0
 */
 ?>

	<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
					<!-- Navigation back to previous page -->
					<!--
					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'mstw-schedules-scoreboards' ); ?></h3>
						<span class="nav-previous">
							<?php //$back =$_SERVER['HTTP_REFERER'];
							//if( isset( $back ) && $back != '' ) { 
								//echo '<a href="' . $back . '">';?>
								<span class="meta-nav">&larr;</span><?php //_e( 'Previous Page', 'mstw-schedules-scoreboards' ) ?></a>
							<?php
							//}?>
						</span> <!-- .nav-previous 	
					</nav><!-- #nav-single
					-->

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php
					//
					// PULL THE GAME DATA
					//
					// need the game schedule to find the home team
					$sched_slug = get_post_meta( $post->ID, 'game_sched_id', true );
					$sched_obj = get_page_by_path( $sched_slug, OBJECT, 'mstw_ss_schedule' );
					// find the home team
					$home_team_slug = get_post_meta( $sched_obj->ID, 'schedule_team', true );
					$home_team_obj = get_page_by_path( $home_team_slug, OBJECT, 'mstw_ss_team' );
					
					// this should be figured out from the display settings
					
					$full_name = get_post_meta( $home_team_obj->ID, 'team_full_name', true );
					//team_short_name
					$full_mascot = get_post_meta( $home_team_obj->ID, 'team_full_mascot', true );
					//team_short_mascot
					$home_team_name = "$full_name $full_mascot";
					
					$is_home_game = get_post_meta($post->ID, 'game_is_home_game', true );
					
					$home_css_tag = ( $is_home_game ) ? 'mstw-ss-home' : '';
					
					//$home_team_obj = get_page_from_slug( 'home-team-slug' );
					?>
					<div class="single-game single-game_<?php echo( $home_team_slug ) ?> <?php echo $home_css_tag ?>">
					<?php
					$opp_team_slug = get_post_meta( $post->ID, 'game_opponent_team', true );
					//mstw_log_msg( 'in single-game template ... $opp_team_slug= ' . $opp_team_slug );
					
					if ( $opp_team_slug == -1 ) {
						// not using the new stuff? sorry, no single game page
						?>
						<h2 class='single-game'>
						<?php _e( 'Opponent must be entered from the Teams DB in order to create a single game page.', 'mstw-schedules-scoreboards' ); ?>
						</h2>
					<?php
					}
					else {
						// Need to get teams' info ... logos, names, links, etc. etc.
						$opp_team_obj = get_page_by_path( $opp_team_slug, OBJECT, 'mstw_ss_team' );
						$opp_team_name = get_the_title( $opp_team_obj->ID );
						
						$base_options = get_option( 'mstw_ss_options' );
						$base_options = wp_parse_args( $base_options, mstw_ss_get_defaults( ) );
						$dtg_options = get_option( 'mstw_ss_dtg_options' );
						$dtg_options = wp_parse_args( $dtg_options, mstw_ss_get_dtg_defaults( ) );
						
						$options = array_merge( (array)$base_options, (array)$dtg_options );
						
						$opp_team_entry = mstw_ss_build_team_entry( $post, $options, 'slider', 'opp' );
						$home_team_entry = mstw_ss_build_team_entry( $post, $options, 'slider', 'home' );
						
						// Set date & time formats from display settings
						$game_time_tba = get_post_meta($post->ID, 'game_time_tba', true );
						$unix_timestamp = get_post_meta($post->ID, 'game_unix_dtg', true );
						//$dtg_format = ( $options['custom_cdt_dtg_format'] != '' ) ? $options['custom_cdt_dtg_format'] : $options['cdt_dtg_format'];
						
						//full date-time group format 
						$cdt_dtg_format = ( $options['cdt_dtg_format'] == 'custom' ? $options['custom_cdt_dtg_format'] : $options['cdt_dtg_format'] ); 
						//date only format
						$cdt_date_format = ( $options['cdt_date_format'] == 'custom' ? $options['custom_cdt_date_format'] : $options['cdt_date_format'] );
						
						$game_is_final = get_post_meta( $post->ID, 'game_is_final', true );
						
						if( $game_time_tba == '' ) {
							//use date & time 
							$date_time_entry = date( $cdt_dtg_format, $unix_timestamp );
						}
						else if ( !$game_is_final ) {
							//use date at TBA
							mstw_log_msg( 'single_game_template ... $game_is_final= ' . $game_is_final );
							$date_time_entry = date( $cdt_date_format, $unix_timestamp ) . ' ' . $game_time_tba;
						}
						else {
							$date_time_entry = date( $cdt_date_format, $unix_timestamp );
						}
						//$game_date = date( $dtg_format, $unix_timestamp );
						//$game_date = date( 'l, d F Y', $unix_timestamp );
						//$game_time = ( $game_time_tba == '' ) ? date( 'h:i A', $unix_timestamp ) : $game_time_tba;
						//$date_time_entry = $game_date . ' ' . $game_time;
						
						//location from Venues DB
						//$venue_slug = get_post_meta($post->ID, 'game_gl_location', true );
						
						$venue_obj = mstw_ss_find_game_venue( $post );
						
						if ( $venue_obj !== null ) {
							$options = get_option( 'mstw_ss_options' );
							$venue_name = mstw_ss_build_location_entry( $post, $options );
							//$venue_name = get_the_title( $venue_obj );
						}
						else {
							$venue_name = 'No venue found.';
						}
						
		
						// build venue string - either from $game_venue_slug or venue in Teams DB 
						// based on display settings?
						
						
						//
						// PROCESS GAME STATUS & RESULT & BUILD THE DATA BLOCK
						//
						// 'home' team score (really schedule team score)		
						$home_score = get_post_meta( $post->ID, 'game_our_score', true );
						$opp_score = get_post_meta( $post->ID, 'game_opp_score', true );
						//$game_is_final = get_post_meta( $post->ID, 'game_is_final', true );
						$curr_period = get_post_meta( $post->ID, 'game_curr_period', true );
						$curr_game_time = get_post_meta( $post->ID, 'game_curr_time', true );
						// game result in 'old school' format
						$result = get_post_meta( $post->ID, 'game_result', true );
						
						// check to see if there's data to build a mini scoreboard
						if ( $home_score != '' and $opp_score != '' ) {
							$score_entry = "$home_score - $opp_score";
							//have a score so now set the status
							if ( $game_is_final ) {
								$status_entry = __( 'FINAL', 'mstw-schedules-scoreboards' );
							}
							else {	//display the data
								$curr_period = mstw_ss_numeral_to_ordinal( $curr_period );
								$status_entry = "$curr_game_time $curr_period";
							}
						} 
						else {
							//got no scores, so we ASS-U-ME game has not started
							$score_entry = __( 'VS', 'mstw-schedules-scoreboards' );
							$status_entry = '';
							
							$status_entry = mstw_ss_build_game_countdown( $post, 'Countdown:' );
							
						}
						
						
						//
						// PROCESS MEDIA LABELS AND LINKS & BUILD THE MEDIA BLOCK
						//
						$media_label_1 = get_post_meta($post->ID, 'game_media_label_1', true );
						$media_url_1 = get_post_meta($post->ID, 'game_media_url_1', true );
						$media_label_2 = get_post_meta($post->ID, 'game_media_label_2', true );
						$media_url_2 = get_post_meta($post->ID, 'game_media_url_2', true );
						$media_label_3 = get_post_meta($post->ID, 'game_media_label_3', true );
						$media_url_3 = get_post_meta($post->ID, 'game_media_url_3', true );
						
						//check the first media URL. If it is not there, we're not going further
						if ( $media_url_1 != ''  and $media_label_1 != '' ) {
							$media_entry = "<a href='$media_url_1' target='_blank'>$media_label_1</a>";
							
							//check the second media URL. If it is not there, we're not going further
							if ( $media_url_2 != ''  and $media_label_2 != '' ) {
								$media_entry .= " | <a href='$media_url_2' target='_blank'>$media_label_2</a>";
								
								//check the third media URL. 
								if ( $media_url_3 != ''  and $media_label_3 != '' ) {
									$media_entry .= " | <a href='$media_url_3' target='_blank'>$media_label_3</a>";
								}	
							}
						}
						else {
							// no media links
							$media_entry = 'No media links available';
						}
						?>
											
						<div class=date-time-block><?php echo $date_time_entry ?></div>

						<div class=single-game-sb-block>
							<div class="sb-team-block sb-home"><?php echo $home_team_entry ?> </div>
							<div class=sb-data>
								<div class=sb-score><?php echo $score_entry ?></div>
								<div class=sb-status><?php echo $status_entry ?></div>
							</div> <!-- .sb-data -->
							<div class="sb-team-block sb-opp"><?php echo $opp_team_entry ?></div>
						</div> <!-- .single-game-sb-block -->
						
						<div class=single-game-venue><?php echo $venue_name ?></div>
						
						<div class=single-game-links><?php echo $media_entry ?></div>
					
					<?php } ?>	
					</div> <!-- .single-game -->
					</article> <!-- #post-<?php the_ID(); ?> -->

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

	<?php get_footer();?>