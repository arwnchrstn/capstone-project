var error_count;

//check the OS if user is using a computer
$(document).ready(function(){
	if(navigator.appVersion.indexOf('Android') != -1 || navigator.appVersion.indexOf('like Mac') != -1){
		alert('This site is unavailable on mobile phones, please try a different device, Thank you!');
		window.location.href = 'page_unavailable';
		window.close();
	}
});
//show password toggle
$('#show-pass-toggle').on('click', function(){
	if(document.getElementById('show-pass-toggle').checked){
		document.getElementById('admin-pass-field').type = 'text';
	}
	else{
		document.getElementById('admin-pass-field').type = 'password';	
	}
});

//when fields are updated, remove invalid and error count
$('#admin-user-field').on('input', function(){
	if($('#admin-user-field').hasClass('is-invalid')){
		$('#admin-user-field').removeClass('is-invalid');
		error_count--;
	}
});
$('#admin-pass-field').on('input', function(){
	if($('#admin-pass-field').hasClass('is-invalid')){
		$('#admin-pass-field').removeClass('is-invalid');
		error_count--;
	}
});

//when login button on admin is clicked
$('#admin-login-form').on('submit', function(event){
	event.preventDefault();
	let admin_user = $('#admin-user-field').val();
	let admin_password = $('#admin-pass-field').val();
	error_count = 0;

	//admin username validation
	if(admin_user == ""){
		$('#admin-user-error').html('Username field cannot be empty');
		$('#admin-user-field').addClass('is-invalid');
		error_count++;
	}

	if(admin_password == ""){
		$('#admin-pass-error').html('Password field cannot be empty');
		$('#admin-pass-field').addClass('is-invalid');
		error_count++;
	}

	//if error count is 0
	//submit request using ajax
	if(error_count == 0){
		$.ajax({
			url: 'includes/admin_login_function',
			method: 'POST',
			data: $('#admin-login-form').serialize(),
			beforeSend: function(){
				$('#loader').show();
				$('#admin-login-btn').attr('disabled', true);
			},
			success: function(data){
				if(data == 'SUCCESS_ADMIN_LOGIN'){
					$('#loader').hide();
					window.location.href = 'admindashboard';
				}
				else if(data == 'ERROR_ADMIN_LOGIN'){
					$('#loader').hide();
					$('#admin-pass-field').val('');
					$('#admin-login-btn').attr('disabled', false);
					$('#alert-error-login').show('fade');
					setTimeout(function(){
						$('#alert-error-login').hide('fade');
					}, 2000);
				}
				else{
					alert('Oops, an error occurred.');
					console.error(data);
					$('#loader').hide();
					$('#admin-pass-field').val('');
					$('#admin-login-btn').attr('disabled', false);
				}
			},
			error: function(xhr){
				alert('Oops, an error occurred.');
	        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
	    	}
		});
	}
});