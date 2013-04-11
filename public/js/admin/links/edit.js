var edit = {
	init:function() {
		$('.link-approve').click(function() {
			var el = $(this);
			var link_id = el.closest('tr').data('id');

			$.ajax({
				url: '/admin/links/edit/' + link_id,
				data: {
					approved: 1
				},
				type: 'put',
				success: function() {
					osa.fade_and_destroy(el);
				}
			});
		});

		$('.link-delete').click(function() {
			var tr = $(this).closest('tr');
			var link_id = tr.data('id');

			$.ajax({
				url: '/admin/links/edit/' + link_id,
				type: 'delete',
				success: function() {
					osa.fade_and_destroy(tr.next('tr'));
					osa.fade_and_destroy(tr);
				}
			});
		});
	}
}

$(edit.init);