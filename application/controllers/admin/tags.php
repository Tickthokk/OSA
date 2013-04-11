<?php

class Tags extends OSA_Controller
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
		$this->_data['left_nav'] = 'ta';
		$this->_data['js'][] = 'admin/manage_tags';
		$this->_load_wrapper('admin/tags');
	}

	public function edit($tag_id = 0)
	{
		if ( ! $tag_id)
			show_404();

		// Load the model
		$this->load->helper('form_helper');
		$this->load->library('form_validation');
		$this->load->model('Tags_model', 'tags');

		$tag = $this->tags->load($tag_id);

		// Has the form been posted?
		if ($this->input->post())
		{
			// They clicked Cancel
			if ($this->input->post('cancel') !== FALSE)
			{
				// Cancel and redirect
				$this->session->set_flashdata('warning', 'No action will be taken on the ' . $tag->name . ' tag');

				redirect('/admin/tags');
			}
			// They submitted
			else if ($this->input->post('submit') !== FALSE)
			{
				// Success
				$tag->enable_set('default', 'approved');
				$tag->default = $this->input->post('default') ?: NULL;
				$tag->approved = $this->input->post('approved') ?: NULL;

				// Log their action
				$this->log->add($this->user->username . ' (' . $this->user->id . ') changed the ' . $tag->name . ' tag');

				// Success Message
				$this->session->set_flashdata('success', 'You changed the ' . $tag->name . 'tag');

				// Redirect to Tags Page
				redirect('/admin/tags');
			}
		}

		// Vars
		$this->_data['tag_id'] = $tag->id;
		$this->_data['name'] = $tag->name;
		$this->_data['default_checkbox'] = form_checkbox('default', '1', $tag->default);
		$this->_data['approved_checkbox'] = form_checkbox('approved', '1', $tag->approved);
		$this->_data['inappropriate_list'] = $tag->get_inappropriate_list();

		// Page Prep and Load
		$this->_data['left_nav'] = 'ta';
		$this->_load_wrapper('admin/tags/edit');
	}

	public function delete($tag_id = 0)
	{
		if ( ! $tag_id)
			show_404();

		// Load the model
		$this->load->helper('form_helper');
		$this->load->library('form_validation');
		$this->load->model('Tags_model', 'tags');

		$tag = $this->tags->load($tag_id);

		// Has the form been posted?
		if ($this->input->post())
		{
			// They clicked Cancel
			if ($this->input->post('cancel') !== FALSE)
			{
				// Cancel and redirect
				$this->session->set_flashdata('warning', 'No action will be taken on the ' . $tag->name . ' tag');

				redirect('/admin/tags');
			}
			// They submitted
			else if ($this->input->post('submit') !== FALSE)
			{
				// Success, delete the tag
				$tag->allow_deletion();
				$tag->delete();

				// Log their action
				$this->log->add($this->user->username . ' (' . $this->user->id . ') deleted the ' . $tag->name . ' tag');

				// Success Message
				$this->session->set_flashdata('success', 'You deleted the ' . $tag->name . ' tag');

				// Redirect to Tags Page
				redirect('/admin/tags');
			}
		}

		// Vars
		$this->_data['tag_id'] = $tag->id;
		$this->_data['name'] = $tag->name;

		// Page Prep and Load
		$this->_data['left_nav'] = 'ta';
		$this->_load_wrapper('admin/tags/delete');
	}

	/*****************
		AJAX CALLS
	******************/

	public function datatable()
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$tags = $this->admin->tag_list($this->input->get());

		// Special things for specific columns
		foreach ($tags['aaData'] as &$row)
		{
			$this->_data['inappropriate_tally'] = $row['inappropriate_count'];
			$this->_data['achievement_tally'] = $row['achievement_usage_count'];

			// Fields to remove and set to _data
			foreach (array('inappropriate_count', 'achievement_usage_count') as $var)
			{
				$this->_data[$var] = $row[$var];
				unset($row[$var]);
			}
			$this->_data['tag_id'] = $row['id'];
			
			$row['actions'] = $this->_preview('admin/tags/_datatable_actions');
			$row['default'] = $row['default'] ? 'Yes' : 'No';
			$row['approved'] = $row['approved'] ? 'Yes' : 'No';

			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}
		
		$this->_ajax_return($tags);
	}

}