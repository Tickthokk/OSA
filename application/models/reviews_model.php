<?php

class Reviews_model extends CI_Model
{
	public
		$game_id = 0;

	public function __construct($game_id)
	{
		parent::__construct();
		$this->game_id = (int) $game_id;
	}

	public function get_last($how_many)
	{
		return $this->db
			->select('r.id, r.gameId, r.authorId, u.username, tc.title, bc.body, r.submittedAt, r.likes')
			->from('reviews AS r')
			->join('title_content AS tc', 'tc.id = r.titleId')
			->join('body_content AS bc', 'bc.id = r.bodyId')
			->join('users AS u', 'u.id = r.authorId')
			->where('r.gameId', $this->game_id)
			->order_by('submittedAt', 'desc')
			->limit(5)
			->get()->result_array();
	}


}