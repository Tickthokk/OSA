<?php

class Admin_Icons_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		// Inputs
		$sort = Input::get('sort') ?: 'id';
		$sort_dir = Input::get('sort_dir') ?: 'asc';
		$search = Input::get('search');
		
		// Begin icons
		$icons = Icon::with(array('tags'))->order_by('icons.' . $sort, $sort_dir);

		// Searching
		if ($search)
		{
			// if (preg_match('/^Special:\s(.*)$/', $search, $matches))
			// {
			// 	switch ($matches[1])
			// 	{
			// 		case 'Flagged':
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
				$icons->where('id', 'LIKE', '%' . $search . '%')
					->or_where('filename', 'LIKE', '%' . $search . '%');
		}
			
		// Paginate games based on previous filters
		$pagination = $icons->paginate(10);

		$results = $pagination->results;
		
		return View::make('admin::icons')->with(array(
			'left_nav' => 'icons',
			'icons' => $results,
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

	public function get_reassess()
	{
		// Get a list of hard file icons, but remove the . and ..
		$map = array_diff(scandir(path('public') . 'img/icons'), array('.', '..'));

		$icons = array();
		foreach ($map as $file)
			$icons[] = preg_replace('/(\.svg|\.png)$/', '', $file); // Strip off .svg or .png

		// There's now two of every icon, an svg and png.  Unique the array.
		$icons = array_unique($icons);

		Icon::reassess($icons);

		Session::flash('success', 'Icons have been reassessed');

		return Redirect::to('/admin/icons');
	}

	public function get_tag_grabber()
	{
		if (Input::get('code') != 'yes')
			exit('Please insert ?code=yes to continue.');

		// Authors (as of 4/5/13)
		$authors = array(
			'lorc', // 1032
			'delapouite', // 20
			'felbrigg', // 5
			'john-colburn', // 1
		);

		if (Input::get('authors') != 'yes')
			exit('Confirm these authors: ' . print_r($authors, TRUE) . 'Please insert &authors=yes to continue.');
		
		// This could take a while
		ini_set('max_execution_time', 1100);
		// Allow for 1 second per icon, although they generally go at 2-4 per second
		// 5:50 needed for 1032 records, for example

		// Prepare a tags array
		$tags = array();
		$i = 0;
		foreach (Icon::all() as $icon)
		{
			$filename = $icon->filename;
			$tags[$filename] = array();

			$base_url = 'http://game-icons.net/';

			// DEVNOTE:  I don't particularly like doing it like this
			//  But as of 4/5/13 the most we'll be doing more than one author
			//   is ~33 times (1 * 3 + 5 * 2 + 20)
			foreach ($authors as $author)
			{
				$url = $base_url . $author . '/originals/' . $filename . '.html';

				// Set up CURL
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Return the HTML, don't display it
				// Yoink
				$buffer = curl_exec($ch);
				$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				// Close it
				curl_close($ch);

				if ($http_status != 200)
					continue; // Doesn't belong to this author, so skip him

				$dom = new DOMDocument();
				@$dom->loadHTML($buffer);
				@$finder = new DomXPath($dom);

				$tag_nodes = $finder->query("//ul[@class='tags']/li/a");

				foreach ($tag_nodes as $tag)
					$tags[$filename][] = $tag->textContent;

				DB::query('INSERT INTO `log` (`text`, `created_at`) VALUES ("' . $i++ . ' - ' . $url . '", NOW())');

				break; // break out of authors loop
			}
		}

		// Reset the Tags
		Icon::reset_tags($tags);

		// Success
		Session::flash('success', 'Icons tags have been grabbed');

		return Redirect::to('/admin/icons');
	}

}