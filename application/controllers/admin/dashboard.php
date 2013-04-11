<?php

class Dashboard extends OSA_Controller
{

	public function __construct()
	{
		parent::__construct();

		# Only available to admin users
		if ( ! $this->user->is_admin())
			show_404();

		$this->_data['css'][] = 'admin/admin';

		$this->theme = 'admin';
	}

	public function index()
	{
		$this->load->model('Admin_model', 'admin');

		$tallys = $this->admin->dashboard_tallys();

		// Cleanup tallys
		foreach ($tallys as &$value)
			$value = number_format($value, 0, '.', ',');

		$this->set_more_data($tallys);

		// Page Prep
		$this->_data['left_nav'] = 'dash';

		// Page Load
		$this->_load_wrapper('admin/dashboard');
	}

}