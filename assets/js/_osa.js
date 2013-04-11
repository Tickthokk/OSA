// _osa.js
// Consider this the "global javascript functions" file
var osa = {
	init:function() {
		// Ajax Defaults
		$.ajaxSetup({
			type: 'POST',
			dataType: "json",
			error:function(jqXHR, textStatus, errorThrown)
			{
				osa.error(errorThrown, jqXHR.responseText);
			}
		});

		osa.tooltips();
	},
	tooltips:function() {
		if ($('[rel=tooltip]').length > 0)
			$('[rel=tooltip]').tooltip();
	},
	// Turn "A Text String - Like This" into "a-text-string-like-this"
	slug:function(text) {
		return text
			.toLowerCase()
			.replace(/[^\w ]+/g, '')
			.replace(/ +/g, '-');
	},
	// Fade an element out, and then destroy it.
	fade_and_destroy:function(el)
	{
		el.fadeOut(300, function() { $(this).remove(); })
	},
	// Display an alert.
	alert:function(title, message, type)
	{
		// Clone the template
		var msgEl = $('#message_template').clone();
		// Remove its ID
		msgEl.attr('id', null);

		// Add the coloring by defining the alert type
		if (typeof(type) !== 'undefined' && type != '')
			msgEl.addClass('alert-' + type);

		// Stop hiding it though CSS and switch it to the fade
		msgEl.removeClass('hidden');

		// Apply the text.  Remove the element if unnecessary.
		if (typeof(title) === 'undefined' || title == '')
			msgEl.children('.alert-heading').remove();
		else
			msgEl.children('.alert-heading').html(title);

		if (typeof(message) === 'undefined' || message == '')
			msgEl.children('p').remove();
		else
			msgEl.children('p').html(message);

		// If the only element left is the "Close" button, don't bother showing a message
		if (msgEl.children().length == 1)
		{
			msgEl.remove();
			return false;
		}

		// Put the message in the box
		msgEl.prependTo($('#alerts .message_box'));
		// Fade it in
		msgEl.fadeIn('fast', 'swing');

		// Auto-destroy the message after 5 seconds
		setTimeout(function() {
			msgEl.alert('close');
		}, 5000);
	},
	// Quick-Access for the Alert function
	error:function(title, message)
	{
		osa.alert(title, message, 'error');
	},
	success:function(title, message)
	{
		osa.alert(title, message, 'success')
	},
	info:function(title, message)
	{
		osa.alert(title, message, 'info')
	},
	// AJAX Spinner
	/**
	 * @param el >> element >> effected element
	 * @param placement >> string >> replace (default) || before || after
	 * @param dark >> boolean >> dark version or not
	 * @param version >> string >> pac (default) || something else
	 * Function will place "pac-loader.gif" inside the element
	 */
	spinner:function(el, placement, alignment) {
		var version = ''; // random from list
		var spinner = '<img src = "/assets/images/loaders/' + osa.spinner_list[Math.floor(Math.random() * osa.spinner_list.length)] + '.gif" class = "spinner" alt = "Loading...">';

		if (typeof(alignment) !== 'undefined') {
			if (alignment == 'center')
				spinner = '<div class = "center spinner_wrap">' + spinner + '</div>';
		}

		if (typeof(placement) === 'undefined')
			el.html(spinner);
		else if (placement == 'after')
			el.after(spinner);
		else
			el.before(spinner);
	},
	spinner_list:[
		'agumon', 'bass', 'bomberman', 'caveman',
		'fighter', 'mariohammer', 'megaman',
		'samus', 'sonic', 'toejam-and-earl', 'tonberry'
	],
	// Scroll To
	scroll_to:function(el) {
		$('html, body').animate({scrollTop: el.offset().top}, 2000);
	},
	// IMG to SVG Replacer
	svg:function() {
		// Special thanks to: http://stackoverflow.com/a/11978996/286467
		$('img.svg').each(function() {
			var img = $(this);
			
			$.ajax({
				url: img.attr('src'),
				type: 'GET',
				dataType: "xml",
				success:function(data) {

					// Parse the SVG portion out of the request
					var svg = $(data).find('svg');
					
					// Add the ID in
					if (typeof(img.attr('id')) !== 'undefined')
						svg = svg.attr('id', img.attr('id'));

					// Add the class in
					if (typeof(img.attr('class')) !== 'undefined')
						svg = svg.attr('class', img.attr('class') + ' replaced-svg');

					// Remove any invalid XML tags as per http://validator.w3.org
					svg = svg.removeAttr('xmlns:a');

					// Now fill in the colors

					// Main SVG element's fill is the BG
					svg.attr('fill', img.data('bg'));

					// Loop through each level.  If it has a current fill or stroke defined
					// Replace those if they match #fff ($color) or #000 ($bg)
					var i = 0;
					svg.find('*').each(function() {
						var el = $(this);
						var fill = el.attr('fill'),
							stroke = el.attr('stroke');

						if (typeof(fill) !== 'undefined')
						{
							if (fill == '#fff')
								el.css('fill', img.data('color'));
							else if (fill == '#000')
								el.css('fill', img.data('bg'));
						}

						if (typeof(stroke) !== 'undefined')
						{
							if (stroke == '#fff')
								el.css('stroke', img.data('color'));
							else if (stroke == '#000')
								el.css('stroke', img.data('bg'));
						}
					});

					// Replace image with new SVG
					img.replaceWith(svg);
				}
			});
		});
	}
}

$(osa.init);

// Extend Jquery with some functions
$.extend({
    array_unique: function(anArray) {
       var result = [];
       $.each(anArray, function(i,v){
           if ($.inArray(v, result) == -1) result.push(v);
       });
       return result;
    },
    array_diff: function(array1, array2) {
    	var difference = [];
    	$.grep(array2, function(el) {
			if ($.inArray(el, array1) == -1) difference.push(el);
		});
		return difference;
    }
});