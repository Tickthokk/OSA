<?php

class Game_model extends OSA_Concept
{

	public 
		$_table = 'games';
	
	public function __construct($game_id = 0, $db)
	{
		parent::__construct($db);
		$this->id = (int) $game_id;
	}
	
}