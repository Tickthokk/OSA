<?php

class Admin_Achievements_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		// Inputs
		$sort = Input::get('sort') ?: 'id';
		$sort_dir = Input::get('sort_dir') ?: 'asc';
		$search = Input::get('search');
		
		// Begin achievements
		$achievements = Achievement::with(array('game', 'user', 'icon'))->order_by('achievements.' . $sort, $sort_dir);

		// Searching
		if ($search)
		{
			if (preg_match('/^Special:\s(.*)$/', $search, $matches))
			{
				switch ($matches[1])
				{
					case 'Flagged':
						// Need to specify selecting * from achievements because (laravel would cause) flag.id to override link.id
						$achievements->select('achievements.*')
							->join('flags','flags.table_id', '=', 'achievements.id')
							->where('flags.section', '=', 'a')
							->where_null('flags.admin_id')
							->group_by('achievements.id');
						break;
				}
			}
			else
				$achievements->where('id', 'LIKE', '%' . $search . '%')
					->or_where('name', 'LIKE', '%' . $search . '%')
					->or_where('description', 'LIKE', '%' . $search . '%');
		}
			
		// Paginate games based on previous filters
		$pagination = $achievements->paginate(10);

		$results = $pagination->results;

		foreach ($results as $achievement)
			$achievement->flag_statistics();
		
		return View::make('admin::achievements')->with(array(
			'left_nav' => 'achievements',
			'achievements' => $results,
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

	public function get_flags($achievement_id)
	{
		$achievement = Achievement::find($achievement_id);

		return View::make('admin::achievements.flags')->with(array(
			'left_nav' => 'flags',
			'achievement' => $achievement,
			'flags' => $achievement->flags()
		));
	}

	public function get_edit($achievement_id)
	{
		Session::put('achievement_edit_goback', Request::server('http_referer'));

		$achievement = Achievement::with(array('game', 'game.systems', 'user', 'icon'))->find($achievement_id);
		
		$game_systems = array();
		foreach ($achievement->game->systems as $gs)
			$game_systems[] = $gs->id;

		return View::make('admin::achievements.edit')->with(array(
			'left_nav' => 'achievements',
			'achievement' => $achievement,
			'game_systems' => $game_systems,
			'systems' => System::all(),
			'icons' => Icon::all()
		));
	}

	public function post_edit($achievement_id)
	{
		$achievement = Achievement::find($achievement_id);

		if (Input::get('submit') == 'submit')
		{
			$achievement->name = Input::get('name');
			$achievement->description = Input::get('description');
			$achievement->system_exclusive = Input::get('system_exclusive') ?: NULL;
			$achievement->created_at = Input::get('created_at');
			$achievement->updated_at = Input::get('updated_at');
			$achievement->icon_id = Input::get('icon_id');
			$achievement->icon_color = Input::get('icon_color') != '' ? hexdec(Input::get('icon_color')) : NULL;
			$achievement->icon_background = Input::get('icon_background') != '' ? hexdec(Input::get('icon_background')) : NULL;

			// Save the Achievement
			$achievement->save();

			Session::flash('success', 'Edited "' . $achievement->name . '"');
		}

		return Redirect::to(Session::get('achievement_edit_goback') ?: '/admin/achievements');
	}

}