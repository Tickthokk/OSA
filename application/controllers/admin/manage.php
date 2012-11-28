<?php

class Manage extends OSA_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Only available to admin users
		if ( ! $this->user->is_admin())
			show_404();

		$this->_data['css'][] = 'admin/admin';

		$this->theme = 'admin';
		$this->_data['left_nav'] = 'um';
	}

	public function users()
	{
		$this->_data['js'][] = 'admin/manage';

		// Page Load
		$this->_load_wrapper('admin/manage/users');
	}

	public function acl($user_id = 0)
	{
		if ( ! $user_id)
			show_404();

		// Load Helpers/Library
		$this->load->helper('form_helper');
		$this->load->library('form_validation');

		$user = $this->concept->load('user', (int) $user_id);

		// Has the form been posted?
		if ($this->input->post())
		{
			if ($this->input->post('cancel') !== FALSE)
			{
				// Cancel and redirect
				$this->session->set_flashdata('warning', 'No action will be taken on ' . $user->username . '\'s account.');

				redirect('/admin/manage/users');
			}
			else if ($this->input->post('submit') !== FALSE)
			{
				// Go through with it
				var_dump($this->input->post());
				exit;
			}
		}

		// Validate Fields
		#TODO#$this->form_validation->set_rules('partner_name', 'lang:partner_label_name', 'trim|xss_clean|alpha_punctuation|required');


		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// Create Form Fields
			#TODO#$this->_data['input_name'] = form_input(array('class' => 'mws-textinput','name' => 'partner_name'), $this->_data['partner']['name']);
		
			// Validation has failed
			$this->_data['error'] = validation_errors('<li>','</li>');

			// Vars
			$this->_data['user_id'] = $user->id;
			$this->_data['username'] = $user->username;

			$this->_load_wrapper('admin/manage/acl');
		}
		else
		{
			// Success
			#TODO# Change Stuff

			// Message
			#TODO#$this->session->set_flashdata('success', $this->lang->line('partner_' . $which . '_success') . ' ' . $this->_data['partner']['name'] . '.');

			// Log their action
			#TODO#$this->User->set_action($this->session->userdata['uid'], ($which == 'add' ? 'Added' : 'Edited') . " Partner " . $this->_data['partner']['name']);

			// Redirect to Users Page
			redirect('/admin/manage/users');
		}

		
	}

	public function ban($user_id = 0)
	{

	}

	public function active($user_id = 0)
	{

	}

}