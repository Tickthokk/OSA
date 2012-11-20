var admin = {
	popover_width: 400,
	init:function() {
		$('.admin_mode').click(function(event) {
			event.preventDefault();
			// Fire the enable or disable event
			admin[(admin.enabled ? 'dis' : 'en') + 'able']();
		});

		// For the sake of testing // TODO REMOVEME
		$('.admin_mode').trigger('click');
	},
	enabled:false,
	drawn:false,
	draw:function() {
		// Draw could have already been fired, so test for that
		if (admin.drawn == true)
			return;

		// Draw game link events
		admin.game_links();

		// Draw Achievements events
		admin.game_achievements_list();





		// Set variable so it won't draw again
		admin.drawn = true;
	},
	enable:function() {
		// Attempt to draw the needed items
		admin.draw();

		// Show any hidden events, in the case of previously disabled
		$('.admin_event').show();

		// Set variable so next time disable will fire
		admin.enabled = true;
	},
	disable:function() {
		// Hide the admin events
		$('.admin_event').hide();

		// Set variable so next time enable will fire
		admin.enabled = false;
	},
	/**
	 * Game Links
	 */
	game_links:function() {
		// Add icons to each link
		$('#links li').each(function(i, el) {
			el = $(el);
			// Ignore official Wikipedia link
			if (el.hasClass('wikipedia'))
				return;

			// Add "Get Info" icon 
			el.append('<i class="icon-info-sign admin_event pointer" title="Info/Flags"></i>');

			// Add Remove icon
			el.append('<i class="icon-trash admin_event pointer" title="Delete"></i>');

			// If it has been flagged as bad
			if (el.find('.icon-thumbs-down').length > 0)
			{
				// Add the remove flags
				el.append('<i class="icon-thumbs-up admin_event pointer" title="Clear Flags"></i>');
			}

			// Just the unapproved links:
			if (el.hasClass('unapproved'))
			{
				// Add Approve icon
				el.append('<i class="icon-ok admin_event pointer" title="Approve"></i>');
			}
		});

		// Events

		// More Info
		$('#links i.icon-info-sign').click(function() {
			var el = $(this);
			var id = el.parent().find('a').data('id');
			
			admin.link_more_info(id);
		});

		// Delete
		$('#links i.icon-trash').click(function() {
			var el = $(this);
			var id = el.parent().find('a').data('id');
			
			admin.link_delete(id, el);
		});

		// UnFlag
		$('#links i.icon-thumbs-up').click(function() {
			var el = $(this);
			var id = el.parent().find('a').data('id');
			
			admin.link_clear_flags(id, el);
		});

		// Approve!
		$('#links i.icon-ok').click(function() {
			var el = $(this);
			var id = $(this).parent().find('a').data('id');
			
			admin.link_approve(id, el);
		});
	},
	link_more_info:function(id, el) {
		$.ajax({
			url: '/admin/game/link/info/' + id,
			dataType: 'json'
		}).done(function(json) {

			var popover = $('<div title="' + json.site + '"></div>');
			
			popover
				.append('<p><strong>Link ID</strong> ' + json.id + '</p>')
				.append('<p><strong>URL</strong> ' + json.url + '</p>')
				.append('<p><strong>Submitted On</strong> ' + json.submitted + '</p>')
				.append('<p><strong>Submitter</strong> <em>'  + json.submitted_by + '</em> ' + json.submitted_by_name + '</p>');

			if (json.approved_by != null)
				popover
					.append('<p><strong>Approved On</strong>' + json.approved + '</p>')
					.append('<p><strong>Approver</strong> <em>'  + json.approved_by + '</em> ' + json.approved_by_name + '</p>');
			
			if (json.flags.length > 0)
				for (i = 0; i < json.flags.length; i++) {
					var flag = json.flags[i];
					var username = '<em>' + flag.submitter + '</em> ' + flag.username
					if (flag.submitter == null)
						username = 'Anonymous';

					popover
						.append('<hr>')
						.append('<p><strong>Reported By</strong> ' + username + ' On ' + flag.created + '</p>')
						.append('<p><strong>Reason:</strong> ' + flag.reason);
				}
			
			popover.dialog({
				modal: true,
        		width: admin.popover_width
			});
		});
	},
	link_delete:function(id, el) {
		var name = el.parent().find('a').html();

		var popover = $('<div title="Deleting Link"></div>');

		popover.data('id', id).data('el', el);

		popover.append('<p>You are about to delete the <strong>' + name + '</strong> link.');

		popover.dialog({
			modal: true,
			width: admin.popover_width,
			buttons: {
				Delete:function() {
					$(this).dialog('close');
					admin.link_really_delete($(this).data('id'), $(this).data('el'));
				},
				Cancel:function() {
					$(this).dialog('close');
				}
			}
		});
	},
	link_really_delete:function(id, el) {
		$.ajax({
			url: '/admin/game/link/delete/' + id
		});
		// Kill the whole row
		osa.fade_and_destroy(el.parent());
		// Success!
		osa.success('Link Deleted');
	},
	link_approve:function(id, el) {
		var name = el.parent().find('a').html();
		
		var popover = $('<div title="Approve Link"></div>');

		popover.data('id', id).data('el', el);

		popover.append('<p>You are about to approve the <strong>' + name + '</strong> link.');

		popover.dialog({
			modal: true,
			width: admin.popover_width,
			buttons: {
				Approve:function() {
					$(this).dialog('close');
					admin.link_really_approve($(this).data('id'), $(this).data('el'));
				},
				Cancel:function() {
					$(this).dialog('close');
				}
			}
		});
	},
	link_really_approve:function(id, el) {
		$.ajax({
			url: '/admin/game/link/approve/' + id
		});
		// Make the Star filled in, remove the tooltip
		el.parent().find('.star').removeClass('icon-star-empty').addClass('icon-star').attr('rel', '').attr('data-original-title', '');
		// Destroy the checkmark icon
		osa.fade_and_destroy(el);
		// Success!
		osa.success('Link Approved');
	},
	link_clear_flags:function(id, el) {
		var name = el.parent().find('a').html();
		
		var popover = $('<div title="Clear Flags"></div>');

		popover.data('id', id).data('el', el);

		popover.append('<p>You are about to clear flags for the <strong>' + name + '</strong> link.');

		popover.dialog({
			modal: true,
			width: admin.popover_width,
			buttons: {
				"Clear Flags":function() {
					$(this).dialog('close');
					admin.link_really_clear_flags($(this).data('id'), $(this).data('el'));
				},
				Cancel:function() {
					$(this).dialog('close');
				}
			}
		});
	},
	link_really_clear_flags:function(id, el) {
		$.ajax({
			url: '/admin/game/link/clear_flags/' + id
		});
		// Destroy the thumbs down icon
		osa.fade_and_destroy(el.parent().find('.icon-thumbs-down'));
		// Destroy the thumbs up icon
		osa.fade_and_destroy(el);
		// Success!
		osa.success('Link Flags Cleared');
	},
	/**
	 * Game Achievements List
	 */
	game_achievements_list:function() {

		$('#achievements .achievement').each(function(i, el) {
			el = $(el);

			// Add Remove icon
			el.append('<i class="icon-trash admin_event pointer" title="Delete"></i>');
		});

		// Events

		// Delete
		$('#achievements .achievement i.icon-trash').click(function() {
			var el = $(this);
			var id = el.parent().data('id');
			
			admin.achievement_delete(id, el);
		});
	},
	achievement_delete:function(id, el) {
		var name = el.parent().find('.title a').html();

		var popover = $('<div title="Deleting Achievement"></div>');

		popover.data('id', id).data('el', el);

		popover.append('<p>You are about to delete the <strong>' + name + '</strong> achievement.');

		popover.dialog({
			modal: true,
			width: admin.popover_width,
			buttons: {
				Delete:function() {
					$(this).dialog('close');
					admin.achievement_really_delete($(this).data('id'), $(this).data('el'));
				},
				Cancel:function() {
					$(this).dialog('close');
				}
			}
		});
	},
	achievement_really_delete:function(id, el) {
		$.ajax({
			url: '/admin/game/achievement/delete/' + id
		});
		// Kill the whole row
		osa.fade_and_destroy(el.parent());
		// Success!
		osa.success('Achievement Deleted');
	}
}

$(admin.init);