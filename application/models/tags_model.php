<?php

class Tags_model extends CI_Model
{
	private
		$_tags = array();
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Load a tag entity
	 * @param integer $tag_id >> ID of tag being loaded
	 * @return tag object
	 */
	public function load($tag_id)
	{
		if ($tag_id <= 0 || ! is_numeric($tag_id))
			return; # TODO nice error: Invalid Tag
		
		if ( ! isset($this->_tags[$tag_id]))
			$this->_tags[$tag_id] = $this->concept->load('tag', $tag_id);
		
		return $this->_tags[$tag_id];
	}

}