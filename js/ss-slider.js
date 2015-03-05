jQuery(function($) {
	//These are legacy. 
	//Most have been replaced in the click functions below
	var left_indent = parseInt($('.schedule-slider').css('left'));
	var block_width = $('.game-block').outerWidth();
	var slider_width = $('.schedule-slider').outerWidth();
	
	//KEEP THIS ONE
	var left_stop = 0; 
	
	var view_width = $('.ss-slider-area').outerWidth();
	var nbr_blocks = Math.floor( view_width/block_width );

	//10 to acccount for extra width of slider
	var right_stop = -slider_width + nbr_blocks*block_width+10; 
	
	
	$('.ss-slider-right-arrow').click(function(){
		var arrow = $(this);
		
		var slider_id = '#'+arrow.attr('id').replace('ss-slider-right-arrow', 'schedule-slider');
		
		var suff = arrow.attr('id').replace('ss-slider-right-arrow', '');
		
		
		var block_width = $('#schedule-slider' + suff + ' .game-block').outerWidth();

		var slider_width = $('#schedule-slider'+suff).outerWidth();

		var view_width = $('.ss-slider-area'+suff).outerWidth();
		
		var nbr_blocks = Math.floor( view_width/block_width );
		
		//fix for 1 block wide slider
		var slide_distance = ( nbr_blocks <= 1 ) ? block_width :(nbr_blocks-1)*block_width;
		
		//10 to acccount for extra width of slider
		var right_stop = -slider_width + nbr_blocks*block_width+10;

		var left_indent = parseInt($('#schedule-slider'+suff).css('left'));
		
		left_indent = Math.max( left_indent - slide_distance, right_stop );
		
		$(slider_id).css( {'left' : left_indent } );
		
	});
	
	$('.ss-slider-left-arrow').click(function(){
		var arrow = $(this);
		
		var slider_id = '#'+arrow.attr('id').replace('ss-slider-left-arrow', 'schedule-slider');
		
		var suff = arrow.attr('id').replace('ss-slider-left-arrow', '');
		
		var block_width = $('#schedule-slider' + suff + ' .game-block').outerWidth();

		var slider_width = $('#schedule-slider'+suff).outerWidth();

		var view_width = $('.ss-slider-area'+suff).outerWidth();
		
		var nbr_blocks = Math.floor( view_width/block_width );

		//fix for 1 block wide slider
		var slide_distance = ( nbr_blocks <= 1 ) ? block_width :(nbr_blocks-1)*block_width;
		
		//10 to acccount for extra width of slider
		var right_stop = -slider_width + nbr_blocks*block_width + 10;

		var left_indent = parseInt($('#schedule-slider'+suff).css('left'));
	
		left_indent = Math.min( left_indent + slide_distance, left_stop );
		
		$(slider_id).css( {'left' : left_indent } );
			
	});
});