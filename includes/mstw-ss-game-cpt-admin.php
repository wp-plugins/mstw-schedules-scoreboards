<?php
/*----------------------------------------------------------------------------
 * mstw-ss-game-cpt-admin.php
 *	This portion of the MSTW Schedules & Scoreboards Plugin admin handles the
 *		mstw_ss_game custom post type.
 *	It is loaded conditioned on is_admin() in mstw-ss-admin.php 
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
// Add the meta box for the mstw_ss_game custom post type
//
add_action( 'add_meta_boxes_mstw_ss_game', 'mstw_ss_game_metaboxes' );

function mstw_ss_game_metaboxes( ) {
		
	add_meta_box('mstw-ss-game-meta', __( 'Game Data', 'mstw-schedules-scoreboards' ), 'mstw_ss_create_games_ui', 
					'mstw_ss_game', 'normal', 'high', null );
					
} //End: mstw_ss_game_metaboxes( )

//-----------------------------------------------------------------
// Build the meta box (controls) for the Games custom post type
//
function mstw_ss_create_games_ui( $post ) {
	
	wp_nonce_field( plugins_url(__FILE__), 'mstw_ss_game_nonce' );
	
	// Months array for <select>/<option> statement in UI
	$mstw_ss_months = array ( 	'01', '02', '03', '04',
								'05', '06', '07', '08',
								'09', '10', '11', '12',
							);
							
	// Days array for <select>/<option> statement in UI
	$game_time_days_arr = array ( '01', '02', '03', '04', '05', '06', '07', '08',
							'09', '10', '11', '12', '13', '14', '15', '16',
							'17', '18', '19', '20', '21', '22', '23', '24',
							'25', '26', '27', '28', '29', '30', '31'
							);
						
	$game_time_mins_arr = array( '00' => '00', '05' => '05',
							 '10' => '10', '15' => '15',
							 '20' => '20', '25' => '25',
							 '30' => '30', '35' => '35',
							 '40' => '40', '45' => '45',
							 '50' => '50', '55' => '55',
							);
	$game_time_hrs_arr = array(  '00' => '00', '01' => '01', 
							 '02' => '02', '03' => '03',
							 '04' => '04', '05' => '05',
							 '06' => '06', '07' => '07', 
							 '08' => '08', '09' => '09',
							 '10' => '10', '11' => '11', 
							 '12' => '12', '13' => '13', 
							 '14' => '14', '15' => '15',
							 '16' => '16', '17' => '17',
							 '18' => '18', '19' => '19',
							 '20' => '20', '21' => '21',
							 '22' => '22', '23' => '23',
							);
	$game_time_tba_arr = array( '---' => '',
							__('TBA', 'mstw-schedules-scoreboards') => 'TBA',
							__('T.B.A.', 'mstw-schedules-scoreboards') => 'T.B.A.',
							__('TBD', 'mstw-schedules-scoreboards') => 'TBD',
							__('T.B.D.', 'mstw-schedules-scoreboards') => 'T.B.D.',
							);
	
	$options = wp_parse_args( get_option( 'mstw_ss_options' ), 
										  mstw_ss_get_defaults( ) );
										  
	// Retrieve the metadata values if they exist
	$game_sched_id = get_post_meta( $post->ID, 'game_sched_id', true );
	
	$game_time_tba = get_post_meta( $post->ID, 'game_time_tba', true );  // game time is TBA
	
	// UNIX timestamp date & time. Used to generate year, month, and day
	$game_unix_dtg = get_post_meta( $post->ID, 'game_unix_dtg', true );
	$game_unix_dtg = ( $game_unix_dtg != '' ) ? $game_unix_dtg : current_time( 'timestamp' );
	
	// This is the text opponent entry
	$game_opponent = get_post_meta( $post->ID, 'game_opponent', true );
	
	$game_opponent_link = get_post_meta( $post->ID, 'game_opponent_link', true );
	
	// This is opponent selected from the Teams DB
	$game_opponent_team = get_post_meta( $post->ID, 'game_opponent_team', true );
	
	// This is location selected from the Game Locations DB
	$game_gl_location = get_post_meta( $post->ID, 'game_gl_location', true );
	
	// This is the text location entry
	$game_location = get_post_meta( $post->ID, 'game_location', true );
	
	$game_location_link = get_post_meta( $post->ID, 'game_location_link', true );
	
	$game_is_home_game = get_post_meta( $post->ID, 'game_is_home_game', true );
	
	$game_our_score = get_post_meta( $post->ID, 'game_our_score', true );
	$game_opp_score = get_post_meta( $post->ID, 'game_opp_score', true );
	$game_is_final = get_post_meta( $post->ID, 'game_is_final', true );
	$game_curr_time = get_post_meta( $post->ID, 'game_curr_time', true );
	$game_curr_period = get_post_meta( $post->ID, 'game_curr_period', true );
	$game_result = get_post_meta( $post->ID, 'game_result', true );
	 
	$game_media_label_1  = get_post_meta( $post->ID, 'game_media_label_1', true );
	$game_media_label_2  = get_post_meta( $post->ID, 'game_media_label_2', true );
	$game_media_label_3  = get_post_meta( $post->ID, 'game_media_label_3', true );
	
	$game_media_url_1  = get_post_meta($post->ID, 'game_media_url_1', true );
	$game_media_url_2  = get_post_meta($post->ID, 'game_media_url_2', true );
	$game_media_url_3  = get_post_meta($post->ID, 'game_media_url_3', true );
	
	$std_length = 128;
	$std_size = 30;
	?>
	
   <table class="form-table">
   
   <?php
   
   // set up the schedules pulldown input
   if ( $sched_options = mstw_ss_build_schedules_list( ) ) {
		$sched_type = 'select-option';
		$sched_curr_value = $game_sched_id;
	}
	else {
		// no schedules exist
		$sched_type = 'label';
		$sched_curr_value = __( 'No schedules found. You must create a schedule before creating a game.', 'mstw-schedules-scoreboards' );
		$sched_options = array();
	}
   
   $admin_fields = array( 	'game_sched_id' => array (
								'type' => $sched_type,
								'curr_value' => $sched_curr_value,
								'label' => __( 'Select Schedule:', 'mstw-schedules-scoreboards' ),
								'options' => $sched_options,
								),
							'game_date' => array (
								'type' => 'text',
								'curr_value' => date( 'Y-m-d', $game_unix_dtg ),
								'label' => $options['date_label'] . ':',
								'maxlength' => $std_length,
								'size' => $std_size,
								'notes' => '',
							),
						);
	

	mstw_build_admin_edit_screen( $admin_fields );
	?>
	
	<!-- Game Time -->
	<?php
	$curr_hrs = date( 'H', (int)esc_attr( $game_unix_dtg ) );
	$curr_mins = date( 'i', (int)esc_attr( $game_unix_dtg ) );
	$curr_tba = $game_time_tba;
	if ( $curr_tba == '' ) {
		$curr_tba = '---';
	}
	?>
	
	<tr valign="top">
		<th scope="row"><label for="game_time_hrs" ><?php echo __( 'Game Time', 'mstw-schedules-scoreboards' ) . ' [hh:mm]:';?></label></th>
		<td>
			<select id='game_time_hrs' name='game_time_hrs'>
				<?php 
				foreach( $game_time_hrs_arr as $key=>$value ) {
					$selected = ( $curr_hrs == trim( $value ) ) ? 'selected="selected"' : '';
					echo "<option value='$value' $selected>$key</option>";
				}
				?>
			</select>
			:
			<select id='game_time_mins' name='game_time_mins'>
				<?php 
				foreach( $game_time_mins_arr as $key=>$value ) {
					$selected = ( $curr_mins == $value ) ? 'selected="selected"' : '';
					echo "<option value='$value' $selected>$key</option>";
				}
				?>
			</select>
			&nbsp;<?php echo __( 'or', 'mstw-schedules-scoreboards' ) ?>&nbsp;
			<select id='game_time_tba' name='game_time_tba'>
				<?php 
				foreach( $game_time_tba_arr as $key=>$value ) {
					$selected = ( $curr_tba == $value ) ? 'selected="selected"' : '';
					echo "<option value='$value' $selected>$key</option>";
				}
				?>
			</select>
		<br/><span class='description'><?php _e( 'If TBA is anything other than "---", then it is used for the game time whether or not a time is entered.', 'mstw-schedules-scoreboards' ) ?></span></td>
		<!--<td><?php //echo '$curr_hrs:$curr_mins: ' . $curr_hrs .':' . $curr_mins; ?> </td>-->
	</tr>
	

	<?php 	
	// This is the new stuff for the MSTW teams CPT
	if ( $opp_options = mstw_ss_build_teams_list() ) {
		$opp_type = 'select-option';
		$opp_curr_value = $game_opponent_team;
		$opp_desc = __( 'This is the preferred way to assign teams to games. It will override Opponent and Opponent Link fields below. It is the only way to add logos to the various displays.', 'mstw-schedules-scoreboards' );
	
	}
	else {
		$opp_type = 'label';
		$opp_options = array();
		$opp_curr_value = __( 'You must first create some teams before assigning them to games.', 'mstw-schedules-scoreboards' );
		$opp_desc = '';
	}
	
	// This is the new stuff for the MSTW Venues CPT (in place of the Game Locations Plugin)
	$location_label = ( $options['location_label'] == '' ? __( 'Location', 'mstw-schedules-scoreboards' ) : $options['location_label'] );
	
	/* = __( 'Game', 'mstw-schedules-scoreboards' ) . ' ' . $location_label ;*/
	$game_gl_location_label = sprintf( __( 'Game %s', 'mstw-schedules-scoreboards' ), $location_label );
	
	/*
	if ( $venue_options = mstw_ss_build_venues_list( ) ) {
		$venue_type = 'select-option';
		$venue
	}
	else { 
		//Need to enter a game message
	}
	*/
	
	$admin_fields = array( 	
					'game_opponent_team' => array (
						'type' => $opp_type,
						'curr_value' => $opp_curr_value,
						'label' => $options['opponent_label']  . ":",
						'options' => $opp_options,
						'desc' => $opp_desc,
						),
					'game_is_home_game' => array (
						'type' => 'checkbox',
						'curr_value' => $game_is_home_game,
						//'checked' => 1,
						'label' =>  __( 'Home Game:', 'mstw-schedules-scoreboards' ),
						'desc' => __( 'Check for home games. Note: this normally determines the game venue.', 'mstw-schedules-scoreboards' ),
						),
					'game_gl_location' => array( 
						'type' => 'select-option',
						'options' => mstw_ss_build_venues_list(),
						'curr_value' => $game_gl_location,
						//'checked' => 1,
						'label' => $game_gl_location_label,
						'desc' => __( 'ONLY NECESSARY FOR NEUTRAL SITE GAMES. Normally, the home team or opponent team\'s venue data will be pulled from the Teams DB.', 'mstw-schedules-scoreboards' ),					
						),
					'game-divider-1' => array( 
							'type' => 'divider',
							'curr_value' => __('Legacy fields supporting admins who have been using the Game Schedules plugin since "back in the day". These fields are DEPRECATED and may be eliminated in a future release.', 'mstw-schedules-scoreboards' ),
							),
							'game_opponent' => array (
								'type' => 'text',
								'curr_value' => $game_opponent,
								'label' => $options['opponent_label']  . ":",
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'Name of opponent (your choice of format).', 'mstw-schedules-scoreboards' ),
								),
							'game_opponent_link' => array (
								'type' => 'text',
								'curr_value' => $game_opponent_link,
								'label' =>  $options['opponent_label'] . ' ' . __( 'Link:', 'mstw-schedules-scoreboards' ),
								'maxlength' => 256,
								'size' => $std_size,
								'desc' => __( 'Link to a website for the opponent (your choice, maybe the team website or school website.', 'mstw-schedules-scoreboards' ),
								),
							'game_location' => array (
								'type' => 'text',
								'curr_value' => $game_location,
								'label' => $location_label  . ":",
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'This setting WILL OVERRIDE any selection from the Game Venues dropdown. It should NOT be used if venue is selected from Game Venues dropdown.', 'mstw-schedules-scoreboards' ),
								),
							'game_location_link' => array (
								'type' => 'text',
								'curr_value' => $game_location_link,
								'label' =>  $location_label . ' ' . __( 'Link:', 'mstw-schedules-scoreboards' ),
								'maxlength' => 256,
								'size' => $std_size,
								'desc' => __( 'This could be a link to a map or to a venue website. It will override the map link in the Game Venues DB. ', 'mstw-schedules-scoreboards' ),
								),
								
							'game-divider-2' => array( 
								'type' => 'divider',
								'curr_value' => __('Game result data fields, primarily for scoreboards.', 'mstw-schedules-scoreboards' ),
								),
							'game_our_score' => array (
								'type' => 'text',
								'curr_value' => $game_our_score,
								'label' =>  __( 'Home Score:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'Score for team assigned to schedule (not necessarily the "home" team). Note: the "Home Score" label can be changed in the Display Settings.', 'mstw-schedules-scoreboards' ),
								),
							'game_opp_score' => array (
								'type' => 'text',
								'curr_value' => $game_opp_score,
								'label' =>  __( 'Opponent Score:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => 'Opponent (the team selected for the game) score.',
								),
							'game_is_final' => array (
								'type' => 'checkbox',
								'curr_value' => $game_is_final,
								'label' =>  __( 'Final Score:', 'mstw-schedules-scoreboards' ),
								'checked' => 1,
								'desc' => __( 'Check the box when game score is final.', 'mstw-schedules-scoreboards' ),
								),
							'game_curr_period' => array (
								'type' => 'text',
								'curr_value' => $game_curr_period,
								'label' =>  __( 'Current Period:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'For games in progress. Current period of game (if not final).', 'mstw-schedules-scoreboards' ),
								),
							'game_curr_time' => array (
								'type' => 'text',
								'curr_value' => $game_curr_time,
								'label' =>  __( 'Time Remaining:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'For games in progress. Current time remaining in game (if not final).', 'mstw-schedules-scoreboards' ),
								),
							'game_result' => array (
								'type' => 'text',
								'curr_value' => $game_result,
								'label' =>  __( 'Game Result:', 'mstw-schedules-scoreboards' ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => __( 'If a result is entered here, it will replace the game time in all front end displays.', 'mstw-schedules-scoreboards' ),
								),
								
							'game-divider-3' => array( 
								'type' => 'divider',
								'curr_value' => __('Links to game information. Originally for "media" links, but can now be repurposed and used as used to suit your site\'s needs.', 'mstw-schedules-scoreboards' ),
								),
							'game_media_label_1' => array (
								'type' => 'text',
								'curr_value' => $game_media_label_1,
								'label' =>  sprintf( __( '%s Label 1:', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => sprintf( __( 'This text will be displayed for %s link 1. If it is blank, NO LINKS WILL BE DISPLAYED.', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								),
							'game_media_url_1' => array (
								'type' => 'text',
								'curr_value' => $game_media_url_1,
								'label' =>  sprintf( __( '%s URL 1:', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								'maxlength' => 256,
								'size' => $std_size,
								'desc' => sprintf( __( 'URL for %s link 1.', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								),
							'game_media_label_2' => array (
								'type' => 'text',
								'curr_value' => $game_media_label_2,
								'label' =>  sprintf( __( '%s Label 2:', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => sprintf( __( 'This text will be displayed for %s link 2. If it is blank, #3 below will be ignored.', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								),
							'game_media_url_2' => array (
								'type' => 'text',
								'curr_value' => $game_media_url_2,
								'label' =>  sprintf( __( '%s URL 2:', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								'maxlength' => 256,
								'size' => $std_size,
								'desc' => sprintf( __( 'URL for %s link 2.', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								),
							'game_media_label_3' => array (
								'type' => 'text',
								'curr_value' => $game_media_label_3,
								'label' =>  sprintf( __( '%s Label 3:', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								'maxlength' => $std_length,
								'size' => $std_size,
								'desc' => sprintf( __( 'This text will be displayed for %s link 3.', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								),
							'game_media_url_3' => array (
								'type' => 'text',
								'curr_value' => $game_media_url_3,
								'label' =>  sprintf( __( '%s URL 3:', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								'maxlength' => 256,
								'size' => $std_size,
								'desc' => sprintf( __( 'URL for %s link 3.', 'mstw-schedules-scoreboards' ), $options['media_label'] ),
								),
						);
	

	mstw_build_admin_edit_screen( $admin_fields );
	?>
	
	</table>
	
<?php        	
}

//-----------------------------------------------------------
//	Build (echoes to output) the select schedule drop-down
//		Called from mstw_ss_create_games_ui()
//
function mstw_ss_build_schedule_input( $current_sched ) {
	$retval = 1; //no problems
	
	
	$scheds = get_posts( array ( 'numberposts' => -1,
								 'post_type' => 'mstw_ss_schedule',
								 'orderby' => 'title',
								 'order' => 'ASC' 
								)
						);
						
	//mstw_log_msg( 'In mstw_ss_build_schedule_input - schedules:' );
	//mstw_log_msg( $scheds );
	
	?>
	<tr valign="top">
		<th><?php _e( 'Select Schedule:', 'mstw-schedules-scoreboards' ) ?></th>
		<td>
		<?php
		if( $scheds ) {
			echo "<select id='game_sched_id' name='game_sched_id'>";
			
			echo "<option value='-1'> ---- </option>";
			foreach( $scheds as $sched ) {
				//$post_data = get_post($post->ID, ARRAY_A);
				$slug = $sched->post_name;
				$selected = ( $current_sched == $slug ) ? 'selected="selected"' : '';
				echo "<option value='" . $slug . "'" . $selected . ">" .  get_the_title( $sched->ID ) . "</option>";
			}
			?>
			</select><br/>
			<span class='description'><?php _e( 'You must create a schedule before entering games.', 'mstw-schedules-scoreboards' ) ?></span>
			<?php
		}
		else {
			?>
			<label><?php _e( 'No schedules found. Create a schedule through the Schedules menu before assigning a game to it.', 'mstw-schedules-scoreboards' ) ?> </label>";
			<?php
			$retval = 0; //No schedules. Tell caller not to build the rest of the page.
		}
		?>
		</td>
	</tr>
	<?php
	return( $retval );
	
} //End: function mstw_ss_build_schedule_input 

//-----------------------------------------------------------
//	Build (echoes to output) the select team drop-down
//		Called from mstw_ss_create_games_ui()
//
function mstw_ss_build_teams_input( $current_team ) {
		
	$teams = get_posts(array( 'numberposts' => -1,
					  'post_type' => 'mstw_ss_team',
					  'orderby' => 'title',
					  'order' => 'ASC' 
					));						

	if( $teams ) {
		?>
		<tr valign="top">
			<th><?php _e( 'Select Opponent:', 'mstw-schedules-scoreboards' ) ?></th>
			<td>
				<select id='game_opponent_team' name='game_opponent_team'>
					<option value='-1'> ---- </option>
					<?php
					foreach( $teams as $team ) {
						$selected = ( $current_team == $team->post_name ) ? 'selected="selected"' : '';
						echo "<option value='" . $team->post_name . "'" . $selected . ">" . get_the_title( $team->ID ) . "</option>";
					}
					?>
				</select>
				<br/><span class='description'><?php _e( 'If set, this setting will override Opponent and Opponent Link. It is also the only way to add logos to the various displays.', 'mstw-schedules-scoreboards' ) ?></span>
			</td>
		</tr>
	<?php	
	} //End: if( $teams )
	
} //End: mstw_ss_build_teams_input( )

function mstw_ss_remove_updated_message( $messages ) {
	//mstw_log_msg( 'in mstw_ss_remove_updated_message' );
    return array();
}

//-----------------------------------------------------------------
// SAVE THE MSTW_SS_GAME CPT META DATA
//
add_action( 'save_post_mstw_ss_game', 'mstw_ss_save_game_meta', 20, 2 );

function mstw_ss_save_game_meta( $post_id, $post ) {

	// check if this is an auto save routine. 
	// If it is our form has not been submitted, so don't do anything
	if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' || $post->post_status == 'trash' ) {
		mstw_log_msg( 'in mstw_ss_save_game_meta ... doing autosave ... nevermind!' );
		return; //$post_id;
	}
	
	//check that we are in the right context ... saving from edit page
	if( isset($_POST['mstw_ss_game_nonce'] ) && 
		check_admin_referer( plugins_url(__FILE__), 'mstw_ss_game_nonce' ) ) {
		
		//
		// PROCESS THE NEW STUFF ... this is how data should be entered
		//
		// gotta have a schedule ID in all cases
		if ( isset( $_POST['game_sched_id'] ) and 
					 !empty( $_POST['game_sched_id'] ) and 
					( $_POST['game_sched_id'] != -1 ) ) {	
			$game_sched_id = sanitize_title( $_POST['game_sched_id'] );
			update_post_meta( $post_id, 'game_sched_id', $game_sched_id );			
		}
		else {  //Display an admin error and exit (don't update any data)			
			mstw_ss_add_admin_notice( 'error', __( 'A schedule MUST be selected before saving the game.', 'mstw-schedules-scoreboards') );	
			return;			
		}
		
		// build the game time; handle TBA
		$game_time_tba = strip_tags( trim( mstw_safe_ref( $_POST, 'game_time_tba' ) ) );
		update_post_meta( $post_id, 'game_time_tba', $game_time_tba );
		
		if ( $game_time_tba != '' ) {
			$game_time_hrs = $game_time_mins = '00';
		}
		else {
			$game_time_hrs = strip_tags( trim( mstw_safe_ref( $_POST, 'game_time_hrs' ) ) );
			$game_time_mins = strip_tags( trim( mstw_safe_ref( $_POST, 'game_time_mins' ) ) );
		}
		
		// Build the DTG string, then store the unix timestamp
		$date_str = strip_tags( trim( $_POST[ 'game_date' ] ) );
		$time_str = $game_time_hrs . ':' . $game_time_mins;
		$dtg_str = $date_str . ' ' . $time_str;
	
		update_post_meta( $post_id, 'game_unix_dtg', strtotime( $dtg_str ) );
	
		// opponent selected from the Teams DB
		update_post_meta( $post_id, 'game_opponent_team', sanitize_text_field( $_POST['game_opponent_team'] ) );
		
		// home game ... checkbox needs special processing
		$game_is_home_game = mstw_safe_ref( $_POST, 'game_is_home_game' );
		$game_is_home_game = ( $game_is_home_game == 1 ) ? 1 : 0;
		update_post_meta( $post_id, 'game_is_home_game', $game_is_home_game );

		// This is location selected from the Game Locations DB
		update_post_meta( $post_id, 'game_gl_location', sanitize_text_field( $_POST['game_gl_location'] ) );
	
		//
		// PROCESS THE LEGACY STUFF ... DEPRECATED
		//
		// text opponent name
		update_post_meta( $post_id, 'game_opponent', sanitize_text_field( $_POST['game_opponent'] ) );
		
		// text opponent link/URL
		mstw_validate_url( $_POST, 'game_opponent_link', $post_id, 'error', 
							  __( 'Invalid opponent link.', 'mstw-schedules-scoreboards' ) );
		
		// text location entry
		update_post_meta( $post_id, 'game_location', sanitize_text_field( $_POST['game_location'] ) );
		
		// text location link/URL
		mstw_validate_url( $_POST, 'game_location_link', $post_id, 'error', 
							  __( 'Invalid game location link.', 'mstw-schedules-scoreboards' ) );
		
		//
		// PROCESS THE GAME RESULTS & SCOREBOARD STUFF
		//
		// home team score		
		update_post_meta( $post_id, 'game_our_score', sanitize_text_field( $_POST['game_our_score'] ) );
		
		// opponent score
		update_post_meta( $post_id, 'game_opp_score', sanitize_text_field( $_POST['game_opp_score'] ) );
		
		// score is final ... checkbox needs special processing
		$game_is_final = mstw_safe_ref( $_POST, 'game_is_final' );
		$game_is_final = ( $game_is_final == 1 ) ? 1 : 0;
		update_post_meta( $post_id, 'game_is_final', $game_is_final );
		
		// current period
		update_post_meta( $post_id, 'game_curr_period', sanitize_text_field( $_POST['game_curr_period'] ) );
		
		// time remaining
		update_post_meta( $post_id, 'game_curr_time', sanitize_text_field( $_POST['game_curr_time'] ) );
		
		// game result in 'old school' format
		update_post_meta( $post_id, 'game_result', sanitize_text_field( $_POST['game_result'] ) );

		//
		// PROCESS MEDIA LABELS AND LINKS
		//
		update_post_meta( $post_id, 'game_media_label_1', sanitize_text_field( $_POST['game_media_label_1'] ) );
		
		update_post_meta( $post_id, 'game_media_label_2', sanitize_text_field( $_POST['game_media_label_2'] ) );
		
		update_post_meta( $post_id, 'game_media_label_3', sanitize_text_field( $_POST['game_media_label_3'] ) );
	
		//function mstw_validate_url( $data_array, $key, $post_id, $notice_type = 'error', $notice = 'Invalid URL:' ) {
		mstw_validate_url( $_POST, 'game_media_url_1', $post_id, 'warning', 
							  __( 'Invalid URL. Media URL 1 not saved.', 'mstw-schedules-scoreboards' ) );
							  
		mstw_validate_url( $_POST, 'game_media_url_2', $post_id, 'warning', 
							  __( 'Invalid URL. Media URL 2 not saved.', 'mstw-schedules-scoreboards' ) );
							  
		mstw_validate_url( $_POST, 'game_media_url_3', $post_id, 'warning', 
							  __( 'Invalid URL. Media URL 3 not saved.', 'mstw-schedules-scoreboards' ) );
							  
		//mstw_ss_add_admin_notice( 'updated', __( 'Game saved.', 'mstw-schedules-scoreboards') );
	}
	else {	
		if ( strpos( wp_get_referer( ), 'trash' ) === FALSE ) {
			mstw_log_msg( 'Oops! In mstw_ss_save_game_meta() game nonce not valid' );
			mstw_ss_add_admin_notice( 'error', __( 'Invalid referrer. Contact system admin.', 'mstw-schedules-scoreboards') );
		}
	}
} //End: mstw_ss_save_game_meta

// ----------------------------------------------------------------
// Set up the View All Games table
//
add_filter( 'manage_edit-mstw_ss_game_columns', 
			'mstw_ss_edit_game_columns' ) ;

function mstw_ss_edit_game_columns( $columns ) {	

	$options = wp_parse_args( (array)get_option( 'mstw_ss_options' ), mstw_ss_get_defaults( ) );
		
	//$new_options = wp_parse_args( (array)$options, mstw_ss_get_defaults( ) );

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Game', 'mstw-schedules-scoreboards' ),
		'game_date' 	=> $options['date_label'],
		'game_time' 	=> __( 'Time', 'mstw-schedules-scoreboards' ),
		'game_opponent' 	=> $options['opponent_label'],
		'game_sched_id' => __( 'Schedule', 'mstw-schedules-scoreboards' ),
		'game_scoreboards' => __( 'Scoreboards', 'mstw-schedules-scoreboards' ),
		//'team_short_mascot' => __( 'Mascot Short Name', 'mstw-schedules-scoreboards' ),
		//'team_link' 		=> __( 'Team Link', 'mstw-schedules-scoreboards' ),
		//'team_logo' 		=> __( 'Table Logo', 'mstw-schedules-scoreboards' ),
		//'team_alt_logo' 	=> __( 'Slider Logo', 'mstw-schedules-scoreboards' ),
		);

	return $columns;
} //End: mstw_ss_edit_game_columns( )

//-----------------------------------------------------------------
// Display the View All Teams table columns
// 
add_action( 'manage_mstw_ss_game_posts_custom_column',
			'mstw_ss_manage_game_columns', 10, 2 );

function mstw_ss_manage_game_columns( $column, $post_id ) {
	//global $post; ??
	//Need the admin time and date formats
	$options = wp_parse_args( get_option( 'mstw_ss_dtg_options' ), mstw_ss_get_dtg_defaults( ) );
	
	$game_timestamp = get_post_meta( $post_id, 'game_unix_dtg', true );
	
	switch( $column ) {	
		case 'game_sched_id':
			$sched_slug = get_post_meta( $post_id, 'game_sched_id', true );

			if ( empty( $sched_slug ) )
				_e( 'No Schedule Defined. (This is bad.)', 'mstw-schedules-scoreboards' );
			else {
				$sched_obj = get_page_by_path( $sched_slug, OBJECT, 'mstw_ss_schedule' );
				if ( $sched_obj === null ) {
					//this is bad
					mstw_log_msg( 'in mstw_ss_manage_game_columns() no object for slug: ' . $sched_slug );
					printf( 'no schedule with slug: %s', $sched_slug );
				}
				else {
					$sched_name = get_the_title( $sched_obj->ID );
					$edit_link = site_url( '/wp-admin/', null ) . 'post.php?post=' . $sched_obj->ID . '&action=edit';
					echo '<a href="' . $edit_link . '">' . $sched_name . '</a>';
					//echo '<a href="' . $edit_link . '">' . $sched_slug . '</a>';
					//printf( '%s', $sched_slug );
				}
			}

			break;

		case 'game_date' :
			// Build from unix timestamp
			if ( empty( $game_timestamp ) ) {
				_e( 'No Game Date', 'mstw-schedules-scoreboards' );
			}
			else {
				$date_format = ( $options['custom_admin_date_format'] != '' ) ?
								 $options['custom_admin_date_format'] : 
								 $options['admin_date_format'];
				echo( date( $date_format, intval( $game_timestamp ) ) );
			}
			break;
		
		case 'game_time' :
			// First, check for TBA, which overrides all
			$game_time_tba = get_post_meta( $post_id, 'game_time_tba', true );

			if ( $game_time_tba != '' ) {
				printf( '%s', $game_time_tba );
			}
			else { // Look for a custom format, if none, use the regular format
				$time_format = ( $options['custom_admin_time_format'] != '' ) ?
								 $options['custom_admin_time_format'] : 
								 $options['admin_time_format'];
				echo( date( $time_format, intval( $game_timestamp ) ) );
			}
			break;

		case 'game_opponent':
			// Get the post meta
			$opponent = get_post_meta( $post_id, 'game_opponent', true );
			$opponent_team = get_post_meta( $post_id, 'game_opponent_team', true );
			
			// if there's a team DB entry, use it
			// else if there's an opponent entry, use it
			if ( !empty( $opponent_team ) and ( $opponent_team != -1 ) ) {
				$team = get_page_by_path( $opponent_team, OBJECT, 'mstw_ss_team' );
				printf( '%s', get_the_title( $team->ID ) );
			}
			else if ( !empty( $opponent ) )
				printf( '%s', $opponent );
			else
				_e( 'No Opponent', 'mstw-schedules-scoreboards' );
			break;
			
		case 'game_scoreboards':
			$sbs = get_the_terms( $post_id, 'mstw_ss_scoreboard' );
			
			$edit_link = site_url( '/wp-admin/', null ) . 'edit-tags.php?taxonomy=mstw_ss_scoreboard&post_type=mstw_ss_game';
			
			if ( is_array( $sbs ) && !is_wp_error( $sbs ) ) {
				$scoreboards = array( );
				foreach( $sbs as $key => $sb ) {
					
					$sbs[$key] = '<a href="' . $edit_link . '">' . $sb->name . '</a>';
				}
				echo implode( ', ', $sbs );
			}
			else {
				echo '<a href="' . $edit_link . '">' . __( 'None', 'mstw-schedules-scoreboards' ) . '</a>';
			}
			break;
	
		default :
			/* Just break out of the switch statement for everything else. */
			break;
	}
} //End: mstw_ss_manage_game_columns( ) 

// ----------------------------------------------------------------
// Add a filter to sort all games table on the schedule id & game date columns
//
add_filter("manage_edit-mstw_ss_game_sortable_columns", 'mstw_ss_games_columns_sort');

function mstw_ss_games_columns_sort( $columns ) {
	$custom = array(
		'game_sched_id' => 'game_sched_id',
		'game_date' 	=> 'game_date'
	);
	return wp_parse_args( $custom, $columns );
}

//-----------------------------------------------------------------
// Sort show all games by schedule by columns. See:
// http://scribu.net/wordpress/custom-sortable-columns.html#comment-4732
//
add_filter( 'request', 'mstw_ss_games_column_order' );

function mstw_ss_games_column_order( $vars ) {
	if ( isset( $vars['orderby'] ) && 'game_sched_id' == $vars['orderby'] ) {
		$custom = array( 'meta_key' => 'game_sched_id',
							 //'orderby' => 'meta_value_num', // does not work
							 'orderby' => 'meta_value'
							 //'order' => 'asc' // don't use this; blocks toggle UI
							);
		$vars = array_merge( $vars, $custom );
	}
	else if ( isset( $vars['orderby'] ) && 'game_date' == $vars['orderby'] ) {
		$custom = array( 'meta_key' => 'game_unix_dtg',
							 //'orderby' => 'meta_value_num', // does not work
							 'orderby' => 'meta_value'
							 //'order' => 'asc' // don't use this; blocks toggle UI
							);
		$vars = array_merge( $vars, $custom );
	}
	
	return $vars;
	
} //End mstw_ss_games_column_order( )

// ----------------------------------------------------------------
// Add a filter to the all games screen based on the Schedule ID
//
add_action( 'restrict_manage_posts','mstw_ss_restrict_games_by_schedID' );

function mstw_ss_restrict_games_by_schedID( ) {
	global $wpdb;
	global $typenow;
	
	if( isset( $typenow ) && $typenow != "" && $typenow == "mstw_ss_game" ) {
		$meta_values = $wpdb->get_col("
			SELECT DISTINCT meta_value
			FROM ". $wpdb->postmeta ."
			WHERE meta_key = 'game_sched_id'
			ORDER BY meta_value
		");

		?>
		<select name="game_sched_id" id="game_sched_id">
			<option value=""><?php _e( 'Show All Schedules', 'mstw-schedules-scoreboards' ) ?></option>
			
			<?php 
			foreach ( $meta_values as $meta_value ) { 
				if ( $meta_value != '' ) {
				?>
					<option value="<?php echo esc_attr( $meta_value ) ?>" 
					<?php 
					if( isset( $_GET['game_sched_id'] ) && !empty( $_GET['game_sched_id'] ) ) 
						selected($_GET['game_sched_id'], $meta_value ); 
					?>
					>
						<?php echo $meta_value ?>
					</option>
			<?php 
				} //End: if ( $meta_value != '' ) 
			} //End: foreach ( $meta_values as $meta_value )
			?>
		</select>
	<?php
	}
}  //End of mstw_ss_restrict_games_by_schedID( )

// ----------------------------------------------------------------
// Add a filter to the where clause in mstw_ss_restrict_games_by_schedID()
//
//add_filter( 'posts_where' , 'mstw_ss_games_where_metavalue' );

function mstw_ss_games_where_metavalue( $where ) {
	if( is_admin( ) ) {
		global $wpdb;       
		if ( isset( $_GET['game_sched_id'] ) && !empty( $_GET['game_sched_id'] ) ) {
			$meta_number = $_GET['game_sched_id'];
			$where .= " AND ID IN (SELECT post_id FROM " . $wpdb->postmeta . " WHERE meta_key='game_sched_id' AND meta_value='$meta_number' )";
		}
	}
	
	return $where;
	
} //End: mstw_ss_games_where_metavalue( )

// ----------------------------------------------------------------
// Add a filter the All Games screen based on the Scoreboard Taxonomy
add_action('restrict_manage_posts','mstw_ss_restrict_games_by_scoreboard');

function mstw_ss_restrict_games_by_scoreboard( ) {
	global $typenow;
	global $wp_query;
	
	if( $typenow == 'mstw_ss_game' ) {
		
		$taxonomy_slugs = array( 'mstw_ss_scoreboard',
								 //'nfc-west-scoreboard-week-1',
								 //'pac-12-scoreboard-week-1'
								);
		
		foreach ( $taxonomy_slugs as $tax_slug ) {
			//retrieve the taxonomy object for the tax_slug
			$tax_obj = get_taxonomy( $tax_slug );
			$tax_name = $tax_obj->labels->name;
			
			$terms = get_terms( $tax_slug );
				
			//output the html for the drop down menu
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
            echo "<option value=''>". __( 'Show All Scoreboards', 'mstw-schedules-scoreboards') . "</option>";
			
			//output each select option line
            foreach ($terms as $term) {
                //check against the last $_GET to show the current selection
				if ( array_key_exists( $tax_slug, $_GET ) ) {
					$selected = ( $_GET[$tax_slug] == $term->slug )? ' selected="selected"' : '';
				}
				else {
					$selected = '';
				}
                echo '<option value=' . $term->slug . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
            }
            echo "</select>"; 
		}	
	}
} //End: mstw_ss_restrict_games_by_scoreboard( )


add_filter('parse_query','mstw_ss_parse_query_for_schedID');

function mstw_ss_parse_query_for_schedID( $query ) {
    global $pagenow;
	
	if( is_admin( ) AND isset( $query->query['post_type'] ) AND $query->query['post_type'] == 'mstw_ss_game' ) {
		//grab a reference to the $wp_query's query_vars
		$qv = &$query->query_vars;

		if( isset( $_GET['game_sched_id'] ) AND !empty( $_GET['game_sched_id'] ) ) {
		  $qv['meta_query'][] = array(
			'field' => 'game_sched_id',
			'value' => $_GET['game_sched_id'],
			'compare' => '=',
			'type' => 'CHAR'
		  );
		}
	}
} //End: mstw_ss_parse_query_for_schedID( )
?>