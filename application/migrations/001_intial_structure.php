<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Nick Wright
 * @created 11/20/2012
 */

class Migration_Intial_structure extends CI_Migration
{

	public function up()
	{
		echo '<p>Creating initial database structure for OSA.</p>';

		$this->db->query("
			CREATE TABLE `achievements` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`game_id` int(10) unsigned NOT NULL,
				`added_by` int(10) NOT NULL COMMENT 'User who created achievement',
				`added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`modified_by` int(10) unsigned DEFAULT NULL,
				`modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
				`name` varchar(255) NOT NULL,
				`description` text NOT NULL,
				`system_exclusive` smallint(5) unsigned DEFAULT NULL,
				`icon` varchar(45) NOT NULL DEFAULT 'royal/compass.png',
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `user_acl` (
				`uid` int(10) unsigned NOT NULL,
				`level` tinyint(1) unsigned NOT NULL,
				`key` char(32) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
				PRIMARY KEY (`uid`)
			);
		");

		$this->db->query("
			CREATE TABLE `users` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`username` varchar(50) COLLATE utf8_bin NOT NULL,
				`password` varchar(255) COLLATE utf8_bin NOT NULL,
				`email` varchar(100) COLLATE utf8_bin NOT NULL,
				`activated` tinyint(1) NOT NULL DEFAULT '1',
				`banned` tinyint(1) NOT NULL DEFAULT '0',
				`ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
				`new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
				`new_password_requested` datetime DEFAULT NULL,
				`new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
				`new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
				`last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
				`last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`achievement_tally` smallint(5) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `system_games` (
				`system_id` smallint(5) unsigned NOT NULL,
				`game_id` int(10) unsigned NOT NULL,
				PRIMARY KEY (`system_id`,`game_id`)
			) COMMENT='Games can appear on multiple systems.';
		");

		$this->db->query("
			CREATE TABLE `flag_sections` (
				`id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(45) NOT NULL,
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `achievement_comments` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`achievement_id` int(10) unsigned NOT NULL,
				`added_by` int(10) unsigned NOT NULL COMMENT 'User who commented',
				`added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`modified_by` int(10) DEFAULT NULL,
				`modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
				`comment` text NOT NULL,
				PRIMARY KEY (`id`),
				KEY `uid` (`added_by`),
				KEY `aid` (`achievement_id`)
			);
		");

		$this->db->query("
			CREATE TABLE `user_autologin` (
				`key_id` char(32) COLLATE utf8_bin NOT NULL,
				`user_id` int(11) NOT NULL DEFAULT '0',
				`user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
				`last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
				`last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`key_id`,`user_id`)
			);
		");

		$this->db->query("
			CREATE TABLE `user_profiles` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`user_id` int(11) NOT NULL,
				`country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
				`website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `log` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`text` tinytext NOT NULL,
				`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `tags` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(15) NOT NULL,
				`default` tinyint(1) unsigned DEFAULT NULL,
				`approved` tinyint(1) unsigned DEFAULT NULL COMMENT 'has admin approval',
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `login_attempts` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
				`login` varchar(50) COLLATE utf8_bin NOT NULL,
				`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `achievement_tag_log` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`achievement_tag_id` int(10) unsigned NOT NULL,
				`user_id` int(10) unsigned NOT NULL,
				`approval` tinyint(2) NOT NULL DEFAULT '1' COMMENT '-1 for disapproval, 1 for approval',
				`when` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `atid` (`achievement_tag_id`),
				KEY `uid` (`user_id`)
			);
		");

		$this->db->query("
			CREATE TABLE `achievement_users` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(10) unsigned NOT NULL,
				`achievement_id` int(10) unsigned NOT NULL,
				`achieved` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `uid` (`user_id`),
				KEY `aid` (`achievement_id`)
			);
		");

		$this->db->query("
			CREATE TABLE `game_links` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`game_id` int(10) unsigned NOT NULL,
				`site` varchar(45) NOT NULL,
				`url` varchar(255) NOT NULL,
				`submitted` datetime NOT NULL,
				`submitted_by` int(10) unsigned NOT NULL,
				`approved` datetime DEFAULT NULL,
				`approved_by` int(10) unsigned DEFAULT NULL COMMENT 'FK User ID',
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `achievement_tags` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(10) unsigned NOT NULL COMMENT 'User who created the tag',
				`added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`achievement_id` int(10) unsigned NOT NULL,
				`tag_id` int(10) unsigned NOT NULL,
				`approval` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Quick Reference Tally',
				PRIMARY KEY (`id`),
				KEY `aid` (`achievement_id`),
				KEY `uid` (`user_id`),
				KEY `tag` (`tag_id`)
			);
		");

		$this->db->query("
			CREATE TABLE `flags` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`section_id` smallint(3) unsigned NOT NULL COMMENT 'game / achievement / gamelink',
				`table_id` int(10) unsigned NOT NULL COMMENT 'FK games, achievements, etc, based on section',
				`submitter` int(10) unsigned DEFAULT NULL COMMENT 'FK User ID',
				`submitter_ip` int(10) unsigned NOT NULL COMMENT 'IP Address stored via INET_ATON.	Pull using INET_NTOA',
				`created` datetime NOT NULL,
				`reason` tinytext NOT NULL,
				`solved` datetime DEFAULT NULL,
				`solved_by` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `subid` (`submitter_ip`),
				KEY `solid` (`solved_by`),
				KEY `sec` (`section_id`,`table_id`)
			);
		");

		$this->db->query("
			CREATE TABLE `systems` (
				`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
				`developer_id` smallint(5) unsigned NOT NULL DEFAULT '0',
				`name` varchar(45) DEFAULT NULL,
				`slug` varchar(45) NOT NULL,
				`type` enum('c','p','') NOT NULL DEFAULT 'c',
				`rank` tinyint(1) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			) COMMENT='Defienes console and portable systems of developers';
		");

		$this->db->query("
			CREATE TABLE `ci_sessions` (
				`session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
				`ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
				`user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
				`last_activity` int(10) unsigned NOT NULL DEFAULT '0',
				`user_data` text COLLATE utf8_bin NOT NULL,
				PRIMARY KEY (`session_id`)
			);
		");

		$this->db->query("
			CREATE TABLE `games` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`first_letter` char(1) NOT NULL,
				`name` varchar(45) NOT NULL,
				`slug` varchar(45) NOT NULL,
				`achievement_tally` tinyint(3) unsigned NOT NULL,
				`wiki_slug` varchar(99) DEFAULT NULL,
				PRIMARY KEY (`id`)
			);
		");

		$this->db->query("
			CREATE TABLE `developers` (
				`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
				`slug` varchar(30) NOT NULL,
				PRIMARY KEY (`id`)
			) COMMENT='Defines Manufacturers and Developers';
		");
		
		echo 'DONE!</p>';
	}

	public function down()
	{
		echo '<p>Deleting the initial database structure for OSA.</p>';
		
		echo '<p>Dropping tons of tables.. ';
		
		$this->dbforge->drop_table('achievements');
		$this->dbforge->drop_table('user_acl');
		$this->dbforge->drop_table('users');
		$this->dbforge->drop_table('system_games');
		$this->dbforge->drop_table('flag_sections');
		$this->dbforge->drop_table('achievement_comments');
		$this->dbforge->drop_table('user_autologin');
		$this->dbforge->drop_table('user_profiles');
		$this->dbforge->drop_table('log');
		$this->dbforge->drop_table('tags');
		$this->dbforge->drop_table('login_attempts');
		$this->dbforge->drop_table('achievement_tag_log');
		$this->dbforge->drop_table('achievement_users');
		$this->dbforge->drop_table('game_links');
		$this->dbforge->drop_table('achievement_tags');
		$this->dbforge->drop_table('flags');
		$this->dbforge->drop_table('systems');
		$this->dbforge->drop_table('ci_sessions');
		$this->dbforge->drop_table('games');
		$this->dbforge->drop_table('developers');
		
		echo 'DONE!</p>';
	}

}