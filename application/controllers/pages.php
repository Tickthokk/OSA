<?php

class Pages extends CI_Controller {
	
	public function view($page = 'home')
	{
		if (!file_exists('application/views/pages/' . $page . '.php'))
		{
			// Page doesn't exist!
			show_404();
		}

		$data['title'] = ucfirst($page); // Cap the first letter

		$this->load->view('wrapper/header', $data);
		$this->load->view('pages/' . $page, $data);
		$this->load->view('wrapper/footer', $data);
	}
}