<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Follow extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'follows';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	// protected $hidden = array('password', 'remember_token');

	public function isFollowing($user_id, $followed_user_id)
	{
		$query = DB::table('follows')
						->where('user_following', '=', $user_id)
						->where('user_followed', '=', $followed_user_id)
						->get();
		if ( sizeof($query) > 0 ) {
			return TRUE;
		}

		return FALSE;
	}

	public function unfollow($user_id, $followed_user_id)
	{
		DB::table('follows')
			->where('user_following', '=', $user_id)
			->where('user_followed', '=', $followed_user_id)
			->delete();
	}

	public function getNumFollowers($user_id)
    {
    	return DB::table('follows')
					->where('user_followed', '=', $user_id)
					->count();
    }

    public function getFollowers($user_id)
    {
    	return DB::table('follows')
    				->select('follows.user_following AS user_id', 'users.fname', 'users.lname')
    				->join('users', 'users.id', '=', 'follows.user_following')
    				->where('follows.user_followed', '=', $user_id)
    				->get();
    }

    public function getFollowing($user_id)
    {
    	return DB::table('follows')
    				->select('follows.user_followed AS user_id', 'users.fname', 'users.lname')
    				->join('users', 'users.id', '=', 'follows.user_followed')
    				->where('follows.user_following', '=', $user_id)
    				->get();
    }

}
