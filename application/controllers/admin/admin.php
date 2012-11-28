<?php

class Admin extends OSA_Controller
{

	public function __construct()
	{
		parent::__construct();

		# Only available to admin users
		if ( ! $this->user->is_admin())
			show_404();

		$this->_data['css'][] = 'admin/admin';

		$this->theme = 'admin';
	}

	public function dashboard()
	{
		$this->load->model('Admin_model', 'admin');

		$tallys = $this->admin->dashboard_tallys();

		// Cleanup tallys
		foreach ($tallys as &$value)
			$value = number_format($value, 0, '.', ',');

		$this->set_more_data($tallys);

		$this->_data['css'][] = 'admin/admin';

		$this->_data['js'][] = 'admin/dashboard';

		$this->_data['left_nav'] = 'dash';

		# Page Load
		$this->_load_wrapper('admin/dashboard');
	}

	/////////////
	// Unapproved
	/////////////

	public function unapproved($what)
	{
		$this->{'_unapproved_' . $what}();
	}

	private function _unapproved_links()
	{
		
	}

	//////////
	// Flagged
	//////////

	public function flagged($what)
	{
		$this->{'_flagged_' . $what}();
	}

	private function _flagged_links()
	{

	}

	private function _flagged_games()
	{

	}

	//////
	// Fix
	//////

	public function fix($what)
	{
		$this->{'_fix_' . $what}();
	}

	private function _fix_games()
	{
		// Recalculate achievement tally on games
		$games = $this->db
			->select('id')
			->from('games')
			->get()->result_array();

		foreach ($games as $g)
		{
			$count = $this->db
				->select('COUNT(id) AS `count`')
				->from('achievements')
				->where('game_id', $g['id'])
				->get()->row('count');

			$this->db
				->set('achievement_tally', $count)
				->where('id', $g['id'])
				->update('games');
		}

		$this->session->set_flashdata('success', 'Game Tallys Fixed');
		
		redirect('admin');
	}

	private function _fix_users()
	{
		// Recalculate achievement tally on users
		$games = $this->db
			->select('id')
			->from('users')
			->get()->result_array();

		foreach ($users as $u)
		{
			$count = $this->db
				->select('COUNT(id) AS `count`')
				->from('achievement_users')
				->where('user_id', $u['id'])
				->get()->row('count');

			$this->db
				->set('achievement_tally', $count)
				->where('id', $g['id'])
				->update('users');
		}

		$this->session->set_flashdata('success', 'User Tallys Fixed');
		
		redirect('admin');

	}

	public function dummy($what, $count)
	{
		$this->{'_create_dummy_' . $what}($count);
	}

	private function _create_dummy_users($count = 100)
	{
		// Create a bunch of dummy users
		foreach (range(1,$count) as $i)
		{
			$uid = uniqid();
			$this->db
				->set('password', 'MD5("' . $uid . '")', FALSE)
				->set('created', 'NOW()', FALSE)
				->insert('users', array(
					'username' => $uid,
					'email' => 'tickthokk+' . $uid . '@gmail.com',
					'activated' => 1
				));
		}

		redirect('admin');
	}

	private function _create_dummy_games($count = 20)
	{
		$this->load->helper('url');

		// Get systems
		$systems = $this->db
			->select('GROUP_CONCAT(id) AS `ids`')
			->from('systems')
			->get()->row('ids');

		$systems = explode(',', $systems);

		// Create a bunch of dummy games
		foreach (range(1,$count) as $i)
		{
			$name = strtolower(random_string(rand(5,20), TRUE));

			// Space in the name?
			if (rand(0, 1))
			{
				$space_goes = rand(3, strlen($name) - 2);
				$name = substr($name, 0, $space_goes) . ' ' . substr($name, $space_goes, strlen($name));
			}

			$name = ucwords($name);
			
			$this->db
				->insert('games', array(
					'first_letter' => strtolower(substr($name, 0, 1)),
					'name' => $name,
					'slug' => url_title($name, '-', true),
					'wiki_slug' => url_title($name, '_')
				));

			// Link a system to it [Max 3]
			$game_id = $this->db->insert_id();

			$another = TRUE;
			$count = 0;
			// Shuffle around the system id's
			shuffle($systems);
			$tmp_system = $systems;
			while ($another && $count++ < 4)
			{
				$system_id = array_pop($tmp_system);

				$this->db
					->insert('system_games', array(
						'game_id' => $game_id,
						'system_id' => $system_id
					));

				// 50% chance to link it to a 2nd or 3rd system
				$another = rand(0, 99) < 50;
			}
		}

		redirect('admin');
	}

	private function _create_dummy_achievements($count = 15)
	{
		// Add dummy achievements (per game)
		$games = $this->db
			->select('id, name, slug')
			->from('games')
			->get()->result_array();

		$tags = $this->db
			->select('GROUP_CONCAT(id) AS `ids`', FALSE)
			->from('tags')
			->where('default', '1')
			->get()->row('ids');

		$users = $this->db
			->select('GROUP_CONCAT(id) AS `ids`', FALSE)
			->from('users')
			->get()->row('ids');

		$tags = explode(',', $tags);
		$users = explode(',', $users);

		$this->load->helper('directory');
		$map = directory_map(FCPATH . 'assets/images/icons/');

		$icons = array();
		foreach ($map as $dir => $m)
			foreach ($m as $png)
				$icons[] = $dir . '/' . $png;

		foreach ($games as $game)
		{
			foreach (range(0,$count) as $acount)
			{
				$name = strtolower(random_string(rand(12,60), TRUE));

				// Space in the name?  75% chance
				$j = 0;
				while (rand(0, 99) < 80 && $j++ < 8)
				{
					$space_goes = rand(3, strlen($name) - 2);
					$name = substr($name, 0, $space_goes) . ' ' . substr($name, $space_goes, strlen($name));
				}

				$name = ucwords($name);

				$description = ucwords(strrev(strtolower($name)));

				// Who created the achievement?
				shuffle($users);
				$user_id = $users[0];

				// 20% chance for system exclusive
				if (rand(0,99) < 20)
				{
					$system_ids = $this->db
						->select('GROUP_CONCAT(system_id) AS `ids`', FALSE)
						->from('system_games')
						->where('game_id', $game['id'])
						->get()->row('ids');
					$system_ids = explode(',', $system_ids);
					shuffle($system_ids);

					$this->db->set('system_exclusive', $system_ids[0]);
				}

				shuffle($icons);

				# Create the achievement
				
				$this->db
					->set('added', 'NOW()', FALSE)
					->insert('achievements', array(
						'user_id' => $user_id,
						'game_id' => $game['id'],
						'name' => $name,
						'description' => $description,
						'icon' => $icons[0]
					));

				$achievement_id = $this->db->insert_id();
				
				shuffle($tags);
				$tmp_tags = $tags;
				$use_tags = array();
				foreach (range(0,rand(2,4)) as $i)
					$use_tags[] = array_pop($tmp_tags);

				# Create the Tags
				foreach ($use_tags as $ut)
				{
					$this->db
						->set('added', 'NOW()', FALSE)
						->insert('achievement_tags', array(
							'user_id' => $user_id,
							'achievement_id' => $achievement_id,
							'tag_id' => $ut,
							'approval' => 1
						));

					$at_id = $this->db->insert_id();

					$this->db
						->set('when', 'NOW()', FALSE)
						->insert('achievement_tag_log', array(
							'achievement_tag_id' => $at_id,
							'user_id' => $user_id,
							'approval' => 1
						));
				}

				shuffle($users);
				# Create achievers
				foreach (range(0, rand($count - 5, $count + 5)) as $i)
				{
					$this->db
						->set('achieved', 'NOW()', FALSE)
						->insert('achievement_users', array(
							'user_id' => $users[$i],
							'achievement_id' => $achievement_id
						));

					$this->db
						->set('achievement_tally', 'achievement_tally + 1', FALSE)
						->where('id', $users[$i])
						->update('users');
				}

				# Create Comments
				foreach (range(0, rand($count - 5, $count + 5)) as $i)
				{
					$comment = strtolower(random_string(rand(27,99), TRUE));

					// Space in the comment?  75% chance
					$j = 0;
					while (rand(0, 99) < 80 && $j++ < 12)
					{
						$space_goes = rand(3, strlen($comment) - 2);
						$comment = substr($comment, 0, $space_goes) . ' ' . substr($comment, $space_goes, strlen($comment));
					}

					$comment = ucwords($comment);

					$this->db
						->set('added', 'NOW()', FALSE)
						->insert('achievement_comments', array(
							'achievement_id' => $achievement_id,
							'user_id' => $user_id,
							'comment' => $comment
						));
				}

			}

		}

		redirect('admin');
	}

	/*****************
		AJAX CALLS
	******************/

	// Resolve something like: /admin/game/link/info/' + id,
	public function game($sub, $type, $id)
	{
		$this->_ajax_only();

		$args = func_get_args();
		$func = '_game_' . array_shift($args) . '_' . array_shift($args);
		
		if (method_exists($this, $func))
			call_user_func_array(array($this, $func), $args);
	}

	// /admin/game/link/info/<ID>
	private function _game_link_info($id = NULL)
	{
		$gl = $this->concept->load('game_links', $id);

		$data = $gl->get_all();
		$data['submitted_by_name'] = $gl->get_submitter_name();
		$data['approved_by_name'] = $data['approved_by'] ? $gl->get_approver_name() : 'N/A';
		$data['id'] = $id;
		$data['flags'] = $gl->get_flags();
		
		$this->_ajax_return($data);
	}

	// /admin/game/link/delete/<ID>
	private function _game_link_delete($id = NULL)
	{
		$gl = $this->concept->load('game_links', $id);
		$gl->delete();
	}

	// /admin/game/link/approve/<ID>
	private function _game_link_approve($id = NULL)
	{
		$gl = $this->concept->load('game_links', $id);
		$gl->approve($this->user->id);
	}

	// /admin/game/link/approve/<ID>
	private function _game_link_clear_flags($id = NULL)
	{
		$gl = $this->concept->load('game_links', $id);
		$gl->clear_flags($this->user->id);
	}

	// /admin/game/achievement/delete/<ID>
	private function _game_achievement_delete($id = NULL)
	{
		$this->load->model('Achievements_model', 'achievements');
		$a = $this->achievements->load($id);
		$a->allow_deletion();
		$a->delete();
	}

	public function user_list()
	{
		$this->_ajax_only();

		$this->load->model('Admin_model', 'admin');

		$users = $this->admin->user_list($this->input->get());

		// Special things for specific columns
		foreach ($users['aaData'] as &$row)
		{
			// Fields to remove and set to _data
			foreach (array('activated', 'banned', 'ban_reason', 'level', 'achievement_tally') as $var)
			{
				$this->_data[$var] = $row[$var];
				unset($row[$var]);
			}
			$this->_data['user_id'] = $row['id'];
			
			$row['actions'] = $this->_preview('admin/manage/_user_actions');

			// Remove keys from each row, that's how DataTables needs it
			$row = array_values($row);
		}
			

		$this->_ajax_return($users);
	}

}