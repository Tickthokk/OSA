var games = {
	init:function() {
		$('#search').typeahead({
			source: search_typeahead
		});
	}
}

$(games.init);