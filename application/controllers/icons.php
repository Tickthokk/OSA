<?php

class Icons extends OSA_Controller
{

	public function chooser()
	{
		# Models
		$this->load->model('Icons_model', 'icons');
		
		# All Icons
		$icons = $this->icons->get_all();

		# All Icon Tags
		$tags = array();
		foreach ($icons as $i)
			foreach (explode(',', $i['tags']) as $t)
				if ($t)
					@$tags[$t]++;

		arsort($tags);
		
		$this->_data['icons'] = $icons;
		$this->_data['tags'] = $tags;

		$this->_ajax_return(array(
			'html' => $this->_preview('icons/chooser'),
			'icons' => $icons,
			'tags' => $tags
		));
	}
	
}