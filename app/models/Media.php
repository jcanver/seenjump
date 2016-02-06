<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Media extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = '';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	// protected $hidden = array('password', 'remember_token');

	public function getMediaFromAPI($imdb_id)
	{
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'http://www.omdbapi.com/?apikey=40364d9f&tomatoes=true&i=' . $imdb_id,
		    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		// Send the request & save response to $resp
		$result = json_decode(curl_exec($curl));
		// Close request to clear up some resources
		curl_close($curl);

		$result->ImageUrl = $this->getPosterFrom($result->Poster, $result->Title, $result->Year);

		return $result;
	}

	public function getPosterFrom($ext_url, $title, $year, $env = 'prod')
	{
		$image_url = $title . '-' . $year . '.jpg';

		if ($env == 'prod') {
			copy($ext_url, '/home/forge/seenjump.com/public/images/posters' . $image_url);
			//copy($ext_url, '/var/chroot/home/content/03/9619003/html/seenjump/public/images/posters/' . $image_url);
		} else {
			copy($ext_url, '/home/vagrant/Code/sites/seenjump/public/images/posters/' . $image_url);
		}
		
		return $image_url;
	}

}
