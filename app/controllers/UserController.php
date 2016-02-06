<?php
ini_set('log_errors', true);
ini_set('error_log', '/tmp/errors.log');
class UserController extends \BaseController {

	function __construct() {
	    
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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$view_data['context'] = 'signup';
		return View::make('loggedout.signup', $view_data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$user_data = Input::get();
		unset($user_data['submit']);
		
		// Validation
		if ($user_data['email'] != $user_data['email2']) {
			Session::flash('fname', $user_data['fname']);
			Session::flash('lname', $user_data['lname']);
			Session::flash('email', $user_data['email']);
			Session::flash('email2', $user_data['email2']);
			Session::flash('birth_date', $user_data['birth_date']);
			Session::flash('gender', $user_data['gender']);
			if (isset($user_data['photo'])) {
				Session::flash('photo', $user_data['photo']);
			}
			return Redirect::to('/signup')->with('error_message', 'your emails do not match');
		}
		if ($user_data['password1'] != $user_data['password2']) {
			Session::flash('fname', $user_data['fname']);
			Session::flash('lname', $user_data['lname']);
			Session::flash('email', $user_data['email']);
			Session::flash('email2', $user_data['email2']);
			Session::flash('birth_date', $user_data['birth_date']);
			Session::flash('gender', $user_data['gender']);
			if (isset($user_data['photo'])) {
				Session::flash('photo', $user_data['photo']);
			}
			return Redirect::to('/signup')->with('error_message', 'your passwords to not match');
		}
		
		$user_data['password'] = $user_data['password1'];
		unset($user_data['password1']);
		unset($user_data['password2']);
		unset($user_data['email2']);

		$user = new User;
		$user->fname      = $user_data['fname'];
		$user->lname      = $user_data['lname'];
		$user->email      = $user_data['email'];
		$user->password   = Hash::make($user_data['password']);
		$user->birth_date = $user_data['birth_date'];
		$user->gender     = $user_data['gender'];
		$saved = $user->save();

		if ( Input::hasFile('photo') && Input::file('photo')->isValid() ) {
			$filepath = '/images/profiles/';
			$filename = $user->id . '.jpg';
		    Input::file('photo')->move(public_path() . $filepath, $filename);
		}
		

		if ( !$saved ) {
			App::abort(500, 'Error');
		}

		return Redirect::to('/login')->with('account_created_message', 'Account created. Welcome to Seenjump!');
	}

	public function storeNewEmail()
	{
		$user_id = Auth::id();
		$email  = Input::get('email');

		$user = new User;
		$update_success = $user->storeNewEmail($user_id, $email);

		echo json_encode($update_success);
	}

	public function checkPassword()
	{
		$user_id = Auth::id();
		$password = Input::get('old_password');

		$user = new User;
		$valid = $user->checkPassword($user_id, $password);

		echo json_encode($valid);
	}

	public function storeNewPassword()
	{
		$user_id = Auth::id();
		$password = Input::get('password');

		$user = new User;
		$success = $user->storeNewPassword($user_id, $password);

		echo json_encode($success);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($user_id)
	{
		$post   = new Post;
		$media  = new Media;
		$like   = new Like;
		$follow = new Follow;
		$tag    = new Tag;

		$comments = $post->getUserComments($user_id);
		foreach ($comments as $key => $comment) {
			$comments[$key]->media = $media->getMediaFromAPI($comment->imdb_id);
			$comments[$key]->num_likes = $like->getNumLikes($comment->id);
			$comments[$key]->tags = $tag->getPostTags($comment->id);
			$likes_help = $like->getLikes($comment->id);

			$likes = array();
			foreach ($likes_help as $k => $v) {
				$likes[$v->user_id] = $v->fname . ' ' . $v->lname;
			}
			
			$comments[$key]->likes = $likes;
		}

		$ratings = $post->getUserRatings($user_id);
		foreach ($ratings as $key => $rating) {
			$ratings[$key]->media = $media->getMediaFromAPI($rating->imdb_id);
			$ratings[$key]->num_likes = $like->getNumLikes($rating->id);
			$ratings[$key]->tags = $tag->getPostTags($rating->id);
			$likes_help = $like->getLikes($rating->id);

			$likes = array();
			foreach ($likes_help as $k => $v) {
				$likes[$v->user_id] = $v->fname . ' ' . $v->lname;
			}
			
			$ratings[$key]->likes = $likes;
		}

		$view_data['user'] = User::find($user_id);
		$view_data['user']['num_followers'] = $follow->getNumFollowers($user_id);

		$view_data['comments'] = $comments;
		$view_data['ratings'] = $ratings;
		$view_data['followers'] = $follow->getFollowers($user_id);
		$view_data['following'] = $follow->getFollowing($user_id);
		$view_data['user']['num_following'] = sizeof($view_data['following']);
		if ( Auth::id() == $user_id ) {
			$view_data['side_nav_page'] = 'myprofile';
		} else {
			$follow = new Follow;
			$view_data['is_following'] = $follow->isFollowing(Auth::id(), $user_id);
		}
		// var_dump($view_data['following']);exit;

		return View::make('profile', $view_data);
	}


	public function feed()
	{
		$user_id = Auth::id();
		// var_dump($user_id);

		$post   = new Post;
		$media  = new Media;
		$like   = new Like;
		$tag    = new Tag;

		$posts = $post->getFeed();
		foreach ($posts as $key => $comment) {
			$posts[$key]->media = $media->getMediaFromAPI($comment->imdb_id);
			$posts[$key]->num_likes = $like->getNumLikes($comment->id);
			$posts[$key]->tags = $tag->getPostTags($comment->id);
			$posts[$key]->type = '<strong>comment</strong> and a <strong>rating</strong>';
			// echo $posts[$key]->comment;
			// echo '<br>';
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
			// var_dump($likes);
			
			$posts[$key]->likes = $likes;

			$posts[$key]->post_date = date('M. d, Y', strtotime($posts[$key]->created_at));
		}

		$view_data['posts'] = $posts;
		$view_data['side_nav_page'] = 'feed';
		// var_dump($posts);exit;


		return View::make('feed', $view_data);
	}

	public function likePost($post_id)
	{
		$user_id = Auth::id();

		$like = new Like;
		$like->user_id = $user_id;
		$like->post_id = $post_id;
		$like->save();
	}

	public function unlikePost($post_id)
	{
		$user_id = Auth::id();
		$like = new Like;

		$like->unlikePost($user_id, $post_id);
	}

	public function follow($followed_user_id)
	{
		$user_id = Auth::id();

		$follow = new Follow;
		$follow->user_following = $user_id;
		$follow->user_followed  = $followed_user_id;
		$follow->save();
	}

	public function unfollow($followed_user_id)
	{
		$user_id = Auth::id();

		$follow = new Follow;
		$follow->unfollow($user_id, $followed_user_id);
	}

	public function showFollowers()
	{
		$user_id = Auth::id();

		$follow = new Follow;
		$view_data['follows'] = $follow->getFollowers($user_id);
		$view_data['title']   = 'Followers';
		$view_data['side_nav_page'] = 'followers';

		$user = User::find($user_id);
		$view_data['user'] = $user;

		return View::make('follows', $view_data);
	}

	public function showFollowing()
	{
		$user_id = Auth::id();

		$follow = new Follow;
		$view_data['follows'] = $follow->getFollowing($user_id);
		$view_data['title']   = 'Following';
		$view_data['side_nav_page'] = 'following';

		$user = User::find($user_id);
		$view_data['user'] = $user;

		return View::make('follows', $view_data);
	}

	public function showSettings()
	{
		$user_id = Auth::id();

		$user = User::find($user_id);

		$view_data['user_email'] = $user->email;
		return View::make('settings', $view_data);
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


	public function authenticate()
	{
		$email    = Input::get('email');
		$password = Input::get('password');

		if (Auth::attempt(array('email' => $email, 'password' => $password), true)) {
		    // return Redirect::intended('/feed');
		    echo json_encode('true');
		} else {
			// return Redirect::to('login')->with('error', 'Email and/or password incorrect');
			echo json_encode('false');
		}
	}

	public function search()
	{
		$full_name = Input::get('search');
		$user_model = new User;

		$matches = $user_model->search($full_name);

		echo json_encode($matches);
	}

}
