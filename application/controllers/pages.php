<?php

class Pages extends OSA_Controller {
	
	public function view($page = 'home')
	{
		// Does the page exist?
		if ( ! file_exists('application/views/pages/' . $page . '.php'))
			show_404();

		// Does a method exist for doing special things?
		if (method_exists($this, $page))
			call_user_func(array($this, $page));
		
		# Page Settings
		$this->set_title(ucfirst($page));
		$this->_data['nav_choice'] = $page;
		
		$this->_load_wrapper('pages/' . $page);
	}

	public function home()
	{
		$this->load->model('Achievements_model', 'achievements');
		$this->load->helper('markdown');

		$this->_data['leaderboard'] = $this->achievements->leaderboard();
		$this->_data['achievement_activity'] = $this->achievements->activity();
		$this->_data['achievement_comment_activity'] = $this->achievements->comment_activity();
	}
	
}