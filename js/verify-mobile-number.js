//when logout button is clicked
$('#logout-btn').on('click', function(){
	$.ajax({
		url: 'includes/logout',
		success: function(data){
			if(data == 'LOGOUT'){
				window.location.href = 'index';
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//prevent non-numeric inputs in the field
$('#otp-field').keypress(function(e){
	if(e.keyCode < 48 || e.keyCode > 57){
		e.preventDefault();
	}
});

//remove the error in otp field
$('#otp-field').on('input', function(){
	if($('#otp-field').hasClass('is-invalid')){
		$('#otp-field').removeClass('is-invalid');
	}
});

//send otp
var timer;
$('#send-otp-btn').on('click', function(){
	var counter = 120;
	$.ajax({
		url: 'includes/generate-otp',
		method: 'POST',
		data: { mobile_number: $('#mobile-number').html() },
		beforeSend: function(){
			$('#spinner').show();
			$('#send-otp-btn').attr('disabled', true);
		},
		success: function(data){
			if(data == 'SUCCESS_OTP'){
				$('#spinner').hide();
				$('#send-otp-btn').attr('disabled', true);
				$('#alert-success-otp').fadeIn('slow', function(){
					setTimeout(function(){
						$('#alert-success-otp').fadeOut('slow');
					}, 1500);
				});
				timer = setInterval(function(){
					if(counter == 0){
						$('#send-otp-btn').html('Send OTP <div class="spinner-border spinner-border-sm text-light" id="spinner" role="status" style="display: none;"><span class="sr-only">Loading...</span></div>');
						$('#send-otp-btn').attr('disabled', false);
						$('#spinner').hide();
						clearInterval(timer);
					}
					else{
						$('#send-otp-btn').html(counter + 's');
						--counter;
					}
				}, 1000);
			}
			else{
				$('#spinner').hide();
				$('#send-otp-btn').attr('disabled', false);
				alert('Oops, an error occurred.');
				console.error(data);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//verify otp
$('#verify-number-btn').on('click', function(){
	let otp = $('#otp-field').val().trim();

	if(otp == ''){
		$('#error-otp-field').html('Field cannot be empty');
		$('#otp-field').addClass('is-invalid');
	}
	else if(otp != ''){
		if(otp.length != 6 || otp.length > 6){
			$('#error-otp-field').html('OTP should be 6 digits');
			$('#otp-field').addClass('is-invalid');
		}
		else{
			$.ajax({
				url: 'includes/verify-otp',
				method: 'POST',
				data: { otp: otp },
				beforeSend: function(){
					$('#verify-number-btn').attr('disabled', true);
					$('#send-otp-btn').attr('disabled', true);
					$('#spinner-verify').show();
				},
				success: function(data){
					$('#spinner-verify').hide();
					if(data == 'OTP_VERIFIED'){
						$('#modal-success-verify').modal('show');
						setTimeout(function(){
							$('#modal-success-verify').modal('hide');
							window.location.href = 'dashboard';
						}, 1500);
					}
					else if(data == 'OTP_FAILED'){
						$('#verify-number-btn').attr('disabled', false);
						if(timer)
							$('#send-otp-btn').attr('disabled', true);
						else
							$('#send-otp-btn').attr('disabled', false);
						
						$('#error-otp-field').html('OTP is invalid or expired');
						$('#otp-field').addClass('is-invalid');
					}
					else{
						$('#verify-number-btn').attr('disabled', false);
						$('#send-otp-btn').attr('disabled', false);
						$('#send-otp-btn').html('Send OTP <div class="spinner-border spinner-border-sm text-light" id="spinner" role="status" style="display: none;"><span class="sr-only">Loading...</span></div>');	
						alert('Oops, an error occurred.');
						console.error(data);
					}
				},
				error: function(xhr){
					alert('Oops, an error occurred.');
		        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
		    	}
			});
		}
	}
});