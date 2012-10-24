var game = {
	init:function() {
		// OverText/LabelOver
		$('label').labelOver('over-apply');

		/*$('#achievements [rel=popover]').popover({
			title: 'How To Achieve',
			placement: 'bottom',
			content:function() {
				return $(this).next('.description').html();
			}
		});*/

		$('.achieved-toggle button').click(function() {
			var el = $(this);

			$('#achievements .achievement').removeClass('hide');
			if (el.attr('rel') == 'unachieved')
				$('#achievements .achievement.achieved').addClass('hide');
			else if (el.attr('rel') == 'achieved')
				$('#achievements .achievement.unachieved').addClass('hide');
		});

		$('.achieved-toggle .btn[rel=achieved] span').html($('#achievements .achievement.achieved').length);
		$('.achieved-toggle .btn[rel=unachieved] span').html($('#achievements .achievement.unachieved').length);
		
		game.events();
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
			success:function(json) {
				$('#links').html(json.html);
				osa.tooltips();
			}
		});
	},
	suggest_link:function(e) {
		e.preventDefault();
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
			url: '/game/link/' + game_id,
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
	unapproved_link:function(e) {
		e.preventDefault();

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
	flag:function() {
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
	bad_link:function() {
		// Clear the select
		$('#flag_for_bad_link select option').remove();
		// Reset reason box
		$('#flag_for_bad_link textarea').val('');

		// Populate select
		$('#links a.external-game-link').each(function() {
			var el = $(this);
			$('#flag_for_bad_link select').append('<option value = "' + el.data('id') + '" title = "' + el.attr('href') + '">' + el.html() + '</option>');
		});

		// Modal the box
		$('#flag_for_bad_link').modal();
	},
	bad_link_submit:function() {
		// Hide the block
		$('#flag_for_bad_link').modal('hide');

		// Get the reason
		var reason = $('#flag_for_bad_link textarea').val(),
			linkId = $('#flag_for_bad_link select').val();

		if (reason == '')
		{
			osa.error('A reason is required to flag.');
			return false;
		}

		$.ajax({
			url: '/flag/gamelink/' + linkId,
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