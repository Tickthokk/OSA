<?php

class AchievementTag extends Eloquent
{
	
	public static 
		$table = 'achievement_tag',
		$timestamps = TRUE;
	
	// Achievements were created by one user
	public function user()
	{
		return $this->belongs_to('User');
	}

	// What achievements the user has created
	public function achievement()
	{
		return $this->belongs_to('Achievement');
	}

	// What tags the achievement is associated with
	public function tag()
	{
		return $this->belongs_to('Tag');
	}

	// Entry Exists?
	public static function achievement_has_tag($achievement_id, $tag_id)
	{
		return AchievementTag::where('achievement_id', '=', $achievement_id)->where('tag_id', '=', $tag_id)->first();
	}

}