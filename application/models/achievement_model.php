<?php

class Achievement_model extends OSA_Concept
{

	public 
		$_table = 'achievements';
	
	public function __construct($achievement_id = 0, $db)
	{
		parent::__construct($db);
		$this->id = (int) $achievement_id;
	}
	
}