<?php

class Admin_Users_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		// Inputs
		$sort = Input::get('sort') ?: 'id';
		$sort_dir = Input::get('sort_dir') ?: 'asc';
		$search = Input::get('search');
		
		// Begin Users
		$users = User::order_by($sort, $sort_dir);

		// Searching
		if ($search)
		{
			if (preg_match('/^Special:\s(.*)$/', $search, $matches))
			{
				switch ($matches[1])
				{
					case 'Banned':
						$users->where('banned', '=', '1');
						break;
					case 'Inactive':
						$users->where('activated', '!=', '1');
						break;
				}
			}
			else
				$users->where('id', 'LIKE', '%' . $search . '%')
					->or_where('username', 'LIKE', '%' . $search . '%');
		}
			
		// Paginate Users based on previous filters
		$pagination = $users->paginate(10);
		
		return View::make('admin::users')->with(array(
			'left_nav' => 'users',
			'users' => $pagination->results,
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

	public function get_control()
	{
		return View::make('admin::users/control')
			->with('user', User::find(Input::get('user_id')));
	}

	public function post_control()
	{
		$user = User::find(Input::get('user_id'));

		$user->banned = Input::get('banned');
		$user->activated = Input::get('activated');
		$user->ban_reason = Input::get('ban_reason');

		$user->save();
	}

}