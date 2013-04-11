<?php

class Achievement_flags extends OSA_Controller
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
		$this->_data['left_nav'] = 'af';
		$this->_data['js'][] = 'admin/manage_achievement_flags';
		$this->_load_wrapper('admin/achievement_flags');
	}

	/*****************
		AJAX CALLS
	******************/

	public function datatable($only_for = NULL)
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$flags = $this->admin->achievement_flag_list($this->input->get(), $only_for);

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
			foreach (array('flagger_ip', 'game_id', 'game_slug', 'achievement_id') as $var)
			{
				$this->_data[$var] = $row[$var];
				unset($row[$var]);
			}
			
			$this->_data['flag_id'] = $row['id'];
			$row['actions'] = $this->_preview('admin/achievement_flags/_datatable_actions');

			// We can re-use the games table name
			$this->_data['game_name'] = $row['game_name'];
			$row['game_name'] = $this->_preview('admin/games/_datatable_name');

			// We can re-use the games table name
			$this->_data['achievement_name'] = $row['achievement_name'];
			$row['achievement_name'] = $this->_preview('admin/achievements/_datatable_name');

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