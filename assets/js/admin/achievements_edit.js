var achievements_edit = {
	init:function() {
		$('#icon_chooser').click(function(event) {
			event.preventDefault();

			icon_chooser.init();
		});
	}
}

$(achievements_edit.init);