<?php
/*---------------------------------------------------------------------------
 *	mstw-ss-cpts.php
 *		Registers the custom post types for MSTW Schedules & Scoreboards
 *		mstw_ss_game, mstw_ss_team, mstw_ss_schedule, mstw_ss_venue
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
 
// ----------------------------------------------------------------
// Register the MSTW Schedules & Scoreboards Custom Post Types 
//	mstw_ss_game, mstw_ss_team, mstw_ss_schedule, mstw_ss_venue
//
function mstw_ss_register_cpts( ) {
	
	$menu_icon_url = MSTW_SS_IMAGES_DIR .  '/mstw-admin-menu-icon.png';
			
	$capability = 'read';
		
	//-----------------------------------------------------------------------
	// register mstw_ss_game custom post type
	//
	$args = array(
		'label'				=> __( 'Games', 'mstw-schedules-scoreboards' ),
		'description'		=> __( 'CPT for games in MSTW Schedules & Scoreboards Plugin', 'mstw-schedules-scoreboards' ),
		
		'public' 			=> true,
		'exclude_from_search'	=> true, //default is opposite value of public
		'publicly_queryable'	=> true, //default is value of public
		'show_ui'			=> true, //default is value of public
		'show_in_nav_menus'	=> false, //default is value of public
		//going to build own admin menu
		'show_in_menu'		=> false, //default is value of show_ui
		'show_in_admin_bar' => false, //default is value of show_in_menu
		//only applies if show_in_menu is true
		//'menu_position'		=> 25, //25 is below comments, which is the default
		'menu_icon'     	=> $menu_icon_url,
		
		//'capability_type'	=> 'post' //post is the default
		//'capabilities'		=> null, //array default is constructed from capability_type
		//'map_meta_cap'	=> null, //null is the default
		
		//'hierarchical'	=> false, //false is the default
		
		'rewrite' 			=> array(
			'slug' 			=> 'game',
			'with_front' 	=> false,
		),
		
		'supports' 			=> array( 'title' ),
		
		//post is the default capability type
		'capability_type'	=> array( 'game', 'games' ), 
		
		'map_meta_cap' 		=> true,
									
		//'register_meta_box_cb'	=> no default for this one
		
		'taxonomies' => 	array( 'mstw_ss_scoreboard' ),
		
		// Note that is interacts with exclude_from_search
		//'has_archive'		=> false, //false is the default
		
		'query_var' 		=> true, //post_type is default mstw_ss_game
		'can_export'		=> true, //default is true
		
		'labels' 			=> array(
									'name' => __( 'Games', 'mstw-schedules-scoreboards' ),
									'singular_name' => __( 'Game', 'mstw-schedules-scoreboards' ),
									'all_items' => __( 'Games', 'mstw-schedules-scoreboards' ),
									'add_new' => __( 'Add New Game', 'mstw-schedules-scoreboards' ),
									'add_new_item' => __( 'Add Game', 'mstw-schedules-scoreboards' ),
									'edit_item' => __( 'Edit Game', 'mstw-schedules-scoreboards' ),
									'new_item' => __( 'New Game', 'mstw-schedules-scoreboards' ),
									//'View Game Schedule' needs a custom page template that is of no value.
									'view_item' => __( 'View Game', 'mstw-schedules-scoreboards' ),
									'search_items' => __( 'Search Games', 'mstw-schedules-scoreboards' ),
									'not_found' => __( 'No Games Found', 'mstw-schedules-scoreboards' ),
									'not_found_in_trash' => __( 'No Games Found In Trash', 'mstw-schedules-scoreboards' ),
									)
		);
		
	register_post_type( 'mstw_ss_game', $args);
	
	//
	// Register the scoreboard taxonomy ... acts like a tag
	//
	$labels = array( 
				'name' => __( 'MSTW Scoreboards', 'mstw-schedules-scoreboards' ),
				'singular_name' =>  __( 'Scoreboard', 'mstw-schedules-scoreboards' ),
				'search_items' => __( 'Search Scoreboards', 'mstw-schedules-scoreboards' ),
				'popular_items' => null, //removes tagcloud __( 'Popular Scoreboards', 'mstw-schedules-scoreboards' ),
				'all_items' => __( 'All Scoreboards', 'mstw-schedules-scoreboards' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit Scoreboard', 'mstw-schedules-scoreboards' ), 
				'update_item' => __( 'Update Scoreboard', 'mstw-schedules-scoreboards' ),
				'add_new_item' => __( 'Add New Scoreboard', 'mstw-schedules-scoreboards' ),
				'new_item_name' => __( 'New Scoreboard Name', 'mstw-schedules-scoreboards' ),
				'separate_items_with_commas' => __( 'Add game to one or more scoreboards (separate scoreboards with commas).', 'mstw-schedules-scoreboards' ),
				'add_or_remove_items' => __( 'Add or Remove Scoreboards', 'mstw-schedules-scoreboards' ),
				'choose_from_most_used' => __( 'Choose from the most used scoreboards', 'mstw-schedules-scoreboards' ),
				'not_found' => __( 'No scoreboards found', 'mstw-schedules-scoreboards' ),
				'menu_name'  => __( 'Scoreboards', 'mstw-schedules-scoreboards' ),
			  );
			  
	$args = array( 
			//'label'				=> 'MSTW Scoreboards', //overridden by $labels->name
			'labels'				=> $labels,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_tagcloud'			=> false,
			//'meta_box_cb'			=> null, provide callback fcn for meta box display
			'show_admin_column'		=> true, //allow automatic creation of taxonomy column in associated post-types table.
			'hierarchical' 			=> false, //behave like tags
			//'update_count_callback'	=> '',
			'query_var' 			=> true, 
			'rewrite' 				=> true,
			'capabilities'			=> array(
											'manage_terms' => 'manage_ss_scoreboards',
											'edit_terms' => 'manage_ss_scoreboards',
											'delete_terms' => 'manage_ss_scoreboards',
											'assign_terms' => 'manage_ss_scoreboards',
											),
			//'sort'					=> null,
		);
		
	register_taxonomy( 'mstw_ss_scoreboard', 'mstw_ss_game', $args );
	register_taxonomy_for_object_type( 'mstw_ss_scoreboard', 'mstw_ss_game' );
	
	//mstw_log_msg( 'in mstw-ss-cpts ...' );
	//if( taxonomy_exists( 'mstw_ss_scoreboard' ) ) {
	//	$tax = get_taxonomy( 'mstw_ss_scoreboard' );
	//	mstw_log_msg( $tax->cap );
	//}
	//else {
	//	mstw_log_msg( 'mstw_ss_scoreboard taxonomy does not exist.' );
	//}
	
	//----------------------------------------------------------------------------
	// register mstw_ss_team post type
	//
	$args = array(
		'public' 			=> true,
		'show_ui'			=> true,
		'show_in_menu'		=> false, //default is value of show_ui
		'show_in_admin_bar' => false, //default is value of show_in_menu
		
		'menu_icon'     	=> $menu_icon_url,
		//'show_in_menu' 		=> 'edit.php?post_type=scheduled_games',
		'query_var' 		=> true, //default is mstw_ss_team
		'rewrite' 			=> array(
			'slug' 			=> 'mstw-ss-team',
			'with_front' 	=> false,
		),
		
		'supports' 			=> array( 'title' ),
		
		//post is the default capability type
		'capability_type'	=> array( 'team', 'teams' ), 
		
		'map_meta_cap' 		=> true,
		
		'labels' 			=> array(
									'name' => __( 'Teams', 'mstw-schedules-scoreboards' ),
									'singular_name' => __( 'Team', 'mstw-schedules-scoreboards' ),
									'all_items' => __( 'Teams', 'mstw-schedules-scoreboards' ),
									'add_new' => __( 'Add New Team', 'mstw-schedules-scoreboards' ),
									'add_new_item' => __( 'Add Team', 'mstw-schedules-scoreboards' ),
									'edit_item' => __( 'Edit Team', 'mstw-schedules-scoreboards' ),
									'new_item' => __( 'New Team', 'mstw-schedules-scoreboards' ),
									//'View Game Schedule' needs a custom page template that is of no value.
									'view_item' => null, 
									'search_items' => __( 'Search Teams', 'mstw-schedules-scoreboards' ),
									'not_found' => __( 'No Teams Found', 'mstw-schedules-scoreboards' ),
									'not_found_in_trash' => __( 'No Teams Found In Trash', 'mstw-schedules-scoreboards' ),
									)
		);
		
	register_post_type( 'mstw_ss_team', $args);
	
	
	
	//---------------------------------------------------------------------
	// register mstw_ss_schedule post type
	//
	$args = array(
		'public' 			=> true,
		'show_ui'			=> true,		
		'show_in_menu'		=> false,
		'show_in_admin_bar' => false, //default is value of show_in_menu
		
		'menu_icon'     	=> $menu_icon_url,
		
		'query_var' 		=> true, //default is 'mstw_ss_schedule',
		'rewrite' 			=> array(
			'slug' 			=> 'mstw-ss-schedule',
			'with_front' 	=> false,
		),
		
		'supports' 			=> array( 'title' ),
		
		//post is the default capability type
		'capability_type'	=> array( 'schedule', 'schedules' ), 
		
		'map_meta_cap' 		=> true,
		
		//not needed because will use the capability type base names
		//'capabilities'		=> null, //array default is constructed from capability_type
		
		'labels' 			=> array(
									'name' => __( 'Schedules', 'mstw-schedules-scoreboards' ),
									'singular_name' => __( 'Schedule', 'mstw-schedules-scoreboards' ),
									'all_items' => __( 'Schedules', 'mstw-schedules-scoreboards' ),
									'add_new' => __( 'Add New Schedule', 'mstw-schedules-scoreboards' ),
									'add_new_item' => __( 'Add Schedule', 'mstw-schedules-scoreboards' ),
									'edit_item' => __( 'Edit Schedule', 'mstw-schedules-scoreboards' ),
									'new_item' => __( 'New Schedule', 'mstw-schedules-scoreboards' ),
									//'View Game Schedule' needs a custom page template that is of no value.
									'view_item' => null, 
									'search_items' => __( 'Search Schedules', 'mstw-schedules-scoreboards' ),
									'not_found' => __( 'No Schedules Found', 'mstw-schedules-scoreboards' ),
									'not_found_in_trash' => __( 'No Schedules Found In Trash', 'mstw-schedules-scoreboards' ),
									)
		);
		
	register_post_type( 'mstw_ss_schedule', $args);
	
	//----------------------------------------------------------------------------
	// register mstw_ss_sport post type
	//
	$args = array(
		'public' 			=> true,
		'show_ui'			=> true,
		//'show_in_menu'		=> false, //default is value of show_ui
		'show_in_menu'		=> false,
		'show_in_admin_bar' => false, //default is value of show_in_menu
		
		'menu_icon'     	=> $menu_icon_url,
		//'show_in_menu' 		=> 'edit.php?post_type=scheduled_games',
		'query_var' 		=> true, //default is mstw_ss_sport
		'rewrite' 			=> array(
			'slug' 			=> 'mstw-ss-sport',
			'with_front' 	=> false,
		),
		
		'supports' 			=> array( 'title' ),
		
		//post is the default capability type
		'capability_type'	=> array( 'sport', 'sports' ), 
		
		'map_meta_cap' 		=> true,
		
		'labels' 			=> array(
									'name' => __( 'Sports', 'mstw-schedules-scoreboards' ),
									'singular_name' => __( 'Sport', 'mstw-schedules-scoreboards' ),
									'all_items' => __( 'All Sports', 'mstw-schedules-scoreboards' ),
									'add_new' => __( 'Add New Sport', 'mstw-schedules-scoreboards' ),
									'add_new_item' => __( 'Add Sport', 'mstw-schedules-scoreboards' ),
									'edit_item' => __( 'Edit Sport', 'mstw-schedules-scoreboards' ),
									'new_item' => __( 'New Sport', 'mstw-schedules-scoreboards' ),
									//'View Game Schedule' needs a custom page template that is of no value.
									'view_item' => null, 
									'search_items' => __( 'Search Sports', 'mstw-schedules-scoreboards' ),
									'not_found' => __( 'No Sports Found', 'mstw-schedules-scoreboards' ),
									'not_found_in_trash' => __( 'No Sports Found In Trash', 'mstw-schedules-scoreboards' ),
									)
		);
		
	register_post_type( 'mstw_ss_sport', $args);
	
	//----------------------------------------------------------------------------
	// register mstw_ss_venue post type - replacement for game_location CPT
	//
	
	$args = array(
    	'public' 			=> true,
		'show_ui'			=> true,
		'show_in_menu'		=> false, //default is value of show_ui
		'show_in_admin_bar' => false, //default is value of show_in_menu
		
		'menu_icon'     	=> $menu_icon_url,
		
        'query_var' => true, //default is mstw_ss_venue
		
        'rewrite' => array(
            'slug' => 'mstw-ss-venue',
            'with_front' => false,
        ),
		
        'supports' => array( 'title' ),
		
		//post is the default capability type
		'capability_type'	=> array( 'venue', 'venues' ),

		'map_meta_cap' 		=> true,		
		
        'labels' => array(
            'name' => __( 'Venues', 'mstw-schedules-scoreboards' ),
            'singular_name' => __( 'Venue', 'mstw-schedules-scoreboards' ),
			'all_items' => __( 'All Venues', 'mstw-schedules-scoreboards' ),
            'add_new' => __( 'Add New Venue', 'mstw-schedules-scoreboards' ),
            'add_new_item' => __( 'Add Venue', 'mstw-schedules-scoreboards' ),
            'edit_item' => __( 'Edit Venue', 'mstw-schedules-scoreboards' ),
            'new_item' => __( 'New Venue', 'mstw-schedules-scoreboards' ),
			'view_item' => null, //'View Venue needs a custom page template that is of little value.
            'search_items' => __( 'Search Venues', 'mstw-schedules-scoreboards' ),
            'not_found' => __( 'No Venues Found', 'mstw-schedules-scoreboards' ),
            'not_found_in_trash' => __( 'No Venues Found In Trash', 'mstw-schedules-scoreboards' ),
        	),
		);
	
	register_post_type( 'mstw_ss_venue', $args );
	
	//
	// Register the venue taxonomy ... acts like a tag
	//
	$labels = array( 
				'name' => __( 'MSTW Venue Groups', 'mstw-schedules-scoreboards' ),
				'singular_name' =>  __( 'Venue Group', 'mstw-schedules-scoreboards' ),
				'search_items' => __( 'Search Venue Groups', 'mstw-schedules-scoreboards' ),
				'popular_items' => null, //removes tagcloud __( 'Popular Scoreboards', 'mstw-schedules-scoreboards' ),
				'all_items' => __( 'All Venue Groups', 'mstw-schedules-scoreboards' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit Venue Group', 'mstw-schedules-scoreboards' ), 
				'update_item' => __( 'Update Venue Group', 'mstw-schedules-scoreboards' ),
				'add_new_item' => __( 'Add New Venue Group', 'mstw-schedules-scoreboards' ),
				'new_item_name' => __( 'New Venue Group Name', 'mstw-schedules-scoreboards' ),
				'separate_items_with_commas' => __( 'Add venue to one or more venue groups (separate groups with commas).', 'mstw-schedules-scoreboards' ),
				'add_or_remove_items' => __( 'Add or Remove Venue Groups', 'mstw-schedules-scoreboards' ),
				'choose_from_most_used' => __( 'Choose from the most used venue groups', 'mstw-schedules-scoreboards' ),
				'not_found' => __( 'No venue groups found', 'mstw-schedules-scoreboards' ),
				'menu_name'  => __( 'Venue Groups', 'mstw-schedules-scoreboards' ),
			  );
			  
	$args = array( 
			//'label'				=> 'MSTW Scoreboards', //overridden by $labels->name
			'labels'				=> $labels,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> true,
			'show_tagcloud'			=> false,
			//'meta_box_cb'			=> null, provide callback fcn for meta box display
			'show_admin_column'		=> true, //allow automatic creation of taxonomy column in associated post-types table.
			'hierarchical' 			=> false, //behave like tags
			//'update_count_callback'	=> '',
			'query_var' 			=> true, 
			'rewrite' 				=> true,
			'capabilities'			=> array(
											'manage_terms' => 'manage_ss_venues',
											'edit_terms' => 'manage_ss_venues',
											'delete_terms' => 'manage_ss_venues',
											'assign_terms' => 'manage_ss_venues',
											),
			//'sort'					=> null,
			
			'show_tagcloud' 		=> false
		);
		
	register_taxonomy( 'mstw_ss_venue_group', 'mstw_ss_venue', $args );
	register_taxonomy_for_object_type( 'mstw_ss_venue_group', 'mstw_ss_venue' );
	
	global $wp_taxonomies;
	
	//mstw_log_msg( 'in mstw-ss-cpts ...' );
	//if( taxonomy_exists( 'mstw_ss_venue_group' ) ) {
		//$tax = get_taxonomy( 'mstw_ss_venue_group' );
		//mstw_log_msg( $tax->cap );
	//}
	//else {
		//mstw_log_msg( 'mstw_ss_venue_group taxonomy does not exist.' );
	//}

} //End: mstw_ss_register_cpts( )
?>