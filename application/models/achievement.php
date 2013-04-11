<?php

class Achievement extends Eloquent
{
	
	public static 
		$table = 'achievements',
		$timestamps = TRUE;

	// Achievements belong to a game
	public function game()
	{
		return $this->belongs_to('Game');
	}

	// Achievements were created by one user
	public function user()
	{
		return $this->belongs_to('User');
	}

	// Achievements can be achieved by many users
	public function users()
	{
		return $this->has_many_and_belongs_to('User');
	}

	// Achievements have many comments
	public function comments()
	{
		return $this->has_many('Comment');
	}

	// Achievements can have many tags
	public function tags()
	{
		return $this->has_many_and_belongs_to('Tag');
	}

	// Achievements can be system specific
	public function system()
	{
		return $this->belongs_to('System', 'system_exclusive');
	}

	// Achievements have one icon
	public function icon()
	{
		return $this->belongs_to('Icon');
	}

	public function basic_statistics()
	{
		//$this->comment_tally = Comment::where('achievement_id', '=', $this->id)->count();
		$this->achiever_tally = AchievementUser::where('achievement_id', '=', $this->id)->count();
	}

	// An achievement can have many flags (custom)
	public function flags()
	{
		return Flag::where('table_id', '=', $this->id)->where('section', '=', 'a')->get();
	}

	public function flag_statistics()
	{
		$flags = $this->flags();

		$achievement_flags = new stdClass();
		foreach (array('tally', 'open', 'solved', 'unique_users') as $key)
			$achievement_flags->$key = 0;

		$unique_users = array();

		foreach ($flags as $flag)
		{
			$achievement_flags->tally++;
			// tally open or solved
			if ( ! $flag->admin_id)
				$achievement_flags->open++;
			else
				$achievement_flags->solved++;

			// from X different users
			$unique_users[] = $flag->flagger_ip;
		}
		
		$achievement_flags->unique_users = count(array_unique($unique_users));

		$this->flag_statistics = $achievement_flags;
	}

	public function attach_tag($tag_string)
	{
		$tag_id = Tag::new_tag($tag_string);

		// Does the relationship between the tag and the achievement exist?
		$at = AchievementTag::achievement_has_tag($this->id, $tag_id);

		// No? Create it
		if ( ! $at)
			AchievementTag::create(array(
				'user_id' => Auth::user()->id,
				'tag_id' => $tag_id,
				'achievement_id' => $this->id
			));
	}

}