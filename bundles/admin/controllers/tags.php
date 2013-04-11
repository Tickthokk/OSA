<?php

class Admin_Tags_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		// Inputs
		$sort = Input::get('sort') ?: 'id';
		$sort_dir = Input::get('sort_dir') ?: 'asc';
		$search = Input::get('search');
		
		// Begin icons
		$tags = Tag::order_by('tags.' . $sort, $sort_dir);

		// Searching
		if ($search)
		{
			// if (preg_match('/^Special:\s(.*)$/', $search, $matches))
			// {
			// 	switch ($matches[1])
			// 	{
			// 		case 'Approved':
			// 			// Need to specify selecting * from icons because (laravel would cause) flag.id to override link.id
			// 			$icons->select('icons.*')
			// 				->join('flags','flags.table_id', '=', 'icons.id')
			// 				->where('flags.section', '=', 'a')
			// 				->where_null('flags.admin_id')
			// 				->group_by('icons.id');
			// 			break;
			// 	}
			// }
			// else
				$tags->where('id', 'LIKE', '%' . $search . '%')
					->or_where('name', 'LIKE', '%' . $search . '%');
		}
			
		// Paginate games based on previous filters
		$pagination = $tags->paginate(10);

		$results = $pagination->results;
		
		return View::make('admin::tags')->with(array(
			'left_nav' => 'tags',
			'tags' => $results,
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

}