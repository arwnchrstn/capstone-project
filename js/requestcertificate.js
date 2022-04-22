var error_count;

$(document).ready(function(){
	$('#modal-notice').modal('show');
});

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
				console.log(data)
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

$('#select-indigency').on('change', function(){
	if(this.checked){
		$('#indigency-purpose').toggle('show');
	}
	else{
		$('#indigency-purpose').toggle('hide');
		$('#purpose-indigency-field').removeClass('is-invalid');
		$('#purpose-indigency-field').val('');
	}
});
$('#select-residency').on('change', function(){
	if(this.checked){
		$('#residency-purpose').toggle('show');
	}
	else{
		$('#residency-purpose').toggle('hide');
		$('#purpose-residency-field').removeClass('is-invalid');
		$('#purpose-residency-field').val('');
	}
});
$('#select-clearance').on('change', function(){
	if(this.checked){
		$('#clearance-purpose').toggle('show');
	}
	else{
		$('#clearance-purpose').toggle('hide');
		$('#purpose-clearance-field').removeClass('is-invalid');
		$('#purpose-clearance-field').val('');
	}
});

//remove error on fields
$('#purpose-indigency-field').on('input', function(){
	if($('#purpose-indigency-field').hasClass('is-invalid')){
		$('#purpose-indigency-field').removeClass('is-invalid');
		error_count--;
	}
})
$('#purpose-residency-field').on('input', function(){
	if($('#purpose-residency-field').hasClass('is-invalid')){
		$('#purpose-residency-field').removeClass('is-invalid');
		error_count--;
	}
})
$('#purpose-clearance-field').on('input', function(){
	if($('#purpose-clearance-field').hasClass('is-invalid')){
		$('#purpose-clearance-field').removeClass('is-invalid');
		error_count--;
	}
})

$('#request-form').submit(function(e){
	e.preventDefault();

	const REGEX_NAME = /^[A-Za-zñÑ\- ]+$/;
	let indigency = $('#select-indigency');
	let residency = $('#select-residency');
	let clearance = $('#select-clearance');
	let indigencyPurpose = $('#purpose-indigency-field');
	let residencyPurpose = $('#purpose-residency-field');
	let clearancePurpose = $('#purpose-clearance-field'); 
	error_count = 0;

	if(!indigency.is(':checked') && !residency.is(':checked') && !clearance.is(':checked')){
		alert('Please select at least one request');
		error_count++;
	}
	else{
		error_count = 0;
		
		if(indigency.is(':checked')){
			if(indigencyPurpose.val().trim() === ""){
				$('#purpose-indigency-error').text('Field cannot be empty');
				indigencyPurpose.addClass('is-invalid');
				error_count++;
			}
			else if(indigencyPurpose.val().trim() !== ""){
				if(!REGEX_NAME.test(indigencyPurpose.val().trim())){
					$('#purpose-indigency-error').text('Only letter and -  are allowed for this field');
					indigencyPurpose.addClass('is-invalid');
					error_count++;
				}
			}
		}

		if(residency.is(':checked')){
			if(residencyPurpose.val().trim() === ""){
				$('#purpose-residency-error').text('Field cannot be empty');
				residencyPurpose.addClass('is-invalid');
				error_count++;
			}
			else if(residencyPurpose.val().trim() !== ""){
				if(!REGEX_NAME.test(residencyPurpose.val().trim())){
					$('#purpose-residency-error').text('Only letter and -  are allowed for this field');
					residencyPurpose.addClass('is-invalid');
					error_count++;
				}
			}
		}

		if(clearance.is(':checked')){
			if(clearancePurpose.val().trim() === ""){
				$('#purpose-clearance-error').text('Field cannot be empty');
				clearancePurpose.addClass('is-invalid');
				error_count++;
			}
			else if(clearancePurpose.val().trim() !== ""){
				if(!REGEX_NAME.test(clearancePurpose.val().trim())){
					$('#purpose-clearance-error').text('Only letter and -  are allowed for this field');
					clearancePurpose.addClass('is-invalid');
					error_count++;
				}
			}
		}
	}

	if(error_count === 0){
		$.ajax({
			url: 'includes/add_new_request_function',
			method:'POST',
			data: $(this).serialize(),
			beforeSend: function(){
				$('#loader').show();
				$('#submit-reqs').attr('disabled', true);
				indigency.attr('disabled', true);
				residency.attr('disabled', true);
				clearance.attr('disabled', true);
				indigencyPurpose.attr('disabled', true);
				residencyPurpose.attr('disabled', true);
				clearancePurpose.attr('disabled', true);
			},
			success: function(data){
				if(data.includes('REQUEST_SUCCESS') && !data.includes('Oops, an error occurred.')){
					$('#modal-success-request').modal('show');
					$('#loader').hide();
					$('#submit-reqs').attr('disabled', false);
					indigency.attr('disabled', false);
					residency.attr('disabled', false);
					clearance.attr('disabled', false);
					indigency.prop('checked', false);
					residency.prop('checked', false);
					clearance.prop('checked', false);
					indigencyPurpose.attr('disabled', false);
					residencyPurpose.attr('disabled', false);
					clearancePurpose.attr('disabled', false);
					indigencyPurpose.val('');
					residencyPurpose.val('');
					clearancePurpose.val('');
					$('#indigency-purpose').hide();
					$('#residency-purpose').hide();
					$('#clearance-purpose').hide();
				}
				else{
					alert('Oops, an error occurred.');
					console.error(data);
					$('#submit-reqs').attr('disabled', false);
					indigency.attr('disabled', false);
					residency.attr('disabled', false);
					clearance.attr('disabled', false);
					indigencyPurpose.attr('disabled', false);
					residencyPurpose.attr('disabled', false);
					clearancePurpose.attr('disabled', false);
				}
			},
			error: function(xhr){
				alert('Oops, an error occurred.');
				console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
			}
		});
	}
});