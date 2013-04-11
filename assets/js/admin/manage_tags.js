var manage = {
	datatable:null,
	init:function() {
		manage.setup_datatable();
		manage.special_search_events();
	},
	setup_datatable:function() {
		manage.datatable = $('#tag_list').dataTable({
			'bProcessing': true,
			'bServerSide': true,
			'sAjaxSource': '/admin/tags/datatable',
			// Bootstrap Capable
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span5'i><'span7'p>>",
			"sPaginationType": "bootstrap",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			},
			"aoColumnDefs": [
		      { "bSortable": false, "aTargets": [ 4 ] } // Disable sorting on actions column
		    ],
		    // Column Widths
		    "bAutoWidth": false,
			"aoColumns": [
				{ "sWidth": "10%" }, 
				{ "sWidth": "30%" }, 
				{ "sWidth": "13%" }, 
				{ "sWidth": "13%" }, 
				{ "sWidth": "34%" }
			],
			"aaSorting": [[ 0, "desc" ]] // Start by sorting id descending
		});

		// Live Events
		$("#tag_list tbody").on("mouseover", 'tr', function() {
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