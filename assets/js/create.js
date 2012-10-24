var create = {
	first_load: true,
	init:function() {
		// Init Events
		create.game();
		create.achievement();
		create.events();

		// Defaults
		$('input[name=c-or-p]:checked').trigger('click');

		create.first_load = false;
	},
	// Both/All functions
	events:function() {
		$('#markdown_popup').popover({
			content:function() {
				return $('#markup_example').html();
			}
		}).click(function(e) {
			e.preventDefault(); // Prevent click action
		});
	},
	game:function() {
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
	},
	achievement:function() {
		$('#tags').tagit({
			'itemName': 'tags',
			'allowSpaces': true,
			'placeholderText': 'add your tag',
			'availableTags': default_tag_names
		});

		$('#default_tags .tagit-choice').click(function() {
			var text = $(this).children('.tagit-label:first').html();
			$('#tags').tagit('createTag', text);
		});

		$('input[name=systemExclusiveYes]').change(function(e) {
			$('#systemExclusive')[$(this).is(':checked') ? 'show' : 'hide']();
		}).trigger('change');

		$('#icon-select').change(function() {
			$('.achievement-icon').attr('src', '/assets/images/icons/' + $(this).val());
		}).keyup(function() {
			$(this).change();
		}).change();

	}
}

$(create.init);