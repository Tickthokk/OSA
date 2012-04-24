<?php

class Game extends OSA_Controller
{
	
	public function index($game_id)
	{
		# Models
		$this->load->model('Games_model', 'games');
		$this->load->model('Achievements_model', 'achievements');

		$game = $this->games->load($game_id);
		$this->achievements->game_id = $game->id;
		
		# Header
		$this->set_title($game->name);
		
		# Body
		$this->set_more_data(compact(
			'game'
		));

		# Page Load
		$this->_load_wrapper('game');
	}

}