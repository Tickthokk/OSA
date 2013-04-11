<?php

class ACL extends Eloquent
{
	
	public static 
		$table = 'acl',
		$timestamps = TRUE;

	public function user()
	{
		return $this->belongs_to('User');
	}

}