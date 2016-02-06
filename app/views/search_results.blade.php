@extends('templates.master')
@section('title')
Search Results
@endsection

@section('content')

<div class="content">
	<div id="results_content">
		<div id="results_crumb">results for: <span>{{{ $input_title }}}</span></div>
		<div class="search_results_title"><i class="fa fa-film"></i> media</div>
		@foreach ($results as $result) 

			<a class="search_result" href="/media/{{{ $result->imdbID }}}">
				<div class="poster" style="background:url('/images/posters/{{{ $result->ImageUrl }}}') bottom center no-repeat;background-size:cover;"></div>
				<div class="float-left result_text">
					<div class="title_info">
						<h4>
							{{{ $result->Title }}}
						</h4>
						<div>
							<span class="result_year">
								{{{ $result->Year }}}
							</span>
							
							<div class="search_logo"></div>
							<span class="search_imdb_rating">
								{{{ $result->imdbRating }}}
							</span>
						</div>
					</div>
					
					<div class="search_plot">
						{{{ $result->Plot }}}
					</div>
					<div>
						<strong>Directed By:</strong> {{{ $result->Director }}}
					</div>
					<div>
						<strong>Written By:</strong> {{{ $result->Writer }}}
					</div>
					<div>
						<strong>Starring: </strong>{{{ $result->Actors }}}
					</div>
				</div>
			</a>
		
		@endforeach

		<br><br>
		<div class="search_results_title"><i class="fa fa-user"></i> jumpers</div>
		<div class="user_results_wrapper">
			@foreach ($users as $user)
			<div class="follow_wrap">
				<div class="follow_picture" style="background:url('{{{ $user->profile_image }}}') no-repeat center center;background-size:cover;"></div>
				<a href="/profile/{{{ $user->id }}}" class="follow_name">{{{ $user->fname . ' ' . $user->lname }}}</a>
			</div>	
			@endforeach
			<br>
		</div>
		

	</div>
</div>

@endsection