<?php

class QueueController extends \BaseController {

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
	public function store($imdb_id)
	{
		$user_id = Auth::id();

		$queue_item = new QueueItem;
		$queue_item->user_id = $user_id;
		$queue_item->imdb_id = $imdb_id;
		$queue_item->watched = 'N';
		$queue_item->save();
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$user_id = Auth::id();
		$media_model = new Media;

		$queue_items = QueueItem::where('user_id', '=', $user_id)->get()->toArray();

		foreach ($queue_items as $key => $queue_item) {
			$queue_items[$key] = $media_model->getMediaFromAPI($queue_item['imdb_id']);
		}
		
		$view_data['queue_items']   = $queue_items;
		$view_data['side_nav_page'] = 'queue';
		
		

		return View::make('queue', $view_data);
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
	public function destroy($imdb_id)
	{
		$user_id = Auth::id();

		DB::table('queue_items')
					->where('user_id', '=', $user_id)
					->where('imdb_id', '=', $imdb_id)
					->delete();
	}


}
