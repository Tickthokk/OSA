<?php

class Admin_Dashboard_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		return View::make('admin::dashboard')->with(array(
			'left_nav' => 'dashboard'
		));
	}

}