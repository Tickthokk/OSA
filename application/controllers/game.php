<?php

class Game_Controller extends Base_Controller 
{

	public $restful = TRUE;

	public function get_view($game_id = 0)
	{
		$game = Game::with(array(
			'systems', 
			'links',
			'achievements',
			'achievements.tags', 
			'achievements.system', 
			'systems.developer',
			'achievements.users' => function($query)
			{
				if (Auth::check())
					$query->where('user_id', '=', Auth::user()->id);
			},
			'achievements.icon',
			'achievement.tags'
		))->find($game_id);

		// Now we need basic information about the game's flags
		$game->flag_statistics();

		// Preload the link flag statistics
		foreach ($game->links as $link)
			$link->flag_statistics();

		// Go through all the achievement bits
		Bundle::start('sparkdown');
		
		// Clean up each achievement
		$tag_list = array();
		$user_achieve_tally = 0;
		foreach ($game->achievements as $achievement)
		{
			// Preload all achievement statistics
			$achievement->basic_statistics();

			// Parse the Markdown, but then remove any html
			$achievement->description = strip_tags(Sparkdown\Markdown($achievement->description));

			// Compress the Tags
			$tags = array();
			foreach ($achievement->tags as $tag)
			{
				$tags[] = $tag->name;
				if ( ! isset($tag_list[$tag->name]))
					$tag_list[$tag->name] = 0;
				$tag_list[$tag->name]++;
			}

			$achievement->compact_tags = json_encode($tags);

			// Did the user achieve this?
			$achievement->user_achieved = $achievement->users && Auth::check() && $achievement->users[0]->id == Auth::user()->id;
			if ($achievement->user_achieved)
				$user_achieve_tally++;
		}

		$tag_list = array_unique($tag_list);
		arsort($tag_list);
		
		return View::make('game')
			->with('game', $game)
			->with('tag_list', $tag_list)
			->with('user_achieve_tally', $user_achieve_tally);
	}

	// GETing links 
	public function get_links($game_id = 0)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		$game = Game::with(array('links'))->find($game_id);

		// Preload the link flag statistics
		foreach ($game->links as $link)
			$link->flag_statistics();

		return View::make('game.links')
			->with('game', $game);
	}

	// POSTing to links is a new link
	public function post_links($game_id = 0)
	{
		// Only available via AJAX
		if ( ! Request::ajax())
			return Event::first('403');

		// Validate data
		$site_name = Input::get('site');
		$site_url = Input::get('url');

		if ( ! filter_var($site_url, FILTER_VALIDATE_URL))
			return Event::first('400', 'URL is invalid.');
		elseif (empty($site_name))
			return Event::first('400', 'Site Name is required.');

		$game = Game::find($game_id);

		// Make sure the game exists
		if ($game->exists)
			// Create a link
			$game->links()->insert(new Link(array(
				'user_id' => Auth::check() ? Auth::user()->id : NULL,
				'site' => $site_name,
				'url' => $site_url
			)));

		return TRUE;
	}

	/**
	 * GET game Image
	 */
	public function get_image($game_id = 0, $width = 300, $height = 300)
	{
		// Temporary: Placeholdit images
		/*header('Content-Type: image/gif');
		readfile('http://placehold.it/' . $width . 'x300');
		exit;*/

		$cache_path = path('storage') . 'cache' . DS . 'images' . DS . 'games' . DS;
		$originals_path = $cache_path . 'originals' . DS;

		// Build the filename, without an extension
		// It could be JPG, GIF, PNG, whatever
		// Example: 27_300_auto // GAMEID_WIDTH_HEIGHT
		$filename = $game_id . '_' . $width . '_' . $height;

		$result = glob($cache_path . $filename . '.{jpg,jpeg,png,gif}', GLOB_BRACE);
		$file = isset($result[0]) ? $result[0] : NULL;

		// If the cached version doesn't exist, look for the original
		if ( ! $file)
		{
			// Re-Set the filename
			// It will be saved as "1.jpg", "2.gif", etc
			$filename = $game_id;

			// Glob again, but in the originals
			$result = glob($originals_path . $filename . '.{jpg,jpeg,png,gif}', GLOB_BRACE);
			$file = isset($result[0]) ? $result[0] : NULL;

			// If the original version doesn't exist, get it from wikipedia
			if ( ! $file)
			{
				// Look up the wiki_slug from the game
				$link = Link::where('game_id', '=', $game_id)->where('site', '=', 'Wikipedia')->order_by('id')->first();

				// Assume we're not gonna find anything, prove me wrong
				$file = $originals_path . 'not_found.jpg';

				if ($link->exists)
				{
					$user_agent = 'User-Agent: OSADataGrabber (+http://oldschoolachievements.com/OSADataGrabber/)';
					
					$wiki_link = $link->url;

					// Replace http://en.wikipedia.org
					//    With http://en.m.wikipedia.org (m. added)
					$wiki_link = preg_replace('/^http\:\/\/en\.wikipedia\.org/', 'http://en.m.wikipedia.org', $wiki_link);

					// Use CURL to get the HTML of the wikipedia page.
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $wiki_link);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
					$result = curl_exec($ch);
					$curl_info = curl_getinfo($ch);
					curl_close($ch);

					if ($curl_info['http_code'] != 404)
					{
						// Load that HTML into the DOM parser
						$dom = new DOMDocument();
						@$dom->loadHTML($result);
						$finder = new DomXPath($dom);

						// Get the Image
						$wiki_image = $finder->query("//table[@class='infobox hproduct']/tr[2]/td/a[@class='image']/img[1]/@src")->item(0)->nodeValue;

						// Get the extension from the src
						preg_match('/\.(\w{3,4})$/', $wiki_image, $matches);
						$ext = $matches[1];
						
						// Grab the file from Wikipedia
						$content = file_get_contents('http:' . $wiki_image);

						// Set the File Path
						$file = $originals_path . $filename . '.' . $ext;

						// Save the file
						file_put_contents($file, $content);
					}
				}
			}
			
			// Original exists now
			// Get the extension again, we won't always know it
			$file_info = pathinfo($file);
			$ext = $file_info['extension'];

			Bundle::start('resizer');

			$new_file = $cache_path . $filename . '_' . $width . '_' . $height . '.' . $ext;

			// Resize it, cache it
			$success = Resizer::open($file)
				->resize($width, $height, 'auto')
				->save($new_file, 90);

			$file = $new_file;
		}

		$mime_types = array(
			'png'	=>	'image/png',
			'jpe'	=>	'image/jpeg',
			'jpeg'	=>	'image/jpeg',
			'jpg'	=>	'image/jpeg',
			'gif'	=>	'image/gif',
		);
		$file_info = pathinfo($file);
		$ext = $file_info['extension'];

		// Cached version exists now, serve it up
		header('Content-Type: ' . $mime_types[$ext]);
		readfile($file);
		exit;
	}

}