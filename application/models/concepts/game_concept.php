<?php

class Game_concept extends OSA_Concept
{

	public 
		$_table = 'games';
	
	public function __construct($db)
	{
		parent::__construct($db);
	}

	public function set_id($game_id = 0)
	{
		$this->id = (int) $game_id;
	}

	public function _get_reviews()
	{
		include_once(APPPATH . 'models/reviews_model.php');
		$this->_data['reviews'] = new Reviews_model($this->id);
	}

	public function get_links($ignore_solved = TRUE)
	{
		$section_id = $this->db
			->select('id')
			->from('flag_sections')
			->where('name', 'game_link')
			->get()->row('id');

		if ( ! is_numeric($section_id))
			return array();

		return $this->db
			->select('gl.id, gl.site, gl.url, gl.approved, COUNT(f.id) AS flagged', FALSE)
			->from('game_links AS gl')
			->join('flags AS f', 'f.table_id = gl.id AND f.section_id = ' . $section_id . ($ignore_solved ? ' AND f.solved_by IS NULL' : ''), 'LEFT')
			->group_by('gl.id')
			->where('gl.game_id', $this->id)
			->get()->result_array();
	}

	public function add_link($user_id, $site, $url)
	{
		$this->db
			->set('submitted', 'NOW()', FALSE)
			->insert('game_links', array(
				'game_id' => $this->id,
				'submitted_by' => $user_id,
				'site' => $site,
				'url' => $url
			));

		return $this->db->insert_id();
	}

	/**
	 * Get Achievements
	 *  Get a list of achievements related to this game
	 * @param integer $top_id >> Don't include achievements newer than this id
	 * @param integer $row_count >> LIMIT $offset, $row_count
	 * @param integer $offset >> LIMIT $offset, $row_count
	 * @return array of achievements
	 */
	public function get_achievements($user_id = 0)
	{
		// Prep Query
		return $this->db
			->select('SQL_CALC_FOUND_ROWS a.id, a.name, a.description, a.icon, se.slug AS systemSlug, se.name AS systemName, au.achieved AS iDidIt, ' . 
				'(SELECT COUNT(id) FROM achievement_comments WHERE achievement_id = a.id) AS comments, ' . 
				'(SELECT COUNT(id) FROM achievement_users WHERE achievement_id = a.id) AS achievers', 
				FALSE)
			->from('achievements AS a')
			->join('systems AS se', 'se.id = a.system_exclusive', 'left')
			->join('achievement_users AS au', 'au.achievement_id = a.id AND au.user_id = ' . (int) $user_id, 'left')
			->where('a.game_id', $this->id)
			->order_by('a.added', 'DESC')
			->get()->result_array();
	}

	/**
	 * Get Systems
	 *  Get a game's systems
	 * @return array of system information
	 */
	public function get_systems()
	{
		return $this->db
			->select('s.id, s.name, s.slug, s.type, d.slug AS developer')
			->from('system_games AS sg')
			->join('systems AS s', 's.id = sg.system_id')
			->join('developers AS d', 'd.id = s.developer_id')
			->where('sg.game_id', $this->id)
			->get()->result_array();
	}

	/**
	 * Set Systems
	 *  Set this game's systems
	 * @param array $systems >> An array of IDs
	 */
	public function set_systems($systems = array())
	{
		// Clear out any systems already in place
		$this->db
			->where('game_id', $this->id)
			->delete('system_games');

		$insert = array();
		foreach ($systems as $s)
			$insert[] = array(
				'system_id' => $s,
				'game_id' => $this->id
			);

		if ($insert)
			$this->db->insert_batch('system_games', $insert);
	}
		
}