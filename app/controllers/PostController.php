<?php

class PostController extends \BaseController {

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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$user_id = Auth::id();
		$comment = Input::get('comment');
		$tags    = Input::get('tagged_uid');
		$rating  = Input::get('rating');
		$type    = Input::get('media_type');
		$imdb_id = Input::get('imdb_id');

		$post = new Post;
		$post->user_id = $user_id;
		$post->type    = $type;
		$post->imdb_id = $imdb_id;
		$post->rating  = $rating;
		$post->comment = $comment;
		$post->save();

		if ( sizeof($tags) > 0 ) {
			foreach ($tags as $tagged_uid) {
				$tag = new Tag;
				$tag->post_id      = $post->id;
				$tag->user_tagging = $user_id;
				$tag->user_tagged  = $tagged_uid;
				$tag->save();
			}
		}

		return Redirect::to('/media/'. $imdb_id);
	}

	public function deletePost($post_id)
	{
		$post = new Post;

		$success = $post->deletePost($post_id);

		return json_encode($success);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
