var icon_editor = {
	svg:null,
	init:function() {
		$('#icons').change(function() {
			icon_editor.load_image($('#icons option:selected').text());
		}).trigger('change');

		$('#stroke select').change(function() {
			icon_editor.generate_string();
		});

		$('.advanced').click(function(el) {
			$('#advanced').toggleClass('hidden');
		});

		// Main Color Picker
		$('#evol-color-main').blur(function() {
			$('input[placeholder="#fff"]').val($(this).val());
			icon_editor.generate_string();
		});

		$('#evol-color-main').colorpicker();

		$('#evol-color-main').on('change.color', function(event, color) {
			$('#evol-color-main').trigger('blur');
		});

		// Background Color Picker
		$('#evol-color-bg').blur(function() {
			$('input[placeholder="#000"]').val($(this).val());
			$('#colors input').first().val($(this).val());
			icon_editor.generate_string();
		});

		$('#evol-color-bg').colorpicker();

		$('#evol-color-bg').on('change.color', function(event, color) {
			$('#evol-color-bg').trigger('blur');
		});
	},
	load_image:function(path) {
		var img = $('.achievement_icon');
		var imgClass = img.attr('class');

		$.ajax({
			url: '/assets/images/icons/' + path + '.svg',
			type: 'GET',
			dataType: "xml",
			success:function(data) {
				// Parse the SVG portion out of the request
				var svg = $(data).find('svg');

				// Remove any invalid XML tags as per http://validator.w3.org
				svg = svg.removeAttr('xmlns:a');

				// Add the class in
				if (typeof(imgClass) !== 'undefined')
					svg = svg.attr('class', imgClass);

				// Replace image with new SVG
				img.replaceWith(svg);

				icon_editor.svg = svg;

				icon_editor.image_events();
			}
		});
	},
	image_events:function() {
		// Remove all color inputs
		$('#colors li').remove();
		$('#replace-all li').remove();

		// Reset the stroke field
		$('#stroke select').val('');

		// Reset the generated string
		$('#generated input').val('');

		var mainColor = $('#evol-color-main').val();
		var bgColor = $('#evol-color-bg').val();

		// Insert a row for each element
		var i = 0;
		var colors = [], widths = [];

		$('#icon_holder').find('*').each(function(a) {
			var el = $(this);

			// Find out it's current fill, stroke and stroke size
			var currentFill = el.attr('fill'),
				currentStroke = el.attr('stroke'),
				currentStrokeWidth = el.attr('stroke-width');

			if (typeof(currentFill) === 'undefined') currentFill = '';
			if (typeof(currentStroke) === 'undefined') currentStroke = '';
			if (typeof(currentStrokeWidth) === 'undefined') currentStrokeWidth = '';

			colors[colors.length] = currentFill;
			colors[colors.length] = currentStroke;
			widths[widths.length] = currentStrokeWidth;

			if (currentFill == '#fff') currentFill = currentFill + '" value="' + mainColor;
			else if (currentFill == '#000') currentFill = currentFill + '" value="' + bgColor;
			
			if (currentStroke == '#fff') currentStroke = currentStroke + '" value="' + mainColor;
			else if (currentStroke == '#000') currentStroke = currentStroke + '" value="' + bgColor;

			if (i == 0 && bgColor != '')
				currentFill = currentFill + '" value="' + bgColor;

			$('#colors').append('<li rel="' + i + '"><input class="span2 fill" placeholder="' + currentFill + '"> <input class="span2 stroke" placeholder="' + currentStroke + '"> <input class="span2 stroke-width" placeholder="' + currentStrokeWidth + '"> <a href="#" class="locate">Locate</a></li>');
			i++;
		});

		colors = $.array_diff([""], $.array_unique(colors));

		$(colors).each(function(itm, string) {
			$('#replace-all').append('<li><input class="span2" rel="' + string + '"> ' + string + '</li>');
		});
		
		$('#replace-all input').keyup(function() {
			var el = $(this);
			var original = el.attr('rel'),
				replacement = el.val();

			$('input[placeholder=' + original + ']').val(replacement);

			icon_editor.generate_string();
		}).blur(function() {
			$(this).trigger('keyup');
		});

		$('#colors .fill, #colors .stroke, #colors .stroke-width').keyup(function() {
			icon_editor.generate_string();
		})

		// Pulsate effect
		$('#colors li .locate').click(function(event) {
			event.preventDefault();
			var rel = $(this).closest('li').attr('rel');
			$($('svg.achievement_icon').find('*')[rel - 1]).effect('pulsate', { times:1 }, 1000);
		});

		icon_editor.generate_string();
	},
	generate_string:function()
	{
		var string = '';

		// Stroke
		var stroke = $('#stroke select').val();

		string = stroke + ';';

		// Fill & Stroke Colors
		$('#colors li').each(function() {
			var vals = $(this).find('.fill').val();

			string = string + 
				$(this).find('.fill').val() + ':' + 
				$(this).find('.stroke').val() + ':' + 
				$(this).find('.stroke-width').val() + ';';
		});

		$('#generated input').val(string);

		icon_editor.modify_svg(string, $('#icon_holder'));
	},
	modify_svg:function(string, el) {
		var config = string.split(';');
		config.pop(); // Last element is pointless
		var stroke = config.shift();

		// Stroke
		var linecap = null, linejoin = null;

		if (stroke == 'm') {
			linecap = 'square';
			linejoin = 'miter';
		} else if (stroke == 'r') {
			linecap = linejoin = 'round';
		} else if (stroke == 'b') {
			linecap = 'butt';
			linejoin = 'bevel';
		}

		if (linecap !== null)
			el.css({
				'stroke-linecap': linecap,
				'stroke-linejoin': linejoin
			});

		// Fill & Stroke Colors

		var i = 0;
		el.find('*').each(function() {
			var colors = config[i++].split(':'),
				el = $(this);

			var fill = colors[0], 
				stroke = colors[1],
				strokeWidth = colors[2];

			if (strokeWidth < .1) strokeWidth = '';

			el.css({
				'fill': fill,
				'stroke': stroke,
				'stroke-width': strokeWidth
			});
		});
	}
}

$(icon_editor.init);