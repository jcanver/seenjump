<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Post extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	// protected $hidden = array('password', 'remember_token');

	public function user()
	{
		return $this->hasOne('User');
	}

	public function getFeed()
	{
		return DB::table('posts')
		            ->join('users', 'users.id', '=', 'posts.user_id')
		            ->select('users.fname', 'users.lname', 'posts.*')
		            ->orderBy('created_at', 'desc')
		            ->get();
	}

	public function getUserComments($user_id)
	{
		return DB::table('posts')
					->where('comment', '!=', '')
					->where('user_id', '=', $user_id)
					->get();
	}

	public function getUserRatings($user_id)
	{
		return DB::table('posts')
					->where('rating', '!=', 'NULL')
					->where('user_id', '=', $user_id)
					->get();
	}

	public function deletePost($post_id)
	{
		DB::table('likes')
				->where('post_id', '=', $post_id)
				->delete();

		DB::table('tags')
				->where('post_id', '=', $post_id)
				->delete();

		$post = Post::find($post_id);

		if ( !$post->delete() )
		{
			return FALSE;
		}

		return TRUE;
	}

	

}
