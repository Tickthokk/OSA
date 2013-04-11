<?php

class Game extends Eloquent
{
	
	public static 
		$table = 'games',
		$timestamps = TRUE;

	// A game can have many achievements
	public function achievements()
	{
		return $this->has_many('Achievement');
	}

	// A game can exist on many systems / platforms
	public function systems()
	{
		return $this->has_many_and_belongs_to('System');
	}

	// A game has many links
	public function links()
	{
		return $this->has_many('Link');
	}

	// A game can have many flags (custom)
	public function flags()
	{
		return Flag::where('table_id', '=', $this->id)->where('section', '=', 'g')->get();
	}

	public function flag_statistics()
	{
		$flags = $this->flags();

		$game_flags = new stdClass();
		foreach (array('tally', 'open', 'solved', 'unique_users') as $key)
			$game_flags->$key = 0;

		$unique_users = array();

		foreach ($flags as $flag)
		{
			$game_flags->tally++;
			// tally open or solved
			if ( ! $flag->admin_id)
				$game_flags->open++;
			else
				$game_flags->solved++;

			// from X different users
			$unique_users[] = $flag->flagger_ip;
		}
		
		$game_flags->unique_users = count(array_unique($unique_users));

		$this->flag_statistics = $game_flags;
	}

	// Doing this at the game level because we want all the links together in one db call
	// public function links_flag_statistics()
	// {
	// 	$link_ids = $results = array();
	// 	foreach ($this->links as $link)
	// 	{
	// 		$link_ids[] = $link->id;

	// 		$results[$link->id] = array();
	// 		$link->flag_statistics = new stdClass();
	// 		foreach (array('count', 'open', 'solved', 'unique_users') as $key)
	// 		{
	// 			$link->flag_statistics->$key = 0;
	// 		}
				
	// 	}

	// 	$flags = DB::table('flags')
	// 		->select(array(
	// 			'id', 'table_id', 'flagger_ip', 'admin_id'
	// 		))
	// 		->where_in('table_id', $link_ids)
	// 		->where('section', '=', 'l')
	// 		->get();

	// 	$results = array();
	// 	foreach ($flags as $flag)
	// 	{

	// 	}

	// 	var_dump($flags);


	// }

	/**
	 *	Get Games
	 *	@param string $developer >> the Slug of the Developer
	 *	@param string $system >> the Slug of the System
	 *	@param letter $letter >> Just one character >> NULL = #/Special
	 * 	@return game list
	 */
	public static function filtrate($developer = NULL, $system = NULL, $letter = '', $per_page = 8)
	{
		// If they're all "all", then we want a random assortment
		if ($system == 'all' && $developer == 'all' && $letter == 'all')
		{
			// But, ORDER BY RAND() is expensive, so let's avoid that

			// Get the max ID
			$max = Game::max('id');

			// Generate 5 times more than we need in that range
			$numbers = array();
			foreach (range(1, $per_page * 5) as $i)
				$numbers[] = rand(1, $max);

			// For an extra spice of randomization, and make sure they're unique
			$numbers = array_unique($numbers);
			shuffle($numbers);


			// Select X out of those X*5!
			return Game::where_in('id', $numbers)->take($per_page)->get();
			//return Game::where_in('id', $numbers)->take($per_page)->paginate($per_page);
		}

		// Start the query
		$fluent = DB::table('game_system AS gs')
			->select(array(
				'g.id', 'g.name', 'g.slug', 'g.achievement_tally'
			))
			->join('games AS g', 'g.id', '=', 'gs.game_id')
			->join('systems AS s', 's.id', '=', 'gs.system_id')
			->join('developers AS d', 'd.id', '=', 's.developer_id')
			->join('achievements AS a', 'a.game_id', '=', 'g.id')
			->group_by('g.id');

		// If system is defined, use that
		// If not, and developer is defined, use that
		if ($system && $system != 'all') 
			$fluent->where('s.slug', '=', $system);
		elseif ($developer && $developer != 'all')
			$fluent->where('d.slug', '=', $developer);

		// If a letter is defined, include it in the search as well
		if (is_null($letter))
			$fluent->where('g.first_letter', 'IS NULL');
		elseif ( ! empty($letter) && $letter != 'all')
			$fluent->where('g.first_letter', '=', $letter);

		return $fluent->get();
		//return $fluent->paginate($per_page);
	}

}