<?php

class Comment_Controller extends Base_Controller 
{

	public $restful = TRUE;

	// public function get_comment()
	// {

	// }

	public function post_comment($achievement_id = 0)
	{
		// The user has to be logged in
		if ( ! Auth::check())
		{
			// Keep their old comment around
			Session::flash('unposted_comment', $comment);

			// Tell them they need to log in
			Session::flash('error', 'You must log in to use this feature!');

			return Redirect::to('auth');
		}
		
		$comment = Input::get('comment');

		// Validate the comment
		if (empty($comment))
			Session::flash('error', 'Cannot post an empty comment.');
		else
		{
			// Validate they're not posting too often
			$last_comment_time = Session::get('last_comment_time');

			if ($last_comment_time && $last_comment_time + $this->seconds_between_posts >= time())
			{
				// They're posting too often
				Session::flash('error', 'Cannot post that often.  Please wait ' . ($last_comment_time + $this->seconds_between_posts - time()) . ' seconds.');

				// Keep their old comment around
				Session::flash('unposted_comment', $comment);
			}
			else
			{
				// Everything is valid, create the comment
				Achievement::find($achievement_id)->comments()->insert(array(
					'user_id' => Auth::user()->id,
					'comment' => $comment
				));

				Session::put('last_comment_time', time());

				// Success Message
				Session::flash('success', 'Your comment has been posted!');
			}
		}

		// Regardless of what happens, take them back to the main achievement page
		return Redirect::to('/achievement/' . $achievement_id);
	}

	public function put_comment($comment_id = 0)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// The user has to be logged in
		if ( ! Auth::check())
			return Event::first('401', 'Feature only available to users.');

		$comment = Comment::find($comment_id);

		if ( ! $comment->exists)
			return Event::first('400', 'That comment does not exist.');

		if ($comment->user_id != Auth::user()->id)
			return Event::first('401', 'You are not the owner of this comment.');

		if ($comment->admin_lock)
			return Event::first('401', 'An administrator has locked your comment.');

		$new_comment = Input::get('comment');

		// Update the database
		$comment->comment = $new_comment;
		$comment->save();

		Bundle::start('sparkdown');

		return Sparkdown\Markdown($new_comment);
	}

	public function delete_comment($comment_id = 0)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// The user has to be logged in
		if ( ! Auth::check())
			return Event::first('401', 'Feature only available to users.');

		$comment = Comment::find($comment_id);

		if ( ! $comment->exists)
			return Event::first('400', 'That comment does not exist.');

		if ($comment->user_id != Auth::user()->id)
			return Event::first('401', 'You are not the owner of this comment.');

		if ($achievement->admin_lock)
			return Event::first('401', 'An administrator has locked your comment.');

		// Everything checks out, delete the comment
		$comment->delete();
	}

}