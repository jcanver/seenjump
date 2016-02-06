<!DOCTYPE html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="/images/popcon.png" type="image/png" sizes="32x32">

		<link href='http://fonts.googleapis.com/css?family=Raleway:500,400,300,200' rel='stylesheet' type='text/css'>
		<link href='/css/font-awesome-4.3.0 2/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
		<link href='/css/animate.min.css' type='text/css' rel='stylesheet'>
		<link href='/css/seenjump.css' type='text/css' rel='stylesheet'>
	</head>
    <body role="document">
		<div id="loading">
		    <div id="loader"></div>
		</div>

		<div id="header" class="login_header">
			@if ( $context == 'login' )
				<a href="/about">about</a>
				<a href="/signup">sign up</a>
			@elseif ( $context == 'signup' )
				<a href="/about">about</a>
				<a href="/login">log in</a>
			@elseif ( $context == 'about' )
				<a href="/signup">sign up</a>
				<a href="/login">log in</a>
			@endif
		</div>
		
        <div id="main">
        	<div id="loggedout_container">
        		@yield('content')
        	</div>
        </div>
    </body>
</html>
<script src='/js/jquery-2.1.4.min.js'></script>
<script src='/js/seenjump.js'></script>