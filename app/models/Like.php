<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Like extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'likes';

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
		            ->get();
	}

	public function getNumLikes($post_id)
	{
		return DB::table('likes')
					->where('post_id', '=', $post_id)
					->count();
	}

	public function getLikes($post_id)
	{
		return DB::table('likes')
					->select('likes.user_id', 'users.fname', 'users.lname')
					->join('users', 'users.id', '=', 'likes.user_id')
					->where('post_id', '=', $post_id)
					->get();
	}

	public function unlikePost($user_id, $post_id)
	{
		DB::table('likes')
			->where('user_id', '=', $user_id)
			->where('post_id', '=', $post_id)
			->delete();
	}

}
