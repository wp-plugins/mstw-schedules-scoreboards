<?php
 /*---------------------------------------------------------------------------
 *	mstw-ss-cdt-widget.php
 *		Contains the code for the MSTW Schedules & Scoreboards countdown
 *			timer widget
 *		- displays a countdown timer to next scheduled game or next
 *			scheduled home game
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

class mstw_ss_cdt_widget extends WP_Widget {

/*--------------------------------------------------------------------
 * construct the widget
 *------------------------------------------------------------------*/	
	function mstw_ss_cdt_widget( ) {
		// processes the widget
		 $widget_ops = array( 
			'classname' => 'mstw_ss_cdt_widget_class', 
			'description' => __( 'Display a countdown timer to the next scheduled game.', 'mstw-schedules-scoreboards' ), 
			); 
        $this->WP_Widget( 'mstw_ss_cdt_widget', 'MSTW Countdown Timer', $widget_ops );
	}
	
/*--------------------------------------------------------------------
 * display/manage the countdown widget settings form
 *------------------------------------------------------------------*/
	
	function form( $instance ) {
	
        $defaults = array(	'cd_title' => __( 'Countdown', 'mstw-schedules-scoreboards' ),
							'sched' => '1', 
							'intro' => __( 'Time to kickoff:', 'mstw-schedules-scoreboards' ), 
							'home_only' => 0, 
							); 
							
		$instance = wp_parse_args( (array) $instance, $defaults );
							
		//$options = get_option( 'mstw_ss_options' );
		//$output = '<pre>OPTIONS:' . print_r( $options, true ) . '</pre>';
	
		// and merge them with the defaults
		//$new_args = wp_parse_args( $options, mstw_ss_get_defaults( ) );
		//$output .= '<pre>ARGS:' . print_r( $args, true ) . '</pre>';
	
		// then merge the parameters passed to the shortcode with the result									
		//$attribs = wp_parse_args( $new_args, (array) $instance );					
		
        
		$cd_title = $instance['cd_title'];
		$sched = $instance['sched'];
		$home_only = $instance['home_only'];
		$intro = $instance['intro'];
		
		mstw_log_msg( '$home_only = ' . $home_only );
		
        ?>
        <p><?php _e( 'Countdown Title:', 'mstw-schedules-scoreboards' ) ?><input class="widefat" name="<?php echo $this->get_field_name( 'cd_title' ); ?>"  
            					type="text" value="<?php echo esc_attr( $cd_title ); ?>" /></p>
        
        <p><?php _e( 'Schedule ID:', 'mstw-schedules-scoreboards' ) ?><input class="widefat" name="<?php echo $this->get_field_name( 'sched' ); ?>"  
        						type="text" value="<?php echo esc_attr( $sched ); ?>" /></p> 
		
		<p><input class="checkbox" type="checkbox" <?php checked( $home_only, 1 ); ?> id="<?php echo $this->get_field_id( 'home_only' ); ?>" name="<?php echo $this->get_field_name( 'home_only' ); ?>" /> 
		<label for="<?php echo $this->get_field_id( 'home_only' ); ?>"><?php _e( 'Use home games only?', 'mstw-schedules-scoreboards' ) ?></label></p>
		
        <p><?php _e( 'Countdown Intro Text:', 'mstw-schedules-scoreboards' ) ?> <input class="widefat" name="<?php echo $this->get_field_name( 'intro' ); ?>"
        						type="text" value="<?php echo esc_attr( $intro ); ?>" /></p>
            
        <?php 
    }
	
/*--------------------------------------------------------------------
 * saves the countdown widget settings
 *------------------------------------------------------------------*/	
   function update($new_instance, $old_instance) {
		
        $instance = $old_instance;
		
		$instance['cd_title'] = strip_tags( $new_instance['cd_title'] );

		$instance['sched'] = strip_tags( $new_instance['sched'] );
		
		// got to handle checkboxes uniquely
		$instance['home_only'] = empty( $new_instance['home_only'] ) ? 0 : 1;
		
		$instance['intro'] = strip_tags( $new_instance['intro'] );
 
        return $instance;
		
    }
	
/*--------------------------------------------------------------------
 * displays the countdown widget
 *------------------------------------------------------------------*/		

	function widget( $args, $instance ) {
		
		// $args holds the global theme variables, such as $before_widget
		extract( $args );
		
		echo $before_widget;
		
		$title = apply_filters( 'widget_title', $instance['cd_title'] );
		
		// get the options set in the admin screen
		$dtg_options = get_option( 'mstw_ss_dtg_options' );
		$base_options = get_option( 'mstw_ss_options' );
		$options = array_merge( $base_options, (array)$dtg_options );
		//$output = '<pre>OPTIONS:' . print_r( $options, true ) . '</pre>';
		
		// Remove all keys with empty values
		foreach ( $options as $k=>$v ) {
			if( $v == '' ) {
				unset( $options[$k] );
			}
		}
	
		// and merge them with the defaults
		$new_args = wp_parse_args( $options, mstw_ss_get_defaults( ) );
		//$output .= '<pre>ARGS:' . print_r( $args, true ) . '</pre>';
	
		// then merge the parameters passed to the shortcode with the result									
		$attribs = wp_parse_args( (array) $instance, $new_args );
		//$output = '<pre>NEW ARGS:' . print_r( $new_args, true ) . '</pre>';
		//$output .= '<pre>INSTANCE:' . print_r( $instance, true ) . '</pre>';
		//echo $output;
		
		// Get the parameters for get_posts() below
		//$sched = $instance['sched'];
		//$home_only = $instance['home_only'];
		//$intro = $instance['intro'];
		
		if( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
			
        echo mstw_ss_build_countdown( $attribs ); 
		
		//echo '<pre>' . print_r( $attribs, true ) . '</pre>';
		//return;
        
        //echo $cd_str;
		
		echo $after_widget;
      	
	} // end of function widget()
	
} // end of class mstw_ss_cdt_widget

?>