@extends('templates.master')
@section('title')
{{{ $title }}}
@endsection

@section('content')

<div class="content">
	<div id="follows_page_content">
		<h2>{{{ $title }}}</h2>
		@if ( sizeof($follows) > 0 )
			@foreach ($follows as $follow)
			
			<div class="follow_wrap">
				<div class="follow_picture" style="background:url('/images/profiles/{{{ $follow->user_id }}}.jpg') no-repeat center center;background-size:cover;"></div>
				<a href="/profile/{{{ $follow->user_id }}}" class="follow_name">
					{{{ $follow->fname . ' ' . $follow->lname }}}
				</a>
			</div>

			@endforeach
		@else
			<div class="missing_user_content">
				<i class="fa fa-exclamation-circle"></i>
				@if ($title == 'followers')
					{{{ $user->fname }}} does not have any followers yet
				@else
					{{{ $user->fname }}} is not following anyone yet
				@endif
			</div>
		@endif
		
	</div>
</div>

@endsection