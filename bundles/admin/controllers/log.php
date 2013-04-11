<?php

class Admin_Log_Controller extends Base_Controller
{

	public $restful = TRUE;

	public function get_index()
	{
		return View::make('admin::log')->with(array(
			'left_nav' => 'log'
		));
	}

}