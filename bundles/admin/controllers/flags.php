<?php

class Admin_Flags_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		return View::make('admin::flags')->with(array(
			'left_nav' => 'flags'
		));
	}

	public function get_control()
	{
		return View::make('admin::flags/control')
			->with('flag', Flag::find(Input::get('flag_id')));
	}

	public function post_control()
	{
		$flag = Flag::find(Input::get('flag_id'));

		$flag->admin_id = Input::get('admin_id');
		$flag->reason = Input::get('reason');

		$flag->save();
	}

}