@extends('templates.master')
@section('title')
Media
@endsection

@section('content')

<div class="content">
	<div id="media_content">
		<div id="media_info">
			<img height="314" width="217" src="/images/posters/<?php echo $media->ImageUrl; ?>">
			<h1 class="mobile_media_title">
				{{{ $media->Title }}}
				<span class="number">{{{ $media->Year }}}</span>
			</h1>
			<div class="media_info">
				<h1 class="media_title">
					{{{ $media->Title }}} 
					<span class="number">{{{ $media->Year }}}</span>
				</h1>
				<div class="media_rating number">
					<span class="rating_logo"></span>
					{{{ $media->imdbRating }}}
					<br>

				</div>
				<br>
				<div class="media_description">
					<div>
						{{{ $media->Plot }}}
					</div>
					<br>
					<table id="media_table">
						<tbody>
							<tr>
								<td><strong>Director: </strong></td>
								<td>{{{ $media->Director }}}</td>
							</tr>
							<tr>
								<td><strong>Writers: </strong></td>
								<td>{{{ $media->Writer }}}</td>
							</tr>
							<tr>
								<td><strong>Stars:</strong></td>
								<td>{{{ $media->Actors }}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div id="queue_button_wrap">
				@if ( in_array($media->imdbID, $queue_items) )
				<i class="fa fa-check-square" title="Remove from Queue"></i>
				<span id="unqueue_button" data-imdb-id="{{{ $media->imdbID }}}">
					Remove from Queue
				</span>
				@else
				<i data-imdb-id="{{{ $media->imdbID }}}" class="fa fa-plus-square" title="Add to Queue"></i>
				<span id="queue_button" data-imdb-id="{{{ $media->imdbID }}}">
					Add to Queue
				</span>
				@endif
			</div>
			
			

			<div id="comment_form_button">
				<i class="fa fa-comment"></i>
				Post a View
			</div>
		</div>

		<form id="post_form" action="/postview" method="post">
			<div id="comment_wrap">
				<textarea name="comment" placeholder="Comment..."></textarea>
			</div>

			<div id="tag_wrap">
				<input name="tag" type="text" placeholder="Tag...">

				<div id="tag_search_results">
					<!--
						Filled dynamically (seenjump.js)
					-->
				</div>
			</div>

			<div id="tagged_people">
				<!--
					Filled dynamically (seenjump.js)
				-->
			</div>

			<div id="rating_wrap">
				Rating: 
				<select name="rating">
					<option selected disabled></option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
			</div>

			<input type="hidden" name="media_type" value="{{{ $media->Type }}}">
			<input type="hidden" name="imdb_id" value="{{{ $media->imdbID }}}">

			<input type="submit" value="Submit">
		</form>

		<div id="comments_menu">
			<div id="top_comments_button" class="comments_menu_button">most popular</div>
			<div id="most_recent_comments_button" class="comments_menu_button">most recent</div>
			<div id="followers_comments_button" class="comments_menu_button">my following</div>
		</div>
		<div id="comments">
			@if (sizeof($posts) > 0)
				@foreach($posts as $post)
					<div class="comment">
						<div class="photo_wrap">
							<div class="photo" style="background:url('/images/profiles/{{{ $post->user_id }}}.jpg') no-repeat center center;background-size:cover;"></div>
							<div class="comment_uname">
								<a href="/profile/{{{ $post->user_id }}}"><strong>{{{ strtoupper($post->name) }}}</strong></a>
								<span>on {{{ $post->post_date }}}  </span>
							</div>
							<div class="mobile_date">{{{ $post->post_date }}}</div>

							<div class="comment_text">
								{{{ $post->comment }}}
							</div>
							<div class="comment_likes">
								<span class="comment_num_likes">
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
						</div>
					</div>
				@endforeach
			@else
				<div class="no_media_content">
					<i class="fa fa-exclamation-circle"></i>

					No comments (yet)
				</div>
			@endif
			



			

		</div>
	</div>
	
</div>
<div id="error_modal">
	<div id="error_text">

	</div>
</div>
@endsection