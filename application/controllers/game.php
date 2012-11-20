<?php

class Game extends OSA_Controller
{
	
	public function index($game_id)
	{
		# Models
		$this->load->model('Games_model', 'games');
		$this->load->model('Achievements_model', 'achievements');
		$this->load->helper(array('markdown'));

		$this->game = $this->games->load($game_id);

		if ( ! $this->game->exists())
			show_error('That game does not exist.');
		
		$this->achievements->game_id = $this->game->id;

		# Header
		$this->set_title($this->game->name);

		# Body
		$this->_data['wiki_slug'] = $this->game->wiki_slug;
		$this->_data['links'] = $this->game->get_links();
		$this->_data['game_id'] = $this->game->id;
		$this->_data['systems'] = $this->game->get_systems();
		
		$this->_data['achievements'] = $this->game->get_achievements($this->user->id);

		$this->_data['js'] = array('game', 'jquery/labelover');
		
		# Page Load
		$this->_load_wrapper('game/view');
	}

	public function create()
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
			$game_id = $this->games->create();

			redirect('/game/' . $game_id . '/' . $this->input->post('slug'), 'location');
		}
	}

	##################
	# AJAX Functions #
	##################

	public function guess_wikislug()
	{

		
	}

	/**
	 * Link
	 *  User suggested a new link
	 */
	public function link($game_id)
	{
		// Method only available via Ajax calls and users who are logged in
		$this->_ajax_only(TRUE);

		// Validate
		$site = $this->input->post('site');
		$url = $this->input->post('url');

		if ( ! filter_var($url, FILTER_VALIDATE_URL))
			$this->_ajax_error('The URL is invalid');
		elseif ( ! $site)
			$this->_ajax_error('The Site name is required.');

		# Models
		$this->load->model('Games_model', 'games');

		$this->game = $this->games->load($game_id);

		$this->game->add_link($this->user->id, $site, $url);
	}

	/**
	 * Links - Get Links
	 */
	public function links($game_id)
	{
		// Method only available via Ajax calls
		$this->_ajax_only();

		# Models
		$this->load->model('Games_model', 'games');

		$this->game = $this->games->load($game_id);

		$this->_data['wiki_slug'] = $this->game->wiki_slug;
		$this->_data['links'] = $this->game->get_links();

		$this->_ajax_return(array(
			'html' => $this->_preview('game/_links')
		));
	}

}