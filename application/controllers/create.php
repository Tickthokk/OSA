<?php

class Create extends OSA_Controller
{

	# TODO drop this file, move the creation into the appropriate controllers

	public function __construct()
	{
		parent::__construct();

		# Creation only available to logged users
		if ( ! $this->user->is_logged)
			redirect('/user/login');
	}
	
	public function main()
	{
		show_404();
	}

	/**
	 * create/game
	 * Show a form to create a game entry
	 */
	public function game()
	{
		# Helpers, Library, Models
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('Games_model', 'games');

		# Form Validation Rules
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('slug', 'Slug', 'required|is_unique[games.slug]');
		$this->form_validation->set_rules('c-or-p', 'Console/Portable', 'required');
		$this->form_validation->set_rules('system[]', 'Systems', 'required');

		# Validate!
		if ($this->form_validation->run() == FALSE)
		{
			# System List
			$systems = $this->games->get_developer_systems();

			# Fix what was posted, because we'll need this later
			$post_system = (array) $this->input->post('system');
			foreach ($systems as $s)
				if ( ! isset($post_system[$s['id']]))
					$post_system[$s['id']] = array();

			# Page Data
			$this->set_title('Add a Game');
			$js = array('create');
			$this->set_more_data(compact(
				'js', 'systems', 'post_system'
			));
			
			# Page Load
			$this->_load_wrapper('create/game');
		}
		else
		{
			// Validation Succeeded
			// Create the game [uses post data]
			$gameId = $this->games->create();

			redirect('/game/' . $gameId . '/' . $this->input->post('slug'), 'location');
		}
	}

	/**
	 * create/achievement
	 * Show a form to create an achievement
	 * @param integer $game_id >> Achievements are created attached to a game
	 */
	public function achievement($game_id = 0)
	{
		if (empty($game_id))
			show_404();
		
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
			$game = $this->games->load($game_id);
			$game_name = $game->name;

			$default_tags = $this->achievements->get_default_tags();

			$this->set_more_data(compact(
				'css', 'js', 'game_name', 'game_id', 'default_tags'
			));
			
			# Page Load
			$this->_load_wrapper('create/achievement');
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

}