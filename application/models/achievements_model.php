<?php

class Achievements_model extends CI_Model
{
	
	public 
		$game_id = null;

	public function __construct()
	{
		parent::__construct();
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
	 * Create Achievement
	 * @uses $this->game_id >> ID of game to attach achievement to
	 * @uses $this->input->post
	 * @return generated $achievement_id
	 */
	public function create()
	{
		# Prep
		$name = $this->input->post('name');
		
		# Insert
		$this->db->insert('achievements', array(
			'gameId' => $this->game_id,
			'name' => $name
		));

		# Get the ID of the Game
		$achievement_id = $this->db->insert_id();
		
		return $achievement_id;
	}

	/**
	 * Get All
	 */
	 public function get_all()
	 {
	 	return $this->db
	 		->from('achievements')
	 		->where('gameId', $this->game_id)
	 		->get()->result_array();
	 }
	
	
}