<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Nick Wright
 * @created 11/20/2012
 */

class Migration_More_startup_data extends CI_Migration
{

	public function up()
	{
		echo '<p>Creating more startup data for OSA.</p>';

		$this->db->query("INSERT IGNORE INTO `flag_sections` VALUES (1,'game')");
		$this->db->query("INSERT IGNORE INTO `flag_sections` VALUES (2,'game_link')");
		$this->db->query("INSERT IGNORE INTO `flag_sections` VALUES (3,'achievement')");
		$this->db->query("INSERT IGNORE INTO `flag_sections` VALUES (4,'achievement_comment')");
		$this->db->query("INSERT IGNORE INTO `flag_sections` VALUES (5,'achievement_comment_lock')");
		
		echo '<p>Creating the admin account</p>';

		// Randomize the username/password.  This is hosted on Github after all.
		$username = random_string(rand(5,15), TRUE);
		$password = random_string(rand(9,20));

		$this->db
			->set('created', 'NOW()', FALSE)
			->insert('users', array(
				'username' => $username,
				'password' => md5($password),
				'email' => 'tickthokk+' . $username . '@gmail.com',
				'activated' => 1
			));

		$uid = $this->db->insert_id();
		
		echo '<h1>Attention! Admin username and password to follow:</h1>';
		echo '<h2>' . $username . '</h2>';
		echo '<h2>' . $password . '</h2>';

		$this->db->insert('user_acl', array(
			'uid' => $uid,
			'level' => 1,
			'key' => md5($this->config->item('encryption_key'))
		));

		echo '<p>DONE!</p>';
	}

	public function down()
	{
		echo '<p>Warning: Startup Data has no "Down", it would involve truncating the following tables: flag_sections.</p>';
		echo '<p>Also, the creation of the Admin account</p>';
		echo '<p>DONE</p>';
	}

}