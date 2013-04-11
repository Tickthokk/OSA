<?php

class System extends Eloquent
{
	
	public static $table = 'systems';

	// Multiple systems can have multiple games
	public function games()
	{
		return $this->has_many_and_belongs_to('Game');
	}

	// A system belongs to one developer
	public function developer()
	{
		return $this->belongs_to('Developer');
	}

}