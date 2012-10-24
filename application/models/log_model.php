<?php

class Log_model extends CI_Model
{

	public function add($message)
	{
		$this->db
			->set('text', $message)
			->insert('log');
	}
	
}