var manage = {
	datatable:null,
	init:function() {
		manage.setup_datatable();
		manage.special_search_events();
	},
	setup_datatable:function() {
		manage.datatable = $('#flag_list').dataTable({
			'bProcessing': true,
			'bServerSide': true,
			//											// Does an "only for" id exist?  Use it.
			'sAjaxSource': '/admin/game_flags/datatable' + (typeof(only_for) !== 'undefined' ? ('/' + only_for) : ''),
			// Bootstrap Capable
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span5'i><'span7'p>>",
			"sPaginationType": "bootstrap",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			},
			"aoColumnDefs": [
		      { "bSortable": false, "aTargets": [ 6 ] } // Disable sorting on actions column
		    ],
		    // Column Widths
		    "bAutoWidth": false,
			"aoColumns": [
				{ "sWidth": "8%" }, 
				{ "sWidth": "20%" }, 
				{ "sWidth": "15%" }, 
				{ "sWidth": "16%" }, 
				{ "sWidth": "13%" }, 
				{ "sWidth": "16%" }, 
				{ "sWidth": "14%" }
			],
			"aaSorting": [[ 3, "desc" ]], // Start by sorting created descending,
			"oSearch": {"sSearch": default_oSearch} // Default search value
		});
	},
	special_search_events:function() {
		$('.special_search a').click(function() {
			var search = $(this).data('search');
			//console.log(search);
			$('.dataTables_filter input').val(search).trigger('keyup');
		});
	}
}

$(manage.init);