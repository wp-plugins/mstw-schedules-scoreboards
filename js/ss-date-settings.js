//Set up the JQuery date picker to work with the game date input field
jQuery(document).ready(function($){
	$('#game_date').datepicker({
        dateFormat : 'yy-mm-dd'
    });
});