<?php
/**
 * Retrieve Data from Wikipedia
 */

class Wiki_model extends CI_Model
{
	public
		$user_agent = 'User-Agent: OSADataGrabber (+http://oldschoolachievements.com/OSADataGrabber/)',
		$wiki_base = 'http://en.m.wikipedia.org/wiki/',
		$base_image_path = NULL;

	public function __construct()
	{
		parent::__construct();
		
		$this->base_image_path = FCPATH . 'assets/images/cache/originals/';

	}

	public function image_grab($slug, $wiki_slug)
	{
		$wikiLink = $this->wiki_base . $wiki_slug;

		# Use CURL to get the HTML of the wikipedia page.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $wikiLink);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		$result = curl_exec($ch);

		# Load that HTML into the DOM parser
		$dom = new DOMDocument();
		@$dom->loadHTML($result);
		$finder = new DomXPath($dom);

		// Get the Image
		$image = $finder->query("//table[@class='infobox hproduct']/tr[2]/td/a[@class='image']/img[1]/@src")->item(0)->nodeValue;

		# Get the extension from the src
		preg_match('/\.(\w{3})$/', $image, $matches);
		$ext = $matches[1];
		$filename = $slug . '.' . $ext;
		
		$content = file_get_contents('http:' . $image);

		# Save the file
		file_put_contents($this->base_image_path . $filename, $content);
		
		return $filename;
	}
	
	/*public function wikipedia_data_grab($slug, $wiki_slug)
	{

		# TEMP
		$wiki_slug = 'Demon%27s_Souls';

		// Use the Mobile version for easier parsing:
		// http://en.m.wikipedia.org/wiki/Final_Fantasy_(video_game)

		$wikiLink = $this->wiki_base . $wiki_slug;

		# Use CURL to get the HTML of the wikipedia page.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $wikiLink);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		$result = curl_exec($ch);

		# Load that HTML into the DOM parser
		$dom = new DOMDocument();
		@$dom->loadHTML($result);
		$finder = new DomXPath($dom);

		// Get the Image
		$image = $finder->query("//table[@class='infobox hproduct']/tr[2]/td/a[@class='image']/img[1]/@src")->item(0)->nodeValue;

		// Grab and save Image
		$this->image_grab($image, $slug);

		$cells = $finder->query("//table[@class='infobox hproduct']/tr/td");

		$data = array();

		for ($i = 0; $i <= $cells->length - 1; $i++)
		{
			$value = $cells->item($i)->nodeValue;

			// Developers
			if (preg_match('/^Developer/', $value))
			{
				$value = array_shift(array_diff(explode("\n", $cells->item(++$i)->nodeValue), array('')));

				$data['developer'] = $value;
			}
			elseif (preg_match('/^Publisher/', $value))
			{
				$value = $cells->item(++$i)->nodeValue;

				echo '<pre>';
				echo $value;
				exit;

				$value = array_shift(array_diff(explode("\n", $value), array('')));

				$data['publisher'] = $value;

			}
			elseif (preg_match('/^Release/', $value))
			{
				$value = array_shift(array_diff(explode("\n", $cells->item(++$i)->nodeValue), array('')));

				$data['release'] = $value;
			}
			elseif (preg_match('/^Genre/', $value))
			{
				$value = array_shift(array_diff(explode("\n", $cells->item(++$i)->nodeValue), array('')));

				$data['genre'] = $value;
			}
			elseif (preg_match('/^Mode/', $value))
			{
				$value = array_shift(array_diff(explode("\n", $cells->item(++$i)->nodeValue), array('')));

				$data['modes'] = explode(', ', $value);
			}




		}

		var_dump($data);
		exit;



	}*/

	

	/*public function wikipedia_image_grab($name, $slug)
	{
		# Helpers
		$this->load->helper('url');

		# wikipedia articles Are_Like_(this)
		# I won't be able to replicate the (this), but I can at least do the_like_this.
		$url = 'http://en.wikipedia.org/wiki/' . url_title($name, 'underscore');
		# Wikipedia also demands a user agent to be set.

		# Use CURL to get the HTML of the wikipedia page.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		$result = curl_exec($ch);

		# Load that HTML into the DOM parser
		$dom = new DOMDocument();
		$dom->loadHTML($result);
		# Get all the images in the dom
		$wiki_images = $dom->getElementsByTagName('img');
		# Get the first image
		$src = $wiki_images->item(0)->getAttribute('src');

		return $this->image_grab($src);
	}*/

}