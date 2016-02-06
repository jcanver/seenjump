@extends('templates.master')
@section('title')
Profile
@endsection

@section('content')

<div class="content">
	<div id="profile_content">
		<div>
			<div id="profile_picture" style="background:url('/images/profiles/{{{ $user['id'] }}}.jpg') no-repeat center center;background-size:cover;"></div>
			<div id="user_info">
				<div id="user_name">{{{ $user['fname'] . ' ' . $user['lname'] }}}</div>
				@if ( Auth::id() != $user['id'] )
					@if ( isset($is_following) && $is_following )
					<div id="followed_button"><i class="fa fa-check"></i> Following</div>
					<span id="unfollow" data-uid="{{{ $user['id'] }}}">Unfollow</span>
					@else
					<div id="follow_button" data-uid="{{{ $user['id'] }}}">Follow</div>
					<span id="unfollow" data-uid="{{{ $user['id'] }}}" style="display:none;">Unfollow</span>
					@endif
				@endif
			</div>
			<div id="profile_tabs">
				<span class="profile_tab profile_tab_active">
					<span class="content_grab">COMMENTS</span>
				</span>
				<span class="profile_tab">
					<span class="content_grab">RATINGS</span>
				</span>
				<span class="profile_tab">
					<span class="content_grab">FOLLOWERS</span>
					<span class="number"> ({{{ $user['num_followers'] }}})</span>
				</span>
				<span class="profile_tab">
					<span class="content_grab">FOLLOWING</span>
					<span class="number"> ({{{ $user['num_following'] }}})</span>
				</span>
			</div>
			
			<i id="profile_burger" class="fa fa-bars"></i>
			<div id="mobile_nav">
				<div class="profile_tab profile_tab_active">
					<span class="content_grab">COMMENTS</span>
				</div>
				<div class="profile_tab">
					<span class="content_grab">RATINGS</span>
				</div>
				<div class="profile_tab">
					<span class="content_grab">FOLLOWERS</span>
					<span class="number"> ({{{ $user['num_followers'] }}})</span>
				</div>
				<div class="profile_tab">
					<span class="content_grab">FOLLOWING</span>
					<span class="number"> ({{{ $user['num_following'] }}})</span>
				</div>
			</div>
		</div>

		<div id="comments_content">
			@if ( sizeof($comments) > 0 )
				@foreach ($comments as $comment)
				<div class="comment">
					@if ( $comment->user_id == Auth::id() )
					<i class="fa fa-times comment_remove"></i>
					<div class="comment_remove_modal">
						<div>
							<div>are you sure you want to delete this post?</div>
							<div class="no_button">no, keep it</div>
							<div class="yes_button" data-comment-removal-id="{{{ $comment->id }}}">yes, delete it</div>
						</div>
					</div>
					@endif

						<div class="comment_media_picture" style="background:url('/images/posters/{{{ $comment->media->ImageUrl }}}');background-size:contain;"></div>
						<div class="comment_info">
							<a class="comment_media_title" href="/media/{{{ $comment->imdb_id }}}">
								{{{ $comment->media->Title }}} <span class="number">{{{ $comment->media->Year }}}</span>
							</a>

							<div class="comment_text">
								{{{ $comment->comment }}}
							</div>

							@if ( sizeof($comment->tags) > 0 )
							<div class="comment_tags">
								<div class="comment_tag_divider"></div>
								<div class="comment_num_tags">
									<span class="number">{{{ sizeof($comment->tags) }}}</span>
									@if (sizeof($comment->tags) == 1)
									tag
									@else
									tags
									@endif
									<ul class="tags_list">
										@foreach ($comment->tags as $tag)
										<li>{{{ $tag->fname . ' ' . $tag->lname }}}</li>
										@endforeach
									</ul>
								</div>
							</div>
							@endif
						</div>

					<div class="comment_likes">
						<span class="comment_num_likes">
							<span class="num_likes number">{{{ $comment->num_likes }}}</span>

							@if ( sizeof($comment->likes) > 0 )
							<ul class="likes_users">
								@foreach ($comment->likes as $user_id => $like)
									<li style="position:relative;">
										{{{ $like }}}
									</li>
								@endforeach
							</ul>
							@endif
						</span>
						
						&nbsp;

						@if ( array_key_exists(Auth::id(), $comment->likes) )
						<i class="fa fa-thumbs-up unlike_button" id="{{{ $comment->id }}}" style="color:rgba(153,0,0,1);"></i>
						@else
						<i class="fa fa-thumbs-up like_button" id="{{{ $comment->id }}}"></i>
						@endif
					</div>

					@if ( $comment->rating != '' )
					<div class="comment_rating number">
						 <span>{{{ $comment->rating }}}</span>
					</div>
					@endif
				</div>
				@endforeach
			@else
				<div class="missing_user_content">
					<i class="fa fa-exclamation-circle"></i>
					{{{ $user->fname }}} has not posted any comments yet
				</div>
			@endif
		</div>

		<div id="ratings_content">
			@if (sizeof($ratings) > 0)
				@foreach ($ratings as $comment)
				<div class="comment">

						<div class="comment_media_picture" style="background:url('/images/posters/{{{ $comment->media->ImageUrl }}}');background-size:contain;"></div>
						<div class="comment_info">
							<a class="comment_media_title" href="/media/{{{ $comment->imdb_id }}}">
								{{{ $comment->media->Title }}} <span class="number">{{{ $comment->media->Year }}}</span>
							</a>

							<div class="comment_text">
								{{{ $comment->comment }}}
							</div>

							@if ( sizeof($comment->tags) > 0 )
							<div class="comment_tags">
								<div class="comment_tag_divider"></div>
								<div class="comment_num_tags">
									<span class="number">{{{ sizeof($comment->tags) }}}</span>
									@if (sizeof($comment->tags) == 1)
									tag
									@else
									tags
									@endif
									<ul class="tags_list">
										@foreach ($comment->tags as $tag)
										<li>{{{ $tag->fname . ' ' . $tag->lname }}}</li>
										@endforeach
									</ul>
								</div>
							</div>
							@endif
						</div>

					<div class="comment_likes">
						<span class="comment_num_likes">
							<i class="fa fa-user"></i> <span class="num_likes number">{{{ $comment->num_likes }}}</span>

							@if ( sizeof($comment->likes) > 0 )
							<ul class="likes_users">
								@foreach ($comment->likes as $user_id => $like)
									<li style="position:relative;">
										{{{ $like }}}
									</li>
								@endforeach
							</ul>
							@endif
						</span>
						
						&nbsp;

						@if ( array_key_exists(Auth::id(), $comment->likes) )
						<i class="fa fa-thumbs-up unlike_button" id="{{{ $comment->id }}}" style="color:rgba(153,0,0,1);"></i>
						@else
						<i class="fa fa-thumbs-up like_button" id="{{{ $comment->id }}}"></i>
						@endif
					</div>

					<div class="comment_rating">
						 <span>{{{ $comment->rating }}}</span>
					</div>
				</div>
				@endforeach
			@else
				<div class="missing_user_content">
					<i class="fa fa-exclamation-circle"></i>
					{{{ $user->fname }}} has not posted any ratings yet
				</div>
			@endif
		</div>

		<div id="followers_content">
			@if (sizeof($followers) > 0)
				@foreach ($followers as $follower)
				
				<div class="follow_wrap">
					<div class="follow_picture" style="background:url('/images/profiles/{{{ $follower->user_id }}}.jpg') no-repeat center center;background-size:cover;"></div>
					<a href="/profile/{{{ $follower->user_id }}}" class="follow_name">{{{ $follower->fname . ' ' . $follower->lname }}}</a>
				</div>

				@endforeach
			@else
				<div class="missing_user_content">
					<i class="fa fa-exclamation-circle"></i>
					{{{ $user->fname }}} does not have any followers yet
				</div>
			@endif
		</div>

		<div id="following_content">
			@if (sizeof($following) > 0)
				@foreach ($following as $follow)
				
				<div class="follow_wrap">
					<div class="follow_picture" style="background:url('/images/profiles/{{{ $follow->user_id }}}.jpg') no-repeat center center;background-size:cover;"></div>
					<a href="/profile/{{{ $follow->user_id }}}" class="follow_name">{{{ $follow->fname . ' ' . $follow->lname }}}</a>
				</div>

				@endforeach
			@else
				<div class="missing_user_content">
					<i class="fa fa-exclamation-circle"></i>
					{{{ $user->fname }}} is not following anyone yet
				</div>
			@endif
		</div>


	</div>
</div>

@endsection