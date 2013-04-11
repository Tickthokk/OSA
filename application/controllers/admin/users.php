<?php

class Users extends OSA_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Only available to admin users
		if ( ! $this->user->is_admin())
			show_404();

		$this->_data['css'][] = 'admin/admin';

		$this->theme = 'admin';
	}

	public function index()
	{
		$this->_data['left_nav'] = 'us';
		$this->_data['js'][] = 'admin/manage_users';
		$this->_load_wrapper('admin/users');
	}

	/**
	 * Users Sub Pages
	 */

	public function acl($user_id = 0)
	{
		if ( ! $user_id)
			show_404();

		// Load Helpers/Library
		$this->load->helper('form_helper');

		$user = $this->concept->load('user', (int) $user_id);

		// Has the form been posted?
		if ($this->input->post())
		{
			// They clicked Cancel
			if ($this->input->post('cancel') !== FALSE)
			{
				// Cancel and redirect
				$this->session->set_flashdata('warning', 'No action will be taken on ' . $user->username . '\'s account');

				redirect('/admin/users');
			}
			// They submitted
			else if ($this->input->post('submit') !== FALSE)
			{
				$level = $this->input->post('level');

				if (is_numeric($level) && $this->user->password_test($this->input->post('password')))
				{
					// Success
					$user->change_acl($level);

					// Log their action
					$this->log->add($this->user->username . ' (' . $this->user->id . ') changed ' . $user->username . ' (' . $user->id . ') ACL to ' . $level);

					// Success Message
					$this->session->set_flashdata('success', 'You changed ' . $user->username . '\'s ACL to ' . $level);

					// Redirect to Users Page
					redirect('/admin/users');
				}

				// The above didn't happen, throw an error
				$this->_data['error'] = 'Error: Your password was incorrect.';
			}
		}

		// Vars
		$this->_data['user_id'] = $user->id;
		$this->_data['username'] = $user->username;
		$this->_data['level_select'] = form_dropdown('level', array(
			0 => 'None',
			1 => 'Administrator',
			9 => 'Moderator'
		), @$level ?: $user->get_acl());

		// Page Prep and Load
		$this->_data['left_nav'] = 'us';
		$this->_load_wrapper('admin/users/acl');
	}

	public function ban($user_id = 0)
	{
		if ( ! $user_id)
			show_404();

		// Load Helpers/Library
		$this->load->helper('form_helper');

		$user = $this->concept->load('user', (int) $user_id);

		// Has the form been posted?
		if ($this->input->post())
		{
			// They clicked Cancel
			if ($this->input->post('cancel') !== FALSE)
			{
				// Cancel and redirect
				$this->session->set_flashdata('warning', 'No action will be taken on ' . $user->username . '\'s account');

				redirect('/admin/users');
			}
			// They submitted
			else if ($this->input->post('submit') !== FALSE)
			{
				// If the Post is false, then the checkbox was not hit, meaning don't ban
				$ban = $this->input->post('ban') == 1 ? 1 : 0;
				$reason = $this->input->post('reason');

				// Success
				$user->change_ban($ban, $reason);
				
				// Log their action
				$this->log->add($this->user->username . ' (' . $this->user->id . ') changed ' . $user->username . ' (' . $user->id . ') Ban to ' . ($ban ? 'Yes' : 'No'));

				// Success Message
				$this->session->set_flashdata('success', 'You changed ' . $user->username . '\'s Ban to ' . ($ban ? 'Yes' : 'No') . '');

				// Redirect to Users Page
				redirect('/admin/users');
			}
		}

		// Vars
		$this->_data['user_id'] = $user->id;
		$this->_data['username'] = $user->username;
		$this->_data['ban_checkbox'] = form_checkbox('ban', '1', @$ban ?: $user->banned);
		$this->_data['ban_reason'] = form_textarea('reason', @$reason ?: $user->ban_reason);

		// Page Prep and Load
		$this->_data['left_nav'] = 'us';
		$this->_load_wrapper('admin/users/ban');
	}

	public function active($user_id = 0)
	{
		if ( ! $user_id)
			show_404();

		// Load Helpers/Library
		$this->load->helper('form_helper');

		$user = $this->concept->load('user', (int) $user_id);

		// Has the form been posted?
		if ($this->input->post())
		{
			// They clicked Cancel
			if ($this->input->post('cancel') !== FALSE)
			{
				// Cancel and redirect
				$this->session->set_flashdata('warning', 'No action will be taken on ' . $user->username . '\'s account');

				redirect('/admin/users');
			}
			// They submitted
			else if ($this->input->post('submit') !== FALSE)
			{
				// If the Post is false, then the checkbox was not hit, meaning don't active
				$active = $this->input->post('active') == 1 ? 1 : 0;

				// Success
				$user->change_active($active);
				
				// Log their action
				$this->log->add($this->user->username . ' (' . $this->user->id . ') changed ' . $user->username . ' (' . $user->id . ') Active to ' . ($active ? 'Yes' : 'No'));

				// Success Message
				$this->session->set_flashdata('success', 'You changed ' . $user->username . '\'s Active to ' . ($active ? 'Yes' : 'No') . '');

				// Redirect to Users Page
				redirect('/admin/users');
			}
		}

		// Vars
		$this->_data['user_id'] = $user->id;
		$this->_data['username'] = $user->username;
		$this->_data['active_checkbox'] = form_checkbox('active', '1', @$active ?: $user->activated);

		// Page Prep and Load
		$this->_data['left_nav'] = 'us';
		$this->_load_wrapper('admin/users/active');
	}

	/*****************
		AJAX CALLS
	******************/

	public function datatable()
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$users = $this->admin->user_list($this->input->get());

		// Special things for specific columns
		foreach ($users['aaData'] as &$row)
		{
			// Fields to remove and set to _data
			foreach (array('activated', 'banned', 'ban_reason', 'level', 'achievement_tally') as $var)
			{
				$this->_data[$var] = $row[$var];
				unset($row[$var]);
			}
			$this->_data['user_id'] = $row['id'];
			$this->_data['username'] = $row['username'];
			
			$row['actions'] = $this->_preview('admin/users/_datatable_actions');
			$row['username'] = $this->_preview('admin/users/_datatable_username');

			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}
		
		$this->_ajax_return($users);
	}

}