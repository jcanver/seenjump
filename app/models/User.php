<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function search($full_name)
    {
    	$first_name_first = DB::table('users')
    							->select('id', 'fname', 'lname')
    							->where(DB::raw('CONCAT(fname, " ", lname)'), 'LIKE', $full_name . '%')
    							->get();

    	$last_name_first = DB::table('users')
    							->select('id', 'fname', 'lname')
    							->where(DB::raw('CONCAT(lname, " ", fname)'), 'LIKE', $full_name . '%')
    							->get();

        $users = array_merge($first_name_first, $last_name_first);
        foreach ($users as $user) {
            if (file_exists('/home/forge/seenjump.com/public/images/profiles/' . $user->id . '.jpg')) {
                $user->profile_image = '/images/profiles/' . $user->id . '.jpg';
            } else {
                $user->profile_image = '/images/generic_user.png';
            }
        }

		return array_merge($first_name_first, $last_name_first);
    }

    public function getUserName($user_id)
    {
    	$user = DB::table('users')
    				->select('fname', 'lname')
    				->where('id', '=', $user_id)
    				->get();
    	$user = $user[0];

    	return $user->fname . ' ' . $user->lname;
    }

    public function storeNewEmail($user_id, $email)
    {
        $user = User::find($user_id);
        $user->email = $email;

        if ( !$user->save() ) {
            return FALSE;
        }

        return TRUE;
    }

    public function checkPassword($user_id, $password)
    {
        $user = User::find($user_id);

        return Hash::check($password, $user->password);
    }

    public function storeNewPassword($user_id, $password)
    {
        $user = User::find($user_id);
        $user->password = Hash::make($password);

        if ( !$user->save() ) {
            return FALSE;
        }

        return TRUE;
    }

}
