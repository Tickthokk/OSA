var manage = {
	datatable:null,
	init:function() {
		manage.setup_datatable();
		manage.special_search_events();
	},
	setup_datatable:function() {
		manage.datatable = $('#icon_list').dataTable({
			'bProcessing': true,
			'bServerSide': true,
			'sAjaxSource': '/admin/icons/datatable',
			// Bootstrap Capable
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span5'i><'span7'p>>",
			"sPaginationType": "bootstrap",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			},
			"aoColumnDefs": [
		      { "bSortable": false, "aTargets": [ 0 ] } // Disable sorting on actions and icons column
		    ],
		    // Column Widths
		    "bAutoWidth": false,
			"aoColumns": [
				{ "sWidth": "80px" }, 
				{ "sWidth": "40%" }, 
				{ "sWidth": "40%" },
			],
			"aaSorting": [[ 1, "asc" ]] // Start by sorting id descending
		});

		// Live Events
		$("#icon_list tbody").on("mouseover", 'tr', function() {
			var tr = $(this);
			if (tr.data('events_loaded') == true)
				return;
			
			manage.user_table_events(tr);

			tr.data('events_loaded', true);
		});
	},
	special_search_events:function() {
		$('.special_search a').click(function() {
			var search = $(this).data('search');
			//console.log(search);
			$('.dataTables_filter input').val(search).trigger('keyup');
		});
	},
	user_table_events:function(rowEl) {
		// Reinitiate tooltips
		rowEl.first('[rel=tooltip]').tooltip();

		rowEl.first('.add_tag').click(function(event) {
			console.log('hi');
		});
	}
}

$(manage.init);