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

		$section_id = $this->db
			->select('id')
			->from('flag_sections')
			->where('name', $what)
			->get()->row('id');

		if ( ! is_numeric($section_id))
			$this->_ajax_error('Section not recognized');
		
		if ($this->user->id)
			$this->db->set('submitter', $this->user->id);

		$this->db
			->set('submitter_ip', 'INET_ATON("' . $this->session->userdata('ip_address') . '")', FALSE)
			->set('created', 'NOW()', FALSE)
			->insert('flags', array(
				'section_id' => (int) $section_id,
				'table_id' => (int) $id,
				'reason' => $this->input->post('reason') ?: ''
			));
	}

}