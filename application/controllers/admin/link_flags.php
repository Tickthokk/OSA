<?php

class Link_flags extends OSA_Controller
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
		$this->_data['left_nav'] = 'lf';
		$this->_data['js'][] = 'admin/manage_link_flags';
		$this->_load_wrapper('admin/link_flags');
	}

	/*****************
		AJAX CALLS
	******************/

	public function datatable($only_for = NULL)
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$flags = $this->admin->game_link_flag_list($this->input->get(), $only_for);

		$this->_data['only_for'] = $only_for;

		// Special things for specific columns
		foreach ($flags['aaData'] as &$row)
		{
			$username_was_ip = FALSE;
			if ( ! $row['flagged_by'])
			{
				$row['flagged_by'] = $row['flagger_ip'];
				$username_was_ip = TRUE;
			}

			$this->_data['slug'] = $row['game_slug'];
			
			// Fields to remove and set to _data
			foreach (array('flagger_ip', 'game_id', 'game_slug', 'url', 'link_id') as $var)
			{
				$this->_data[$var] = $row[$var];
				unset($row[$var]);
			}
			
			$this->_data['flag_id'] = $row['id'];
			$row['actions'] = $this->_preview('admin/link_flags/_datatable_actions');

			$this->_data['site'] = $row['site'];
			$row['site'] = $this->_preview('admin/link_flags/_datatable_site');

			// We can re-use the games table name
			$this->_data['game_name'] = $row['game_name'];
			$row['game_name'] = $this->_preview('admin/games/_datatable_name');

			// And we can re-use the username thing
			if ( ! $username_was_ip)
			{
				$this->_data['username'] = $row['flagged_by'];
				$row['flagged_by'] = $this->_preview('admin/users/_datatable_username');	
			}

			// Do the username for the solver as well
			$this->_data['username'] = $row['solved_by'];
			$row['solved_by'] = $this->_preview('admin/users/_datatable_username');

			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}
		
		$this->_ajax_return($flags);
	}

}