<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Tag extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tags';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	// protected $hidden = array('password', 'remember_token');

	public function getPostTags($post_id)
	{
		return DB::table('tags')
					->select('users.fname', 'users.lname','tags.user_tagged AS user_id')
					->join('users', 'users.id', '=', 'tags.user_tagged')
					->where('tags.post_id', '=', $post_id)
					->get();
	}

}
