jQuery(function($) {
	$('.sbt-next').click(function(){
		var arrow = $(this);
		//alert( "arrow.attr('id') = " + arrow.attr('id') );
		
		var slider_id = '#'+arrow.attr('id').replace('sbt-next', 'schedule-ticker');
		var suff = arrow.attr('id').replace('sbt-next', '');
		
		//alert( 'Right Arrow: ' + 'slider_id: ' + slider_id  + ' suff: ' + suff );
		
		var block_width = $( '.sbt-list-item' ).outerWidth( true );

		var view_width = $('#sbt-ticker-content'+suff).outerWidth();
		
		//alert( 'block_width: ' + block_width  + ' view_width: ' + view_width );
		
		var nbr_games = $('li.sbt-list-item').length;
		
		var viewable_games = Math.ceil( view_width/block_width );;
		
		//alert( 'nbr_games: ' + nbr_games  + ' viewable_games: ' + viewable_games );
		
		if ( nbr_games < viewable_games ) {
			alert( 'We don\'t do nuthin\' ' );
		}
		else {
			var curr_left_pos = parseInt($('#sbt-ticker-content'+suff + ' ul').css('left'));
			
			var right_stop = -(nbr_games*block_width - view_width ) + 4;
		
			//alert( ' view_width: ' + view_width + ' nbr_games*block_width: ' + nbr_games*block_width );
			
			//alert( ' curr_left_pos: ' + curr_left_pos + ' right_stop: ' + right_stop );
			
		
			new_left_pos = Math.max( curr_left_pos - view_width, right_stop );
			
			//alert( 'next arrow new_left_pos: ' + new_left_pos );
			
			//$('.schedule-slider').css( {'left' : new_left_pos } );
			$('#sbt-ticker-content'+suff + ' ul').css( {'left' : new_left_pos } );
		}
	});
	
	$('.sbt-prev').click(function(){
		var arrow = $(this);
		
		var slider_id = '#'+arrow.attr('id').replace('sbt-prev', 'schedule-ticker');
		
		var suff = arrow.attr('id').replace('sbt-prev', '');
		
		//alert( 'Prev Arrow: ' + 'slider_id: ' + slider_id  + ' suff: ' + suff );
		
		var block_width = $( '.sbt-list-item' ).outerWidth( true );

		var view_width = $('#sbt-ticker-content'+suff).outerWidth();
		
		//alert( 'block_width: ' + block_width  + ' view_width: ' + view_width );
		
		var nbr_games = $('li.sbt-list-item').length;
		
		var viewable_games = Math.ceil( view_width/block_width );;
		
		//alert( 'nbr_games: ' + nbr_games  + ' viewable_games: ' + viewable_games );
	
		if ( nbr_games > viewable_games ) {
			var curr_left_pos = parseInt($('#sbt-ticker-content'+suff + ' ul').css('left'));
			
			var left_stop = 0;
		
			//alert( ' view_width: ' + view_width + ' nbr_games*block_width: ' + nbr_games*block_width );
			
			//alert( ' curr_left_pos: ' + curr_left_pos + ' left_stop: ' + left_stop );
			
			new_left_pos = Math.min( curr_left_pos + view_width, left_stop );
			
			//alert( 'next arrow new_left_pos: ' + new_left_pos );
			
			//$('.schedule-slider').css( {'left' : new_left_pos } );
			$('#sbt-ticker-content'+suff + ' ul').css( {'left' : new_left_pos } );
		}	
	});
});