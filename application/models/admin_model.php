<?php

class Admin_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();


	}

	public function dashboard_tallys()
	{
		$tally_list = array(
			'users', 'games', 'game_links', 
			'achievements', 'achievement_comments', 'achieved', 
			'unapproved_links', 'flagged_links', 'flagged_games'
		);

		$CI =& get_instance();
		$CI->load->driver('cache');

		$return = array();
		foreach ($tally_list as $tally_name)
		{
			$cache_key = 'admin_' . $tally_name . '_tally';
			$tally = $CI->cache->get($cache_key);
			if ( ! $tally)
			{
				$CI->cache->save($cache_key, $tally, NULL, 3600); // Cache for 1 hour
				$tally = $this->{$tally_name . '_tally'}();
			}
			
			$return[$tally_name . '_tally'] = $tally;
		}

		return $return;
	}

	private function basic_tally($table)
	{
		$count = $this->db
			->select('COUNT(*) AS `count`')
			->from($table)
			->get()->row('count');

		if ( ! is_numeric($count))
			return 0;

		return $count;
	}

	public function users_tally()
	{
		return $this->basic_tally('users');
	}

	public function games_tally()
	{
		return $this->basic_tally('games');
	}

	public function game_links_tally()
	{
		return $this->basic_tally('game_links');
	}

	public function achievements_tally()
	{
		return $this->basic_tally('achievements');
	}

	public function achievement_comments_tally()
	{
		return $this->basic_tally('achievement_comments');
	}

	public function achieved_tally()
	{
		return $this->basic_tally('achievement_users');
	}

	public function unapproved_links_tally()
	{
		$this->db->where('approved', NULL);
		return $this->basic_tally('game_links');
	}

	public function flagged_links_tally()
	{
		$section_id = $this->find_section_id('game_link');

		$this->db->where('section_id', $section_id);
		$this->db->where('solved_by', NULL);
		return $this->basic_tally('flags');
	}

	public function flagged_games_tally()
	{
		$section_id = $this->find_section_id('game');

		$this->db->where('section_id', $section_id);
		$this->db->where('solved_by', NULL);
		return $this->basic_tally('flags');
	}

	public function find_section_id($section_name)
	{
		$CI =& get_instance();
		$CI->load->model('Flags_model', 'flags');
		return $CI->flags->get_section_id($section_name);
	}

	/**
	 * User List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function user_list($filter = array())
	{
		$cache_code = md5('admin_user_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Define columns
		$aColumns = array('id', 'username', 'last_login', 'actions');
		$searchable = array('username');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'banned':
					$this->db->where('u.banned', '1');
					break;
				case 'inactive':
					$this->db->where('u.activated', '0');
					break;
				case 'admins':
					$this->db->where('ua.level', 1);
					break;
				case 'mods':
					$this->db->where('ua.level', 9);
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS u.id, u.username, u.activated, u.banned, u.ban_reason, u.last_login, u.achievement_tally, ua.level, NULL AS actions', FALSE)
			->from('users AS u')
			->join('user_acl AS ua', 'ua.uid = u.id', 'LEFT')
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('users')
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Log List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function log_list($filter = array())
	{
		$cache_code = md5('admin_log_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Define columns
		$aColumns = array('id', 'text', 'created');
		$searchable = array('text');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			for ($i = 0; $i < count($aColumns); $i++)
				if (in_array($aColumns[$i], $searchable))
					$this->db->or_like($aColumns[$i], $filter['sSearch']);
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS l.id, l.created, l.text', FALSE)
			->from('log AS l')
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('users')
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Game List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function game_list($filter = array())
	{
		$cache_code = md5('admin_game_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Get the section id for flagged games
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('game');

		// Define columns
		$aColumns = array('id', 'name', 'actions');
		$searchable = array('g.name');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'flagged':
					$this->db->where('f.id !=', 'NULL');
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS g.id, g.name, g.slug, g.achievement_tally, COUNT(f.id) AS flagged, NULL AS actions', FALSE)
			->from('games AS g')
			->join('flags AS f', 'f.table_id = g.id AND f.section_id = ' . $section_id, 'LEFT')
			->group_by('g.id')
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('games')
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Game Flag List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function game_flag_list($filter = array(), $only_for = NULL)
	{
		$cache_code = md5('admin_game_flag_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Get the section id for flagged games
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('game');

		// Define columns
		$aColumns = array('id', 'game_name', 'flagged_by', 'flagged_on', 'solved_by', 'solved_on', 'actions');
		$searchable = array('g.name');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'unsolved':
					$this->db->where('f.solved_by IS NULL');
					break;
				case 'solved':
					$this->db->where('f.solved_by IS NOT NULL');
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		// Special Filter: "Only For"
		if ($only_for)
			$this->db->where('g.id', $only_for);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS f.id, g.id AS game_id, g.name AS game_name, g.slug AS game_slug, u.username AS flagged_by, f.flagged_on, INET_NTOA(f.flagger_ip) AS flagger_ip, su.username AS solved_by, f.solved_on, NULL AS actions', FALSE)
			->from('flags AS f')
			->join('games AS g', 'g.id = f.table_id')
			->join('users AS u', 'u.id = f.flagged_by', 'LEFT')
			->join('users AS su', 'su.id = f.solved_by', 'LEFT')
			->where('f.section_id', $section_id)
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');
		
		// Special Filter: "Only For", do it again for "total" count
		if ($only_for)
			$this->db->where('table_id', $only_for);
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('flags')
			->where('section_id', $section_id)
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Tag List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function tag_list($filter = array())
	{
		$cache_code = md5('admin_tag_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Get the section id for flagged games
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('game');

		// Define columns
		$aColumns = array('id', 'name', 'default', 'approved', 'actions');
		$searchable = array('name');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'default':
					$this->db->where('t.default', 1);
					break;
				case 'nondefault':
					$this->db->where('t.default IS NULL');
					break;
				case 'approved':
					$this->db->where('t.approved', 1);
					break;
				case 'unapproved':
					$this->db->where('t.approved IS NULL');
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS t.id, t.name, t.default, t.approved', FALSE)
			->select('(SELECT COUNT(atl.id) FROM achievement_tag_log AS atl JOIN achievement_tags AS at ON at.id = atl.achievement_tag_id WHERE atl.approval = -2 AND at.tag_id = t.id) AS inappropriate_count', FALSE)
			->select('(SELECT COUNT(at.id) FROM achievement_tags AS at WHERE at.tag_id = t.id) AS achievement_usage_count', FALSE)
			->select('NULL AS actions', FALSE)
			->from('tags AS t')
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('tags')
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Link List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function link_list($filter = array())
	{
		$cache_code = md5('admin_link_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Get the section id for flagged games
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('game_link');

		// Define columns
		$aColumns = array('gl.id', 'game_name', 'site', 'submitted_by', 'approved_by', 'actions');
		$searchable = array('game_name', 'site', 'url');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'flagged':
					$this->db->having('flagged > 0');
					break;
				case 'unapproved':
					$this->db->where('gl.approved_by IS NULL');
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS gl.id, g.id AS game_id, g.name AS game_name, g.slug AS game_slug,, gl.site, gl.url, u.username AS submitted_by, ua.username AS approved_by, COUNT(f.id) AS flagged, NULL AS actions', FALSE)
			->from('game_links AS gl')
			->join('games AS g', 'g.id = gl.game_id')
			->join('flags AS f', 'f.table_id = gl.id AND f.section_id = ' . $section_id, 'LEFT')
			->join('users AS u', 'u.id = gl.submitted_by', 'LEFT')
			->join('users AS ua', 'ua.id = gl.approved_by', 'LEFT')
			->group_by('gl.id')
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('game_links')
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Game Link Flag List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function game_link_flag_list($filter = array(), $only_for = NULL)
	{
		$cache_code = md5('admin_game_link_flag_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Get the section id for flagged games
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('game_link');

		// Define columns
		$aColumns = array('id', 'game_name', 'site', 'flagged_by', 'flagged_on', 'solved_by', 'solved_on', 'actions');
		$searchable = array('g.name', 'gl.site');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'unsolved':
					$this->db->where('f.solved_by IS NULL');
					break;
				case 'solved':
					$this->db->where('f.solved_by IS NOT NULL');
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		// Special Filter: "Only For"
		if ($only_for)
			$this->db->where('gl.id', $only_for);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS f.id, gl.id AS link_id, g.id AS game_id, g.name AS game_name, g.slug AS game_slug, gl.site, gl.url, u.username AS flagged_by, f.flagged_on, INET_NTOA(f.flagger_ip) AS flagger_ip, su.username AS solved_by, f.solved_on, NULL AS actions', FALSE)
			->from('flags AS f')
			->join('game_links AS gl', 'gl.id = f.table_id')
			->join('games AS g', 'g.id = gl.game_id')
			->join('users AS u', 'u.id = f.flagged_by', 'LEFT')
			->join('users AS su', 'su.id = f.solved_by', 'LEFT')
			->where('f.section_id', $section_id)
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');

		// Special Filter: "Only For", do it again for "total" count
		if ($only_for)
			$this->db->where('table_id', $only_for);
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('flags')
			->where('section_id', $section_id)
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Achievement Flag List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function achievement_flag_list($filter = array(), $only_for = NULL)
	{
		$cache_code = md5('admin_achievement_flag_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Get the section id for flagged games
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('achievement');

		// Define columns
		$aColumns = array('id', 'game_name', 'achievement_name', 'flagged_by', 'flagged_on', 'solved_by', 'solved_on', 'actions');
		$searchable = array('g.name', 'a.name');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'unsolved':
					$this->db->where('f.solved_by IS NULL');
					break;
				case 'solved':
					$this->db->where('f.solved_by IS NOT NULL');
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		// Special Filter: "Only For"
		if ($only_for)
			$this->db->where('a.id', $only_for);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS f.id, a.id AS achievement_id, g.id AS game_id, g.name AS game_name, g.slug AS game_slug, a.name AS achievement_name, u.username AS flagged_by, f.flagged_on, INET_NTOA(f.flagger_ip) AS flagger_ip, su.username AS solved_by, f.solved_on, NULL AS actions', FALSE)
			->from('flags AS f')
			->join('achievements AS a', 'a.id = f.table_id')
			->join('games AS g', 'g.id = a.game_id')
			->join('users AS u', 'u.id = f.flagged_by', 'LEFT')
			->join('users AS su', 'su.id = f.solved_by', 'LEFT')
			->where('f.section_id', $section_id)
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');

		// Special Filter: "Only For", do it again for "total" count
		if ($only_for)
			$this->db->where('table_id', $only_for);
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('flags')
			->where('section_id', $section_id)
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Achievement List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function achievements_list($filter = array())
	{
		$cache_code = md5('admin_game_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Get the section id for flagged games
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('achievement');

		// Define columns
		$aColumns = array('id', 'name', 'actions');
		$searchable = array('g.name');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'flagged':
					$this->db->where('f.id !=', 'NULL');
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS a.id, g.id AS game_id, g.name AS game_name, g.slug as game_slug, a.name AS achievement_name, COUNT(f.id) AS flagged, NULL AS actions', FALSE)
			->select('(SELECT COUNT(*) FROM achievement_users AS sub_au WHERE sub_au.achievement_id = a.id) AS achievers', FALSE)
			->from('achievements AS a')
			->join('games AS g', 'g.id = a.game_id')
			->join('flags AS f', 'f.table_id = a.id AND f.section_id = ' . $section_id, 'LEFT')
			->group_by('a.id')
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('achievements')
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

	/**
	 * Icon List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function icon_list($filter = array())
	{
		$cache_code = md5('admin_icon_list_' . serialize($filter));
		$CI =& get_instance();
		$CI->load->driver('cache');

		if ($return = $CI->cache->get($cache_code))
			return $return;

		// Define columns
		$aColumns = array('icon', 'filename', 'tags'/*, 'actions'*/);
		$searchable = array('filename', 'tags');

		// Paging
		if (isset($filter['iDisplayStart']) && $filter['iDisplayLength'] != -1)
			$this->db->limit($filter['iDisplayLength'], $filter['iDisplayStart']);

		// Ordering
		if (isset($filter['iSortCol_0']))
			for ($i = 0; $i < intval($filter['iSortingCols']); $i++)
				if ($filter['bSortable_' . intval($filter['iSortCol_' . $i])] == 'true')
					$this->db->order_by($aColumns[intval($filter['iSortCol_' . $i])], $filter['sSortDir_' . $i]);

		// Filtering
		if ($filter['sSearch'] != '')
			switch ($filter['sSearch'])
			{
				case 'tagless':
					$this->db->where('tag_count', 0);
					break;
				case 'undertagged':
					$this->db->where('tag_count <', 3);
					break;
				default:
					for ($i = 0; $i < count($aColumns); $i++)
						if (in_array($aColumns[$i], $searchable))
							$this->db->or_like($aColumns[$i], $filter['sSearch']);
			}
		
		// Column Filtering
		for ($i = 0; $i < count($aColumns); $i++)
			if ($filter['bSearchable_' . $i] == 'true' && $filter['sSearch_' . $i] != '')
				if (in_array($aColumns[$i], $searchable))
					$this->db->like($aColumns[$i], $filter['sSearch_' . $i]);

		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS NULL AS icon, i.filename', FALSE)
			->select('GROUP_CONCAT(t.name) AS tags', FALSE)
			->select('NULL AS actions', FALSE)
			->from('icons AS i')
			->join('icon_tags AS it', 'it.icon_id = i.id', 'LEFT')
			->join('tags AS t', 't.id = it.tag_id', 'LEFT')
			->group_by('i.id')
			->get()->result_array();

		$filtered_total = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');
		
		$total = $this->db
			->select('COUNT(id) AS `count`', FALSE)
			->from('icons')
			->get()->row('count');

		$return = array(
			'sEcho' => intval($filter['sEcho']),
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $filtered_total,
			'aaData' => $results
		);

		$CI->cache->save($cache_code, $return, 300); // 5 minutes

		return $return;
	}

}