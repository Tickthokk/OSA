var users = {
	init:function() {
		$('.user_control').click(function() {
			var user_id = $(this).closest('tr').data('id');

			// Reset and set user ID
			$('#control_user .modal-body').html('');
			$('#control_user').data('user_id', user_id);

			$.ajax({
				type: 'get',
				dataType: 'html',
				url: '/admin/users/control',
				data: { user_id: user_id },
				success:function(html) {
					// Set HTML and modal the box
					$('#control_user .modal-body').html(html);
					$('#control_user').modal();
				}
			})
		});

		$('#control_user .btn-primary').click(users.control_save);

		$('#search').typeahead({
			source: search_typeahead
		});
	},
	control_save:function() {
		$.ajax({
			type: 'post',
			url: '/admin/users/control',
			data: {
				user_id: $('#control_user').data('user_id'),
				banned: $('#control_user select[name=banned]').val(),
				ban_reason: $('#control_user textarea[name=ban_reason]').val(),
				activated: $('#control_user select[name=activated]').val()
			},
			success:function() {
				osa.success('User Updated');
			}
		});
		$('#control_user .close').trigger('click');
	}
}

$(users.init);