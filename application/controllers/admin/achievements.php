<?php

class Achievements extends OSA_Controller
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
		$this->_data['left_nav'] = 'ac';
		$this->_data['js'][] = 'admin/manage_achievements';
		$this->_load_wrapper('admin/achievements');
	}

	public function edit($achievement_id = 0)
	{
		// Load Helpers/Library/Models
		$this->load->helper('form_helper');
		$this->load->library('form_validation');
		$this->load->model('Games_model', 'games');
		$this->load->model('Achievements_model', 'achievements');

		$achievement = $this->achievements->load($achievement_id);

		if ( ! $achievement->exists())
			show_error('That achievement does not exist.');

		$game = $this->games->load($achievement->game_id);
		
		if ($this->input->post('cancel') !== FALSE)
		{
			// Cancel
			$this->session->set_flashdata('warning', 'No changes made to ' . $achievement->name);

			// Redirect to Games Page
			redirect('/admin/achievements');
		}

		$this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|alpha_punctuation|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|xss_clean|alpha_punctuation|required');
		
		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// Validator Errors?
			$this->_data['error'] = validation_errors('<li>','</li>');

			// All systems
			$systems = array('NULL' => '-- N/A --');
			foreach ($this->games->get_all_systems() as $s)
				$systems[$s['id']] = $s['name'];

			// All icons
			// TODO cache this?
			$this->load->helper('directory');
			$map = directory_map(FCPATH . 'assets/images/icons/');
			$icons = array();
			/*foreach ($map as $folder => $files)
				foreach ($files as $file)
					$icons[] = $folder . '/' . $file;*/
			
			// Form Inputs
			$this->_data['game_name_input'] = form_input('game_name', $game->name, 'disabled');
			$this->_data['name_input'] = form_input('name', $this->input->post('name') ?: $achievement->name);
			$this->_data['description_input'] = form_textarea('description', $this->input->post('description') ?: $achievement->description);
			$this->_data['system_exclusive_input'] = form_dropdown('system_exclusive', $systems, $this->input->post('system_exclusive') ?: $achievement->system_exclusive);
			$this->_data['icon_input'] = form_dropdown('icon', $icons, $this->input->post('icon') ?: $achievement->icon);
			
			// Page Vars
			$this->_data['achievement_name'] = $achievement->name;
			$this->_data['achievement_id'] = $achievement->id;

			// Page Prep
			$this->_data['js'][] = 'admin/manage_achievement_flags';
			$this->_data['js'][] = 'admin/achievements_edit';
			$this->_data['js'][] = 'icon_chooser';
			$this->_data['left_nav'] = 'ac';
			$this->_load_wrapper('admin/achievements/edit');
		}
		else
		{
			// Success - Update the game information
			/*$game->enable_set('name', 'slug', 'wiki_slug', 'first_letter');
			$game->name = $this->input->post('name');
			$game->slug = $this->input->post('slug');
			$game->wiki_slug = $this->input->post('wiki');
			$game->set_systems($this->input->post('system'));*/

			// Log their action
			$this->log->add($this->user->username . ' (' . $this->user->id . ') edited the Achievement "' . $achievement->name . '" (' . $achievement->id . ')');

			// Success Message
			$this->session->set_flashdata('success', 'You changed ' . $achievement->name);

			// Redirect to Users Page
			redirect('/admin/achievements');
		}
	}

	/*****************
		AJAX CALLS
	******************/

	public function datatable($only_for = NULL)
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$flags = $this->admin->achievements_list($this->input->get(), $only_for);

		// Special things for specific columns
		foreach ($flags['aaData'] as &$row)
		{
			$this->_data['slug'] = $row['game_slug'];
			
			// Fields to remove and set to _data
			foreach (array('flagged', 'game_id', 'game_slug', 'achievers') as $var)
			{
				$this->_data[$var] = $row[$var];
				unset($row[$var]);
			}
			
			$this->_data['achievement_id'] = $row['id'];
			$row['actions'] = $this->_preview('admin/achievements/_datatable_actions');

			// We can re-use the games table name
			$this->_data['game_name'] = $row['game_name'];
			$row['game_name'] = $this->_preview('admin/games/_datatable_name');

			// We can re-use the games table name
			$this->_data['achievement_name'] = $row['achievement_name'];
			$row['achievement_name'] = $this->_preview('admin/achievements/_datatable_name');

			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}
		
		$this->_ajax_return($flags);
	}

}