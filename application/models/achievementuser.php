<?php

class AchievementUser extends Eloquent
{
	
	public static 
		$table = 'achievement_user',
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

	// Entry Exists?
	public static function user_has_achieved($user_id, $achievement_id)
	{
		return AchievementUser::where('user_id', '=', $user_id)->where('achievement_id', '=', $achievement_id)->first();
	}

}