<?php

class Links_model extends CI_Model
{
	private
		$_links = array();
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Load a link entity
	 * @param integer $link_id >> ID of link being loaded
	 * @return link object
	 */
	public function load($link_id)
	{
		if ($link_id <= 0 || ! is_numeric($link_id))
			return; # TODO nice error: Invalid link
		
		if ( ! isset($this->_links[$link_id]))
			$this->_links[$link_id] = $this->concept->load('link', $link_id);
		
		return $this->_links[$link_id];
	}

}