var create = {
	first_load: true,
	init:function() {
		// Init Events
		create.events();

		// Defaults
		$('input[name=c-or-p]:checked').trigger('click');

		create.first_load = false;
	},
	events:function() {
		// Console/Portable Radio Button
		$('input[name=c-or-p]').click(function(el) {
			var id = $(this).attr('id');
			
			// Keep whatever's checked at the start of things, but only the first time
			if (create.first_load === false)
				$('.system_type :checkbox:checked').removeAttr('checked');
			
			$('.system_type').hide();
			$('.system_type.' + id).show();
		});
		
		// Name Blur
		$('#name').blur(function() {
			// Set the Slug to the slugged value of Name
			$('#slug').val(osa.slug($(this).val()));
		});
	}
}

$(create.init);