<?php

class Images extends OSA_Controller
{
	
	public function view($for, $slug, $width = 199, $height = 180)
	{
		# Helpers and Models
		$this->load->helper('file');
		$this->load->library('image_lib');
		$this->load->model('Images_model', 'images');

		# Defaults
		if ($width < 1) $width = 260;
		if ($height < 1) $height = 180;
		$width = (int) $width;  
		$height = (int) $height;


		# Get the name from the slug
		$name = $this->{'_' . $for}($slug);

		# Get the file path
		$path = $this->images->get_path($name, $slug);

		# Prep configuration for image resizer
		$config = array_merge($this->images->base_config, array(
			'source_image' => $path,
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