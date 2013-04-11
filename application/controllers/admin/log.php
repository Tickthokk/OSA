<?php

class Log extends OSA_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Only available to admin users
		if ( ! $this->user->is_admin())
			show_404();

		$this->theme = 'admin';
	}

	public function index()
	{
		$this->_data['left_nav'] = 'lo';
		$this->_data['js'][] = 'admin/manage_log';
		$this->_load_wrapper('admin/log');
	}
	
	/*****************
		AJAX CALLS
	******************/

	public function datatable()
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$log_items = $this->admin->log_list($this->input->get());
		
		// Special things for specific columns
		foreach ($log_items['aaData'] as &$row)
		{
			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}

		$this->_ajax_return($log_items);
	}

}