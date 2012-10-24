<?php

class Games_model extends CI_Model
{
	private
		$_games = array();
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Load a game entity
	 * @param integer $game_id >> ID of game being loaded
	 * @return game object
	 */
	public function load($game_id)
	{
		if ($game_id <= 0 || ! is_numeric($game_id))
			return; # TODO nice error: Invalid Game
		
		if ( ! isset($this->_games[$game_id]))
			$this->_games[$game_id] = $this->concept->load('game', $game_id);
		
		return $this->_games[$game_id];
	}

	/**
	 *	Get the Systems of a/all Developers
	 *	@param integer $developerId >> If null, get all developers
	 *	@param boolean $split >> Split up the consoles and portables?
	 *	@return Assoc array
	 *  	@example 
	 *		array(
	 *			'slug' => 'DEVELOPER NAME',
	 *			'consoles' => array(
	 *				array(
	 *					'slug' => 'SYSTEM SLUG',
	 *					'title' => 'SYSTEM FULL NAME'
	 *				),
	 *				...
	 *			),
	 *			'portables' => array(
	 *				array(
	 *					'slug' => 'SYSTEM SLUG',
	 *					'title' => 'SYSTEM FULL NAME'
	 *				),
	 *				...
	 *			)
	 *		),
	 *		...
	 */
	public function get_developer_systems($developerId = null, $split = true)
	{
		
		if ( ! is_null($developerId) )
    		$this->db->where('id', (int) $developerId);
    	
		$query = $this->db->get('developers');
		$developers = $query->result();

		$query = $this->db->get('systems');
		$systems = $query->result();

		$return = array();

		foreach ($developers as $d)
		{
			$array = array(
				'id' => $d->id,
				'slug' => $d->slug,
				'other' => array(),
				'consoles' => array(),
				'portables' => array()
			);
			foreach ($systems as $key => $s) 
			{
				if ($d->id != $s->developerId) 
					continue;
				
				if ($split === false) 
					$type = 'systems';
				elseif ($s->type == '')
					$type = 'other';
				else
					$type = $s->type == 'c' ? 'consoles' : 'portables';
				
				$array[$type][] = array(
					'id' => $s->id,
					'slug' => $s->slug,
					'title' => $s->name
				);

				unset($systems[$key]);
			}
			$return[] = $array;
		}

		return $return;
	}

	/**
	 *	Get Games
	 *	@param string $manufacturer >> the Slug of the Developer
	 *	@param string $system >> the Slug of the system
	 *	@param letter $letter >> Just one character
	 * 	@return assoc array of games
	 */
	public function get_games($manufacturer = null, $system = null, $letter = null)
	{
		# If system is defined, use that
		if ($system && $system != 'all') 
		{
			$systemId = $this->unslug('systems', $system);

			# Main Join
			$this->db->join('system_games', 'games.id = system_games.gameId AND systemId = ' . $systemId);
		}
		# If not, and manufacturer is defined, find the systems of a developer, and use that.
		elseif ($manufacturer && $manufacturer != 'all')
		{
			$developerId = $this->unslug('developers', $manufacturer);

			$systems = $this->get_developer_systems($developerId, false);

			$systemIds = array();
			foreach ($systems[0]['systems'] as $s)
				$systemIds[] = $s['id'];
			
			# Main Join
			$this->db
				->where_in('systemId', $systemIds)
				->join('system_games', 'games.id = system_games.gameId AND systemId IN (' . implode(',', $systemIds) . ')');
		}

		# If a letter is defined, include it in the search as well
		if ($letter && $letter != 'all')
			$this->db->where('firstLetter', $letter);
		
		# If everything was "All", just grab 4 random games
		if ($system == 'all' && $manufacturer == 'all' && $letter == 'all')
		{
			$this->db
				->order_by('RAND()')
				->limit(4);
		}

		//$this->db->select();
		
		return $this->db
			->from('games')
			->get()
			->result_array();
	}

	/**
	 * Unslug <Something>
	 * 	We are given something like `slug-name`, which needs to be translated to their database Id
	 * <Something> is most likely "developers", "systems" or "games"
	 * @param string $type >> The table, "developers", "systems", "games", "etc"
	 * @param string $slug >> The text version of the <something>
	 * @return integer $<something>Id
	 */
	public function unslug($type, $slug)
	{
		$result = $this->db->select('id')->from($type)->where('slug', $slug)->get()->result();
		if (empty($result)) 
			return 0;
		return (int) $result[0]->id;
	}

	/**
	 *	Get Name
	 *	@param string $slug
	 *  @return string $game_name
	 */
	public function get_name($slug)
	{
		$result = $this->db->select('name')->from('games')->where('slug', $slug)->get()->result();
		if (empty($result)) 
			return false;
		return $result[0]->name;
	}

	/**
	 * Search for Games!
	 * @param string $term >> The searched term
	 * No search term?  Limit to 4 random entries
	 * @return assoc array
	 */
	public function search($term)
	{
		$this->db
			->select('g.*, s.slug AS systemSlug, s.name AS systemName')
			->from('games AS g')
			->join('system_games AS sg', 'sg.gameId = g.id')
			->join('systems AS s', 's.id = sg.systemId');
		
		if ( ! empty($term))
			$this->db->like('g.name', $term);
		else
			$this->db->limit(4)->order_by('RAND()');
		
		return $this->db->get()->result_array();
	}

	/**
	 * Create Game
	 * @uses $this->input->post
	 * @return generated $game_id
	 */
	public function create()
	{
		# Prep
		$name = $this->input->post('name');
		
		# Insert
		$this->db->insert('games', array(
			'name' => $name,
			'slug' => $this->input->post('slug'),
			'firstLetter' => strtolower($name[0])
		));

		# Get the ID of the Game
		$game_id = $this->db->insert_id();

		# Connect the game to the designated systems
		foreach ($this->input->post('system') as $system_id)
			$this->db->insert('system_games', array(
				'systemId' => $system_id,
				'gameId' => $game_id
			));
		
		return $game_id;
	}

}