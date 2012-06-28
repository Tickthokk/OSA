<?php

class Game_model extends OSA_Concept
{

	public 
		$_table = 'games';
	
	public function __construct($db)
	{
		parent::__construct($db);
	}

	public function set_id($game_id = 0)
	{
		$this->id = (int) $game_id;
	}

	public function _get_reviews()
	{
		include_once(APPPATH . 'models/reviews_model.php');
		$this->_data['reviews'] = new Reviews_model($this->id);
	}
	
}