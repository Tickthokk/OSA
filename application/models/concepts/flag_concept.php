<?php

class Flag_concept extends OSA_Concept
{

	public 
		$_table = 'flags';
	
	public function __construct($db)
	{
		parent::__construct($db);
	}

	public function set_id($flag_id = 0)
	{
		$this->id = (int) $flag_id;
	}

	public function _get_flagger_username()
	{
		$flagged_by = $this->flagged_by;
		$flagger_ip = $this->flagger_ip;

		return 
			$flagged_by
			? $this->db
				->select('u.username')
				->from('users AS u')
				->where('u.id', $flagged_by)
				->get()->row('username')
			: $this->db
				->select('INET_NTOA(' . $flagger_ip . ') AS ip', FALSE)
				->get()->row('ip')
			;
	}

	public function _get_solver_username()
	{
		$solved_by = $this->solved_by;

		return $this->db
				->select('u.username')
				->from('users AS u')
				->where('u.id', $solved_by)
				->get()->row('username');
	}

}