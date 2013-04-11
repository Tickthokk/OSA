<?php

class Flags extends OSA_Controller
{

	public function __construct()
	{
		parent::__construct();

		# Only available to admin users
		if ( ! $this->user->is_admin())
			show_404();

		$this->theme = 'admin';
	}

	public function all()
	{
		echo 'TODO';
		exit;
	}

	public function view($flag_id = 0)
	{
		// Load the model
		$this->load->model('Flags_model', 'flags');
		$flag = $this->flags->load($flag_id);

		$this->session->set_userdata(array(
			'flag_referer' => $_SERVER['HTTP_REFERER']
		));
		$this->_data['referer'] = $_SERVER['HTTP_REFERER'];

		// Page Data
		$this->_data['section_name'] = $this->flags->get_section_name($flag->section_id);
		$this->_data['flag_id'] = $flag->id;
		$this->_data['flagger_username'] = $flag->flagger_username;
		$this->_data['solver_username'] = $flag->solver_username;
		$this->set_more_data($flag->get_all());

		// Prep and Load Page
		$this->_data['left_nav'] = $this->input->get('nav');
		$this->_load_wrapper('admin/flags/view');
	}

	public function resolve($direction = 'yes', $flag_id = 0)
	{
		// Load the model
		$this->load->model('Flags_model', 'flags');
		$flag = $this->flags->load($flag_id);

		// Resolve it (or Unresolve it)
		$flag->enable_set('solved_by', 'solved_on');
		$flag->solved_by = $direction == 'yes' ? $this->user->id : NULL;
		$flag->solved_on = $direction == 'yes' ? 'NOW()' : NULL;

		// Success message
		$this->session->set_flashdata('success', 'You have marked game flag ' . $flag_id . ' as ' . ($direction == 'yes' ? '' : 'un') . 'resolved.');

		// Was there a referer?
		$referer = $this->session->userdata('flag_referer');
		$this->session->unset_userdata('flag_referer');

		// Redirect to the referer, and if it didn't exist, back to the view page
		redirect($referer ?: ('/admin/flags/view/' . $flag_id . '?nav=' . $this->input->get('nav')));
	}

}