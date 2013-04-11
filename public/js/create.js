var create = {
	init:function() {
		// Init Events
		create.markdown();
		create.icon_events();
		create.tag_events();

		// Defaults
		//$('input[name=c-or-p]:checked').trigger('click');

		
	},
	tag_events:function() {
		$('#tag-chooser').tagit({
			fieldName: 'tags',
			allowSpaces: true,
			tagLimit: 12
		});

		$('.tag-suggestions .tag').click(function() {

			$('#tag-chooser').tagit('createTag', $(this).data('tag'));
		});
	},
	icon_events:function() {
		osa.svg();

		$('#icon-color').colorpicker({
			displayIndicator: false
		}).on('change.color', function(event, color) {
			create.colorize_icon();
		});

		$('#icon-bg').colorpicker({
			displayIndicator: false
		}).on('change.color', function(event, color) {
			create.colorize_icon();
		});

		$('.clear-color').click(function() {
			$('#icon-color').colorpicker('val', '');
			create.colorize_icon('color');
		});

		$('.clear-bg').click(function() {
			$('#icon-bg').colorpicker('val', '');
			create.colorize_icon('bg');
		});

		$('#select_icon').click(function() {
			$('#icon-chooser').show();
			$('.hide-on-icon-chooser').hide();
		});

		create.icon_chooser_events();
	},
	colorize_icon:function(force) {
		var color = $('#icon-color').colorpicker('val'),
			bg = $('#icon-bg').colorpicker('val'),
			svg = $('#main-icon');

		osa.svg_colorize(svg, color, bg, force);

		//svg.replaceWith(new_svg);
	},
	icon_chooser_events:function() {
		// Closing the chooser
		$('#icon-chooser .close').click(function() {
			$('#icon-chooser').hide();
			$('.hide-on-icon-chooser').show();
		});

		// Clicking on a Tag
		$('#icon-tags .icon_tag').click(function() {
			var tag = $(this).data('name');
			$('#icon-search').val('Tag:' + tag);
			$('#icon-search + .btn').trigger('click');
		});

		// Input box, Enter/Return == Search
		$('#icon-search').keypress(function(event) {
			if (event.keyCode == 13)
			{
				event.preventDefault();
				$('#icon-search + .btn').trigger('click');
			}
		});

		// Random "Search"
		$('#random-icon-search').click(function() {
			// Hide tags box
			$('#icon-tags').hide();

			// Show the icons
			$('#icons-found').show();

			// Load up random icons
			create.random_icons();
		});
		
		// Clear Search
		$('#clear-icon-search').click(function() {
			// Hide the icons
			$('#icons-found').hide();

			// Show the tags
			$('#icon-tags').show();

			// Clear the search box
			$('#icon-search').val('').focus();
		});

		// Search
		$('#icon-search + .btn').click(function() {
			// Hide tags box
			$('#icon-tags').hide();

			// Show the icons
			$('#icons-found').show();

			create.icon_search($('#icon-search').val());
		});
	},
	random_icons:function() {
		// reset found icons
		$('#icons-found').html('');

		// insert random icons
		for (var i = 0; i < 10; i++)
		{
			var icon = icon_list[Math.floor(Math.random()*icon_list.length)];

			$('<img>', {
				'src': '/img/icons/' + icon.filename + '.svg',
				'class': 'svg no-replace found-icon',
				'data-filename': icon.filename,
				'title': icon.filename,
				click:function() {
					create.icon_selected($(this).data('filename'));
				}
			}).appendTo('#icons-found');
		}

		// Found Icons events
		$('.found-icon').tooltip({ 'placement': 'bottom' });
	},
	icon_search:function(str) {
		// reset found icons
		$('#icons-found').html('');

		var tag_search = false;

		if (str.substring(0, 4) == 'Tag:')
		{
			tag_search = true;
			str = str.substring(4);
		}

		if (str.length < 3)
		{
			osa.error('Search requires 3 characters');
			$('#icon-tags').show();
			$('#icons-found').hide();
			return;
		}

		for (var i = 0; i < icon_list.length; i++)
		{
			var icon = icon_list[i];

			var include_icon = false;

			if (tag_search == true)
			{
				for (var j = 0; j < icon.tags.length; j++)
				{
					if (icon.tags[j] == str)
					{
						include_icon = true;
						break;
					}
				}
			}
			else
			{
				var regex = new RegExp(str, 'i');

				if (icon.filename.match(regex))
					include_icon = true;
			}
			
			if (include_icon == true)
				$('<img>', {
					'src': '/img/icons/' + icon.filename + '.svg',
					'class': 'svg no-replace found-icon',
					'data-filename': icon.filename,
					'title': icon.filename,
					click:function() {
						create.icon_selected($(this).data('filename'));
					}
				}).appendTo('#icons-found');
		}

		// Found Icons events
		$('.found-icon').tooltip({ 'placement': 'bottom' });
	},
	icon_selected:function(filename) {
		// Close the chooser
		$('#icon-chooser .close').trigger('click');
		$('#clear-icon-search').trigger('click');

		// Replace the icon with the img tag
		$('#main-icon').replaceWith('<img src="/img/icons/' + filename + '.svg" class="svg" id="main-icon" data-color="' + $('#icon-color').colorpicker('val') + '" data-bg="' + $('#icon-bg').colorpicker('val') + '">');

		// Set the Input field that will get submitted
		$('#icon').val(filename);

		// Convert img to svg
		osa.svg();
	},
	markdown:function() {
		$('#markdown_popup').popover({
			html: 'true',
			width: '600px',
			content:function() {
				return $('#markup_example').html();
			}
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

		$('input[name=system_exclusive_yes]').change(function(e) {
			$('#system_exclusive')[$(this).is(':checked') ? 'show' : 'hide']();
		}).trigger('change');

		$('#icon-select').change(function() {
			$('.achievement-icon').attr('src', '/assets/images/icons/' + $(this).val());
		}).keyup(function() {
			$(this).change();
		}).change();
	}
}

$(create.init);