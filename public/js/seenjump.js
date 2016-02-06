(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 200; // 0.2s second default timeout
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                // Chrome Fix (Use keyup over keypress to detect backspace)
                // thank you @palerdot
                $el.is(':input') && $el.on('keyup keypress',function(e){
                    // This catches the backspace button in chrome, but also prevents
                    // the event from triggering too premptively. Without this line,
                    // using tab/shift+tab will make the focused element fire the callback.
                    if (e.type=='keyup' && e.keyCode!=8) return;
                    
                    // Check if timeout has been set. If it has, "reset" the clock and
                    // start over again.
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        // if we made it here, our timeout has elapsed. Fire the
                        // callback
                        doneTyping(el);
                    }, timeout);
                }).on('blur',function(){
                    // If we can, fire the event since we're leaving the field
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);

$(document).ready(function() {
	// Loading GIF
	$(window).load(function() {
		$('#loading').fadeOut(function() {
			$('#main, #header').fadeIn();
		});
	});

	// Click Burger
	$('body').on('click', '#burger', function() {
		var sideNav = $('#side_nav');
		var newClass;

		if (sideNav.css('left') == '0px') {
			newClass = 'hide_nav';
		} else if (sideNav.css('left') == '-216px') {
			newClass = 'show_nav';
		}

		sideNav.removeClass().addClass(newClass);
	});

	// Link Loading GIF before page change
	$('body').on('click', 'a', function() {
		$('#loading').fadeIn();
	});

	// Listen for date change (sign up page)
	$('#dob').change(function() {
		var date = document.getElementById("dob").value;
		if (date == "") {
			$('#dob').css('color', '#990000');
		} else {
			$('#dob').css('color', '#990000');
		}
	});

	// AJAX login form validation
	$('#login_form').on('submit', function(e){
        e.preventDefault();
        $('#loading').fadeIn();

        // Collect inputs
		var email    = $('input[name=email]').val();
		var password = $('input[name=password]').val();

		$.ajax({
			method: 'post',
			url: '/login',
			cache: false,
			dataType: 'json',
			data: {
				email: email,
				password: password
			},
			success: function(success) {
				if (success == 'true') {
					window.location = '/feed';
				} else {
					$('#error_modal').fadeIn();
					$('#loading').fadeOut();
				}
			}
		});
    });

    // AJAX email form validation (settings page)
	$('#email_form').on('submit', function(e){
        e.preventDefault();

        // Collect inputs
		var email  = $('input[name=email]').val();
		var email2 = $('input[name=email2]').val();

		if ( email != email2 ) {
			$('#error_text').text('your emails do not match');
			$('#error_modal').fadeIn();
		} else {
			$('#loading').fadeIn();
			$.ajax({
				method: 'post',
				url: '/storenewemail',
				cache: false,
				dataType: 'json',
				data: {
					email: email
				},
				success: function(success) {
					if (success) {
						$('#error_text').html('<i class="fa fa-check"></i> your email has been successfully changed');
						$('#error_modal').fadeIn();
						$('#loading').fadeOut();
						$('input[name=email]').val('');
						$('input[name=email2]').val('');
						$('#current_email span').text(email);
					}
				}
			});
		}
    });

    // AJAX password form validation (settings page)
	$('#password_form').on('submit', function(e){
        e.preventDefault();

        // Collect inputs
		var oldPassword  = $('input[name=old_password]').val();
		var newPassword  = $('input[name=new_password]').val();
		var newPassword2 = $('input[name=new_password2]').val();

		$.ajax({
			method: 'post',
			url: '/checkpassword',
			cache: false,
			dataType: 'json',
			data: {
				old_password: oldPassword
			},
			success: function(success) {
				if (success) {
					if ( newPassword == '' ) {
						$('#error_text').text('please enter a new password');
						$('#error_modal').fadeIn();
					} else if ( newPassword != newPassword2 ) {
						$('#error_text').text('your emails do not match');
						$('#error_modal').fadeIn();
					} else {
						$('#loading').fadeIn();
						
						$.ajax({
							method: 'post',
							url: '/storenewpassword',
							cache: false,
							dataType: 'json',
							data: {
								password: newPassword
							},
							success: function(success2) {
								if (success2) {
									$('#error_text').html('<i class="fa fa-check"></i> your password has been successfully changed');
									$('#error_modal').fadeIn();
									$('#loading').fadeOut();
									$('input[name=old_password]').val('');
									$('input[name=new_password]').val('');
									$('input[name=new_password2]').val('');
								}
							}
						});
					}
				} else {
					$('#error_text').text('your old password is not correct');
					$('#error_modal').fadeIn();
				}
			}
		});
    });

	// Closing error messages
	$('body').on('click', '#error_modal', function() {
		$('#error_modal').fadeOut();
	});

	// Bring up confirm delete comment modal
	$('body').on('click', '.comment_remove', function() {
		$(this).next('.comment_remove_modal').fadeIn();
	});

	$('body').on('click', '.no_button', function() {
		$(this).parents('.comment_remove_modal').fadeOut();
	});

	// Delete comment
	$('body').on('click', '.yes_button', function() {
		var commentId = $(this).data('comment-removal-id');
		
		$.ajax({
			method: 'post',
			url: '/deletecomment/' + commentId,
			cache: false,
			success: function(success) {
				if (success) {
					$('.comment_remove_modal').html('<div><div><i class="fa fa-check"></i> successfully removed</div></div>');
				}
			}
		});
	});

	// Clicking a gender (sign up page)
	$('.gender_text').click(function() {
		$('.gender_text').not(this).removeClass('gender_active');
		$(this).addClass('gender_active');
	});

	// Listen for profile picture upload (sign up page)
	$('#photo_input').change(function() {
		var filename = $(this).val().split('\\');
		filename = filename[filename.length-1];
		$('#filename').text(filename);
		$('label[for=photo_input]').css('color','#990000');
		$('label[for=photo_input] i').removeClass('fa-upload').addClass('fa-check-square');
	});

	// adding a media object to queue
	$('body').on('click', '#queue_button', function() {
		var imdbId = $(this).data('imdb-id');

		$.ajax({
			method: 'post',
			url: '/addtoqueue/' + imdbId,
			cache: false,
			success: function(success) {
				$('#queue_button_wrap i').removeClass('fa-plus-square').addClass('fa-check-square');
				$('#queue_button_wrap span').attr('id', 'unqueue_button').text('Remove from Queue');
			}
		});
	});
	$('body').on('click', '#unqueue_button', function() {
		var imdbId = $(this).data('imdb-id');

		$.ajax({
			method: 'post',
			url: '/removefromqueue/' + imdbId,
			cache: false,
			success: function(success) {
				$('#queue_button_wrap i').removeClass('fa-check-square').addClass('fa-plus-square');
				$('#queue_button_wrap span').attr('id', 'queue_button').text('Add to Queue');
			}
		});
	});

	$('#comment_form_button').click(function() {
		$('#post_form').slideToggle();
	});

	$('body').on('click', '.comment_likes .like_button', function() {
		var elem = $(this);
		var postId = elem.attr('id');

		$.ajax({
			method: 'post',
			url: '/likePost/' + postId,
			cache: false,
			success: function(success) {
				var likesElem = elem.siblings('.comment_num_likes').children('.num_likes');

				var incremented = Number(likesElem.text()) + 1;
				likesElem.text(incremented);
				elem.css('color', 'rgba(153,0,0,1)').addClass('unlike_button').removeClass('like_button');
			}
		});
	});

	$('body').on('click', '.comment_likes .unlike_button', function() {
		var elem = $(this);
		var postId = elem.attr('id');

		$.ajax({
			method: 'post',
			url: '/unlikePost/' + postId,
			cache: false,
			success: function(success) {
				var likesElem = elem.siblings('.comment_num_likes').children('.num_likes');

				var decremented = Number(likesElem.text()) - 1;
				likesElem.text(decremented);
				elem.css('color', 'rgba(153,0,0,0.4)').addClass('like_button').removeClass('unlike_button');
			}
		});
	});


	
	$('body').on('click', '#follow_button', function() {
		var uid = $(this).data('uid');
		
		$.ajax({
			method: 'post',
			url: '/follow/' + uid,
			cache: false,
			success: function(success) {
				var numFollowers = $('#user_name > span').text();
				numFollowers = numFollowers.replace('(', '');
				numFollowers = numFollowers.replace(')', '');
				numFollowers = Number(numFollowers) + 1;
				$('#user_name > span').text('(' + numFollowers + ')');
				$('#follow_button').attr('id', 'followed_button');
				$('#followed_button').html('<i class="fa fa-check"></i> Following').attr('title', 'Unfollow');
				$('#unfollow').fadeIn();
			}
		});
	});

	$('body').on('click', '#unfollow', function() {
		var uid = $(this).data('uid');
		
		$.ajax({
			method: 'post',
			url: '/unfollow/' + uid,
			cache: false,
			success: function(success) {
				var numFollowers = $('#user_name > span').text();
				numFollowers = numFollowers.replace('(', '');
				numFollowers = numFollowers.replace(')', '');
				numFollowers = Number(numFollowers) - 1;
				$('#user_name > span').text('(' + numFollowers + ')');
				$('#followed_button').attr('id', 'follow_button');
				$('#follow_button').html('Follow').attr('title', '');
				$('#unfollow').fadeOut();
			}
		});
	});

	$('body').on('click', 'span.profile_tab:not(.profile_tab_active)', function() {
		var tab = $(this);
		var newTab = $(this).children('.content_grab').text().toLowerCase();
		var oldTab = $('span.profile_tab_active').children('.content_grab').text().toLowerCase();

		$('span.profile_tab_active').removeClass('profile_tab_active');
		tab.addClass('profile_tab_active');

		$('#' + oldTab + '_content').fadeOut(function() {
			$('#' + newTab + '_content').fadeIn();
		});
	});

	$('body').on('click', 'div.profile_tab:not(.profile_tab_active)', function() {
		var tab = $(this);
		var newTab = $(this).children('.content_grab').text().toLowerCase();
		var oldTab = $('div.profile_tab_active').children('.content_grab').text().toLowerCase();

		$('div.profile_tab_active').removeClass('profile_tab_active');
		tab.addClass('profile_tab_active');

		

		$('#mobile_nav').slideUp(200);
		$('#' + oldTab + '_content').fadeOut(function() {
			$('#' + newTab + '_content').fadeIn();
		});
	});

	$('body').on('click', '#profile_burger', function() {
		$('#mobile_nav').slideToggle(200);
	});
	
	

	$('input[name=tag]').donetyping(function() {
		var search = $(this).val();

		if ( search.length > 1 ) {
			$.ajax({
				method: 'post',
				url: '/usersearch',
				cache: false,
				data: {
					search: search
				},
				success: function(matches) {
					matches = JSON.parse(matches);
					console.log(matches);
					if ( matches.length > 0 ) {
						$('#tag_search_results').empty();
						$.each(matches, function(key, match) {
							var appendString = ''+
								'<div class="tag_option" data-fname="'+ match['fname'] +'" data-lname="'+ match['lname'] +'" data-uid="'+ match['id'] +'">'+
									'<div>'+
										'<div class="tag_img" style="background:url(\'' + match['profile_image'] + '\') no-repeat center center; background-size:cover;"></div>'+
										'<div class="tag_info">'+
											'<div class="tag_name">'+ match['fname'] + ' ' + match['lname'] +'</span></div>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'';
							console.log(appendString);
							$('#tag_search_results').append(appendString);
						});
						$('#tag_search_results').fadeIn();
					} else {
						$('#tag_search_results').empty();
					}
				}
			});
		} else {
			$('#tag_search_results').fadeOut(function() {
				$('#tag_search_results').empty();
			});
		}
	});

	// tagging a user
	$('body').on('click', '.tag_option', function() {
		$('#tag_search_results').fadeOut(function() {
			$('#tag_search_results').empty();
		});
		
		$('input[name=tag]').val('');

		var uid   = $(this).data('uid');
		var fname = $(this).data('fname');
		var lname = $(this).data('lname');

		var appendString = ''+
			'<div class="tagged_person">'+
				'<div class="tagged_img" style="background:url(\'/images/profiles/'+ uid +'.jpg\') no-repeat center center;background-size:cover;"></div>'+
				'<span>'+ fname + ' ' + lname +'</span>'+
				'&nbsp;&nbsp;'+
				'<i class="fa fa-times"></i>'+
				'<input type="hidden" name="tagged_uid[]" value="'+ uid +'">'+
			'</div>'+
		'';

		$('#tagged_people').append(appendString);
	});

	// deleting tagged user
	$('body').on('click', '.tagged_person .fa-times', function() {
		$(this).parents('.tagged_person').remove();
	});

	/*
	// submitting a post view form
	$('#post_form input[type=submit]').click(function(e) {
		e.preventDefault();

		var comment = $('textarea[name=comment]').val();
		var rating  = $('select[name=rating]').val();
		var tagElements = $('input[name^=tagged_uid]');

		var tagged_uids = [];
		$.each(tagElements, function() {
			tagged_uids.push( Number($(this).val()) );
		});

		$.ajax({
			method: 'post',
			url: '/AppController/postview',
			cache: false,
			data: {
				comment: comment,
				rating: rating,
				tagged_uids: tagged_uids
			},
			success: function(success) {
				if ( success ) {
					console.log('success');
				} else {
					console.log('error');
				}
				
			}
		});
	});
	*/


	// Searching to follow
	$('#friend_search').keyup(function() {
		var search = $('#friend_search').val();

		if ( search.length > 2 ) {
			$.ajax({
				method: 'post',
				url: '/usersearch',
				cache: false,
				data: {
					search: search
				},
				success: function(matches) {
					matches = JSON.parse(matches);
					if (matches.length == 0) {
						$('#results').empty();
					} else {
						$.each(matches, function(key, match) {
							$('#results').html('<p>'+ match.fname +' '+ match.lname +'</p><div class="follow" id="'+ match.id +'">Follow</div>');
						});
					}
					
				}
			});
		} else {
			$('#results').empty();
		}
	});

	$('#search input').donetyping(function() {
		var search = $(this).val();

		if ( search.length > 1 ) {
			$.ajax({
				method: 'GET',
				url: 'http://www.omdbapi.com/',
				cache: false,
				data: {
					apikey: '40364d9f',
					s: search
				},
				success: function(results) {
					$('#media_results').empty();
					// if (!results.hasOwnProperty('Response')) {
					if (results['Response'] == 'True') {
						results = results['Search'];

						if (results.length > 0) {
							$.each(results, function(key, result) {
								// get imdb info before poster
								$.ajax({
									method: 'GET',
									url: 'http://www.omdbapi.com/',
									cache: false,
									data: {
										apikey: '40364d9f',
										i: result['imdbID']
									},
									success: function(media) {
										if ( media['imdbVotes'].replace(/,/g, '') > 20000 ) {
											if ( document.getElementById('media_ajax_title') == null ) {
												$('#media_results').append('<div id="media_ajax_title" class="ajax_title"><i class="fa fa-film"></i> media</div>');
											}
											$.ajax({
												method: 'POST',
												url: '/getposter',
												cache: false,
												data: {
													image_url: result['Poster'],
													media_title: result['Title'],
													year: result['Year']
												},
												success: function() {
													var appendString = '' +
														'<a class="search_option" href="/media/'+ media['imdbID'] +'">' +
															'<div>' +
																'<img height="60" width="41" src="/images/posters/'+ media['Title'] +'-'+ media['Year'] +'.jpg">' +
																'<div class="search_media_info">' +
																	'<div class="search_media_title">'+ media['Title'] +' &nbsp;<span>'+ media['Year'] +'</span></div>' +
																	'<div class="search_media_director">'+ media['Director'] +'</div>' +
																	'<div class="search_media_stars">'+ media['Actors'] +'</div>' +
																'</div>' +
															'</div>' +
															'<div class="search_divider"></div>' +
														'</a>';
													$('#media_results').append(appendString);
												}
											});
										}
									}
								});			
							});
						}
					}			
				}
			});

			$('#users_results').empty();
			// Get searches for other seenjump users
			$.ajax({
				method: 'post',
				url: '/usersearch',
				cache: false,
				data: {
					search: search
				},
				success: function(matches) {
					matches = JSON.parse(matches);
					if (matches.length > 0) {
						// $('#user_results').empty();
						$('#users_results').append('<div class="ajax_title"><i class="fa fa-user"></i> jumpers</div>');
						$.each(matches, function(key, match) {
							var userAppendString =''+
								'<a href="/profile/'+ match['id'] +'" class="search_user_wrap">'+
									'<div class="search_user_picture" style="background:url(\''+ match['profile_image'] +'\') no-repeat center center;background-size:cover;"></div>'+
									'<span class="search_user_name">'+ match['fname'] + ' ' + match['lname'] +'</span>'+
								'</a>';
							$('#users_results').append(userAppendString);
						});
					}
				}
			});
			
		} else {
			$('#media_results, #users_results').empty();
		}
	});

});