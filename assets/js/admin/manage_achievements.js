var manage = {
	datatable:null,
	init:function() {
		manage.setup_datatable();
		manage.special_search_events();
	},
	setup_datatable:function() {
		manage.datatable = $('#achievement_list').dataTable({
			'bProcessing': true,
			'bServerSide': true,
			'sAjaxSource': '/admin/achievements/datatable',
			// Bootstrap Capable
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span5'i><'span7'p>>",
			"sPaginationType": "bootstrap",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			},
			"aoColumnDefs": [
		      { "bSortable": false, "aTargets": [ 3 ] } // Disable sorting on actions column
		    ],
		    // Column Widths
		    "bAutoWidth": false,
			"aoColumns": [
				{ "sWidth": "10%" }, 
				{ "sWidth": "35%" }, 
				{ "sWidth": "35%" }, 
				{ "sWidth": "20%" }
			]
		});

		// Live Events
		$("#achievement_list tbody").on("mouseover", 'tr', function() {
			var tr = $(this);
			if (tr.data('events_loaded') == true)
				return;
			
			manage.user_table_events();

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
	user_table_events:function() {
		// Reinitiate tooltips
		$('[rel=tooltip]').tooltip();
	}
}

$(manage.init);