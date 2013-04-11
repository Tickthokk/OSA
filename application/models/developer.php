<?php

class Developer extends Eloquent
{
	
	public static 
		$table = 'developers';

	// A developer can have many systems
	public function systems()
	{
		return $this->has_many('System');
	}
	
}