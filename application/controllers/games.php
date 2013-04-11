<?php

class Games_Controller extends Base_Controller 
{

	public $per_page = 8;

	public function action_index($developer = 'all', $system = 'all', $letter = 'all')
	{
		// $query_string = array();
		// foreach (array('developer', 'system', 'letter') as $var)
		// {
		// 	$value =& $$var;
		// 	if (is_null($value))
		// 	{
		// 		$value = Input::get($var);
		// 		if ( ! $value)
		// 			$value = 'all';
		// 	}
		// 	$query_string[$var] = $value;
		// }
		
		//$pagination = Game::filtrate($developer, $system, $letter, $this->per_page);

		return View::make('games')
			->with(array(
				'chosen_developer' => $developer,
				'chosen_system' => $system,
				'chosen_letter' => $letter,
				'developers' => Developer::with(array('systems'))->order_by('slug')->get(),
				//'games' => $pagination->results,
				//'pagination' => $pagination->appends($query_string)->links(),
				'games' => Game::filtrate($developer, $system, $letter),
				'pagination' => ''
			));
	}

	public function action_search()
	{
		$search_term = Input::get('search');

		$search_term = trim($search_term);

		$pagination = DB::table('games')
			->where('games.name', 'like', '%' . $search_term . '%')
			->paginate($this->per_page);

		return View::make('search')
			->with(array(
				'games' => $pagination->results,
				'pagination' => $pagination->appends(array('search' => $search_term))->links(),
				'search_term' => $search_term,
				'per_page' => $this->per_page
			));
	}

}