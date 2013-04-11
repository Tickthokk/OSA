<?php

class Comment extends Eloquent
{

	public static $table = 'comments';

	// Comments are made by one user
	public function user()
	{
		return $this->belongs_to('User');
	}

	// Comments belong to one achievement
	public function achievement()
	{
		return $this->belongs_to('Achievement');
	}

}