<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 *  Extend base class to allow some site-wide consistancies
 */

class OSA_Controller extends CI_Controller
{
	public $_data = array();
	
	public function __construct() 
	{
		parent::__construct();

		# Page Definitions
		$this->_data = array(
			'page_author' => 'Nick Wright',
			'page_description' => 'Old School Achievements',
			'page_keywords' => 'Old School, Achievements',
			'page_title' => 'Old School Achievements',
			'page_nav_choice' => null,
			'page_navigation' => array(
				'home',
				'games',
				'about'
			),
			'page_search' => ''
		);
	}

	public function set_more_data($array)
	{
		$this->_data = array_merge($this->_data, $array);
	}

	public function set_title($title)
	{
		$this->_data['page_title'] .= ' | ' . $title;
	}

	public function _load_wrapper($page_path)
	{
		$this->load->view('wrapper/header', $this->_data);
		$this->load->view($page_path, $this->_data);
		$this->load->view('wrapper/footer', $this->_data);
	}

}