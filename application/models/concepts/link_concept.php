<?php

class Link_concept extends OSA_Concept
{

	public 
		$_table = 'game_links',
		$allow_deletion = FALSE;

	public function __construct($db)
	{
		parent::__construct($db);
	}

	public function set_id($game_link_id = 0)
	{
		$this->id = (int) $game_link_id;
	}

	public function get_submitter_name()
	{
		$result = $this->db
			->select('username')
			->from('users')
			->where('id', (int) $this->submitted_by)
			->get()->first_row();

		return @$result->username;
	}

	public function get_approver_name()
	{
		$result = $this->db
			->select('username')
			->from('users')
			->where('id', (int) $this->approved_by)
			->get()->first_row();

		return @$result->username;
	}

	public function get_flags()
	{
		$CI =& get_instance();
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('game_link');

		return $this->db
			->select('f.id, f.flagged_by, u.username AS flagged_by_username, f.flagged_on, INET_NTOA(f.flagger_ip) AS flagger_ip, f.reason', FALSE)
			->from('flags AS f')
			->join('users AS u', 'u.id = f.flagged_by', 'LEFT')
			->where('f.solved_on IS NULL')
			->where('f.section_id', $section_id)
			->where('f.table_id', $this->id)
			->get()->result_array();
	}

	public function approve($approver_id = NULL)
	{
		$this->db
			->set('approved', 'NOW()', FALSE)
			->set('approved_by', $approver_id)
			->where('id', $this->id)
			->update('game_links');
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
		$CI->log->add('Link Permanently Deleted - ' . $data['site']);

		// Delete any flags for this link
		$CI =& get_instance();
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('game_link');

		$this->db
			->where('section_id', $section_id)
			->where('table_id', $data['id'])
			->delete('flags');
	}

	public function clear_flags($approver_id = NULL)
	{
		$CI =& get_instance();
		$CI->load->model('Flags_model', 'flags');
		$section_id = $CI->flags->get_section_id('game_link');

		if ( ! is_numeric($section_id))
			return FALSE;

		$this->db
			->set('solved_on', 'NOW()', FALSE)
			->set('solved_by', $approver_id)
			->where('section_id', $section_id)
			->where('table_id', $this->id)
			->update('flags');
	}

}