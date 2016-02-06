@extends('templates.master')
@section('title')
My Settings
@endsection

@section('content')
<div class="content">
	<div id="settings_content">
		<!-- Email Form -->
		<form id="email_form">
			<div class="settings_title">Change Email</div>

			<div>
				<input type="email" name="email" placeholder="email" required>
			</div>
			<div>
				<input type="email" name="email2" placeholder="re-enter email" required>
			</div>
			<div id="current_email">current: <span>{{{ $user_email }}}</span></div>	

			<input type="submit" value="submit">
		</form>

		<br><br>

		<!-- Password Form -->
		<form id="password_form">
			<div class="settings_title">Change Password</div>
			<div>
				<input type="password" name="old_password" placeholder="old password" required>
			</div>
			<div>
				<input type="password" name="new_password" placeholder="new password" required>
			</div>
			<div>
				<input type="password" name="new_password2" placeholder="re-enter new password" required>
			</div>
			
			<input type="submit" value="submit">
		</form>
	</div>
</div>
<div id="error_modal">
	<div id="error_text">
		
	</div>
</div>
@endsection