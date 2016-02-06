@extends('templates.master')
@section('title')
Queue
@endsection

@section('content')

<div class="content">
	<div id="queue_content">
		@if ( sizeof($queue_items) > 0 )
			@foreach ($queue_items as $media)
				<a class="search_result" href="/media/{{{ $media->imdbID }}}">
					<div class="poster" style="background:url('/images/posters/{{{ $media->ImageUrl }}}') bottom center no-repeat;background-size:cover;"></div>
					<div class="float-left result_text">
						<div class="title_info">
							<h4>
								{{{ $media->Title }}}
							</h4>
							<div>
								<span class="number">
									{{{ $media->Year }}}
								</span>
								
								<div class="media_ratings">
									<div class="search_imdb_logo"></div>
									<span class="search_imdb_rating number">
										{{{ $media->imdbRating }}}
									</span>
									<br>
									@if ($media->tomatoMeter != 'N/A')
									<div class="search_tomato_logo" style="background:url('/images/fresh.png') no-repeat center center;background-size:100%"></div>
									<span class="search_imdb_rating number">
										{{{ $media->tomatoMeter }}}
									</span>
									@endif
								</div>
								
							</div>
						</div>
						
						<div class="search_plot">
							{{{ $media->Plot }}}
						</div>
						<div>
							<strong>Directed By:</strong> {{{ $media->Director }}}
						</div>
						<div>
							<strong>Written By:</strong> {{{ $media->Writer }}}
						</div>
						<div>
							<strong>Starring: </strong>{{{ $media->Actors }}}
						</div>
					</div>
				</a>
			@endforeach
		@else
			<div class="missing_user_content">
				<i class="fa fa-exclamation-circle"></i>
				you do not have any movies or shows in your queue.
				<br>
				visit their pages in order to get started adding them!
			</div>
		@endif		
	</div>
</div>

@endsection