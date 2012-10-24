<?php

class Test extends OSA_Controller
{

	public function json()
	{
		$this->_ajax_return(array('1' => 2, 'a' => 'b'));
	}

	public function failboat()
	{
		$this->_ajax_error('Gone to the beach');
	}

}