var flags = {
	init:function() {
		$('.flag_control').click(function() {
			var flag_id = $(this).closest('tr').data('id');


		});

		$('.flag_control').click(function() {
			var flag_id = $(this).closest('tr').data('id');

			// Reset and set flag ID
			$('#control_flag .modal-body').html('');
			$('#control_flag').data('flag_id', flag_id);

			$.ajax({
				type: 'get',
				dataType: 'html',
				url: '/admin/flags/control',
				data: { flag_id: flag_id },
				success:function(html) {
					// Set HTML and modal the box
					$('#control_flag .modal-body').html(html);
					$('#control_flag').modal();
				}
			})
		});

		$('#control_flag .btn-primary').click(flags.control_save);
	},
	control_save:function() {
		$.ajax({
			type: 'post',
			url: '/admin/flags/control',
			data: {
				flag_id: $('#control_flag').data('flag_id'),
				admin_id: $('#control_flag select[name=admin_id]').val(),
				reason: $('#control_flag textarea[name=reason]').val()
			},
			success:function() {
				osa.success('Flag Updated');
			}
		});
		$('#control_flag .close').trigger('click');
	}
}

$(flags.init);