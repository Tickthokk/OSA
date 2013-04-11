var edit = {
	init:function() {
		$('#name').keyup(function() {
			var name = $(this).val().toLowerCase();

			// First Letter
			var first_letter = name[0];

			if ( ! first_letter.match(/[a-zA-Z]/))
				first_letter = '';
			
			$('#first_letter').val(first_letter);
			$('#first_letter_display').html(first_letter);

			// Slug
			var slug = name.replace(/\W/g, '-');
			while (slug.match(/--/))
				slug = slug.replace(/--/, '-');
			
			$('#slug').val(slug);
			$('#slug_display').html(slug);
		});
	}
}

$(edit.init);