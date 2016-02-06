<!DOCTYPE html>
	<head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> @yield('title') - Seenjump</title>
        <link rel="icon" href="/images/popcon.png" type="image/png" sizes="32x32">

		<link href='http://fonts.googleapis.com/css?family=Raleway:500,400,300,200' rel='stylesheet' type='text/css'>
        <link href='/css/font-awesome-4.3.0 2/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <link href='/css/seenjump.css' type='text/css' rel='stylesheet'>

       
	</head>
    <body role="document">
        <div id="loading">
            <div id="loader"></div>
        </div>

    	<div id="header" class="animated">
    		<a href="/feed" id="logo"></a>
            <i id="burger" class="fa fa-bars"></i>

    		<form action="/search" id="search" method="post">
    			<input type="text" name="title" placeholder="search seenjump">
    		</form>
    		<div id="header_search_results" class="animated slideInDown" style="">
    			<div id="media_results">
                    <!-- Filled Dynamically --> 
                </div>
                <div id="users_results">
                    <!-- Filled Dynamically -->
                </div>
    		</div>
    		
    		<div id="greeting">
                <a href="/settings" id="settings"><i class="fa fa-cog fa-spin-hover"></i></a>
    			<span id="welcome">Welcome, {{{ Auth::user()->fname }}}</span>
                <span id="user_picture" style="background:url('/images/profiles/{{{ Auth::id() }}}.jpg') no-repeat center center;background-size:100%;"></span>
    			<br>
    			<a href="/login">Log Out</a>
    		</div>
    	</div>
    	

        <div id="main">
            <!-- Side Nav -->
            <ul id="side_nav" class="animated">
                <br>

                <form id="mobile_search" action="/search" method="post">
                    <input type="text" name="title" placeholder="search seenjump">
                    <br><br>
                </form>

                @if ( isset($side_nav_page) && $side_nav_page == 'feed' )
                <li id="side_nav_active">
                @else
                <li>
                @endif
                    <a href="/feed" class="nav_button">
                        <i class="fa fa-comments"></i>
                        News Feed
                    </a>
                </li>

                @if ( isset($side_nav_page) && $side_nav_page == 'myprofile' )
                <li id="side_nav_active">
                @else
                <li>
                @endif
                    <a href="/profile/{{{ Auth::id() }}}" class="nav_button">
                        <i class="fa fa-home"></i> 
                        My Profile
                    </a>
                </li>

                 @if ( isset($side_nav_page) && $side_nav_page == 'followers' )
                <li id="side_nav_active">
                @else
                <li>
                @endif
                    <a href="/followers" class="nav_button">
                        <i class="fa fa-users"></i>
                        Followers
                    </a>
                </li>

                 @if ( isset($side_nav_page) && $side_nav_page == 'following' )
                <li id="side_nav_active">
                @else
                <li>
                @endif
                    <a href="/following" class="nav_button">
                        <i class="fa fa-users"></i>
                        Following
                    </a>
                </li>

                @if ( isset($side_nav_page) && $side_nav_page == 'queue' )
                <li id="side_nav_active">
                @else
                <li>
                @endif
                    <a href="/queue" class="nav_button">
                        <i class="fa fa-list"></i>
                        Viewing Queue
                    </a>
                </li>
                

                <br>


                <li style="display:none;">
                    <a href="/spoilervision" class="nav_button">
                    <i class="fa fa-ban"></i>
                        SpoilerVision
                    </a>
                </li>
                <li style="display:none;">
                    <a href="/suggestions" class="nav_button">
                    <i class="fa fa-video-camera"></i>
                        SJ Suggestions
                    </a>
                </li>
            </ul>

            @yield('content')
        </div>
    </body>
</html>
<script src='/js/jquery-2.1.4.min.js'></script>
<script src='/js/seenjump.js'></script>