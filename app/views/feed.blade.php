@extends('templates.master')
@section('title')
Feed
@endsection

@section('content')

<div class="content">
	<div id="feed_content">
	
		@foreach ($posts as $post)
		<div class="comment">
			<div class="photo" style="background:url('/images/profiles/{{{ $post->user_id }}}.jpg') no-repeat center center;background-size:cover;"></div>
			<div class="comment_uname">
				<a href="/profile/{{{ $post->user_id }}}"><strong>{{{ strtoupper($post->fname) . ' ' . strtoupper($post->lname) }}}</strong></a>
				<span>posted a {{ $post->type }} on {{{ $post->post_date }}}  </span>
			</div>
			<div class="mobile_date">{{{ $post->post_date }}}</div>
			
			<div class="comment_media_picture" style="background:url('/images/posters/{{{ $post->media->ImageUrl }}}') no-repeat top center;background-size:cover;"></div>
			<div class="comment_info">
				<a class="comment_media_title" href="/media/{{{ $post->imdb_id }}}">
					{{{ $post->media->Title }}} <span class="number">{{{ $post->media->Year }}}</span>
				</a>

				<div class="comment_text">
					{{{ $post->comment }}}
				</div>

				@if ( sizeof($post->tags) > 0 )
				<div class="comment_tags">
					<div class="comment_tag_divider"></div>
					<div class="comment_num_tags">
						<span class="number">{{{ sizeof($post->tags) }}}</span>
						@if (sizeof($post->tags) == 1)
						tag
						@else
						tags
						@endif
						<ul class="tags_list">
							@foreach ($post->tags as $tag)
							<li>{{{ $tag->fname . ' ' . $tag->lname }}}</li>
							@endforeach
						</ul>
					</div>
				</div>
				@endif
			</div>

			<div class="comment_likes">
				<span class="comment_num_likes">
					{{-- <i class="fa fa-user"></i> --}}
					<span class="num_likes number">{{{ $post->num_likes }}}</span>

					@if ( sizeof($post->likes) > 0 )
					<ul class="likes_users">
						@foreach ($post->likes as $user_id => $like)
							<li style="position:relative;">
								{{{ $like }}}
							</li>
						@endforeach
					</ul>
					@endif
				</span>
				
				&nbsp;

				@if ( array_key_exists(Auth::id(), $post->likes) )
				<i class="fa fa-thumbs-up unlike_button" id="{{{ $post->id }}}" style="color:rgba(153,0,0,1);"></i>
				@else
				<i class="fa fa-thumbs-up like_button" id="{{{ $post->id }}}"></i>
				@endif
			</div>

			@if ($post->rating != '')
			<div class="comment_rating number">
				 <span>{{{ $post->rating }}}</span>
			</div>
			@endif
		</div>

		@endforeach
	</div>
</div>

@endsection