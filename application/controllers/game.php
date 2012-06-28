<?php

class Game extends OSA_Controller
{
	
	public function index($game_id)
	{
		# Models
		$this->load->model('Games_model', 'games');
		$this->load->model('Achievements_model', 'achievements');

		$this->game = $this->games->load($game_id);
		#$reviews = $game->reviews->get_last(5);
		$this->achievements->game_id = $this->game->id;

		
		# Header
		$this->set_title($this->game->name);

		# Body
		$this->_data['game_id'] = $this->game->id;
		$this->_data['achievements'] = $this->achievements->get_all();
		
		# Page Load
		$this->_load_wrapper('game');
	}

}