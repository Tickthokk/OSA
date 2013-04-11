<?php

class Admin_Links_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		// Inputs
		$sort = Input::get('sort') ?: 'id';
		$sort_dir = Input::get('sort_dir') ?: 'asc';
		$search = Input::get('search');
		
		// Begin links
		$links = Link::with(array('game', 'user'))->order_by('links.' . $sort, $sort_dir);

		// Searching
		if ($search)
		{
			if (preg_match('/^Special:\s(.*)$/', $search, $matches))
			{
				switch ($matches[1])
				{
					case 'Flagged':
						// Need to specify selecting * from links because (laravel would cause) flag.id to override link.id
						$links->select('links.*')
							->join('flags','flags.table_id', '=', 'links.id')
							->where('flags.section', '=', 'l')
							->where_null('flags.admin_id')
							->group_by('links.id');
						break;
					case 'Unapproved':
						// Need to specify selecting * from links because (laravel would cause) flag.id to override link.id
						$links->where_null('links.admin_id');
						break;
				}
			}
			else
				$links->where('id', 'LIKE', '%' . $search . '%')
					->or_where('site', 'LIKE', '%' . $search . '%')
					->or_where('url', 'LIKE', '%' . $search . '%');
		}
			
		// Paginate games based on previous filters
		$pagination = $links->paginate(10);

		$results = $pagination->results;

		foreach ($results as $link)
			$link->flag_statistics();
		
		return View::make('admin::links')->with(array(
			'left_nav' => 'links',
			'links' => $results,
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

	public function get_flags($link_id)
	{
		$link = Link::with(array('game', 'user'))->find($link_id);

		return View::make('admin::links.flags')->with(array(
			'left_nav' => 'flags',
			'link' => $link,
			'flags' => $link->flags()
		));
	}

	public function get_edit($link_id)
	{
		Session::put('link_edit_goback', Request::server('http_referer'));

		$link = Link::with('game')->find($link_id);
		
		return View::make('admin::links.edit')->with(array(
			'left_nav' => 'links',
			'link' => $link,
			'admin' => User::find($link->admin_id)
		));
	}

	public function post_edit($link_id)
	{
		$link = Link::find($link_id);

		if (Input::get('submit') == 'submit')
		{
			$link->site = Input::get('site');
			$link->url = Input::get('url');

			$link->admin_id = Input::get('approved') == 1 
				? Auth::user()->id 
				: null;

			// Save the Game
			$link->save();

			Session::flash('success', 'Edited "' . $link->site . '"');
		}

		return Redirect::to(Session::get('link_edit_goback') ?: '/admin/links');
	}

	public function put_edit($link_id)
	{
		if (Input::get('approved') == 1)
		{
			$link = Link::find($link_id);
			$link->admin_id = Auth::user()->id;
			$link->save();
		}
	}

	public function delete_edit($link_id)
	{
		Link::find($link_id)->delete();
	}

}