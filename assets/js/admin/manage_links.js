var manage = {
	datatable:null,
	init:function() {
		manage.setup_datatable();
		manage.special_search_events();
	},
	setup_datatable:function() {
		manage.datatable = $('#link_list').dataTable({
			'bProcessing': true,
			'bServerSide': true,
			'sAjaxSource': '/admin/links/datatable',
			// Bootstrap Capable
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span5'i><'span7'p>>",
			"sPaginationType": "bootstrap",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			},
			"aoColumnDefs": [
		      { "bSortable": false, "aTargets": [ 5 ] } // Disable sorting on actions column
		    ],
		    // Column Widths
		    "bAutoWidth": false,
			"aoColumns": [
				{ "sWidth": "8%" }, 
				{ "sWidth": "20%" }, 
				{ "sWidth": "15%" }, 
				{ "sWidth": "18%" }, 
				{ "sWidth": "18%" }, 
				{ "sWidth": "15%" }
			],
			"aaSorting": [[ 0, "desc" ]], // Start by sorting created descending,
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