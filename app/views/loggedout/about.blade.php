@extends('templates.logged-out-master')

@section('content')
<div class="about_section">
	<div class="about_img_wrapper">
		<i class="fa fa-ticket"></i>
		<div class="about_text">
			Seenjump is your destionation for sharing and discovering
			new movies and television
		</div>
	</div>
</div
><br class="about_line_breaks"><div class="about_section">
	<div class="about_img_wrapper">
		{{-- <div id="about_popcorn"></div> --}}
		<i class="fa fa-users"></i>
		<div class="about_text">
			Connect with your friends to see what they are watching and
			recommending
		</div>
	</div>
</div
><br class="about_line_breaks"><div class="about_section">
	<div class="about_img_wrapper">
		<i class="fa fa-film"></i>
		<div class="about_text">
			Cultivate your movie queue to expand your viewing horizon
		</div>
	</div>
</div>
<br><br><br><br>
<i class="fa fa-exclamation-circle" style="font-size:1.5em;position:relative;left:-4px;top:2px;"></i> Seenjump is currently under construction, stay tuned! 
<i class="fa fa-exclamation-circle" style="font-size:1.5em;position:relative;left:4px;top:2px;"></i>
<br><br><br><br>
@endsection