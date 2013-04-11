<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Nick Wright
 * @created 11/20/2012
 */

class Migration_Startup_data extends CI_Migration
{

	public function up()
	{
		echo '<p>Creating startup data for OSA.</p>';

		$this->db->query("INSERT IGNORE INTO `developers` VALUES (1,'nintendo')");
		$this->db->query("INSERT IGNORE INTO `developers` VALUES (2,'sony')");
		$this->db->query("INSERT IGNORE INTO `developers` VALUES (3,'computer')");

		$this->db->query("INSERT IGNORE INTO `systems` VALUES (1,1,'Nintendo Entertainment System','nes','c',0)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (2,1,'Super Nintendo Entertainment System','snes','c',1)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (3,1,'Nintendo 64','n64','c',2)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (4,1,'Gameboy','gb','p',0)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (5,1,'Gameboy Color','gbc','p',1)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (6,1,'Gameboy Advance','gba','p',2)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (7,2,'Playstation (One)','psx','c',0)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (8,2,'Playstation 2','ps2','c',1)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (9,2,'Playstation Portable','psp','p',0)");
		$this->db->query("INSERT IGNORE INTO `systems` VALUES (10,3,'DosBox','dos','',0)");

		$this->db->query("INSERT IGNORE INTO `games` VALUES (3,'q','Q-Bert','q-bert',49,'Q-Bert')");
		$this->db->query("INSERT IGNORE INTO `games` VALUES (4,'p','Pokemon Red and Blue','pokemon-red-and-blue',48,'Pokemon_red_and_blue')");
		$this->db->query("INSERT IGNORE INTO `games` VALUES (5,'b','Blast Corps','blast-corps',49,'Blast_Corps')");
		$this->db->query("INSERT IGNORE INTO `games` VALUES (6,'f','Final Fantasy','final-fantasy',54,'Final_Fantasy_(video_game)')");
		$this->db->query("INSERT IGNORE INTO `games` VALUES (7,'f','Final Fantasy IV','final-fantasy-iv',48,'Final_Fantasy_IV')");
		$this->db->query("INSERT IGNORE INTO `games` VALUES (8,'f','Final Fantasy VII','final-fantasy-vii',48,'Final_Fantasy_VII')");
		$this->db->query("INSERT IGNORE INTO `games` VALUES (9,'f','Flying Dragon','flying-dragon',48,'Flying_Dragon')");

		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,3)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,6)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,70)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,72)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,75)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,83)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,91)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,96)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,99)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,110)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,120)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,121)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (1,128)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (2,7)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (2,81)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (2,104)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (2,111)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (2,114)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (2,116)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (2,125)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (2,128)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,5)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,9)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,76)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,78)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,85)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,86)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,94)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,102)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,109)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,115)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,117)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,123)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (3,127)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,4)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,71)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,73)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,83)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,86)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,91)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,93)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,106)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,107)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,112)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,114)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (4,124)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (5,79)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (5,95)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (5,101)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (5,104)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (5,113)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,6)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,74)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,77)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,90)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,97)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,103)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,105)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,111)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,118)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,122)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (6,126)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,6)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,8)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,82)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,84)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,86)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,92)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,104)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,105)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,125)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (7,128)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (8,89)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (8,128)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (9,6)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (9,82)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (9,88)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (9,100)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (9,111)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (9,119)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (9,124)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (9,129)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (10,80)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (10,87)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (10,98)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (10,108)");
		$this->db->query("INSERT IGNORE INTO `system_games` VALUES (10,118)");

		$this->db->query("INSERT IGNORE INTO `tags` VALUES (1,'completionist',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (2,'collecting',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (3,'grinding',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (4,'unlocking',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (5,'exploration',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (6,'death',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (7,'quick',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (8,'time consuming',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (9,'kills',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (10,'time trial',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (11,'multiplayer',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (12,'points',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (13,'fail',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (14,'survial',1,1)");
		$this->db->query("INSERT IGNORE INTO `tags` VALUES (15,'minimalist',1,1)");

		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Animal',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Weapon',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Fire',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Food',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Action',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Liquid',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Symbol',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Skull',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Plant',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Body',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Heart',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Tool',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Arrow',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Sea',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Stone',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Eye',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Machine',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Sword',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Face',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Chemical',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Hand',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Shield',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Desk',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Space',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Light',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Building',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Mammal',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Drop',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Creature',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Bird',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Bottle',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Death',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Head',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Wing',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Lightning',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Bone',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Trap',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Book',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('State',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Reptile',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Lock',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Bolt',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Tree',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Cross',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Blade',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Jewellery',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Sky',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Metal',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Jaw',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Wound',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Bomb',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Boot',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Shot',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Glass',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Explosion',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Mouth',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Mask',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Target',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Claw',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Mineral',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Insect',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Emblem',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Ice',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Poison',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Nature',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Ball',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Wood',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Armor',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Axe',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Vehicle',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Crack',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Goo',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Music',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Kitchenware',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Blood',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Monster',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Shell',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Life',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Fruit',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Earth',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Crown',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Mood',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Spike',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Sound',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Tentacle',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Dagger',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Smoke',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Attack',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Toy',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Clothing',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Snow',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Helm',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Forecast',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Knife',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Slash',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Time',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Spear',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Sun',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Fish',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Shout',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Board',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Steel',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Snowflake',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Tube',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Sport',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Flag',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Cloud',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Egypt',1)");
		$this->db->query("INSERT INTO `tags` (`name`, `approved`) VALUES ('Hat',1)");

		echo '<p>DONE!</p>';
	}

	public function down()
	{
		echo '<p>Warning: Startup Data has no "Down", it would involve truncating the following tables: developers, systems, games, system_games, tags.</p>';
		echo '<p>DONE</p>';
	}

}