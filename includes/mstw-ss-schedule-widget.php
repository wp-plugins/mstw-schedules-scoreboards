<?php
 /*---------------------------------------------------------------------------
 *	mstw-ss-schedule-widget.php
 *		Contains the code for the MSTW Schedules & Scoreboards schedule widget
 *		- displays a simple schedule table with date and opponent columns only
 *  	- does NOT include opponent links (no particular reason other than K.I.S.S.)
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
 
class mstw_ss_sched_widget extends WP_Widget {

    // constructs a new widget instance
    public function mstw_ss_sched_widget( ) {
        $widget_ops = array( 
			'classname' => 'mstw_ss_sched_widget_class', 
			'description' => __( 'Display (simple) game schedules.', 'mstw-schedules-scoreboards' ),
			); 
        $this->WP_Widget( 'mstw_ss_sched_widget', 'MSTW Game Schedules', $widget_ops );
    }
 
    // builds the widget settings form
    function form( $instance ) {
        $defaults = array(	'sched_title' => __( 'Schedule', 'mstw-schedules-scoreboards' ),
							'sched_id' => '1', 
							'sched_yr' => date('Y'),
							'sched_start_date' => 0, 
							'sched_end_date' => PHP_INT_MAX, //strtotime( '2999-12-31'), 
							'sched_max_to_show' => -1, 
							); 
							
        $instance = wp_parse_args( (array) $instance, $defaults );
		$sched_title = $instance['sched_title'];
		$sched_id = $instance['sched_id'];
		
		//$sched_start_date = $instance['sched_start_date'];
		if ( $instance['sched_start_date'] == 'now' ) {
			$sched_start_date = 'now';
		}
		else {
			$sched_start_date = date( 'Y-m-d H:i', (int)esc_attr( $instance['sched_start_date'] ) );
		}
		
		$sched_end_date = $instance['sched_end_date'];
		$sched_max_to_show = $instance['sched_max_to_show'];
		
        ?>
        <p><?php _e( 'Schedule Title:', 'mstw-schedules-scoreboards' ) ?><input class="widefat" name="<?php echo $this->get_field_name( 'sched_title' ); ?>"  
            					type="text" value="<?php echo esc_attr( $sched_title ); ?>" /></p>
        <p><?php _e( 'Schedule ID:', 'mstw-schedules-scoreboards' ) ?>  <input class="widefat" name="<?php echo $this->get_field_name( 'sched_id' ); ?>"  
        						type="text" value="<?php echo esc_attr( $sched_id ); ?>" /></p>
		<p><?php _e( 'The dates below MUST be in the format yyyy-mm-dd hh:mm. (You can omit the hh:mm for 00:00.) Otherwise, you can expect unexpected results. Use "now" as the start date to show only future games.', 'mstw-schedules-scoreboards' ) ?></p>
		<p><?php _e( 'Display Start Date:', 'mstw-schedules-scoreboards' ) ?><input class="widefat" name="<?php echo $this->get_field_name( 'sched_start_date' ); ?>"	type="text" value="<?php echo $sched_start_date; ?>" />
		</p>
        <p><?php _e( 'Display End Date:', 'mstw-schedules-scoreboards' ) ?> <input class="widefat" name="<?php echo $this->get_field_name( 'sched_end_date' ); ?>"  type="text" value="<?php echo date('Y-m-d H:i', (int)esc_attr( $sched_end_date ) ); ?>" />
		</p>
		<p><?php _e( 'Maximum # of games to show (-1 to show all games):', 'mstw-schedules-scoreboards' ) ?> <input class="widefat" name="<?php echo $this->get_field_name( 'sched_max_to_show' ); ?>" type="text" value="<?php echo esc_attr( $sched_max_to_show ); ?>" />
		</p>
        <?php
    }
 
    //save the widget settings
    function update($new_instance, $old_instance) {
		
        $instance = $old_instance;
		
		$instance['sched_title'] = strip_tags( $new_instance['sched_title'] );

		$instance['sched_id'] = strip_tags( $new_instance['sched_id'] );
		
		// 'now' means use the current date
		if ( $new_instance['sched_start_date'] == 'now' ) {
			$instance['sched_start_date'] = $new_instance['sched_start_date'];
		}
		else {
			$instance['sched_start_date'] = strtotime( strip_tags( $new_instance['sched_start_date'] ) );
		}
		
		$instance['sched_end_date'] = strtotime( strip_tags( $new_instance['sched_end_date'] ) );
		
		$instance['sched_max_to_show'] = strip_tags( $new_instance['sched_max_to_show'] );
 
        return $instance;
		
    }
 
	//--------------------------------------------------------------------
	// displays the schedule widget
	//
	function widget( $args, $instance ) {
		// $args holds the global theme variables, such as $before_widget
		extract( $args );
		
		// get the options set in the admin display settings screen
		$base_options = get_option( 'mstw_ss_options' );
		$dtg_options = get_option( 'mstw_ss_dtg_options' );
		//$options = get_option( 'mstw_ss_options' );
		$options = array_merge( $base_options, $dtg_options );
		
		// Remove all keys with empty values
		foreach ( $options as $k=>$v ) {
			if( $v == '' ) {
				unset( $options[$k] );
			}
		}
		
		// and merge them with the defaults
		$defaults = array_merge( mstw_ss_get_defaults( ), mstw_ss_get_dtg_defaults( ) );
		$options = wp_parse_args( $options, $defaults );
		
		//echo "<pre>" . print_r( $options ) . "</pre>";
		//echo "<pre>" . print_r( $instance ) . "</pre>";
		//return;
		
		// then merge the parameters passed to the widget with the result									
		//$attribs = shortcode_atts( $args, $atts );
		
		//$options = wp_parse_args( $options, mstw_ss_get_defaults() );
		
		//Build the date format from the display settings
		$date_format = ( $options['table_widget_date_format'] == 'custom' ? $options['custom_table_widget_date_format'] : $options['table_widget_date_format'] );
		
		//$time_format = ( $options['table_widget_time_format'] == 'custom' ? $options['custom_table_widget_time_format'] : $options['table_widget_time_format'] );
		$time_format = "H:i";
		
		echo $before_widget;
		
		$title = apply_filters( 'widget_title', $instance['sched_title'] );
		
		// Get the parameters for get_posts() below
		$sched_id = $instance['sched_id'];
		
		if ( $instance['sched_start_date'] == 'now' ) {
			$first_dtg = current_time( 'timestamp' ); //time( );
		}
		else {
			$first_dtg = $instance['sched_start_date'];
		}
		
		$last_dtg = $instance['sched_end_date'];
		
		$max_to_show = $instance['sched_max_to_show']; 
		
		// show the widget title, if there is one
		if( !empty( $title ) ) {
			echo  $before_title . $title . $after_title;
		}
		
		// Get the game posts for $sched_id 
		$posts = get_posts(array( 'numberposts' => $max_to_show,
								  'relation' => 'AND',
							  	  'post_type' => 'mstw_ss_game',
							  	  'meta_query' => array(
												array(
													'key' => 'game_sched_id',
													'value' => $sched_id,
													'compare' => '='
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
							      'order' => 'ASC' 
							));						
	
   	 	// Make table of posts
		if($posts) {	
			// Start with the table header
        	$output = ''; ?>
        
        	<table class="mstw-ss-sw-tab mstw-ss-sw-tab-<?php echo $sched_id; ?>">
        	<thead class="mstw-ss-sw-tab-head mstw-ss-sw-tab-head-<?php echo $sched_id; ?>"><tr>
				<?php if( $options['show_date'] == 1 ) { ?>
						<th><?php echo $options['widget_date_label'] ?></th>
				<?php } ?>
				<!-- no option on opponent, always shown -->
				<th><?php echo $options['widget_opponent_label'] ?></th>
				<?php if( $options['show_widget_time'] ) { ?>
						<th><?php echo $options['widget_time_label'] ?></th>				
				<?php } ?>	
			</tr></thead>
        
			<?php
			// Loop through the posts and make the rows
			$even_and_odd = array('even', 'odd');
			$row_cnt = 1; // Keeps track of even and odd rows. Start with row 1 = odd.
		
			foreach( $posts as $post ) {
				// set up some housekeeping to make styling in the loop easier
				$is_home_game = get_post_meta($post->ID, 'game_is_home_game', true );
				$even_or_odd_row = $even_and_odd[$row_cnt]; 
				$row_class = "mstw-ss-sw-$even_or_odd_row mstw-ss-sw-$even_or_odd_row" . "_$sched_id";
				if ( $is_home_game ) 
					$row_class = $row_class . ' mstw-ss-sw-home';
			
				$row_tr = '<tr class="' . $row_class . '">';
				//$row_tr = '<tr>';
				$row_td = '<td class="' . $row_class . '">'; 
			
				// create the row
				$row_string = $row_tr;		
			
				// column 1: Build the game date in a specified format
				if( $options['show_date'] == 1 ) { 
					$date_string = mstw_date_loc( $date_format, (int)get_post_meta( $post->ID, 'game_unix_dtg', true ) );
				
					$row_string .= $row_td . $date_string . '</td>';
				}
				// column 2: create the opponent entry
				//$opponent = get_post_meta( $post->ID, '_mstw_ss_opponent', true);
				$opponent = mstw_ss_build_opponent_entry( $post, $options, 'table' );
				
				if ( !$is_home_game ) {
					$opponent = '@ ' . $opponent;
				}
				
				$row_string .= $row_td . $opponent . '</td>';
				
				// column 3: create the time/results entry
				if ( $options['show_widget_time'] ) {
				//if ( true ) {
					$game_time_tba = get_post_meta( $post->ID, 'game_time_tba', true );
					$game_result = get_post_meta( $post->ID, 'game_result', true );
					$time_string = mstw_date_loc( $time_format, (int)get_post_meta( $post->ID, 'game_unix_dtg', true ) );
					if ( !empty( $game_result ) ) {
						//$row_string =  $row_string . $row_td . $game_result . '</td>';
						$row_string .= "{$row_td}{$game_result}</td>";
					}	
					else if ( !empty( $game_time_tba ) ) {
						$time_string = mstw_date_loc( $time_format, (int)get_post_meta( $post->ID, 'game_unix_dtg', true ) );
					
						$row_string .= $row_td . $time_string . '</td>'; 
					}
					else { //show the tame time
						$row_string .=  "{$row_td}{$time_string}</td>";
					}
				}
				echo $row_string . '</tr>';
			
				$row_cnt = 1- $row_cnt;  // Get the styles right
			
			} // end of foreach post

			echo '</table>';
		}
		else { // No posts were found

			_e( 'No Scheduled Games Found', 'mstw-schedules-scoreboards' );

		} // End of if ($posts)
		
		echo $after_widget;
	
	} // end of function widget( )
} // End of class mstw_ss_sched_widget
?>