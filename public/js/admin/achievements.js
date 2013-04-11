var achievements = {
	init:function() {
		$('#search').typeahead({
			source: search_typeahead
		});
	}
}

$(achievements.init);