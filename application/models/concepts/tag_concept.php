<?php

class Tag_concept extends OSA_Concept
{

	public 
		$_table = 'tags',
		$allow_deletion = FALSE;
	
	public function __construct($db)
	{
		parent::__construct($db);
	}

	public function set_id($tag_id = 0)
	{
		$this->id = (int) $tag_id;
	}

	/**
	 * Allow Deletion
	 *  A failsafe to prevent unintentional deletions.
	 * Modifies the @access private $allow_deletion variable.
	 * @param bool $allowed
	 */
	public function allow_deletion($allowed = TRUE)
	{
		$this->allow_deletion = (bool) $allowed;
	}

	/**
	 * Delete this achievement
	 *  The controller will confirm they are who they say they are.
	 */
	public function delete()
	{
		if ( ! $this->allow_deletion)
			return FALSE;

		$data = $this->get_all();

		// Delete the achievement
		$this->db
			->where('id', $this->id)
			->delete($this->_table);

		// Log the deletion
		$CI =& get_instance();
		$CI->log->add('Tag Permanently Deleted - ' . $data['name']);

		// Achievement Tags
		$this->db
			->where('tag_id', $this->id)
			->delete('achievement_tags');
		
		// Delete tag logs
		// Run a general query of "tag_id doesn't exist anymore, so delete it"
		$this->db->query(
			'DELETE atl.* FROM ' . $this->db->dbprefix('achievement_tag_log') . ' AS atl ' . 
			'LEFT JOIN ' . $this->db->dbprefix('achievement_tags') . ' AS at ON at.id = atl.achievement_tag_id ' . 
			'WHERE at.id IS NULL'
		);
	}

	public function get_inappropriate_list($limit = 10) 
	{
		return $this->db
			->select('atl.id, g.id AS game_id, g.name AS game_name, g.slug AS game_slug, a.id AS achievement_id, a.name AS achievement_name, u.username, atl.when')
			->from('achievement_tag_log AS atl')
			->join('achievement_tags AS at', 'at.id = atl.achievement_tag_id')
			->join('achievements AS a', 'a.id = at.achievement_id')
			->join('games AS g', 'g.id = a.game_id')
			->join('users AS u', 'u.id = atl.user_id')
			->where('atl.approval', '-2')
			->where('at.tag_id', $this->id)
			->limit($limit)
			->get()->result_array();
	}

}