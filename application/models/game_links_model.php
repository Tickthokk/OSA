<?php

class Game_links_model extends OSA_Concept
{

	public 
		$_table = 'game_links';

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
		return $this->db
			->select('f.submitter, u.username, f.created, f.reason')
			->from('flags AS f')
			->join('flag_sections AS fs', 'fs.id = f.section_id')
			->join('users AS u', 'u.id = f.submitter', 'LEFT')
			->where('fs.name', 'game_link')
			->where('f.solved IS NULL')
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

	public function delete()
	{
		$this->db
			->where('id', $this->id)
			->delete('game_links');
	}

	public function clear_flags($approver_id = NULL)
	{
		$section_id = $this->db
			->select('id')
			->from('flag_sections')
			->where('name', 'game_link')
			->get()->row('id');

		$this->db
			->set('solved', 'NOW()', FALSE)
			->set('solved_by', $approver_id)
			->where('section_id', $section_id)
			->where('table_id', $this->id)
			->update('flags');
	}

}