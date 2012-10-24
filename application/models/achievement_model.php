<?php

class Achievement_model extends OSA_Concept
{

	public 
		$_table = 'achievements';

	private $allow_deletion = FALSE;
	
	public function __construct($achievement_id = 0, $db)
	{
		parent::__construct($db);
		$this->id = (int) $achievement_id;
	}

	public function _get_username() {
		return $this->db
			->select('username')
			->from('users')
			->where('id', $this->userId)
			->get()->row('username');
	}

	##########################
	# General
	##########################

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
		# temp disable
		$this->db
			->where('id', $this->id)
			->delete($this->_table);

		// Log the deletion
		$CI =& get_instance();
		$CI->log->add('Achievement Deleted - ' . $data['name']);
		
		### Games

		// Decrement the Games achievement tally
		$this->db
			->set('achievementTally', 'achievementTally - 1', FALSE)
			->where('id', $data['gameId'])
			->where('achievementTally > 0')
			->update('games');

		### Comments

		// Delete achievement_comments
		$this->db
			->where('achievementId', $this->id)
			->delete('achievement_comments');

		### Achievers

		// Get achievers
		$achievers = $this->db
			->select('GROUP_CONCAT(userId) AS achievers', FALSE)
			->from('achievement_users')
			->where('achievementId', $this->id)
			->get()->row('achievers');

		// Group concat will return 1,2,3,4
		$achievers = explode(',', $achievers);

		// Delete achiever records
		$this->db
			->where('achievementId', $this->id)
			->delete('achievement_users');

		### Users

		// Decrement the user's achievement tally
		$this->db
			->set('achievementTally', 'achievementTally - 1', FALSE)
			->where_in('id', $achievers)
			->where('achievementTally > 0')
			->update('users');

		### Tags / Tags Log

		// Delete achievement tags
		$this->db
			->where('achievementId', $this->id)
			->delete('achievement_tags');

		// Delete tag logs
		// Run a general query of "tagId doesn't exist anymore, so delete it"
		$this->db->query(
			'DELETE atl.* FROM ' . $this->db->dbprefix('achievement_tag_log') . ' AS atl ' . 
			'LEFT JOIN ' . $this->db->dbprefix('achievement_tags') . ' AS at ON at.id = atl.achievementTagId ' . 
			'WHERE at.id IS NULL'
		);
	}

	/**
	 * System Exclusive?
	 *  Find the systemId and system name
	 */
	public function system_exclusive()
	{
		if ( ! $this->systemExclusive)
			return FALSE;

		return $this->db
			->select('id, slug, name')
			->from('systems')
			->where('id', $this->systemExclusive)
			->get()->row_array();
	}

	##########################
	# END General
	##########################

	##########################
	# Achieving
	##########################

	/**
	 * Get Achiever Count
	 *  Get the number of users who have claimed this achievement
	 * @return integer
	 */
	public function get_achiever_count()
	{
		return $this->db
			->select('COUNT(au.id) AS `count`', FALSE)
			->from('achievement_users AS au')
			->where('au.achievementId', $this->d)
			->get()->row('count');
	}

	/**
	 * Get Achievers
	 *  Get a list of users who have claimed this achievement
	 *  Order decending, limit by @param $count
	 * @param integer $count >> how many to get
	 * @return array of users
	 */
	public function get_achievers($count = 10)
	{
		// Prep query
		$this->db
			->select('u.id, u.username, u.achievementTally, au.achievedAt')
			->from('achievement_users AS au')
			->join('users AS u', 'u.id = au.userId')
			->where('au.achievementId', $this->id)
			->order_by('au.achievedAt', 'desc');

		// Limit if available
		if ($count)
			$this->db->limit($count);

		// return array
		return $this->db->get()->result_array();
	}

	/**
	 * Has the User Achieved this achievement?
	 * @param integer $user_id
	 * @return boolean
	 */
	public function has_achieved($user_id)
	{
		if (empty($user_id))
			return FALSE;

		$achieved_id = $this->db
			->select('id')
			->from('achievement_users')
			->where('achievementId', $this->id)
			->where('userId', $user_id)
			->get()->row('id');

		return (bool) $achieved_id;
	}

	/**
	 * Achieve!
	 *  Grant a user this achievement
	 * @param integer $user_id
	 * @return boolean >> success
	 */
	public function achieve($user_id)
	{
		if ($this->has_achieved($user_id))
			return FALSE;

		$this->db->insert('achievement_users', array(
			'achievementId' => $this->id,
			'userId' => $user_id
		));

		return TRUE;
	}

	##########################
	# END Achieving
	##########################

	##########################
	# Tags
	##########################

	/**
	 * Tag Achievement - upon initial creation
	 *  When a user votes on the difficulty of an achievement, create the record and re-calculate the acheivement's quick difficulty
	 * @param integer $user_id
	 * @param array $tags >> Tag List
	 * @return boolean >> false if already voted
	 */
	public function initial_tags($user_id, $tags)
	{
		if (empty($tags))
			return false;

		# Get list of Tag IDs.  Create tags if they don't exist.
		$existing_tags = $this->db
			->select('id, name')
			->from('tags')
			->where_in('name', $tags)
			->get()->result_array();

		$existing_tag_names = $existing_tag_ids = array();
		foreach ($existing_tags as $et) {
			$existing_tag_names[] = strtolower($et['name']);
			$existing_tag_ids[] = (int) $et['id'];
		}

		$tags = array_diff($tags, $existing_tag_names);

		# Create what exists in $tags
		$created_ids = array();
		foreach ($tags as $t)
		{
			$this->db->insert('tags', array(
				'name' => $t
			));
			$created_ids[] = $this->db->insert_id();
		}

		$tag_ids = array_merge($existing_tag_ids, $created_ids);

		# Prepare for Batch Insertion
		$achievement_tag_log = array();

		# Build Batch data
		foreach ($tag_ids as $tag_id)
		{
			$this->db->insert('achievement_tags', array(
				'userId' => $user_id,
				'achievementId' => $this->id,
				'tagId' => $tag_id,
				'approval' => 1 // Initial insertion, by default user always votes for its inclusion
			));

			$achievement_tag_id = $this->db->insert_id();

			$achievement_tag_log[] = array(
				'achievementTagId' => $achievement_tag_id,
				'userId' => $user_id,
				'approval' => 1 // Initial insertion, by default the user always votes for its inclusion
			);
		}

		$this->db->insert_batch('achievement_tag_log', $achievement_tag_log);

		return $tag_ids;
	}

	/**
	 * Get Tags
	 *  Get a list of tags related to this achievement
	 * @return array of tags
	 */
	public function get_tags($user_id)
	{
		return $this->db
			->select('at.id, t.id AS tagId, t.name, SUM(atl.approval) AS approval, uatl.approval AS user_approval, t.approved AS admin_approval')
			->from('achievement_tags AS at')
			->join('tags AS t', 't.id = at.tagId')
			->join('achievement_tag_log AS atl', 'atl.achievementTagId = at.id', 'LEFT') # LEFT JOIN
			->join('achievement_tag_log AS uatl', 'uatl.achievementTagId = at.id AND uatl.userId = ' . (int) $user_id, 'LEFT') # LEFT JOIN
			->where('at.achievementId', $this->id)
			->group_by('at.id')
			->order_by('approval', 'desc')
			->get()->result_array();
	}

	/**
	 * Vote
	 *  A user voted on an achievement tag.  
	 *  Overwrite their previous vote
	 * @param integer $user_id
	 * @param integer $achievement_tag_id
	 * @param integer $approval
	 */
	public function vote($user_id, $achievement_tag_id, $approval)
	{
		$vote_id = $this->db
			->select('id')
			->from('achievement_tag_log')
			->where('achievementTagId', $achievement_tag_id)
			->where('userId', $user_id)
			->get()->row('id');

		if ($vote_id)
		{
			$this->db->update('achievement_tag_log', array(
				'approval' => $approval,
				'when' => 'now()'
			), array(
				'id' => $vote_id
			));

			return $vote_id;
		}
		else
		{
			$this->db->insert('achievement_tag_log', array(
				'achievementTagId' => $achievement_tag_id,
				'userId' => $user_id,
				'approval' => $approval
			));

			return $this->db->insert_id();
		}
	}

	##########################
	# END Tags
	##########################

	##########################
	# Comments
	##########################

	/**
	 * Get Comments
	 *  Get a list of comments related to this achievement
	 * @param integer $top_id >> Don't include comments newer than this id
	 * @param integer $row_count >> LIMIT $offset, $row_count
	 * @param integer $offset >> LIMIT $offset, $row_count
	 * @return array of comments
	 */
	public function get_comments($top_id = NULL, $offset = 0, $row_count = 5)
	{
		// Prep Query
		$this->db
			->select('SQL_CALC_FOUND_ROWS ac.id, ac.userId, u.username, ac.added, ac.modified, ac.comment', FALSE)
			->from('achievement_comments AS ac')
			->join('users AS u', 'u.id = ac.userId')
			->where('ac.achievementId', $this->id)
			->order_by('ac.added', 'DESC')
			->limit($row_count, $offset);

		// Add to query if $top_id is set
		if ( ! is_null($top_id))
			$this->db->where('ac.id <=', (int) $top_id);

		$comments = $this->db
			->get()->result_array();

		$count = $this->db
			->query('SELECT FOUND_ROWS() AS `count`')
			->row('count');

		return array($comments, $count);
	}

	/**
	 * Add Comment
	 *  When a user comments on an achievement
	 * @param integer $user_id
	 * @param string $comment
	 * @param integer $in_reply_to_id >> basically "ParentId" for comments
	 * @return boolean >> success
	 */
	public function add_comment($user_id, $comment/*, $in_reply_to_id = NULL*/)
	{
		$this->db
			->set('added', 'NOW()', FALSE)
			->insert('achievement_comments', array(
				'achievementId' => $this->id,
				'userId' => $user_id,
				'comment' => $comment
			));

		return $this->db->insert_id();
	}

	/**
	 * Edit Comment
	 * @param integer $user_id
	 * @param integer $achievement_comment_id
	 * @param string $comment
	 * @return boolean >> success
	 */
	public function edit_comment($user_id, $achievement_comment_id, $comment)
	{
		// Check if they own this comment (also "validates" userId)
		if ( ! $this->is_their_comment($user_id, $achievement_comment_id))
			return FALSE;

		// Update the comment
		$this->db
			->set('modified', 'NOW()', FALSE)
			->update('achievement_comments', array(
				'comment' => $comment 
			), array(
				'id' => $achievement_comment_id
			));

		return TRUE;
	}

	/**
	 * Delete Comment
	 * @param integer $user_id
	 * @param integer $achievement_comment_id
	 * @return boolean >> success
	 */
	public function delete_comment($user_id, $achievement_comment_id)
	{
		// Check if they own this comment (also "validates" userId)
		if ( ! $this->is_their_comment($user_id, $achievement_comment_id))
			return FALSE;

		// Delete the comment
		$this->db
			->where('id', $achievement_comment_id)
			->delete('achievement_comments');

		return TRUE;
	}

	/**
	 * Is this their comment?
	 */
	public function is_their_comment($user_id, $achievement_comment_id)
	{
		// Does the user exist?
		if (empty($user_id))
			return FALSE;

		// Is this their comment?
		$is_their_comment = $this->db
			->select('id')
			->from('achievement_comments')
			->where('id', $achievement_comment_id)
			->where('userId', $user_id)
			->get()->row('id');

		if ( ! $is_their_comment)
			return FALSE;

		return TRUE;
	}

	##########################
	# Comments END
	##########################

}