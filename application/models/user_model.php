<?php

class User_model extends OSA_Concept
{
	public 
		$is_logged = FALSE,
		$acl = NULL;

	public 
		$_table = 'users';

	public function __construct($db, &$CI)
	{
		parent::__construct($db);

		$this->CI = $CI;
	}

	public function _hash($string)
	{
		return md5($string);
	}

	public function set_id($id = NULL)
	{
		if ($id)
		{
			if ( ! is_numeric($id))
				$id = $this->find_id_from_username($id);

			if ($id)
			{
				$this->id = $id;
				$this->is_logged = TRUE;
			}
		}
	}

	public function find_id_from_username($username)
	{
		return $this->db
			->select('id')
			->from('users')
			->where('username', $username)
			->get()->row('id');
	}

	/**
	 * Notes about ACL [Access Control Level]
	 * 1 == Admin
	 * 9 == Moderator
	 */

	/**
	 * Is the user an Administrator?
	 */
	public function is_admin()
	{
		$this->get_acl('admin');
		return (bool) $this->acl == 1;
	}

	/**
	 * Is the user a Moderator?
	 * If they're an administrator: yes
	 */
	public function is_moderator()
	{
		$this->get_acl('mod');
		return (bool) in_array($this->acl, array(1,9));
	}

	/**
	 * Get ACL 
	 * @param string $type >> 'admin' || 'mod'
	 */
	public function get_acl($type = 'admin')
	{
		if ( ! $this->acl) 
			$this->acl = (bool) $this->db
				->select('COUNT(*) AS `count`', FALSE)
				->from('user_acl')
				->where('uid', $this->id)
				->where('key', md5($this->CI->config->item('encryption_key')))
				->get()->row('count');

		return $this->acl;
	}

	public function login($username, $password)
	{
		$result = $this->db
			->select('id, achievement_tally')
			->from('users')
			->where('username', $username)
			->where('password', $this->_hash($password))
			->get()->first_row();

		if (isset($result->id))
		{
			# Set the user id
			$this->set_id($result->id);
			
			# Update last_login
			$this->db
				->set('last_login', 'NOW()', FALSE)
				->where('id', $this->id)
				->update('users');

			$this->CI->session->set_userdata(array(
				'user_id' => $this->id,
				'tally' => $result->achievement_tally
			));

			$this->is_logged = TRUE;
			
			return true;
		}
		else
		{
			$this->logout();
			return false;
		}
	}

	public function logout()
	{
		$this->id = 0;

		$this->CI->session->set_userdata(array(
			'user_id' => 0
		));

		$this->is_logged = FALSE;
	}

	public function register($email, $username, $password)
	{
		// CI already checked for unique
		$this->db
			->set('created', 'NOW()', FALSE)
			->insert('users', array(
				'email' => $email,
				'username' => $username,
				'password' => $this->_hash($password)
			));

		$this->login($username, $password);
	}

	public function auto_reset_password($email_or_username)
	{
		# Determine which, email or username?
		$field = preg_match('/@/', $email_or_username) ? 'email' : 'username';

		$result = $this->db
			->select('id')
			->from('users')
			->where($field, $email_or_username)
			->get()->first_row();

		if (isset($result->id))
		{
			# Generate Random String for new password
			$this->CI->load->helper('string');

			$new_password = random_string('alnum', 9);

			# Update database with new password
			$this->db
				->set('password', $this->_hash($new_password))
				->where('id', $result->id)
				->update('users');

			# Send Confirmation
			$this->CI->load->library('email');
			$this->CI->email
				->from('osa@gmail.com', 'Old School Achievements')
				->to($email)
				->subject('Your New Password')
				->message('Your new password is: <strong><code>' . $new_password . '</code></strong>')
				->send();

			return TRUE;
		}
	}

}