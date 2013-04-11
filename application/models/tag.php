<?php

class Tag extends Eloquent
{

	public static 
		$table = 'tags',
		$timestamps = FALSE;

	// Icons can be tagged
	public function icons()
	{
		return $this->has_many_and_belongs_to('Icon');
	}

	// Achievements can be tagged
	public function achievements()
	{
		return $this->has_many_and_belongs_to('Achievement');
	}

	public static function new_tag($tag_string)
	{
		// Does the tag exist in the Tag database?
		$tag = Tag::where('name', '=', $tag_string)->first();

		// No? Create it
		if ( ! $tag)
			$tag = Tag::create(array(
				'name' => $tag_string
			));

		return $tag->id;
	}

	// Get all "Default" tags
	public static function defaults()
	{
		return Tag::where('default', '=', 1)->order_by('name')->get();
	}
	
}