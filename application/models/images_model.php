<?php

class Images_model extends CI_Model
{

	public $base_file_path, $base_config; 
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->base_file_path = FCPATH . 'assets/images/cache/originals/';
		$this->base_config = array(
			'image_library' => 'gd2',
			'maintain_ratio' => TRUE,
			'create_thumb' => TRUE
		);
	}

	# The Plan
	# Take the Slug, remove dashes.
	# Search wikipedia, grab the dom.
	# Take the image (dom crawling?)
	# save a local version (assets/images/cache/originals)
	# Reduce the image size to the desired $width and $height
	# Save that in assets/images/cache/<width>/<height>/slugname.extension

	public function get_path($name, $slug)
	{
		$image_path = $this->does_image_exist($slug);
		if ( ! $image_path )
		{
			$image_path = $this->wikipedia_grab($name, $slug);
		}
		return $this->base_file_path . $image_path;
	}

	public function does_image_exist($slug)
	{
		# Helpers
		$this->load->helper('file');

		$filename_list = get_filenames($this->base_file_path);
		$filename = null;
		foreach ($filename_list as $fnl)
		{
			$a = explode('.', $fnl);
			if ($a[0] == $slug)
				$filename = $fnl;
		}

		return $filename ?: false;
	}

	public function wikipedia_grab($name, $slug)
	{
		# Helpers
		$this->load->helper('url');

		# wikipedia articles Are_Like_(this)
		# I won't be able to replicate the (this), but I can at least do the_like_this.
		$url = 'http://en.wikipedia.org/wiki/' . url_title($name, 'underscore');
		# Wikipedia also demands a user agent to be set.
		$user_agent = 'User-Agent: OSAImageGrabber (+http://oldschoolachievements.com/OSAImageGrabber/)';

		# Use CURL to get the HTML of the wikipedia page.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		$result = curl_exec($ch);

		# Load that HTML into the DOM parser
		$dom = new DOMDocument();
		$dom->loadHTML($result);
		# Get all the images in the dom
		$wiki_images = $dom->getElementsByTagName('img');
		# Get the first image
		$src = $wiki_images->item(0)->getAttribute('src');

		# No image?  Just return.
		# TODO make default "no image" image.
		if (empty($src))
			return false;

		# Get the extension from the src
		preg_match('/\.(\w\w\w)$/', $src, $matches);
		$ext = $matches[1];
		$filename = $slug . '.' . $ext;
		
		# Save the file
		$content = file_get_contents('http:' . $src);
		file_put_contents($this->base_file_path . $filename, $content);
		
		return $filename;
	}
}