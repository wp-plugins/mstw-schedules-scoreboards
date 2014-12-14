<?php
/*----------------------------------------------------------------------------
 * mstw-ss-dtg-settings.php
 *	All functions for the MSTW Schedules & Scoreboards Plugin's 
 *		date-time format settings.
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
// Set up date-time format tab
//
function mstw_ss_dtg_format_setup( ) {
	//mstw_log_msg( "<h1>mstw_ss_dtg_format_setup</h1>" );
	mstw_ss_admin_section_setup( );
	mstw_ss_table_section_setup( );
	mstw_ss_cdt_section_setup( );
	mstw_ss_slider_section_setup( );
}

//----------------------------------------------------------
// Admin format settings section
//
function mstw_ss_admin_section_setup( ) {

	$display_on_page =  'mstw-ss-dtg-settings';
	$page_section = 'mstw-ss-admin-dtg-settings';
	
	$options = wp_parse_args( get_option( 'mstw_ss_dtg_options' ), mstw_ss_get_dtg_defaults( ) );
	
	add_settings_section(
		$page_section,
		__( 'Admin Page Formats', 'mstw-schedules-scoreboards' ),
		'mstw_ss_admin_dtg_inst',
		$display_on_page
		);
	
	$arguments = array(
					array( 	// ADMIN DATE FORMAT
							'type'    => 'date-only', 
							'id' => 'admin_date_format',
							'name'	=> 'mstw_ss_dtg_options[admin_date_format]',
							'value'	=> $options['admin_date_format'],
							'desc'	=> __( 'Formats for 7 Apr 2013.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Admin Table Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// ADMIN CUSTOM DATE FORMAT
							'type'    => 'text', 
							'id' => 'custom_admin_date_format',
							'name'	=> 'mstw_ss_dtg_options[custom_admin_date_format]',
							'value'	=> $options['custom_admin_date_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Admin Table Custom Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// ADMIN TIME FORMAT
							'type'    => 'time-only', 
							'id' => 'admin_time_format',
							'name' => 'mstw_ss_dtg_options[admin_time_format]',
							'value' => $options['admin_time_format'],
							'desc'	=> __( 'Formats for eight in the morning.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Admin Table Time Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// ADMIN CUSTOM TIME FORMAT
							'type'    => 'text', 
							'id' => 'custom_admin_time_format',
							'name'	=> 'mstw_ss_dtg_options[custom_admin_time_format]',
							'value'	=> $options['custom_admin_time_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Custom Admin Table Time Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					);

	mstw_build_settings_screen( $arguments );
	
} //End: mstw_ss_admin_section_setup( )

//----------------------------------------------------------------------	
// 	Admin section instructions
//
function mstw_ss_admin_dtg_inst( ) {
	echo '<p>' . __( 'Enter the date-time formats for the admin pages. ', 'mstw-schedules-scoreboards' ) . '<br>' .  __( 'If a custom PHP date() format is entered, it will override the format setting above it. NOTE: there is no error checking of custom formats, so you should know what you are doing before entering a custom format string.', 'mstw-schedules-scoreboards' ) . '</p>';
}
	
//----------------------------------------------------------
// Table format settings section
//
function mstw_ss_table_section_setup( ) {

	$display_on_page =  'mstw-ss-dtg-settings';
	$page_section = 'mstw-ss-table-dtg-settings';
	
	$options = wp_parse_args( get_option( 'mstw_ss_dtg_options' ), mstw_ss_get_dtg_defaults( ) );
	
	add_settings_section(
		$page_section,
		__( 'Table Shortcode & Widget Formats', 'mstw-schedules-scoreboards' ),
		'mstw_ss_table_dtg_inst',
		$display_on_page
		);
	
	$arguments = array(
					array( 	// DATE FORMAT FOR SCHEDULE TABLE SHORTCODE
							'type'    => 'date-only', 
							'id' => 'table_date_format',
							'name'	=> 'mstw_ss_dtg_options[table_date_format]',
							'value'	=> $options['table_date_format'],
							'desc'	=> __( 'Formats for 7 Apr 2013.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Schedule Table [shortcode] Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// CUSTOM DATE FORMAT FOR SCHEDULE TABLE SHORTCODE
							'type'    => 'text', 
							'id' => 'custom_table_date_format',
							'name'	=> 'mstw_ss_dtg_options[custom_table_date_format]',
							'value'	=> $options['custom_table_date_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Schedule Table [shortcode] Custom Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// SCHEDULE TABLE SHORTCODE TIME FORMAT
							'type'    => 'time-only', 
							'name' => 'mstw_ss_dtg_options[table_time_format]',
							'id' => 'table_time_format',
							'value' => $options['table_time_format'],
							'title'	=> __( 'Schedule Table [shortcode] Time Format:', 'mstw-schedules-scoreboards' ),
							'desc'	=> __( 'Formats for eight in the morning.', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// SCHEDULE TABLE SHORTCODE CUSTOM TIME FORMAT
							'type'    => 'text', 
							'id' => 'custom_table_time_format',
							'name'	=> 'mstw_ss_dtg_options[custom_table_time_format]',
							'value'	=> $options['custom_table_time_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Admin Table Custom Time Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// DATE FORMAT FOR SCHEDULE TABLE WIDGET
							'type'    => 'date-only', 
							'id' => 'table_widget_date_format',
							'name'	=> 'mstw_ss_dtg_options[table_widget_date_format]',
							'value'	=> $options['table_widget_date_format'],
							'desc'	=> __( 'Formats for 7 Apr 2013.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Schedule Table (widget) Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// CUSTOM DATE FORMAT FOR SCHEDULE TABLE WIDGET
							'type'    => 'text', 
							'id' => 'custom_table_widget_date_format',
							'name'	=> 'mstw_ss_dtg_options[custom_table_widget_date_format]',
							'value'	=> $options['custom_table_widget_date_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Schedule Table Widget Custom Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					);
	
	mstw_build_settings_screen( $arguments );	
	
} //End: mstw_ss_table_section_setup( )

//----------------------------------------------------------------------	
// 	Table section instructions
//
function mstw_ss_table_dtg_inst( ) {
	echo '<p>' . __( 'Enter the date-time formats for the schedule tables [shortcodes] and widgets. ', 'mstw-schedules-scoreboards' ) . '<br>' .  __( 'If a custom PHP date() format is entered, it will override the format setting above it. NOTE: there is no error checking of custom formats, so you should know what you are doing before entering a custom format string.', 'mstw-schedules-scoreboards' ) . '</p>';
}
	
//----------------------------------------------------------
// Countdown timer date-time settings section
//
function mstw_ss_cdt_section_setup( ) {
	
	$display_on_page =  'mstw-ss-dtg-settings';
	$page_section = 'mstw-ss-cdt-dtg-settings';
	
	$options = wp_parse_args( get_option( 'mstw_ss_dtg_options' ), mstw_ss_get_dtg_defaults( ) );
	
	add_settings_section(
		$page_section,
		__( 'Countdown Timer Formats', 'mstw-schedules-scoreboards' ),
		'mstw_ss_cdt_dtg_inst',
		$display_on_page
		);
	
	$arguments = array(
					array( 	// DATE-TIME FORMAT FOR COUNTDOWN TIMER
							'type'    => 'date-time', 
							'id' => 'cdt_dtg_format',
							'name'	=> 'mstw_ss_dtg_options[cdt_dtg_format]',
							'value'	=> $options['cdt_dtg_format'],
							'desc'	=> __( 'Formats for 7 Apr 2013 13:15.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Countdown Timer (widget & [shortcode]) Date & Time Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// CUSTOM DATE-TIME FORMAT FOR COUNTDOWN TIMER
							'type'    => 'text', 
							'id' => 'custom_cdt_dtg_format',
							'name'	=> 'mstw_ss_dtg_options[custom_cdt_dtg_format]',
							'value'	=> $options['custom_cdt_dtg_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=>  __( 'Countdown Timer (widget & [shortcode]) Custom Date & Time Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// DATE (ONLY) FORMAT FOR COUNTDOWN TIMER
							'type'    => 'date-only', 
							'id' => 'cdt_date_format',
							'name'	=> 'mstw_ss_dtg_options[cdt_date_format]',
							'value'	=> $options['cdt_date_format'],
							'desc'	=> __( 'Formats for 7 Apr 2013. Used when game time is TBA.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Countdown Timer (widget & [shortcode]) Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// CUSTOM DATE (ONLY) FORMAT FOR COUNTDOWN TIMER
							'type'    => 'text', 
							'id' => 'custom_cdt_date_format',
							'name'	=> 'mstw_ss_dtg_options[custom_cdt_date_format]',
							'value'	=> $options['custom_cdt_date_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string, which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Countdown Timer (widget & [shortcode]) Custom Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					);
	
	mstw_build_settings_screen( $arguments );

} //End: mstw_ss_cdt_section_setup( )

//----------------------------------------------------------------------	
// 	Countdown timer section instructions
//
function mstw_ss_cdt_dtg_inst( ) {
	echo '<p>' . __( 'Enter the date-time formats for the countdown timer [shortcode] & widgets. ', 'mstw-schedules-scoreboards' ) . '<br>' . __( 'If a custom PHP date() format is entered, it will override the format setting above it. NOTE: there is no error checking of custom formats, so you should know what you are doing before entering a custom format string.', 'mstw-schedules-scoreboards' ) . '</p>';
}
	
//----------------------------------------------------------
// Slider date-time settings section
//
function mstw_ss_slider_section_setup( ) {
	
	$display_on_page =  'mstw-ss-dtg-settings';
	$page_section = 'mstw-ss-slider-dtg-settings';
	
	$options = wp_parse_args( get_option( 'mstw_ss_dtg_options' ), mstw_ss_get_dtg_defaults( ) );
	
	add_settings_section(
		$page_section,
		__( 'Schedule Slider Formats', 'mstw-schedules-scoreboards' ),
		'mstw_ss_slider_dtg_inst',
		$display_on_page
		);
		
	
	$arguments = array(
					array( 	// DATE FORMAT FOR SCHEDULE SLIDER
							'type'    => 'date-only', 
							'id' => 'slider_date_format',
							'name'	=> 'mstw_ss_dtg_options[slider_date_format]',
							'value'	=> $options['slider_date_format'],
							'desc'	=> 'Formats for 7 Apr 2013.',
							'title'	=> __( 'Schedule Slider Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// CUSTOM DATE FORMAT FOR SCHEDULE SLIDER
							'type'    => 'text', 
							'id' => 'custom_slider_date_format',
							'name'	=> 'mstw_ss_dtg_options[custom_slider_date_format]',
							'value'	=> $options['custom_slider_date_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=>  __( 'Schedule Slider Custom Date Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),	
					array( 	// TIME FORMAT FOR SCHEDULE SLIDER
							'type'    => 'time-only', 
							'name' => 'mstw_ss_dtg_options[slider_time_format]',
							'id' => 'slider_time_format',
							'value' => $options['slider_time_format'],
							'desc'	=> __( 'Formats for eight in the morning.', 'mstw-schedules-scoreboards' ),
							'title'	=> __( 'Schedule Slider Time Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					array( 	// CUSTOM TIME FORMAT FOR SCHEDULE SLIDER
							'type'    => 'text', 
							'id' => 'custom_slider_time_format',
							'name'	=> 'mstw_ss_dtg_options[custom_slider_time_format]',
							'value'	=> $options['custom_slider_time_format'],
							'desc'	=> __( 'Enter a custom PHP date() format string which will override the above setting.', 'mstw-schedules-scoreboards' ),
							'title'	=>  __( 'Schedule Slider Custom Time Format:', 'mstw-schedules-scoreboards' ),
							'default' => '',
							'options' => '',
							'page' => $display_on_page,
							'section' => $page_section,
						),
					);
	
	mstw_build_settings_screen( $arguments );	
	
} //End: mstw_ss_slider_section_setup

//----------------------------------------------------------------------	
// 	Slider section instructions
//
function mstw_ss_slider_dtg_inst( ) {
	echo '<p>' . __( 'Enter the date-time formats for the schedule slider [shortcode]', 'mstw-schedules-scoreboards' ) . '<br>' .  __( 'If a custom PHP date() format is entered, it will override the format setting above it. NOTE: there is no error checking of custom formats, so you should know what you are doing before entering a custom format string.', 'mstw-schedules-scoreboards' ) . '</p>';
}

//-------------------------------------------------------------------------------
// Validate the user data entries in Date/Time Formats tab
// 
function mstw_ss_validate_dtg( $input ) {
	//mstw_log_msg( 'in mstw_ss_validate_main' );
	//$output = $input;
	// Create an array for storing the validated options
	$output = array();
	
	//check if the reset button was pressed and confirmed
	//array_key_exists() returns true for null, isset does not
	if ( array_key_exists( 'reset', $input ) ) {
		if( $input['reset'] == 'Resetting Defaults' ) {
			// reset to defaults
			//add_settings_error( 'mstw_ss_settings_main', esc_attr( 'settings-reset' ), 'Settings reset to defaults', 'updated' );
			//add_action('admin_notices', 'mstw_ss_print_errors' );
			$output = mstw_ss_get_dtg_defaults( );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults.' );
		}
		else {
			// return the previous (last good) options
			$output = get_option( 'mstw_ss_dtg_options' );
			mstw_ss_add_admin_notice( 'updated', 'Settings reset to defaults canceled.' );
		}
	}
	else {
		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// If the option has a value, sanitize it
			if( isset( $input[$key] ) ) {
				$output[$key] = sanitize_text_field( $input[$key] );
			} // end if isset
		} // end foreach
		mstw_ss_add_admin_notice( 'updated', 'Date/time settings updated.' );
	}
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'mstw_ss_sanitize_dtg_options', $output, $input );

} //End: mstw_ss_validate_dtg( )
?>