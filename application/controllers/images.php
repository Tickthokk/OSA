<?php

class Images extends OSA_Controller
{
	
	public function view($for, $game_id, $width = 199, $height = 180)
	{
		# Helpers and Models
		$this->load->helper('file');
		$this->load->library('image_lib');
		$this->load->model('Games_model', 'games');
		$this->load->model('Images_model', 'images');

		$this->game = $this->games->load($game_id);

		$this->images->base_file_path .= $for . '/';

		# Defaults
		if ($width < 1) $width = 260;
		if ($height < 1) $height = 180;
		$width = (int) $width;  
		$height = (int) $height;

		/*if ($this->firewall)
		{
		#############
		# TEMPORARY #
		#############
		# Just to avoid unnecessary traffic to wikipedia
		header('Content-Type: image/gif');
		readfile('http://placehold.it/' . $width . 'x' . $height);
		exit;
		##############
		# /TEMPORARY #
		##############
		}*/

		# Get the file path
		$path = $this->images->get_path($this->game->slug, $this->game->wiki_slug);
		
		# Prep configuration for image resizer
		$config = array_merge($this->images->base_config, array(
			'source_image' => $path,
			'new_image' => $this->images->base_file_path,
			'thumb_marker' => '_' . $width . 'x' . $height . '_thumb',
			'width' => $width,
			'height' => $height
		));

		$this->image_lib->initialize($config); 
		
		$this->image_lib->resize();
		
		$dest_path = preg_replace('/^(.*)\//', '', $this->image_lib->full_dst_path);

		$fullpath = $this->images->base_file_path . $dest_path;

		$mime = get_mime_by_extension($fullpath);
		
		header('Content-Type: ' . $mime);
		readfile($fullpath);

		exit;
	}

	private function _game($slug)
	{
		# Models
		$this->load->model('Games_model', 'games');

		$name = $this->games->get_name($slug);

		if ($name === FALSE)
			exit;
		
		return $name;
	}
}