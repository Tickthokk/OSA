<?php

class Flag_Controller extends Base_Controller 
{

	/**
	 * g >> game
	 * l >> link
	 * a >> achievement
	 * c >> comment
	 * cl >> comment lock
	 */

	private function _create_flag($section = 'l', $table_id = 0, $reason = 'Lorem Ipsum')
	{
		Flag::create(array(
			'section' => $section,
			'table_id' => $table_id,
			'flagger_ip' => DB::raw('INET_ATON("' . Request::ip() . '")'),
			'user_id' => Auth::check() ? Auth::user()->id : NULL,
			'reason' => $reason
		));
	}

	// Flagging a Game Link
	public function action_link($link_id = 0)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// Validate data
		$reason = Input::get('reason');

		if (empty($reason))
			return Event::first('400', 'That is not a valid reason.');

		$link = Link::find($link_id);

		if ( ! $link->exists)
			return Event::first('400', 'That is not a valid link');
		
		// Add flag
		$this->_create_flag('l', $link_id, $reason);

		return TRUE;
	}

	// Flagging a Game
	public function action_game($game_id)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// Validate data
		$reason = Input::get('reason');

		if (empty($reason))
			return Event::first('400', 'That is not a valid reason.');

		$game = Game::find($game_id);

		if ( ! $game->exists)
			return Event::first('404', 'That game does not exist.');

		// Add flag
		$this->_create_flag('g', $game_id, $reason);

		return TRUE;
	}

	// Flagging an Achievement
	public function action_achievement($achievement_id)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// Validate data
		$reason = Input::get('reason');

		if (empty($reason))
			return Event::first('400', 'That is not a valid reason.');

		$achievement = Achievement::find($achievement_id);

		if ( ! $achievement->exists)
			return Event::first('404', 'That achievement does not exist.');

		// Add flag
		$this->_create_flag('a', $achievement_id, $reason);

		return TRUE;
	}

	// Flagging a Comment
	public function action_comment($comment_id)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// Validate data
		$reason = Input::get('reason');

		if (empty($reason))
			return Event::first('400', 'That is not a valid reason.');

		$comment = Comment::find($comment_id);

		if ( ! $comment->exists)
			return Event::first('404', 'That comment does not exist.');

		// Add flag
		$this->_create_flag('c', $comment_id, $reason);

		return TRUE;
	}

}