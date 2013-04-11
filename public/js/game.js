var game = {
	init:function() {
		// Convert IMG to SVG and colorize
		osa.svg();

		game.achievements_sort_events();
		game.achievements_filter_events();
		game.events();

		
	},
	achievements_sort_events:function() {
		$('.sort-toggle button').click(function() {
			var el = $(this);

			// Function fires before bootstrap's button 'active' class gets added
			var active = el.hasClass('active');

			// How to sort?  order by the opposite and set it
			var order = el.data('order');

			// If it's not active, don't inverse order, keep it the same
			if (active == true)
			{
				if (order == 'asc') order = 'desc';
				else order = 'asc';	
				el.data('order', order);
			}
			
			// Reverse the chevron
			var chevron = el.find('.chevron');

			if (order == 'asc')
				chevron.removeClass('icon-chevron-down').addClass('icon-chevron-up');
			else
				chevron.addClass('icon-chevron-down').removeClass('icon-chevron-up');

			game.achievements_sort(el.data('sort'), order);
		});
	},
	achievements_sort:function(sort_var, order) {
		var achievement_els = $('#achievements .achievement');

		achievement_els.sort(function(a, b) {

			a = $(a).data(sort_var);
			b = $(b).data(sort_var);

			if (a > b) return order == 'asc' ? -1 : 1;
			else if (a < b) return order == 'asc' ? 1 : -1;
			else return 0;
		});

		$('#achievements .span8').append(achievement_els);
	},
	achievements_filter_events:function() {
		$('.achieved-toggle button').click(function() {
			var el = $(this);

			var filter = el.data('filter');

			// If we're filtering on tags
			if (filter != 'tag')
			{
				var was_active = el.hasClass('active');

				// deactivate each button
				$('.achieved-toggle button').removeClass('active');
				
				// Remove the (potential) popover
				$('.achieved-toggle button[data-filter=tag]').popover('destroy');


				// This fires before Bootstrap adds the active class
				// Re add the active class so it can remove itself

				if (was_active)
				{
					el.addClass('active');
					// de-filter
					$('.achievement').show();
				}
				else
				{
					// It wasn't active, so filter
					if (el.data('filter') == 'achieved')
					{
						$('.achievement[data-achieved=yes]').show();
						$('.achievement[data-achieved=]').hide();
					}
					else
					{
						$('.achievement[data-achieved=yes]').hide();
						$('.achievement[data-achieved=]').show();
					}
				}
			}
		});

		$('.achieved-toggle .tag').click(function(event) {
			event.preventDefault();

			var el = $(this);
			var tag = el.data('tag');

			// DeActivate all filter buttons
			$('.achieved-toggle button').removeClass('active');

			// If the tag is actually something
			if (tag != '')
			{
				// Activate the button
				$('.achieved-toggle button[data-filter=tag]').addClass('active');

				// Give it a popover, destroy the old one first
				$('.achieved-toggle button[data-filter=tag]').popover('destroy');
				$('.achieved-toggle button[data-filter=tag]').popover({ 
					placement: 'left', 
					title: null,
					content: tag,
					trigger: 'manual'
				}).popover('show');

				// Go through the achievements and show/hide based on the tags
				$('#achievements .achievement').each(function() {
					var el = $(this);
					var tags = el.data('tags');

					// Assume there's no match
					el.hide();

					for (var i = 0; i < tags.length; i++)
						if (tags[i] == tag)
							el.show();
				});
			}
			else
			{
				// Show all achievements
				$('.achievement').show();
				// Kill the old popover
				$('.achieved-toggle button[data-filter=tag]').popover('destroy');
			}
		});
	},
	achievements_unfilter:function() {


		// $('.achieved-toggle button').click(function() {
		// 	var el = $(this);

		// 	$('#achievements .achievement').removeClass('hide');
		// 	if (el.attr('rel') == 'unachieved')
		// 		$('#achievements .achievement.achieved').addClass('hide');
		// 	else if (el.attr('rel') == 'achieved')
		// 		$('#achievements .achievement.unachieved').addClass('hide');
		// });

		// $('.achieved-toggle .btn[rel=achieved] span').html($('#achievements .achievement.achieved').length);
		// $('.achieved-toggle .btn[rel=unachieved] span').html($('#achievements .achievement.unachieved').length);

	},
	events:function() {
		// Suggest Link
		$('.suggest-link').click(game.suggest_link);
		$('.submit-link').click(game.suggest_link_submit);

		// Unapproved Links
		$('#links .unapproved a').click(game.unapproved_link);
		$('.link-go').click(game.unapproved_link_submit);

		// Flag as Inappropriate
		$('.flag-as-inappropriate').click(game.flag);
		$('.flag-go').click(game.flag_submit);

		// Flag a Link as Inappropriate/Bad
		$('.bad-link').click(game.bad_link);
		$('.flag-link-go').click(game.bad_link_submit);
	},
	// Reload links after flagging or new
	reload_links:function() {
		$.ajax({
			url: '/game/links/' + game_id,
			dataType: 'html',
			type: 'GET',
			success:function(html) {
				$('#links').html(html);
				osa.tooltips();
			}
		});
	},
	suggest_link:function(event) {
		event.preventDefault();
		$('.btn-group.open').removeClass('open');
		$('#game_suggest_link').modal();
	},
	suggest_link_submit:function() {
		// UnModal
		$('#game_suggest_link').modal('hide');

		// Get the link name and URL
		var site = $('#game_suggest_link input[name=site]').val(),
			url = $('#game_suggest_link input[name=url]').val();

		if (site == '' || url == '') 
		{
			osa.error('Site Name and URL are required fields.');
			return false;
		}

		$.ajax({
			url: '/game/links/' + game_id,
			type: 'POST',
			data: {
				site: site,
				url: url
			},
			success:function() {
				osa.alert('Thank you for the link!');

				// Clear the inputs
				$('#game_suggest_link input').val('');

				// Reload the links chunk
				game.reload_links();
			}
		});
	},
	unapproved_link:function(event) {
		event.preventDefault();

		var newWindow = e.ctrlKey || e.button === 1,
			url = $(this).attr('href');

		$('#game_link_go .goto').html(url).data('url', url).data('newWindow', newWindow);
		$('#game_link_go').modal();
	},
	unapproved_link_submit:function() {
		var url = $('#game_link_go .goto').data('url'),
			newWindow = $('#game_link_go .goto').data('newWindow');

		if (newWindow)
		{
			window.open(url);
			$('#game_link_go').modal('hide');
		}
		else
			window.location = url;
	},
	flag:function(event) {
		event.preventDefault();
		$('#flag_as_inappropriate').modal();
	},
	flag_submit:function() {
		// Hide the block
		$('#flag_as_inappropriate').modal('hide');

		// Get the reason
		var reason = $('#flag_as_inappropriate textarea').val();

		if (reason == '')
		{
			osa.error('A reason is required to flag.');
			return false;
		}

		$.ajax({
			url: '/flag/game/' + game_id,
			data: {
				reason: reason
			},
			success:function() {
				osa.alert('Thank you for flagging.', 'A moderator will review your concerns.');

				// Make the cancel button say something else
				$('.flag-go').prev('.btn').html('Okay');

				// Remove the flag-go button so they can't re-submit
				$('.flag-go').remove();

				// Replace the textarea
				$('#flag_as_inappropriate textarea').after('<p>' + reason + '</p>');
				$('#flag_as_inappropriate textarea').remove();
			}
		});
	},
	bad_link:function(event) {
		event.preventDefault();
		// Clear the select
		$('#flag_for_bad_link select option').remove();
		// Reset reason box
		$('#flag_for_bad_link textarea').val('');

		// Populate select
		$('#links a.external-game-link').each(function() {
			var el = $(this);

			$('<option/>', {
				value: el.data('id'),
				title: el.attr('href'),
				html: el.html(),
				data: el.data()
			}).appendTo($('#flag_for_bad_link select'));
		});

		$('#flag_for_bad_link select').unbind('change').change(function() {
			var option = $(this).find(':selected');
			
			if (option.data('flagTally') == 0)
			{
				$('.link_flag_tally_report').hide();
				return false;
			}
			// else
			$('.link_flag_tally_report').show();
			$('.link_flag_unique_users').html(option.data('flagUniqueUsers'));
			$('.link_flag_unique_users_plural')[option.data('flagUniqueUsers') == 1 ? 'hide' : 'show']();
			$('.link_flag_tally').html(option.data('flagTally'));
			$('.link_flag_tally_plural')[option.data('flagTally') == 1 ? 'hide' : 'show']();
			$('.link_flag_solved').html(option.data('flagSolved'));
		}).trigger('change');

		// Modal the box
		$('#flag_for_bad_link').modal();
	},
	bad_link_submit:function() {
		// Hide the block
		$('#flag_for_bad_link').modal('hide');

		// Get the reason
		var reason = $('#flag_for_bad_link textarea').val(),
			link_id = $('#flag_for_bad_link select').val();

		if (reason == '')
		{
			osa.error('A reason is required to flag.');
			return false;
		}

		$.ajax({
			url: '/flag/link/' + link_id,
			data: {
				reason: reason
			},
			success:function() {
				osa.alert('Thank you for flagging.', 'A moderator will review your concerns.');

				game.reload_links();
			}
		});
	}
}

$(game.init);