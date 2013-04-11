<?php

class Admin_Advanced_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		return View::make('admin::advanced')->with(array(
			'left_nav' => 'advanced'
		));
	}

	public function fix_games_tally()
	{
		DB::query('
			UPDATE games AS g 
			SET achievement_tally = (
				SELECT COUNT(id) FROM achievements
				WHERE game_id = g.id
			)
		');

		// Success
		Session::flash('success', 'Games Tallys Fixed');

		return Redirect::to('/admin/advanced');
	}

	public function fix_users_tally()
	{
		DB::query('
			UPDATE users AS u 
			SET achievement_tally = (
				SELECT COUNT(id) FROM achievement_user
				WHERE user_id = u.id
			)
		');

		$this->session->set_flashdata('success', 'User Tallys Fixed');
		
		redirect('admin/advanced');
	}

}