var links = {
	init:function() {
		$('#search').typeahead({
			source: search_typeahead
		});

		$('#hide_urls').click(function() {
			$('td.url').toggle();
		});
	}
}

$(links.init);