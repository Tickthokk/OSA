<?php

class Icons_model extends CI_Model
{

	/**
	 * Get All Icons
	 */
	public function get_all()
	{
		return $this->db
			->select('i.id, i.filename, GROUP_CONCAT(t.name) AS tags', FALSE)
			->from('icons AS i')
			->join('icon_tags AS it', 'it.icon_id = i.id', 'LEFT')
			->join('tags AS t', 't.id = it.tag_id', 'LEFT')
			->group_by('i.id')
			->get()->result_array();
	}

	/**
	 * Reassess icons on file
	 *  This will insert and delete appropriately
	 * @param array $icons >> An array of icons, without ".svg" or ".png"
	 */
	public function reassess($icons = array())
	{
		$results = $this->db
			->select('filename')
			->from('icons')
			->get()->result_array();

		$table_icons = array();
		foreach ($results as $row)
			$table_icons[] = $row['filename'];

		$insert_me = array_diff($icons, $table_icons);
		$delete_me = array_diff($table_icons, $icons);

		if (count($insert_me))
		{
			$batch = array();
			foreach ($insert_me as $filename)
				$batch[] = array(
					'filename' => $filename
				);

			$this->db->insert_batch('icons', $batch);
		}

		if (count($delete_me))
		{
			foreach ($delete_me as $filename)
				$this->db->or_where('filename', $filename);

			$this->db->delete('icons');

			// TODO also delete any related tags
			
		}
	}

	/**
	 * Reset Icon tags
	 * @param array $tags >> Assoc Array with filename as key, tags as values
	 */
	public function reset_tags($data)
	{
		// Load base CI for log usage later
		$CI =& get_instance();

		// Get all existing icons
		$icons = array();
		foreach ($this->get_all() as $icon)
			$icons[$icon['filename']] = $icon['id'];

		// Get all existing tags
		$tags = array();
		$results = $this->db
			->select('id, name')
			->from('tags')
			->get()->result_array();

		foreach ($results as $row)
			$tags[$row['name']] = $row['id'];

		// Loop through the tags, setup the batch insert
		// Create new tags as you go, if needed
		$batch = array();
		foreach ($data as $icon => $tags)
		{
			$icon_id = $icons[$icon];

			foreach ($tags as $tag)
			{
				if ( ! isset($tags[$tag]))
				{
					$this->db->insert('tags', array(
						'name' => $tag
					));
					$tag_id = $this->db->insert_id();

					$CI->log->add('Tag "' . $tag . '" automatically created by icon tag parser');
				} 
				else
					$tag_id = $tags[$tag];

				$batch[] = array(
					'icon_id' => $icon_id,
					'tag_id' => $tag_id
				);
			}
		}

		// Truncate the Icon Tags table completely
		$this->db->query('TRUNCATE ' . $this->db->dbprefix('icon_tags'));

		// Insert the icon to tag relationship
		$this->db->insert_batch('icon_tags', $batch);
	}

}