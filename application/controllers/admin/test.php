<?php

class Test extends OSA_Controller
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
		$this->load->model('Icons_model', 'icons');

		$icons = array();
		foreach ($this->icons->get_all() as $icon)
			$icons[] = $icon['filename'];

		$this->_data['icons'] = $icons;

		$this->_data['js'][] = 'admin/test';
		$this->_load_wrapper('admin/test');
	}

}