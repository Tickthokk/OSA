<?php

class User extends Eloquent
{
	
	public static 
		$table = 'users',
		$timestamps = TRUE,
		$hidden = array('password');

	// What achievements the user has achieved
	public function achievements()
	{
		return $this->has_many_and_belongs_to('Achievement');
	}

	// What achievements the user has created
	public function created_achievements()
	{
		return $this->has_many('Achievement');
	}

	// What comments the user has made
	public function comments()
	{
		return $this->has_many('Comment');
	}

	// What links for games a user has created
	public function links()
	{
		return $this->has_many('Link');
	}

	public function has_achieved($achievement_id)
	{
		return AchievementUser::user_has_achieved($this->id, $achievement_id);
	}

	public function acl()
	{
		return $this->has_one('ACL');
	}

}