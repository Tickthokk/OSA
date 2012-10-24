<?php

class Achievements_model extends CI_Model
{
	
	public 
		$game_id = null,
		$_games = array();

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
			return;
		
		# Manually include necessary files
		include_once(APPPATH . 'core/OSA_Concept.php');
		include_once(APPPATH . 'models/achievement_model.php');
		
		if ( ! isset($this->_games[$achievement_id]))
			$this->_games[$achievement_id] = new Achievement_model($achievement_id, $this->db);
		
		return $this->_games[$achievement_id];
	}

	/**
	 * Find From Tag Id
	 *  Find the Achievement ID from a given Achievement Tag ID
	 * @return integer
	 */
	public function find_from_tag_id($achievement_tag_id)
	{
		return $this->db
			->select('achievementId')
			->from('achievement_tags')
			->where('id', $achievement_tag_id)
			->get()->row('achievementId');
	}

	/**
	 * Find From Comment Id
	 *  Find the Achievement ID from a given Achievement Comment ID
	 * @return integer
	 */
	public function find_from_comment_id($achievement_comment_id)
	{
		return $this->db
			->select('achievementId')
			->from('achievement_comments')
			->where('id', $achievement_comment_id)
			->get()->row('achievementId');
	}

	/**
	 * Create Achievement
	 * @param array $data
	 * @uses $this->game_id >> ID of game to attach achievement to
	 * @return generated $achievement_id
	 */
	public function create($user_id, $game_id, $data)
	{
		if (isset($data['systemExclusiveYes']) && isset($data['systemExclusive']))
			$this->db->set('systemExclusive', (int) $data['systemExclusive']);
		
		# Insert
		$this->db->insert('achievements', array(
			'userId' => $user_id,
			'gameId' => $this->game_id,
			'name' => $data['name'],
			'description' => $data['description'],
			'icon' => $data['icon']
		));

		# Get the ID of the Game, return it.
		$achievement_id = $this->db->insert_id();

		# Increment the Game Achivement Tally
		$this->db
			->set('achievementTally', 'achievementTally + 1', FALSE)
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
	
}