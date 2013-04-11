<?php

class ImageControl {

	public 
		$cache_path,
		$originals_path,
		$config = array(
			'image_library' => 'gd2',
			'maintain_ratio' => TRUE,
			'create_thumb' => TRUE
		);

	public 
		$user_agent = 'User-Agent: OSADataGrabber (+http://oldschoolachievements.com/OSADataGrabber/)',
		$wiki_base = 'http://en.m.wikipedia.org/wiki/';

	public function __construct()
	{
		$this->cache_path = path('storage') . 'cache' . DS . 'images' . DS . 'games' . DS;
		$this->originals_path = $this->cache_path . 'originals' . DS;
	}

	// The Plan
	// Take the Slug, remove dashes.
	// Search wikipedia, grab the dom.
	// Take the image (dom crawling?)
	// save a local version (assets/images/cache/originals)
	// Reduce the image size to the desired $width and $height
	// Save that in assets/images/cache/<width>/<height>/slugname.extension

	public function get_path($slug, $wiki_slug)
	{
		$filename = $this->does_image_exist($slug);
		if ( ! $filename)
			$filename = $this->image_grab($slug, $wiki_slug);

		return $this->originals_path . $filename;
	}

	public function does_image_exist($slug)
	{
		// Look for a JPG, GIF or PNG
		foreach (array('jpg', 'gif', 'png') as $ext)
			if (file_exists($this->originals_path . $slug . '.' . $ext))
				return $filename . '.' . $ext;

		return FALSE;
	}

	public function grab_original($slug, $wiki_slug)
	{
		if ( ! $wiki_slug)
			return FALSE;

		$wikiLink = $this->wiki_base . $wiki_slug;

		// if ($this->firewall)
		// {
		// 	$image = '//placehold.it/' . rand(400,500);
		// 	$ext = 'gif';
		// }
		// else
		// {
			// Use CURL to get the HTML of the wikipedia page.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $wikiLink);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
			$result = curl_exec($ch);

			// Load that HTML into the DOM parser
			$dom = new DOMDocument();
			@$dom->loadHTML($result);
			$finder = new DomXPath($dom);

			// Get the Image
			$image = $finder->query("//table[@class='infobox hproduct']/tr[2]/td/a[@class='image']/img[1]/@src")->item(0)->nodeValue;

			// Get the extension from the src
			preg_match('/\.(\w{3,4})$/', $image, $matches);
			$ext = $matches[1];
		// }

		$filename = strtolower($slug . '.' . $ext);
			
		$content = file_get_contents('http:' . $image);

		// Save the file
		file_put_contents($this->originals_path . $filename, $content);
		
		return $filename;
	}

	public function get_cache_path()
	{

	}

}