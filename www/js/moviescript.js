$(document).ready(function(){
	
	// Caching the movieName textbox:
	var movieName = $('#title');
	
	// Defining a placeholder text:
	//movieName.defaultText('Type a Move Title');
	
	
	// Using jQuery UI's autocomplete widget:
	movieName.autocomplete({
		minLength	: 5,
		source		: 'movieInfo.php',
		select		: function(e, ui) { 
			$.getJSON("/ymdb/movieDetails.php?term=" + ui.item.id, function (data) {
				$('#imdb').val(data.imdb);
			});
		}
	});
	
});