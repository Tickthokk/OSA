<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Migrations
 *
 * An open source utility for Code Igniter inspired by Ruby on Rails
 *
 * @package		Migrations
 * @author		Matías Montes
 */

// ------------------------------------------------------------------------

/**
 * Migrate Class
 *
 * Utility main controller.
 *
 * @package		Migrations
 * @author		Matías Montes
 */
class Migrate extends CI_Controller {

	public function index()
	{
		$this->load->library('migration');

		if ( ! $this->migration->current())
			show_error($this->migration->error_string());
	}

	/*public function reset()
	{
		$this->load->library('migration');

		if ( ! $this->migration->version('0'))
			show_error($this->migration->error_string());

		exit;
	}*/

}