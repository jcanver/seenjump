<?php

class MediaController extends \BaseController {

	public function getPoster()
	{
		$image_url     = Input::get('image_url');
		$media_title   = Input::get('media_title');
		$year          = Input::get('year');
		$new_image_url = $media_title . '-' . $year . '.jpg';
		
		copy($image_url, '/home/forge/seenjump.com/public/images/posters/' . $new_image_url);
	}

	public function search()
	{
		$input_title = Input::get('title');

		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'http://www.omdbapi.com/?apikey=40364d9f&s=' . urlencode($input_title),
		    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		// Send the request & save response to $resp
		$results = json_decode(curl_exec($curl));
		// Close request to clear up some resources
		curl_close($curl);

		return $this->searchResults($results->Search, $input_title);
	}

	/**
	 * Stores the posters for each search result and loads view
	 */
	public function searchResults($results, $input_title)
	{
		$i = 0;
		foreach ($results as $result) {
			if ( $result->Poster != 'N/A' ) {
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
				    CURLOPT_RETURNTRANSFER => 1,
				    CURLOPT_URL => 'http://www.omdbapi.com/?apikey=40364d9f&i=' . $result->imdbID,
				    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
				));
				// Send the request & save response to $resp
				$results[$i] = json_decode(curl_exec($curl));
				// Close request to clear up some resources
				curl_close($curl);

				$results[$i]->imdbVotes = (int)str_replace(',', '', $results[$i]->imdbVotes);

				if ( $results[$i]->imdbVotes > 2000) {
					$image_url = $result->Title . '-' . $result->Year . '.jpg';
					copy($result->Poster, '/home/forge/seenjump.com/public/images/posters/' . $image_url);
					$results[$i]->ImageUrl = $image_url;
				} else {
					unset($results[$i]);
				}
			} else {
				unset($results[$i]);
			}

			$i++;
		}

		// Get users
		$user = new User;
		$users = $user->search($input_title);

		$view_data['users'] 	  = $users;
		$view_data['results']     = $results;
		$view_data['input_title'] = $input_title;

		return View::make('search_results', $view_data);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $imdbId
	 * @return Response
	 */
	public function show($imdbId)
	{
		$user_id = Auth::id();

		// Media info
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'http://www.omdbapi.com/?apikey=40364d9f&i=' . $imdbId,
		    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		$media = json_decode(curl_exec($curl));
		curl_close($curl);

		$media->ImageUrl    = $media->Title . '-' . $media->Year . '.jpg';
		$view_data['media'] = $media;

		// User info
		$queue_items = DB::table('queue_items')
							->select('imdb_id')
							->where('user_id', '=', $user_id)
							->get();

		$view_data['queue_items'] = array();
		foreach ($queue_items as $queue_item) {
			$view_data['queue_items'][] = $queue_item->imdb_id;
		}

		// Posts
		$posts = DB::table('posts')
						->select('*')
						->where('imdb_id', '=', $imdbId)
						->get();

		$post  = new Post;
		$media = new Media;
		$like  = new Like;
		$tag   = new Tag;
		$user  = new User;

		foreach ($posts as $key => $comment) {
			$posts[$key]->num_likes = $like->getNumLikes($comment->id);
			$posts[$key]->name = $user->getUserName($comment->user_id);
			$posts[$key]->tags = $tag->getPostTags($comment->id);
			$posts[$key]->type = '<strong>comment</strong> and a <strong>rating</strong>';

			if ( $posts[$key]->comment == '' ) {
				$posts[$key]->type = '<strong>rating</strong>';
			}
			if ( sizeof($posts[$key]->rating) === 0 ) {
				$posts[$key]->type = '<strong>comment</strong>';
			}
			$likes_help = $like->getLikes($comment->id);
			
			$likes = array();
			foreach ($likes_help as $k => $v) {
				$likes[$v->user_id] = $v->fname . ' ' . $v->lname;
			}
			
			$posts[$key]->likes = $likes;
			$posts[$key]->post_date = date('M. d, Y', strtotime($posts[$key]->created_at));
		}
		$view_data['posts'] = $posts;
		// var_dump($view_data['posts']);exit;

		return View::make('media', $view_data);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
