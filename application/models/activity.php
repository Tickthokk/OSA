<?php

class Activity {
	
	public static function leaderboard($take = 10)
	{
		return User::order_by('achievement_tally', 'DESC')
			->order_by('last_login')
			->take($take)
			->get();
	}
	public static function achievements($take = 10)
	{
		return AchievementUser::with(array('user', 'achievement', 'achievement.game'))
			->order_by('created_at', 'DESC')
			->order_by('id', 'DESC')
			->take($take)
			->get();
	}

	public static function comments($take = 10)
	{
		return Comment::with(array('user', 'achievement', 'achievement.game'))
			->order_by('created_at', 'DESC')
			->order_by('id', 'DESC')
			->take($take)
			->get();
	}

}