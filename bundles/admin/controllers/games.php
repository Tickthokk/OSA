<?php

class Admin_Games_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		// Inputs
		$sort = Input::get('sort') ?: 'id';
		$sort_dir = Input::get('sort_dir') ?: 'asc';
		$search = Input::get('search');
		
		// Begin games
		$games = Game::with(array('achievements', 'links'))->order_by('games.' . $sort, $sort_dir);

		// Searching
		if ($search)
		{
			if (preg_match('/^Special:\s(.*)$/', $search, $matches))
			{
				switch ($matches[1])
				{
					case 'Flagged':
						// Need to specify selecting * from games because (laravel would cause) flag.id to override game.id
						$games->select('games.*')
							->join('flags','flags.table_id', '=', 'games.id')
							->where('flags.section', '=', 'g')
							->where_null('flags.admin_id')
							->group_by('games.id');
						break;
				}
			}
			else
				$games->where('id', 'LIKE', '%' . $search . '%')
					->or_where('name', 'LIKE', '%' . $search . '%');
		}
			
		// Paginate games based on previous filters
		$pagination = $games->paginate(10);

		$results = $pagination->results;

		foreach ($results as $game)
			$game->flag_statistics();
		
		return View::make('admin::games')->with(array(
			'left_nav' => 'games',
			'games' => $results,
			'pagination' => $pagination->appends(array(
								'sort' => $sort, 
								'sort_dir' => $sort_dir, 
								'search' => $search
							))->links(),
			'sort' => $sort,
			'sort_dir' => $sort_dir,
			'search' => $search
		));
	}

	public function get_edit($game_id)
	{
		Session::put('game_edit_goback', Request::server('http_referer'));

		$game = Game::with('systems')->find($game_id);
		
		$game_systems = array();
		foreach ($game->systems as $gs)
			$game_systems[] = $gs->id;

		return View::make('admin::games.edit')->with(array(
			'left_nav' => 'games',
			'game' => $game,
			'game_systems' => $game_systems,
			'systems' => System::all()
		));
	}

	public function post_edit($game_id)
	{
		$game = Game::find($game_id);

		if (Input::get('submit') == 'submit')
		{
			$game->name = Input::get('name');
			$game->slug = Input::get('slug');
			$game->first_letter = Input::get('first_letter') ?: DB::raw('NULL');

			// Save the Game
			$game->save();

			$game_systems = Input::get('systems');

			// Save the Systems
			$game->systems()->sync($game_systems);

			Session::flash('success', 'Edited "' . $game->name . '"');
		}

		return Redirect::to(Session::get('game_edit_goback') ?: '/admin/games');
	}

	public function get_links($game_id)
	{
		$game = Game::with(array('links', 'links.user'))->find($game_id);

		foreach ($game->links as $link)
			$link->flag_statistics();

		return View::make('admin::games.links')->with(array(
			'left_nav' => 'links',
			'game' => $game
		));
	}

	public function get_flags($game_id)
	{
		$game = Game::find($game_id);

		return View::make('admin::games.flags')->with(array(
			'left_nav' => 'flags',
			'game' => $game,
			'flags' => $game->flags()
		));
	}

	public function get_achievements($game_id)
	{
		$game = Game::with(array(
			'achievements',
			'achievements.user',
			'achievements.icon'
		))->find($game_id);

		foreach ($game->achievements as $achievement)
			$achievement->flag_statistics();

		return View::make('admin::games.achievements')->with(array(
			'left_nav' => 'achievements',
			'game' => $game
		));
	}

}