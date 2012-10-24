<?php

class Search extends OSA_Controller {
	
	public function main()
	{
		# Models
		$this->load->model('Games_model', 'games');

		# Data
		$search = $this->input->post('search');

		# Game Search Results
		$games = $this->games->search($search);

		# Achievement Search Results
		// TODO $achievements = $this->achievements->search($search);
		
		# Page Data
		$this->set_title('Search');
		$this->set_more_data(compact(
			'search', 'games'
		));

		# Page Load
		$this->_load_wrapper('search');
	}
}