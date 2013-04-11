<?php

class Flags_model extends CI_Model
{
	private
		$_flags = array(),
		// Never re-arrange the order
		$_sections = array(
			'site',
			'game',
			'game_link',
			'achievement',
			'achievement_comment',
			'achievement_comment_lock',
		);
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Load a flag entity
	 * @param integer $flag_id >> ID of flag being loaded
	 * @return flag object
	 */
	public function load($flag_id)
	{
		if ($flag_id <= 0 || ! is_numeric($flag_id))
			return; # TODO nice error: Invalid Flag
		
		if ( ! isset($this->_flags[$flag_id]))
			$this->_flags[$flag_id] = $this->concept->load('flag', $flag_id);
		
		return $this->_flags[$flag_id];
	}

	public function get_section_id($name = '')
	{
		$sections_flipped = array_flip($this->_sections);
		return $sections_flipped[$name];
	}

	public function get_section_name($id = 0)
	{
		return $this->_sections[$id];
	}

}