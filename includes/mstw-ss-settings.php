<?php
/*----------------------------------------------------------------------------
 * mstw-ss-settings.php
 *	All functions for the MSTW Schedules & Scoreboards Plugin settings.
 *		Loaded conditioned on is_admin() in mstw-ss-admin.php 
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-15 Mark O'Donnell (mark@shoalsummitsolutions.com)
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

//-------------------------------------------------------------------------------
// Render the display settings page (3 tabs & help)
//
function mstw_ss_settings_page( ) {
	global $pagenow;
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php echo __( 'Schedules & Scoreboards Plugin Settings', 'mstw-schedules-scoreboards') ?></h2>
		<?php //settings_errors(); ?> 
		
		<?php 
		//Get or set the current tab - default to first/main settings tab
		$current_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'files-columns-tab' );
		
		//Display the tabs, showing the current tab
		mstw_ss_admin_tabs( $current_tab );  
		?>
		
		<form action="options.php" method="post" id="target">
		
		<?php 
		//echo '<h2>pagenow = ' . $pagenow . ' page = ' . $_GET['page'] . '</h2>';
		//WHY DO WE NEED THIS CONDITIONAL, REALLY?
		if ( $pagenow == 'edit.php' && $_GET['page'] == 'mstw-ss-settings' ) {
			switch ( $current_tab ) {
				case 'files-columns-tab':
					settings_fields( 'mstw_ss_options' );
					do_settings_sections( 'mstw-ss-settings' );
					$options_name = 'mstw_ss_options[reset]';
					break;
				case 'date-time-tab';
					settings_fields( 'mstw_ss_dtg_options' );
					do_settings_sections( 'mstw-ss-dtg-settings' );
					$options_name = 'mstw_ss_dtg_options[reset]';
					break;
				case 'colors-tab':
					settings_fields( 'mstw_ss_color_options' );
					do_settings_sections( 'mstw-ss-colors' );
					$options_name = 'mstw_ss_color_options[reset]';
					break;
				case 'venues-tab':
					settings_fields( 'mstw_ss_venue_options' );
					do_settings_sections( 'mstw-ss-venue-settings' );
					$options_name = 'mstw_ss_venue_options[reset]';
					break;
				case 'scoreboards-tab':
					settings_fields( 'mstw_ss_scoreboard_options' );
					do_settings_sections( 'mstw-ss-scoreboard-settings' );
					$options_name = 'mstw_ss_scoreboard_options[reset]';
					break;
			}
			?>
			<table class="form-table">
			<!-- Add a spacer row -->
			<tr><td></td></tr>
			<tr>
				<td>
					<?php //submit_button( __( 'Save Changes', 'mstw-schedules-scoreboards' ), 'primary', 'Submit', false, null ) ?>
					<input name="Submit" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'mstw-schedules-scoreboards' ) ?>" />
				</td>
				<td>
					<?php //submit_button( __( 'Reset Defaults', 'mstw-schedules-scoreboards' ), 'secondary', $options_name, false, array( 'id' => 'reset_btn' ) ) ?>
					<input type="submit" class="button-secondary" id="reset_btn" name="<?php echo $options_name ?>" onclick="ss_confirm_reset_defaults()" value="<?php _e( 'Reset Defaults', 'mstw-schedules-scoreboards' ) ?>" />
				</td>
			</tr>
			</table>
		<?php
		} //End: if ( $pagenow == 'edit.php' && $_GET['page'] == 'mstw_ss_settings' )
		?>	
		</form>
	</div> <!-- <div class="wrap"> -->
<?php
}

//-------------------------------------------------------------------------------
// Create admin page tabs
//
function mstw_ss_admin_tabs( $current_tab = 'fields-columns-tab' ) {
	$tabs = array( 	'files-columns-tab' => __( 'Display', 'mstw-schedules-scoreboards' ),
					'date-time-tab' => __( 'Date/Time Formats', 'mstw-schedules-scoreboards' ),
					'colors-tab' => __( 'Colors', 'mstw-schedules-scoreboards' ),
					'venues-tab' => __( 'Venues Settings', 'mstw-schedules-scoreboards' ),
					'scoreboards-tab' => __( 'Scoreboards Settings', 'mstw-schedules-scoreboards' ),
					
					);
	//echo '<div id="icon-themes" class="icon32"><br></div>';
	echo '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ){
		$class = ( $tab == $current_tab ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='edit.php?post_type=mstw_ss_game&page=mstw-ss-settings&tab=$tab'>$name</a>";	
	}
	echo '</h2>';
}

//-------------------------------------------------------------------------------
//
// HELP FUNCTIONS
//
//-------------------------------------------------------------------------------
// Callback to add help to settings screen
//	
function mstw_ss_settings_help( ) {
	
	$screen = get_current_screen( );
	
	//mstw_log_msg( "mstw_ss_settings_help:" );
	//mstw_log_msg( '$screen->base: ' . $screen->base );
	//mstw_log_msg( '$screen->base: ' . $screen->post_type );
	//mstw_log_msg( $screen );
	
	$screen = get_current_screen( );
	if ( 'post' === $screen->base && 'mstw-ss-game' !== $screen->post_type ) return;

	$sidebar = '<p><strong>' . __( 'For more information:', 'mstw-schedules-scoreboards' ) . '</strong></p>' .
		'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'MSTW Schedules & Scoreboards Admin Users Manual', 'mstw-schedules-scoreboards' ) . '</a></p>' .
		'<p><a href="http://dev.shoalsummitsolutions.com" target="_blank">' . __( 'See MSTW Schedules in Action', 'mstw-schedules-scoreboards' ) . '</a></p>' .
		'<p><a href="http://wordpress.org/plugins/mstw-schedules-scoreboards/" target="_blank">' . __( 'MSTW Schedules & Scoreboards on WordPress.org', 'mstw-schedules-scoreboards' ) . '</a></p>';
	
	$tabs = array(
		array(
			'title'    => __( 'Data Fields & Columns', 'mstw-schedules-scoreboards' ),
			'id'       => 'fields-columns-help',
			'callback'  => 'mstw_ss_fields_columns_options_help'
			),
		array(
			'title'    => __( 'Date/Time Formats', 'mstw-schedules-scoreboards' ),
			'id'       => 'date-time-help',
			'callback'  => 'mstw_ss_date_time_options_help'
			),
		array(
			'title'		=> __( 'Colors', 'mstw-schedules-scoreboards' ),
			'id'		=> 'colors-help',
			'callback'	=> 'mstw_ss_color_options_help'
			),
		array(
			'title'		=> __( 'Venue Settings', 'mstw-schedules-scoreboards' ),
			'id'		=> 'venues-help',
			'callback'	=> 'mstw_ss_venues_options_help'
			),
	);

	foreach( $tabs as $tab ) {
		$screen->add_help_tab( $tab );
	}
		
	$screen->set_help_sidebar( $sidebar );

}

//----------------------------------------------------------------------------
// help tab content
//
function mstw_ss_fields_columns_options_help( ) {
	$help = '<h3><strong>' . __( 'Data Fields & Columns Settings:', 'mstw-schedules-scoreboards' ) . '</strong></h3>' .
			'<p>' . __('This screen controls the visibility of data fields and the corresponding columns, the field/column labels, and some format elements of the schedule tables, schedule sliders, and countdown timers. ', 'mstw-schedules-scoreboards' ) . "</p>\n" .
			'<p>' . __('Note that these settings apply to ALL schedule tables, sliders, and timers on the site. To control individual tables, sliders, and timers, set the corresponding arguments in the shortcodes.', 'mstw-schedules-scoreboards' ) . "</p>\n" .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-schedules-scoreboards' ) . "</a></p>\n";
	echo $help;
}

function mstw_ss_color_options_help( ) {
	$help = '<h3><strong>' . __( 'Color Settings:', 'mstw-schedules-scoreboards' ) . '</strong></h3>' .
			'<p>' . __('This screen controls the default colors for the schedule tables, sliders, and countdown timers.  These global defaults are very useful for setting the colors of all displays, especially if your site is for a single team or organization. Note that these settings apply to ALL schedule tables and sliders on the website.', 'mstw-schedules-scoreboards' ) . "</p>\n" .
			'<p>' . __('Unique CSS tags are provided for each team, allowing control of individual tables, sliders, and countdown timers. Using these tags, different color schemes can be applied to different. To do so, the plugin\'s stylesheet - mstw-gs-styles.css - must be edited. An admin with a knowledge of CSS can simply inspect the HTML elements in a browser and style them as desired. Those not experienced with CSS, may find some of the tutorials and code snippets on <a href="http://shoalsummitsolutions.com" target="_blank">ShoalSummitSolutions.com</a> may be of use. <br/> <a href="http://dev.shoalsummitsolutions.com/schedule-test/" target="_blank">Examples are provided on the MSTW plugin development site.</a>', 'mstw-schedules-scoreboards' ) . "</p>\n" .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-schedules-scoreboards' ) . "</a></p>\n";
	echo $help;
}
	
function mstw_ss_date_time_options_help( ) {
	$help = '<h3><strong>' . __( 'Date/Time Settings:', 'mstw-schedules-scoreboards' ) . '</strong></h3>' .
			'<p>' . __('This screen controls the date time formats for the schedule tables, sliders, and countdown timers, as well as the admin screens. There are a number of built-in formats and the capability to provide any custom format. ', 'mstw-schedules-scoreboards' ) . "</p>\n" .
			'<p>' . __('Note that these settings apply to ALL schedule tables, sliders, and timers on the site. To control individual tables, sliders, or timers, set the corresponding arguments in the shortcodes.', 'mstw-schedules-scoreboards' ) . "</p>\n" .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-schedules-scoreboards' ) . "</a></p>\n";
	echo $help;
}

function mstw_ss_venues_options_help( ) {
	$help = '<h3><strong>' . __( 'Venues Table Settings:', 'mstw-schedules-scoreboards' ) . '</strong></h3>' .
			'<p>' . __('This screen controls the visibility of columns, their labels, and some format elements of the Venue tables. ', 'mstw-schedules-scoreboards' ) . "</p>\n" .
			'<p>' . __('Note that these settings apply to ALL venue tables on the site. To control individual tables, set the corresponding arguments in the shortcodes.', 'mstw-schedules-scoreboards' ) . "</p>\n" .
			'<p><a href="http://shoalsummitsolutions.com/category/users-manuals/ss-plugin/" target="_blank">' . __( 'See the Game Schedules Users Manual for more documentation.', 'mstw-schedules-scoreboards' ) . "</a></p>\n";
	echo $help;
}

//-----------------------------------------------------------------
// Set up data & fields  section	
//
function mstw_ss_data_fields_setup( ) {
	// Data fields/columns -- show/hide and labels
	$display_on_page = 'mstw-ss-settings';
	$page_section = 'mstw_ss_fields_columns_settings';
	
	//$options = get_option( 'mstw_ss_options' );
	$options = wp_parse_args( 	get_option( 'mstw_ss_options' ), 
								mstw_ss_get_defaults( ) 
							);
	
	add_settings_section(
		$page_section,  //id attribute of tags
		__( 'Data Field and Table Column Settings', 'mstw-schedules-scoreboards' ),	//title of the section
		'mstw_ss_data_fields_inst',		//callback to fill section with desired output - should echo
		$display_on_page				//menu page slug on which to display
	);
	
	$arguments = array(
					// Show/Hide Date Column
					array( 	// the HTML form element to use
						'type'    => 'checkbox', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'show_date',
						// the label for the HTML form element
						'title'	=> __( 'Show Date Column:', 'mstw-schedules-scoreboards' ),
						// the description displayed under the HTML form element
						'desc'	=> __( 'Show or hide the Date field/column. (Default: Show)', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '1', //show
						// only used for select-option and ..
						'options' => "",
						// name of HTML form element
						'name'	=> 'mstw_ss_options[show_date]',
						// current value of field
						'value'	=> $options['show_date'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),
						
					// DATE field/column label
					array( 	// the HTML form element to use
						'type'    => 'text', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'date_label',
						// the label for the HTML form element
						'title'	=> __( 'Date Column Label:', 'mstw-schedules-scoreboards' ), 
						// the description displayed under the HTML form element
						'desc'	=> __( 'Set the label/title for date data field and/or column. (Default: "Date")', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '', 
						// only used for select-option and ..
						'options' => "",
						// name of HTML form element
						'name'	=> 'mstw_ss_options[date_label]',
						// current value of field
						'value'	=> $options['date_label'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),
					
					// OPPONENT field/column label
					array( 	// the HTML form element to use
						'type'    => 'text', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'opponent_label',
						// the label for the HTML form element
						'title'	=>__( 'Opponent Column Label:', 'mstw-schedules-scoreboards' ), 
						// the description displayed under the HTML form element
						'desc'	=> __( 'Set label for opponent data field or column. (Default: "Opponent") NOTE: THE OPPONENT FIELD MUST  BE SHOWN.', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '', 
						// only used for select-option and ..
						'options' => "",
						// name of HTML form element
						'name'	=> 'mstw_ss_options[opponent_label]',
						// current value of field
						'value'	=> $options['opponent_label'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),
						
					// Show/hide LOCATION column
					array( 	// the HTML form element to use
						'type'    => 'checkbox', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'show_location',
						// the label for the HTML form element
						'title'	=> __( 'Show Location Column:', 'mstw-schedules-scoreboards' ),
						// the description displayed under the HTML form element
						'desc'	=> __( 'Show or hide the Location field/column. (Default: Show)', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '1', //show
						// only used for select-option and ..
						'options' => "",
						// name of HTML form element
						'name'	=> 'mstw_ss_options[show_location]',
						// current value of field
						'value'	=> $options['show_location'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),
						
						// LOCATION field/column label
						array( 	// the HTML form element to use
						'type'    => 'text', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'location_label',
						// the label for the HTML form element
						'title'	=>__( 'Location Column Label:', 'mstw-schedules-scoreboards' ), 
						// the description displayed under the HTML form element
						'desc'	=> __( 'Set label for location data field or column. (Default: "Location")', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '', 
						// only used for select-option and ..
						'options' => "",
						// name of HTML form element
						'name'	=> 'mstw_ss_options[location_label]',
						// current value of field
						'value'	=> $options['location_label'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),
						
						// Show/hide TIME/RESULT column
						array( 	// the HTML form element to use
						'type'    => 'checkbox', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'show_time',
						// the label for the HTML form element
						'title'	=> __( 'Show Time/Result Column:', 'mstw-schedules-scoreboards' ),
						// the description displayed under the HTML form element
						'desc'	=> __( 'Show or hide the Time/Result field or column. (Default: Show)', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '1', //show
						// only used for select-option and ..
						'options' => "",
						// name of HTML form element
						'name'	=> 'mstw_ss_options[show_time]',
						// current value of field
						'value'	=> $options['show_time'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),
						
						// TIME/RESULT field/column label
						array( 	// the HTML form element to use
						'type'    => 'text', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'time_label',
						// the label for the HTML form element
						'title'	=>__( 'Time/Result Column Label:', 'mstw-schedules-scoreboards' ), 
						// the description displayed under the HTML form element
						'desc'	=> __( 'Set label for time/result data field or column. (Default: "Time/Result")', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '', 
						// only used for select-option and ..
						'options' => "",
						// name of HTML form element
						'name'	=> 'mstw_ss_options[time_label]',
						// current value of field
						'value'	=> $options['time_label'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),
						
						// Show/hide MEDIA column
						array( 	// the HTML form element to use
							'type'    => 'select-option', 
							// the ID of the setting in options array, 
							// and the ID of the HTML form element
							'id' => 'show_media',
							// the label for the HTML form element
							'title'	=> __( 'Show Media Column:', 'mstw-schedules-scoreboards' ),
							// the description displayed under the HTML form element
							'desc'	=> __( 'Show a number of media fields (1-3) or hide the Media field or column. (Default: Show all 3)', 'mstw-schedules-scoreboards' ),
							// the default value for this setting
							'default' => '',
							// only used for select-option and ..
							'options' => array( __( 'Hide', 'mstw-schedules-scoreboards' ) => 0,
												__( 'Show 1', 'mstw-schedules-scoreboards' ) => 1,
												__( 'Show 2', 'mstw-schedules-scoreboards' ) => 2,
												__( 'Show 3', 'mstw-schedules-scoreboards' ) => 3,
												),
							// name of HTML form element
							'name'	=> 'mstw_ss_options[show_media]',
							// current value of field
							'value'	=> $options['show_media'],
							// page on which to display HTML control
							'page' => $display_on_page,
							// page section in which to display HTML control
							'section' => $page_section,
						),
						
						// MEDIA field/column label
						array( 	// the HTML form element to use
						'type'    => 'text', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'media_label',
						// the label for the HTML form element
						'title'	=>__( 'Media Column Label:', 'mstw-schedules-scoreboards' ), 
						// the description displayed under the HTML form element
						'desc'	=> __( 'Set label for media data field or column. (Default: "Media Links")', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '', 
						// only used for select-option and ..
						'options' => "",
						// name of HTML form element
						'name'	=> 'mstw_ss_options[media_label]',
						// current value of field
						'value'	=> $options['media_label'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),
						
					);
	
	mstw_build_settings_screen( $arguments );	

	//---------------------------------------------------------------
	// TEAM LOGOS SECTION
	//
	$page_section = 'mstw-gs-team-logos-section';
	
	add_settings_section(
		$page_section,	//id attribute of tags
		__( 'Team Name & Logo Settings', 'mstw-schedules-scoreboards' ),	//title of the section
		'mstw_ss_name_logo_inst',	//callback to fill section with desired output - should echo
		$display_on_page	//menu page slug on which to display
	);

	// Opponent name format
	$arguments = array (
					// OPPONENT NAME FORMAT FOR SCHEDULE TABLES
					array( 	// the HTML form element to use
							'type'    => 'select-option', 
							// the ID of the setting in options array, 
							// and the ID of the HTML form element
							'id' => 'table_opponent_format',
							// the label for the HTML form element
							'title'	=> __( 'Opponent Name Format in Schedule TABLES:', 'mstw-schedules-scoreboards' ),
							// the description displayed under the HTML form element
							'desc'	=> __( '(Default: Full Name Only)', 'mstw-schedules-scoreboards' ),
							// the default value for this setting
							'default' => '',
							// only used for select-option and ..
							'options' => array( __( 'Short Name Only', 'mstw-schedules-scoreboards' ) => 'short-name',
												__( 'Full Name Only', 'mstw-schedules-scoreboards' ) => 'full-name',
												__( 'Short Name & Mascot', 'mstw-schedules-scoreboards' ) => 'short-name-mascot',
												__( 'Full Name & Mascot', 'mstw-schedules-scoreboards' ) => 'full-name-mascot',
												),
							// name of HTML form element
							'name'	=> 'mstw_ss_options[table_opponent_format]',
							// current value of field
							'value'	=> $options['table_opponent_format'],
							// page on which to display HTML control
							'page' => $display_on_page,
							// page section in which to display HTML control
							'section' => $page_section,
						),
					// OPPONENT NAME FORMAT FOR SLIDERS
					array( 	// the HTML form element to use
							'type'    => 'select-option', 
							// the ID of the setting in options array, 
							// and the ID of the HTML form element
							'id' => 'slider_opponent_format',
							// the label for the HTML form element
							'title'	=> __( 'Opponent Name Format in Schedule SLIDERS:', 'mstw-schedules-scoreboards' ),
							// the description displayed under the HTML form element
							'desc'	=> __( '(Default: Full Name Only)', 'mstw-schedules-scoreboards' ),
							// the default value for this setting
							'default' => '',
							// only used for select-option and ..
							'options' => array( __( 'Short Name Only', 'mstw-schedules-scoreboards' ) => 'short-name',
												__( 'Full Name Only', 'mstw-schedules-scoreboards' ) => 'full-name',
												__( 'Short Name & Mascot', 'mstw-schedules-scoreboards' ) => 'short-name-mascot',
												__( 'Full Name & Mascot', 'mstw-schedules-scoreboards' ) => 'full-name-mascot',
												),
							// name of HTML form element
							'name'	=> 'mstw_ss_options[slider_opponent_format]',
							// current value of field
							'value'	=> $options['slider_opponent_format'],
							// page on which to display HTML control
							'page' => $display_on_page,
							// page section in which to display HTML control
							'section' => $page_section,
						),
					// SHOW/HIDE LOGO IN SCHEDULE TABLES
					array( 	'type'    => 'select-option',
							'id' => 'show_table_logos',
							'name'	=> 'mstw_ss_options[show_table_logos]',
							'value'	=> $options['show_table_logos'],
							'desc'	=> __( 'NOTE: this setting only applies if scheduled opponents are selected from the MSTW Teams database. . (Default: Show Name Only)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Show Team Logos in Schedule TABLES:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => array( __( 'Show Name Only', 'mstw-schedules-scoreboards' ) => 'name-only',
												__( 'Show Logo & Name', 'mstw-schedules-scoreboards' ) => 'logo-name',
												__( 'Show Logo Only', 'mstw-schedules-scoreboards' ) => 'logo-only',
												),
							'page' => $display_on_page,
							'section' => $page_section,
						),
					// SHOW/HIDE LOGO IN SCHEDULE SLIDERS
					array( 	'type'    => 'select-option',
							'id' => 'show_slider_logos',
							'name'	=> 'mstw_ss_options[show_slider_logos]',
							'value'	=> $options['show_slider_logos'],
							'desc'	=> __( 'NOTE: this setting only applies if scheduled opponents are selected from the MSTW Teams database. (Default: Hide-Show Name Only)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Show Team Logos in Schedule SLIDERS:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => array( __( 'Show Name Only', 'mstw-schedules-scoreboards' ) => 'name-only',
												__( 'Show Logo & Name', 'mstw-schedules-scoreboards' ) => 'logo-name',
												__( 'Show Logo Only', 'mstw-schedules-scoreboards' ) => 'logo-only',
												),
							'page' => $display_on_page,
							'section' => $page_section,
						),
					// Link from OPPONENT field	
					array( 	// the HTML form element to use
						'type'    => 'select-option', 
						// the ID of the setting in options array, 
						// and the ID of the HTML form element
						'id' => 'opponent_link',
						// the label for the HTML form element
						'title'	=>__( 'Link From Opponent Entry in Schedule TABLES & SLIDERS:', 'mstw-schedules-scoreboards' ), 
						// the description displayed under the HTML form element
						'desc'	=> __( 'Select link for Opponent entry (label and/or logo. (Default: "No Link").', 'mstw-schedules-scoreboards' ),
						// the default value for this setting
						'default' => '', 
						// only used for select-option and ..
						'options' => array( __( 'No Link', 'mstw-schedules-scoreboards' ) => 'no-link',
											__( 'Game Page', 'mstw-schedules-scoreboards' ) => 'game-page',
											__( 'Team URL', 'mstw-schedules-scoreboards' ) => 'team-url',
											),
						// name of HTML form element
						'name'	=> 'mstw_ss_options[opponent_link]',
						// current value of field
						'value'	=> $options['opponent_link'],
						// page on which to display HTML control
						'page' => $display_on_page,
						// page section in which to display HTML control
						'section' => $page_section,
						),

					// FORMAT VENUE
					array( 	'type'    => 'select-option',
							'id' => 'venue_format',
							'name'	=> 'mstw_ss_options[venue_format]',
							'value'	=> $options['venue_format'],
							'desc'	=> __( 'NOTE: this setting only applies if scheduled opponents are selected from the MSTW Teams database. (Default: Name Only)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Format for Location:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => array( 
											__( 'Show Name Only', 'mstw-schedules-scoreboards' ) => 'name-only',
											__( 'Show City, State (Name)', 'mstw-schedules-scoreboards' ) => 'city-state-name',
										),
							'page' => $display_on_page,
							'section' => $page_section,
						),
					// LINK FROM VENUE
					array( 	'type'    => 'select-option',
							'id' => 'venue_link_format',
							'name'	=> 'mstw_ss_options[venue_link_format]',
							'value'	=> $options['venue_link_format'],
							'desc'	=> __( 'NOTE: this setting only applies if scheduled opponents are selected from the MSTW Teams database. (Default: No Link)', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Link from Location:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => array( 
											__( 'No Link', 'mstw-schedules-scoreboards' ) => 'no-link',
											__( 'Link to Venue URL', 'mstw-schedules-scoreboards' ) => 'link-to-venue',
											__( 'Link to Map URL', 'mstw-schedules-scoreboards' ) => 'link-to-map',
										),
							'page' => $display_on_page,
							'section' => $page_section,
						),
				);
	
	mstw_build_settings_screen( $arguments );		

} //End mstw_ss_data_fields_setup()
	
//-----------------------------------------------------------------	
// 	Main section instructions	
// 	
function mstw_ss_data_fields_inst( ) {
	echo '<p>' . __( 'Settings to control the visibility of data fields & table columns as well as to change their labels to "re-purpose" the fields. ', 'mstw-schedules-scoreboards' ) .'</p>';
	/* Just in case we add some colors someday
	'<br/>' . __( 'All color values are in hex, starting with a hash(#), followed by either 3 or 6 hex digits. For example, #123abd or #1a2.', 'mstw-schedules-scoreboards' ) .  '</p>';
	*/
} //End: mstw_ss_data_fields_inst()

// ----------------------------------------------------------------	
// 	Team Names & Logos section instructions	
//
function mstw_ss_name_logo_inst( ) {
	echo '<p>' . __( "Control the display of team names & logos. NOTE: THESE SETTINGS ONLY APPLY WHEN SELECTING OPPONENTS FROM THE MSTW TEAMS DATABASE.", 'mstw-schedules-scoreboards' ) . '</p>';
}	

//-------------------------------------------------------------------------------
//
// VALIDATION FUNCTIONS
//
//-------------------------------------------------------------------------------
// Validate the user data entries in Display (fields/data) tab
// 
function mstw_ss_validate_main( $input ) {
	
	//check if the reset button was pressed and confirmed
	//array_key_exists() returns true for null, isset does not
	if ( array_key_exists( 'reset', $input ) ) {
		if( $input['reset'] == 'Resetting Defaults' ) {
			// reset to defaults
			//add_settings_error( 'mstw_ss_settings_main', esc_attr( 'settings-reset' ), 'Settings reset to defaults', 'updated' );
			//add_action('admin_notices', 'mstw_ss_print_errors' );
			$output = mstw_ss_get_defaults( );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults.' );
		}
		else {
			// Don't change nuthin'
			$output = get_option( 'mstw_ss_options' );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults canceled.' );
		}
	}
	else {
		$defaults = mstw_ss_get_defaults( );
		
		//special handling for the checkboxes
		$output['show_date'] = isset( $input['show_date'] ) ? 1 : 0;
		if ( isset( $input['show_date'] ) ) 
			unset( $input['show_date'] );
		$output['show_location'] = isset( $input['show_location'] ) ? 1 : 0;
		if ( isset( $input['show_location'] ) ) 
			unset( $input['show_location'] );
		$output['show_time'] = isset( $input['show_time'] ) ? 1 : 0;
		if ( isset( $input['show_time'] ) ) 
			unset( $input['show_time'] );
		
		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// If the option has a value, sanitize it
			//mstw_log_msg( 'in validate_main ... $key= ' . $key . ' $value= ' . $value );
			
			switch( $key ) {
				case 'date_label':
				case 'opponent_label':
				case 'location_label':
				case 'time_label':
				case 'media_label':
					if( isset( $input[$key] ) and $input[$key] == '' ) {
						$input[$key] = $defaults[$key];
					}
					// intentionally falling thru here
				default:
					if( isset( $input[$key] ) ) {
						$output[$key] = sanitize_text_field( $input[$key] );
					}
					break;
			}
			
		}
		mstw_ss_add_admin_notice( 'updated', 'Display settings updated.' );
	}
	
	return apply_filters( 'mstw_ss_sanitize_options', $output, $input );
	
} //End: mstw_ss_validate_main( )

function mstw_ss_print_errors( ) {
	?>
	<div class="updated">
        <p><?php _e( 'Settings reset to defaults', 'mstw-schedules-scoreboards' ); ?></p>
    </div>
	<?php
}
?>