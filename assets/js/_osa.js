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
	}
}

$(osa.init);