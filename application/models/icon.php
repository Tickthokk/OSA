<?php

class Icon extends Eloquent
{

	public static $table = 'icons';

	// Icons can have tags
	public function tags()
	{
		return $this->has_many_and_belongs_to('Tag');
	}

	// Icons have many achievements
	public function achievements()
	{
		return $this->has_many('Achievement');
	}

	// Get all unique tags for all icons
	public static function unique_tags()
	{
		return DB::table('icon_tag AS it')
			->select(array('t.id', 't.name', DB::raw('COUNT(it.id) AS icons')))
			->join('tags AS t', 't.id', '=', 'it.tag_id')
			->group_by('t.id')
			->order_by('icons', 'desc')
			->order_by('t.name')
			->get();
	}

	// Get the id of an icon by its filename
	public static function find_icon_id($name = '')
	{
		$icon = Icon::where('filename', '=', $name)->first();

		return $icon ? $icon->id : 0;
	}

	/**
	 * Reassess icons on file
	 *  This will insert and delete appropriately
	 * @param array $icons >> An array of icons, without ".svg" or ".png"
	 */
	public static function reassess($icons = array())
	{
		$table_icons = array();
		foreach (Icon::all() as $icon)
			$table_icons[] = $icon->filename;

		$insert_me = array_diff($icons, $table_icons);
		$delete_me = array_diff($table_icons, $icons);

		if ($insert_me)
			foreach ($insert_me as $filename)
				Icon::create(array('filename' => $filename));

		if ($delete_me)
			foreach ($delete_me as $filename)
			{
				// Find the icon
				$icon = Icon::where('filename', '=', $filename)->first();

				// Delete any related tags
				DB::table('icon_tag')->where('icon_id', '=', $icon->id)->delete();

				// Delete the icon
				$icon->delete();
			}

		return TRUE;
	}

	/**
	 * Reset Icon tags
	 * @param array $tags >> Assoc Array with filename as key, tags as values
	 */
	public static function reset_tags($data)
	{
		// Get all existing icons
		$icons = array();
		foreach (Icon::all() as $icon)
			$icons[$icon->filename] = $icon->id;

		// Get all existing tags
		$tags = array();
		foreach (Tag::all() as $tag)
			$tags[$tag->name] = $tag->id;

		// Loop through the tags, setup the batch insert
		// Create new tags as you go, if needed
		foreach ($data as $filename => $tags)
		{
			$icon_id = $icons[$filename];

			if ( ! $icon_id) 
				continue;

			foreach ($tags as $tag)
			{
				if ( ! isset($tags[$tag]))
				{
					$new_tag = Tag::create(array('name' => $tag));
					$tags[$tag] = $new_tag->id;
					// TODO - Refactor Log - $CI->log->add('Tag "' . $tag . '" automatically created by icon tag parser');
				}
				
				$tag_id = $tags[$tag];

				$batch[] = array(
					'icon_id' => $icon_id,
					'tag_id' => $tag_id
				);
			}
		}

		// Clear out the icon_tag table
		DB::query('TRUNCATE TABLE icon_tag');

		// Insert the icon to tag relationship
		IconTag::insert($batch);

		return TRUE;
	}

}