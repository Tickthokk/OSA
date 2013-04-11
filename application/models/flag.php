<?php

class Flag extends Eloquent
{
	
	public static 
		$table = 'flags',
		$timestamps = TRUE;
	
	// A flag is created by one user
	public function user()
	{
		return $this->belongs_to('User');
	}

}