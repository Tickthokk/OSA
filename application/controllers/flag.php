<?php

class Flag extends OSA_Controller
{
	
	/**
	 * /flag/game/6
	 * /flag/gamelink/227
	 * /flag/achievement/2832
	 * @todo Model-ize this.  It works for now, but it goes against theory to have database stuff in a controller.
	 */
	public function run($what, $id)
	{
		// Method only available via Ajax calls
		$this->_ajax_only();

		$allowedWhat = array(
			'game', 'gamelink', 'acheivement'
		);

		if ( ! in_array($what, $allowedWhat))
			$this->_ajax_error('Section not recognized.');

		if ($this->user->id)
			$this->db->set('submitter', $this->user->id);

		$this->db
			->set('submitterIP', 'INET_ATON("' . $this->session->userdata('ip_address') . '")', FALSE)
			->set('created', 'NOW()', FALSE)
			->insert('flags', array(
				'section' => $what,
				'sectionId' => (int) $id,
				'reason' => $this->input->post('reason') ?: ''
			));
	}

}