<?php

class Game_model extends OSA_Concept
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

	public function get_systems()
	{
		return $this->db
			->select('s.id, s.name, s.slug, s.type, d.slug AS developer')
			->from('system_games AS sg')
			->join('systems AS s', 's.id = sg.systemId')
			->join('developers AS d', 'd.id = s.developerId')
			->where('sg.gameId', $this->id)
			->get()->result_array();
	}

	public function get_links()
	{
		return $this->db
			->select('gl.id, gl.site, gl.url, gl.approved, COUNT(f.id) AS flagged', FALSE)
			->from('game_links AS gl')
			->join('flags AS f', 'f.sectionId = gl.id AND f.section = "gamelink"', 'left')
			->group_by('gl.id')
			->where('gl.gameId', $this->id)
			->get()->result_array();
	}

	public function add_link($user_id, $site, $url)
	{
		$this->db
			->set('submitted', 'NOW()', FALSE)
			->insert('game_links', array(
				'gameId' => $this->id,
				'submittedBy' => $user_id,
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
			->select('SQL_CALC_FOUND_ROWS a.id, a.name, a.description, a.icon, se.slug AS systemSlug, se.name AS systemName, au.achievedAt AS iDidIt, ' . 
				'(SELECT COUNT(id) FROM achievement_comments WHERE achievementId = a.id) AS comments, ' . 
				'(SELECT COUNT(id) FROM achievement_users WHERE achievementId = a.id) AS achievers', 
				FALSE)
			->from('achievements AS a')
			->join('systems AS se', 'se.id = a.systemExclusive', 'left')
			->join('achievement_users AS au', 'au.achievementId = a.id AND au.userId = ' . (int) $user_id, 'left')
			->where('a.gameId', $this->id)
			->order_by('a.added', 'DESC')
			->get()->result_array();
	}
	
}