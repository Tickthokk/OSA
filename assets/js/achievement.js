var achievement = {
	init:function() {
		// Initialize tags
		achievement.init_tags();

		// Initialize Comments
		achievement.init_comments();

		// Misc. Page Functions
		achievement.manage_events();
	},
	manage_events:function() {
		$('#i-did-it').click(achievement.achieve);

		$('.edit_achievement').click(achievement.edit);

		$('.delete_achievement').click(achievement.del);

		// Edit "Save" function
		$('#achievement_editing .save').click(achievement.edit_confirm);

		// Edit "Save" function
		$('#achievement_deletion .yes-delete').click(achievement.delete_confirm);
	},
	init_tags:function() {
		$.fn.tagcloud.defaults = {
			size: {start: 12, end: 24, unit: 'px'},
			color: {start: '#999', end: '#f52'}
		};

		achievement.init_tag_cloud();

		// jQuery/Bootstrap radio 'button' functionality
		$('#tag_vote .btn-group').button();

		// Suggest new tags
		$('.suggest-new-tag').click(function() {
			$('.suggest-new-tag').hide();
			$('#suggest-tag').show();
		});

		// Suggest new tag cancel
		$('#suggest-tag .btn.cancel').click(function() {
			// Clear the input
			$('#suggest-tag input').val('');

			$('#suggest-tag').hide();
			$('.suggest-new-tag').show();
		});

		// Suggest new tag submit
		$('#suggest-tag .btn.submit').click(achievement.suggest_tag);
	},
	init_tag_cloud:function() {
		$('#tags span').tagcloud();

		$('#tags span').click(function(e) {
			achievement.vote_creation($(this), e.pageX, e.pageY);
		});
	},
	suggest_tag:function() {
		var suggested_tag = $('#suggest-tag input').val();

		// Hide the box
		$('#suggest-tag .btn.cancel').click();

		$.ajax({
			url: '/achievement/suggest_tag/' + achievement_id,
			data: {
				tag: suggested_tag
			},
			success:function(json) {
				osa.success(suggested_tag + ' has been added!');

				// Set HTML
				$('#tags').html(json.html);

				// Re-Initialize tags
				achievement.init_tag_cloud();
			}
		});

	},
	achieve:function() {
		$.ajax({
			url: '/achievement/achieve/' + achievement_id,
			success:function(json) {
				osa.success('You did it!!');

				$('#achievers').html(json.achievers);

				$('#i-did-it').addClass('hidden');
				$('#you-did-it').removeClass('hidden');
			}
		});
	},
	edit:function() {
		$('#achievement_editing').modal();
		$('#achievement_editing').data('original', $('#achievement_editing textarea').val());
	},
	edit_confirm:function() {
		// Hide the modal box
		$('#achievement_editing').modal('hide');

		// Get Data
		var description = $('#achievement_editing textarea').val(),
			original = $('#achievement_editing').data('original'),
			failsafe = $('.description').html();

			console.log(failsafe);

		// If the description is the same, don't do anything else.
		if (description == original)
			return;

		// Update element description
		$('#achievement_editing').data('original', description);

		// Put a spinner in the content spot
		var content_el = $('.description');
		osa.spinner(content_el);

		// Server call to update database
		$.ajax({
			url: '/achievement/edit_description/' + achievement_id,
			context: content_el,
			data: {
				description: description
			},
			success:function(json) {
				$(this).html(json.description);
				osa.alert('The description has been updated.');
			},
			error:function(a, b) {
				$.ajaxSettings.error(a, b); // parent::__construct
				$(this).html(failsafe);
			}
		});
	},
	del:function() {
		$('#achievement_deletion').modal();
		$('#achievement_deletion input').val('');
	},
	delete_confirm:function() {
		// Hide the modal box
		$('#achievement_deletion').modal('hide');

		// Get the value of the input box
		var captcha = $('#achievement_deletion input').val();

		// They need to type "DELETE"
		if (captcha !== 'DELETE')
		{
			osa.error('Please type "DELETE" to delete.');
			return false;
		}

		// Server call to update database
		$.ajax({
			url: '/achievement/delete/' + achievement_id,
			success:function() {
				window.location = '/game/' + game_id;
			}
		});
	},
	/**
	 * Voting
	 */
	vote_is_setup: false,
	vote_setup:function() {
		achievement.vote_is_setup = true;
		// Give buttons events
		$('#tag_vote .keep').click(function() {
			achievement.vote_up($(this));
		});
		$('#tag_vote .drop').click(function() {
			achievement.vote_down($(this));
		});
		$('#tag_vote .flag').click(function() {
			achievement.vote_flag($(this));
		});

		$('#tag_vote .disabled').unbind('click');
	},
	vote_up:function(el)
	{
		// Pre-cursory check if it's already been voted up by the user
		// [Note, PHP will double check this]
		if (el.hasClass('active')) 
			return;

		var btngroup = el.parent('.btn-group');

		var id = btngroup.data('id');
		var name = btngroup.data('el').parent('span').data('name');

		// Activate Keep
		achievement.vote_button_style('add', btngroup, 'keep');

		// Deactivate others
		achievement.vote_button_style('remove', btngroup, 'drop');
		achievement.vote_button_style('remove', btngroup, 'flag');

		// Set the element data for this vote
		btngroup.data('el').parent('span').data('userVote', 1);

		// Ajax Call
		$.ajax({
			url: '/achievement/vote/up/' + id,
			success:function() {
				osa.success('You approve of ' + name + '.');
			}
		});
	},
	vote_down:function(el)
	{
		// Pre-cursory check if it's already been voted up by the user
		// [Note, PHP will double check this]
		if (el.hasClass('active')) 
			return;

		var btngroup = el.parent('.btn-group');

		var id = btngroup.data('id');
		var name = btngroup.data('el').parent('span').data('name');

		// Activate Drop
		achievement.vote_button_style('add', btngroup, 'drop');

		// Deactivate others
		achievement.vote_button_style('remove', btngroup, 'keep');
		achievement.vote_button_style('remove', btngroup, 'flag');

		// Set the element data for this vote
		btngroup.data('el').parent('span').data('userVote', -1);

		// Ajax Call
		$.ajax({
			url: '/achievement/vote/down/' + id,
			success:function() {
				osa.info('You disapprove of ' + name + '.');
			}
		});
	},
	vote_flag:function(el)
	{
		// Pre-cursory check if it's already been voted up by the user
		// [Note, PHP will double check this]
		if (el.hasClass('active')) 
			return;

		var btngroup = el.parent('.btn-group');

		var id = btngroup.data('id');
		var name = btngroup.data('el').parent('span').data('name');

		// Activate Flag
		achievement.vote_button_style('add', btngroup, 'flag');

		// Deactivate others
		achievement.vote_button_style('remove', btngroup, 'keep');
		achievement.vote_button_style('remove', btngroup, 'drop');

		// Set the element data for this vote
		btngroup.data('el').parent('span').data('userVote', -2);

		// Ajax Call
		$.ajax({
			url: '/achievement/vote/flag/' + id,
			success:function() {
				osa.alert('You have flagged ' + name + '.', 'An administrator will review.');
			}
		});
	},
	vote_button_style:function(way, el, cls) {
		el
			.children('.' + cls)[way + 'Class']('active')[way + 'Class']('btn-inverse')
			.children('i')[way + 'Class']('icon-white');
	},
	vote_creation:function(el, xcoord, ycoord)
	{
		var data = el.data();

		if ( ! achievement.vote_is_setup)
			achievement.vote_setup();

		if (el.children('.btn-group').length > 0)
			return;

		// Clone [DEEP] original button group
		// Place it inside the element
		var btngroup = $('#tag_vote .btn-group').clone(true)
		btngroup.appendTo($('body'));

        btngroup.css('left', xcoord - 14);
        btngroup.css('top', ycoord - (btngroup.height() / 2));
        
		// Give it a data-id attribute based on data.id
		btngroup.data('id', data.id);
		btngroup.data('el', el);

		// Highlight based on data.userVote
		if (data.userVote !== "")
		{
			var use = 'drop'; // default

			if (data.userVote == 1)
				use = 'keep';
			else if (data.userVote == -2)
				use = 'flag';

			achievement.vote_button_style('add', btngroup, use);
		}

		// remove flag option based on data.adminApproval
		if (data.adminApproval === 1)
			btngroup.children('.flag').remove();

		// Hover Out event
		btngroup.mouseleave(function() {
			achievement.vote_destroy($(this));
		});
	},
	vote_destroy:function(el)
	{
		osa.fade_and_destroy(el);
	},
	/**
	 * Comments
	 */
	init_comments:function() {
		// Markdown explanation
		$('#markdown_popup').popover({
			placement: 'top',
			content:function() {
				return $('#markup_example').html();
			}
		}).click(function(e) {
			e.preventDefault(); // Prevent click action
		});

		// Comment Functions
		$('.reply_cancel').click(achievement.reply_cancel);

		// Comment "Save" function
		$('#comment_editing .save').click(achievement.edit_comment_confirm);

		// Comment "Delete" function
		$('#comment_deletion .yes-delete').click(achievement.delete_comment_confirm);

		achievement.more_comment_events();
	},
	load_more_comments:function() {
		var btn = $(this),
			top_id = $('#comments').data('top-comment-id'),
			current_comment_count = $('#comments').data('current-comment-count');
		var prev = btn.prev();

		// Remove the button
		$(this).remove();

		// Add a loading spinner
		osa.spinner(prev, 'after', 'center');
		
		$.ajax({
			url: '/achievement/more_comments/' + achievement_id,
			context: prev,
			data: {
				top_id: top_id,
				offset: current_comment_count
			},
			success:function(json) {
				// Remove the spinner and add the content
				$(this).next('.spinner_wrap').remove();
				$(this).after(json.html);

				// Add 10 to the comment count
				// 	If we really only added 8, it won't matter because there won't be a "next time" for this integer to be utilized
				$('#comments').data('current-comment-count', current_comment_count + 10);

				achievement.more_comment_events();
			}
		});
	},
	more_comment_events:function() {
		// Load More Comments button, re-initialize
		$('.load-more-comments').click(achievement.load_more_comments);

		$('.edit_comment').click(achievement.edit_comment);
		$('.delete_comment').click(achievement.delete_comment);
		$('.reply').click(achievement.reply);
	},
	edit_comment:function() {
		// Prepare the modal box
		var el = $(this).closest('.user_comment');
		$('#comment_editing').data('id', el.data('id'));
		$('#comment_editing textarea').val(el.data('comment'));

		// Modal the box
		$('#comment_editing').modal();
		$('#comment_editing').on('show', function() {
			$(this).children('textarea').focus();
		});
	},
	edit_comment_confirm:function() {
		// Hide the modal box
		$('#comment_editing').modal('hide');

		// Get Data
		var id = $('#comment_editing').data('id'),
			comment = $('#comment_editing textarea').val();

		// Get Element
		var el = $('.user_comment[data-id=' + id + ']');
		var original_comment = el.data('comment');

		// If the comment is the same, don't do anything else.
		if (comment == original_comment)
			return;

		// Update element comment
		el.data('comment', comment);

		// Put a spinner in the content spot
		var content_el = el.children('blockquote');
		osa.spinner(content_el);

		// Server call to update database
		$.ajax({
			url: '/achievement/edit_comment/' + id,
			context: content_el,
			data: {
				comment: comment
			},
			success:function(json) {
				$(this).html(json.comment);
				osa.alert('Your comment has been updated.');
			},
			error:function(a, b) {
				$.ajaxSettings.error(a, b);
				$(this).html(original_comment);
			}
		});
	},
	delete_comment:function() {
		// Prepare the modal box
		var el = $(this).closest('.user_comment');
		$('#comment_deletion').data('el', el);

		// Modal the box
		$('#comment_deletion').modal();
	},
	delete_comment_confirm:function() {
		// Hide the modal box
		$('#comment_deletion').modal('hide');

		// Get the element
		var comment_box = $('#comment_deletion').data('el');
		var id = comment_box.data('id');

		// Server call to update database
		$.ajax({
			url: '/achievement/delete_comment/' + id,
			context: comment_box,
			success:function() {
				osa.fade_and_destroy($(this));
				osa.alert('Your comment has been deleted.');
			}
		});
	},
	reply:function() {
		var el = $(this).closest('.user_comment');
		var id = el.data('id'),
			uname = el.find('.username').html();

		// Save el for later use
		$('.reply_cancel').data('el', el).data('prev', el.prev());

		// Single out their comment
		el.addClass('alert-info').addClass('alert');
		$('#comments').append(el);

		// Show the "reply to" box, set the ID, and the name
		$('#in-reply-to').show();
		$('#in-reply-to input:hidden').val(id);
		$('#in-reply-to input:text').val(uname);

		// hide reply buttons
		$('.reply').hide();

		// Focus in on the textarea
		$('textarea[name=comment]').focus();
		// And scroll to it
		osa.scroll_to($('textarea[name=comment]'));
	},
	reply_cancel:function() {
		var el = $(this).data('el'),
			prev = $(this).data('prev');

		// De-single out their comment
		el.removeClass('alert').removeClass('alert-info');
		prev.after(el);

		// Hide the "reply to" box, and un-set ID/Name
		$('#in-reply-to').hide();
		$('#in-reply-to input:hidden').val('');
		$('#in-reply-to input:text').val('');

		// show reply buttons
		$('.reply').show();
	}
}

$(achievement.init);