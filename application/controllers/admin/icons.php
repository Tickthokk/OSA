<?php

class Icons extends OSA_Controller
{

	public function __construct()
	{
		parent::__construct();

		# Only available to admin users
		if ( ! $this->user->is_admin())
			show_404();

		$this->theme = 'admin';
	}

	public function index()
	{
		$this->_data['left_nav'] = 'ic';
		$this->_data['js'][] = 'admin/manage_icons';
		$this->_load_wrapper('admin/icons');
	}

	public function editor()
	{
		// Load the models/helpers
		$this->load->model('Icons_model', 'icons');
		$this->load->helper('form_helper');

		$icons = array();
		foreach ($this->icons->get_all() as $icon)
			$icons[] = $icon['filename'];

		$this->_data['icon_dropdown'] = form_dropdown('icons', $icons, '', 'id="icons"');

		$this->_data['css'][] = 'thirdparty/evol.colorpicker';
		$this->_data['js'][] = 'admin/icons_editor';
		$this->_data['js'][] = 'jquery/evol.colorpicker.min';
		$this->_load_wrapper('admin/icons/editor');
	}

	public function reassess()
	{
		// Load the models/helpers
		$this->load->helper('directory');
		$this->load->model('Icons_model', 'icons');

		// Get a list of the hard file icons, but only at root level
		$map = directory_map(FCPATH . 'assets/images/icons/', 1);
		$icons = array();
		foreach ($map as $file)
			$icons[] = preg_replace('/(\.svg|\.png)$/', '', $file); // Strip off .svg or .png

		// There's now two of every icon, an svg and png.  Unique the array.
		$icons = array_unique($icons);
		
		// Run the Reassess function
		$this->icons->reassess($icons);

		// Set a success message
		$this->session->set_flashdata('success', 'Icons have been reassessed');

		// Go back to the index
		redirect('/admin/icons');
	}

	/** 
	 * WARNING
	 *  This is CURLing an outside domain at least 950 times
	 */
	public function tag_grabber()
	{
		$code = $this->input->get('code');

		if ($code != 'yes')
			show_error('Please insert ?code=yes to continue.');

		// Load the models/helpers
		$this->load->model('Icons_model', 'icons');
		$this->load->helper('form_helper');
		$this->load->library('curl');

		$icons = array();
		foreach ($this->icons->get_all() as $icon)
			$icons[] = $icon['filename'];

		$tags = array();
		foreach ($icons as $filename)
		{
			$tags[$filename] = array();

			$url = 'http://game-icons.net/lorc/originals/' . $filename . '.html';

			$buffer = $this->curl->simple_get($url/*, array_merge(array(CURLOPT_USERAGENT => random_useragent()), $options)*/);

			$dom = new DOMDocument();
			@$dom->loadHTML($buffer);
			@$finder = new DomXPath($dom);

			$tag_nodes = $finder->query("//ul[@class='tags']/li/a");

			foreach ($tag_nodes as $tag)
				$tags[$filename][] = $tag->textContent;
		}
		
		// Run the Reassess function
		$this->icons->reset_tags($tags);

		// Set a success message
		$this->session->set_flashdata('success', 'Icons tags have been grabbed');

		// Go back to the index
		redirect('/admin/icons');
	}

	/*****************
		AJAX CALLS
	******************/

	public function datatable()
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$icons = $this->admin->icon_list($this->input->get());

		// Special things for specific columns
		foreach ($icons['aaData'] as &$row)
		{
			$this->_data['filename'] = $row['filename'];
			$row['icon'] = $this->_preview('admin/icons/_datatable_icon');

			//$row['actions'] = $this->_preview('admin/icons/_datatable_actions');
			unset($row['actions']); // Not used anymore

			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}
		
		$this->_ajax_return($icons);
	}

}