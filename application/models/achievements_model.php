<?php

class Achievements_model extends CI_Model
{
	
	public 
		$game_id = null,
		$_achievements = array();

	public function __construct()
	{
		parent::__construct();
	}

	public function set_game_id($game_id)
	{
		$this->game_id = (int) $game_id;
	}
	
	/**
	 * Load an achievement entity
	 * @param integer $achievement_id >> ID of achievement being loaded
	 * @return achievement object
	 */
	public function load($achievement_id)
	{
		if ($achievement_id <= 0 || ! is_numeric($achievement_id))
			return; # TODO nice error: Invalid Game
		
		if ( ! isset($this->_achievements[$achievement_id]))
			$this->_achievements[$achievement_id] = $this->concept->load('achievement', $achievement_id);
		
		return $this->_achievements[$achievement_id];
	}

	/**
	 * Find From Tag _id
	 *  Find the Achievement ID from a given Achievement Tag ID
	 * @return integer
	 */
	public function find_from_tag_id($achievement_tag_id)
	{
		return $this->db
			->select('achievement_id')
			->from('achievement_tags')
			->where('id', $achievement_tag_id)
			->get()->row('achievement_id');
	}

	/**
	 * Find From Comment _id
	 *  Find the Achievement ID from a given Achievement Comment ID
	 * @return integer
	 */
	public function find_from_comment_id($achievement_comment_id)
	{
		return $this->db
			->select('achievement_id')
			->from('achievement_comments')
			->where('id', $achievement_comment_id)
			->get()->row('achievement_id');
	}

	/**
	 * Create Achievement
	 * @param array $data
	 * @uses $this->game_id >> ID of game to attach achievement to
	 * @return generated $achievement_id
	 */
	public function create($user_id, $game_id, $data)
	{
		if (isset($data['system_exclusive_yes']) && isset($data['system_exclusive']))
			$this->db->set('system_exclusive', (int) $data['system_exclusive']);
		
		# Insert
		$this->db
			->set('added', 'NOW()', FALSE)
			->insert('achievements', array(
				'added_by' => $user_id,
				'game_id' => $this->game_id,
				'name' => $data['name'],
				'description' => $data['description'],
				'icon' => $data['icon']
			));

		# Get the ID of the Game, return it.
		$achievement_id = $this->db->insert_id();

		# Increment the Game Achivement _tally
		$this->db
			->set('achievement_tally', 'achievement_tally + 1', FALSE)
			->where('id', $game_id)
			->update('games');

		return $achievement_id;
	}

	/**
	 * Get Default Tags
	 */
	public function get_default_tags()
	{
		return $this->db
			->select('id, name')
			->from('tags')
			->where('default', 1)
			->get()->result_array();
	}

	/**
	 * Get User Achievements
	 * @param int $user_id >> User's ID
	 * @param int $row_count >> Per Page
	 * @param int $offset >> Which Page (in multiples of Per Page)
	 * @sets $this->user_achievements_count
	 * @return array of results
	 */
	public $user_achievements_count = 0;
	public function get_user_achievements($user_id, $row_count = 10, $offset = 0)
	{
		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS au.id AS auid, au.achieved, au.achievement_id, a.name AS achievement_name, a.description AS achievement_description, a.icon AS achievement_icon, g.id AS game_id, g.name AS game_name', FALSE)
			->from('achievement_users AS au')
			->join('achievements AS a', 'a.id = au.achievement_id')
			->join('games AS g', 'g.id = a.game_id')
			->where('au.user_id', $user_id)
			->limit($row_count, $offset)
			->get()->result_array();

		$this->user_achievements_count = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');

		return $results;
	}

	/**
	 * Get User Achievement Comments
	 */
	public $user_achievement_comments_count = 0;
	public function get_user_achievement_comments($user_id, $row_count = 10, $offset = 0)
	{
		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS ac.id AS acid, ac.modified_by, ac.comment, ac.added, ac.achievement_id, a.name AS achievement_name, g.id AS game_id, g.name AS game_name', FALSE)
			->from('achievement_comments AS ac')
			->join('achievements AS a', 'a.id = ac.achievement_id')
			->join('games AS g', 'g.id = a.game_id')
			->join('users AS mu', 'mu.id = ac.modified_by', 'LEFT')
			->where('ac.added_by', $user_id)
			->limit($row_count, $offset)
			->get()->result_array();

		$this->user_achievement_comments_count = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');

		return $results;
	}


	/**
	 * Get User Created Achievements
	 */
	public $user_created_achievements_count = 0;
	public function get_user_created_achievements($user_id, $row_count = 10, $offset = 0)
	{
		$results = $this->db
			->select('SQL_CALC_FOUND_ROWS a.id, a.added_by, a.added, a.name AS achievement_name, g.id AS game_id, g.name AS game_name, a.icon', FALSE)
			// Get comments count
			->select('(SELECT COUNT(id) FROM achievement_comments WHERE achievement_id = a.id) AS comments', FALSE)
			// Get achievers count
			->select('(SELECT COUNT(id) FROM achievement_users WHERE achievement_id = a.id) AS achievers', FALSE)
			->from('achievements AS a')
			->join('games AS g', 'g.id = a.game_id')
			->join('users AS mu', 'mu.id = a.modified_by', 'LEFT')
			->where('a.added_by', $user_id)
			->limit($row_count, $offset)
			->get()->result_array();

		$this->user_created_achievements_count = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');

		return $results;
	}

	/**
	 * Leaderboard (top 10 achievers)
	 * Sorting DESC by last_login - In events of a tie, the user who had the most recent activity last will place higher
	 */
	public function leaderboard($wanted = 10)
	{
		return $this->db
			->select('u.id, u.username, u.achievement_tally')
			->from('users AS u')
			->join('user_acl AS ua', 'ua.uid = u.id', 'LEFT')
			->where('ua.level IS NULL') // Prevent Administrators from displaying on Leaderboard
			->order_by('achievement_tally DESC, last_login', 'DESC')
			->limit($wanted)
			->get()->result_array();
	}

	/**
	 * Activity (last 10 achievers)
	 */
	public function activity($wanted = 10)
	{
		return $this->db
			->select('au.id AS auid, a.id AS achievement_id, g.id AS game_id, u.id AS user_id, u.username, g.name AS game_name, a.name AS achievement_name, au.achieved')
			->from('achievement_users AS au')
			->join('achievements AS a', 'a.id = au.achievement_id')
			->join('games AS g', 'g.id = a.game_id')
			->join('users AS u', 'u.id = au.user_id')
			->order_by('au.achieved DESC, au.id DESC')
			->limit($wanted)
			->get()->result_array();
	}

	/**
	 * Comment Activity (last 10 comments)
	 */
	public function comment_activity($wanted = 10)
	{
		return $this->db
			->select('ac.id AS acid, a.id AS achievement_id, g.id AS game_id, u.id AS user_id, u.username, g.name AS game_name, a.name AS achievement_name, ac.comment, ac.added')
			->from('achievement_comments AS ac')
			->join('achievements AS a', 'a.id = ac.achievement_id')
			->join('games AS g', 'g.id = a.game_id')
			->join('users AS u', 'u.id = ac.added_by')
			->order_by('ac.added DESC, ac.id DESC')
			->limit($wanted)
			->get()->result_array();
	}

}