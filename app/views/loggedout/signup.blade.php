@extends('templates.logged-out-master')

@section('content')
<form id="registration_form" action="/signup" class="loggedout_form" method="POST" enctype="multipart/form-data">
	<div id="error_box"></div>
	<div>
		<input type="text" name="fname" placeholder="first name"

		@if (Session::has('fname'))
		value="{{{ Session::get('fname') }}}"
		@endif

		required>
	</div>
	<div>
		<input type="text" name="lname" placeholder="last name" 
		
		@if (Session::has('lname'))
		value="{{{ Session::get('lname') }}}"
		@endif

		required>
	</div>
	<div>
		<input type="email" name="email" placeholder="email" 
		
		@if (Session::has('email'))
		value="{{{ Session::get('email') }}}"
		@endif

		required>
	</div>
	<div>
		<input type="email" name="email2" placeholder="re-enter email" 
		
		@if (Session::has('email2'))
		value="{{{ Session::get('email2') }}}"
		@endif

		required>
	</div>
	<div>
		<input type="password" name="password1" placeholder="password" required>
	</div>
	<div>
		<input type="password" name="password2" placeholder="re-enter password" required>
	</div>
	<div>
		<input id="dob" type="date" name="birth_date" placeholder="date of birth" 
		
		@if (Session::has('birth_date'))
		value="{{{ Session::get('birth_date') }}}"
		style="color:#990000;"
		@endif

		required>
	</div>
	<div>
		<input type="file" name="photo" id="photo_input"/>
		<label for="photo_input">
			<i class="fa fa-upload"></i>
			<span>upload a profile picture</span>
		</label>
		<span id="filename"></span>
	</div>
	<div>
		<input type="radio" name="gender" value="male" id="male" 
		@if (Session::has('gender') && Session::get('gender') == 'male')
		checked
		@endif
		required>
		<label for="male" class="gender_text
		@if (Session::has('gender') && Session::get('gender') == 'male')
		gender_active
		@endif
		">Male</label
		
		><input type="radio" name="gender" value="female" id="female" required
		@if (Session::has('gender') && Session::get('gender') == 'female')
		checked
		@endif

		><label for="female" class="gender_text
		@if (Session::has('gender') && Session::get('gender') == 'female')
		gender_active
		@endif
		">Female</label>
	</div>
	<div>
		<input id="register_submit" type="submit" name="submit" value="Submit">
	</div>
</form>
@if (Session::has('error_message'))
<div id="error_modal" style="display:block;">
@else
<div id="error_modal">
@endif
	<div id="error_text">
		@if (Session::has('error_message'))
		{{{ Session::get('error_message') }}}
		@endif
	</div>
</div>
@endsection