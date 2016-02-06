@extends('templates.logged-out-master')

@section('content')
<img src="/images/logo.png" width="86" height="100" class="animated zoomIn">
<h1 id="MOTHER-FUCKING-SEENJUMP-IN-YO-FACE">seenjump</h1>
<div id="slogan">what are you watching?</div>

<form id="login_form" class="loggedout_form" action="/login" method="post">
	<div>
		<input type="text" name="email" placeholder="email" required>
	</div>
	<div>
		<input type="password" name="password" placeholder="password" required>
	</div>
	<div>
		<input id="login_submit" type="submit" value="submit">
	</div>
</form>

<div id="error_modal">
	<div id="error_text">
		email and/or password incorrect
	</div>
</div>

{{{ $account_created_message or '' }}}
@endsection