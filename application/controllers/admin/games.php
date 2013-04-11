<?php

class Games extends OSA_Controller
{

	public function __construct()
	{
		parent::__construct();

		# Only available to admin users
		if ( ! $this->user->is_admin())
			show_404();

		$this->theme = 'admin';
	}

	public function index()
	{
		$this->_data['left_nav'] = 'ga';
		$this->_data['js'][] = 'admin/manage_games';
		$this->_load_wrapper('admin/games');
	}

	public function edit($game_id = 0)
	{
		// Load Helpers/Library/Models
		$this->load->helper('form_helper');
		$this->load->library('form_validation');
		$this->load->model('Games_model', 'games');

		$game = $this->games->load($game_id);

		if ( ! $game->exists())
			show_error('That game does not exist.');
		
		if ($this->input->post('cancel') !== FALSE)
		{
			// Cancel
			$this->session->set_flashdata('warning', 'No changes made to ' . $game->name);

			// Redirect to Games Page
			redirect('/admin/games');
		}

		$this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|alpha_punctuation|required');
		$this->form_validation->set_rules('wiki', 'Wiki Slug', 'trim|xss_clean|alpha_punctuation|required');
		$this->form_validation->set_rules('slug', 'Slug', 'trim|xss_clean|alpha_punctuation|required');
		$this->form_validation->set_rules('system[]', 'Systems', 'required');

		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// Validator Errors?
			$this->_data['error'] = validation_errors('<li>','</li>');

			// All systems
			$systems = $this->games->get_all_systems();

			// Systems this game applies to
			// If it was posted, save what was gotten last time, not this time
			$game_system_keys = array();
			if ($this->input->post())
			{
				foreach ($this->input->post('system') as $gs)
					$game_system_keys[] = $gs;
			}
			else
			{
				$game_systems = $game->get_systems();
				foreach ($game_systems as $gs)
					$game_system_keys[] = $gs['id'];
			}

			foreach ($systems as &$system_ref)
				$system_ref['checkbox'] = form_checkbox('system[]', $system_ref['id'], in_array($system_ref['id'], $game_system_keys));

			// Form Inputs
			$this->_data['name_input'] = form_input('name', $this->input->post('name') ?: $game->name);
			$this->_data['slug_input'] = form_input('slug', $this->input->post('slug') ?: $game->slug);
			$this->_data['wiki_input'] = form_input('wiki', $this->input->post('wiki') ?: $game->wiki_slug);
			$this->_data['systems'] = $systems;

			// Page Vars
			$this->_data['game_name'] = $game->name;
			$this->_data['game_id'] = $game->id;

			// Page Prep
			$this->_data['js'][] = 'admin/manage_game_flags';
			$this->_data['left_nav'] = 'ga';
			$this->_load_wrapper('admin/games/edit');
		}
		else
		{

			// Success - Update the game information
			$game->enable_set('name', 'slug', 'wiki_slug', 'first_letter');
			$game->name = $this->input->post('name');
			$game->slug = $this->input->post('slug');
			$game->wiki_slug = $this->input->post('wiki');
			$game->set_systems($this->input->post('system'));
			// Update the first letter based on the name

			$first_letter = strtolower(substr($game->name, 0, 1));
			if ( ! preg_match('/[a-z]/', $first_letter))
				$first_letter = NULL;
			$game->first_letter = $first_letter;

			// Log their action
			$this->log->add($this->user->username . ' (' . $this->user->id . ') edited the Game "' . $game->name . '" (' . $game->id . ')');

			// Success Message
			$this->session->set_flashdata('success', 'You changed ' . $game->name);

			// Redirect to Games Page
			redirect('/admin/games');
		}
	}

	/*****************
		AJAX CALLS
	******************/

	public function datatable()
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$games = $this->admin->game_list($this->input->get());

		// Special things for specific columns
		foreach ($games['aaData'] as &$row)
		{
			// Fields to remove and set to _data
			foreach (array('flagged', 'achievement_tally', 'slug') as $var)
			{
				$this->_data[$var] = $row[$var];
				unset($row[$var]);
			}
			$this->_data['game_id'] = $row['id'];
			$this->_data['game_name'] = $row['name'];
			
			$row['actions'] = $this->_preview('admin/games/_datatable_actions');
			$row['name'] = $this->_preview('admin/games/_datatable_name');

			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}
		
		$this->_ajax_return($games);
	}

}