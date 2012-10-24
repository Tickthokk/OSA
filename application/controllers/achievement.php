<?php

class Achievement extends OSA_Controller
{

	/**
	 * achievement/view
	 */
	public function view($achievement_id = 0)
	{
		# Helpers, Library, Models
		$this->load->model('Games_model', 'games');
		$this->load->model('Achievements_model', 'achievements');
		$this->load->helper(array('form', 'markdown', 'time_elapsed', 'text'));

		$this->achievement = $this->achievements->load($achievement_id);

		if ( ! $this->achievement->exists())
			show_error('That achievement does not exist.');

		$this->game = $this->games->load($this->achievement->gameId);

		if ( ! $this->game->exists())
			show_error('That game does not exist.');

		$game_data = $this->game->get_all();
		$game_data['id'] = $this->achievement->gameId;
		
		$achievement_data = $this->achievement->get_all();
		$achievement_data['systemExclusive'] = $this->achievement->system_exclusive();
		$achievement_data['username'] = $this->achievement->username;
		
		list($comments, $total_comments) = $this->achievement->get_comments();

		$this->_data['comments'] = $comments;
		$this->_data['total_comments'] = $total_comments;

		$this->_data['tags'] = $this->achievement->get_tags($this->user->id);
		$this->_data['achievers'] = $this->achievement->get_achievers();
		
		$this->_data['comments_already_shown'] = 0;
		$this->_data['top_comment_id'] = $total_comments ? $comments[0]['id'] : 0;

		$this->_data['game'] = $game_data;
		$this->_data['achievement'] = $achievement_data;
		$this->_data['achievement_id'] = $achievement_id;
		$this->_data['user_has_achieved'] = $this->achievement->has_achieved($this->user->id);
		$this->_data['old_comment'] = $this->session->flashdata('old_comment');

		# Page Data
		$this->set_title('Achievement | ' . $this->achievement->name);
		
		$this->_data['css'] = array(
			'thirdparty/jquery.tagit',
			'thirdparty/tagit.ui-zendesk',
		);

		$this->_data['js'] = array(
			'jquery/tagcloud.min',
			'achievement'
		);
		
		# Page Load
		$this->_load_wrapper('achievement/view');
	}

	/**
	 * achievement/create
	 */
	public function create($game_id)
	{
		if (empty($game_id))
			show_404();

		$this->_users_only();

		# Helpers, Library, Models
		$this->load->helper(array('form', 'url', 'markdown'));
		$this->load->library('form_validation');
		$this->load->model('Games_model', 'games');
		$this->load->model('Achievements_model', 'achievements');

		$this->achievements->set_game_id($game_id);

		# Form Validation Rules
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('tags', 'Tags', 'required');
		
		# Validate!
		if ($this->form_validation->run() == FALSE)
		{
			# Page Data
			$this->set_title('Add an Achievement');
			$css = array(
				'thirdparty/jquery.tagit',
				'thirdparty/tagit.ui-zendesk'
			);
			$js = array(
				'jquery/tag-it', 
				'create'
			);

			# Get List of icons
			# TODO cache this (somehow)
			$this->load->helper('directory');
			$map = directory_map(FCPATH . 'assets/images/icons/');

			$this->game = $this->games->load($game_id);

			$game_name = $this->game->name;

			$default_tags = $this->achievements->get_default_tags();

			$systems = $this->game->get_systems();

			$this->set_more_data(compact(
				'css', 'js', 'game_name', 'game_id', 'default_tags', 'systems', 'map'
			));
			
			# Page Load
			$this->_load_wrapper('achievement/create');
		}
		else
		{
			// Validation Succeeded
			// Create the achievement
			$achievement_id = $this->achievements->create($this->user->id, $game_id, $this->input->post());

			// Add Tags to the achievement
			$this->achievement = $this->achievements->load($achievement_id);

			$this->achievement->initial_tags($this->user->id, explode(',', strtolower($this->input->post('tags'))));

			redirect('/achievement/' . $achievement_id, 'location');
		}
	}

	#######################
	# POST Only Functions #
	#######################

	public function comment($achievement_id)
	{
		# Only allow logged users
		$this->_users_only();

		# Helpers, Library, Models
		$this->load->model('Achievements_model', 'achievements');
		$this->load->helper(array('form', 'markdown', 'time_elapsed'));
		$this->load->library('form_validation');

		$this->achievement = $this->achievements->load($achievement_id);

		# When did the person last comment?
		$last_comment = $this->session->userdata('last_comment');
		// debug 
		$last_comment = NULL;

		# Validate the comment
		$this->form_validation->set_rules('comment', 'Comment', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			# Form didn't validate (aka empty comment), Fail

			# Set error message
			# TODO move to a language file
			$this->session->set_flashdata('error', 'You cannot post empty comments.');

			# Keep their old comment
			$this->session->set_flashdata('old_comment', $comment);
		}
		elseif ($last_comment && $last_comment + $this->config->item('time_between_posts') >= time())
		{
			# If the last comment time exists
			# And the last comment, plus `x` seconds is greater than now, Fail

			# Set error message
			# TODO move to a language file
			$this->session->set_flashdata('error', 'You cannot post that often, sorry!  Try again in ' . $this->config->item('time_between_posts') . ' seconds.');

			# Keep their old comment
			$this->session->set_flashdata('old_comment', $comment);
		}
		else
		{
			# Otherwise, Success!

			# Add to the database
			$this->achievement->add_comment($this->user->id, $this->input->post('comment'));

			# Set their "last comment"
			$this->session->set_userdata('last_comment', time());

			# Success message
			# TODO move to a language file
			$this->session->set_flashdata('success', 'Your comment has been posted!');
		}

		# Regardless of what happens...
		# Redirect back to achievement page
		redirect('/achievement/' . $achievement_id);
	}

	##################
	# AJAX Functions #
	##################

	public function vote($way, $achievement_tag_id)
	{
		// Method only available via Ajax calls and users who are logged in
		$this->_ajax_only(TRUE);

		$approval = 1; // Default: Approve!
		if ($way == 'down') $approval = -1;
		elseif ($way == 'flag') $approval = -2;

		$this->load->model('Achievements_model', 'achievements');
		
		$achievement_id = $this->achievements->find_from_tag_id($achievement_tag_id);
		
		$this->achievement = $this->achievements->load($achievement_id);

		$this->achievement->vote($this->user->id, $achievement_tag_id, $approval);
	}

	public function achieve($achievement_id)
	{
		// Method only available via Ajax calls and users who are logged in
		$this->_ajax_only(TRUE);

		# Helpers, Library, Models
		$this->load->model('Achievements_model', 'achievements');
		$this->load->helper(array('time_elapsed'));

		$this->achievement = $this->achievements->load($achievement_id);

		$this->achievement->achieve($this->user->id);

		$this->session->set_userdata('tally', $this->session->userdata('tally') + 1);

		$this->_data['achievers'] = $this->achievement->get_achievers();

		$this->_ajax_return(array(
			'achievers' => $this->_preview('achievement/_achievers')
		));
	}

	public function edit_comment($comment_id)
	{
		// Method only available via Ajax calls and users who are logged in
		$this->_ajax_only(TRUE);

		# Helpers, Library, Models
		$this->load->model('Achievements_model', 'achievements');
		$this->load->helper('markdown');
		
		$achievement_id = $this->achievements->find_from_comment_id($comment_id);

		$this->achievement = $this->achievements->load($achievement_id);

		$comment = $this->input->post('comment');

		if ($this->achievement->edit_comment($this->user->id, $comment_id, $comment))
			$this->_ajax_return(array(
				'comment' => markdown($comment)
			));
		else
			$this->_ajax_error('This is not your comment.');
	}

	public function delete_comment($comment_id)
	{
		// Method only available via Ajax calls and users who are logged in
		$this->_ajax_only(TRUE);

		# Helpers, Library, Models
		$this->load->model('Achievements_model', 'achievements');
		
		$achievement_id = $this->achievements->find_from_comment_id($comment_id);

		$this->achievement = $this->achievements->load($achievement_id);

		if ( ! $this->achievement->delete_comment($this->user->id, $comment_id))
			$this->_ajax_error('This is not your comment.');
	}

	public function more_comments($achievement_id)
	{
		// Method only available via Ajax calls
		$this->_ajax_only();

		# Helpers, Library, Models
		$this->load->model('Achievements_model', 'achievements');
		$this->load->helper('markdown');

		$this->achievement = $this->achievements->load($achievement_id);

		$top_id = $this->input->post('top_id');
		$offset = $this->input->post('offset');
		
		list($comments, $total_comments) = $this->achievement->get_comments($top_id, $offset, 10);

		$this->_data['comments'] = $comments;
		$this->_data['total_comments'] = $total_comments;
		$this->_data['comments_already_shown'] = $offset;
		
		$this->_ajax_return(array(
			'html' => $this->_preview('achievement/_comments')
		));
	}

	public function edit_description($achievement_id)
	{
		// Method only available via Ajax calls and users who are logged in
		$this->_ajax_only(TRUE);

		# Helpers, Library, Models
		$this->load->model('Achievements_model', 'achievements');
		$this->load->helper('markdown');

		$this->achievement = $this->achievements->load($achievement_id);

		$description = $this->input->post('description');

		# Does the achievement belong to them?
		if ($this->achievement->userId == $this->user->id) {

			# Too many achievers prevents editing
			if ($this->achievement->get_achiever_count() < $this->config->item('modify_if_achievers_under'))
			{
				# Update the database description
				$this->achievement->set_more(array(
					'description' => $description,
					'modified' => 'NOW()'
				));

				$this->_ajax_return(array(
					'description' => markdown($description)
				));
			}
			else
				// TODO language file
				$this->_ajax_error('You no longer own this achievement.  Too many achievers.');
		}
		else
			// TODO language file
			$this->_ajax_error('You did not create this achievement.');
	}

	public function delete($achievement_id)
	{
		// Method only available via Ajax calls and users who are logged in
		$this->_ajax_only(TRUE);

		# Helpers, Library, Models
		$this->load->model('Achievements_model', 'achievements');

		$this->achievement = $this->achievements->load($achievement_id);

		# Does the achievement belong to them?
		if ($this->achievement->userId == $this->user->id) {

			# Too many achievers prevents deleting
			if ($this->achievement->get_achiever_count() < $this->config->item('modify_if_achievers_under'))
			{
				// Pre-populate achievement name.  After it's deleted, we won't be able to pull it.
				$achievement_name = $this->achievement->name;

				# Trigger deletion failsafe
				$this->achievement->allow_deletion();
				# Delete the achievement.
				$this->achievement->delete();

				# Success message
				# TODO move to a language file
				$this->session->set_flashdata('success', $achievement_name . ' has been deleted.');

				$this->_ajax_return(array(
					'deleted' => TRUE
				));
			}
			else
				// TODO language file
				$this->_ajax_error('You no longer own this achievement.  Too many achievers.');
		}
		else
			// TODO language file
			$this->_ajax_error('You did not create this achievement.');
	}

	public function suggest_tag($achievement_id)
	{
		// Method only available via Ajax calls and users who are logged in
		$this->_ajax_only(TRUE);

		# Helpers, Library, Models
		$this->load->model('Achievements_model', 'achievements');

		$this->achievement = $this->achievements->load($achievement_id);

		$suggested_tag = strtolower($this->input->post('tag'));

		if ($this->achievement->initial_tags($this->user->id, array($suggested_tag)))
		{
			$this->_data['tags'] = $this->achievement->get_tags($this->user->id);
			
			$this->_ajax_return(array(
				'html' => $this->_preview('achievement/_tags')
			));
		}
		else
			$this->_ajax_error('That tag already exists.');
	}

}