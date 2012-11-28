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
		$this->db->where('solved', NULL);
		return $this->basic_tally('flags');
	}

	public function flagged_games_tally()
	{
		$section_id = $this->find_section_id('game');

		$this->db->where('section_id', $section_id);
		$this->db->where('solved', NULL);
		return $this->basic_tally('flags');
	}

	public function find_section_id($section_name)
	{
		$section_id = $this->db
			->select('id')
			->from('flag_sections')
			->where('name', $section_name)
			->get()->row('id');

		if ( ! is_numeric($section_id))
			return 0;

		return $section_id;
	}

	/**
	 * User List
	 * @param array $filter >> data derrived from jquery/DataTables
	 * @return array specific to DataTables
	 */
	public function user_list($filter = array())
	{
		$cache_code = md5('xadmin_user_list_' . serialize($filter));
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

}