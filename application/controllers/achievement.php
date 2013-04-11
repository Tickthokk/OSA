<?php

class Achievement_Controller extends Base_Controller 
{

	public $restful = TRUE;

	public $seconds_between_posts = 20;

	public function get_view($achievement_id = 0)
	{
		//$old_comment = Session::get('unposted_comment');

		$achievement = Achievement::with(array(
				'system', 
				'user',
				'tags',
				'icon',
				'game',
				/*'comments',
				'comments.user',*/
				'users' => function($query)
				{
					$query->order_by('created_at', 'DESC')->take(10);
				},
				'achievements.icon',
				'achievements.users'
			))->find($achievement_id);

		// Parse the Markdown
		Bundle::start('sparkdown');
		$achievement->markdown_description = Sparkdown\Markdown($achievement->description);

		// Get the basic information
		$achievement->basic_statistics();

		// Markdown each comment, check for Disemvowelment
		// foreach ($achievement->comments as $comment)
		// {
		// 	$comment->markdown_comment = Sparkdown\Markdown($comment->comment);

		// 	if ($comment->admin_lock)
		// 		$comment->markdown_comment = '<strong>An administrator has disemvoweled this comment.</strong><br>' . 
		// 									preg_replace('/[aeiouy\d\-\.\_]/i', '', strip_tags($comment->markdown_comment));
		// }
		
		return View::make('achievement')
			->with(array(
				'achievement' => $achievement,
				'user_has_achieved' => Auth::check() 
					? Auth::user()->has_achieved($achievement_id) 
					: NULL/*,
				'top_comment_id' => 0,
				'old_comment' => $old_comment*/
			));
	}

	// User has Achieved this one
	public function get_achieve($achievement_id = 0)
	{
		// The user has to be logged in
		if ( ! Auth::check())
		{
			Session::flash('error', 'You must log in to use this feature!');
			Session::put('login_redirect', $_SERVER['REQUEST_URI']);
			return Redirect::to('auth/login');
		}

		$au = AchievementUser::where('user_id', '=', Auth::user()->id)
			->where('achievement_id', '=', $achievement_id)->get();

		// Make sure an achievement doesn't already exist for that user
		if ($au)
			Session::flash('warning', 'You have already achieved ' . Achievement::find($achievement_id)->name);
		else
		{
			AchievementUser::create(array(
				'user_id' => Auth::user()->id,
				'achievement_id' => $achievement_id
			));

			Session::flash('success', 'You achieved ' . Achievement::find($achievement_id)->name);
		}

		return Redirect::to('/achievement/' . $achievement_id);
	}

	// Create an Achievement Form
	public function get_create($game_id = 0)
	{
		// The user has to be logged in
		if ( ! Auth::check())
		{
			Session::flash('error', 'You must log in to use this feature!');
			Session::put('login_redirect', $_SERVER['REQUEST_URI']);
			return Redirect::to('auth/login');
		}

		// Prep Icons for javascript/json
		$icons = array();
		foreach (Icon::with('tags')->get() as $icon)
		{
			$i = array(
				'id' => $icon->id,
				'filename' => $icon->filename,
				'tags' => array()
			);

			foreach($icon->tags as $tag) 
				$i['tags'][] = $tag->name;

			$icons[] = $i;
		}

		// Old Input Values
		$old_values = Input::old();
		// Make sure the indexes exist
		foreach (array('name', 'description', 'system-exclusive', 'icon', 'icon-color', 'icon-bg', 'item') as $field)
			if ( ! isset($old_values[$field]))
				$old_values[$field] = NULL;

		if ( ! isset($old_values['item']['tags']))
			$old_values['item'] = array('tags' => array());
			

		Bundle::start('sparkdown');
		return View::make('achievement.create')
			->with('game', Game::with('systems', 'systems.developer')->find($game_id))
			->with('unique_icon_tags', Icon::unique_tags())
			->with('icons', $icons)
			->with('default_tags', Tag::defaults())
			->with('old_values', $old_values);
	}

	// Create an Achievement
	public function post_create($game_id = 0)
	{
		// The user has to be logged in
		if ( ! Auth::check())
		{
			Session::flash('error', 'You must log in to use this feature!');
			Session::put('login_redirect', $_SERVER['REQUEST_URI']);
			return Redirect::to('auth/login');
		}

		// Validate Data
		$validation = Validator::make(Input::all(), array(
			'name' => 'required',
			'description' => 'required',
			'icon' => 'required'
		));

		// Make sure the icon exists
		$icon_id = Icon::find_icon_id(Input::get('icon'));

		if ($validation->fails() || Input::get('item') == null || $icon_id == 0)
		{
			$errors = array();

			if (Input::get('item') == null)
				$errors[] = '<div><strong>tags</strong> The tags field is required.</div>';

			if ($icon_id == 0)
				$errors[] = '<div><strong>icon</strong> That was not a valid icon.  Hax?</div>';
			
			foreach ($validation->errors->messages as $field => $messages)
				foreach ($messages as $message)
					$errors[] = '<div><strong>' . $field . '</strong> ' . $message . '</div>';

			Session::flash('error', implode('', $errors));
			Input::flash();
			return Redirect::to('/achievement/create/' . $game_id);
		}

		// Prep some vars
		$system_exclusive = Input::get('system-exclusive');
		if ($system_exclusive == 'all')
			$system_exclusive = null;

		// Create the achievement
		$achievement = Game::find($game_id)->achievements()->insert(
			new Achievement(array(
				'user_id' => Auth::user()->id, // Auth::check() above
				'name' => Input::get('name'),
				'description' => Input::get('description'),
				'system_exclusive' => $system_exclusive,
				'icon_id' => $icon_id,
				'icon_color' => hexdec(Input::get('icon-color')) ?: null,
				'icon_background' => hexdec(Input::get('icon-bg')) ?: null
			))
		);

		// Add tags to achievements
		$item = Input::get('item');
		foreach ($item['tags'] as $tag)
			AchievementTag::create(array(
				'user_id' => Auth::user()->id,
				'achievement_id' => $achievement->id,
				'tag_id' => Tag::new_tag($tag) // Only creates if needed, returns ID
			));


		Session::flash('success', 'You created this achievement!');

		return Redirect::to('/achievement/' . $achievement->id);
	}

	// AJAX Calls

	// Add a new Tag
	public function post_tag($achievement_id = 0)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// The user has to be logged in
		if ( ! Auth::check())
			return Event::first('400', 'Feature only available to users.');

		Achievement::find($achievement_id)->attach_tag(trim(strtolower(Input::get('tag'))));
		
		return View::make('achievement.tags')
			->with('achievement', Achievement::with('tags')->find($achievement_id));
	}

	// Update the Achievement Description
	public function put_view($achievement_id = 0)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// The user has to be logged in
		if ( ! Auth::check())
			return Event::first('400', 'Feature only available to users.');

		$achievement = Achievement::find($achievement_id);

		if ( ! $achievement->exists)
			return Event::first('400', 'That achievement does not exist.');

		if ($achievement->user_id != Auth::user()->id)
			return Event::first('401', 'You are not the owner of this achievement.');

		if ($achievement->admin_lock)
			return Event::first('401', 'An administrator has locked this achievement.');

		$new_description = Input::get('comment');

		// Update the database
		$achievement->description = $new_description;
		$achievement->save();

		Bundle::start('sparkdown');

		return Sparkdown\Markdown($new_description);
	}

}