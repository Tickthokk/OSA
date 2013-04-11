<?php

class Auth_Controller extends Base_Controller 
{

	public $restful = TRUE;

	public function get_login()
	{
		if ( ! Session::has('login_redirect'))
			Session::put('login_redirect', $_SERVER['HTTP_REFERER']);

		return View::make('auth.login')
			->with('username', Session::get('login_username'));
	}

	public function post_login()
	{
		$validation = Validator::make(Input::all(), array(
			'username' => 'required',
			'password' => 'required'
		));

		if ($validation->fails())
		{
			$errors = array();
			foreach ($validation->errors->messages as $field => $messages)
				foreach ($messages as $message)
					$errors[] = '<div><strong>' . $field . '</strong> ' . $message . '</div>';
		}
		// Test if credentials are correct
		if (Auth::attempt(array(
			'username' => Input::get('username'), 
			'password' => Input::get('password'), 
			'remember' => true
		)))
		{
			Auth::user()->last_login = DB::raw('NOW()');
			Auth::user()->save();
			
			Session::flash('success', 'You have successfully logged in.');

			$redirect_to = '/';
			if (Session::has('login_redirect'))
			{
				$redirect_to = Session::get('login_redirect');
				Session::forget('login_redirect');
			}

			return Redirect::to($redirect_to);
		}
			
		// Else:  Credentials are false!

		Session::flash('login_username', Input::get('username'));
		Session::flash('error', 'Your username or password was incorrect.');


		return Redirect::to('auth/login');
	}

	public function get_logout()
	{
		Auth::logout();
		Session::flash('success', 'You have successfully logged out.');
		return Redirect::to('auth/login');
	}

}