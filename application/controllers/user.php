<?php

class User_Controller extends Base_Controller 
{

	public function action_view($username = NULL)
	{
		if (is_null($username))
			$user = Auth::user();
		else
			$user = User::where_username($username)->first();

		if ( ! $user)
			return Response::error('404');

		return View::make('user')
			->with('user', $user);
	}

}