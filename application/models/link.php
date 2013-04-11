<?php

class Link extends Eloquent
{

	public static 
		$table = 'links',
		$timestamps = TRUE;

	// A link is created by one user
	public function user()
	{
		return $this->belongs_to('User');
	}

	// A link belongs to one game
	public function game()
	{
		return $this->belongs_to('Game');
	}

	// A link can have many flags (custom)
	public function flags()
	{
		return Flag::where('table_id', '=', $this->id)->where('section', '=', 'l')->get();
	}

	// Get statistics about the flags of the link
	public function flag_statistics()
	{
		$flags = $this->flags();

		$link_flags = new stdClass();
		foreach (array('tally', 'open', 'solved', 'unique_users') as $key)
			$link_flags->$key = 0;

		$unique_users = array();

		foreach ($flags as $flag)
		{
			$link_flags->tally++;
			// tally open or solved
			if ( ! $flag->admin_id)
				$link_flags->open++;
			else
				$link_flags->solved++;

			// from X different users
			$unique_users[] = $flag->flagger_ip;
		}
		;
		$link_flags->unique_users = count(array_unique($unique_users));

		$this->flag_statistics = $link_flags;
	}

}