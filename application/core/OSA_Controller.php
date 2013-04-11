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

		$this->environment = @$_SERVER['APPLICATION_ENV'] ?: 'production';
		$this->firewall = @$_SERVER['BEHIND_FIREWALL'] ?: FALSE;
		$this->theme = '';

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
			'page_search' => '',
			'firewall_enabled' => $this->firewall,
			'css' => array(),
			'js' => array(),
			'is_moderator' => FALSE,
			'is_admin' => FALSE,
			'gravatar_url' => '',
			'left_nav' => '', // Admin Only
		);

		# Manually include the necessary files
		$this->load->model('Concept_model', 'concept');
		$this->load->model('Log_model', 'log');
		
		$this->user = $this->concept->load('user', $this->session->userdata('user_id'));

		if ($this->user->is_logged)
		{
			if ($this->user->banned)
			{
				$this->session->set_flashdata('error', 'Your account has been banned.  Reason: ' . $this->user->ban_reason);
				$this->user->logout();
				redirect('/user/login');
			}
			/*elseif ( ! $this->user->activated)
			{
				// Your account has not been activated
			}*/
		}
	}

	public function set_more_data($array)
	{
		if (empty($array)) return;

		foreach ($array as $key => $value)
			$this->_data[$key] = $value;
	}

	public function set_title($title)
	{
		$this->_data['page_title'] .= ' | ' . $title;
	}

	public function _load_wrapper($page_path)
	{
		# Common Message Variables
		foreach (array('success', 'warning', 'error') as $message)
			if ( ! isset($this->_data[$message]))
				$this->_data[$message] = $this->session->flashdata($message);

		# Is the user a Moderator?
		if ($this->user->is_moderator())
		{
			$this->_data['css'][] = 'admin';
			$this->_data['js'][] = 'admin';
			$this->_data['is_moderator'] = TRUE;
			
			if ($this->user->is_admin())
			{
				$this->_data['is_admin'] = TRUE;
			}
		}

		//$placeholder_image = 'http://placehold.it/40x40.jpg';
		$placeholder_image = 'http://lorempixel.com/output/abstract-q-c-40-40-' . rand(1,6) . '.jpg';

		# Figure out a gravatar url:
		$this->_data['gravatar_url'] = $this->user->is_logged 
			? 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->user->email ?: ''))) . "?d=" . urlencode($placeholder_image) . '&s=40' 
			: $placeholder_image;

		# Page Display
		$this->parser->parse('wrapper/' . ($this->theme ? ($this->theme . '/'): '') . 'header', $this->_data);
		$this->parser->parse($page_path, $this->_data);
		$this->parser->parse('wrapper/' . ($this->theme ? ($this->theme . '/'): '') . 'footer', $this->_data);
	}

	public function _preview($page_path)
	{
		ob_start();
		echo $this->parser->parse($page_path, $this->_data, TRUE);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function _ajax_only($user_only = FALSE)
	{
		# TODO put these in language file
		if ( ! $this->input->is_ajax_request())
			show_error('This page can only be accessed through an AJAX call.');
		
		if ($user_only && ! $this->user->is_logged)
			$this->_ajax_error('You are not logged in!', 'Restricted');
	}

	public function _ajax_return($var)
	{
		exit(json_encode($var));
	}

	public function _ajax_error($var, $title = 'Error')
	{
		header('HTTP/1.1 500 ' . $title);
		exit(json_encode($var));
	}

	public function _users_only($error_message = TRUE)
	{
		if ($this->user->is_logged)
			return true;

		# TODO put this in language file
		if ($error_message)
			$this->session->set_flashdata('error', 'You must log in to use this feature!');

		$this->session->set_userdata('redirect_after_login', $_SERVER['REQUEST_URI']);

		redirect('/user/login');
	}

}