<?php

class Links extends OSA_Controller
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
		$this->_data['search'] = $this->input->get('search');
		$this->_data['left_nav'] = 'li';
		$this->_data['js'][] = 'admin/manage_links';
		$this->_load_wrapper('admin/links');
	}

	public function edit($link_id)
	{
		if ( ! $link_id)
			show_404();

		// Load the model
		$this->load->helper('form_helper');
		$this->load->library('form_validation');
		$this->load->model('Links_model', 'links');

		$link = $this->links->load($link_id);

		// Has the form been posted?
		if ($this->input->post())
		{
			// They clicked Cancel
			if ($this->input->post('cancel') !== FALSE)
			{
				// Cancel and redirect
				$this->session->set_flashdata('warning', 'No action will be taken on the ' . $link->site . ' link');

				redirect('/admin/links');
			}
			// They submitted
			else if ($this->input->post('submit') !== FALSE || $this->input->post('approve') !== FALSE)
			{
				// Success
				$verbiage = 'changed';
				if ($this->input->post('approve') !== FALSE)
				{
					$verbiage = 'approved';
					// Approved!
					$link->enable_set('approved', 'approved_by');
					$link->approved = 'NOW()';
					$link->approved_by = $this->user->id;
				}
				
				$link->enable_set('site', 'url');
				$link->site = $this->input->post('site');
				$link->url = $this->input->post('url');

				// Log their action
				$this->log->add($this->user->username . ' (' . $this->user->id . ') ' . $verbiage . ' the ' . $link->site . ' link');

				// Success Message
				$this->session->set_flashdata('success', 'You ' . $verbiage . ' the ' . $link->site . ' link');

				// Redirect to Tags Page
				redirect('/admin/links');
			}
		}

		// Load the games model to get the name
		$this->load->model('Games_model', 'games');
		$game = $this->games->load($link->game_id);

		// Vars
		$this->_data['link_id'] = $link->id;
		$this->_data['site'] = $link->site;
		$this->_data['approved'] = $link->approved;
		$this->_data['game_input'] = form_input('game', $game->name, 'disabled');
		$this->_data['site_input'] = form_input('site', $this->input->post('site') ?: $link->site);
		$this->_data['url_input'] = form_input('url', $this->input->post('url') ?: $link->url);
		$this->_data['flags'] = $link->get_flags();
		
		$this->_data['left_nav'] = 'li';
		$this->_data['js'][] = 'admin/manage_link_flags';
		$this->_load_wrapper('admin/links/edit');
	}

	public function delete($link_id = 0)
	{
		if ( ! $link_id)
			show_404();

		// Load the model
		$this->load->helper('form_helper');
		$this->load->library('form_validation');
		$this->load->model('Links_model', 'links');

		$link = $this->links->load($link_id);

		// Has the form been posted?
		if ($this->input->post())
		{
			// They clicked Cancel
			if ($this->input->post('cancel') !== FALSE)
			{
				// Cancel and redirect
				$this->session->set_flashdata('warning', 'No action will be taken on the ' . $link->site . ' link');

				redirect('/admin/links');
			}
			// They submitted
			else if ($this->input->post('submit') !== FALSE)
			{
				// Success, delete the tag
				$link->allow_deletion();
				$link->delete();

				// Log their action
				$this->log->add($this->user->username . ' (' . $this->user->id . ') deleted the ' . $link->site . ' link');

				// Success Message
				$this->session->set_flashdata('success', 'You deleted the ' . $link->site . ' link');

				// Redirect to Tags Page
				redirect('/admin/links');
			}
		}

		// Vars
		$this->_data['link_id'] = $link->id;
		$this->_data['site'] = $link->site;
		$this->_data['url'] = $link->url;

		// Page Prep and Load
		$this->_data['left_nav'] = 'li';
		$this->_load_wrapper('admin/links/delete');
	}

	/*****************
		AJAX CALLS
	******************/

	public function datatable()
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$links = $this->admin->link_list($this->input->get());

		// Special things for specific columns
		foreach ($links['aaData'] as &$row)
		{
			// Fields to remove and set to _data
			foreach (array('game_id', 'game_slug', 'url', 'flagged') as $var)
			{
				$this->_data[$var] = $row[$var];
				unset($row[$var]);
			}

			$this->_data['link_id'] = $row['id'];
			$row['actions'] = $this->_preview('admin/links/_datatable_actions');

			$this->_data['site'] = $row['site'];
			$row['site'] = $this->_preview('admin/links/_datatable_site');

			$this->_data['game_name'] = $row['game_name'];
			$this->_data['slug'] = $this->_data['game_slug'];
			$row['game_name'] = $this->_preview('admin/games/_datatable_name');

			$this->_data['username'] = $row['submitted_by'];
			$row['submitted_by'] = $this->_preview('admin/users/_datatable_username');

			$this->_data['username'] = $row['approved_by'];
			$row['approved_by'] = $row['approved_by'] ? $this->_preview('admin/users/_datatable_username') : 'Needs Approval';

			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}
		
		$this->_ajax_return($links);
	}

/*
	// /admin/game/link/info/<ID>
	private function _game_link_info($id = NULL)
	{
		$gl = $this->concept->load('game_links', $id);

		$data = $gl->get_all();
		$data['submitted_by_name'] = $gl->get_submitter_name();
		$data['approved_by_name'] = $data['approved_by'] ? $gl->get_approver_name() : 'N/A';
		$data['id'] = $id;
		$data['flags'] = $gl->get_flags();
		
		$this->_ajax_return($data);
	}

	// /admin/game/link/delete/<ID>
	private function _game_link_delete($id = NULL)
	{
		$gl = $this->concept->load('game_links', $id);
		$gl->delete();
	}

	// /admin/game/link/approve/<ID>
	private function _game_link_approve($id = NULL)
	{
		$gl = $this->concept->load('game_links', $id);
		$gl->approve($this->user->id);
	}

	// /admin/game/link/approve/<ID>
	private function _game_link_clear_flags($id = NULL)
	{
		$gl = $this->concept->load('game_links', $id);
		$gl->clear_flags($this->user->id);
	}*/

}