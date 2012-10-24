<?php

class Games extends OSA_Controller
{
	
	public function view($manufacturer = 'all', $system = 'all', $letter = 'all')
	{
		# Models
		$this->load->model('Games_model', 'games');
		
		# Data
		$developer_systems = $this->games->get_developer_systems();

		$games = $this->games->get_games($manufacturer, $system, $letter);

		# Header
		$this->set_title('Games');
		$this->_data['page_nav_choice'] = 'Games';

		# Body
		$this->set_more_data(compact(
			'manufacturer', 'system', 'letter', 
			'developer_systems', 'games'
		));

		# Page Load
		$this->_load_wrapper('games/filter');
	}
	
}