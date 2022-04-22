var error_count_reset;
var error_count;

//accordion on mission vision
$(function(){
	$('#accordion-index').accordion({
		collapsible: false,
		heightStyle: 'content',
		icons: false
	});
});

//go to create user account when clicked
$('#create-user-acct').on('click', function(){
	window.location.href = 'createaccount';
});

//reset modal forgot password when closed
$('#modal-forgot-pass').on('hidden.bs.modal', function(){
	$('#forgot-pass-form')[0].reset();
	
	if($('#email-resetpass-field').hasClass('is-invalid')){
		$('#email-resetpass-field').removeClass('is-invalid');
		error_count_reset = 0;
	}
});

//show password toggle
$('#show-pass-toggle').on('click', function(){
	if(document.getElementById('show-pass-toggle').checked){
		document.getElementById('passuser-login-field').type = 'text';
	}
	else{
		document.getElementById('passuser-login-field').type = 'password';	
	}
});

//when fields are updated, remove invalid and error count
$('#emailuser-login-field').on('input', function(){
	if($('#emailuser-login-field').hasClass('is-invalid')){
		$('#emailuser-login-field').removeClass('is-invalid');
		error_count--;
	}
});
$('#passuser-login-field').on('input', function(){
	if($('#passuser-login-field').hasClass('is-invalid')){
		$('#passuser-login-field').removeClass('is-invalid');
		error_count--;
	}
});
$('#email-resetpass-field').on('input', function(){
	if($('#email-resetpass-field').hasClass('is-invalid')){
		$('#email-resetpass-field').removeClass('is-invalid');
		error_count_reset--;
	}
});

//when login button is clicked in user login
$('#user-login-form').on('submit', function(){
	let REGEX_EMAIL = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	let email_user = $('#emailuser-login-field').val().toLowerCase();
	let password_user = $('#passuser-login-field').val();
	error_count = 0;

	//validate email input
	if(email_user == ""){
		$('#user-email-error').html('Email field cannot be empty');
		$('#emailuser-login-field').addClass('is-invalid');
		error_count++;
	}
	else if(email_user != ""){
		if(!REGEX_EMAIL.test(email_user)){
			$('#user-email-error').html('Please enter a valid email');
			$('#emailuser-login-field').addClass('is-invalid');
			error_count++;		
		}
	}

	//validate password input
	if(password_user == ""){
		$('#user-pass-error').html('Password field cannot be empty');
		$('#passuser-login-field').addClass('is-invalid');
		error_count++;
	}

	//if no error was found
	//submit request using ajax
	if(error_count == 0){
		$.ajax({
			url: 'includes/user_login_function',
			method: 'POST',
			data: { email_user: email_user, password_user: password_user },
			beforeSend: function(){
				$('#loader').show();
				$('#user-login-btn').attr('disabled', true);
			},
			success: function(data){
				$('#loader').hide();
				if(data == 'NO_RECORD'){
					$('#user-login-btn').attr('disabled', false);
					$('#passuser-login-field').val("");
					$('#alert-error-login').show('fade');
					setTimeout(function(){
						$('#alert-error-login').hide('fade');
					}, 2000)
				}
				else if(data == 'SUCCESS_LOGIN'){
					window.location.href = 'dashboard';
				}
				else{
					alert('Oops, an error occurred.');
					console.error(data);
					$('#user-login-btn').attr('disabled', false);
				}
			},
			error: function(xhr){
				alert('Oops, an error occurred.');
	        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
	    	}
		});
	}
});

//when reset password button is clicked
$('#forgot-pass-form').on('submit', function(){
	let REGEX_EMAIL = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	let reset_email = $('#email-resetpass-field').val().trim();
	error_count_reset = 0

	if(reset_email == ""){
		$('#resetpass-email-error').html('Field cannot be empty');
		$('#email-resetpass-field').addClass('is-invalid');
		error_count_reset++;
	}
	else if (reset_email != ""){
		//check if email is existing
		$.ajax({
			url: 'includes/reset_password_process',
			method: 'POST',
			data: { checkEmail: reset_email },
			async: false,
			success: function(data){
				if(parseInt(data) === 0){
					$('#resetpass-email-error').html('Email does not match our records');
					$('#email-resetpass-field').addClass('is-invalid');
					error_count_reset++;
				}
				else if(parseInt(data) !== 1){
					alert('Oops, an error occurred.');
					console.error(data);
				}
			},
			error: function(xhr){
				alert('Oops, an error occurred.');
	        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
	    	}
		});

		if(!REGEX_EMAIL.test(reset_email)){
			$('#resetpass-email-error').html('Please enter a valid email');
			$('#email-resetpass-field').addClass('is-invalid');
			error_count_reset++;
		}
	}
	
	//if no error, send ajax request
	if(error_count_reset == 0){
		//check if email is existing
		$.ajax({
			url: 'includes/reset_password_process',
			method: 'POST',
			data: { resetEmail: reset_email },
			beforeSend: function(){
				$('#email-resetpass-field').attr('disabled', true);
				$('#reset-btn').attr('disabled', true);
				$('#loader-reset').show();
			},
			success: function(data){
				if(data == 'SUCCESS_REQUEST_RESET'){
					$('#email-resetpass-field').attr('disabled', false);
					$('#reset-btn').attr('disabled', false);
					$('#loader-reset').hide();
					$('#modal-forgot-pass').modal('hide');
					$('#modal-success-reset').modal('show');
				}
				else{
					alert('Oops, an error occurred.');
					console.error(data);
					$('#email-resetpass-field').attr('disabled', false);
					$('#reset-btn').attr('disabled', false);
					$('#loader-reset').hide();
				}
			},
			error: function(xhr){
				alert('Oops, an error occurred.');
	        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
	    	}
		});
	}
});
