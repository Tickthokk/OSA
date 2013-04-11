var manage = {
	datatable:null,
	init:function() {
		manage.setup_datatable();
	},
	setup_datatable:function() {
		manage.datatable = $('#log_list').dataTable({
			'bProcessing': true,
			'bServerSide': true,
			'sAjaxSource': '/admin/log/datatable',
			// Bootstrap Capable
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span5'i><'span7'p>>",
			"sPaginationType": "bootstrap",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			},
			"aoColumnDefs": [
		      { "bSortable": false, "aTargets": [ 2 ] } // Disable sorting on text column
		    ],
		    // Column Widths
		    "bAutoWidth": false,
			"aoColumns": [
				{ "sWidth": "6%" }, 
				{ "sWidth": "20%" }, 
				{ "sWidth": "74%" }
			],
			"aaSorting": [[ 0, "desc" ]] // Start by sorting id descending
		});
	}
}

$(manage.init);