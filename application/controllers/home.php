<?php

class Home_Controller extends Base_Controller 
{

	/*
	|--------------------------------------------------------------------------
	| The Default Controller
	|--------------------------------------------------------------------------
	|
	| Instead of using RESTful routes and anonymous functions, you might wish
	| to use controllers to organize your application API. You'll love them.
	|
	| This controller responds to URIs beginning with "home", and it also
	| serves as the default controller for the application, meaning it
	| handles requests to the root of the application.
	|
	| You can respond to GET requests to "/home/profile" like so:
	|
	|		public function action_profile()
	|		{
	|			return "This is your profile!";
	|		}
	|
	| Any extra segments are passed to the method as parameters:
	|
	|		public function action_profile($id)
	|		{
	|			return "This is the profile for user {$id}.";
	|		}
	|
	*/

	public function action_index()
	{
		// $comments = Activity::comments();

		// Bundle::start('sparkdown');

		// // Markdown each comment, strip the tags and check for Disemvowelment
		// foreach ($comments as $comment)
		// {
		// 	$comment->markdown_comment = strip_tags(Sparkdown\Markdown($comment->comment));

		// 	if ($comment->admin_lock)
		// 		$comment->markdown_comment = 'An administrator has disemvoweled this comment.' . "\n\n" . 
		// 									preg_replace('/[aeiouy\d\-\.\_\n]/i', '', $comment->markdown_comment);
		// }

		return View::make('front')
			->with('leaderboard', Activity::leaderboard())
			->with('achievements', Activity::achievements());
			//->with('comments', $comments);
	}

	public function action_disqus()
	{
		// TESTING

		$url = 'https://disqus.com/api/3.0/posts/list.json?forum=oldschoolachievements&limit=10&secret_key=aqGyz0dfU6Wl3A3YmjMqrZyeYcOY8Krw0rIpk82KBVjhlnEi381JjlfLn2FdTp3J';
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		var_dump($result);
		exit;

	}

}