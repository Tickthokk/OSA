<?php

class Pages extends OSA_Controller {
	
	public function view($page = 'home')
	{
		if ( ! file_exists('application/views/pages/' . $page . '.php'))
		{
			// Page doesn't exist!
			show_404();
		}
		
		$this->_data['success'] = $this->session->flashdata('success');
		
		# Page Settings
		$this->set_title(ucfirst($page));
		$this->_data['nav_choice'] = $page;
		
		$this->_load_wrapper('pages/' . $page);
	}
}